<?php

namespace App\Http\Controllers;

use App\Actions\Microsites\DeleteAction;
use App\Constants\DocumentTypes;
use App\Constants\PolicyName;
use App\Models\Microsites;
use App\Models\Category;
use App\Http\Requests\StoremicrositesRequest;
use App\Http\Requests\UpdatemicrositesRequest;
use Illuminate\Http\RedirectResponse;
use App\Actions\Microsites\StoreAction;
use App\Actions\Microsites\UpdateAction;
use App\Constants\Currency;
use App\Constants\MicrositesTypes;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class MicrositesController extends Controller
{
    public function index(): View
    {
        $this->authorize(PolicyName::VIEW_ANY, Microsites::class);

        $user = User::find(Auth::user()->id);

        if ($user->hasRole('Admin')) {
            $microsites = Microsites::all();
        } else {
            $microsites = Microsites::where('user_id', $user->id)->get();
        }

        return view('microsites.index', compact('microsites'));
    }

    public function showAll(): \Inertia\Response
    {
        $microsites = Microsites::with('category')->get();
        return Inertia::render('Microsites/Index', compact('microsites'));
    }

    public function create(): View
    {
        $this->authorize(PolicyName::CREATE, Microsites::class);
        $categories = Category::query()->select('id', 'name')->get();
        $documentTypes = DocumentTypes::cases();
        $currencies = Currency::cases();
        $micrositesTypes = MicrositesTypes::cases();
        return view('microsites.create', compact('categories', 'documentTypes', 'currencies', 'micrositesTypes'));
    }

    public function store(StoremicrositesRequest $request, StoreAction $storeAction): RedirectResponse
    {
        $this->authorize(PolicyName::CREATE, Microsites::class);
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $storeAction->execute($data);
        return redirect()->route('microsites.index')->with('success', 'Sitio creado correctamente.');
    }

    public function show(microsites $microsite): View | RedirectResponse
    {

        $this->authorize(PolicyName::VIEW, $microsite);
        $user = Auth::user();
        if ($microsite->user_id !== $user->id) {
            return redirect()->route('microsites.index')->with('error', 'No tienes permisos para ver este sitio.');
        }
        return view('microsites.show', compact('microsite'));
    }

    public function showMicrosite($slug, $id): \Inertia\Response
    {
        $microsite = Microsites::with('category')->findOrFail($id);
        return Inertia::render('Microsites/Show', [
            'microsite' => $microsite,
        ]);
    }

    public function edit(microsites $microsite, Category $category): View
    {

        $this->authorize(PolicyName::UPDATE, $microsite);
        $categories = Category::query()->select('id', 'name')->get();
        $documentTypes = DocumentTypes::cases();

        return view('microsites.edit', compact('microsite', 'categories', 'documentTypes'));
    }

    public function update(UpdatemicrositesRequest $request, microsites $microsite, UpdateAction $updateAction): RedirectResponse
    {
        $this->authorize(PolicyName::UPDATE, $microsite);
        $data = $request->validated();
        $data['id'] = $microsite->id;
        $updateAction->execute($data);

        return redirect()->route('microsites.index')->with('success', 'Sitio actualizado correctamente.');
    }

    public function destroy(microsites $microsite, DeleteAction $deleteAction): RedirectResponse
    {
        $micrositse = Microsites::find($microsite->id);
        $this->authorize(PolicyName::DELETE, $microsite);
        $deleteAction->execute($micrositse);
        return redirect()->route('microsites.index');
    }
}

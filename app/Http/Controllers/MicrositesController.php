<?php

namespace App\Http\Controllers;

use App\Actions\Microsites\StoreAction;
use App\Actions\Microsites\UpdateAction;
use App\Constants\Currency;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Constants\PolicyName;
use App\Http\Requests\StoremicrositesRequest;
use App\Http\Requests\UpdatemicrositesRequest;
use App\Models\Category;
use App\Models\Microsites;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MicrositesController extends Controller
{
    public function index(): \Inertia\Response
    {
        $this->authorize(PolicyName::VIEW_ANY, Microsites::class);
        $user = Auth::user();
        if ($user->hasRole('Admin')) {
            $microsites = Microsites::orderBy('created_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

        } else {
            $microsites = Microsites::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return Inertia::render('Microsites/AdminPanel', [
            'microsites' => $microsites,
        ]);
    }

    public function showAll(): \Inertia\Response
    {
        $microsites = Microsites::with('category')
            ->paginate(30);

        return Inertia::render('Microsites/Index', compact('microsites'));
    }

    public function create()
    {
        $this->authorize(PolicyName::CREATE, Microsites::class);
        $categories = Category::query()->select('id', 'name')->get();
        $documentTypes = DocumentTypes::toArray();
        $currencies = Currency::toArray();
        $micrositesTypes = MicrositesTypes::toArray();

        return Inertia::render('Microsites/MicrositeCreate', [
            'categories' => $categories,
            'documentTypes' => $documentTypes,
            'currencies' => $currencies,
            'micrositesTypes' => $micrositesTypes,
        ]);

    }

    public function store(StoremicrositesRequest $request, StoreAction $storeAction): RedirectResponse
    {
        $this->authorize(PolicyName::CREATE, Microsites::class);
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $storeAction->execute($data);

        return redirect()->route('microsites.index')->with('success', 'Sitio creado correctamente.');
    }

    public function show(Microsites $microsite)
    {
        $this->authorize(PolicyName::VIEW, $microsite);

        return Inertia::render('Microsites/MicrositesShow', [
            'microsite' => $microsite,
        ]);
    }

    public function showMicrosite(string $slug, $id): \Inertia\Response
    {
        $microsite = Microsites::with('category')->findOrFail($id);

        return Inertia::render('Microsites/Show', [
            'microsite' => $microsite,
        ]);
    }

    public function edit(Microsites $microsite, Category $category)
    {

        $this->authorize(PolicyName::UPDATE, $microsite);
        $categories = Category::query()->select('id', 'name')->get();
        $documentTypes = DocumentTypes::cases();
        $documentTypesArray = array_map(function ($type) {
            return $type->name;
        }, $documentTypes);

        return Inertia::render('Microsites/MicrositeEdit', [
            'microsite' => $microsite,
            'categories' => $categories,
            'documentTypes' => $documentTypesArray,

        ]);
    }

    public function update(UpdatemicrositesRequest $request, microsites $microsite, UpdateAction $updateAction): RedirectResponse
    {
        $this->authorize(PolicyName::UPDATE, $microsite);
        $data = $request->validated();
        $data['id'] = $microsite->id;
        $updateAction->execute($data);

        return redirect()->route('microsites.index')->with('success', 'Sitio actualizado correctamente.');
    }

    public function destroy(Microsites $microsite): RedirectResponse
    {
        $microsites = Microsites::find($microsite->id);
        $this->authorize(PolicyName::DELETE, $microsite);
        $microsites->delete();

        return redirect()->route('microsites.index');
    }
}

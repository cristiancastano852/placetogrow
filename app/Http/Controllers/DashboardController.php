<?php

namespace App\Http\Controllers;

use App\Constants\Roles;
use App\Http\Requests\DashboardRequest;
use App\Models\Microsites;
use App\Services\Dashboard\DashboardService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(DashboardRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        $userId = $user->hasRole(Roles::ADMIN->value) ? null : Auth::user()->id;
        $email = $user->hasRole(Roles::GUEST->value) ? Auth::user()->email : null;
        $metrics = $this->dashboardService->getInvoicesMetrics($data, $userId, $email);
        $microsites = Microsites::MicrositesByUser(Auth::user())->get();

        return Inertia::render('Dashboard', [
            'metrics' => $metrics,
            'microsites' => $microsites,
        ]);
    }
}

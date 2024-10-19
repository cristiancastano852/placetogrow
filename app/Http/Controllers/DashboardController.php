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
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $micrositeId = $data['micrositeId'] ?? null;
        $role = Auth::user()->role;
        $userId = $role === Roles::ADMIN->value ? null : Auth::user()->id;
        $metrics = $this->dashboardService->getInvoiceMetrics($startDate, $endDate, $userId, $micrositeId);

        $microsites = Microsites::MicrositesByUser(Auth::user())->get();

        return Inertia::render('Dashboard', [
            'metrics' => $metrics,
            'microsites' => $microsites,
        ]);
    }
}

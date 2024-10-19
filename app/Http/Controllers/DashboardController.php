<?php

namespace App\Http\Controllers;

use App\Constants\InvoiceStatus;
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
        $user = Auth::user();
        $userId = $user->hasRole(Roles::ADMIN->value) ? null : Auth::user()->id;
        $email = $user->hasRole(Roles::GUEST->value) ? Auth::user()->email : null;

        $metricsByStatus = $this->dashboardService->getInvoiceMetrics($startDate, $endDate, $userId, $micrositeId);
        $metricsInvoicesExpiredLast30Days = $this->dashboardService->getInvoicesMetricsByParams($userId, $email, now()->subMonth(), now()->subDay(), InvoiceStatus::EXPIRED->name);
        $metricInvoicesDueExpirate = $this->dashboardService->getInvoicesMetricsByParams($userId, $email, now(), $data['expirateDateLimit'], InvoiceStatus::PENDING->name);

        $invoicesExpiredAndDueExpirate = collect($metricInvoicesDueExpirate['invoices'])
            ->merge($metricsInvoicesExpiredLast30Days['invoices'])
            ->sortByDesc('expiration_date');
        $paginatedInvoices = $this->paginateCollection($invoicesExpiredAndDueExpirate, 10);
        $metrics = [
            'statusInvoices' => $metricsByStatus,
            'invoicesAlert' => $paginatedInvoices,
        ];
        $microsites = Microsites::MicrositesByUser(Auth::user())->get();

        return Inertia::render('Dashboard', [
            'metrics' => $metrics,
            'microsites' => $microsites,
        ]);
    }

    private function paginateCollection($collection, $perPage)
    {
        $currentPage = request()->get('page', 1);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage);
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator($currentPageItems, $collection->count(), $perPage);
        $paginatedItems->setPath(request()->url());

        return $paginatedItems;
    }
}

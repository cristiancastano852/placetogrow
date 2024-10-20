<?php

namespace App\Services\Dashboard;

use App\Constants\DateFilterTypes;
use App\Constants\InvoiceStatus;
use App\Constants\Roles;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getInvoicesMetrics(?array $data, ?User $user): array
    {
        $userId = $user->hasRole(Roles::ADMIN->value) ? null :  $user->id;
        $email = $user->hasRole(Roles::GUEST->value) ? $user->email : null;
        if ($email) {
            $userId = null;
        }
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $micrositeId = $data['micrositeId'] ?? null;
        $metricsByStatus = $this->getInvoiceMetricsCountByStatus($startDate, $endDate, $userId, $email, $micrositeId);
        $metricsInvoicesExpiredLast30Days = $this->getInvoicesMetricsByParams($userId, $email, now()->subMonth(), now()->subDay(), InvoiceStatus::EXPIRED->name);
        $metricInvoicesDueExpirate = $this->getInvoicesMetricsByParams($userId, $email, now(), $data['expirateDateLimit'], InvoiceStatus::PENDING->name);
        $invoicesExpiredAndDueExpirate = collect($metricInvoicesDueExpirate['invoices'])
            ->merge($metricsInvoicesExpiredLast30Days['invoices'])
            ->sortByDesc('expiration_date');
        $paginatedInvoices = $this->paginateCollection($invoicesExpiredAndDueExpirate, 10);
        $metricsByStatus['numberInvoicesDueExpire'] = $metricInvoicesDueExpirate['total_count'];
        $metrics = [
            'statusInvoices' => $metricsByStatus,
            'invoicesAlert' => $paginatedInvoices,
        ];

        return $metrics;
    }

    private function getInvoiceMetricsCountByStatus(
        ?string $startDate = null, 
        ?string $endDate = null, 
        ?string $userId = null, 
        ?string $email = null,
        ?int $micrositeId = null): array
    {
        $startDate = $this->formatDate($startDate, DateFilterTypes::START->value);
        $endDate = $this->formatDate($endDate, DateFilterTypes::END->value);
        $cacheKey = 'invoice_status_metrics'.$userId.'_'.($startDate ?? DateFilterTypes::START->value).'_'.($endDate ?? DateFilterTypes::END->value).'_'.($micrositeId ?? 'all');
        $queryResult = Cache::remember($cacheKey, 3600, function () use ($startDate, $endDate, $userId, $email, $micrositeId) {
            return DB::select('CALL GetInvoiceMetrics(?, ?, ?, ?, ?)', [$startDate, $endDate, $userId, $email, $micrositeId]);
        });
        $metrics = [
            InvoiceStatus::PENDING->name => 0,
            InvoiceStatus::PAID->name => 0,
            InvoiceStatus::EXPIRED->name => 0,
        ];
        foreach ($queryResult as $result) {
            $metrics[$result->status] = $result->total;
        }
        return $metrics;
    }

    private function getInvoicesMetricsByParams(
        ?string $userId = null,
        ?string $email = null,
        ?string $startDate = null,
        ?string $endDate = null,
        ?string $status = InvoiceStatus::PENDING->name
    ): array {
        $startDate = $this->formatDate($startDate, DateFilterTypes::START->value);
        $endDate = $this->formatDate($endDate, DateFilterTypes::END->value);
        $cacheKey = 'invoices_metric_by_params'.$userId.'_'.$email.'_'.($startDate ?? 'no_start_date').'_'.
            ($endDate ?? 'no_end_date').'_'.$status;
        $queryResult = Cache::remember($cacheKey, 3600, function () use ($userId, $email, $startDate, $endDate, $status) {
            return DB::select('CALL GetInvoicesDueExpireAlert(?, ?, ?, ?, ?)', [
                $userId,
                $email,
                $startDate,
                $endDate,
                $status,
            ]);
        });

        if (count($queryResult) <= 0) {
            return [
                'total_count' => 0,
                'invoices' => [],
            ];
        }
        $totalCount = $queryResult[0]->total_count;
        $data = [
            'total_count' => $totalCount,
            'invoices' => $queryResult,
        ];

        return $data;
    }

    private function formatDate(?string $date, string $type): ?string
    {
        if (! $date) {
            return null;
        }

        $parsedDate = date_create($date);
        if (! $parsedDate) {
            return null;
        }

        return $parsedDate->format('Y-m-d').($type === DateFilterTypes::START->value ? ' 00:00:00' : ' 23:59:59');
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

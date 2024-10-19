<?php

namespace App\Services\Dashboard;

use App\Constants\DateFilterTypes;
use App\Constants\InvoiceStatus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getInvoiceMetrics(?string $startDate = null, ?string $endDate = null, ?string $userId = null, ?int $micrositeId = null): array
    {
        $startDate = $this->formatDate($startDate, DateFilterTypes::START->value);
        $endDate = $this->formatDate($endDate, DateFilterTypes::END->value);
        $cacheKey = 'invoice_status_metrics'.$userId.'_'.($startDate ?? DateFilterTypes::START->value).'_'.($endDate ?? DateFilterTypes::END->value).'_'.($micrositeId ?? 'all');
        $queryResult = Cache::remember($cacheKey, 3600, function () use ($startDate, $endDate, $userId, $micrositeId) {
            return DB::select('CALL GetInvoiceMetrics(?, ?, ?, ?)', [$startDate, $endDate, $userId, $micrositeId]);
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
}

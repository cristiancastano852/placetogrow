<?php

namespace App\Services\Microsites;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MicrositeService
{
    public function __construct()
    {
    }
    public function getLast4MonthsPayments(int $micrositeId): array
    {
        $paymentsByMonth = [];

        $currentMonth = Carbon::now()->startOfMonth();
        $threeMonthsAgo = $currentMonth->copy()->subMonths(3);
        $previousMonths = $this->getCachedPaymentsForPreviousMonths($micrositeId, $threeMonthsAgo, $currentMonth->subMonth()->endOfMonth() );
        $currentMonthPayments = $this->getCachedPaymentsForCurrentMonth($micrositeId, $currentMonth);
        $paymentsByMonth = array_merge($previousMonths, [$currentMonthPayments]);
        return $paymentsByMonth;
    }

    private function getCachedPaymentsForPreviousMonths(int $micrositeId, Carbon $startDate, Carbon $endDate): array
    {
        $cacheKey = "microsite_{$micrositeId}_previous_months";
        $status = null;
        return Cache::remember($cacheKey, $this->getEndOfMonthExpiration(), function () use ($micrositeId, $status, $startDate, $endDate) {
            return DB::select('
                CALL GetAmountMounthlyByMicrosite(?, ?, ?, ?)
            ', [$micrositeId, $status, $startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        });
    }

    private function getCachedPaymentsForCurrentMonth(int $micrositeId, Carbon $currentMonth): object | null
    {
        $cacheKey = "microsite_{$micrositeId}_current_month";
        $status = null;
        return Cache::remember($cacheKey, now()->addDays(7), function () use ($micrositeId,$status, $currentMonth) {
            return DB::selectOne('
                CALL GetAmountMounthlyByMicrosite(?, ?, ?, ?)
            ', [$micrositeId,$status, $currentMonth->format('Y-m-d'), Carbon::now()->endOfMonth()]);
        });
    }

    private function getEndOfMonthExpiration(): Carbon
    {
        return Carbon::now()->endOfMonth();
    }
}

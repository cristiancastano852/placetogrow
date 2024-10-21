<?php

namespace App\Services\Microsites;

use App\Constants\PaymentStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MicrositeService
{
    public function __construct() {}

    public function getLast4MonthsPayments(int $micrositeId): array
    {
        $status = implode(',', [PaymentStatus::APPROVED->name, PaymentStatus::APPROVED_PARTIAL->name]);
        $currentMonth = Carbon::now()->endOfMonth();
        $threeMonthsAgo = $currentMonth->copy()->subMonths(3);
        $previousMonths = $this->getCachedPaymentsForPreviousMonths($micrositeId, $status,  $threeMonthsAgo, $currentMonth->subMonth()->endOfMonth());
        $currentMonthPayments = $this->getCachedPaymentsForCurrentMonth($micrositeId, $status, $currentMonth);

        return $this->formatPaymentsByMonth($threeMonthsAgo, $previousMonths, $currentMonthPayments);
    }

    private function getCachedPaymentsForPreviousMonths(int $micrositeId, ?string $status =null, Carbon $startDate, Carbon $endDate): array
    {
        $cacheKey = "microsite_{$micrositeId}_previous_months";

        return Cache::remember($cacheKey, $this->getEndOfMonthExpiration(), function () use ($micrositeId, $status, $startDate, $endDate) {
            return DB::select('
                CALL GetAmountMounthlyByMicrosite(?, ?, ?, ?)
            ', [$micrositeId, $status, $startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        });
    }

    private function getCachedPaymentsForCurrentMonth(int $micrositeId, ?string $status=null, Carbon $currentMonth): ?object
    {
        $cacheKey = "microsite_{$micrositeId}_current_month";

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($micrositeId, $status, $currentMonth) {
            return DB::selectOne('
                CALL GetAmountMounthlyByMicrosite(?, ?, ?, ?)
            ', [$micrositeId, $status, $currentMonth->format('Y-m-d'), Carbon::now()->endOfMonth()]);
        });
    }

    private function getEndOfMonthExpiration(): Carbon
    {
        return Carbon::now()->endOfMonth();
    }

    private function formatPaymentsByMonth(Carbon $threeMonthsAgo, array $previousMonths, ?object $currentMonthPayments): array
    {
        $formattedMonths = [
            'threeMonthAgo' => $threeMonthsAgo->format('Y-m'),
            'twoMonthAgo' => $threeMonthsAgo->addMonth()->format('Y-m'),
            'oneMonthAgo' => $threeMonthsAgo->addMonth()->format('Y-m'),
            'currentMonth' => $threeMonthsAgo->addMonth()->format('Y-m')
        ];

        return [
            0 => [
                'month' => $formattedMonths['threeMonthAgo'],
                'total_payments' => $previousMonths[0]->total_payments ?? 0,
            ],
            1 => [
                'month' => $formattedMonths['twoMonthAgo'],
                'total_payments' => $previousMonths[1]->total_payments ?? 0,
            ],
            2 => [
                'month' => $formattedMonths['oneMonthAgo'],
                'total_payments' => $previousMonths[2]->total_payments ?? 0,
            ],
            3 => [
                'month' => $formattedMonths['currentMonth'],
                'total_payments' => $currentMonthPayments->total_payments ?? 0,
            ],
        ];
    }
}

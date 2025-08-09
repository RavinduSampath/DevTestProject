<?php

namespace App\Services;

use Carbon\Carbon;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class BillCalculator
{
    /**
     * Calculate bill according to spec.
     *
     * @param int|string $previousReading
     * @param int|string $currentReading
     * @param string|Carbon $previousDate  (older)
     * @param string|Carbon $currentDate   (newer)
     * @return array
     * @throws InvalidArgumentException
     */
    public function calculateBill($previousReading, $currentReading, $previousDate, $currentDate): array
    {
        // basic validation & casting
        if (!is_numeric($previousReading) || !is_numeric($currentReading)) {
            throw new InvalidArgumentException('Meter readings must be numeric.');
        }

        $previousReading = (int) $previousReading;
        $currentReading  = (int) $currentReading;

        if ($currentReading < $previousReading) {
            throw new InvalidArgumentException('Current reading cannot be less than previous reading.');
        }

        // parse dates to Carbon
        $prev = $this->parseDate($previousDate);
        $curr = $this->parseDate($currentDate);

        // Ensure order prev <= curr. If not, swap (defensive)
        if ($prev->gt($curr)) {
            [$prev, $curr] = [$curr, $prev];
            Log::warning('BillCalculator: dates were reversed and have been swapped to ensure correct order.');
        }

        // days difference (units for first range)
        $daysDifference = $prev->diffInDays($curr);

        // If same day, treat as 1 day (so first-range capacity is 1) — avoids zero-cap issues.
        if ($daysDifference <= 0) {
            $daysDifference = 1;
        }

        // units consumed
        $unitsConsumed = $currentReading - $previousReading;
        if ($unitsConsumed < 0) $unitsConsumed = 0; // extra safety

        // capacities
        $firstCap  = $daysDifference;           // first range units capacity
        $secondCap = 2 * $daysDifference;       // second range units capacity

        // allocate units to ranges
        $firstUnits  = min($unitsConsumed, $firstCap);
        $remaining   = max(0, $unitsConsumed - $firstUnits);

        $secondUnits = min($remaining, $secondCap);
        $remaining   = max(0, $remaining - $secondUnits);

        $thirdUnits  = max(0, $remaining);

        // charges per spec
        $firstCharge  = $firstUnits * 20;
        $secondCharge = $secondUnits * 35;
        $thirdCharge  = $this->sumProgressiveUnitsCharge($thirdUnits, 40); // 40,41,...

        // fixed charge: only for the top range reached
        if ($thirdUnits > 0) {
            $fixedCharge = 1500;
        } elseif ($secondUnits > 0) {
            $fixedCharge = 1000;
        } elseif ($firstUnits > 0) {
            $fixedCharge = 500;
        } else {
            $fixedCharge = 0;
        }

        $total = $firstCharge + $secondCharge + $thirdCharge + $fixedCharge;

        return [
            'previous_reading' => $previousReading,
            'current_reading'  => $currentReading,
            'date_difference'  => $daysDifference,
            'unit_difference'  => $unitsConsumed,

            'first_range' => [
                'units'       => $firstUnits,
                'rate'        => 20,
                'charge'      => $firstCharge,
                'description' => "First {$daysDifference} units @ 20 LKR/unit"
            ],

            'second_range' => [
                'units'       => $secondUnits,
                'rate'        => 35,
                'charge'      => $secondCharge,
                'description' => "Next {$secondCap} units @ 35 LKR/unit"
            ],

            'third_range' => [
                'units'       => $thirdUnits,
                'rate'        => 'progressive',
                'charge'      => $thirdCharge,
                'description' => $this->getThirdRangeDescription($thirdUnits)
            ],

            'fixed_charge' => [
                'amount'      => $fixedCharge,
                'description' => $fixedCharge > 0 ? 'Fixed charge for top range reached' : 'No fixed charge'
            ],

            'total_charge' => $total
        ];
    }

    protected function parseDate($date): Carbon
    {
        if ($date instanceof Carbon) return $date;

        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid date format: ' . $e->getMessage());
        }
    }

    /**
     * Sum progressive third-range unit charges starting from $startRate and increment by 1.
     * E.g. units=3, startRate=40 => 40+41+42
     */
    protected function sumProgressiveUnitsCharge(int $units, int $startRate): int
    {
        if ($units <= 0) return 0;
        // arithmetic series sum: n/2 * (2a + (n-1)d) where d=1
        return (int) (($units * (2 * $startRate + ($units - 1))) / 2);
    }

    protected function getThirdRangeDescription(int $units): string
    {
        if ($units <= 0) return 'No units in third range';

        $startRate = 40;
        $endRate = $startRate + $units - 1;

        if ($units <= 6) {
            return 'Progressive rates: ' . implode(' + ', range($startRate, $endRate)) . ' LKR';
        }

        return "Progressive rates: {$startRate} + ... + {$endRate} LKR (total {$units} units)";
    }
}

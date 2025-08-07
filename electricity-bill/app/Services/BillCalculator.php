<?php

namespace App\Services;

use App\Models\MeterReadings;
use Carbon\Carbon;

class BillCalculator
{
    public function calculateBill($accountNumber)
    {
        // Find the customer by account number
        $customer = \App\Models\Customers::where('customer_account_number', $accountNumber)->first();
        if (!$customer) {
            return null; // Customer not found
        }

        // Get readings by customer_id
        $readings = MeterReadings::where('customer_id', $customer->id)
            ->orderBy('reading_date', 'desc')
            ->take(2)
            ->get();

        if ($readings->count() < 2) {
            return null; // Not enough readings
        }

        $lastReading = $readings[0];
        $previousReading = $readings[1];

        $dateDiff = Carbon::parse($lastReading->reading_date)->diffInDays($previousReading->reading_date);
        $unitDiff = $lastReading->reading - $previousReading->reading;

        $firstRangeUnits = min($dateDiff, $unitDiff);
        $secondRangeUnits = min(2 * $dateDiff, max(0, $unitDiff - $firstRangeUnits));
        $thirdRangeUnits = max(0, $unitDiff - $firstRangeUnits - $secondRangeUnits);

        $fixedCharge = 0;
        $firstRangeCharge = $firstRangeUnits * 20;
        $secondRangeCharge = $secondRangeUnits * 35;
        $thirdRangeCharge = 0;

        if ($thirdRangeUnits > 0) {
            $fixedCharge = 1500;
            $thirdRangeCharge = 0;
            for ($i = 0; $i < $thirdRangeUnits; $i++) {
                $thirdRangeCharge += (40 + $i);
            }
        } elseif ($secondRangeUnits > 0) {
            $fixedCharge = 1000;
        } elseif ($firstRangeUnits > 0) {
            $fixedCharge = 500;
        }

        $totalCharge = $fixedCharge + $firstRangeCharge + $secondRangeCharge + $thirdRangeCharge;

        return [
            'last_reading_date' => $lastReading->reading_date,
            'last_reading_value' => $lastReading->reading,
            'previous_reading' => $previousReading->reading,
            'fixed_charge' => $fixedCharge,
            'first_range_charge' => $firstRangeCharge,
            'second_range_charge' => $secondRangeCharge,
            'third_range_charge' => $thirdRangeCharge,
            'total_charge' => $totalCharge,
        ];
    }
}

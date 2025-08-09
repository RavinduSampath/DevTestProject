<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use App\Services\BillCalculator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomersController extends Controller
{
    protected $billCalculator;

    public function __construct(BillCalculator $billCalculator)
    {
        $this->billCalculator = $billCalculator;
    }

    /**
     * Display customer search page
     */
    public function index()
    {
        return view('pages.customers.index');
    }

    /**
     * Search for customer by account number
     */
    public function search(Request $request)
    {
        $request->validate([
            'customer_account_number' => 'required|string|max:20'
        ]);

        try {
            $customer = Customers::where('customer_account_number', $request->customer_account_number)
                ->firstOrFail();

            return redirect()->route('pages.customers.details', $customer->customer_account_number);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Account number not found.');
        }
    }


    public function details($account_number)
    {
        try {
            $customer = Customers::with(['meterReadings' => function ($query) {
                $query->orderBy('reading_date', 'desc');
            }])
                ->where('customer_account_number', $account_number)
                ->firstOrFail();

            $readings = $customer->meterReadings; // collection sorted desc

            // always pass meterReadings so blade won't break
            if ($readings->count() < 2) {
                return view('pages.customers.details', [
                    'customer' => $customer,
                    'meterReadings' => $readings,
                    'bill' => null,
                    'warning' => 'Not enough meter readings to calculate bill (need at least 2 readings)'
                ]);
            }

            // latest (index 0) is current, index 1 is previous (since sorted desc)
            $currentReading  = $readings[0];
            $previousReading = $readings[1];

            // call the injected service
            $bill = $this->billCalculator->calculateBill(
                $previousReading->reading,
                $currentReading->reading,
                $previousReading->reading_date,
                $currentReading->reading_date
            );

            return view('pages.customers.details', [
                'customer' => $customer,
                'meterReadings' => $readings,
                'bill' => $bill,
                'current_reading_obj' => $currentReading,
                'previous_reading_obj' => $previousReading
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('customers.index')
                ->with('error', 'Customer account not found.');
        }
    }
}

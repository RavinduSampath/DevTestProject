<?php

namespace App\Http\Controllers;

use App\Models\MeterReadings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MeterReadingsController extends Controller
{
    public function index()
    {
        $readings = MeterReadings::with('customer')->paginate(10);
        return view('pages.reder.index', compact('readings'));
    }

    public function create()
    {
        return view('pages.reder.add-reading'); // Updated to point to the correct view
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'customer_account_number' => 'required|exists:customers,customer_account_number',
            'reading_date' => 'required|date',
            'reading' => 'required|integer|min:0',
        ]);

        try {
            MeterReadings::create([
                'customer_account_number' => $request->customer_account_number,
                'reading_date' => $request->reading_date,
                'reading' => $request->reading,
            ]);

            return redirect()->route('meter-reader.index')
                ->with('success', 'Reading added successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add meter reading: ' . $e->getMessage());
        }
    }

    public function show(MeterReadings $meterReading)
    {
        //
    }

    public function edit(MeterReadings $meterReading)
    {
        //
    }

    public function update(Request $request, MeterReadings $meterReading)
    {
        //
    }

    public function destroy(MeterReadings $meterReading)
    {
        //
    }
}

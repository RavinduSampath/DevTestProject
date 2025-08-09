<?php

namespace App\Http\Controllers;

use App\Models\MeterReadings;
use Illuminate\Http\Request;

class MeterReadingsController extends Controller
{
    public function index()
    {
        $readings = MeterReadings::with('customer')->paginate(10);
        return view('pages.reder.index', compact('readings'));
    }

    public function create()
    {
        return view('pages.meter-reader.add-reading');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_account_number' => 'required|exists:customers,customer_account_number',
            'reading_date' => 'required|date',
            'reading' => 'required|integer|min:0',
        ]);

        try {
            MeterReadings::create($request->only('customer_account_number', 'reading_date', 'reading'));
            return redirect()->route('meter-reader.index')->with('success', 'Reading added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to add meter reading: ' . $e->getMessage());
        }
    }
}

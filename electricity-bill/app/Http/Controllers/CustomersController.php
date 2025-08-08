<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use App\Services\BillCalculator;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customers $customer)
    {
        // return view('pages.customers.details', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customers $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customers $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customers $customer)
    {
        //
    }

    public function search(Request $request)
    {
        $customer = Customers::where('customer_account_number', $request->customer_account_number)->first();
        if ($customer) {
            return redirect()->route('pages.customers.details', $customer->customer_account_number);
        } else {
            return redirect()->back()->with('error', 'Account number not found.');
        }
    }

    public function details($account_number)
    {
        $customer = Customers::where('customer_account_number', $account_number)
            ->with('meterReadings')
            ->firstOrFail();

        $bill = app(BillCalculator::class)->calculateBill($account_number);

        return view('pages.customers.details', compact('customer', 'bill'));
    }
}

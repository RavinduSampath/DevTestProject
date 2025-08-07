@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <h3>Customer Details</h3>
        <p><strong>Account Number:</strong> {{ $customer->customer_account_number }}</p>
        <p><strong>Name:</strong> {{ $customer->customer_name }}</p>

        <h4>Meter Readings</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reading</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customer->meterReadings as $reading)
                    <tr>
                        <td>{{ $reading->reading_date }}</td>
                        <td>{{ $reading->reading }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($bill)
            <h4>Bill Details</h4>
            <ul>
                <li>Last Reading Date: {{ $bill['last_reading_date'] }}</li>
                <li>Last Reading Value: {{ $bill['last_reading_value'] }}</li>
                <li>Previous Reading: {{ $bill['previous_reading'] }}</li>
                <li>Fixed Charge: {{ $bill['fixed_charge'] }}</li>
                <li>First Range Charge: {{ $bill['first_range_charge'] }}</li>
                <li>Second Range Charge: {{ $bill['second_range_charge'] }}</li>
                <li>Third Range Charge: {{ $bill['third_range_charge'] }}</li>
                <li><strong>Total Charge: {{ $bill['total_charge'] }}</strong></li>
            </ul>
        @else
            <div class="alert alert-warning">Not enough readings to calculate the bill.</div>
        @endif
    </div>
@endsection

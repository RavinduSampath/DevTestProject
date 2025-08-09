@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Customer Info -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4>Customer Details</h4>
            </div>
            <div class="card-body">
                <p><strong>Account Number:</strong> {{ $customer->customer_account_number }}</p>
                <p><strong>Name:</strong> {{ $customer->customer_name }}</p>
            </div>
        </div>

        <!-- Meter Readings History -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h4>Meter Readings History</h4>
            </div>
            <div class="card-body">
                @if ($meterReadings->count() > 0)
                    @php
                        // sorted descending already from controller, but ensure values() so indices are contiguous
                        $sorted = $meterReadings->sortByDesc('reading_date')->values();
                    @endphp

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reading</th>
                                <th>Units Consumed</th>
                                <th>Days Difference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sorted as $i => $row)
                                @php
                                    // for each row, compare with next older reading (index i+1) to get units/days
                                    $next = $sorted->get($i + 1); // older reading
                                @endphp
                                <tr @if ($i === 0) class="table-success" @endif>
                                    <td>
                                        {{ \Carbon\Carbon::parse($row->reading_date)->format('Y-m-d') }}
                                        @if ($i === 0)
                                            <span class="badge bg-success ms-2">Latest</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($row->reading) }}</td>
                                    <td>
                                        @if ($next)
                                            {{ number_format($row->reading - $next->reading) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($next)
                                            {{ \Carbon\Carbon::parse($next->reading_date)->diffInDays(\Carbon\Carbon::parse($row->reading_date)) }}
                                            days
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">No meter readings available.</div>
                @endif
            </div>
        </div>

        <!-- Bill Calculation -->
        @if ($bill)
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h4>Current Bill Calculation</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <strong>Previous Reading:</strong><br>
                            <span class="fs-5">{{ number_format($bill['previous_reading']) }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Current Reading:</strong><br>
                            <span class="fs-5">{{ number_format($bill['current_reading']) }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Units Consumed:</strong><br>
                            <span class="fs-5 text-primary">{{ number_format($bill['unit_difference']) }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Days Difference:</strong><br>
                            <span class="fs-5 text-info">{{ $bill['date_difference'] }} days</span>
                        </div>
                    </div>

                    <h5>Charge Breakdown</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-secondary">
                                <tr>
                                    <th style="width:25%">Range</th>
                                    <th style="width:15%">Units</th>
                                    <th style="width:15%">Rate (LKR)</th>
                                    <th style="width:25%">Calculation</th>
                                    <th style="width:20%">Amount (LKR)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($bill['first_range']['units'] > 0)
                                    <tr>
                                        <td class="text-start">
                                            <span class="badge bg-primary">First Range</span><br>
                                            <small class="text-muted">{{ $bill['first_range']['description'] }}</small>
                                        </td>
                                        <td>{{ number_format($bill['first_range']['units']) }}</td>
                                        <td>20.00</td>
                                        <td>{{ number_format($bill['first_range']['units']) }} × 20</td>
                                        <td class="text-end">{{ number_format($bill['first_range']['charge'], 2) }}</td>
                                    </tr>
                                @endif

                                @if ($bill['second_range']['units'] > 0)
                                    <tr>
                                        <td class="text-start">
                                            <span class="badge bg-warning">Second Range</span><br>
                                            <small class="text-muted">{{ $bill['second_range']['description'] }}</small>
                                        </td>
                                        <td>{{ number_format($bill['second_range']['units']) }}</td>
                                        <td>35.00</td>
                                        <td>{{ number_format($bill['second_range']['units']) }} × 35</td>
                                        <td class="text-end">{{ number_format($bill['second_range']['charge'], 2) }}</td>
                                    </tr>
                                @endif

                                @if ($bill['third_range']['units'] > 0)
                                    <tr>
                                        <td class="text-start">
                                            <span class="badge bg-danger">Third Range</span><br>
                                            <small class="text-muted">{{ $bill['third_range']['description'] }}</small>
                                        </td>
                                        <td>{{ number_format($bill['third_range']['units']) }}</td>
                                        <td>Progressive</td>
                                        <td>
                                            @php
                                                $rates = [];
                                                for ($i = 0; $i < min($bill['third_range']['units'], 4); $i++) {
                                                    $rates[] = 40 + $i;
                                                }
                                                echo implode(' + ', $rates);
                                                if ($bill['third_range']['units'] > 4) {
                                                    echo ' + ...';
                                                }
                                            @endphp
                                        </td>
                                        <td class="text-end">{{ number_format($bill['third_range']['charge'], 2) }}</td>
                                    </tr>
                                @endif

                                <!-- Fixed charge -->
                                <tr class="table-secondary fw-bold">
                                    <td colspan="4" class="text-start">Fixed Charge</td>
                                    <td class="text-end">{{ number_format($bill['fixed_charge']['amount'], 2) }}</td>
                                </tr>
                            </tbody>
                            <tfoot class="table-success fw-bold">
                                <tr>
                                    <td colspan="4" class="text-start">TOTAL AMOUNT</td>
                                    <td class="text-end fs-5">LKR {{ number_format($bill['total_charge'], 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>Rate Structure:</strong><br>
                            • First {{ $bill['date_difference'] }} units: LKR 20/unit<br>
                            • Next {{ $bill['date_difference'] * 2 }} units: LKR 35/unit<br>
                            • Additional units: LKR 40+ (progressive)<br>
                            • Fixed charge based on highest range reached
                        </small>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                Not enough meter readings to calculate the bill. At least two readings are required.
            </div>
        @endif

        <a href="{{ route('customers.index') }}" class="btn btn-secondary mt-3">&larr; Back to Search</a>
    </div>
@endsection

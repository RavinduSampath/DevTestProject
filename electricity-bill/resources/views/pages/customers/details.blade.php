@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <!-- Customer Info -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4>{{ __('messages.customer_details') }}</h4>
            </div>
            <div class="card-body">
                <p><strong>{{ __('messages.account_number') }}:</strong> {{ $customer->customer_account_number }}</p>
                <p><strong>{{ __('messages.name') }}:</strong> {{ $customer->customer_name }}</p>
            </div>
        </div>

        <!-- Meter Readings History -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h4>{{ __('messages.meter_readings_history') }}</h4>
            </div>
            <div class="card-body">
                @if ($meterReadings->isEmpty())
                    <div class="alert alert-info">{{ __('messages.no_meter_readings') }}</div>
                @else
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('messages.reading_date') }}</th>
                                <th>{{ __('messages.reading') }}</th>
                                {{-- <th>{{ __('messages.units_consumed') }}</th>
                                <th>{{ __('messages.days_difference') }}</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($meterReadings as $index => $reading)
                                <tr @if ($index === 0) class="table-success" @endif>
                                    <td>{{ \Carbon\Carbon::parse($reading->reading_date)->format('Y-m-d') }}
                                        @if ($index === 0)
                                            <span class="badge bg-success ms-2">{{ __('messages.latest') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($reading->reading) }}</td>
                                    {{-- <td>
                                        {{ $reading->units_consumed ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $reading->days_difference ?? '-' }}
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <!-- Bill Calculation -->
        @if ($bill)
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h4>{{ __('messages.current_bill_calculation') }}</h4>
                </div>
                <div class="card-body">

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <strong>{{ __('messages.previous_reading') }}:</strong><br>
                            <span class="fs-5">{{ number_format($bill['previous_reading']) }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>{{ __('messages.current_reading') }}:</strong><br>
                            <span class="fs-5">{{ number_format($bill['current_reading']) }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>{{ __('messages.units_consumed') }}:</strong><br>
                            <span class="fs-5 text-primary">{{ number_format($bill['unit_difference']) }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>{{ __('messages.days_difference') }}:</strong><br>
                            <span class="fs-5 text-info">{{ $bill['date_difference'] }} {{ __('messages.days') }}</span>
                        </div>
                    </div>

                    <h5>{{ __('messages.charge_breakdown') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-secondary">
                                <tr>
                                    <th style="width:25%">{{ __('messages.range') }}</th>
                                    <th style="width:15%">{{ __('messages.units') }}</th>
                                    <th style="width:15%">{{ __('messages.rate') }}</th>
                                    <th style="width:25%">{{ __('messages.calculation') }}</th>
                                    <th style="width:20%">{{ __('messages.amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($bill['first_range']['units'] > 0)
                                    <tr>
                                        <td class="text-start">
                                            <span class="badge bg-primary">{{ __('messages.first_range') }}</span><br>
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
                                            <span class="badge bg-warning">{{ __('messages.second_range') }}</span><br>
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
                                            <span class="badge bg-danger">{{ __('messages.third_range') }}</span><br>
                                            <small class="text-muted">{{ $bill['third_range']['description'] }}</small>
                                        </td>
                                        <td>{{ number_format($bill['third_range']['units']) }}</td>
                                        <td>{{ __('messages.progressive') }}</td>
                                        <td>{{ $bill['third_range']['calculation_string'] ?? __('messages.progressive_rates') }}
                                        </td>
                                        <td class="text-end">{{ number_format($bill['third_range']['charge'], 2) }}</td>
                                    </tr>
                                @endif

                                <tr class="table-secondary fw-bold">
                                    <td colspan="4" class="text-start">{{ __('messages.fixed_charge') }}</td>
                                    <td class="text-end">{{ number_format($bill['fixed_charge']['amount'], 2) }}</td>
                                </tr>
                            </tbody>
                            <tfoot class="table-success fw-bold">
                                <tr>
                                    <td colspan="4" class="text-start">{{ __('messages.total_amount') }}</td>
                                    <td class="text-end fs-5">LKR {{ number_format($bill['total_charge'], 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>{{ __('messages.rate_structure') }}:</strong><br>
                            • {{ __('messages.first_range_detail', ['units' => $bill['date_difference']]) }}<br>
                            • {{ __('messages.second_range_detail', ['units' => $bill['date_difference'] * 2]) }}<br>
                            • {{ __('messages.additional_units') }}<br>
                            • {{ __('messages.fixed_charge_detail') }}
                        </small>
                    </div>
                </div>
            </div>
        @elseif (!empty($warning))
            <div class="alert alert-warning">{{ $warning }}</div>
        @else
            <div class="alert alert-info">{{ __('messages.no_bill_available') }}</div>
        @endif

        <a href="{{ route('customers.index') }}" class="btn btn-secondary mt-3">&larr;
            {{ __('messages.back_to_search') }}</a>
    </div>
@endsection

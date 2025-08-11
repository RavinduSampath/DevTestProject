@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100">

        <h1 class="mb-4">{{ __('messages.app_title') }}</h1>

        <form action="{{ route('pages.customers.search') }}" method="GET" class="w-50">

            <div class="mb-3">
                <input type="number" name="customer_account_number" class="form-control"
                    placeholder="{{ __('messages.customer_account_number') }}"
                    aria-label="{{ __('messages.customer_account_number') }}" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                {{ __('messages.submit') }}
            </button>
        </form>
        @if (session('error'))
            <div class="alert alert-danger mt-3 w-50 text-center">
                {{ session('error') }}
            </div>
        @endif
    </div>
@endsection

@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100">
        <form action="{{ route('pages.customers.search') }}" method="GET" class="w-25">
            <input type="number" name="customer_account_number" class="form-control mb-2"
                placeholder="Enter Customer Account Number" aria-label="Customer Account Number" required>
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </form>
        @if (session('error'))
            <div class="alert alert-danger mt-2">{{ session('error') }}</div>
        @endif
    </div>
@endsection

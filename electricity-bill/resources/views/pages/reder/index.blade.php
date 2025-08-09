<!-- resources/views/pages/reder/add-reading.blade.php -->
@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <h3 class="mb-4">Add Meter Reading</h3>
        <form action="{{ route('meter-reader.store') }}" method="POST" class="w-50 mx-auto">
            @csrf
            <div class="mb-3">
                <label for="customer_account_number" class="form-label">Customer Account Number</label>
                <input type="text" name="customer_account_number" id="customer_account_number" class="form-control"
                    required>
            </div>
            <div class="mb-3">
                <label for="reading" class="form-label">Reading</label>
                <input type="number" name="reading" id="reading" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="reading_date" class="form-label">Reading Date</label>
                <input type="date" name="reading_date" id="reading_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Submit</button>
        </form>
        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection

@extends('layouts.app')
@section('main-container')
    <div class="container mt-5">
        <h2>Add Payment</h2>
        <form id="paymentForm" class="mt-3" data-url="{{ route('admin.student.temp') }}" >
            <input type="text" name="id" value="31b9f408-6db5-4719-bd34-89b48fac2625" id="id">
            <div class="mb-3">
                <label for="month" class="form-label">Month</label>
                <input type="month" class="form-control" id="month" name="month" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" value="4850" required>
            </div>
            <div class="mb-3">
                <label for="paid" class="form-label">Paid</label>
                <input type="number" class="form-control" id="paid" name="paid" value="4800" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="2025-02-21" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <!-- Success/Error Message -->
        <div id="responseMessage" class="mt-3"></div>

        <!-- Display Records (Optional) -->
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Month</th>
                    <th>Amount</th>
                    <th>Paid</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="paymentTable">
                <!-- Records will be appended here -->
            </tbody>
        </table>
    </div>
@endsection



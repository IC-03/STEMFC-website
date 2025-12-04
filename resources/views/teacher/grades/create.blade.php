
<!--
@extends('layouts.app')

@php $title = 'Assign Grades'; @endphp

@section('main-container')
<div class="container mx-auto max-w-xl mt-10 bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">
        Add Grade for<br>
        <span class="text-blue-600">{{ $student->full_name }}</span>
        <span class="text-gray-500 text-lg">({{ $course->course_name }})</span>
    </h2>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('grades.store') }}" method="POST" class="space-y-5">
        @csrf
        <input type="hidden" name="student_id" value="{{ $student->id }}">
        <input type="hidden" name="course_id"  value="{{ $course->id }}">

        <div>
            <label class="block text-gray-700 font-medium mb-1">Assessment Type</label>
            <select name="assessment_type"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200">
                <option value="">— Select type —</option>
                <option value="test"      {{ old('assessment_type')=='test'      ? 'selected':'' }}>Test</option>
                <option value="classwork" {{ old('assessment_type')=='classwork' ? 'selected':'' }}>Classwork</option>
                <option value="homework"  {{ old('assessment_type')=='homework'  ? 'selected':'' }}>Homework</option>
            </select>
            @error('assessment_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Percentage (0–100%)</label>
            <input type="number"
                   name="percentage"
                   min="0"
                   max="100"
                   value="{{ old('percentage') }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200"
            >
            @error('percentage')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Date</label>
            <input type="date"
                   name="date"
                   value="{{ old('date', $today ?? now()->toDateString()) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200"
            >
            @error('date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded shadow">
                Submit Grade
            </button>
        </div>
    </form>
</div>
@endsection
-->
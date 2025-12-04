<!--

@extends('layouts.app')

@php $title = 'Select a Student to Grade'; @endphp

@section('main-container')
<div class="mt-8">
    <h2 class="text-2xl font-bold mb-4">Students in {{ $course->course_name }}</h2>

    @if($students->isEmpty())
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
            <p class="text-blue-700">No students are currently registered in this course.</p>
        </div>
    @else
        <ul class="space-y-2">
            @foreach($students as $student)
                <li class="bg-white border border-gray-200 rounded flex justify-between items-center p-4 shadow-sm">
                    <span>{{ $student->full_name }}</span>
                    <a href="{{ route('grades.create', ['student' => $student->id, 'course' => $course->id]) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Grade
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
-->
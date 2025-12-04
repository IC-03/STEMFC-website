{{-- resources/views/teacher/attendance/list.blade.php --}}
@extends('layouts.app')

@section('main-container')
<style>
	.error{ color: #D40004; font-size: 12px;}
</style>
  <section class="content">
    <div class="container-fluid">

      {{-- ======================
            FILTER CARD 
         ====================== --}}
      <div class="card card-secondary card-outline">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-user-check"></i>
            {{ $title }}   
          </h3>
        </div>
        <p style='margin-left: 30%;'>Select a group and period first and send then you select course</p>
        <div class="card-body">
          <form action="{{ route('teacher.attendance.list') }}" method="GET" id="attendanceFormID" name="attendanceFormID" >
            <div class="form-row">

              {{-- 1) Group selector --}}
              <div class="form-group col-md-3">
                <label for="groupSelect">Group:</label>
                <select id="groupSelect"
                        class="form-control select2"
                        name="group_id"
                        required>
                  <option   value="">Select Group</option>
                  @foreach($groups as $grp)
                    <option value="{{ $grp->id }}"
                      {{ request('group_id') == $grp->id ? 'selected' : '' }}>
                      {{ $grp->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              {{-- 2) Course selector (populated only if a group is chosen) --}}
              <div class="form-group col-md-3">
                <label for="courseSelect">Course:</label>
                <select id="courseSelect"
                        class="form-control select2"
                        name="course_id" 
                        {{-- removed required here --}}
                        {{ isset($availableCourses) && $availableCourses->isNotEmpty() ? '' : '' }} required>
                  <option disabled selected value="">Select Course</option>
                  @if(isset($availableCourses) && $availableCourses->isNotEmpty())
                    @foreach($availableCourses as $course)
                      <option value="{{ $course->id }}"
                        {{ request('course_id') == $course->id ? 'selected' : '' }}>
                        {{ $course->course_name }}
                      </option>
                    @endforeach
                  @endif
                </select>
              </div>

              {{-- 3) Date selector --}}
              <div class="form-group col-md-2">
                <label for="date">Date:</label>
                <input type="date"
                       class="form-control"
                       name="date"
                       id="date"
                       value="{{ request('date', today()->toDateString()) }}"
                       required>
              </div>

              {{-- 4) Period selector --}}
              <div class="form-group col-md-2">
                <label for="period">Period:</label>
                <select id="period"
                        class="form-control select2"
                        name="period"
                        required>
                  <option disabled selected value="">Select Period</option>
                  <option value="1" {{ request('period') == '1' ? 'selected' : '' }}>Period 1</option>
                  <option value="2" {{ request('period') == '2' ? 'selected' : '' }}>Period 2</option>
                </select>
              </div>
				
				    <div class="form-group col-md-2">
                <label for="courseStudents">Students:</label>
                <select id="courseStudents"
                        class="form-control select2"
                        name="courseStudents"
                        >
                  <option  selected value="">All</option>
                    @if(isset($studentsListDD) && count($studentsListDD) > 0)
                    @foreach($studentsListDD as $ddstudent)
                      <option value="{{ $ddstudent->id }}"
                        {{ request('courseStudents') == $ddstudent->id ? 'selected' : '' }}>
                        {{ $ddstudent->full_name }}
                      </option>
                    @endforeach
                  @endif
                </select>
              </div>

            </div><!-- /.form-row -->

            <div class="form-group float-right">
              <button type="submit" class="btn btn-outline-secondary">
                <i class="fas fa-paper-plane"></i> Send
              </button>
            </div>
          </form>
        </div>
      </div><!-- /.card -->

      {{-- ======================
            ATTENDANCE MARKING 
         ====================== --}}
      @if(
        isset($group_id, $course_id, $mydate, $period)
        && $students->isNotEmpty()
      )
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <p class="fs-1 text-center">
                  Mark Attendance for:<br>
                  <span class="text-primary">{{ $className }} / {{ $courseName }}</span><br>
                  on <span class="text-danger">{{ $mydate }}</span>
                  &middot; Period <span class="text-info">{{ $period }}</span>
                </p>
              </div>

              <form action="{{ route('teacher.attendance.store') }}" method="POST">
                @csrf

                {{-- Hidden inputs so we know group/course/date/period on POST --}}
                <input type="hidden" name="group_id"  value="{{ $group_id }}">
                <input type="hidden" name="course_id" value="{{ $course_id }}">
                <input type="hidden" name="mydate"    value="{{ $mydate }}">
                <input type="hidden" name="period"    value="{{ $period }}">
				 <input type="hidden" name="student_id"    value="{{ $student_id }}">

                <div class="card-body">
                  <table id="attendanceTable" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th class="text-center">Picture</th>
                        <th class="text-center">Registration Status</th>
                        <th class="text-center">Comments</th>
                        <th class="text-center">Attendance</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($students as $user)
						
						<?php
					
							$record = collect($attendanceQry)->firstWhere('user_id', $user->id);
							$attendance_comments = $record['attendance_comments'] ?? null;
						?>
                        <tr class="{{ $loop->even ? 'even' : 'odd' }}">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                          <td class="text-center">
                            <img
                              src="{{ $user->picture 
                                        ? asset('storage/' . $user->picture)
                                        : 'https://cdn.dribbble.com/users/4438388/screenshots/15854247/media/0cd6be830e32f80192d496e50cfa9dbc.jpg' 
                                     }}"
                              alt="{{ $user->first_name }}'s profile picture"
                              style="width:50px; height:50px; padding:2px;
                                     box-shadow:0 0 5px #333; border:1px solid #000;
                                     border-radius:50%;">
                          </td>
                          <td class="text-center">
                            @if($user->is_register == 1)
                              <span class="badge bg-success"><i class="fas fa-check"></i></span>
                            @else
                              <span class="badge bg-danger"><i class="fas fa-times"></i></span>
                            @endif
                          </td>
                          <td class="text-center"><textarea class="form-control" name="attendance_comments[]" id="attendance_comments[{{ $user->id }}]" rows="3" placeholder="Enter comments">{{$attendance_comments}}</textarea></td>
                          <td class="text-center">
                            <input type="hidden" name="users[]" value="{{ $user->id }}">
                            <div class="form-check form-check-inline">
                              <input class="form-check-input"
                                     type="radio"
                                     name="status[{{ $loop->index }}]"
                                     id="present{{ $user->id }}"
                                     value="Present"
                                     {{ old("status.{$loop->index}", 'Present') == 'Present' ? 'checked' : '' }}>
                              <label class="form-check-label" for="present{{ $user->id }}">Present</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input"
                                     type="radio"
                                     name="status[{{ $loop->index }}]"
                                     id="absent{{ $user->id }}"
                                     value="Absent"
                                     {{ old("status.{$loop->index}") == 'Absent' ? 'checked' : '' }}>
                              <label class="form-check-label" for="absent{{ $user->id }}">Absent</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input"
                                     type="radio"
                                     name="status[{{ $loop->index }}]"
                                     id="excused{{ $user->id }}"
                                     value="Excused"
                                     {{ old("status.{$loop->index}") == 'Excused' ? 'checked' : '' }}>
                              <label class="form-check-label" for="excused{{ $user->id }}">Excused</label>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th class="text-center">Picture</th>
                        <th class="text-center">Registration Status</th>
                        <th class="text-center">Comments</th>
                        <th class="text-center">Attendance</th>
                      </tr>
                    </tfoot>
                  </table>

                  <div class="form-row float-right mt-4">
                    <button type="submit" class="btn btn-outline-info">
                      Mark Attendance
                    </button>
                  </div>
                </div>
              </form>
            </div><!-- /.card -->
          </div><!-- /.col-md-12 -->
        </div><!-- /.row -->
      @elseif(isset($group_id))
        <div class="alert alert-warning">
          No students found for that Group / Course combination.
        </div>
      @endif

    </div><!-- /.container-fluid -->
  </section>


@endsection


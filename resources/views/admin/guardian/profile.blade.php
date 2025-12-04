@extends('layouts.app')
@section('main-container')
<section class="content">
  <div class="container-fluid">
    <div class="row gutters-sm">
      <!-- Parent Profile Card -->
      <div class="col-md-4 mb-3">
        <div class="card">
          <div class="card-body text-center">
            <img
              src="{{ $parent->picture
                        ? asset('storage/'.$parent->picture)
                        : 'https://via.placeholder.com/150' }}"
              alt="{{ $parent->first_name }} {{ $parent->last_name }}"
              class="rounded-circle img-fluid mb-3"
              style="width:150px; border:1px solid #333; padding:3px;"
            >
            <h4>{{ $parent->first_name }} {{ $parent->last_name }}</h4>
            <p class="text-secondary mb-1">{{ $parent->telephone }}</p>
            <p class="text-muted font-size-sm">{{ $parent->id_no }}</p>
          </div>
        </div>
      </div>

      <!-- Parent Details & Children -->
      <div class="col-md-8">
        <div class="card mb-3">
          <div class="card-body">
            <!-- Full Name -->
            <div class="row">
              <div class="col-sm-3"><h6 class="mb-0">Full Name</h6></div>
              <div class="col-sm-9 text-secondary">{{ $parent->first_name }} {{ $parent->last_name }}</div>
            </div>
            <hr>

            <!-- ID -->
            <div class="row">
              <div class="col-sm-3"><h6 class="mb-0">ID Number(National)</h6></div>
              <div class="col-sm-9 text-secondary">{{ $parent->id_no }}</div>
            </div>
            <hr>

            <!-- Phone -->
            <div class="row">
              <div class="col-sm-3"><h6 class="mb-0">Phone</h6></div>
              <div class="col-sm-9 text-secondary">{{ $parent->telephone }}</div>
            </div>
            <hr>
          </div>
        </div>

        <!-- Children Cards -->
        @if($students->isNotEmpty())
          <div class="card card-solid">
            <div class="card-body pb-0">
              <h4 class="mb-4">
                Children of {{ $parent->first_name }} {{ $parent->last_name }}
                <span class="badge badge-primary">{{ $students->count() }}</span>
              </h4>
              <div class="row">
                @foreach($students as $student)
                  <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                    <div class="card bg-light flex-fill">
                      <div class="card-header text-muted border-bottom-0">{{ $student->id_no }}</div>
                      <div class="card-body pt-0">
                        <div class="row">
                          <div class="col-7">
                            <h2 class="lead"><b>{{ $student->first_name }} {{ $student->last_name }}</b></h2>
                            @if(!empty($student->stratum))
                              <p class="text-muted text-sm"><b>Stratum:</b> {{ $student->stratum }}</p>
                            @endif
                            <ul class="ml-4 mb-0 fa-ul text-muted">
                              <li class="small">
                                <span class="fa-li"><i class="fas fa-lg fa-building"></i></span>
                                {{ $student->address }}, {{ $student->city }}
                              </li>
                              <li class="small">
                                <span class="fa-li"><i class="fas fa-lg fa-phone"></i></span>
                                {{ $student->telephone }}
                              </li>
                            </ul>
                          </div>
                          <div class="col-5 text-center">
                            <img
                              src="{{ $student->picture
                                        ? asset('storage/'.$student->picture)
                                        : 'https://via.placeholder.com/150' }}"
                              alt="{{ $student->first_name }} {{ $student->last_name }}"
                              class="rounded-circle img-fluid"
                              style="width:100px; height:100px;"
                            >
                          </div>
                        </div>
                      </div>
                      <div class="card-footer">
                        <div class="text-right">
                          <a
                            href="{{ route('admin.student.profile', $student->uuid) }}"
                            class="btn btn-sm btn-outline-primary"
                          >
                            <i class="fas fa-user"></i> View Profile
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @else
          <p class="text-center text-muted">No children found for this guardian.</p>
        @endif

      </div>
    </div>
  </div>
</section>
@endsection

@extends('layouts.app')
@section('main-container')
<style>
        table { border-collapse: collapse; width: 100%; font-size: 12px; }
        th, td { border: 1px solid #aaa; padding: 4px 6px; text-align: center; }
        th.date-header { background-color: #eaeaea; }
        .present { background-color: #ffffff; }
        .absent  { background-color: #f4cccc; }
        .excused { background-color: #d9ead3; }
        .na      { background-color: #f0f0f0; }
	
	@media print {
        form, button, .no-print {
            display: none !important;
        }
        table {
            page-break-inside: avoid;
        }
    }
    </style>

        <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
            <div class="col-lg-12">
                <!-- small box -->
				
				<div class="card">
              
              <!-- /.card-header -->
              <div class="card-body">
          <div style="margin: 1rem 0;">

			  
			  @php
    $start = request('start_date') ?? now()->subMonths(5)->startOfMonth()->toDateString();
    $end = request('end_date') ?? now()->toDateString();
@endphp

<a href="{{ route('attendancesheet.pdf', ['start_date' => $start, 'end_date' => $end,'group_id'=>request('group_id'),'class_period'=>request('class_period')]) }}"  class="btn btn-warning"target="_blank">
  Print
</a>
</div>
				   <form method="GET" action="">
					   <div class="form-row">
    <div class="form-group col-md-2">
       <label for="start_date">Start Date:</label>
        <input class="form-control" type="date" name="start_date" id="start_date" value="{{ request('start_date') }}">
    </div>
    <div class="form-group col-md-2">
      <label for="end_date">End Date:</label>
        <input class="form-control" type="date" name="end_date" id="end_date" value="{{ request('end_date') }}">
    </div>   
		   
						   
						   <div class="form-group col-md-2">
      <label for="end_date">Course:</label>
							   	<select class="form-control select2bs4" name="group_id" id="atn_group_id">
       <option value="">All</option>
       <?php if($activeGroups->count() > 0){
		foreach($activeGroups as $group){
		?>
				
                          <option value="{{ $group->id }}" @selected(request('group_id') == $group->id)>
    {{ $group->name }}
</option>
                     	   
		<?php }} ?>
									   </select>	
    </div>
						   
	 <div class="form-group col-md-2">
      <label for="end_date">Period:</label>
							   	<select class="form-control select2bs4" name="class_period" id="class_period">
       <option value="">Both Period</option>
    	<option value="Period 1" @selected(request('class_period') == 'Period 1')>
   Period 1
</option>
									<option value="Period 2" @selected(request('class_period') == 'Period 2')>
   Period 2
</option>

									   </select>	
    </div>					   
						   
						   <div class="form-group col-md-1"> <label for="filterbtn">&nbsp;</label><button type="submit" class="btn btn-block btn-secondary" id="filterbtn">Filter</button></div>
						    <div class="form-group col-md-1"> <label for="filterbtn">&nbsp;</label><a href="{{ route('attendance.sheet') }}" class="btn btn-block btn-default" id="filterbtn">Reset</a></div>
  </div>
					   
       

      

        
    </form>
				  
					</div></div>	
				<div class="card">
         
              <!-- /.card-header -->
              <div class="card-body">
  <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table-head-fixed text-nowrap">
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Name</th>
                @foreach ($dates as $date)
                    <th <?php if(count($periodMap)==2){ echo 'colspan="2"'; } ?> class="date-header">
                        {{ \Carbon\Carbon::parse($date)->format('M d') }}
                    </th>
                @endforeach
            </tr>
            <tr>
                @foreach ($dates as $date)
                    @foreach ($periodMap as $period => $label)
                        <th>{{ $label }}</th>
                    @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($students as $student)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $student['name'] }}</td>
                    @foreach ($dates as $date)
                        @foreach ($periodMap as $period => $label)
                            @php
                                $status = $student['data'][$date][$period] ?? 'NA';
                                $class = match($status) {
                                    'P' => 'present',
                                    'A' => 'absent',
                                    'EXC' => 'excused',
                                    default => 'na'
                                };
                            @endphp
                            <td class="{{ $class }}">{{ $status }}</td>
                        @endforeach
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
 </div>
				</div></div>  
				  
            </div>
      
</div>

    </div>

    @include('_message')

           
            <!-- /.row -->
            <!-- Main row -->
</section>


 @endsection
@extends('layouts.app')
@section('main-container')

@php
    // Existing fee/attendance calculations...
    $stats7    = $stats7   ?? [];
    $statsYear = $statsYear ?? [];
    $statsAll  = $statsAll  ?? [];

    $stratum = $value->stratum ?? null;
    switch ($stratum) {
        case 1:
        case 2:
            $baseFee = 180000;
            break;
        case 3:
            $baseFee = 220000;
            break;
        case 4:
            $baseFee = 280000;
            break;
        default:
            $baseFee = 0;
    }

    $discount = 20000;
    $fullPaymentAmount = $baseFee - $discount;
    $installmentCount = 4;
    $installmentAmount = $baseFee / $installmentCount;
    $totalToPay = $baseFee;
    $totalPaid  = $fees->sum('amount_paid');
    $runningBalance = $totalToPay - $totalPaid;
    $lastBalance = $runningBalance;
@endphp



@if(isset($value->id))
<section class="content">
    <div class="container-fluid">
        <div class="row">

            {{-- Left sidebar --}}
            <div class="col-md-3">
                {{-- Profile Card --}}
                <div class="card card-secondary card-outline">
                    <div class="card-body box-profile text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{ $value->picture ? asset('storage/'.$value->picture) : 'https://via.placeholder.com/150' }}"
                             alt="User profile picture">
                        <h3 class="profile-username mt-3">{{ $value->first_name }} {{ $value->last_name }}</h3>
                        <p class="text-muted">{{ $value->role->role_name }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
							    @isset($value->id_no)
                                <li class="list-group-item"  style="text-align: left;">
                                    <b>ID</b> <span class="float-right">{{ $value->id_no }}</span>
                                </li>
                            @endisset
							
                            @isset($value->age)
                                <li class="list-group-item" style="text-align: left;">
                                    <b>Age</b> <span class="float-right">{{ $value->age }}</span>
                                </li>
                            @endisset
                            @isset($value->telephone)
                                <li class="list-group-item" style="text-align: left;">
                                    <b>Telephone</b> <span class="float-right">{{ $value->telephone }}</span>
                                </li>
                            @endisset
                            <li class="list-group-item" style="text-align: left;">
                                <b>Parent</b>
                                <span class="float-right">
                                    @if($parent)
                                        <a href="{{ route('admin.guardian.profile', $parent->uuid) }}">
                                            {{ $parent->first_name }} {{ $parent->last_name }}
                                        </a>
                                    @else
                                        Not found
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- About Me --}}
                <div class="card card-secondary">
                    <div class="card-header"><h3 class="card-title">About Me</h3></div>
                    <div class="card-body">
                        @isset($value->city)
                            <strong><i class="fas fa-city mr-1"></i> City</strong>
                            <p class="text-muted">{{ $value->city }}</p><hr>
                        @endisset
                        @isset($value->address)
                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                            <p class="text-muted">{{ $value->address }}</p><hr>
                        @endisset
                        @isset($value->notes)
                            <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>
                            <p class="text-muted">{{ $value->notes }}</p>
                        @endisset
                    </div>
                </div>
            </div>

            {{-- Main content tabs --}}
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link" href="#courses" data-toggle="tab">Courses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#grades" data-toggle="tab">Grades</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="#payments" data-toggle="tab">Payments</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#attendance" data-toggle="tab">Attendance</a>
                            </li>
                            @if(Auth::user()->uuid === $value->uuid)
                                <li class="nav-item">
                                  <a class="nav-link" href="#settings" data-toggle="tab">Settings</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">

                            {{-- Courses Tab --}}
                            <div class="tab-pane" id="courses">
                                @if($courses->isNotEmpty())
                                    <h5>Enrolled Courses:</h5>
                                    <table class="table table-bordered table-hover mt-3">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Course Name</th>
                                                <th>Class</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($courses as $course)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $course->course_name }}</td>
                                                    <td>{{ $groups[$course->pivot->group_id]->name ?? 'N/A' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <h5>No courses enrolled. Please <a href="{{ route('admin.registration.create') }}">register</a>.</h5>
                                @endif
                            </div>

                            {{-- Grades Tab --}}
                            <div class="tab-pane" id="grades">
                                @if($grades->isEmpty())
                                    <div class="alert alert-info">
                                        No grades found for this student.
                                    </div>
                                @else
                                    <h5>All Grades:</h5>
                                    <table id="studentGradesTable" class="table table-bordered table-hover mt-3">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Course</th>
                                                <th>Type</th>
                                                <th>Percentage</th>
                                                <th>Grade Level</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                     <tbody>
    <?php
        $typeTotals = [
            'test' => ['sum' => 0, 'count' => 0],
            'classwork' => ['sum' => 0, 'count' => 0],
            'homework' => ['sum' => 0, 'count' => 0],
            'other' => ['sum' => 0, 'count' => 0],
        ];
										 
										 
    ?>

    @foreach($grades as $grade)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ \Carbon\Carbon::parse($grade->date)->format('M d, Y') }}</td>
            <td>{{ $grade->course->course_name }}</td>
            <td class="text-capitalize">{{ $grade->assessment_type }}</td>
            <td>
                <?php
                    $calculateOriginalGrade = $grade->grade;

                    $type = $grade->assessment_type;
                    if (!isset($typeTotals[$type])) {
                        $type = 'other';
                    }

                    $typeTotals[$type]['sum'] += $calculateOriginalGrade;
                    $typeTotals[$type]['count']++;
                ?>
                {{ $grade->percentage }}%
            </td>

            <td>{{ $grade->grade }}</td>

            <td>
                @if($calculateOriginalGrade >= 3.5)
                    <span class="badge badge-success">Pass</span>
                @else
                    <span class="badge badge-danger">Fail</span>
                @endif
            </td>
        </tr>
    @endforeach
</tbody>

<tfoot>
    <?php
	$grandTotalAvg = 0;
	$finalGrandAvgofStd = 0;
	//echo '<pre>'; print_r($typeTotals); exit;
        foreach ($typeTotals as $type => $data) {
            //echo "<tr><td colspan='7'><strong>" . ucfirst($type) . " Average:</strong> ";
			
			
            if ($data['count'] > 0) {
                $avg = round($data['sum'] / $data['count'], 2);
            } else {
                $avg = 0;
            }
			//tests 4.5 x 60% = 2.7  Classwork 5 x 30% = 1.5  Homework 5 x 10% = .5   2.7 + 1.5 + .5 = 4.7
				if($type=='test'){
						$finalAvg =  $avg*0.60;
					//echo $finalAvg.' = fa<br>';
				 }elseif($type=='classwork'){
						$finalAvg =  $avg*0.30;	
					//echo $finalAvg.' = cw<br>';
				 }elseif($type=='homework'){
						$finalAvg =  $avg*0.10;	
					//echo $finalAvg.' = hm<br>';
				 }elseif($type=='other'){
						$finalAvg =  0;	
					//echo $finalAvg.' = hm<br>';
				 }

 
			$finalGrandAvgofStd = $finalGrandAvgofStd + $finalAvg;
        }
    ?>
	 	 <tr>
                                                <td>&nbsp;</td>
                                                 <td>&nbsp;</td>
                                                 <td>&nbsp;</td>
                                                 <td>&nbsp;</td>
                                                 <td>Final Grade(s)</td>
                                                 <td>{{round($finalGrandAvgofStd,1)}}</td>
                                                <td>&nbsp;</td>
                                            </tr>
</tfoot>


                                    </table>
                                @endif
                            </div>


                            {{-- Payments Tab --}}
                            <div class="tab-pane active" id="payments">
                                @if($baseFee > 0)
                                    @if(session('success'))
                                        <script>Swal.fire({ icon:'success', title:'Success', text:'{{ session('success') }}' });</script>
                                    @endif
                                    @if(session('error'))
                                        <script>Swal.fire({ icon:'error', title:'Error', text:'{{ session('error') }}' });</script>
                                    @endif

                                    @php
                                        // Recalculate base values
                                        $totalPaid = $fees->sum('amount_paid');

                                        // Determine actual amount-to-pay depending on payment type used
                                        $firstPaymentOption = $fees->first()->amount_to_pay ?? null;

                                        // If the student paid using discounted total (full payment), use that
                                        if ($firstPaymentOption == $fullPaymentAmount) {
                                            $displayTotalFee = $fullPaymentAmount;
                                        } else {
                                            // Else assume installment (full base fee)
                                            $displayTotalFee = $baseFee;
                                        }

                                        $displayBalance = $displayTotalFee - $totalPaid;
                                    @endphp


							<?php if($custom_amount){
										$displayBalance = floatval($amountToPay)-floatval($totalPaid); 
								}
							?>
								
								
                                    <div class="mb-4">
                                        <h5>Tuition Fee for Stratum {{ $stratum }}:</h5>
                                        <ul>
                                            <li>Full Payment: <strong>${{ number_format($fullPaymentAmount,2) }}</strong> (${{ number_format($discount,2) }} discount)</li>
                                            <li>4 Installments Total: <strong>${{ number_format($baseFee,2) }}</strong> (<em>4 × ${{ number_format($installmentAmount,2) }} each</em>)</li>
                                        </ul>
                                    </div>

                                    <script>
                                    function syncDisplays(amountToPay) {
                                        const totalPaid = parseFloat("{{ $totalPaid }}");
                                        const balance   = amountToPay - totalPaid;
                                        document.getElementById('totalFeeDisplay').innerText     = '$' + amountToPay.toLocaleString(undefined,{minimumFractionDigits:2});
                                        document.getElementById('totalBalanceDisplay').innerText = '$' + balance.toLocaleString(undefined,{minimumFractionDigits:2});
                                    }
                                    </script>
                                   

                                    @if($fees->isEmpty())
                                        <form method="POST" action="{{ route('admin.student.deposit') }}" id="initialPaymentForm" class="mb-4">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $value->id }}">
                                            <input type="hidden" name="amount_to_pay" id="amountToPay" value="{{ $displayTotalFee }}">
                                            <input type="hidden" name="balance"       id="balance"     value="{{ $displayTotalFee }}">

                                            <div class="form-row">
                                                <div class="form-group col-md-3">
                                                    <label>Payment Option</label>
                                                    <select name="payment_option" id="paymentOption" class="form-control" required>
                                                        <option value="full" data-amount="{{ $fullPaymentAmount }}">Full payment</option>
                                                        <option value="installment" data-amount="{{ $baseFee }}">4 Installments</option>
														
														<option value="custom amount" data-amount="0">Custom Amount</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Amount to Pay</label>
                                                    <input type="text" id="displayAmount" class="form-control"
                                                        value="${{ number_format($displayTotalFee,2) }}" readonly>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Month</label>
                                                    <input type="month" name="month" class="form-control" required>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Amount Paid</label>
                                                    <input type="number" name="amount_paid" step="0.01" class="form-control" required>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Payment Date</label>
                                                    <input type="date" name="payment_date" class="form-control" required>
                                                </div>
                                            </div>

                                            <button class="btn btn-outline-secondary">
                                                <i class="fas fa-save"></i> Add Payment
                                            </button>
                                        </form>

                                        <script>
                                        document.getElementById('paymentOption').addEventListener('change', function() {
                                            const selected = this.selectedOptions[0];
                                            const amount = Number(selected.dataset.amount);
											
											if(selected.value=='custom amount'){
												
												document.querySelector('#displayAmount').readOnly = false;
											}else{
												document.querySelector('#displayAmount').readOnly = true;
											}

                                            if (!isNaN(amount)) {
                                                document.getElementById('amountToPay').value = amount;
                                                document.getElementById('balance').value = amount;
                                                document.getElementById('displayAmount').value = '$' + amount.toLocaleString('en-US', { minimumFractionDigits: 2 });
                                                document.getElementById('totalFeeDisplay').textContent = '$' + amount.toLocaleString('en-US', { minimumFractionDigits: 2 });
                                                document.getElementById('balanceDisplay').textContent = '$' + amount.toLocaleString('en-US', { minimumFractionDigits: 2 });
                                            } else {
                                                alert("Amount to pay could not be determined. Please contact support.");
                                            }
                                        });
											
										 document.getElementById('displayAmount').addEventListener('keyup', function() {
												const daValue =  this.value = this.value.replace(/^\$/, '');
												 if (!isNaN(daValue)) {
                                                document.getElementById('amountToPay').value = daValue;
                                                document.getElementById('balance').value = daValue;
												 }else{
													  alert("Amount to pay could not be determined. Please contact support.");
												 }
											});
											
											
                                        window.addEventListener('DOMContentLoaded', () => {
                                            document.getElementById('paymentOption').dispatchEvent(new Event('change'));
                                        });
                                        </script>

                                    @elseif($displayBalance > 0)
                                        <button id="showRemaining" class="btn btn-outline-primary mb-2">
                                            <i class="fas fa-money-check-alt"></i> Make Remaining Payment
                                        </button>

                                        <form method="POST" action="{{ route('admin.student.deposit') }}" id="remainingPaymentForm" class="mb-4" style="display:none;">
                                            @csrf
											
											
											  <input type="hidden" name="payment_option" id="payment_option22" value="{{ $custom_amount }}">
											
                                            <input type="hidden" name="user_id"       value="{{ $value->id }}">
                                            <input type="hidden" name="amount_to_pay" id="remainingAmount" value="{{ $displayBalance }}">
                                            <input type="hidden" name="balance"       id="remainingBalance" value="{{ $displayBalance }}">

                                            <div class="form-row">
                                                <div class="form-group col-md-3">
                                                    <label>Month</label>
                                                    <input type="month" name="month" class="form-control" required>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Amount to Pay</label>
                                                    <input type="text" id="displayRemaining" class="form-control"
                                                        value="${{ number_format($displayBalance,2) }}" readonly>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Amount Paid</label>
                                                    <input type="number" name="amount_paid" step="0.01" class="form-control" required>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Payment Date</label>
                                                    <input type="date" name="payment_date" class="form-control" required>
                                                </div>
                                            </div>

                                            <button class="btn btn-success">
                                                <i class="fas fa-check"></i> Confirm Payment
                                            </button>
                                        </form>

                                        <script>
                                        document.getElementById('showRemaining').addEventListener('click', function(e){
                                            e.preventDefault();
                                            const form = document.getElementById('remainingPaymentForm');
                                            form.style.display = form.style.display==='none'?'block':'none';
                                            const amt = parseFloat(document.getElementById('remainingAmount').value);
                                            syncDisplays(amt);
                                        });
                                        </script>
                                    @endif

                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Month</th>
                                                <th class="text-right">To Pay ($)</th>
                                                <th class="text-right">Paid ($)</th>
                                                <th class="text-right">Balance ($)</th>
                                                <th>Payment Date</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($fees as $fee)
                                                <tr>
                                                    <td>{{ date('M Y', strtotime($fee->month.'-01')) }}</td>
                                                    <td class="text-right">${{ number_format($fee->amount_to_pay,2) }}</td>
                                                    <td class="text-right">${{ number_format($fee->amount_paid,2) }}</td>
                                                    <td class="text-right">${{ number_format($fee->balance,2) }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($fee->payment_date)->format('M d, Y') }}</td>
                                                    <td class="text-center">
                                                        <button class="btn btn-outline-danger btn-sm btn-delete-fee" data-url="{{ route('admin.student.deposit.delete', $fee->uuid) }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="mt-4">
										
									<?php if($custom_amount){ ?>
										<table class="table">
                                            <tr><th>Total Fee</th><th>Total Paid</th><th>Balance</th></tr>
                                            <tr>
                                                <td >${{ number_format($amountToPay, 2) }}</td>
                                                <td >${{ number_format($totalPaid, 2) }}</td>
                                                <td>${{ number_format($displayBalance, 2) }}</td>
                                            </tr>
                                        </table>
										<?php }else{ ?>	
										
                                        <table class="table">
                                            <tr><th>Total Fee</th><th>Total Paid</th><th>Balance</th></tr>
                                            <tr>
                                                <td id="totalFeeDisplay">${{ number_format($displayTotalFee, 2) }}</td>
                                                <td id="totalPaidDisplay">${{ number_format($totalPaid, 2) }}</td>
                                                <td id="balanceDisplay">${{ number_format($displayBalance, 2) }}</td>
                                            </tr>
                                        </table>
										
										<?php } ?>
										<div class="row">
												<div class="form-group col-md-2" style="padding-top: 32px;">
													  <label>&nbsp;</label>
                                        <a href="{{ route('admin.student.receipt',$value->uuid) }}" class="btn btn-default print-receipt-jay-cls" target="_blank">
                                            <i class="fas fa-print"></i> Print Receipt
                                        </a>
										    </div>
										<div class="form-group col-md-8">
                                                    <label>Invoice Date</label>
                                                    <input type="date" name="payment_print_date" id="payment_print_date" class="form-control">
                                                </div>
										
										</div>
                                    </div>

                                    <script>
                                    document.querySelectorAll('.btn-delete-fee').forEach(btn=>{
                                        btn.addEventListener('click',()=>{
                                            const url = btn.dataset.url;
                                            Swal.fire({
                                                title: 'Delete payment?',
                                                text: 'This action cannot be undone.',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonText: 'Yes, delete',
                                                cancelButtonText: 'Cancel'
                                            }).then(res=>{
                                                if (res.isConfirmed) window.location.href = url;
                                            });
                                        });
                                    });
                                    </script>

                                @else
                                    <span class="badge badge-warning">Invalid or unset stratum.</span>
                                @endif
                            </div>



                             {{-- Attendance Tab --}}
                            <div class="tab-pane" id="attendance">
                                {{-- unchanged attendance content --}}
                                <ul class="nav nav-pills mb-3" id="attend-tab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#attend-7days">Last 7 Days</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#attend-1year">Last 1 Year</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#attend-all">Overall</a></li>
                                </ul>
                                <div class="tab-content" id="attend-tabContent">
                                    <div class="tab-pane fade show active" id="attend-7days" role="tabpanel">
                                        <table class="table table-bordered text-center">
                                            <tr><th>Present</th><th>Absent</th><th>Excused</th><tr>
                                            <tr><td>{{ $stats7['Present'] ?? 0 }}</td><td>{{ $stats7['Absent'] ?? 0 }}</td><td>{{ $stats7['Excused'] ?? 0 }}</td><tr>
                                        </table>
										
											@include('admin.attendance.partials.studentattn', [
												'students' => $lastSevenDays,
												'dates' => array_keys(collect($lastSevenDays)->flatMap(fn($s) => $s['data'] ?? [])->toArray()),
												'periodMap' => $periodMap
											])
										
										
                                      <!--  <canvas id="chart7days" width="150" height="150"></canvas>-->
                                    </div>
                                    <div class="tab-pane fade" id="attend-1year" role="tabpanel">
                                        <table class="table table-bordered text-center">
                                            <tr><th>Present</th><th>Absent</th><th>Excused</th></tr>
                                            <tr><td>{{ $statsYear['Present'] ?? 0 }}</td><td>{{ $statsYear['Absent'] ?? 0 }}</td><td>{{ $statsYear['Excused'] ?? 0 }}</td></tr>
                                        </table>
										
											@include('admin.attendance.partials.studentattn', [
												 'students' => $lastYear,
												 'dates' => array_keys(collect($lastYear)->flatMap(fn($s) => $s['data'] ?? [])->toArray()),
												'periodMap' => $periodMap
											])
										
                                        <!--<canvas id="chart1year" width="150" height="150"></canvas>-->
                                    </div>
                                    <div class="tab-pane fade" id="attend-all" role="tabpanel">
                                        <table class="table table-bordered text-center">
                                            <tr><th>Present</th><th>Absent</th><th>Excused</th></tr>
                                            <tr><td>{{ $statsAll['Present'] ?? 0 }}</td><td>{{ $statsAll['Absent'] ?? 0 }}</td><td>{{ $statsAll['Excused'] ?? 0 }}</td></tr>
                                        </table>
										
											@include('admin.attendance.partials.studentattn', [
												'students' => $overall,
												'dates' => array_keys(collect($overall)->flatMap(fn($s) => $s['data'] ?? [])->toArray()),
												'periodMap' => $periodMap
											])
                                        <!--<canvas id="chartAll" width="150" height="150"></canvas>-->
                                    </div>
                                </div>
                            <!--    <script>
                                    const makeChart = (ctxId,dataObj) => {
                                        new Chart(document.getElementById(ctxId).getContext('2d'),{
                                            type:'pie',
                                            data:{labels:['Present','Absent','Excused'],datasets:[{data:[dataObj.Present||0,dataObj.Absent||0,dataObj.Excused||0]}]}
                                        });
                                    };
                                    document.addEventListener('DOMContentLoaded',()=>{makeChart('chart7days',@json($stats7));makeChart('chart1year',@json($statsYear));makeChart('chartAll',@json($statsAll));});
                                </script>-->
                            </div>

                            {{-- Settings Tab --}}
                            @if(Auth::user()->uuid === $value->uuid)
                                <div class="tab-pane" id="settings">
                                    <form action="{{ route('admin.student.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf @method('PUT')
                                        {{-- Your edit fields --}}
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


@else
<section class="content">
    <div class="error-page text-center">
        <h2 class="headline text-warning">404</h2>
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Student not found.</h3>
            <p><a href="{{ route('admin.dashboard') }}">Return to Dashboard</a></p>
        </div>
    </div>
</section>
@endif
<script>

$(document).on('change', '#payment_print_date', function() {
    let selectedDate = $(this).val(); // get selected date
    let uuid = "{{ $value->uuid }}";  // Laravel UUID from blade

    // Base route without query string
    let url = "{{ route('admin.student.receipt', ':uuid') }}"; 
    url = url.replace(':uuid', uuid);

    // Append query parameter
    url += "?invoice_date=" + selectedDate;

    // Update the link href
    $('.print-receipt-jay-cls').attr('href', url);
});
</script>

@endsection

@extends('layouts.app')

@section('main-container')
<div class="container-fluid">

  {{-- Filter Panel --}}
  <div class="card border mb-4">
    <div class="card-header bg-light">
      <strong>Filter Fee Report</strong>
    </div>
    <div class="card-body">
      <form method="GET" class="form-inline">

        {{-- Year --}}
        <div class="form-group mr-3 mb-2">
          <label class="mr-2">Year</label>
          <select name="year" class="form-control">
            <option value="">All</option>
            @foreach(range(date('Y'), date('Y') - 10) as $y)
              <option value="{{ $y }}" {{ (string)$year === (string)$y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
          </select>
        </div>

        {{-- Month --}}
        <div class="form-group mr-3 mb-2">
          <label class="mr-2">Month</label>
          <select name="month" class="form-control">
            <option value="">All</option>
            @foreach(range(1, 12) as $m)
              <option value="{{ $m }}" {{ (string)$month === (string)$m ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group mr-3">
            <label class="mr-2">Mode</label>
            <select name="mode" class="form-control">
                <option value="">All</option>
                <option value="full" {{ $mode == 'full' ? 'selected' : '' }}>Full</option>
                <option value="installment" {{ $mode == 'installment' ? 'selected' : '' }}>Installment</option>
            </select>
        </div>

        {{-- From Date --}}
        <div class="form-group mr-3 mb-2">
          <label class="mr-2">From</label>
          <input type="date" name="from" value="{{ $dateFrom }}" class="form-control">
        </div>

        {{-- To Date --}}
        <div class="form-group mr-3 mb-2">
          <label class="mr-2">To</label>
          <input type="date" name="to" value="{{ $dateTo }}" class="form-control">
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn btn-primary mb-2">Apply</button>
      </form>
    </div>
  </div>

  {{-- Summary Tiles --}}
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card border text-center">
        <div class="card-body">
          <h6><strong>Total Registrations</strong> <br>(Under Registration Tab)</h6>
          <h3>{{ $totalRegs }}</h3>
        </div>
      </div>
    </div>
    @foreach($summary as $str => $sum)
      <div class="col-md-3">
        <div class="card border">
          <div class="card-header">Stratum {{ $str }}</div>
          <div class="card-body">
            <p class="mb-1"><strong>Registrations:</strong> {{ $sum['registrations'] }}</p>
            <p class="mb-1"><strong>Full:</strong> ${{ number_format($sum['billed_discount']) }}</p>
            <p class="mb-1"><strong>4 Installments:</strong> ${{ number_format($sum['billed_install']) }}</p>
            <p class="mb-1"><strong>Paid:</strong> ${{ number_format($sum['paid']) }}</p>
            <p class="mb-0"><strong>Outstanding:</strong> ${{ number_format($sum['outstanding_inst']) }}</p>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Detailed Table --}}
  <div class="card border p-2">
    <div class="card-header bg-light">
      <strong>Fee Details</strong>
    </div>
    <div class="card-body p-0">
     <div class="table-responsive">
      <table id="fee-report" class="table table-bordered table-hover mb-0">
        <thead class="thead-light">
          <tr>
            <th>#</th>
            <th>Student</th>
            <th>Stratum</th>
            <th>Group</th>
            <th>Mode</th>
            <th>Total Due</th>
            <th>Paid</th>
            <th>Outstanding</th>
            <th>Last Payment</th>
          </tr>
        </thead>
        <tbody>
			<?php 
					$total_to_pay = 0;
					$total_paid = 0;
					$total_balance = 0;
			?>
          @foreach($reportArray as $i => $r)
			
			<?php
			
					$total_to_pay = $total_to_pay + floatval($r['total_due']);
					$total_paid = $total_paid + floatval($r['paid']);
					$total_balance = $total_balance + floatval($r['bal_inst']);
			
			?>
			
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{!! $r['student'] !!}</td>
            <td>{{ $r['stratum'] }}</td>
            <td>{{ $r['group_name'] }}</td>
            <td>{{ ucfirst($r['mode']) }}</td>
            <td>${{ number_format($r['total_due']) }}</td>
            <td>${{ number_format($r['paid']) }}</td>
            <td>
				
                ${{ number_format($r['total_due']-$r['paid']) }}
          
            </td>
            <td>{{ $r['last_date'] ?: '—' }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
			
          <tr class="font-weight-bold">
            <td colspan="5" class="text-right">Totals:</td>
            <td>${{ number_format($total_to_pay) }}</td>
            <td>${{ number_format($total_paid ) }}</td>
            <td>${{ number_format($total_to_pay-$total_paid) }}</td>
            <td>&nbsp;</td>
          </tr>
        </tfoot>
      </table>
     </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
  $(function(){
    $('#fee-report').DataTable({
      dom: "l" +
      "<'row mb-2'<'col-md-6'B><'col-md-6'f>>" + 
         "<'row'<'col-sm-12'tr>>" + 
         "<'row'<'col-sm-5'i><'col-sm-7'p>>",
          footer: true, // enable footer support
          pageLength: -1,
          lengthMenu: [[10, 50, 100, -1], [10, 50, 100, "All"]],
          buttons: [
            {
              extend: 'copy',
              footer: true
            },
            {
              extend: 'csv',
              footer: true
            },
            {
              extend: 'excel',
              footer: true
            },
            {
              extend: 'pdf',
              footer: true
            },
            {
              extend: 'print',
              footer: true
            }
          ],
          responsive: true,
          paging: true,
          ordering: true,
          info: true,
          
          searching: true,
    });
  });
</script>

@push('styles')
<style>
  div.dataTables_wrapper .dt-buttons,
  div.dataTables_wrapper .dataTables_filter {
    padding-top: 13px; /* adjust this value as needed */
    padding-left: 5px;
    padding-right: 5px;
  }
</style>
@endpush

@endpush


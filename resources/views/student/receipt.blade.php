<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>STEM Foundation | Payment Receipt</title>

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- AdminLTE Theme -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">

    <style>
        body { font-size: 14px; line-height: 1.6; }
        .invoice { padding: 20px; border: 1px solid #dee2e6; border-radius: 8px; background: #fff; margin-top: 20px; }
        .invoice h2, .invoice h5 { font-weight: 600; }
        .table th, .table td { vertical-align: middle; }
        .invoice-info address { line-height: 1.5; }
        .table th { background-color: #f8f9fa; }
        .summary-box { background: #f1f1f1; padding: 15px; border-radius: 6px; }
        .payment-note { font-weight: bold; color: #dc3545; text-align: center; margin-top: 20px; }
        @media print {
            .page-header { border-bottom: 1px solid #ccc; margin-bottom: 20px; padding-bottom: 10px; }
        }
    </style>
</head>
<body>

@php
    // Determine base fee per stratum
    switch($value->stratum) {
        case 1: case 2: $baseFee = 180000; break;
        case 3:          $baseFee = 220000; break;
        case 4:          $baseFee = 280000; break;
        default:         $baseFee = 0;
    }

    $discount = 20000;
    $fullPaymentAmount = $baseFee - $discount;

    // Calculate total expected payment (sum of all amount_to_pay)
    $totalPaid = $fees->sum('amount_paid');

    // Determine actual amount-to-pay based on payment method
    $firstPaymentOption = $fees->first()->amount_to_pay ?? null;

    if ($firstPaymentOption == $fullPaymentAmount) {
        // Paid full upfront (with discount)
        $totalExpected = $fullPaymentAmount;
    } else {
        // Paying in installments
        $totalExpected = $baseFee;
    }

    $balance = max(0, $totalExpected - $totalPaid);

@endphp


<div class="container">
    <section class="invoice">
        <!-- Header -->
        <div class="row">
            <div class="col-12 d-flex align-items-center">
                <img src="{{ url('dist/img/AdminLTELogo.png') }}" alt="Logo"
                     class="img-circle elevation-3 mr-3" style="width:50px; height:50px;">
                <div>
                    <h2 class="mb-0">STEM FOUNDATION</h2>
                    <small class="text-muted">Colombia</small>
                </div>
                <div class="ml-auto text-right">
                    <strong>Fecha/Hora:</strong> {{ $currentDate->format('l, M d, Y h:i A') }}
                </div>
            </div>
        </div>

        <hr>

        <!-- Info -->
        <div class="row invoice-info mt-3 mb-4">
            <div class="col-sm-4">
                <strong>De:</strong>
                <address>
                    STEM Foundation<br>
                    Colombia
                </address>
            </div>
            <div class="col-sm-4">
                <strong>Para:</strong>
                <address>
                    {{ $value->first_name }} {{ $value->last_name }}<br>
                    {{ $value->address }}<br>
                    Tel: {{ $value->telephone }}<br>
                    ID Estudiante: {{ $value->id_no }}
                </address>
            </div>
            <div class="col-sm-4 text-right">
                <strong>Factura #:</strong> 00{{ $value->id }}<br>
                <strong>Vence:</strong>
				<?php 
					$today = new DateTime();
$formatted = DateTime::createFromFormat('Y-m-d', $today->format('Y-m-15'))
    ->format('M d, Y');
				
				echo $formatted;
				?>
				
				<!--{{ $lastDate->format('M d, Y') }}-->
            </div>
        </div>

        <!-- Late Payment Notice -->
        <div class="payment-note">
            Después del día 15 del mes: $5,000 por mora
        </div>

        <!-- Payment History -->
        <div class="row mt-4">
            <div class="col-12">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mes</th>
                            <th>Cuota</th>
                            <th>Pagado</th>
                            <th>Saldo</th>
                            <th>Fecha Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($fees as $fee)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ date('M-Y', strtotime($fee->month . '-01')) }}</td>
                            <td>${{ number_format($fee->amount_to_pay, 2) }}</td>
                            <td>${{ number_format($fee->amount_paid, 2) }}</td>
                            <td>${{ number_format($fee->balance, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($fee->payment_date)->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bank Details & Summary -->
        <div class="row mt-4">
            <!-- Bank Instructions -->
            <div class="col-md-6">
                <h5><strong>Detalles de Transferencia</strong></h5>
                <p class="text-muted">
                    <strong>Bancamia</strong><br>
                    STEM Foundation Colombia<br>
                    NIT: 901378346<br>
                    Cuenta Ahorros: 3990065633900011
                </p>
                <p class="text-muted">
                 <!--   <strong>Bancolombia Corresponsal:</strong> Convenio 73182 - Depósito NIT 901378346<br>
                    <strong>Nequi / Daviplata:</strong> 301 796 0580<br>-->
                    <strong>PuntoRed:</strong> Depósito Bancamia 901378346
                </p>
            </div>

            <!-- Payment Summary -->
            <div class="col-md-6">
                <h5 class="mb-3"><strong>Resumen del Pago</strong></h5>
                <div class="summary-box">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width:60%">Total Matrícula:</th>
                            <td>${{ number_format($totalExpected, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Total Pagado:</th>
                            <td>${{ number_format($totalPaid, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Saldo Restante:</th>
                            <td>${{ number_format($balance, 2) }}</td>
                        </tr>
                    </table>

                    <p class="mt-3 text-right">
                        <strong>Fecha Límite:</strong> {{$formatted}}
						
						
						
						<!--{{ $lastDate->format('M d, Y') }}-->
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Auto-print script -->
<script>
    window.addEventListener("load", function () {
        window.print();
    });
</script>

</body>
</html>

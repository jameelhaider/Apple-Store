@extends('dashboard.master2')
@php
    $title = 'View Details | ' . $account->customer_name;
@endphp
@section('admin_title', $title)
@section('content2')
    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a href="{{ url('admin/accounts') }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-6 col-sm-8">
                    <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">
                        {{ 'View Details | ' . $account->customer_name }}</h3>

                    <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                        {{ 'View Details | ' . $account->customer_name }}</h5>
                </div>
            </div>
        </div>

        <style>
            .custom-back-button {
                font-size: 16px;
                height: 100%;
                width: 100%;
                border-radius: 0;
                text-decoration: none;
                transition: all 0.3s ease;
                font-weight: 500;
            }

            .custom-back-button:hover {
                background-color: #314861;
            }

            .custom-back-button i {
                font-size: 18px;
            }
        </style>


        <style>
            .nav-tabs .nav-link.active {
                background-color: #f5f5f5 !important;
                color: #000000 !important;

            }

            .nav-tabs .nav-link {
                background-color: #f5f5f5 !important;
                color: rgb(117, 113, 113) !important;
                margin-bottom: 1px;
            }
        </style>



        <!-- Centered Tabs -->
        <ul class="nav nav-tabs justify-content-center mt-2" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details"
                    type="button" role="tab" aria-controls="details" aria-selected="true">Details</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sale-invoices-tab" data-bs-toggle="tab" data-bs-target="#sale-invoices"
                    type="button" role="tab" aria-controls="sale-invoices" aria-selected="false">
                    Invoices</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cash-receiveds-tab" data-bs-toggle="tab" data-bs-target="#cash-receiveds"
                    type="button" role="tab" aria-controls="cash-receiveds" aria-selected="false">Cash
                    Receiveds</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="ledger-tab" data-bs-toggle="tab" data-bs-target="#ledger" type="button"
                    role="tab" aria-controls="ledger" aria-selected="false">Ledger</button>
            </li>
        </ul>

        <!-- Tabs Content inside Cards -->
        <div class="tab-content mt-1" id="myTabContent">

            {{-- Start Details Tab --}}
            <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                <div class="card shadow-sm p-3 rounded-0">
                    <h5 class="mb-3">Account Details</h5>

                    <div class="row g-3">
                        <!-- Account Info -->
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="border-bottom pb-2 mb-2">Account Information</h6>
                                <p><strong class="text-dark">Account ID:</strong> {{ $account->id }}</p>
                                <p><strong class="text-dark">Account Status:</strong>
                                    @if ($account->prev_balance > 0)
                                        <span class="badge" style="background-color: rgb(253, 27, 27)">
                                            Remainings
                                        </span>
                                    @elseif ($account->prev_balance == 0)
                                        <span class="badge" style="background-color: rgb(1, 149, 1)">
                                            Clear
                                        </span>
                                    @else
                                        <span class="badge" style="background-color: #6c757d">
                                            Credit
                                        </span>
                                    @endif
                                </p>




                                <p><strong class="text-dark">Current Balance:</strong>
                                    @if ($account->prev_balance > 0)
                                        <span class="fw-bold" style="color: rgb(253, 27, 27)">
                                            {{ ' Rs.' . number_format($account->prev_balance) }}
                                        </span>
                                    @elseif ($account->prev_balance == 0)
                                        <span class="fw-bold" style="color: rgb(1, 149, 1)">
                                            {{ ' Rs.' . number_format($account->prev_balance) }}
                                        </span>
                                    @else
                                        <span class="fw-bold" style="color: #6c757d">
                                            {{ ' Rs.' . number_format($account->prev_balance) }}
                                        </span>
                                    @endif
                                </p>
                                <p><strong class="text-dark">Customer Name:</strong> {{ $account->customer_name }}</p>
                                @if ($account->customer_address != null)
                                    <p><strong class="text-dark">Customer Address:</strong>
                                        {{ $account->customer_address }}</p>
                                @endif

                                <p><strong class="text-dark">Created Date:</strong>
                                    {{ \Carbon\Carbon::parse($account->created_at)->format('d M Y') }}</p>



                                   <hr>
                                <h5 class="text-dark fw-bold">Closing Balance</h5>
                                <hr>

                                <!-- New Stats -->
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Debit: (Stock Sale Invoices)</span>
                                    <strong class="text-dark">
                                        {{ 'Rs. ' . number_format($saleinvoices->sum('total_bill') ?? 0) }}
                                    </strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Credit: (Cash Receiveds)</span>
                                    <strong class="text-dark">
                                        {{ 'Rs. ' . number_format($cash->sum('ammount') ?? 0) }}
                                    </strong>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Current Balance:</span>
                                    <strong class="text-dark">
                                        {{ 'Rs. ' . number_format($account->prev_balance ?? 0) }}
                                    </strong>
                                </div>


                                 <hr>
                                <h5 class="text-dark fw-bold">Cash Receiveds</h5>
                                <hr>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Cash Receiveds:</span>
                                    <strong class="text-dark">{{ $cash->count() }} Times</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Nill Balance:</span>
                                    <strong class="text-dark">{{ $cash->where('final_rem', 0)->count() }} Times</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Not Nill Balance:</span>
                                    <strong class="text-dark">{{ $cash->where('final_rem', '>', 0)->count() }}
                                        Times</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Credit The Balance:</span>
                                    <strong class="text-dark">{{ $cash->where('final_rem', '<', 0)->count() }}
                                        Times</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Cash Receiveds:</span>
                                    @php
                                        $totalcashrec = $cash->sum('ammount');
                                    @endphp
                                    <strong class="text-dark">
                                        {{ 'Rs. ' . number_format($totalcashrec ?? 0) }}
                                    </strong>
                                </div>


                            </div>
                        </div>


                        @php
                            use Carbon\Carbon;
                            $totalSales = $saleinvoices->sum('total_bill') ?? 0;
                            $netSales = $totalSales;
                            $totalProfit = $saleinvoices->sum('profit') ?? 0;
                            $firstSaleDate = $saleinvoices->min('sold_date');
                            $lastSaleDate = $saleinvoices->max('sold_date');
                            if ($firstSaleDate && $lastSaleDate) {
                                $totalDays =
                                    Carbon::parse($firstSaleDate)->diffInDays(Carbon::parse($lastSaleDate)) + 1;
                            } else {
                                $totalDays = 1;
                            }
                            $avgDaily = $totalDays > 0 ? $netSales / $totalDays : 0;
                            $avgDailyRevenue = $totalDays > 0 ? $totalProfit / $totalDays : 0;
                        @endphp
                        <!-- Stats Summary -->
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="border-bottom pb-2 mb-3">Account Statistics</h6>










                                <hr>
                                <h5 class="text-dark fw-bold">Sale Stats</h5>
                                <hr>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Sale Invoices:</span>
                                    <strong class="text-dark">{{ $saleinvoices->count() }}</strong>
                                </div>


                                <!-- Sales Overview -->
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Sale:</span>
                                    <strong class="text-dark">{{ 'Rs. ' . number_format($totalSales) }}</strong>
                                </div>





                                <!-- Average Sales -->
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Average Daily Sale:</span>
                                    <strong class="text-dark">{{ 'Rs. ' . number_format($avgDaily) }}</strong>
                                </div>


                                <hr>
                                <h5 class="text-dark fw-bold mt-3">Revenue Stats</h5>
                                <hr>

                                <!-- Revenue (Profit) Stats -->
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Revenue (Profit):</span>
                                    <strong class="text-dark">{{ 'Rs. ' . number_format($totalProfit) }}</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Average Daily Revenue:</span>
                                    <strong class="text-dark">{{ 'Rs. ' . number_format($avgDailyRevenue) }}</strong>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Details Tab --}}



            {{-- Start Sale Invoices Tab --}}
            <div class="tab-pane fade" id="sale-invoices" role="tabpanel" aria-labelledby="sale-invoices-tab">
                <div class="card shadow-sm p-2 rounded-0">
                    @if ($saleinvoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                   <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Invoice Id</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Company</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Model</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Sale</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Sold To</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Date</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                            </tr>
                                </thead>
                                <tbody>

                                      @foreach ($saleinvoices as $key => $invoice)
                                <tr class="text-center">
                                    <td class="text-dark">{{ ++$key }}</td>
                                    <td>
                                        <a class="nav-link text-dark fw-bold"
                                            href="{{ route('invoice.view', ['id' => $invoice->id]) }}">
                                            {{ $invoice->invoice_id }}
                                        </a>
                                    </td>
                                    <td> <a class="nav-link text-dark"
                                            href="{{ route('invoice.view', ['id' => $invoice->id]) }}">
                                            {{ $invoice->company_name ? $invoice->company_name : '----------' }}
                                        </a>
                                    </td>
                                    <td class="fw-bold">
                                        <a class="nav-link text-dark"
                                            href="{{ route('invoice.view', ['id' => $invoice->id]) }}">
                                            {{ $invoice->model_name ? $invoice->model_name : '----------' }}
                                        </a>
                                    </td>
                                    <td class="text-dark fw-bold" style="font-size: 17px">
                                        {{ 'Rs.' . number_format($invoice->total_bill) }}
                                    </td>
                                    <td class="text-dark">
                                        {{ $invoice->buyer_name }}
                                        @if ($invoice->buyer_phone)
                                            <br>
                                            {{ $invoice->buyer_phone }}
                                        @endif
                                    </td>

                                    <td class="text-dark"
                                        title="{{ \Carbon\Carbon::parse($invoice->sold_date)->format('d M y') }}">
                                        {{ \Carbon\Carbon::parse($invoice->sold_date)->format('d M y') }}
                                    </td>


                                    <td>
                                        <div class="dropdown ms-auto">
                                            <button class="btn btn-dark btn-sm dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <a class="dropdown-item" target="_BLANCK"
                                                        href="{{ route('invoice.view', ['id' => $invoice->id]) }}">View
                                                        Invoice</a>
                                                </li>


                                            </ul>
                                        </div>
                                    </td>



                                </tr>
                            @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                       <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Invoice Id</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Company</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Model</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Sale</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Sold To</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Date</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                            </tr>
                                </thead>
                            </table>
                        </div>

                        <h4 class="h4 mt-2 text-center text-dark fw-bold">No Data Found!</h4>
                    @endif

                </div>
            </div>
            {{-- End Sale Invoices Tab --}}




            {{-- Start Cash Receiveds Tab --}}
            <div class="tab-pane fade" id="cash-receiveds" role="tabpanel" aria-labelledby="cash-receiveds-tab">
                <div class="card shadow-sm p-2 rounded-0">
                    @if ($cash->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">#</th>
                                        <th style="font-size:14px;width:28%" class="text-dark fw-bold">Customer Name</th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">
                                            Narration</th>
                                        <th style="font-size:14px;width:20%" class="text-dark fw-bold text-center">Amount
                                            Received
                                        </th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">R.A.R</th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Date
                                        </th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cash as $key => $csh)
                                        <tr>
                                            <td class="text-dark text-center">{{ ++$key }}</td>
                                            <td>
                                                <a class="text-dark fw-bold"
                                                    href="{{ route('cash.view.receipt', ['id' => $csh->id]) }}">
                                                    {{ $csh->customer_name }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a class="text-dark" href="{{ route('cash.edit', ['id' => $csh->id]) }}">
                                                    {{ $csh->narration }}
                                                </a>
                                            </td>
                                            <td class="text-dark text-center fw-bold">
                                                {{ 'Rs.' . number_format($csh->ammount) }}
                                            </td>
                                            <td class="text-dark text-center">
                                                @if ($csh->final_rem > 0)
                                                    <span class="fw-bolder" style="color: rgb(253, 27, 27);">
                                                        {{ 'Rs.' . number_format($csh->final_rem) }}</span>
                                                @elseif ($csh->final_rem < 0)
                                                    <span class="fw-bolder" style="color: #6c757d;">
                                                        {{ 'Rs.' . number_format($csh->final_rem) }}</span>
                                                @else
                                                    <span class="fw-bolder" style="color: rgb(1, 149, 1);">
                                                        {{ 'Rs.' . number_format($csh->final_rem) }}</span>
                                                @endif

                                            </td>

                                            <td class="text-dark text-center">
                                                {{ \Carbon\Carbon::parse($csh->created_at)->format('d M y') }}
                                            </td>





                                            <td>
                                                <div class="dropdown ms-auto">
                                                    <button class="btn btn-dark btn-sm dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <li>
                                                            <a class="dropdown-item" target="_Blank"
                                                                href="{{ route('cash.view.receipt', ['id' => $csh->id]) }}">View
                                                                Receipt</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" target="_Blank"
                                                                href="{{ route('cash.edit', ['id' => $csh->id]) }}">Edit</a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">#</th>
                                        <th style="font-size:14px;width:28%" class="text-dark fw-bold">Customer Name</th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">
                                            Narration</th>
                                        <th style="font-size:14px;width:20%" class="text-dark fw-bold text-center">Amount
                                            Received
                                        </th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">R.A.R</th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Date
                                        </th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <h4 class="h4 text-center fw-normal text-muted mt-2">No Data Found!</h4>
                    @endif
                </div>
            </div>
            {{-- End Cash Receiveds Tab --}}

            {{-- Start Ledger Tab --}}
            <div class="tab-pane fade" id="ledger" role="tabpanel" aria-labelledby="ledger-tab">
                <div class="card shadow-sm p-2 rounded-0">
                    @if ($ledgerEntries->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px;" class="text-dark fw-bold text-center">#</th>
                                        <th style="font-size:14px;" class="text-dark fw-bold">Date
                                        </th>
                                        <th style="font-size:14px;" class="text-dark fw-bold">Type
                                        </th>
                                        <th style="font-size:14px;" class="text-dark fw-bold">View | Description</th>
                                        <th style="font-size:14px;" class="text-dark fw-bold text-end">Debit
                                        </th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-end">Credit</th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-end">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $balance = 0; @endphp
                                    @foreach ($ledgerEntries as $key => $entry)
                                        @php
                                            $balance += $entry['debit'];
                                            $balance -= $entry['credit'];
                                        @endphp
                                        <tr>
                                            <td class="text-center text-dark">{{ ++$key }}</td>
                                            <td class="text-dark">
                                                {{ \Carbon\Carbon::parse($entry['date'])->format('d M y') }}</td>

                                            <td class="text-dark">{{ $entry['type'] }}</td>
                                            <td class="text-dark">
                                                @if ($entry['type'] === 'Invoice')
                                                    <a target="_BLANK" href="{{ route('invoice.view', $entry['id']) }}"
                                                        class="btn btn-primary btn-xs">
                                                        View | {{ $entry['type'] }} #{{ $entry['id'] }}
                                                    </a>
                                                @elseif ($entry['type'] === 'Return Invoice')
                                                    <a target="_BLANK"
                                                        href="{{ route('invoice.return.view', $entry['id']) }}"
                                                        class="btn text-white btn-xs"
                                                        style="background-color: rgb(255, 162, 0);">
                                                        View | {{ $entry['type'] }} #{{ $entry['id'] }}
                                                    </a>
                                                @elseif ($entry['type'] === 'Cash Received')
                                                    <a target="_BLANK"
                                                        href="{{ route('cash.view.receipt', $entry['id']) }}"
                                                        class="btn btn-xs text-white"
                                                        style="background-color: rgb(1, 149, 1);">
                                                        View | {{ $entry['type'] }} #{{ $entry['id'] }}
                                                    </a>
                                                @endif
                                            </td>

                                            <td class="text-end text-dark">{{ number_format($entry['debit'], 2) }}</td>
                                            <td class="text-end text-dark">{{ number_format($entry['credit'], 2) }}</td>
                                            <td class="text-end text-dark">{{ number_format($balance, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th colspan="4" class="text-end fw-bold text-dark">Closing Balance</th>
                                        <th class="text-end text-dark">
                                            {{ number_format($ledgerEntries->sum('debit'), 2) }}</th>
                                        <th class="text-end text-dark">
                                            {{ number_format($ledgerEntries->sum('credit'), 2) }}</th>
                                        <th class="text-end text-dark">{{ number_format($balance, 2) }}</th>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px;" class="text-dark fw-bold text-center">#</th>
                                        <th style="font-size:14px;" class="text-dark fw-bold">Date
                                        </th>
                                        <th style="font-size:14px;" class="text-dark fw-bold">Type
                                        </th>
                                        <th style="font-size:14px;" class="text-dark fw-bold">View | Description</th>
                                        <th style="font-size:14px;" class="text-dark fw-bold text-center">Debit
                                        </th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">Credit</th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">Balance</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <h4 class="h4 text-center fw-normal text-muted mt-2">No Data Found!</h4>
                    @endif
                </div>
            </div>
            {{-- End Ledger Tab --}}
        </div>








    </div>


@endsection

@extends('dashboard.master2')
@section('admin_title', 'Admin | Invoices')
@section('content2')

    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-6 col-md-4 col-sm-4">
                    <a href="{{ url('/admin') }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-home-circle me-2"></i> Dashboard
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-8 col-sm-8">
                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family: cursive;">Invoices</h3>
                        <h5 class="mt-1 d-block d-sm-block d-lg-none d-md-none" style="font-family: cursive;">Invoices</h5>

                    </div>
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
            .custom-back-button i {
                font-size: 18px;
            }
        </style>




        <div class="card mb-2 p-2 mt-2" >
            <form action="" method="GET">
                <div class="row">
                    <div class="col-lg-5 col-md-4 col-sm-4 col-6 mt-1 mb-1">
                        <input type="text" class="form-control" value="{{ request()->invoice_id }}"
                            placeholder="Invoice Id" name="invoice_id">
                    </div>

                    <div class="col-lg-5 col-md-4 col-sm-4 col-6 mt-1 mb-1">
                        <input type="date" class="form-control" value="{{ request()->date }}" name="date">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-4 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ url('admin/invoices') }}" title="Clear" class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>
                            <style>
                                .btn-outline-success:hover {
                                    background-color: #89c72d;
                                }

                                .btn-outline-danger:hover {
                                    background-color: #ef6347;
                                }
                            </style>

                        </div>
                    </div>
                </div>
            </form>
        </div>


        @if ($invoices->count() > 0 && (request()->invoice_id || request()->date))
            <div class="alert bg-primary text-white mt-3">
                <strong>{{ $invoices->count() }}
                    {{ $invoices->count() > 0 && $invoices->count() < 2 ? 'Result' : 'Results' }}
                    Found</strong>
            </div>
        @elseif ($invoices->count() < 1 && (request()->invoice_id || request()->date))
            <div class="alert bg-warning text-white mt-3">
                <strong>No Results Found !</strong>
            </div>
        @endif


        <div class="card p-2 mb-0" >
            @if ($invoices->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Invoice Id</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Company</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Model</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Profit</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Sale</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Sold To</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Date</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $key => $invoice)
                                <tr class="text-center">
                                    <td class="text-dark">{{ ++$key }}</td>
                                    <td>
                                        <a class="nav-link text-dark" href="">
                                            {{ $invoice->invoice_id }}
                                        </a>
                                    </td>
                                    <td> <a class="nav-link text-dark" href="">
                                            {{ $invoice->company_name ? $invoice->company_name : '----------' }}
                                        </a>
                                    </td>
                                    <td>
                                        <a class="nav-link text-dark" href="">
                                            {{ $invoice->model_name ? $invoice->model_name : '----------' }}
                                        </a>
                                    </td>
                                     <td class="text-dark">
                                            {{'Rs.'. number_format($invoice->profit) }}
                                    </td>
                                        <td class="text-dark">
                                            {{'Rs.'. number_format($invoice->total_bill) }}
                                    </td>
                                       <td class="text-dark">
                                            {{$invoice->buyer_name}}
                                            @if ($invoice->buyer_phone)
                                            <br>
                                            {{$invoice->buyer_phone}}
                                            @endif
                                    </td>

                                   <td class="text-dark" title="{{ \Carbon\Carbon::parse($invoice->sold_date)->format('d M y') }}">
    {{ \Carbon\Carbon::parse($invoice->sold_date)->format('d M y') }}
</td>


                                    <td>
                                            <a href=""
                                                class="btn btn-primary btn-sm" title="View Invoice"> View Invoice <i class="bx bx-show"></i></a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="float-end mt-2">
                        {{ $invoices->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
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
                                <th style="font-size:14px" class="text-dark fw-bold">Date</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <h3 class="h3 text-center text-dark fw-bold">No Data Found!</h3>
            @endif
        </div>

    </div>
@endsection

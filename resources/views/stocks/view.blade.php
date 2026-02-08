@extends('dashboard.master2')
@php
    $title = 'View Details | ' . $stock->company_name . ' ' . $stock->model_name;
@endphp

@section('admin_title', $title)
@section('content2')
    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a href="{{ route('stock.index', ['type' => $stock->type]) }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-sm-8 col-6">
                    <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">
                        {{ $title }}
                    </h3>
                    <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                        {{ $title }}
                    </h5>
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


        <div class="card shadow border-0 mt-4">
            <div class="card-body p-4">

                {{-- ================= PURCHASE DETAILS ================= --}}
                <div class="mb-5">
                    <h4 class="fw-bold text-primary border-bottom pb-2 mb-4">
                        <i class="bi bi-receipt me-2"></i>Purchase Details
                    </h4>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="info-box">
                                <span>Purchasing Type</span>
                                <strong>{{ $stock->purchasing_from }}</strong>
                            </div>
                        </div>


                        @if ($stock->purchasing_from == 'Local')

                            <div class="col-md-6">
                                <div class="info-box">
                                    <span>Name</span>
                                    <strong>{{ $stock->pushasing_from_name }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span>Phone</span>
                                    <strong>{{ $stock->pushasing_from_phone }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span>CNIC</span>
                                    <strong>{{ $stock->pushasing_from_cnic }}</strong>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="info-box">
                                    <span>Address</span>
                                    <strong>{{ $stock->pushasing_from_address }}</strong>
                                </div>
                            </div>
                        @else
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span>Bussiness Name</span>
                                    <strong>{{ $stock->dealer_bussiness }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span>Dealer Name</span>
                                    <strong>{{ $stock->dealer_name }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span>Dealer Phone</span>
                                    <strong>{{ $stock->dealer_phone }}</strong>
                                </div>
                            </div>
                            @if ($stock->dealer_address)
                                <div class="col-md-12">
                                    <div class="info-box">
                                        <span>Address</span>
                                        <strong>{{ $stock->dealer_address ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- ================= STOCK DETAILS ================= --}}
                <div class="mb-5">

                    <h4 class="fw-bold text-primary border-bottom pb-2 mb-4">
                        <i class="bi bi-receipt me-2"></i>Stock Details
                    </h4>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span>Company</span>
                                <strong>{{ $stock->company_name }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <span>Model</span>
                                <strong>{{ $stock->model_name }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <span>ROM</span>
                                <strong>{{ $stock->rom }}</strong>
                            </div>
                        </div>

                            <div class="col-md-6">
                                <div class="info-box">
                                    <span>RAM</span>
                                    <strong>{{ $stock->ram ?? 'N/A' }}</strong>
                                </div>
                            </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <span>IMEI 1</span>
                                <strong>{{ $stock->imei1 }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <span>IMEI 2</span>
                                <strong>{{ $stock->imei2 ?? 'N/A' }}</strong>

                            </div>
                        </div>

                        @if ($stock->type === 'apple')
                            <div class="col-md-12">
                                <div class="info-box">
                                    <span>Battery Health</span>
                                    <strong>{{ $stock->health }}%</strong>
                                </div>
                            </div>
                        @endif



                        <div class="col-md-4">
                             <div class="info-box">
                                <span>PTA Status</span>
                                <strong>{{ $stock->pta_status }}</strong>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box">
                                <span>Activation Status</span>
                                <strong>{{ $stock->activation_status }}</strong>
                            </div>
                        </div>

                        <div class="col-md-4">
                             <div class="info-box">
                                <span>Country Lock Status</span>
                                <strong>{{ $stock->country_status }}</strong>
                            </div>
                        </div>



                        @if ($stock->status == 'Available')
                      <div class="col-md-12">
                             <div class="info-box">
                                <span>Sale Price</span>
                                <strong>{{'Rs.'. number_format($stock->sale) }}</strong>
                            </div>
                        </div>
                    @endif

                    </div>


                </div>

                {{-- ================= SOLD DETAILS ================= --}}
                @if ($stock->status === 'Sold Out')
                    <div>
                        <h4 class="fw-bold text-primary border-bottom pb-2 mb-4">
                            <i class="bi bi-receipt me-2"></i>Sold Details
                        </h4>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span>Buyer Name</span>
                                    <strong>{{ $stock->buyer_name }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box">
                                    <span>Phone</span>
                                    <strong>{{ $stock->buyer_phone ?? 'N/A' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box">
                                    <span>Sold Date</span>
                                    <strong>{{ \Carbon\Carbon::parse($stock->sold_date)->format('d M Y') }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box">
                                    <span>Backup Days</span>
                                    <strong>{{ $stock->backup }}</strong>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="info-box">
                                    <span>Sold Price</span>
                                    <strong>{{ 'Rs.' . number_format($stock->total_bill) }}</strong>
                                </div>
                            </div>


                        </div>
                    </div>
                @endif

            </div>
        </div>


        <style>
            .info-box {
                background: #f8f9fa;
                border: 1px solid #e4e6ef;
                border-radius: 8px;
                padding: 14px;
            }

            .info-box span {
                display: block;
                font-size: 12px;
                color: #6c757d;
            }

            .info-box strong {
                font-size: 15px;
                color: #212529;
            }
        </style>


    </div>




@endsection

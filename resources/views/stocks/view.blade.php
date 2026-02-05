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


        <div class="card rounded-0 p-3 mt-3">
            <ul class="list-group list-group-flush mt-2">
                <li class="list-group-item"><span class="text-dark fw-bold">Company</span> <span
                        class="text-dark float-end">{{ $stock->company_name }}</span></li>
                <li class="list-group-item"><span class="text-dark fw-bold">Model</span> <span
                        class="text-dark float-end">{{ $stock->model_name }}</span></li>
                <li class="list-group-item"><span class="text-dark fw-bold">IMEI 1</span> <span
                        class="text-dark float-end">{{ $stock->imei1 }}</span></li>
                <li class="list-group-item"><span class="text-dark fw-bold">IMEI 2</span> <span
                        class="text-dark float-end">{{ $stock->imei2 }}</span></li>

                @if ($stock->type == 'apple')
                    <li class="list-group-item"><span class="text-dark fw-bold">Battery Health</span> <span
                            class="text-dark float-end">{{ $stock->health . '%' }}</span></li>
                @endif
                @if ($stock->type == 'others')
                    <li class="list-group-item"><span class="text-dark fw-bold">RAM</span> <span
                            class="text-dark float-end">{{ $stock->ram }}</span></li>
                @endif
                <li class="list-group-item"><span class="text-dark fw-bold">ROM</span> <span
                        class="text-dark float-end">{{ $stock->rom }}</span></li>
                <li class="list-group-item"><span class="text-dark fw-bold">PTA Status</span> <span
                        class="text-dark float-end">{{ $stock->pta_status }}</span></li>
                <li class="list-group-item"><span class="text-dark fw-bold">Actication Status</span> <span
                        class="text-dark float-end">{{ $stock->activation_status }}</span></li>
                <li class="list-group-item"><span class="text-dark fw-bold">Country Status</span> <span
                        class="text-dark float-end">{{ $stock->country_status }}</span></li>
                <li class="list-group-item" style="font-size: 20px"><span class="text-dark fw-bolder">Sale
                        Price</span> <span
                        class="text-dark float-end">{{ 'Rs.' . number_format($stock->sale) }}</span>
                </li>
            </ul>
        </div>
    </div>




@endsection

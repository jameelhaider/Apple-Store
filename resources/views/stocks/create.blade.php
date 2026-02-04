@php
    $action = request()->routeIs('stock.edit') ? 'Edit Stock' : 'Add New Stock';

    $title = match (request()->type) {
        'apple' => "$action | Apple Phones",
        'others' => "$action | Other Phones",
        default => 'Title Not Found',
    };
@endphp

@extends('dashboard.master2')
@section('admin_title', $title)
@section('content2')



    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a href="{{ route('stock.index', ['type' => request()->type]) }}"
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


        <div class="card p-3 mt-3">
            <form action="{{ $stock->id != null ? route('update.stock', ['id' => $stock->id]) : route('submit.stock') }}"
                method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ request()->type }}">
                @if (request()->type == 'apple')
                    <input type="hidden" name="company_name" value="Apple">
                @endif

                <div class="row mt-3">
                    <hr>
                    <h4 class="fw-bold mb-3">Stock Information</h4>
                    <hr>
                </div>
                <div class="row">
                    @if (request()->type == 'apple')
                        <div class="col-lg-8 col-md-8 col-12">
                            <label for="" class="fw-bold mb-2 text-dark">SELECT MODEL<span
                                    class="text-danger">*</span></label>



                            <select name="model_name" class="form-select @error('model_name') is-invalid @enderror">
                                <option value="">Select iPhone Model</option>

                                @foreach (iphone_models() as $model)
                                    <option value="{{ $model }}"
                                        {{ old('model_name', $stock->model_name) == $model ? 'selected' : '' }}>
                                        {{ $model }}
                                    </option>
                                @endforeach
                            </select>






                            @error('model_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endif



                    @if (request()->type == 'others')
                        <div class="col-lg-4 col-md-4 col-6">
                            <label for="" class="fw-bold mb-2 text-dark">SELECT COMPANY<span
                                    class="text-danger">*</span></label>



                            <select name="company_name" required class="form-select @error('company_name') is-invalid @enderror">
                                <option value="">Select Company</option>

                                @foreach (other_companies() as $company)
                                    <option value="{{ $company }}"
                                        {{ old('company_name', $stock->company_name) == $company ? 'selected' : '' }}>
                                        {{ $company }}
                                    </option>
                                @endforeach
                            </select>
                            @error('model_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="col-lg-4 col-md-4 col-6">
                            <label for="" class="fw-bold mb-2 text-dark">Model Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" required placeholder="Model Name" name="model_name"
                                value="{{ old('model_name', $stock->model_name) }}"
                                class="form-control @error('model_name') is-invalid @enderror">
                            @error('model_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                    @endif


                </div>



                <div class="row mt-3">
                    <!-- IMEI -->
                    <div class="col-lg-4 col-md-4 col-6">
                        <label class="fw-bold mb-2 text-dark">IMEI 1<span class="text-danger">*</span></label>
                        <input type="text" pattern="\d{15}" maxlength="15" required placeholder="IMEI" name="imei1"
                            value="{{ old('imei1', $stock->imei1) }}"
                            class="form-control @error('imei1') is-invalid @enderror">
                        @error('imei1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- IMEI 2 -->
                    <div class="col-lg-4 col-md-4 col-6">
                        <label class="fw-bold mb-2 text-dark">IMEI 2 (Optional)</label>
                        <input type="text" pattern="\d{15}" maxlength="15" placeholder="IMEI 2" name="imei2"
                            value="{{ old('imei2', $stock->imei2) }}"
                            class="form-control @error('imei2') is-invalid @enderror">
                        @error('imei2')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>



                <div class="row mt-3">


                    <div class="col-lg-4 col-md-4 col-6">
                        <label class="fw-bold mb-2 text-dark">ROM<span class="text-danger">*</span></label>
                        <select name="rom" required class="form-select @error('rom') is-invalid @enderror">
                            <option value="">Select Option</option>
                            <option value="1 GB" {{ old('rom', $stock->rom) == '1 GB' ? 'selected' : '' }}>1 GB</option>
                            <option value="2 GB" {{ old('rom', $stock->rom) == '2 GB' ? 'selected' : '' }}>2 GB</option>
                            <option value="4 GB" {{ old('rom', $stock->rom) == '4 GB' ? 'selected' : '' }}>4 GB</option>
                            <option value="8 GB" {{ old('rom', $stock->rom) == '8 GB' ? 'selected' : '' }}>8 GB</option>
                            <option value="16 GB" {{ old('rom', $stock->rom) == '16 GB' ? 'selected' : '' }}>16 GB
                            </option>
                            <option value="32 GB" {{ old('rom', $stock->rom) == '32 GB' ? 'selected' : '' }}>32 GB
                            </option>
                            <option value="64 GB" {{ old('rom', $stock->rom) == '64 GB' ? 'selected' : '' }}>64 GB
                            </option>
                            <option value="128 GB" {{ old('rom', $stock->rom) == '128 GB' ? 'selected' : '' }}>128 GB
                            </option>
                            <option value="256 GB" {{ old('rom', $stock->rom) == '256 GB' ? 'selected' : '' }}>256 GB
                            </option>
                            <option value="512 GB" {{ old('rom', $stock->rom) == '512 GB' ? 'selected' : '' }}>512 GB
                            </option>
                            <option value="1 TB" {{ old('rom', $stock->rom) == '1 TB' ? 'selected' : '' }}>1 TB
                            </option>
                            <option value="2 TB" {{ old('rom', $stock->rom) == '2 TB' ? 'selected' : '' }}>2 TB
                            </option>
                        </select>
                        @error('rom')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>


                    @if (request()->type == 'apple')
                        <div class="col-lg-4 col-md-4 col-6">
                            <label for="" class="fw-bold mb-2 text-dark">BATTERY HEALTH<span
                                    class="text-danger">*</span></label>
                            <input type="number" min="1" max="100" placeholder="Battery Health"
                                name="health" value="{{ old('health', $stock->health) }}"
                                class="form-control @error('health') is-invalid @enderror">
                            @error('health')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @else
                        <div class="col-lg-4 col-md-4 col-6">
                            <label class="fw-bold mb-2 text-dark">RAM<span class="text-danger">*</span></label>
                            <select name="ram" required class="form-select @error('ram') is-invalid @enderror">
                                <option value="">Select Option</option>
                                <option value="128 MB" {{ old('ram', $stock->ram) == '128 MB' ? 'selected' : '' }}>128 MB
                                </option>
                                <option value="256 MB" {{ old('ram', $stock->ram) == '256 MB' ? 'selected' : '' }}>256 MB
                                </option>
                                <option value="512 MB" {{ old('ram', $stock->ram) == '512 MB' ? 'selected' : '' }}>512 MB
                                </option>
                                <option value="1 GB" {{ old('ram', $stock->ram) == '1 GB' ? 'selected' : '' }}>1 GB
                                </option>
                                <option value="1.5 GB" {{ old('ram', $stock->ram) == '1.5 GB' ? 'selected' : '' }}>1.5 GB
                                </option>
                                <option value="2 GB" {{ old('ram', $stock->ram) == '2 GB' ? 'selected' : '' }}>2 GB
                                </option>
                                <option value="3 GB" {{ old('ram', $stock->ram) == '3 GB' ? 'selected' : '' }}>3 GB
                                </option>
                                <option value="4 GB" {{ old('ram', $stock->ram) == '4 GB' ? 'selected' : '' }}>4 GB
                                </option>
                                <option value="6 GB" {{ old('ram', $stock->ram) == '6 GB' ? 'selected' : '' }}>6 GB
                                </option>
                                <option value="8 GB" {{ old('ram', $stock->ram) == '8 GB' ? 'selected' : '' }}>8 GB
                                </option>
                                <option value="12 GB" {{ old('ram', $stock->ram) == '12 GB' ? 'selected' : '' }}>12 GB
                                </option>
                                <option value="16 GB" {{ old('ram', $stock->ram) == '16 GB' ? 'selected' : '' }}>16 GB
                                </option>
                                <option value="18 GB" {{ old('ram', $stock->ram) == '18 GB' ? 'selected' : '' }}>18 GB
                                </option>
                                <option value="24 GB" {{ old('ram', $stock->ram) == '24 GB' ? 'selected' : '' }}>24 GB
                                </option>
                            </select>
                            @error('ram')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    @endif





                </div>



                <div class="row mt-3">
                    <!-- PTA Status -->
                    <div class="col-lg-4 col-md-4 col-12">
                        <label class="fw-bold mb-2 text-dark">PTA STATUS<span class="text-danger">*</span></label>
                        <select name="pta_status" class="form-select @error('pta_status') is-invalid @enderror" required>
                            <option value="">Select Option</option>
                            <option value="Official Approved"
                                {{ old('pta_status', $stock->pta_status) == 'Official Approved' ? 'selected' : '' }}>
                                Official Approved
                            </option>
                            <option value="Not Approved"
                                {{ old('pta_status', $stock->pta_status) == 'Not Approved' ? 'selected' : '' }}>Not
                                Approved</option>
                            @if (request()->type == 'apple')
                                <option value="Not Approved (4 months remaining)"
                                    {{ old('pta_status', $stock->pta_status) == 'Not Approved (4 months remaining)' ? 'selected' : '' }}>
                                    Not Approved (4 months remaining)</option>
                            @endif

                            @if (request()->type == 'others')
                                <option value="Patch Approved"
                                    {{ old('pta_status', $stock->pta_status) == 'Patch Approved' ? 'selected' : '' }}>Patch
                                    Approved</option>
                                <option value="CPID Approved"
                                    {{ old('pta_status', $stock->pta_status) == 'CPID Approved' ? 'selected' : '' }}>CPID
                                    Approved</option>
                            @endif

                        </select>
                        @error('pta_status')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Activation Status -->
                    <div class="col-lg-4 col-md-4 col-6">
                        <label class="fw-bold mb-2 text-dark">ACTIVATION STATUS<span class="text-danger">*</span></label>
                        <select name="activation_status"
                            class="form-select @error('activation_status') is-invalid @enderror" required>
                            <option value="">Select Option</option>
                            <option value="Active"
                                {{ old('activation_status', $stock->activation_status) == 'Active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="Non Active"
                                {{ old('activation_status', $stock->activation_status) == 'Non Active' ? 'selected' : '' }}>
                                Non
                                Active</option>
                        </select>
                        @error('activation_status')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Country Locked -->
                    <div class="col-lg-4 col-md-4 col-6">
                        <label class="fw-bold mb-2 text-dark">COUNTRY LOCKED?<span class="text-danger">*</span></label>
                        <select name="country_status" class="form-select @error('country_status') is-invalid @enderror"
                            required>
                            <option value="">Select Option</option>


                            @if (request()->type == 'apple')
                                <option value="JV"
                                    {{ old('country_status', $stock->country_status) == 'JV' ? 'selected' : '' }}>JV
                                </option>
                                <option value="Factory"
                                    {{ old('country_status', $stock->country_status) == 'Factory' ? 'selected' : '' }}>
                                    Factory
                                </option>
                            @endif

                            @if (request()->type == 'others')
                                <option value="Yes"
                                    {{ old('country_status', $stock->country_status) == 'Yes' ? 'selected' : '' }}>Yes
                                </option>
                                <option value="No"
                                    {{ old('country_status', $stock->country_status) == 'No' ? 'selected' : '' }}>No
                                </option>
                            @endif

                        </select>
                        @error('country_status')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>




                <div class="row mt-3">
                    <div class="col-lg-4 col-md-4 col-6">
                        <label for="" class="fw-bold mb-2 text-dark">PURCHASE PRICE<span
                                class="text-danger">*</span></label>
                        <input type="number" min="1" required placeholder="Purchase Price"
                            value="{{ old('purchase', $stock->purchase) }}"
                            class="form-control @error('purchase') is-invalid @enderror" name="purchase">
                        @error('purchase')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-4 col-6">
                        <label for="" class="fw-bold mb-2 text-dark">SALE PRICE<span
                                class="text-danger">*</span></label>
                        <input type="number" min="2" required placeholder="Sale Price"
                            value="{{ old('sale', $stock->sale) }}"
                            class="form-control @error('sale') is-invalid @enderror" name="sale">
                        @error('sale')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <button type="submit" name="action" value="save" class="btn btn-primary mt-3 float-end ms-2"
                    title="Save">
                    {{ $stock->id != null ? 'Update' : 'Save' }} <i class="bx bx-check-circle"></i>
                </button>

                @if ($stock->id == null)
                    <button type="submit" name="action" value="save_add_new" class="btn btn-dark mt-3 float-end"
                        title="Save and Add New">
                        Save & Add New <i class="bx bx-plus-circle"></i>
                    </button>
                @endif

            </form>
        </div>
    </div>


    <style>
        .select2-container--default .select2-selection--single {
            display: block;
            width: 100%;
            padding: 0.300rem 0.200rem 0.300rem 0.200rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            height: auto;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50%;
            right: 0.32rem;
            transform: translateY(-50%);
            height: auto;
        }
    </style>

    <script>
        $(":input").inputmask();
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script>
        $('#dealer-select').select2({
            placeholder: 'Select Dealer',
            allowClear: true
        });

        $('#color-select').select2({
            placeholder: 'Select Color',
            allowClear: true
        });

        $('#quality-select').select2({
            placeholder: 'Select Quality',
            allowClear: true
        });

        $('#type-select').select2({
            placeholder: 'Select Type',
            allowClear: true
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function populateModels(companyId, selectedModelId = null) {
                if (companyId) {
                    $.ajax({
                        url: '/company/' + companyId + '/models',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#model-select').empty();
                            $('#model-select').append('<option value="">Select Model</option>');

                            $.each(data, function(index, model) {
                                let selected = (model.id == selectedModelId) ? 'selected' : '';
                                $('#model-select').append('<option value="' + model.id + '" ' +
                                    selected + '>' + model.model + '</option>');
                            });
                        },
                        error: function() {
                            alert('Error fetching models. Please try again.');
                        }
                    });
                } else {
                    $('#model-select').empty();
                    $('#model-select').append('<option value="">Select Model</option>');
                }
            }

            $('#company-select').on('change', function() {
                var companyId = $(this).val();
                populateModels(companyId);
            });

            // Initial population on page load for edit
            var initialCompanyId = $('#company-select').val();
            var initialModelId = '{{ old('model_id', $stock->model_id ?? '') }}';
            if (initialCompanyId) {
                populateModels(initialCompanyId, initialModelId);
            }
        });
    </script>


@endsection

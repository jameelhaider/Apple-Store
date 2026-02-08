@extends('dashboard.master2')
@section('admin_title', 'Admin | Stocks | ' . (request()->type == 'apple' ? 'Apple Phones' : 'Other Phones'))
@section('content2')


    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-6 col-md-4 col-sm-5">
                    <a href="{{ route('create.stock', ['type' => request()->type]) }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-plus me-1"></i> Add New Stock
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-8 col-sm-7">
                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family: cursive;">
                            {{ request()->type == 'apple' ? 'Apple Phones' : 'Other Phones' }}
                        </h3>

                        <h5 class="mt-1 d-block d-lg-none d-md-none" style="font-family: cursive;">
                            {{ request()->type == 'apple' ? 'Apple Phones' : 'Other Phones' }}
                        </h5>

                        <div class="ms-4 d-none d-lg-block">
                            <span id="togglePurchasePriceButton" style="cursor: pointer;color:black">
                                <i class="bx bx-show"></i> Show Purchase Price
                            </span>
                        </div>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const toggleBtn = document.getElementById('togglePurchasePriceButton');
                                const priceFields = document.querySelectorAll('.purchase-price');

                                // Load saved preference
                                let showPrice = localStorage.getItem('showPurchasePrice') === 'true';

                                // Set initial state
                                updatePriceVisibility(showPrice);

                                // Toggle function
                                toggleBtn.addEventListener('click', function() {
                                    showPrice = !showPrice;
                                    localStorage.setItem('showPurchasePrice', showPrice);
                                    updatePriceVisibility(showPrice);
                                });

                                function updatePriceVisibility(show) {
                                    priceFields.forEach(field => {
                                        field.style.display = show ? 'table-cell' : 'none';
                                    });
                                    toggleBtn.innerHTML = show ?
                                        '<i class="bx bx-hide"></i> Hide Purchase Price' :
                                        '<i class="bx bx-show"></i> Show Purchase Price';
                                }
                            });
                        </script>




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

            .custom-back-button:hover {
                background-color: #2b2b2b;
                border: 0px;
            }

            .custom-back-button i {
                font-size: 18px;
            }
        </style>




        <div class="card mb-2 p-2 mt-2">
            <form action="" method="GET">
                <div class="row">
                    @if (request()->type == 'apple')
                        <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                            <select name="model_name" id="model-select" class="form-select" onchange="this.form.submit()">
                                <option value="{{ null }}">Select Model</option>
                                @foreach (iphone_models() as $model)
                                    <option value="{{ $model }}"
                                        {{ request()->model_name == $model ? 'selected' : '' }}>
                                        {{ $model }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                            <select name="company_name" id="company-select" class="form-select"
                                onchange="this.form.submit()">
                                <option value="{{ null }}">Select Company</option>
                                @foreach (other_companies() as $company)
                                    <option value="{{ $company }}"
                                        {{ request()->company_name == $company ? 'selected' : '' }}>
                                        {{ $company }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                        <input type="text" pattern="\d{15}" maxlength="15" required placeholder="IMEI 1" name="imei1"
                            value="{{ request()->imei1 }}" class="form-control">
                    </div>


                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                        <select name="pta_status" class="form-control" onchange="this.form.submit()" id="">
                            <option value="">PTA Status</option>
                            <option value="Official Approved"
                                {{ request()->pta_status == 'Official Approved' ? 'selected' : '' }}>Official Approved
                            </option>
                            <option value="Not Approved" {{ request()->pta_status == 'Not Approved' ? 'selected' : '' }}>
                                Not Approved</option>
                            @if (request()->type == 'apple')
                                <option value="Not Approved (4 months remaining)"
                                    {{ request()->pta_status == 'Not Approved (4 months remaining)' ? 'selected' : '' }}>
                                    Not Approved (4 months remaining)</option>
                            @endif


                            @if (request()->type == 'others')
                                <option value="Patch Approved"
                                    {{ request()->pta_status == 'Patch Approved' ? 'selected' : '' }}>Patch
                                    Approved</option>
                                <option value="CPID Approved"
                                    {{ request()->pta_status == 'CPID Approved' ? 'selected' : '' }}>CPID
                                    Approved</option>
                            @endif

                        </select>
                    </div>


                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ route('stock.index', ['type' => request()->type]) }}" title="Clear"
                                class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>

                        </div>
                    </div>

                </div>
            </form>
        </div>


        @if (
            $stocks->count() > 0 &&
                (request()->model_name || request()->company_name || request()->imei1 || request()->pta_status))
            <div class="alert bg-primary text-white mt-3">
                <strong>{{ $stocks->count() }} {{ $stocks->count() > 0 && $stocks->count() < 2 ? 'Result' : 'Results' }}
                    Found</strong>
            </div>
        @elseif (
            $stocks->count() < 1 &&
                (request()->model_name || request()->company_name || request()->imei1 || request()->pta_status))
            <div class="alert bg-warning text-white mt-3">
                <strong>No Results Found !</strong>
            </div>
        @endif


        <div class="card p-2 mb-0">
            @if ($stocks->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:12px" class="text-dark fw-bold">#</th>
                                @if (request()->type == 'others')
                                    <th style="font-size:12px" class="text-dark fw-bold">Comapny</th>
                                @endif

                                <th style="font-size:12px" class="text-dark fw-bold">Model</th>
                                <th style="font-size:12px" class="text-dark fw-bold">Imei's</th>
                                <th style="font-size:12px;" class="text-dark fw-bold purchase-price">
                                    Purchase</th>
                                <th style="font-size:12px" class="text-dark fw-bold">Sale</th>
                                <th style="font-size:12px" class="text-dark fw-bold">Entry</th>
                                <th style="font-size:12px" class="text-dark fw-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stocks as $key => $stock)
                                <tr class="text-center">
                                    <td class="text-dark">{{ ++$key }}</td>
                                    @if (request()->type == 'others')
                                        <td class="text-dark">
                                            {{ $stock->company_name }}
                                        </td>
                                    @endif

                                    <td class="text-dark fw-bold" title="View Details" style="font-size: 16px;">
                                        <a href="{{ route('stock.view', ['id' => $stock->id]) }}">
                                            {{ $stock->model_name }}
                                        </a>

                                    </td>

                                    <td class="text-dark">{{ $stock->imei1 }}
                                        @if ($stock->imei2)
                                            <br>
                                            {{ $stock->imei2 }}
                                        @endif
                                    </td>
                                    <td class="text-dark purchase-price">
                                        {{ 'Rs. ' . number_format($stock->purchase) }}
                                    </td>


                                    <td class="text-dark fw-bold" title="Edit Stock" style="font-size: 20px">

                                        <a
                                            href="{{ route('stock.edit', ['type' => request()->type, 'id' => $stock->id]) }}">
                                            {{ 'Rs.' . number_format($stock->sale) }}
                                        </a>
                                    </td>
                                    <td class="text-dark">{{ $stock->created_at->format('d M y') }}</td>





                                    <td>
                                        <div class="dropdown ms-auto">
                                            <button class="btn btn-dark btn-sm dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                        onclick="openMarkAsSoldModal({{ $stock->id }}, {{ $stock->sale }})">Mark
                                                        As Sold</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('stock.view', ['id' => $stock->id]) }}">View
                                                        Details</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('stock.edit', ['type' => request()->type, 'id' => $stock->id]) }}">Edit</a>
                                                </li>
                                                {{-- <li>
                                                    <a class="dropdown-item"
                                                        onclick="confirmDelete('{{ route('stock.delete', ['id' => $stock->id]) }}')">Delete</a>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="float-end mt-2">
                        {{ $stocks->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:12px" class="text-dark fw-bold">#</th>
                                <th style="font-size:12px" class="text-dark fw-bold">Comapny</th>
                                <th style="font-size:12px" class="text-dark fw-bold">Model</th>
                                <th style="font-size:12px" class="text-dark fw-bold">Imei's</th>
                                <th style="font-size:12px" class="text-dark fw-bold">Entry</th>
                                <th style="font-size:12px" class="text-dark fw-bold">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <h4 class="h4 text-center fw-normal text-muted mt-2">No Data Found!</h4>
            @endif
        </div>

    </div>




    {{-- <script>
        // Function to handle delete confirmation
        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure you want to delete this Stock?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }
    </script> --}}



    <!-- Mark As Sold Modal -->
    <div class="modal fade" id="markAsSoldModal" tabindex="-1" role="dialog" aria-labelledby="markAsSoldModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="markAsSoldModalLabel">Mark As Sold</h5>
                    <button type="button" class="btn-close" aria-label="Close" onclick="handleModalClose()">
                    </button>
                </div>
                <form id="markAsSoldForm" action="{{ route('stock.markAsSold') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="stock_id" id="stockId">

                        <div class="row mt-2">
                            <div class="col-lg-6 col-6">
                                <div class="form-group">
                                    <label for="soldOutPrice" class="fw-bold text-dark mb-1">Sale Price <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min="1" class="form-control" placeholder="Sale Price"
                                        id="salePrice" name="sale_price" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-6">
                                <div class="form-group">
                                    <label for="soldOutDate" class="fw-bold text-dark mb-1">Sold Out Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="soldOutDate" name="sold_out_date"
                                        required>
                                    <script>
                                        const today = new Date().toISOString().split('T')[0];
                                        document.getElementById('soldOutDate').value = today;
                                    </script>

                                </div>
                            </div>
                        </div>




                        <div class="row mt-2">
                            <div class="col-lg-12 col-12">
                                <div class="form-group">
                                    <label for="buyerName" class="fw-bold text-dark mb-1">Select Account <span
                                            class="text-danger">*</span></label>
                                    <select name="account_id" required class="form-select" id="accountSelect">
                                        <option value="">Select Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                data-name="{{ $account->customer_name }}"
                                                data-phone="{{ $account->customer_phone }}">
                                                {{ $account->customer_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>



                        {{-- <div class="row mt-2">
                            <div class="col-lg-6 col-6">
                                <div class="form-group">
                                    <label for="buyerName" class="fw-bold text-dark mb-1">Buyer Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Buyer Name" id="buyerName"
                                        name="buyer_name" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-6">
                                <div class="form-group">
                                    <label for="buyerPhone" class="fw-bold text-dark mb-1">Buyer Phone (optional)</label>
                                    <input type="text" class="form-control" maxlength="12"
                                        placeholder="0399-99999999" data-inputmask="'mask': '0399-99999999'"
                                        id="buyerPhone" name="buyer_phone">
                                </div>
                            </div>
                        </div> --}}


                        {{-- <select name="account_id" required class="form-select mt-2" id="accountSelect">
                            <option value="">Select Account</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}" data-name="{{ $account->customer_name }}"
                                    data-phone="{{ $account->customer_phone }}">
                                    {{ $account->customer_name }}
                                </option>
                            @endforeach
                        </select> --}}


                        <div class="row mt-2" id="buyerFields" style="display: none">
                            <div class="col-lg-6 col-6">
                                <div class="form-group">
                                    <label class="fw-bold text-dark mb-1">
                                        Buyer Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" placeholder="Buyer Name" id="buyerName"
                                        name="buyer_name">
                                </div>
                            </div>

                            <div class="col-lg-6 col-6">
                                <div class="form-group">
                                    <label class="fw-bold text-dark mb-1">
                                        Buyer Phone (optional)
                                    </label>

                                    <input type="text" maxlength="12" placeholder="0399-99999999"
                                        data-inputmask="'mask': '0399-99999999'" class="form-control @error('buyer_phone') is-invalid @enderror" name="buyer_phone"
                                        id="buyerPhone">
                                        @error('buyer_phone')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror


                                </div>
                            </div>
                        </div>


                        <script>
                            document.getElementById('accountSelect').addEventListener('change', function() {
                                const buyerFields = document.getElementById('buyerFields');
                                const buyerName = document.getElementById('buyerName');
                                const buyerPhone = document.getElementById('buyerPhone');

                                const selectedOption = this.options[this.selectedIndex];
                                const accountId = this.value;

                                if (accountId == 1) {
                                    // Show fields for manual entry
                                    buyerFields.style.display = 'flex';
                                    buyerName.value = '';
                                    buyerPhone.value = '';
                                    buyerName.required = true;
                                } else if (accountId) {
                                    // Hide fields & auto-fill
                                    buyerFields.style.display = 'none';
                                    buyerName.value = selectedOption.dataset.name || '';
                                    buyerPhone.value = selectedOption.dataset.phone || '';
                                    buyerName.required = false;
                                } else {
                                    // Reset if no account selected
                                    buyerFields.style.display = 'flex';
                                    buyerName.value = '';
                                    buyerPhone.value = '';
                                }
                            });
                        </script>




                        <div class="row mt-2">
                            <div class="col-lg-12 col-12">
                                <div class="form-group">
                                    <label for="buyerName" class="fw-bold text-dark mb-1">Backup Days <span
                                            class="text-danger">*</span></label>
                                    <select name="backup" required class="form-select">
                                        <option value="">Select Backup</option>
                                        <option value="1 Day">1 Day</option>
                                        <option value="2 Days">2 Days</option>
                                        <option value="3 Days">3 Days</option>
                                        <option value="4 Days">4 Days</option>
                                        <option value="5 Days">5 Days</option>
                                        <option value="6 Days">6 Days</option>
                                        <option value="7 Days">7 Days</option>
                                        <option value="10 Days">10 Days</option>
                                        <option value="14 Days">14 Days</option>
                                    </select>
                                </div>
                            </div>
                        </div>





                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="handleModalClose()">Close</button>
                        <button type="submit" class="btn btn-primary" id="confirmMarkAsSold">Mark As Sold & Generate
                            Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        $(":input").inputmask();
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function openMarkAsSoldModal(stockId, SalePrice) {
            $('#stockId').val(stockId);
            $('#salePrice').val(SalePrice);
            $('#markAsSoldModal').modal('show');
        }

        function handleModalClose() {
            $('#markAsSoldModal').modal('hide');
        }
    </script>



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


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script>
        $('#model-select').select2({
            placeholder: 'Select Model',
            allowClear: true
        });
        $('#acc-select').select2({
            placeholder: 'Select Accounr',
            allowClear: true
        });
        $('#company-select').select2({
            placeholder: 'Select Company',
            allowClear: true
        });
    </script>
@endsection

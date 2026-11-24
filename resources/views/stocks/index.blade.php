@extends('dashboard.master2')
@section('admin_title', 'Admin | Stocks | ' . (request()->type == 'apple' ? 'Apple Phones' : 'Other Phones'))
@section('content2')

    @php
        $isApple = request()->type == 'apple';
        $pageTitle = $isApple ? 'Apple Phones' : 'Other Phones';
        $hasFilter = request()->model_name || request()->company_name || request()->imei1 || request()->pta_status;
    @endphp

    <div class="container-fluid px-3">

        {{-- ============ HEADER BAR ============ --}}
        <div class="card shadow-sm bg-white rounded-0 stock-header">
            <div class="row g-0">
                <div class="col-lg-3 col-6 col-md-4 col-sm-5">
                    <a href="{{ route('create.stock', ['type' => request()->type]) }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-plus me-1"></i> Add New Stock
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-8 col-sm-7">
                    <div class="d-flex align-items-center justify-content-between h-100 px-3 py-2">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 d-none d-md-block" style="font-family: cursive;">{{ $pageTitle }}</h3>
                            <h5 class="mb-0 d-block d-md-none" style="font-family: cursive;">{{ $pageTitle }}</h5>
                            <span class="count-pill ms-3 d-none d-sm-inline-block">{{ $stocks->total() ?? $stocks->count() }}
                                in stock</span>
                        </div>

                        <div class="form-check form-switch purchase-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="purchaseSwitch">
                            <label class="form-check-label" for="purchaseSwitch">Show all purchase prices</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ FILTERS ============ --}}
        <div class="card mb-2 p-2 mt-2 filter-card">
            <form action="" method="GET">
                <div class="row">
                    @if ($isApple)
                        <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                            <select name="model_name" id="model-select" class="form-select" onchange="this.form.submit()">
                                <option value="{{ null }}">Select Model</option>
                                @foreach (iphone_models() as $model)
                                    <option value="{{ $model }}"
                                        {{ request()->model_name == $model ? 'selected' : '' }}>{{ $model }}
                                    </option>
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
                                        {{ request()->company_name == $company ? 'selected' : '' }}>{{ $company }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                        <input type="text" pattern="\d{15}" maxlength="15" required placeholder="IMEI 1" name="imei1"
                            value="{{ request()->imei1 }}" class="form-control">
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                        <select name="pta_status" class="form-control" onchange="this.form.submit()">
                            <option value="">PTA Status (All)</option>
                            <option value="Official Approved"
                                {{ request()->pta_status == 'Official Approved' ? 'selected' : '' }}>Official Approved
                            </option>
                            @if (request()->type == 'others')
                                <option value="Patch Approved"
                                    {{ request()->pta_status == 'Patch Approved' ? 'selected' : '' }}>Patch Approved
                                </option>
                                <option value="CPID Approved"
                                    {{ request()->pta_status == 'CPID Approved' ? 'selected' : '' }}>CPID Approved
                                </option>
                            @endif
                            <option value="Not Approved" {{ request()->pta_status == 'Not Approved' ? 'selected' : '' }}>
                                Not Approved</option>
                            <option value="Not Approved (4 months remaining)"
                                {{ request()->pta_status == 'Not Approved (4 months remaining)' ? 'selected' : '' }}>
                                Not Approved (4 months remaining)</option>
                            <option value="Not Approved (2 months remaining)"
                                {{ request()->pta_status == 'Not Approved (2 months remaining)' ? 'selected' : '' }}>
                                Not Approved (2 months remaining)</option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ route('stock.index', ['type' => request()->type]) }}" title="Clear"
                                class="btn btn-outline-dark">Clear</a>
                            <button type="submit" title="Search" class="btn btn-dark">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        @if ($stocks->count() > 0 && $hasFilter)
            <div class="alert alert-dark rounded-0 mt-3 mb-2 py-2">
                <strong>{{ $stocks->count() }} {{ $stocks->count() == 1 ? 'Result' : 'Results' }} Found</strong>
            </div>
        @elseif ($stocks->count() < 1 && $hasFilter)
            <div class="alert alert-warning rounded-0 mt-3 mb-2 py-2">
                <strong>No Results Found !</strong>
            </div>
        @endif

        {{-- ============ STOCK LIST ============ --}}
        @if ($stocks->count() > 0)
            <div class="stock-list mt-2" id="stockList">
                @foreach ($stocks as $stock)
                    @php
                        $pta = $stock->pta_status;
                        $ptaClass = $pta == 'Official Approved' ? 'ok' : ($pta == 'Not Approved' ? 'bad' : 'mid');
                        $health = $stock->health;
                        $healthText = $health !== null && $health !== '' ? (is_numeric($health) ? $health . '%' : $health) : '—';
                        $companyLabel = $stock->company_name ?: ($isApple ? 'Apple' : null);
                        $delay = min($loop->index, 10) * 45;
                    @endphp

                    <article class="stock-item" style="animation-delay: {{ $delay }}ms">

                        {{-- MAIN --}}
                        <div class="item-main">
                            <div class="item-top">
                                @if ($companyLabel)
                                    <span class="item-company">{{ $companyLabel }}</span>
                                @endif
                                <span class="pta {{ $ptaClass }}"><i></i>{{ $pta }}</span>
                                @if ($stock->purchasing_from)
                                    <span class="from-tag"><i class="bx bx-store-alt"></i>{{ $stock->purchasing_from }}</span>
                                @endif
                                <span class="item-date">{{ $stock->created_at->format('d M Y') }}</span>
                            </div>

                            <a href="{{ route('stock.view', ['id' => $stock->id]) }}" class="item-model"
                                title="View Details">{{ $stock->model_name }}</a>

                            <div class="specs">
                                <div class="sp"><small>ROM</small><b>{{ $stock->rom ?: '—' }}</b></div>
                                @if ($isApple)
                                    <div class="sp"><small>Battery Health</small><b>{{ $healthText }}</b></div>
                                @else
                                    <div class="sp"><small>RAM</small><b>{{ $stock->ram ?: '—' }}</b></div>
                                @endif
                                 <div class="sp"><small>Sim Type</small><b>{{ $stock->sim_status ?: '—' }}</b></div>
                                <div class="sp"><small>Activation Status</small><b>{{ $stock->activation_status ?: '—' }}</b></div>
                                <div class="sp"><small>Country Lock Status</small><b>{{ $stock->country_status ?: '—' }}</b></div>
                            </div>

                            <div class="imeis">
                                <span><small>IMEI 1</small><b>{{ $stock->imei1 }}</b></span>
                                @if ($stock->imei2)
                                    <span><small>IMEI 2</small><b>{{ $stock->imei2 }}</b></span>
                                @endif
                            </div>
                        </div>

                        {{-- PRICE --}}
                        <div class="item-price">
                            <a class="sale"
                                href="{{ route('stock.edit', ['type' => request()->type, 'id' => $stock->id]) }}"
                                title="Edit Stock">
                                <small>Sale price</small>
                                <b>Rs. {{ number_format($stock->sale) }}</b>
                            </a>

                            <button type="button" class="purchase-toggle" onclick="togglePurchase(this)"
                                aria-pressed="false" title="Show / hide purchase price">
                                <span class="pt-label"><i class="bx bx-show"></i> Purchase</span>
                                <span class="pt-mask">Rs. ••••••</span>
                                <span class="pt-real">Rs. {{ number_format($stock->purchase) }}</span>
                            </button>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="item-actions">
                            <button type="button" class="btn btn-dark btn-sold"
                                onclick="openMarkAsSoldModal({{ $stock->id }}, {{ $stock->sale }})">
                                <i class="bx bx-check-circle"></i> Mark Sold
                            </button>
                            <a class="btn btn-outline-dark btn-icon"
                                href="{{ route('stock.view', ['id' => $stock->id]) }}" title="View Details">
                                <i class="bx bx-show"></i>
                            </a>
                            <a class="btn btn-outline-dark btn-icon"
                                href="{{ route('stock.edit', ['type' => request()->type, 'id' => $stock->id]) }}"
                                title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $stocks->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="card p-4 mt-2 empty-card text-center">
                <i class="bx bx-mobile-alt" style="font-size:48px"></i>
                <h4 class="h4 fw-normal text-muted mt-2 mb-0">No Data Found!</h4>
            </div>
        @endif

    </div>

    {{-- ============ MARK AS SOLD MODAL ============ --}}
    <div class="modal fade" id="markAsSoldModal" tabindex="-1" role="dialog" aria-labelledby="markAsSoldModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="markAsSoldModalLabel">Mark As Sold</h5>
                    <button type="button" class="btn-close" aria-label="Close" onclick="handleModalClose()"></button>
                </div>
                <form id="markAsSoldForm" action="{{ route('stock.markAsSold') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="stock_id" id="stockId">

                        <div class="row mt-2">
                            <div class="col-lg-6 col-6">
                                <div class="form-group">
                                    <label for="salePrice" class="fw-bold text-dark mb-1">Sale Price <span
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
                                        document.getElementById('soldOutDate').value = new Date().toISOString().split('T')[0];
                                    </script>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-12 col-12">
                                <div class="form-group">
                                    <label for="accountSelect" class="fw-bold text-dark mb-1">Select Account <span
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

                        <div class="row mt-2" id="buyerFields" style="display: none">
                            <div class="col-lg-6 col-6">
                                <div class="form-group">
                                    <label class="fw-bold text-dark mb-1">Buyer Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Buyer Name" id="buyerName"
                                        name="buyer_name">
                                </div>
                            </div>
                            <div class="col-lg-6 col-6">
                                <div class="form-group">
                                    <label class="fw-bold text-dark mb-1">Buyer Phone (optional)</label>
                                    <input type="text" maxlength="12" placeholder="0399-99999999"
                                        data-inputmask="'mask': '0399-99999999'"
                                        class="form-control @error('buyer_phone') is-invalid @enderror"
                                        name="buyer_phone" id="buyerPhone">
                                    @error('buyer_phone')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-12 col-12">
                                <div class="form-group">
                                    <label class="fw-bold text-dark mb-1">Backup Days <span
                                            class="text-danger">*</span></label>
                                    <select name="backup" required class="form-select">
                                        <option value="">Select Backup</option>
                                        @foreach (['1 Day', '2 Days', '3 Days', '4 Days', '5 Days', '6 Days', '7 Days', '10 Days', '14 Days'] as $b)
                                            <option value="{{ $b }}">{{ $b }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" onclick="handleModalClose()">Close</button>
                        <button type="submit" class="btn btn-dark" id="confirmMarkAsSold">Mark As Sold & Generate
                            Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============ STYLES (inline) ============ --}}
    <style>
        :root {
            --ink: #111;
            --ink-2: #555;
            --ink-3: #8a8a8a;
            --hair: #e7e7e7;
            --paper: #fff;
            --ease: cubic-bezier(.22, .61, .36, 1);
        }

        /* ---------- header ---------- */
        .stock-header {
            border: 1.5px solid var(--ink);
        }

        .custom-back-button {
            font-size: 16px;
            height: 100%;
            min-height: 52px;
            width: 100%;
            border-radius: 0;
            text-decoration: none;
            transition: all .3s var(--ease);
            font-weight: 500;
        }

        .custom-back-button:hover {
            background-color: #2b2b2b;
        }

        .custom-back-button i {
            font-size: 18px;
            transition: transform .3s var(--ease);
        }

        .custom-back-button:hover i {
            transform: rotate(90deg);
        }

        .count-pill {
            font-size: 12px;
            font-weight: 600;
            padding: 3px 10px;
            border: 1.5px solid var(--ink);
            border-radius: 50rem;
            color: var(--ink);
            white-space: nowrap;
        }

        .purchase-switch {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-left: 0;
        }

        .purchase-switch .form-check-input {
            width: 2.7em;
            height: 1.4em;
            margin: 0;
            float: none;
            cursor: pointer;
            border: 1.5px solid var(--ink);
            background-color: #fff;
            transition: background-color .3s var(--ease), background-position .3s var(--ease);
            --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23111'/%3e%3c/svg%3e");
        }

        .purchase-switch .form-check-input:checked {
            background-color: var(--ink);
            border-color: var(--ink);
            --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        }

        .purchase-switch .form-check-input:focus {
            box-shadow: 0 0 0 .2rem rgba(0, 0, 0, .15);
            border-color: var(--ink);
        }

        .purchase-switch label {
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            color: var(--ink);
            user-select: none;
        }

        /* ---------- filters ---------- */
        .filter-card {
            border: 1.5px solid var(--ink);
            border-radius: 0;
        }

        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: var(--ink);
            box-shadow: 0 0 0 .2rem rgba(0, 0, 0, .1);
        }

        /* ---------- list ---------- */
        .stock-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        @keyframes itemIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .stock-item {
            display: grid;
            grid-template-columns: 1fr 210px auto;
            align-items: center;
            gap: 28px;
            background: var(--paper);
            border: 1px solid var(--hair);
            border-radius: 14px;
            padding: 18px 22px;
            opacity: 0;
            animation: itemIn .5s var(--ease) forwards;
            transition: border-color .25s, box-shadow .25s, transform .25s var(--ease);
        }

        .stock-item:hover {
            border-color: var(--ink);
            box-shadow: 0 8px 22px rgba(0, 0, 0, .07);
            transform: translateY(-1px);
        }

        /* ---------- main block ---------- */
        .item-main {
            min-width: 0;
        }

        .item-top {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 4px;
        }

        .item-company {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--ink-3);
        }

        .pta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--ink);
            padding: 2px 10px 2px 8px;
            border: 1px solid var(--hair);
            border-radius: 50rem;
            background: #fafafa;
        }

        .pta i {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
        }

        .pta.ok i {
            background: #16a34a;
        }

        .pta.bad i {
            background: #dc2626;
        }

        .pta.mid i {
            background: #f59e0b;
        }

        .from-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            font-weight: 600;
            padding: 2px 10px;
            border: 1px solid var(--ink);
            border-radius: 50rem;
            color: var(--ink);
        }

        .item-date {
            margin-left: auto;
            font-size: 12px;
            color: var(--ink-3);
        }

        .item-model {
            display: inline-block;
            font-size: 20px;
            font-weight: 700;
            color: var(--ink);
            text-decoration: none;
            line-height: 1.3;
            margin-bottom: 12px;
            background-image: linear-gradient(var(--ink), var(--ink));
            background-repeat: no-repeat;
            background-position: 0 100%;
            background-size: 0 2px;
            transition: background-size .35s var(--ease);
        }

        .item-model:hover {
            color: var(--ink);
            background-size: 100% 2px;
        }

        /* specs */
        .specs {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .sp {
            padding: 0 22px;
            border-left: 1px solid var(--hair);
            line-height: 1.3;
        }

        .sp:first-child {
            padding-left: 0;
            border-left: 0;
        }

        .sp small,
        .imeis small,
        .sale small {
            display: block;
            font-size: 10px;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--ink-3);
        }

        .sp b {
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
        }

        /* imeis */
        .imeis {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 26px;
        }

        .imeis span {
            display: flex;
            align-items: baseline;
            gap: 8px;
        }

        .imeis small {
            display: inline;
        }

        .imeis b {
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: .05em;
            color: var(--ink-2);
        }

        /* ---------- price ---------- */
        .item-price {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sale {
            display: block;
            background: var(--ink);
            color: #fff;
            border-radius: 10px;
            padding: 10px 14px;
            text-decoration: none;
            transition: transform .25s var(--ease), box-shadow .25s var(--ease);
        }

        .sale:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, .22);
        }

        .sale small {
            color: #aaa;
        }

        .sale b {
            font-size: 20px;
            font-weight: 800;
            white-space: nowrap;
        }

        /* per-item purchase toggle */
        .purchase-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            width: 100%;
            background: #fff;
            border: 1px solid var(--ink);
            border-radius: 10px;
            padding: 7px 12px;
            font-size: 12.5px;
            color: var(--ink);
            cursor: pointer;
            transition: background .25s, color .25s;
        }

        .purchase-toggle:hover {
            background: #f4f4f4;
        }

        .pt-label {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
            color: var(--ink-2);
        }

        .pt-mask,
        .pt-real {
            font-weight: 700;
            white-space: nowrap;
        }

        .pt-real {
            display: none;
        }

        .purchase-toggle.open {
            background: var(--ink);
            color: #fff;
        }

        .purchase-toggle.open .pt-label {
            color: #cfcfcf;
        }

        .purchase-toggle.open .pt-mask {
            display: none;
        }

        .purchase-toggle.open .pt-real {
            display: inline;
            animation: fadeNum .3s var(--ease);
        }

        @keyframes fadeNum {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* ---------- actions ---------- */
        .item-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .item-actions .btn {
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: transform .2s var(--ease), background .2s, color .2s, box-shadow .2s;
        }

        .btn-sold {
            height: 38px;
            padding: 0 16px;
        }

        .btn-icon {
            width: 38px;
            height: 38px;
            padding: 0;
            font-size: 18px;
        }

        .item-actions .btn:hover {
            transform: translateY(-2px);
        }

        .btn-outline-dark:hover {
            background: var(--ink);
            color: #fff;
        }

        .empty-card {
            border: 1.5px dashed var(--ink);
            border-radius: 12px;
        }

        /* ---------- select2 ---------- */
        .select2-container--default .select2-selection--single {
            display: block;
            width: 100%;
            padding: .3rem .2rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: .375rem;
            height: auto;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50%;
            right: .32rem;
            transform: translateY(-50%);
            height: auto;
        }

        /* ---------- responsive ---------- */
        @media (max-width: 1199.98px) {
            .stock-item {
                grid-template-columns: 1fr 200px;
                gap: 16px 24px;
            }

            .item-actions {
                grid-column: 1 / -1;
                padding-top: 14px;
                border-top: 1px solid var(--hair);
            }

            .btn-sold {
                flex: 1;
            }
        }

        @media (max-width: 767.98px) {
            .stock-item {
                grid-template-columns: 1fr;
                padding: 16px;
            }

            .item-price {
                flex-direction: row;
            }

            .item-price>* {
                flex: 1;
            }

            .sp {
                padding: 0 14px;
            }

            .item-date {
                margin-left: 0;
            }
        }

        @media (max-width: 479.98px) {
            .item-price {
                flex-direction: column;
            }

            .specs {
                row-gap: 10px;
            }

            .sp {
                width: 50%;
                padding: 0;
                border-left: 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .stock-item,
            .stock-item *,
            .pt-real {
                animation: none !important;
                transition: none !important;
            }

            .stock-item {
                opacity: 1;
            }
        }
    </style>

    {{-- ============ SCRIPTS (inline) ============ --}}
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

        // Account -> buyer fields
        document.getElementById('accountSelect').addEventListener('change', function() {
            const buyerFields = document.getElementById('buyerFields');
            const buyerName = document.getElementById('buyerName');
            const buyerPhone = document.getElementById('buyerPhone');
            const selectedOption = this.options[this.selectedIndex];

            if (this.value == 1) {
                buyerFields.style.display = 'flex';
                buyerName.value = '';
                buyerPhone.value = '';
                buyerName.required = true;
            } else if (this.value) {
                buyerFields.style.display = 'none';
                buyerName.value = selectedOption.dataset.name || '';
                buyerPhone.value = selectedOption.dataset.phone || '';
                buyerName.required = false;
            } else {
                buyerFields.style.display = 'flex';
                buyerName.value = '';
                buyerPhone.value = '';
            }
        });

        // ---- Purchase price: per-item toggle + global switch ----
        function setToggle(btn, open) {
            btn.classList.toggle('open', open);
            btn.setAttribute('aria-pressed', open ? 'true' : 'false');
            const icon = btn.querySelector('.pt-label i');
            if (icon) icon.className = open ? 'bx bx-hide' : 'bx bx-show';
        }

        function syncSwitch() {
            const sw = document.getElementById('purchaseSwitch');
            const all = document.querySelectorAll('.purchase-toggle');
            if (!sw || !all.length) return;
            sw.checked = [...all].every(b => b.classList.contains('open'));
        }

        // click on one phone's purchase button only affects that phone
        function togglePurchase(btn) {
            setToggle(btn, !btn.classList.contains('open'));
            syncSwitch();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sw = document.getElementById('purchaseSwitch');
            const all = document.querySelectorAll('.purchase-toggle');
            if (!sw) return;

            let on = false;
            try { on = localStorage.getItem('showPurchasePrice') === 'true'; } catch (e) {}
            sw.checked = on;
            all.forEach(b => setToggle(b, on));

            // the switch controls every phone at once
            sw.addEventListener('change', function() {
                all.forEach(b => setToggle(b, sw.checked));
                try { localStorage.setItem('showPurchasePrice', sw.checked); } catch (e) {}
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script>
        $('#model-select').select2({
            placeholder: 'Select Model',
            allowClear: true
        });
        $('#company-select').select2({
            placeholder: 'Select Company',
            allowClear: true
        });
    </script>
@endsection

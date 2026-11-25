@extends('dashboard.master2')
@section('admin_title', 'Admin | Stocks | ' . (request()->type == 'apple' ? 'Apple Phones' : 'Other Phones'))
@section('content2')

    @php
        $isApple = request()->type == 'apple';
        $pageTitle = $isApple ? 'Apple Phones' : 'Other Phones';
        $hasFilter = request()->model_name || request()->company_name || request()->imei1 || request()->pta_status;
        $totalStock = method_exists($stocks, 'total') ? $stocks->total() : $stocks->count();
    @endphp

    <div class="container-fluid px-3 stock-page">

        {{-- ============ HEADER ============ --}}
        <div class="card shadow-sm bg-white rounded-0 stock-header">
            <span class="scan-line"></span>
            <div class="row g-0">
                <div class="col-lg-3 col-6 col-md-4 col-sm-5">
                    <a href="{{ route('create.stock', ['type' => request()->type]) }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center fx-ripple">
                        <i class="bx bx-plus me-1"></i> Add New Stock
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-8 col-sm-7">
                    <div class="d-flex align-items-center justify-content-between h-100 px-3 py-2">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 d-none d-md-block page-title">{{ $pageTitle }}</h3>
                            <h5 class="mb-0 d-block d-md-none page-title">{{ $pageTitle }}</h5>
                            <span class="count-pill ms-3 d-none d-sm-inline-flex">
                                <i class="live-dot"></i>
                                <span data-count="{{ $totalStock }}">{{ $totalStock }}</span>&nbsp;in stock
                            </span>
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
                <input type="hidden" name="type" value="{{ request()->type }}">
                <div class="row">
                    @if ($isApple)
                        <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                            <select name="model_name" id="model-select" class="form-select" onchange="this.form.submit()">
                                <option value="">Select Model</option>
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
                                <option value="">Select Company</option>
                                @foreach (other_companies() as $company)
                                    <option value="{{ $company }}"
                                        {{ request()->company_name == $company ? 'selected' : '' }}>{{ $company }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                        <input type="text" inputmode="numeric" pattern="\d{15}" maxlength="15"
                            placeholder="IMEI 1 (15 digits)" name="imei1" value="{{ request()->imei1 }}"
                            class="form-control">
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                        <select name="pta_status" class="form-select" onchange="this.form.submit()">
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
                                class="btn btn-outline-dark fx-ripple">Clear</a>
                            <button type="submit" title="Search" class="btn btn-dark fx-ripple">
                                <i class="bx bx-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        @if ($stocks->count() > 0 && $hasFilter)
            <div class="alert alert-dark rounded-0 mt-3 mb-2 py-2 result-alert">
                <strong>{{ $stocks->count() }} {{ $stocks->count() == 1 ? 'Result' : 'Results' }} Found</strong>
            </div>
        @elseif ($stocks->count() < 1 && $hasFilter)
            <div class="alert alert-warning rounded-0 mt-3 mb-2 py-2 result-alert">
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

                        $from = strtolower(trim($stock->purchasing_from ?? ''));
                        $fromIcon = 'bx-store-alt';
                        $fromLabel = null;
                        if ($from === 'dealer') {
                            $fromLabel = 'DEALER' . ($stock->dealer_name ? ' - ' . $stock->dealer_name : '');
                        } elseif ($from === 'local') {
                            $fromIcon = 'bx-user';
                            $fromLabel = 'LOCAL' . ($stock->pushasing_from_name ? ' - ' . $stock->pushasing_from_name : '');
                        } elseif ($from !== '') {
                            $fromLabel = $stock->purchasing_from;
                        }
                    @endphp

                    <article class="stock-item reveal" style="--i: {{ min($loop->index, 10) }}">
                        <span class="edge-glow"></span>

                        {{-- MAIN --}}
                        <div class="item-main">
                            <div class="item-top">
                                <span
                                    class="row-no">{{ str_pad(($stocks->firstItem() ?? 1) + $loop->index, 2, '0', STR_PAD_LEFT) }}</span>
                                @if ($companyLabel)
                                    <span class="item-company">{{ $companyLabel }}</span>
                                @endif
                                <span class="pta {{ $ptaClass }}"><i></i>{{ $pta }}</span>
                                <span class="tag-available"><i></i>Available</span>
                                @if ($fromLabel)
                                    <span class="from-tag"><i class="bx {{ $fromIcon }}"></i>{{ $fromLabel }}</span>
                                @endif
                                <span class="item-date"><i class="bx bx-calendar"></i>
                                    {{ $stock->created_at->format('d M Y') }}</span>
                            </div>

                            <a href="{{ route('stock.view', ['id' => $stock->id]) }}"
                                class="item-model"
                                title="View Details">
                                {{ $stock->model_name }}{{ $stock->color ? ' - ' . $stock->color : '' }}
                            </a>

                            <div class="specs">
                                <div class="sp"><small>Storage</small><b>{{ $stock->rom ?: '—' }}</b></div>
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
                                <span class="imei-chip" data-copy="{{ $stock->imei1 }}" role="button" tabindex="0"
                                    title="Click to copy IMEI 1">
                                    <small>IMEI 1</small>
                                    <b>{{ $stock->imei1 }}</b>
                                    <button type="button" class="copy-btn" aria-label="Copy IMEI 1">
                                        <i class="bx bx-copy"></i>
                                    </button>
                                </span>
                                @if ($stock->imei2)
                                    <span class="imei-chip" data-copy="{{ $stock->imei2 }}" role="button"
                                        tabindex="0" title="Click to copy IMEI 2">
                                        <small>IMEI 2</small>
                                        <b>{{ $stock->imei2 }}</b>
                                        <button type="button" class="copy-btn" aria-label="Copy IMEI 2">
                                            <i class="bx bx-copy"></i>
                                        </button>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- PRICE --}}
                        <div class="item-price">
                            <a class="sale"
                                href="{{ route('stock.edit', ['type' => request()->type, 'id' => $stock->id]) }}"
                                title="Edit Stock">
                                <small>Sale price</small>
                                <b>Rs. <span data-count="{{ (int) $stock->sale }}">{{ number_format($stock->sale) }}</span></b>
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
                            <button type="button" class="btn btn-dark btn-sold fx-ripple"
                                onclick="openMarkAsSoldModal({{ $stock->id }}, {{ (float) $stock->sale }})">
                                <i class="bx bx-check-circle"></i> Mark Sold
                            </button>
                            <a class="btn btn-outline-dark btn-icon fx-ripple"
                                href="{{ route('stock.view', ['id' => $stock->id]) }}" title="View Details">
                                <i class="bx bx-show"></i>
                            </a>
                            <a class="btn btn-outline-dark btn-icon fx-ripple"
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
                <i class="bx bx-mobile-alt empty-icon"></i>
                <h4 class="h4 fw-normal text-muted mt-2 mb-0">No Data Found!</h4>
            </div>
        @endif

    </div>

    {{-- ============ TOAST ============ --}}
    <div class="toast-stack" id="toastStack" aria-live="polite"></div>

    {{-- ============ MARK AS SOLD MODAL ============ --}}
    <div class="modal fade sold-modal" id="markAsSoldModal" tabindex="-1" aria-labelledby="markAsSoldModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="markAsSoldModalLabel"><i class="bx bx-check-shield"></i> Mark As Sold
                    </h5>
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
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
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
                                    <input type="text" maxlength="12" inputmode="numeric" autocomplete="off"
                                        placeholder="0300-1234567"
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
                            <div class="col-12">
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
                        <button type="button" class="btn btn-outline-dark fx-ripple"
                            onclick="handleModalClose()">Close</button>
                        <button type="submit" class="btn btn-dark fx-ripple" id="confirmMarkAsSold">
                            <span class="btn-text">Mark As Sold & Generate Invoice</span>
                            <span class="btn-spin spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============ STYLES ============ --}}
    <style>
        :root {
            --ink: #0b0b0f;
            --ink-2: #4b4b55;
            --ink-3: #8a8a96;
            --hair: #e6e6ec;
            --paper: #fff;
            --accent: #00e0b0;
            --accent-2: #6a5cff;
            --ease: cubic-bezier(.22, .61, .36, 1);
            --spring: cubic-bezier(.34, 1.56, .64, 1);
        }

        .stock-page .page-title {
            font-family: cursive;
            background: linear-gradient(90deg, var(--ink), var(--accent-2), var(--ink));
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: titleFlow 6s linear infinite;
        }

        @keyframes titleFlow {
            to {
                background-position: 200% center;
            }
        }

        /* ---------- header ---------- */
        .stock-header {
            border: 1.5px solid var(--ink);
            position: relative;
            overflow: hidden;
            animation: dropIn .6s var(--ease) both;
        }

        @keyframes dropIn {
            from {
                opacity: 0;
                transform: translateY(-14px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .scan-line {
            position: absolute;
            left: 0;
            bottom: 0;
            height: 2px;
            width: 40%;
            background: linear-gradient(90deg, transparent, var(--accent), var(--accent-2), transparent);
            animation: scan 3.2s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes scan {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(260%);
            }
        }

        .custom-back-button {
            font-size: 16px;
            height: 100%;
            min-height: 52px;
            width: 100%;
            border-radius: 0;
            text-decoration: none;
            font-weight: 500;
            transition: background .3s var(--ease), letter-spacing .3s var(--ease);
        }

        .custom-back-button:hover {
            background-color: #22222b;
            letter-spacing: .03em;
        }

        .custom-back-button i {
            font-size: 18px;
            transition: transform .4s var(--spring);
        }

        .custom-back-button:hover i {
            transform: rotate(180deg) scale(1.15);
        }

        .count-pill {
            align-items: center;
            gap: 7px;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 12px;
            border: 1.5px solid var(--ink);
            border-radius: 50rem;
            color: var(--ink);
            white-space: nowrap;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent);
            display: inline-block;
            animation: pulseDot 1.8s ease-in-out infinite;
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
            transition: background-color .3s var(--ease), background-position .35s var(--spring);
            --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%230b0b0f'/%3e%3c/svg%3e");
        }

        .purchase-switch .form-check-input:checked {
            background-color: var(--ink);
            border-color: var(--ink);
            --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%2300e0b0'/%3e%3c/svg%3e");
        }

        .purchase-switch .form-check-input:focus {
            box-shadow: 0 0 0 .2rem rgba(106, 92, 255, .2);
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
            animation: dropIn .6s .08s var(--ease) both;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            transition: border-color .25s, box-shadow .25s, transform .25s var(--ease);
        }

        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: var(--ink);
            box-shadow: 0 0 0 .2rem rgba(106, 92, 255, .15);
            transform: translateY(-1px);
        }

        .result-alert {
            animation: dropIn .5s var(--ease) both;
        }

        /* ---------- list ---------- */
        .stock-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .stock-item {
            --mx: 50%;
            --my: 50%;
            position: relative;
            display: grid;
            grid-template-columns: 1fr 210px auto;
            align-items: center;
            gap: 28px;
            background: var(--paper);
            border: 1px solid var(--hair);
            border-radius: 16px;
            padding: 18px 22px;
            overflow: hidden;
            isolation: isolate;
            transition: border-color .3s, box-shadow .3s, transform .35s var(--ease);
        }

        /* scroll reveal (JS adds .in) */
        .reveal {
            opacity: 0;
            transform: translateY(22px) scale(.985);
        }

        .reveal.in {
            opacity: 1;
            transform: none;
            transition: opacity .6s var(--ease), transform .7s var(--ease), border-color .3s, box-shadow .3s;
            transition-delay: calc(var(--i) * 55ms), calc(var(--i) * 55ms), 0s, 0s;
        }

        .reveal.in.settled {
            transition-delay: 0s;
        }

        /* cursor-following glow */
        .stock-item::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            background: radial-gradient(420px circle at var(--mx) var(--my), rgba(106, 92, 255, .09), transparent 60%);
            opacity: 0;
            transition: opacity .3s;
        }

        /* animated gradient edge */
        .edge-glow {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--accent), var(--accent-2));
            transform: scaleY(0);
            transform-origin: top;
            transition: transform .45s var(--ease);
            z-index: 1;
        }

        .stock-item:hover {
            border-color: var(--ink);
            box-shadow: 0 14px 34px rgba(11, 11, 15, .1);
            transform: translateY(-2px);
        }

        .stock-item:hover::before {
            opacity: 1;
        }

        .stock-item:hover .edge-glow {
            transform: scaleY(1);
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
            background: #fafafc;
        }

        .pta i {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
        }

        .pta.ok i {
            background: #16a34a;
            animation: pulseDot 2s ease-in-out infinite;
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

        .row-no {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 22px;
            padding: 0 7px;
            border-radius: 6px;
            background: var(--ink);
            color: #fff;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: .04em;
            transition: transform .3s var(--spring);
        }

        .stock-item:hover .row-no {
            transform: scale(1.12) rotate(-4deg);
        }

        .tag-available {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 600;
            padding: 2px 10px 2px 8px;
            border: 1px solid #bbf7d0;
            border-radius: 50rem;
            background: #f0fdf4;
            color: #15803d;
        }

        .tag-available i {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #16a34a;
            display: inline-block;
            animation: pulseDot 2s ease-in-out infinite;
        }

        @keyframes pulseDot {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(22, 163, 74, .5);
            }

            50% {
                box-shadow: 0 0 0 5px rgba(22, 163, 74, 0);
            }
        }

        .item-date {
            margin-left: auto;
            font-size: 12px;
            color: var(--ink-3);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .item-model {
            display: inline-block;
            font-size: 20px;
            font-weight: 700;
            color: var(--ink);
            text-decoration: none;
            line-height: 1.3;
            margin-bottom: 12px;
            background-image: linear-gradient(90deg, var(--accent), var(--accent-2));
            background-repeat: no-repeat;
            background-position: 0 100%;
            background-size: 0 3px;
            transition: background-size .4s var(--ease), letter-spacing .3s var(--ease);
        }

        .item-model:hover {
            color: var(--ink);
            background-size: 100% 3px;
            letter-spacing: .01em;
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
            transition: transform .3s var(--ease);
        }

        .sp:hover {
            transform: translateY(-2px);
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

        /* ---------- imeis + copy ---------- */
        .imeis {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 12px;
        }

        .imei-chip {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 4px 6px 4px 10px;
            border: 1px dashed var(--hair);
            border-radius: 10px;
            background: #fafafc;
            cursor: pointer;
            overflow: hidden;
            transition: border-color .25s, background .25s, transform .25s var(--ease), box-shadow .25s;
        }

        .imei-chip:hover,
        .imei-chip:focus-visible {
            border-color: var(--ink);
            background: #fff;
            outline: none;
            box-shadow: 0 4px 12px rgba(11, 11, 15, .08);
            transform: translateY(-1px);
        }

        .imei-chip:active {
            transform: scale(.97);
        }

        .imei-chip small {
            display: inline;
        }

        .imei-chip b {
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: .05em;
            color: var(--ink-2);
        }

        .copy-btn {
            width: 26px;
            height: 26px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: var(--ink-2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            cursor: pointer;
            transition: background .25s, color .25s, transform .3s var(--spring);
        }

        .imei-chip:hover .copy-btn {
            background: var(--ink);
            color: #fff;
            transform: scale(1.08);
        }

        .imei-chip.copied {
            border-color: #16a34a;
            background: #f0fdf4;
            animation: copiedPop .5s var(--spring);
        }

        .imei-chip.copied .copy-btn {
            background: #16a34a;
            color: #fff;
        }

        .imei-chip.copied b {
            color: #15803d;
        }

        @keyframes copiedPop {
            0% {
                transform: scale(1);
            }

            40% {
                transform: scale(1.06);
            }

            100% {
                transform: scale(1);
            }
        }

        /* ---------- price ---------- */
        .item-price {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sale {
            display: block;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #0b0b0f, #23232e);
            color: #fff;
            border-radius: 12px;
            padding: 10px 14px;
            text-decoration: none;
            transition: transform .3s var(--ease), box-shadow .3s var(--ease);
        }

        .sale::after {
            content: "";
            position: absolute;
            top: 0;
            left: -80%;
            width: 50%;
            height: 100%;
            background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .22), transparent);
            transform: skewX(-20deg);
            transition: left .7s var(--ease);
        }

        .sale:hover {
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 12px 22px rgba(11, 11, 15, .28);
        }

        .sale:hover::after {
            left: 130%;
        }

        .sale small {
            color: #aaa;
        }

        .sale b {
            font-size: 20px;
            font-weight: 800;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .purchase-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            width: 100%;
            background: #fff;
            border: 1px solid var(--ink);
            border-radius: 12px;
            padding: 7px 12px;
            font-size: 12.5px;
            color: var(--ink);
            cursor: pointer;
            transition: background .3s, color .3s, transform .2s var(--ease);
        }

        .purchase-toggle:hover {
            background: #f4f4f8;
        }

        .purchase-toggle:active {
            transform: scale(.97);
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
            color: #cfcfd6;
        }

        .purchase-toggle.open .pt-mask {
            display: none;
        }

        .purchase-toggle.open .pt-real {
            display: inline;
            animation: fadeNum .35s var(--ease);
        }

        @keyframes fadeNum {
            from {
                opacity: 0;
                transform: translateY(6px);
                filter: blur(3px);
            }

            to {
                opacity: 1;
                transform: none;
                filter: none;
            }
        }

        /* ---------- actions ---------- */
        .item-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .item-actions .btn {
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: transform .25s var(--spring), background .2s, color .2s, box-shadow .2s;
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
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(11, 11, 15, .15);
        }

        .btn-sold i {
            transition: transform .4s var(--spring);
        }

        .btn-sold:hover i {
            transform: scale(1.25) rotate(360deg);
        }

        .btn-outline-dark:hover {
            background: var(--ink);
            color: #fff;
        }

        .empty-card {
            border: 1.5px dashed var(--ink);
            border-radius: 14px;
            animation: dropIn .6s var(--ease) both;
        }

        .empty-icon {
            font-size: 48px;
            animation: floaty 3s ease-in-out infinite;
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        /* ---------- ripple ---------- */
        .fx-ripple {
            position: relative;
            overflow: hidden;
        }

        .ripple-wave {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .45);
            transform: scale(0);
            animation: ripple .6s ease-out forwards;
            pointer-events: none;
        }

        .btn-outline-dark .ripple-wave {
            background: rgba(11, 11, 15, .2);
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* ---------- toast ---------- */
        .toast-stack {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 2000;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }

        .toast-pop {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--ink);
            color: #fff;
            padding: 11px 16px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 600;
            box-shadow: 0 12px 28px rgba(0, 0, 0, .3);
            border-left: 3px solid var(--accent);
            animation: toastIn .45s var(--spring) both;
        }

        .toast-pop i {
            color: var(--accent);
            font-size: 18px;
        }

        .toast-pop.err {
            border-left-color: #ef4444;
        }

        .toast-pop.err i {
            color: #ef4444;
        }

        .toast-pop.out {
            animation: toastOut .35s var(--ease) forwards;
        }

        .toast-pop code {
            font-family: ui-monospace, Menlo, Consolas, monospace;
            letter-spacing: .05em;
            color: #d6d6e0;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(40px) scale(.9);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        @keyframes toastOut {
            to {
                opacity: 0;
                transform: translateX(40px);
            }
        }

        /* ---------- modal ---------- */
        .sold-modal.fade .modal-dialog {
            transform: translateY(40px) scale(.94);
            transition: transform .45s var(--spring), opacity .3s;
        }

        .sold-modal.show .modal-dialog {
            transform: none;
        }

        .sold-modal .modal-content {
            border: 1.5px solid var(--ink);
            border-radius: 16px;
            overflow: hidden;
        }

        .sold-modal .modal-header {
            background: var(--ink);
            color: #fff;
            border: 0;
        }

        .sold-modal .modal-title {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sold-modal .modal-title i {
            color: var(--accent);
            font-size: 22px;
        }

        .sold-modal .btn-close {
            filter: invert(1);
        }

        .sold-modal .form-control:focus,
        .sold-modal .form-select:focus {
            border-color: var(--ink);
            box-shadow: 0 0 0 .2rem rgba(106, 92, 255, .15);
        }

        .sold-modal .modal-body .row {
            animation: fadeNum .5s var(--ease) both;
        }

        #confirmMarkAsSold .btn-spin {
            display: none;
            margin-left: 8px;
        }

        #confirmMarkAsSold.loading .btn-spin {
            display: inline-block;
        }

        #confirmMarkAsSold.loading {
            pointer-events: none;
            opacity: .85;
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

            .toast-stack {
                left: 14px;
                right: 14px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .stock-page *,
            .toast-pop,
            .sold-modal * {
                animation: none !important;
                transition: none !important;
            }

            .reveal {
                opacity: 1;
                transform: none;
            }
        }
    </style>

    {{-- ============ SCRIPTS ============ --}}
    <script>
        /* ---------- toast ---------- */
        function showToast(html, isError) {
            const stack = document.getElementById('toastStack');
            if (!stack) return;
            const t = document.createElement('div');
            t.className = 'toast-pop' + (isError ? ' err' : '');
            t.innerHTML = '<i class="bx ' + (isError ? 'bx-error-circle' : 'bx-check-circle') + '"></i><span>' + html + '</span>';
            stack.appendChild(t);
            setTimeout(function() {
                t.classList.add('out');
                setTimeout(function() { t.remove(); }, 380);
            }, 2200);
        }

        /* ---------- copy IMEI ---------- */
        function copyText(text) {
            if (navigator.clipboard && window.isSecureContext) {
                return navigator.clipboard.writeText(text);
            }
            return new Promise(function(resolve, reject) {
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.setAttribute('readonly', '');
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                try {
                    document.execCommand('copy') ? resolve() : reject();
                } catch (e) {
                    reject(e);
                }
                ta.remove();
            });
        }

        function handleCopy(chip) {
            const value = chip.dataset.copy;
            if (!value) return;
            copyText(value).then(function() {
                const icon = chip.querySelector('.copy-btn i');
                chip.classList.add('copied');
                if (icon) icon.className = 'bx bx-check';
                showToast('IMEI copied <code>' + value + '</code>');
                setTimeout(function() {
                    chip.classList.remove('copied');
                    if (icon) icon.className = 'bx bx-copy';
                }, 1600);
            }).catch(function() {
                showToast('Could not copy IMEI', true);
            });
        }

        document.addEventListener('click', function(e) {
            const chip = e.target.closest('.imei-chip');
            if (chip) handleCopy(chip);
        });
        document.addEventListener('keydown', function(e) {
            if ((e.key === 'Enter' || e.key === ' ') && e.target.classList && e.target.classList.contains('imei-chip')) {
                e.preventDefault();
                handleCopy(e.target);
            }
        });

        /* ---------- ripple ---------- */
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.fx-ripple');
            if (!btn) return;
            const r = btn.getBoundingClientRect();
            const size = Math.max(r.width, r.height);
            const w = document.createElement('span');
            w.className = 'ripple-wave';
            w.style.width = w.style.height = size + 'px';
            w.style.left = (e.clientX - r.left - size / 2) + 'px';
            w.style.top = (e.clientY - r.top - size / 2) + 'px';
            btn.appendChild(w);
            setTimeout(function() { w.remove(); }, 650);
        });

        /* ---------- buyer phone: always starts with 03 -> 03XX-XXXXXXX (plain JS, no plugins) ---------- */
        const BUYER_PHONE_PREFIX     = '03';
        const BUYER_PHONE_MAX_DIGITS = 11;   // 0300-1234567
        const BUYER_PHONE_DASH_AT    = 4;

        const buyerPhoneField = document.getElementById('buyerPhone');

        function formatBuyerPhone(value) {
            let d = value.replace(/\D/g, '');

            if (d.startsWith('92')) d = d.substring(2);   // country code
            if (d.startsWith('0'))  d = d.substring(1);   // leading zero
            if (d.startsWith('3'))  d = d.substring(1);   // the "3" of "03"

            d = (BUYER_PHONE_PREFIX + d).substring(0, BUYER_PHONE_MAX_DIGITS);

            return d.length > BUYER_PHONE_DASH_AT
                ? d.substring(0, BUYER_PHONE_DASH_AT) + '-' + d.substring(BUYER_PHONE_DASH_AT)
                : d;
        }

        // Enforce the full format only while the buyer fields are visible,
        // so a hidden (account-filled) phone can never block the submit.
        function syncBuyerPhoneRules(visible) {
            if (visible) {
                buyerPhoneField.setAttribute('pattern', '03\\d{2}-\\d{7}');
                buyerPhoneField.setAttribute('title', 'Format: 03XX-XXXXXXX');
            } else {
                buyerPhoneField.removeAttribute('pattern');
                buyerPhoneField.removeAttribute('title');
            }
        }

        buyerPhoneField.addEventListener('input', function() {
            buyerPhoneField.value = formatBuyerPhone(buyerPhoneField.value);
        });

        // Show the "03" prefix as soon as the field is focused
        buyerPhoneField.addEventListener('focus', function() {
            if (buyerPhoneField.value === '') {
                buyerPhoneField.value = BUYER_PHONE_PREFIX;
                setTimeout(function() {
                    const len = buyerPhoneField.value.length;
                    try { buyerPhoneField.setSelectionRange(len, len); } catch (e) {}
                }, 0);
            }
        });

        // Phone is optional: if only the bare prefix is left, clear it again
        buyerPhoneField.addEventListener('blur', function() {
            if (buyerPhoneField.value === BUYER_PHONE_PREFIX) buyerPhoneField.value = '';
        });

        syncBuyerPhoneRules(false); // buyer fields start hidden

        /* ---------- mark as sold modal ---------- */
        function getSoldModal() {
            const el = document.getElementById('markAsSoldModal');
            if (window.bootstrap && bootstrap.Modal) return bootstrap.Modal.getOrCreateInstance(el);
            return null;
        }

        function openMarkAsSoldModal(stockId, salePrice) {
            document.getElementById('stockId').value = stockId;
            document.getElementById('salePrice').value = salePrice;
            document.getElementById('soldOutDate').value = new Date().toISOString().split('T')[0];
            const m = getSoldModal();
            if (m) m.show();
            else if (window.jQuery) jQuery('#markAsSoldModal').modal('show');
        }

        function handleModalClose() {
            const m = getSoldModal();
            if (m) m.hide();
            else if (window.jQuery) jQuery('#markAsSoldModal').modal('hide');
        }

        document.getElementById('accountSelect').addEventListener('change', function() {
            const buyerFields = document.getElementById('buyerFields');
            const buyerName = document.getElementById('buyerName');
            const buyerPhone = document.getElementById('buyerPhone');
            const opt = this.options[this.selectedIndex];

            if (this.value == 1) {
                buyerFields.style.display = 'flex';
                buyerName.value = '';
                buyerPhone.value = '';
                buyerName.required = true;
                syncBuyerPhoneRules(true);
            } else if (this.value) {
                buyerFields.style.display = 'none';
                buyerName.value = opt.dataset.name || '';
                buyerPhone.value = opt.dataset.phone || '';
                buyerName.required = false;
                syncBuyerPhoneRules(false);
            } else {
                buyerFields.style.display = 'flex';
                buyerName.value = '';
                buyerPhone.value = '';
                buyerName.required = false;
                syncBuyerPhoneRules(true);
            }
        });

        // loading state + double-submit guard
        document.getElementById('markAsSoldForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('confirmMarkAsSold');
            if (btn.classList.contains('loading')) {
                e.preventDefault();
                return;
            }
            btn.classList.add('loading');
        });

        /* ---------- purchase price toggles ---------- */
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
            sw.checked = [...all].every(function(b) { return b.classList.contains('open'); });
        }

        function togglePurchase(btn) {
            setToggle(btn, !btn.classList.contains('open'));
            syncSwitch();
        }

        /* ---------- count-up ---------- */
        function countUp(el) {
            const target = parseInt(el.dataset.count, 10);
            if (isNaN(target)) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            const dur = 900;
            const start = performance.now();
            function tick(now) {
                const p = Math.min((now - start) / dur, 1);
                const eased = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(target * eased).toLocaleString('en-US');
                if (p < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // today's date default for modal
            const sd = document.getElementById('soldOutDate');
            if (sd) sd.value = new Date().toISOString().split('T')[0];

            // global purchase switch
            const sw = document.getElementById('purchaseSwitch');
            const all = document.querySelectorAll('.purchase-toggle');
            if (sw) {
                let on = false;
                try { on = localStorage.getItem('showPurchasePrice') === 'true'; } catch (e) {}
                sw.checked = on && all.length > 0;
                all.forEach(function(b) { setToggle(b, on); });
                sw.addEventListener('change', function() {
                    all.forEach(function(b) { setToggle(b, sw.checked); });
                    try { localStorage.setItem('showPurchasePrice', sw.checked); } catch (e) {}
                });
            }

            // header counter
            document.querySelectorAll('.count-pill [data-count]').forEach(countUp);

            // scroll reveal + sale count-up + glow tracking
            const items = document.querySelectorAll('.stock-item');
            const reveal = function(el) {
                el.classList.add('in');
                el.querySelectorAll('.sale [data-count]').forEach(countUp);
                setTimeout(function() { el.classList.add('settled'); }, 1200);
            };
            if ('IntersectionObserver' in window) {
                const io = new IntersectionObserver(function(entries) {
                    entries.forEach(function(en) {
                        if (en.isIntersecting) {
                            reveal(en.target);
                            io.unobserve(en.target);
                        }
                    });
                }, { threshold: 0.08 });
                items.forEach(function(el) { io.observe(el); });
            } else {
                items.forEach(reveal);
            }

            items.forEach(function(el) {
                el.addEventListener('mousemove', function(e) {
                    const r = el.getBoundingClientRect();
                    el.style.setProperty('--mx', (e.clientX - r.left) + 'px');
                    el.style.setProperty('--my', (e.clientY - r.top) + 'px');
                });
            });
        });

        /* ---------- plugins (select2 only) ---------- */
        (function() {
            function loadScript(src, cb) {
                const s = document.createElement('script');
                s.src = src;
                s.onload = cb;
                document.body.appendChild(s);
            }

            function initPlugins() {
                const $ = window.jQuery;
                if (!$ || !$.fn.select2) return;
                $('#model-select').select2({ placeholder: 'Select Model', allowClear: true });
                $('#company-select').select2({ placeholder: 'Select Company', allowClear: true });
            }

            function withSelect2() {
                if (window.jQuery.fn.select2) return initPlugins();
                loadScript('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js', initPlugins);
            }

            document.addEventListener('DOMContentLoaded', function() {
                if (window.jQuery) withSelect2();
                else loadScript('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js', withSelect2);
            });
        })();
    </script>
@endsection

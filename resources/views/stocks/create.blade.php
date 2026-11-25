@extends('dashboard.master2')

@php
    /*
    |--------------------------------------------------------------------------
    | View state
    |--------------------------------------------------------------------------
    */
    $type    = request()->type;                       // 'apple' | 'others'
    $isApple = $type === 'apple';
    $isOther = $type === 'others';
    $isEdit  = $stock->id !== null;

    $action = request()->routeIs('stock.edit') ? 'Edit Stock' : 'Add New Stock';
    $title  = match ($type) {
        'apple'  => "$action | Apple Phones",
        'others' => "$action | Other Phones",
        default  => 'Title Not Found',
    };

    $formAction = $isEdit
        ? route('update.stock', ['id' => $stock->id])
        : route('submit.stock');

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    // Current value: old input first, then the model.
    $val = fn(string $field) => old($field, $stock->$field);

    // "selected" attribute for <option>.
    $sel = fn(string $field, $value) => $val($field) == $value ? 'selected' : '';

    /*
    |--------------------------------------------------------------------------
    | Option lists
    |--------------------------------------------------------------------------
    */
    $romOptions = ['1 GB', '2 GB', '4 GB', '8 GB', '16 GB', '32 GB', '64 GB', '128 GB', '256 GB', '512 GB', '1 TB', '2 TB'];

    $ramOptions = ['128 MB', '256 MB', '512 MB', '1 GB', '1.5 GB', '2 GB', '3 GB', '4 GB', '6 GB', '8 GB', '12 GB', '16 GB', '18 GB', '24 GB'];

    $ptaOptions = array_merge(
        ['Official Approved'],
        $isOther ? ['Patch Approved', 'CPID Approved'] : [],
        ['Not Approved', 'Not Approved (4 months remaining)', 'Not Approved (2 months remaining)']
    );

    $activationOptions = ['Active', 'Non Active'];

    $countryOptions = $isApple ? ['JV', 'Factory'] : ['Yes', 'No'];

    $simOptions = ['Single Physical SIM', 'Dual Physical SIM', 'Single eSIM', 'Dual eSIM', 'Physical SIM + eSIM'];

    $purchasingFromOptions = ['Local', 'Dealer'];

    // TIP: move this map into a helper (e.g. iphone_colors()) next to iphone_models().
    $iphoneColors = [
        // Black
        'Black' => '#000000', 'Space Black' => '#292929', 'Jet Black' => '#050505', 'Graphite' => '#414141',
        'Space Gray' => '#535353', 'Midnight' => '#191E2A', 'Midnight Black' => '#101010',
        'Onyx Black' => '#353839', 'Matte Black' => '#222222',
        // White / Silver
        'White' => '#FFFFFF', 'Silver' => '#C0C0C0', 'Starlight' => '#F0E5D3', 'Star White' => '#F5F5F0',
        'Cloud White' => '#F4F3EF', 'White Titanium' => '#E5E5E0', 'Glacier' => '#DCE7EA',
        'Cream' => '#FFFDD0', 'Ivory' => '#FFFFF0',
        // Gold
        'Gold' => '#D4AF37', 'Rose Gold' => '#B76E79', 'Champagne Gold' => '#E7C27D', 'Light Gold' => '#D9C5A3',
        // Red
        'Red' => '#D70018', 'PRODUCT RED' => '#C8102E', 'Red (PRODUCT RED)' => '#C8102E', 'Burgundy' => '#800020',
        'Wine Red' => '#722F37', 'Dark Red' => '#8B0000', 'Coral' => '#FF7F50',
        // Pink
        'Pink' => '#F5A9BC', 'Soft Pink' => '#F4C2C2', 'Rose Pink' => '#F4C2C2', 'Light Pink' => '#FFB6C1',
        // Blue
        'Blue' => '#4A90E2', 'Deep Blue' => '#173B65', 'Dark Blue' => '#00008B', 'Light Blue' => '#ADD8E6',
        'Midnight Blue' => '#191970', 'Pacific Blue' => '#345B75', 'Sierra Blue' => '#A9C3D5',
        'Blue Titanium' => '#526477', 'Ultramarine' => '#4B54C8', 'Sky Blue' => '#87CEEB',
        'Mist Blue' => '#A9C9D5', 'Space Blue' => '#3A5A78',
        // Green
        'Green' => '#5E8C61', 'Alpine Green' => '#53665A', 'Midnight Green' => '#45564B', 'Sage' => '#A3AC91',
        'Teal' => '#008C95', 'Mint' => '#BDECC8', 'Light Green' => '#90EE90', 'Dark Green' => '#006400',
        'Olive Green' => '#808000',
        // Yellow / Orange
        'Yellow' => '#F9D849', 'Orange' => '#FFA500', 'Cosmic Orange' => '#F47A38',
        // Purple
        'Purple' => '#A88BC5', 'Deep Purple' => '#594267', 'Lavender' => '#C8A2C8', 'Violet' => '#8F00FF',
        // Titanium
        'Titanium' => '#878681', 'Natural Titanium' => '#A7A49B', 'Black Titanium' => '#373737',
        'Desert Titanium' => '#BFA58A',
        // Other
        'Brown' => '#8B4513', 'Tan' => '#D2B48C', 'Rose' => '#FF007F', 'Gray' => '#808080', 'Grey' => '#808080',
    ];
@endphp

@section('admin_title', $title)

@section('content2')
    <div class="container-fluid px-3">

        {{-- ============================== Page header ============================== --}}
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                    <a href="{{ route('stock.index', ['type' => $type]) }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-sm-8 col-6">
                    <h3 class="mt-1 d-none d-md-block" style="font-family: cursive">{{ $title }}</h3>
                    <h5 class="mt-1 d-block d-md-none" style="font-family: cursive">{{ $title }}</h5>
                </div>
            </div>
        </div>

        {{-- ================================== Form ================================== --}}
        <div class="card p-3 mt-3">
            <form action="{{ $formAction }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                @if ($isApple)
                    <input type="hidden" name="company_name" value="Apple">
                @endif

                {{-- ---------------------------- Purchasing source ---------------------------- --}}
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <label class="fw-bold mb-2 text-dark">PURCHASING FROM<span class="text-danger">*</span></label>
                        <select name="purchasing_from" id="purchasing-from" required
                            class="form-select @error('purchasing_from') is-invalid @enderror">
                            <option value="">Select Option</option>
                            @foreach ($purchasingFromOptions as $option)
                                <option value="{{ $option }}" {{ $sel('purchasing_from', $option) }}>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('purchasing_from')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Dealer (shown when "Dealer") --}}
                    <div class="col-lg-4 col-md-4" id="dealer-information" style="display: none;">
                        <label class="fw-bold mb-2 text-dark">SELECT DEALER<span class="text-danger">*</span></label>
                        <select name="dealer_id" id="dealer-select"
                            class="form-select @error('dealer_id') is-invalid @enderror">
                            <option value="">Select Dealer</option>
                            @foreach ($dealers as $dealer)
                                <option value="{{ $dealer->id }}" {{ $sel('dealer_id', $dealer->id) }}>
                                    {{ $dealer->name }} ( {{ $dealer->bussiness_name }} )
                                </option>
                            @endforeach
                        </select>
                        @error('dealer_id')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                {{-- ---------------------- Seller information (shown when "Local") ---------------------- --}}
                <div id="seller-information" style="display: none;">
                    <div class="row mt-4">
                        <hr>
                        <h4 class="fw-bold mb-3 text-dark">Seller Information</h4>
                        <hr>

                        <div class="col-lg-4 col-md-4 col-6">
                            <label class="fw-bold mb-2 text-dark">NAME<span class="text-danger">*</span></label>
                            <input type="text" name="pushasing_from_name" id="name-field" placeholder="Name"
                                value="{{ $val('pushasing_from_name') }}"
                                class="form-control @error('pushasing_from_name') is-invalid @enderror">
                            @error('pushasing_from_name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-4 col-6">
                            <label class="fw-bold mb-2 text-dark">PHONE<span class="text-danger">*</span></label>
                            <input type="text" name="pushasing_from_phone" id="phone-field" maxlength="12" inputmode="numeric" autocomplete="off"
                                pattern="03\d{2}-\d{7}" title="Format: 03XX-XXXXXXX"
                                placeholder="0300-1234567" value="{{ $val('pushasing_from_phone') }}"
                                class="form-control @error('pushasing_from_phone') is-invalid @enderror">
                            @error('pushasing_from_phone')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <label class="fw-bold mb-2 text-dark">CNIC<span class="text-danger">*</span></label>
                            <input type="text" name="pushasing_from_cnic" id="cnic-field" maxlength="15" inputmode="numeric" autocomplete="off"
                                pattern="\d{5}-\d{7}-\d" title="Format: XXXXX-XXXXXXX-X"
                                placeholder="XXXXX-XXXXXXX-X" value="{{ $val('pushasing_from_cnic') }}"
                                class="form-control @error('pushasing_from_cnic') is-invalid @enderror">
                            @error('pushasing_from_cnic')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-4 mt-3">
                            <label class="fw-bold mb-2 text-dark">ADDRESS (Optional)</label>
                            <textarea name="pushasing_from_address" id="address-field" rows="3" placeholder="Address"
                                class="form-control @error('pushasing_from_address') is-invalid @enderror">{{ $val('pushasing_from_address') }}</textarea>
                            @error('pushasing_from_address')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ------------------------------ Stock information ------------------------------ --}}
                <div class="row mt-3">
                    <hr>
                    <h4 class="fw-bold text-dark mb-3">Stock Information</h4>
                    <hr>
                </div>

                {{-- Model / Company --}}
                <div class="row">
                    @if ($isApple)
                        <div class="col-lg-8 col-md-8 col-12">
                            <label class="fw-bold mb-2 text-dark">SELECT MODEL<span class="text-danger">*</span></label>
                            <select name="model_name" id="model-select" required
                                class="form-select @error('model_name') is-invalid @enderror">
                                <option value="">Select iPhone Model</option>
                                @foreach (iphone_models() as $model)
                                    <option value="{{ $model }}" {{ $sel('model_name', $model) }}>{{ $model }}</option>
                                @endforeach
                            </select>
                            @error('model_name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    @endif

                    @if ($isOther)
                        <div class="col-lg-4 col-md-4 col-6">
                            <label class="fw-bold mb-2 text-dark">SELECT COMPANY<span class="text-danger">*</span></label>
                            <select name="company_name" id="company-select" required
                                class="form-select @error('company_name') is-invalid @enderror">
                                <option value="">Select Company</option>
                                @foreach (other_companies() as $company)
                                    <option value="{{ $company }}" {{ $sel('company_name', $company) }}>{{ $company }}</option>
                                @endforeach
                            </select>
                            @error('company_name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-4 col-6">
                            <label class="fw-bold mb-2 text-dark">Model Name<span class="text-danger">*</span></label>
                            <input type="text" name="model_name" required placeholder="Model Name"
                                value="{{ $val('model_name') }}"
                                class="form-control @error('model_name') is-invalid @enderror">
                            @error('model_name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    @endif
                </div>

                {{-- IMEI --}}
                <div class="row mt-3">
                    <div class="col-lg-4 col-md-4 col-6">
                        <label class="fw-bold mb-2 text-dark">IMEI 1<span class="text-danger">*</span></label>
                        <input type="text" name="imei1" pattern="\d{15}" maxlength="15" required placeholder="IMEI"
                            value="{{ $val('imei1') }}" class="form-control @error('imei1') is-invalid @enderror">
                        @error('imei1')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-4 col-6">
                        <label class="fw-bold mb-2 text-dark">IMEI 2 (Optional)</label>
                        <input type="text" name="imei2" pattern="\d{15}" maxlength="15" placeholder="IMEI 2"
                            value="{{ $val('imei2') }}" class="form-control @error('imei2') is-invalid @enderror">
                        @error('imei2')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                {{-- Storage + (Battery & Color | RAM) --}}
                <div class="row mt-3">
                    <div class="col-lg-4 col-md-4 col-6">
                        <label class="fw-bold mb-2 text-dark">Storage / ROM<span class="text-danger">*</span></label>
                        <select name="rom" id="rom-select" required
                            class="form-select @error('rom') is-invalid @enderror">
                            <option value="">Select Option</option>
                            @foreach ($romOptions as $option)
                                <option value="{{ $option }}" {{ $sel('rom', $option) }}>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('rom')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    @if ($isApple)
                        <div class="col-lg-4 col-md-4 col-6">
                            <label class="fw-bold mb-2 text-dark">BATTERY HEALTH<span class="text-danger">*</span></label>
                            <input type="number" name="health" min="1" max="100" required
                                placeholder="Battery Health" value="{{ $val('health') }}"
                                class="form-control @error('health') is-invalid @enderror">
                            @error('health')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-4 col-6">
                            <label class="fw-bold mb-2 text-dark">COLOR<span class="text-danger">*</span></label>
                            <select name="color" id="color-select" required
                                class="form-select @error('color') is-invalid @enderror">
                                <option value="">Select Color</option>
                                @foreach ($iphoneColors as $colorName => $colorHex)
                                    <option value="{{ $colorName }}" {{ $sel('color', $colorName) }}>{{ $colorName }}</option>
                                @endforeach
                            </select>
                            @error('color')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror

                            {{-- Live colour preview --}}
                            <div id="color-preview" class="mt-3 p-2 rounded border align-items-center d-none"
                                style="gap: 12px;">
                                <div id="color-circle" class="color-circle"></div>
                                <div>
                                    <div class="small text-muted">Selected Color</div>
                                    <div id="color-name" class="fw-bold text-dark"></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-4 col-md-4 col-6">
                            <label class="fw-bold mb-2 text-dark">RAM<span class="text-danger">*</span></label>
                            <select name="ram" id="ram-select" required
                                class="form-select @error('ram') is-invalid @enderror">
                                <option value="">Select Option</option>
                                @foreach ($ramOptions as $option)
                                    <option value="{{ $option }}" {{ $sel('ram', $option) }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('ram')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    @endif
                </div>

                {{-- PTA / Activation / Country lock --}}
                <div class="row mt-3">
                    <div class="col-lg-4 col-md-4 col-12">
                        <label class="fw-bold mb-2 text-dark">PTA STATUS<span class="text-danger">*</span></label>
                        <select name="pta_status" required
                            class="form-select @error('pta_status') is-invalid @enderror">
                            <option value="">Select Option</option>
                            @foreach ($ptaOptions as $option)
                                <option value="{{ $option }}" {{ $sel('pta_status', $option) }}>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('pta_status')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-4 col-6">
                        <label class="fw-bold mb-2 text-dark">ACTIVATION STATUS<span class="text-danger">*</span></label>
                        <select name="activation_status" required
                            class="form-select @error('activation_status') is-invalid @enderror">
                            <option value="">Select Option</option>
                            @foreach ($activationOptions as $option)
                                <option value="{{ $option }}" {{ $sel('activation_status', $option) }}>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('activation_status')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-4 col-6">
                        <label class="fw-bold mb-2 text-dark">COUNTRY LOCKED?<span class="text-danger">*</span></label>
                        <select name="country_status" required
                            class="form-select @error('country_status') is-invalid @enderror">
                            <option value="">Select Option</option>
                            @foreach ($countryOptions as $option)
                                <option value="{{ $option }}" {{ $sel('country_status', $option) }}>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('country_status')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                {{-- SIM / Prices --}}
                <div class="row mt-3">
                    <div class="col-lg-4 col-md-4 col-6">
                        <label class="fw-bold mb-2 text-dark">SIM TYPE<span class="text-danger">*</span></label>
                        <select name="sim_status" required
                            class="form-select @error('sim_status') is-invalid @enderror">
                            <option value="">Select SIM Type</option>
                            @foreach ($simOptions as $option)
                                <option value="{{ $option }}" {{ $sel('sim_status', $option) }}>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('sim_status')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-4 col-6">
                        <label class="fw-bold mb-2 text-dark">PURCHASE PRICE<span class="text-danger">*</span></label>
                        <input type="number" name="purchase" min="1" required placeholder="Purchase Price"
                            value="{{ $val('purchase') }}"
                            class="form-control @error('purchase') is-invalid @enderror">
                        @error('purchase')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-4 col-6">
                        <label class="fw-bold mb-2 text-dark">SALE PRICE<span class="text-danger">*</span></label>
                        <input type="number" name="sale" min="2" required placeholder="Sale Price"
                            value="{{ $val('sale') }}" class="form-control @error('sale') is-invalid @enderror">
                        @error('sale')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                {{-- ------------------------------- Actions ------------------------------- --}}
                <button type="submit" name="action" value="save" class="btn btn-primary mt-3 float-end ms-2"
                    title="Save">
                    {{ $isEdit ? 'Update' : 'Save' }} <i class="bx bx-check-circle"></i>
                </button>

                @unless ($isEdit)
                    <button type="submit" name="action" value="save_add_new" class="btn btn-dark mt-3 float-end"
                        title="Save and Add New">
                        Save &amp; Add New <i class="bx bx-plus-circle"></i>
                    </button>
                @endunless
            </form>
        </div>
    </div>

    {{-- ================================== Styles ================================== --}}
    <style>
        .custom-back-button {
            font-size: 16px;
            height: 100%;
            width: 100%;
            border-radius: 0;
            text-decoration: none;
            transition: all .3s ease;
            font-weight: 500;
        }

        .custom-back-button:hover { background-color: #314861; }
        .custom-back-button i { font-size: 18px; }

        .color-circle {
            width: 38px;
            height: 38px;
            min-width: 38px;
            border-radius: 50%;
            border: 2px solid #999;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .15);
        }

        #color-preview:not(.d-none) { display: flex; }

        .select2-container--default .select2-selection--single {
            display: block;
            width: 100%;
            padding: .3rem .2rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .375rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            height: auto;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50%;
            right: .32rem;
            transform: translateY(-50%);
            height: auto;
        }
    </style>

    {{-- ================================== Scripts ================================== --}}
    {{-- Phone & CNIC masks: standalone plain JS (no jQuery, no plugins). --}}
    <script>
        (function () {
            const PHONE_PREFIX     = '03';
            const PHONE_MAX_DIGITS = 11;   // 0300-1234567
            const PHONE_DASH_AT    = 4;
            const CNIC_MAX_DIGITS  = 13;   // 12345-1234567-1

            const digitsOnly = (v) => v.replace(/\D/g, '');

            /**
             * Phone: the "03" prefix is always present and cannot be removed.
             * Whatever is typed/pasted after it is kept.
             *   "3001234567"   -> 0300-1234567
             *   "03001234567"  -> 0300-1234567
             *   "923001234567" -> 0300-1234567
             */
            function formatPhone(value) {
                let d = digitsOnly(value);

                if (d.startsWith('92')) d = d.substring(2);   // country code
                if (d.startsWith('0'))  d = d.substring(1);   // leading zero
                if (d.startsWith('3'))  d = d.substring(1);   // the "3" of "03"

                d = (PHONE_PREFIX + d).substring(0, PHONE_MAX_DIGITS);

                return d.length > PHONE_DASH_AT
                    ? d.substring(0, PHONE_DASH_AT) + '-' + d.substring(PHONE_DASH_AT)
                    : d;
            }

            // CNIC: 12345-1234567-1
            function formatCnic(value) {
                const d = digitsOnly(value).substring(0, CNIC_MAX_DIGITS);
                return [d.substring(0, 5), d.substring(5, 12), d.substring(12)]
                    .filter(Boolean)
                    .join('-');
            }

            const masks = {
                'phone-field':          formatPhone,
                'pushasing_from_phone': formatPhone,
                'cnic-field':           formatCnic,
                'pushasing_from_cnic':  formatCnic,
            };

            const getFormatter = (el) => masks[el.id] || masks[el.name];
            const isPhone      = (el) => el.id === 'phone-field' || el.name === 'pushasing_from_phone';

            const moveCaretToEnd = (el) => {
                const len = el.value.length;
                try { el.setSelectionRange(len, len); } catch (e) {}
            };

            // Live formatting while typing / pasting
            document.addEventListener('input', function (e) {
                const format = getFormatter(e.target);
                if (!format) return;
                e.target.value = format(e.target.value);
            });

            // Phone: show the "03" prefix as soon as the field is focused
            document.addEventListener('focusin', function (e) {
                if (!isPhone(e.target)) return;
                if (e.target.value === '') {
                    e.target.value = PHONE_PREFIX;
                    // wait a tick so the browser does not reset the caret
                    setTimeout(() => moveCaretToEnd(e.target), 0);
                }
            });

            // Phone: if only the bare prefix was left, clear it again
            // (so "required" validation and the placeholder work properly)
            document.addEventListener('focusout', function (e) {
                if (isPhone(e.target) && e.target.value === PHONE_PREFIX) {
                    e.target.value = '';
                }
            });

            // Format any pre-filled values (edit page / validation errors)
            function formatExisting() {
                document.querySelectorAll('#phone-field, #cnic-field, [name="pushasing_from_phone"], [name="pushasing_from_cnic"]')
                    .forEach(function (el) {
                        const format = getFormatter(el);
                        if (format && el.value) el.value = format(el.value);
                    });
            }

            // Debug marker: open the console (F12). If you do not see this line,
            // this view is NOT the one being rendered.
            console.log('[stock-form] phone/cnic masks loaded v4');

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', formatExisting);
            } else {
                formatExisting();
            }
        })();
    </script>

    {{-- Needs jQuery from the master layout; the code below still works without it. --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const $id = (id) => document.getElementById(id);

            /* -------------------------------------------------------------
             | 1. Purchasing source: Local (seller info) / Dealer (dealer select)
             * ------------------------------------------------------------- */
            const purchasingFrom  = $id('purchasing-from');
            const sellerSection   = $id('seller-information');
            const dealerSection   = $id('dealer-information');
            const dealerSelect    = $id('dealer-select');
            const sellerRequired  = ['name-field', 'phone-field', 'cnic-field'].map($id);
            const sellerPatterned = ['phone-field', 'cnic-field'].map($id);

            // Remember each field's pattern so it can be switched off while hidden
            sellerPatterned.forEach((el) => { el.dataset.pattern = el.getAttribute('pattern'); });

            const setRequired = (el, required) => el.toggleAttribute('required', required);

            function togglePurchasingSource() {
                const isLocal  = purchasingFrom.value === 'Local';
                const isDealer = purchasingFrom.value === 'Dealer';

                sellerSection.style.display = isLocal ? 'block' : 'none';
                dealerSection.style.display = isDealer ? 'block' : 'none';

                sellerRequired.forEach((el) => setRequired(el, isLocal));

                // Phone/CNIC format is only enforced when the seller section is visible
                sellerPatterned.forEach((el) => {
                    if (isLocal) el.setAttribute('pattern', el.dataset.pattern);
                    else         el.removeAttribute('pattern');
                });

                setRequired(dealerSelect, isDealer);
            }

            purchasingFrom.addEventListener('change', togglePurchasingSource);
            togglePurchasingSource();

            /* -------------------------------------------------------------
             | 2. iPhone colour preview (Apple only)
             * ------------------------------------------------------------- */
            @if ($isApple)
                const colorMap     = @json($iphoneColors);
                const colorSelect  = $id('color-select');
                const colorPreview = $id('color-preview');
                const colorCircle  = $id('color-circle');
                const colorName    = $id('color-name');

                const isLight = (hex) => {
                    const n = parseInt(hex.slice(1), 16);
                    const r = (n >> 16) & 255, g = (n >> 8) & 255, b = n & 255;
                    return (0.299 * r + 0.587 * g + 0.114 * b) > 200;
                };

                function updateColorPreview() {
                    const color = colorSelect.value;
                    const hex   = colorMap[color];

                    colorPreview.classList.toggle('d-none', !hex);
                    if (!hex) return;

                    colorCircle.style.backgroundColor = hex;
                    colorCircle.style.borderColor     = isLight(hex) ? '#999999' : '#cccccc';
                    colorName.textContent             = color;
                }

                colorSelect.addEventListener('change', updateColorPreview);
                updateColorPreview();
            @endif

            /* -------------------------------------------------------------
             | 3. Select2 (last, and guarded, so a missing plugin can never
             |    break the code above)
             * ------------------------------------------------------------- */
            if (window.jQuery && jQuery.fn.select2) {
                [
                    ['#model-select',   'Select Model'],
                    ['#company-select', 'Select Company'],
                    ['#color-select',   'Select Color'],
                    ['#ram-select',     'Select Ram'],
                    ['#rom-select',     'Select Storage / Rom'],
                ].forEach(([selector, placeholder]) => {
                    jQuery(selector).select2({ placeholder, allowClear: true });
                });

                // Select2 fires jQuery events - forward to the colour preview.
                @if ($isApple)
                    jQuery('#color-select').on('change', updateColorPreview);
                @endif
            } else {
                console.warn('Select2 / jQuery not found - using plain selects.');
            }
        });
    </script>
@endsection

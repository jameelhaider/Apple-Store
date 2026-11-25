@extends('dashboard.master2')

@php
    $isEdit = $account->id !== null;
    $title  = $isEdit ? 'Edit Account' : 'Create New Account';

    $formAction = $isEdit
        ? route('update.account', ['id' => $account->id])
        : route('submit.account');

    // Current value: old input first, then the model.
    $val = fn(string $field) => old($field, $account->$field ?? '');
@endphp

@section('admin_title', $title)

@section('content2')
    <div class="container-fluid px-3">

        {{-- ============================== Page header ============================== --}}
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                    <a href="{{ url('admin/accounts') }}"
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

                <div class="row">
                    {{-- Customer name --}}
                    <div class="col-lg-4 col-md-4">
                        <label class="fw-bold mb-2">Customer Name<span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" required placeholder="Customer Name"
                            value="{{ $val('customer_name') }}"
                            class="form-control @error('customer_name') is-invalid @enderror">
                        @error('customer_name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Phone (always starts with 03 -> 03XX-XXXXXXX) --}}
                    <div class="col-lg-4 col-md-4">
                        <label class="fw-bold mb-2">Phone<span class="text-danger">*</span></label>
                        <input type="text" name="customer_phone" id="phone-field" required
                            maxlength="12" inputmode="numeric" autocomplete="off"
                            pattern="03\d{2}-\d{7}" title="Format: 03XX-XXXXXXX"
                            placeholder="0300-1234567" value="{{ $val('customer_phone') }}"
                            class="form-control @error('customer_phone') is-invalid @enderror">
                        @error('customer_phone')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                <div class="row mt-3">
                    {{-- Address --}}
                    <div class="col-lg-6 col-md-8">
                        <label class="fw-bold mb-2">Address (Optional)</label>
                        <textarea name="customer_address" rows="5" placeholder="Customer Address"
                            class="form-control @error('customer_address') is-invalid @enderror">{{ $val('customer_address') }}</textarea>
                        @error('customer_address')
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
    </style>

    {{-- ================================== Scripts ================================== --}}
    {{-- Phone mask: plain JS (no jQuery, no inputmask plugin). --}}
    <script>
        (function () {
            const PHONE_PREFIX     = '03';
            const PHONE_MAX_DIGITS = 11;   // 0300-1234567
            const PHONE_DASH_AT    = 4;

            const field = document.getElementById('phone-field');
            if (!field) return;

            const digitsOnly = (v) => v.replace(/\D/g, '');

            /**
             * The "03" prefix is always present and cannot be removed.
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

            const moveCaretToEnd = () => {
                const len = field.value.length;
                try { field.setSelectionRange(len, len); } catch (e) {}
            };

            // Live formatting while typing / pasting
            field.addEventListener('input', function () {
                field.value = formatPhone(field.value);
            });

            // Show the "03" prefix as soon as the field is focused
            field.addEventListener('focus', function () {
                if (field.value === '') {
                    field.value = PHONE_PREFIX;
                    setTimeout(moveCaretToEnd, 0);
                }
            });

            // If only the bare prefix was left, clear it again
            // (so "required" validation and the placeholder work properly)
            field.addEventListener('blur', function () {
                if (field.value === PHONE_PREFIX) field.value = '';
            });

            // Format a pre-filled value (edit page / validation errors)
            if (field.value) field.value = formatPhone(field.value);
        })();
    </script>
@endsection

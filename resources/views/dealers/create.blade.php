@extends('dashboard.master2')
@php
    $title = $dealer->id != null ? 'Edit Dealer' : 'Add New Dealer';
@endphp
@section('admin_title', $title)


@section('content2')
    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a href="{{ url('admin/dealers') }}" class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-2"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-6 col-sm-8">
                  <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">{{ $dealer->id != null ? 'Edit Dealer' : 'Add New Dealer' }}</h3>

                  <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">{{ $dealer->id != null ? 'Edit Dealer' : 'Add New Dealer' }}</h5>
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
                background-color: #314861;;
            }

            .custom-back-button i {
                font-size: 18px;
            }
        </style>


        <div class="card p-3 mt-3">
            <form
                action="{{ $dealer->id != null ? route('update.dealer', ['id' => $dealer->id]) : route('submit.dealer') }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-lg-4 col-md-4">
                        <label for="" class="fw-bold mb-2 text-dark">Bussiness Name <span class="text-danger">*</span></label>
                        <input type="text" required placeholder="Bussiness Name" value="{{ $dealer->bussiness_name }}"
                            class="form-control @error('bussiness_name') is-invalid @enderror" name="bussiness_name">
                        @error('bussiness_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-4">
                        <label for="" class="fw-bold mb-2 text-dark">Dealer Name<span class="text-danger">*</span></label>
                        <input type="text" placeholder="Dealer Name" required value="{{ $dealer->name }}"
                            class="form-control @error('name') is-invalid @enderror" name="name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-4">
                        <label for="" class="fw-bold mb-2 text-dark">Phone<span class="text-danger">*</span></label>

                            <input type="text" required value="{{ old('phone', $dealer->phone) }}"
                            name="phone" placeholder="0300-0000000"
                            class="form-control @error('phone') is-invalid @enderror"
                            data-inputmask="'mask': '0399-9999999'" type="number" maxlength = "12">


                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>






                </div>





<div class="row mt-3">
    <div class="col-lg-6 col-md-4">
        <label for="" class="fw-bold mb-2 text-dark">Address (Optional)</label>
<textarea name="address" class="form-control" placeholder="Address" id="" cols="30" rows="5">{{ $dealer->address }}</textarea>
        @error('address')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>




<button type="submit" class="btn btn-primary mt-3 float-end" title="Submit">
    {{ $dealer->id != null ? 'Update' : 'Save' }}
    <i class="bx bx-check-circle"></i>
    <style>
        .btn-success:hover{
            background-color: #89c72d;
        }
    </style>
</button>


            </form>
        </div>
    </div>
      <script>
        $(":input").inputmask();
    </script>
@endsection

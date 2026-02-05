@extends('dashboard.master2')
@section('admin_title', 'Admin | Check Device Info')
@section('content2')

    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-6 col-md-3 col-sm-4">
                    <a href="{{ url('/admin') }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-home-circle me-2"></i> Dashboard
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-9 col-sm-8">
                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family: cursive;">Check Device Info</h3>
                        <h5 class="mt-1 d-sm-block d-block d-lg-none d-md-none" style="font-family: cursive;">Check Device
                            Info</h5>
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
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-7 col-sm-10 col-12 mt-2">


                <div class="card p-3" style="border-radius:20px">
                    <h3 class="text-center">Free IMEI Check Online!</h3>
                    <form action="" method="get">
                        <div class="row mt-2">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-10">

                                @if (request()->imei && $response)
                                    <div class="p-3"
                                        style="background-color:  #2b2b2b;border-radius:20px; height: auto;">
                                    @else
                                        <div class="p-3"
                                            style="background-color:  #2b2b2b;border-radius:20px;height:180px;">
                                @endif

                                <div class="input-group mb-3">
                                    <input type="text" maxlength="15" required name="imei"
                                        value="{{ request()->imei }}" class="form-control" placeholder="Enter your IMEI"
                                        style="background-color: #f1f4f6;border-radius:10px 0px 0px 10px;height:43px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-wide" style="background-color: #000000;color:white;font-size:18px;border-radius:0px 10px 10px 0px"
                                            type="submit"><i class="bx bx-search"></i></button>
                                    </div>
                                </div>
                                <style>
                                    .btn-wide {
                                        width: 100px;
                                        min-width: 100px;
                                    }
                                </style>




                                @if (request()->imei && $response)
                                    <div class="text-center">
                                        <i class="bx bx-mobile" style="font-size: 100px;color:white"></i>
                                    </div>
                                    <h3 class="text-center fw-bold text-white">Device Info</h3>

                                    <div class="text-center text-white" id="responseContainer">
                                        {!! $response !!}
                                    </div>





                                    <div class="text-center">
                                        <div class="mt-1" style="height: 1px;background-color:white"></div>
                                        <p class="mt-2 fw-bold text-white">
                                            {{ \Carbon\Carbon::now()->format('F j, Y g:i A') }}</p>
                                        <div style="height: 1px;background-color:white"></div>
                                        <button id="copyResponse" class="btn mt-2 btn-secondary" type="button"><i
                                                class="bx bx-copy"></i> Copy
                                            Info</button>
                                        <a href="{{ url('admin/check-model') }}" class="btn mt-2 btn-dark">Check More</a>
                                    </div>
                                @endif
                                <script>
                                    document.getElementById('copyResponse').addEventListener('click', function() {
                                        const responseHTML = document.getElementById('responseContainer').innerHTML;
                                        const tempDiv = document.createElement('div');
                                        tempDiv.innerHTML = responseHTML;
                                        let responseText = tempDiv.innerHTML.replace(/<br\s*\/?>/gi, '\n');
                                        responseText = responseText.replace(/<\/?[^>]+(>|$)/g, "");
                                        responseText = responseText.trim();
                                        const textarea = document.createElement('textarea');
                                        textarea.value = responseText;
                                        document.body.appendChild(textarea);
                                        textarea.select();
                                        document.execCommand('copy');
                                        document.body.removeChild(textarea);
                                        alert('Response copied to clipboard!');
                                    });
                                </script>



                            </div>
                        </div>
                        <div class="col-lg-1"></div>
                </div>

                <div class="p-1 mt-2" style="background-color: #eab310;border-radius:7px;color:white">
                    <small style="font-size: 12px">Checks Model, Storage, Color, Find My iPhone status, Blacklist
                        status, SIM-Lock status, Carrier, and Warranty information for Apple devices. All other
                        brands return model and blacklist information.</small>
                </div>
                </form>
            </div>



        </div>
    </div>
    </div>
@endsection

@extends('dashboard.master2')
@section('admin_title', 'Admin | Dealers')
@section('content2')

    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-6 col-md-4 col-sm-5">
                    <a href="{{ route('create.dealer') }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-plus me-2"></i> Add New Dealer
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-8 col-sm-7">
                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family: cursive;">Dealers</h3>
                        <h5 class="mt-1 d-block d-lg-none d-md-none" style="font-family: cursive;">Dealers</h5>
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




        <div class="card mb-2 p-2 mt-2" >
            <form action="" method="GET">
                <div class="row">
                    <div class="col-lg-5 col-md-4 col-sm-4 col-6 mt-1 mb-1">
                        <input type="text" class="form-control" value="{{ request()->name }}" placeholder="Dealer Name"
                            name="name">
                    </div>
                    <div class="col-lg-5 col-md-4 col-sm-4 col-6 mt-1 mb-1">

                            <input type="text" value="{{ request()->phone }}"
                            name="phone" placeholder="0300-0000000"
                            class="form-control"
                            data-inputmask="'mask': '0399-9999999'" type="number" maxlength = "12">


                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-4 col-12 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ url('admin/dealers') }}" title="Clear" class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>


                        </div>
                    </div>
                </div>
            </form>
        </div>


        @if ($dealers->count() > 0 && (request()->name || request()->phone))
            <div class="alert bg-primary text-white mt-3">
                <strong>{{ $dealers->count() }} {{ $dealers->count() > 0 && $dealers->count() < 2 ? 'Result' : 'Results' }}
                    Found</strong>
            </div>
        @elseif ($dealers->count() < 1 && (request()->name || request()->phone))
            <div class="alert bg-warning text-white mt-3">
                <strong>No Results Found !</strong>
            </div>
        @endif


        <div class="card p-2 mb-0" >
            @if ($dealers->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Bussiness Name</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Dealer Name</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Phone</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dealers as $key => $dealer)
                                <tr class="text-center">
                                    <td class="text-dark">{{ ++$key }}</td>
                                    <td>
                                        <a class="nav-link fw-bold text-dark" href="{{ route('dealer.edit', ['id' => $dealer->id]) }}">
                                            {{ $dealer->bussiness_name }}
                                        </a>

                                    </td>
                                    <td>
                                        <a class="nav-link text-dark"
                                            href="{{ route('dealer.edit', ['id' => $dealer->id]) }}">
                                            {{ $dealer->name }}
                                        </a>
                                    </td>

                                    <td class="text-dark fw-bold">{{ $dealer->phone }}</td>

                                    <td>
                                        <div class="dropdown ms-auto">
                                            <button class="btn btn-dark btn-sm dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('dealer.edit', ['id' => $dealer->id]) }}">Edit</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        onclick="confirmDelete('{{ route('dealer.delete', ['id' => $dealer->id]) }}')">Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>



                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="float-end mt-2">
                        {{ $dealers->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Bussiness Name</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Dealer Name</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Phone</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <h3 class="h3 mt-2 text-center fw-normal text-dark">No Data Found!</h3>
            @endif
        </div>

    </div>

  <script>
        $(":input").inputmask();
    </script>


    <script>
        // Function to handle delete confirmation
        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure you want to delete this Dealer?',
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
    </script>
@endsection














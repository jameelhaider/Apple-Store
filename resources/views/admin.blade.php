@extends('dashboard.master2')
@section('admin_title', 'Apple Store | Dashboard')
@section('content2')
    <style>
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            margin-top: 20px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.205);
        }

        .card:hover {
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
        }

        .card-header {
            background: linear-gradient(135deg, #000000 70%, #bac2c8 70%);
            padding: 10px 10px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .card-header:hover {
            background: linear-gradient(135deg, #000000, #2b2b2b, #bac2c8);
        }

        .card-body {
            font-size: 1.3rem;
            padding: 20px;
            font-weight: 900;
            color: #000000;
            background: #d6e3f3;
        }

        .card-icon {
            font-size: 1.5rem;
            margin-right: 10px;
        }
    </style>

    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
            background: linear-gradient(135deg, rgba(106, 102, 102, 0.16), white)
        }

        .chart-container canvas {
            height: 100% !important;
            width: 100% !important;
        }
    </style>

    <div class="container-fluid px-3">
    </div>
@endsection

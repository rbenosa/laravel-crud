@extends('layout.app')
@section('title', 'Home')

@section('styles')

<style>
    body {
        display: flex;
        align-items: center;
        padding-bottom: 40px;
        padding-top: 3rem !important;
    }

    .min-vh-100 {
        min-height: calc(100vh - 100px) !important;
    }

    .btn-float-bottom {
        display: grid !important;
        position: absolute;
        bottom: 0;
    }
</style>

@endsection

@section('content')

<div class="row row-cols-1 row-cols-md-2 g-5 d-flex align-items-center min-vh-100">
    <div class="col">
        <div class="card">
            <div class="row g-0">
                <div class="col-md-4">
                    <img class="card-img img-fluid" src="https://images.pexels.com/photos/3184435/pexels-photo-3184435.jpeg?auto=compress&cs=tinysrgb&dpr=3">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title h1">People</h5>

                        <div class="btn-float-bottom mb-4 ">
                            <a href="{{ route('person') }}" class="btn btn-lg btn-secondary">View People</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="row g-0">

                <div class="col-md-4">
                    <img class="card-img img-fluid" src="https://images.pexels.com/photos/3782226/pexels-photo-3782226.jpeg?auto=compress&cs=tinysrgb&dpr=2">
                </div>
                
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title h1">Organizations</h5>

                        <div class="btn-float-bottom mb-4 ">
                            <a href="{{ route('organization') }}" class="btn btn-lg btn-secondary">View Organization</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


@endsection
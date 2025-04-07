@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Dashboard</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="alert alert-success">
                        Welcome, {{ Auth::user()->name }}! You are logged in.
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Customer Management</h5>
                                    <p class="card-text">Manage your customer database</p>
                                    <a href="{{ route('customers.index') }}" class="btn btn-primary">
                                        Go to Customers
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Account Settings</h5>
                                    <p class="card-text">Update your profile information</p>
                                    <a href="#" class="btn btn-secondary">
                                        Edit Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
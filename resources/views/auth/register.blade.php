@extends('auth.layout')

@section('content')
<div class="form-wrapper">
    <div class="container">
        <div class="card">
            <div class="row g-0">
                <div class="col">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="ltf-block-logo d-block d-lg-none text-center text-lg-start">
                                <img width="120" src="{{ asset('assets/textio.png') }}" alt="logo">
                            </div>
                            <div class="my-5 text-center text-lg-start">
                                <h1 class="display-8">Create Account</h1>
                                <p class="text-muted">You can create a free account now</p>
                            </div>
                            <form class="mb-5">
                                <div class="mb-3">
                                    <input type="text" class="form-control" placeholder="Enter fullname" autofocus
                                           required>
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control" placeholder="Enter email"
                                           required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" class="form-control" placeholder="Enter password"
                                           required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" class="form-control" placeholder="Re-enter password"
                                           required>
                                </div>
                                <div class="text-center text-lg-start">
                                    <button class="btn btn-primary">Sign Up</button>
                                </div>
                            </form>
                            <div class="social-links justify-content-center">
                                <a href="#">
                                    <i class="ti-google bg-google"></i> Sign in with Google
                                </a>
                                {{-- <a href="#">
                                    <i class="ti-facebook bg-facebook"></i> Sign in with Facebook
                                </a> --}}
                            </div>
                            <p class="text-center d-block d-lg-none mt-5 mt-lg-0">
                                Don't have an account? <a href="#">Sign In</a>.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col d-none d-lg-flex border-start align-items-center justify-content-between flex-column text-center">
                    <div class="logo">
                        <img width="120" src="{{ asset('assets/textio.png') }}" alt="logo">
                    </div>
                    <div>
                        <h3 class="fw-bold">Welcome to Textio!</h3>
                        <p class="lead my-5">Do you already have an account?</p>
                        <a href="{{route('login')}}" class="btn btn-primary">Sign In</a>
                    </div>
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a href="#">Privacy Policy</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">Terms & Conditions</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
    @endsection
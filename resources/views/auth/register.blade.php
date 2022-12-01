@extends('layouts.app')

@section('content')
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-90">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Sign Up</h4>
                                    <p class="mb-0">Enter Your Details to Sign Up</p>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('register.perform') }}">
                                        @csrf
                                        <div class="flex flex-col mb-3">
                                            <input type="text" name="name" class="form-control" placeholder="Name" aria-label="Name" value="{{ old('name') }}" >
                                            @error('name') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                        </div>
                                        <div class="flex flex-col mb-3">
                                            <input type="email" name="email" class="form-control" placeholder="Email" aria-label="Email" value="{{ old('email') }}" >
                                            @error('email') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                        </div>
                                        <div class="flex flex-col mb-3">
                                            <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password">
                                            @error('password') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                        </div>
                                        <div class="form-check form-check-info text-start">
                                            <input class="form-check-input" type="checkbox" name="terms" id="flexCheckDefault" >
                                            <label class="form-check-label" for="flexCheckDefault">
                                                I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and
                                                    Conditions</a>
                                            </label>
                                            @error('terms') <p class='text-danger text-xs'> {{ $message }} </p> @enderror
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign up</button>
                                        </div>
                                        <p class="text-sm mt-3 mb-0">Already have an account? <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">Sign in</a></p>
                                    </form>

                                </div>

                            </div>
                        </div>
                        <div
                                class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                                 style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signup-ill.jpg');
              background-size: cover;">
                                <span class="mask bg-primary opacity-4"></span>
                                <h4 class="mt-5 text-white font-weight-bolder position-relative">"Your journey starts here"</h4>
                                <p class="text-white position-relative">Just as it takes a company to sustain a product, it takes a community to sustain a protocol.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('layouts.footers.guest.footer')
@endsection


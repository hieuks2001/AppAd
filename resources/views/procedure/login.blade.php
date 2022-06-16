@extends('layout')
@section('login')
    <div class="container px-5 md:px-0 md:w-1/3 mx-auto h-full grid place-items-center ">
        <div class="flex flex-col items-center">
            <h2 class="text-4xl mb-10">
                Log-in to your account
            </h2>
            @if (session()->has('error'))
                <div>
                    <div class="">
                        @php
                            echo Session::get('error');
                        @endphp
                    </div>
            @endif
            @if (session()->has('message'))
                <div class="">
                    @php
                        echo Session::get('message');
                    @endphp
                </div>
            @endif
            <form class="" method="POST" action="{{ URL::to('/login') }}">
                @csrf
                <input type="text" name="username" placeholder="Username"
                    class="appearance-none input input-bordered w-full mb-5 ">
                <input type="password" name="password" placeholder="Password" class="input input-bordered w-full mb-5">
                <button class="btn btn-block">Login</button>
            </form>
            <div class="">
                New to us? <a href="{{ URL::to('register') }}">Sign Up</a>
            </div>
        </div>
    </div>
@endsection

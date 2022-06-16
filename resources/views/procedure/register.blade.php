@extends('layout')
@section('register')
    <div class="container px-5 md:px-0 md:w-1/3 mx-auto h-full grid place-items-center">
        <div class="flex flex-col items-center">
            <h2 class="text-4xl mb-10">
                REGISTER
            </h2>
            @if (session()->has('error'))
                <div class="ui error message">
                    @php
                        echo Session::get('error');
                    @endphp
                </div>
            @endif


            <form method="POST" action="{{ URL::to('/register') }}">
                @csrf
                <input type="text" name="username" placeholder="username" class="input input-bordered w-full mb-5">
                <input type="password" name="password" placeholder="Password" class="input input-bordered w-full mb-5">
                <input type="password" name="re_password" placeholder="Re-Password" class="input input-bordered w-full mb-5">
                <button class="btn btn-block">Submit</button>
            </form>

        </div>
    </div>
@endsection

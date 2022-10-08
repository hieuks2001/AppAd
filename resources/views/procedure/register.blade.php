@extends('layout')
@section('register')
    <div class="container px-5 md:px-0 md:w-1/3 mx-auto h-screen grid place-items-center">
        <div class="flex flex-col items-center">
            <h2 class="text-4xl mb-10">
                REGISTER
            </h2>
            @if (session()->has('error'))
                <div class="alert alert-error shadow-lg mb-5">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            @php
                                echo Session::get('error');
                            @endphp
                        </span>
                    </div>
                </div>
            @endif
            @if ($errors->all())
              @foreach ($errors->all() as $err)
                <div class="alert alert-error mb-5 shadow-lg">
                  <div>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-6 w-6 flex-shrink-0 stroke-current"
                      fill="none"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                      />
                    </svg>
                    <span>
                      {{ $err }}
                    </span>
                  </div>
                </div>
              @endforeach
            @endif
            <form method="POST" action="{{ URL::to('/register') }}">
                @csrf
                <input type="text" name="username" placeholder="username" class="input input-bordered w-full mb-5">
                <input type="password" name="password" placeholder="Password" class="input input-bordered w-full mb-5">
                <input type="password" name="re_password" placeholder="Re-Password" class="input input-bordered w-full mb-5">
                @if (request()->has("ref"))
                <input type="hidden" name="reference" placeholder="Mã giới thiệu" class="input input-bordered w-full mb-5" value="{{ request()->query("ref")}}">
                @endif
                <button class="btn btn-block">Submit</button>
            </form>
        </div>
    </div>
@endsection

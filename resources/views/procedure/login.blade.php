@extends('layout')
@section('login')
    <div class="ui container" style="margin-top:10%">
        <div class="ui middle aligned center aligned grid">
            <div class="column">
                <h2 class="ui teal image header">
                    {{-- <img src="assets/images/logo.png" class="image"> --}}
                    <div class="content">
                        Log-in to your account
                    </div>
                </h2>
                @if (session()->has('error'))
                    <div class="ui error message">
                        @php
                            echo Session::get('error');
                        @endphp
                    </div>
                @endif
                @if (session()->has('message'))
                    <div class="ui success message">
                        @php
                            echo Session::get('message');
                        @endphp
                    </div>
                @endif
                <form class="ui large form" method="POST" action="{{ URL::to('/login') }}">
                    @csrf
                    <div class="ui stacked segment">
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="user icon"></i>
                                <input type="text" name="username" placeholder="Username">
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="lock icon"></i>
                                <input type="password" name="password" placeholder="Password">
                            </div>
                        </div>
                        <button class="ui fluid large teal submit button">Login</button>
                    </div>

                    <div class="ui error message"></div>

                </form>

                <div class="ui message">
                    New to us? <a href="{{ URL::to('register') }}">Sign Up</a>
                </div>
            </div>
        </div>
    </div>
@endsection

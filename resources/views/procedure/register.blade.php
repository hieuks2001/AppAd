@extends('layout')
@section('register')
    <div class="ui container" style="margin-top:10%">
        <div class="ui middle aligned center aligned grid">
            <div class="column">
                <h2 class="ui teal image header">
                    {{-- <img src="assets/images/logo.png" class="image"> --}}
                    <div class="content">
                        REGISTER
                    </div>
                </h2>
                @if (session()->has('error'))
                    <div class="ui error message">
                        @php
                            echo Session::get('error');        
                        @endphp
                    </div>
                @endif


                <form class="ui large form" method="POST" action="{{URL::to('/register')}}">
                    @csrf
                    <div class="ui stacked segment">
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="user icon"></i>
                                <input type="text" name="username" placeholder="username">
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="lock icon"></i>
                                <input type="password" name="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="lock icon"></i>
                                <input type="password" name="re_password" placeholder="Re-Password">
                            </div>
                        </div>
                        <button class="ui fluid large teal submit button">Submit</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
@endsection

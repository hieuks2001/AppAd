@extends('layout')
@section('main')
    <div class="ui inverted menu">
        <div class="ui container">
            <a href="" class="item">Home</a>
            <div class="right icon item">
                {{-- <a href="{{URL::to('login')}}" class="ui inverted item">Login</a>
                <a href="{{URL::to('register')}}" class="ui inverted item">Sign Up</a> --}}
                <a href="#" class="ui item">{{ Session::get('user')->username }} </a>
                <a href="/logout" class="ui item"><i class="logout red icon"></i></a>

            </div>

        </div>
    </div>
    <br>
    <div class="ui middle aligned center aligned container">
        <div class="ui left aligned grid">
            <div class="four wide blue column">
                <h2>USDT</h2>
                <b>Thu nhập</b>
            </div>
            <div class="four wide yellow column">
                <h2>USDT</h2>
                <b>Hoa hồng</b>
            </div>
            <div class="four wide purple column">
                <h2>USDT</h2>
                <b>Tổng cộng</b>
            </div>
            <div class="four wide pink column">
                <h2>USDT</h2>
                <b>Số dư</b>
            </div>
        </div>
        <br>
        @yield('startmission')
        @yield('mission')
        <div class="ui left aligned segment">
            <h3>Nhiệm vụ đã thực hiện</h3>
            <div class="ui right aligned ">
                <div class="ui icon input">
                    <i class="search icon"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <table class="ui striped table">
                    <thead>
                        <tr>
                            <th>Nhiệm vụ</th>
                            <th>Bắt đầu nhiệm vụ</th>
                            <th>Hoàn thành lúc</th>
                            <th>Số tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($missions as $key => $value)
                            <tr>
                                <td>{{ $value->ms_name }}</td>
                                <td>{{ $value->created_at }}</td>
                                <td>{{ $value->updated_at }}</td>
                                <td>{{ $value->ms_price }}</td>
                                <td>{{ $value->ms_status }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

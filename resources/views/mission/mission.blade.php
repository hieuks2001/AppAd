@extends('main')
@section('mission')
    <div class="ui left aligned segment">

        <h2 style="text-align:center !important">Nhiệm vụ của bạn</h2>
        <div class="ui list">
            <div class="item">
                <p class="ui huge red text"><b>Bước 1:</b> truy cập vào <a href="/test">{{ $mission->ms_name }}</a></p>
            </div>
            <div class="item">
                <b>Bước 2:</b> <img class="ui larger image" src="./images/{{$page->page_image}}" style="max-width: 450px"/>
            </div>
            <div class="item">
                <p><b>Bước 3:</b> Click nhận mã và chờ {{ $mission->ms_countdown }}s</p>
            </div>
            <div class="item">
                <p><b>Bước 4:</b> Copy mã và nhập vào ô ở phía dưới!</p>
            </div>
        </div>
        <hr>
        @if (session()->has('loi'))
            <div class="ui error message">
                @php
                    echo Session::get('loi');
                @endphp
            </div>
        @endif
        <form action="/paste-key" method="post">
            @csrf
            <div class="ui fluid input">
                <input type="text" name="key" placeholder="Nhập mã của bạn vào đây...">
            </div>
            <br>
            <div class="" style="text-align:center !important">
                <button class="ui inverted green button">Hoàn thành nhiệm vụ</button>
                <a class="ui inverted red button" href="/cancel-mission">Bỏ qua nhiệm vụ</a>
            </div>
        </form>



    </div>
@endsection

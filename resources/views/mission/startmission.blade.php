@extends('main')
@section('startmission')
    <div class="ui teal label header">
        <div class="huge content">
            <p> Cảnh báo: Yêu cầu khi làm nhiệm vụ phải gõ đúng TỪ KHÓA nếu sai hệ thống sẽ quét và
                khóa tài khoản vĩnh viễn</p>
        </div>
    </div>
    <br>
    <br>
    <div class="">
        <a class="ui huge inverted primary button" href="{{URL::to('tu-khoa')}}">
            Làm nhiệm vụ
        </a>
    </div>
@endsection

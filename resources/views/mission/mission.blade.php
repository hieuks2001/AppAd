@extends('main')
@section('mission')
    @include('box.patternBox1')
    <br />
    @if (!isset($mission))
        @include('mission.startmission')
    @else
        <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl container md:w-2/5 mx-auto">
            <div class="text-slate-800">
                <h2 class="text-2xl font-bold text-center mb-5">Nhiệm vụ của bạn</h2>
                <p class="mb-3"><b>Bước 1:</b> Truy cập công cụ tìm kiếm: <b>>> Google.com</b>
                <p class="mb-3"><b>Bước 2:</b> Tìm kiếm từ khoá  <b>>> <span style="color: red;">{{$page->keyword}}</span></b>
                <p class="mb-3"><b>Bước 3:</b> Truy cập vào trang web như hướng dẫn:
                <img class="mb-3 mx-auto" src="./images/{{ $page->image }}" style="max-width: 450px" />
                <p class="mb-3"><b>Bước 3:</b> Lướt thật chậm từ trên xuống dưới giống như đang đọc nội dung bài viết rồi ấn vào nút <b>Nhận mã ngay</b> và đợi {{ $page->onsite }}s kết thúc</p>
                <p class="mb-3"><b>Bước 4:</b> Copy mã và nhập vào ô ở phía dưới và bấm vào nút "<b>Hoàn thành nhiệm vụ</b>" và nhận <b>{{$mission->reward}}</b> USDT</p>
            </div>
            <hr>
            @if ($errors->all())
                <div class="alert alert-error shadow-lg">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        foreach($errors->all() as $error)
                        <span>
                            {{ $error }}
                        </span>
                    </div>
                </div>
            @endif
            <form action="/paste-key" method="post" class="mb-0">
                @csrf
                <input type="text" name="key" placeholder="Nhập mã của bạn vào đây..."
                    class="input input-bordered w-full mb-3 ">
                <button class="btn btn-block mb-3">Hoàn thành nhiệm vụ</button>
                <a class="btn btn-block btn-outline" href="/cancel-mission">Bỏ qua nhiệm vụ</a>
            </form>
        </div>
    @endif
    <br />
    <br />
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Nhiệm vụ đã thực hiện</h3>
            <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs" />
        </div>
        <br />
        <table class="table w-full bg-white">
            <!-- head -->
            <thead class="bg-white">
                <tr>
                    <th class="bg-slate-200">Nhiệm vụ</th>
                    <th class="bg-slate-200">Bắt đầu nhiệm vụ</th>
                    <th class="bg-slate-200">Hoàn thành lúc</th>
                    <th class="bg-slate-200">Số tiền</th>
                    <th class="bg-slate-200">Trạng thái</th>
                </tr>
            <tbody>
            @foreach($missions as $mission)
            <tr>
                <td>{{$mission->id}}</td>
                <td>{{$mission->created_at}}</td>
                <td>{{$mission->updated_at}}</td>
                <td>{{$mission->reward}}</td>
                @switch($mission->status)
                    @case (0)
                        <td class="bg-white">Đang chờ</td>
                        @break
                    @case (1)
                        <td class="bg-white">Hoàn thành</td>
                        @break
                    @case (-1)
                        <td class="bg-white">Đã huỷ</td>
                        @break
                @endswitch
            </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection

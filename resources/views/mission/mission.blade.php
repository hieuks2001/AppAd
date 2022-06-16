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
                <p class="mb-3"><b>Bước 1:</b> truy cập vào <a href="/test">{{ $mission->ms_name }}</a></p>
                <b>Bước 2:</b>
                <img class="mb-3 mx-auto" src="./images/{{ $page->page_image }}" style="max-width: 450px" />
                <p class="mb-3"><b>Bước 3:</b> Click nhận mã và chờ {{ $mission->ms_countdown }}s</p>
                <p class="mb-3"><b>Bước 4:</b> Copy mã và nhập vào ô ở phía dưới!</p>
            </div>
            <hr>
            @if (session()->has('loi'))
                <div class="alert alert-error shadow-lg">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            @php
                                echo Session::get('loi');
                            @endphp
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
                @foreach ($missions as $key => $value)
                    <tr>
                        <td class="bg-white">{{ $value->ms_name }}</td>
                        <td class="bg-white">{{ $value->created_at }}</td>
                        <td class="bg-white">{{ $value->updated_at }}</td>
                        <td class="bg-white">{{ $value->ms_price }}</td>
                        <td class="bg-white">{{ $value->ms_status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

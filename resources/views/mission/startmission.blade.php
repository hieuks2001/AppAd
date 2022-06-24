@if ($errors->all())
    <div class="alert alert-error shadow-lg mb-5">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            @foreach ($errors->all() as $error)
                <span>
                    {{ $error }}
                </span>
            @endforeach
        </div>
    </div>
@endif
<div class="alert alert-warning shadow-lg">
    <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Cảnh báo: Yêu cầu khi làm nhiệm vụ phải gõ đúng TỪ KHÓA nếu sai hệ thống sẽ quét và
            khóa tài khoản vĩnh viễn</span>
    </div>
</div>
<br>
<br>
<div class="flex justify-center">
    <form action="{{ URL::to('tu-khoa') }}" method="post">
        @csrf
        <button type="submit" class="btn btn-success text-xl">Làm nhiệm vụ</button>
    </form>

</div>

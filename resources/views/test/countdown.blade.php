@extends('layout')
@section('countdown')
    <div class="">
        <b>DEMO</b> <br>
        @if ($mission->ms_code != null)
            <b id="text">{{ $mission->ms_code }}</b>
        @else
            <button class="ui red button" id="btntake" onclick="Time({{ $mission->ms_countdown }})">Ấn để nhận mã</button>
        @endif
        <form action="">
            @csrf
            <input type="hidden" id="name" value="{{ $mission->ms_name }}">
        </form>
    </div>
@endsection
<script>
    function Time(time) {
        document.getElementById("btntake").innerHTML = "Nhận mã sau " + '<span id="count"></span>';
        var countDownDate = 3;
        // Set the date we're counting down to

        // Update the count down every 1 second
        var x = setInterval(function() {
            // Get today's date and time
            countDownDate = countDownDate - 1;
            // Output the result in an element with id="demo"
            document.getElementById("count").innerHTML = countDownDate + "s ";

            // If the count down is over, write some text 
            if (countDownDate == 0) {
                clearInterval(x);
                var _token = $("input[name='_token']").val();
                var name = $('#name').val();
                $.ajax({
                    url: "/test1",
                    type: "POST",
                    data: {
                        _token: _token,
                        name: name,
                    },
                    success: (result) => {
                        location.reload();
                    }
                });
            }
        }, 1000);
    }
    // function copy link
    function CopyLink() {
        var clipboard = new ClipboardJS('#btnCopy');
        clipboard.on('success', function(e) {
            Swal.fire(
                '',
                "Copy thành công",
                'success'
            )
        });

        clipboard.on('error', function(e) {
            Swal.fire(
                '',
                "Copy thất bại",
                'error'
            )
        });
    }
</script>

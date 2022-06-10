@extends('layout')
@section('regispage')
    <div class="ui container" style="margin-top:10%">
        <div class="ui middle aligned center aligned grid">
            <div class="column-page">
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
                <form action="/add-page" method="post" class="ui massive form" enctype="multipart/form-data">
                    @csrf
                    <div class="ui stacked larger segment">
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="user icon"></i>
                                <input type="text" name="pagename" placeholder="Page name" required>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="user icon"></i>
                                <input type="file" accept="image/*" name="image" onchange="loadFile(event)" required>
                            </div>
                            <img id="output" class="ui larger image" />
                        </div>
                        <button class="ui fluid large teal submit button">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };
</script>

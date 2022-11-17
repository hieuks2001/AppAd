@if (count($notification) > 0)
<div id="toast-global" class="toast toast-top toast-center absolute bottom-5 right-5 z-50">
  @foreach ($notification as $key=>$value)
  <div class="alert alert-info mt-3 shadow-lg w-72">
    <div>
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
        class="stroke-current flex-shrink-0 w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <span>{{$value}}</span>
    </div>
  </div>
  @endforeach
  <progress id="toast-progress" class="progress progress-primary w-72 mt-2" value="0" max="100"></progress>
</div>
@endif
<script>
  const toastEle = document.getElementById("toast-global");
  const toastProgEle = document.getElementById("toast-progress");
  let value_prog = 0;
  let inter = setInterval(() => {
    value_prog += 0.1;
    toastProgEle?.value = value_prog;
  }, 10);
  setTimeout(() => {
    toastEle.style.display = "none"
  }, 10000);
</script>

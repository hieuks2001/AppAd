@extends('main')
@section('regispage')
  @include('box.patternBox2')
  <br />
  <div
    class="container mx-auto md:w-2/5"
    style="margin-top:5%"
  >

    @if ($errors->all())
      @foreach ($errors->all() as $err)
        <div class="alert alert-error mb-5 shadow-lg">
          <div>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6 flex-shrink-0 stroke-current"
              fill="none"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            <span>
              {{ $err }}
            </span>
          </div>
        </div>
      @endforeach
    @endif
    @if (session()->has('message'))
      <div class="alert alert-success mb-5 shadow-lg">
        <div>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 flex-shrink-0 stroke-current"
            fill="none"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <span>
            @php
              echo Session::get('message');
            @endphp
          </span>
        </div>
      </div>
    @endif
    @if (session()->has('pageId'))
      <input
        type="checkbox"
        id="my-modal"
        class="modal-toggle"
        checked
      />
      <div class="modal">
        <div class="modal-box">
          <div class="rounded-md bg-slate-200 p-3">
            <code>
              &lt;div style=&quot;width: 100%; display: flex; justify-content: center&quot;&gt; &lt;input hidden type=&quot;text&quot; name=&quot;publicKey&quot; id=&quot;publicKey&quot; placeholder=&quot;Nhập key để lấy m&atilde;&quot; /&gt; &lt;button id=&quot;getCode&quot; style=&quot; margin-left: 10px; display: block; padding: 10px 20px; outline: none; border: 0; background-color: red; color: white; font-weight: bold; border-radius: 10px; &quot; &gt; Lấy m&atilde; &lt;/button&gt; &lt;/div&gt; &lt;div id=&quot;canihelpu&quot; style=&quot;text-align: center&quot;&gt; &lt;span id=&quot;countdown&quot; style=&quot;font-size: 2rem; font-weight: bold&quot;&gt;&lt;/span&gt; &lt;p id=&quot;notification&quot; style=&quot;font-size: 1.2rem; font-weight: bold; margin: 0&quot;&gt;&lt;/p&gt; &lt;/div&gt; &lt;script&gt; var value = &quot;{{ Session::get('pageId') }}&quot; &lt;/script&gt; &lt;script src=&quot;https://nhiemvu.app/ican.js&quot;&gt;&lt;/script&gt;
            </code>
          </div>
          <p>
            + Copy toàn bộ code này và gắn vào Footer của website
          </p>
          <p>
            + Cần tắt các plugin cache để tối ưu với thời gian thực
          </p>
          <p class="text-primary mt-3 text-xl font-bold">Những lưu ý khi dùng
            traffic user:</p>
          <small>
            + Nên đẩy traffic user khi từ khoá đã vào được ít nhất từ trang 1-5 để
            thành viên có thể tìm thấy và click vào web.
            <br>
            + Nên đẩy traffic user ít nhất từ 20-30 ngày để có hiệu quả tốt nhất.
            <br>
            + Khi đẩy key chính nên đẩy kèm với key brand để tăng tính tự nhiên
            cho
            website (Đẩy key brand tốt cho key chính).
            <br>
            + Traffic user là chất xúc tác giúp từ khóa lên TOP nhanh hơn, tuy
            nhiên
            website cần phải tối ưu onpage và backlink thật tốt trước khi đẩy
            traffic.
            <br>
            + Quý khách nên chủ động theo dõi dữ liệu từ Google analytics và
            Google
            search console để đối chiếu với dữ liệu thống kê của chúng tôi.
            <br>
            + Chúng tôi cam kết 100% traffic user người dùng là thật, không tool,
            không fake IP, quý khách có thể tự mình làm nhiệm vụ để kiểm chứng.
          </small>
          <div class="modal-action">
            <label
              for="my-modal"
              class="btn btn-block"
              id="btn-copy-script"
            >Sao chép và Hoàn thành</label>
          </div>
        </div>
      </div>
      <script>
      document.getElementById("btn-copy-script").addEventListener("click",()=>{
        navigator.clipboard.writeText(document.getElementsByTagName("code")[0].textContent)
      })
      </script>
    @endif
    <form
      action="/add-page"
      method="post"
      enctype="multipart/form-data"
    >
      @csrf
      <div class="rounded-2xl p-5 text-center shadow-2xl">
        <input
          type="text"
          name="url"
          placeholder="Nhập url đích muốn chạy traffic"
          class="input input-bordered mb-5 w-full"
          required
        >
        <input
          type="text"
          name="keyword"
          placeholder="Nhập từ khóa"
          class="input input-bordered mb-5 w-full"
          required
        >
        <input
          type="text"
          name="traffic_per_day"
          placeholder="Nhập lượng Traffic mỗi ngày"
          class="input input-bordered mb-5 w-full"
          required
        >
        <input
          type="text"
          name="traffic_sum"
          id="traffic_sum"
          placeholder="Nhập tổng Traffic"
          class="input input-bordered mb-5 w-full"
          required
        >
        <select
          name="page_type"
          id="site_types"
          class="select select-bordered mb-5 w-full"
        >
          <option
            selected
            disabled
          > Vui lòng chọn loại site </option>
          @foreach ($onsite as $key => $value)
            <option value="{{ $value->id }}">{{ $value->name }}</option>
          @endforeach
        </select>
        <select
          name="onsite"
          id="site_type__onsite"
          class="select select-bordered mb-5 w-full"
        >
          <option
            value=""
            selected
            disabled
          > Vui lòng chọn gói Onsite </option>
          {{-- @foreach ($onsite as $key1 => $value1)
                  @foreach ($value1->onsite as $key => $value)
                      <option value="{{ $key }}">{{ $value1->name }} Time onsite > {{ $key }}
                      </option>
                  @endforeach
              @endforeach --}}
        </select>
        <p
          id="price"
          class="input input-bordered text-start w-full p-2 read-only:bg-slate-200"
        >Tổng USDT phải
          trả</p>
        </label>
        <div class="avatar mb-5">
          <div class="max-w-xs rounded">
            <img
              id="output"
              class="object-contain"
            />
          </div>
        </div>
        <button class="btn btn-block">Submit</button>
      </div>
    </form>
  </div>
  <div class="mt-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
    <h3 class="text-2xl font-bold text-slate-800">Lịch sử traffic</h3>
    @php
      $tabs = ['Đang chờ', 'Đang chạy', "Người làm nhiệm vụ", 'Hoàn thành', 'Lỗi'];
    @endphp
    <div class="tabs tabs-boxed my-3 bg-transparent">
      @foreach ($tabs as $index => $tab)
        <a
          class="tab text-lg"
          href="/regispage/tab-{{ (int) $index + 1 }}"
        >{{ $tab }}</a>
      @endforeach
    </div>
    <br />
    @yield('tab1-blade')
    @yield('tab2-blade')
    @yield('tab3-blade')
    @yield('tab4-blade')
    @yield('tab5-blade')
  </div>
  <script>
    const siteTypes = Object.entries({!! json_encode($onsite->toArray(), JSON_HEX_TAG) !!}).map(([key, value]) =>value)
    var output = document.getElementById('output');
    var fileUpload = document.getElementById('fileUpload');
    fileUpload?.addEventListener('change', (event) => {
      if (event.target.files.length > 0) {
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
          URL.revokeObjectURL(output.src) // free memory
        }
      } else {
        output.removeAttribute('src');
      }
    });
    let tabs = document.querySelectorAll('.tab');
    if (window.location.href.endsWith("regispage")) {
      tabs[0].classList.add("tab-active", "text-white")
    } else {
      tabs.forEach(tab => {
        if (window.location.href.includes(tab.getAttribute('href'))) {
          tab.classList.add("tab-active", "text-white")
        }
      });
    }


    const selectSiteTypesEle = document.getElementById("site_types");
    const selectSiteTypeOnsiteEle = document.getElementById("site_type__onsite");
    const priceEle = document.getElementById("price");
    const trafficSumEle = document.getElementById("traffic_sum");

    selectSiteTypesEle.addEventListener("change", (e) => {
      while (selectSiteTypeOnsiteEle.childNodes.length > 2) {
        selectSiteTypeOnsiteEle.removeChild(selectSiteTypeOnsiteEle
          .lastChild);
      }
      selectSiteTypeOnsiteEle.value = ""
      priceEle.textContent = "0.00 USDT"
      console.log(siteTypes);
      const siteType = siteTypes.find(siteType => siteType.id == e.target.value);
      for (const k in siteType.onsite) {
        const option = document.createElement("option");
        option.value = k;
        option.textContent = `${siteType.name} Time onsite > ${k}s`;
        selectSiteTypeOnsiteEle.appendChild(option);
      }
    })

    selectSiteTypeOnsiteEle.addEventListener("change", (e) => {
      handlePriceChange(trafficSumEle.value, e.target.value)
    })
    trafficSumEle.addEventListener("input", (e) => {
      handlePriceChange(e.target.value, selectSiteTypeOnsiteEle.value)
    })

    function handlePriceChange(trafficSumValue, timeOnsiteValue) {
      const siteType = selectSiteTypesEle.value;
      const trafficSum = trafficSumValue;
      const timeOnsite = siteTypes.find(site => site.id == siteType).onsite[
        timeOnsiteValue];
      priceEle.textContent = `${(timeOnsite * trafficSum).toFixed(2)} USDT`;
    }
    window.scrollTo(0, document.body.scrollHeight);
  </script>
@endsection

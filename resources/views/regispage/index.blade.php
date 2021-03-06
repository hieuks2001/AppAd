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
              &lt;div style=&quot;width: 100%&quot;&gt;
              &lt;button
              id=&quot;getCode&quot;
              style=&quot;
              margin: auto;
              display: block;
              padding: 10px 20px;
              outline: none;
              border: 0;
              background-color: red;
              color: white;
              font-weight: bold;
              border-radius: 10px;
              &quot;
              &gt;
              L???y m&atilde;
              &lt;/button&gt;
              &lt;/div&gt;
              &lt;div id=&quot;canihelpu&quot; style=&quot;text-align:
              center&quot;&gt;
              &lt;span id=&quot;countdown&quot; style=&quot;font-size: 2rem;
              font-weight: bold&quot;&gt;&lt;/span&gt;
              &lt;p id=&quot;notification&quot; style=&quot;font-size: 1.2rem;
              font-weight: bold; margin: 0&quot;&gt;&lt;/p&gt;
              &lt;/div&gt;
              &lt;script&gt;
              var value = &quot;{{ Session::get('pageId') }}&quot;;
              &lt;/script&gt;
              &lt;script
              src=&quot;{{ asset('ican.js') }}&quot;&gt;&lt;/script&gt; </code>
          </div>
          <p>
            + Copy to??n b??? code n??y v?? g???n v??o Footer c???a website
          </p>
          <p>
            + C???n t???t c??c plugin cache ????? t???i ??u v???i th???i gian th???c
          </p>
          <p class="text-primary mt-3 text-xl font-bold">Nh???ng l??u ?? khi d??ng
            traffic user:</p>
          <small>
            + N??n ?????y traffic user khi t??? kho?? ???? v??o ???????c ??t nh???t t??? trang 1-5 ?????
            th??nh vi??n c?? th??? t??m th???y v?? click v??o web.
            <br>
            + N??n ?????y traffic user ??t nh???t t??? 20-30 ng??y ????? c?? hi???u qu??? t???t nh???t.
            <br>
            + Khi ?????y key ch??nh n??n ?????y k??m v???i key brand ????? t??ng t??nh t??? nhi??n
            cho
            website (?????y key brand t???t cho key ch??nh).
            <br>
            + Traffic user l?? ch???t x??c t??c gi??p t??? kh??a l??n TOP nhanh h??n, tuy
            nhi??n
            website c???n ph???i t???i ??u onpage v?? backlink th???t t???t tr?????c khi ?????y
            traffic.
            <br>
            + Qu?? kh??ch n??n ch??? ?????ng theo d??i d??? li???u t??? Google analytics v??
            Google
            search console ????? ?????i chi???u v???i d??? li???u th???ng k?? c???a ch??ng t??i.
            <br>
            + Ch??ng t??i cam k???t 100% traffic user ng?????i d??ng l?? th???t, kh??ng tool,
            kh??ng fake IP, qu?? kh??ch c?? th??? t??? m??nh l??m nhi???m v??? ????? ki???m ch???ng.
          </small>
          <div class="modal-action">
            <label
              for="my-modal"
              class="btn btn-block"
            >Ho??n th??nh</label>
          </div>
        </div>
      </div>
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
          placeholder="Nh???p url ????ch mu???n ch???y traffic"
          class="input input-bordered mb-5 w-full"
          required
        >
        <input
          type="text"
          name="keyword"
          placeholder="Nh???p t??? kh??a"
          class="input input-bordered mb-5 w-full"
          required
        >
        <input
          type="text"
          name="traffic_per_day"
          placeholder="Nh???p l?????ng Traffic m???i ng??y"
          class="input input-bordered mb-5 w-full"
          required
        >
        <input
          type="text"
          name="traffic_sum"
          id="traffic_sum"
          placeholder="Nh???p t???ng Traffic"
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
          > Vui l??ng ch???n lo???i site </option>
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
            selected
            disabled
          > Vui l??ng ch???n g??i Onsite </option>
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
        >T???ng USDT ph???i
          tr???</p>
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
    <div class="flex items-center justify-between">
      <h3 class="text-2xl font-bold text-slate-800">L???ch s??? traffic</h3>
      <input
        type="text"
        placeholder="Search..."
        class="input input-ghost w-full max-w-xs"
      />
    </div>
    @php
      $tabs = ['??ang ch???', '??ang ch???y', 'Ho??n th??nh', 'L???i'];
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
  </div>
  <script>
    const siteTypes = {!! json_encode($onsite->toArray(), JSON_HEX_TAG) !!}
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
      const siteType = siteTypes.find(siteType => siteType.id == e.target
        .value);
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
      console.log(timeOnsite, trafficSum);
      priceEle.textContent = `${(timeOnsite * trafficSum).toFixed(2)} USDT`;
    }
    window.scrollTo(0, document.body.scrollHeight);
  </script>
@endsection

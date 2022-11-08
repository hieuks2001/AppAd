<?php

namespace App\Http\Requests;

use App\Constants\OntimeTypeConstants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderPageTraffic extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'url' => 'required|url',
      'keyword' => 'required|alpha',
      'traffic_per_day' => 'required|integer|min:30',
      'traffic_sum' => 'required|integer|gt:traffic_per_day',
      'page_type' => 'required|uuid',
      'onsite' => 'required|numeric'
    ];
  }
  public function messages()
  {
    return [
      'url.required' => 'Vui lòng nhập đường dẫn trang',
      'url.url' => 'Đường dẫn trang không đúng định dạng',
      'keyword.required' => 'Vui lòng nhập từ khoá',
      'keyword.alpha' => 'Từ khoá chỉ gồm các từ',
      'traffic_per_day.required' => 'Vui lòng nhập số traffic mỗi ngày',
      'traffic_per_day.integer' => 'Số traffic là các chữ số',
      'traffic_per_day.min' => 'Số traffic tối thiểu là 30',
      'traffic_sum.required' => 'Vui lòng nhập tổng traffic muốn mua',
      'traffic_sum.integer' => 'Tổng traffic là các chữ số',
      'traffic_sum.gt' => 'Tổng traffic phải lớn hơn số traffic mỗi ngày',
      'page_type.required' => 'Vui lòng chọn loại trang',
      'page_type.uuid' => 'Vui lòng chọn lại loại trang',
      'onsite.required' => 'Vui lòng chọn gói onsite',
      'onsite.numeric' => 'Vui lòng chọn lại gói onsite',
    ];
  }
}

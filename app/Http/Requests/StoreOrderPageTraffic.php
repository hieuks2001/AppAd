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
            'traffic_per_day' => 'required|numeric|min:30',
            'traffic_sum' => 'required|numeric|min:500',
            // 'onsite' => [
            //     'required', Rule::in([
            //         OntimeTypeConstants::TYPE_60,
            //         OntimeTypeConstants::TYPE_70,
            //         OntimeTypeConstants::TYPE_90,
            //         OntimeTypeConstants::TYPE_120,
            //         OntimeTypeConstants::TYPE_150
            //     ])
            // ],
            'onsite' => 'required|numeric',
            'page_type' => 'required',
            'keyword' => 'required'
        ];
    }
}

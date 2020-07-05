<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        if ($this->isMethod('POST')) {
            return [
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required', 'string', 'max:255'],
                'publication_date' => ['required', 'date_format:Y-m-d'],
                'beginning' => ['required', 'date_format:Y-m-d'],
                'end' => ['required', 'date_format:Y-m-d']
            ];
        } else {
            return [
                'title' => ['string', 'max:255'],
                'content' => ['string', 'max:255'],
                'publication_date' => ['date_format:Y-m-d'],
                'beginning' => ['date_format:Y-m-d'],
                'end' => ['date_format:Y-m-d']
            ];
        }
    }
}

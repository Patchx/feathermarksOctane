<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePageRequest extends FormRequest
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
            'category_id' => "required",
            'instaopen_command' => "max:10",
            'page_html' => "required",
            'page_name' => "required|max:100",
            'search_keywords' => "required|max:100",
            'visibility_level' => "required|in:anyone,me_only",
        ];
    }
}

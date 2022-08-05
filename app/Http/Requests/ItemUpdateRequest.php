<?php

namespace App\Http\Requests;

class ItemUpdateRequest extends BaseRequest
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
            'name' => 'required|string|max:255',
            'note' => 'required|string',
            'photos' => 'array|min:1|max:8',
            'links' => 'sometimes|array',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'note' => 'Note',
            'links' => 'Links',
            'photos' => 'Photos',
        ];
    }
}

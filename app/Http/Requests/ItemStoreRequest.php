<?php

namespace App\Http\Requests;

class ItemStoreRequest extends BaseRequest
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
            'closetId' => 'required|integer|exists:closets,id',
            'name' => 'required|string|max:255',
            'note' => 'required|string',
            'photos' => 'required|array',
            'links' => 'sometimes|array',
        ];
    }

    public function attributes()
    {
        return [
            'closetId' => 'Closet',
            'name' => 'Name',
            'note' => 'Note',
            'links' => 'Links',
            'photos' => 'Photos',
        ];
    }
}

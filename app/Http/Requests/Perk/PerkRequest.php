<?php

namespace App\Http\Requests\Perk;

use App\Http\Requests\Request;

class PerkRequest extends Request
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
        $rules =  [
            'title' => 'required',
            'description' => 'required',
            'type_id' => 'required',
            'link' => 'required',
            'logo' => 'required'
        ];
        if($this->input('type_id') > 1){
            $riles['value'] = 'required|numeric|min:0';
        }

        return $rules;
    }
}

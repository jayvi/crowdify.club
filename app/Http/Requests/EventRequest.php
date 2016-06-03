<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EventRequest extends Request
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
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            //'logo' => 'required',
            'categories' => 'required|array',
            'type'  => 'required'
        ];

        if($this->has('publish_social')){
            $rules['facebook_link'] = 'required';
            $rules['twitter_link'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'A title is required',
            'description.required'  => 'A description is required',
            'location.required'    => 'Please specify the event location',
            'start_date.required'   => 'Please specify the event start date',
            'end_date.required'   => 'Please specify the event ending date',
            'categories.required' => 'Please select event categories',
            'categories.array' => 'Please select event categories',
            'type'            => 'Please select event type',
        ];
    }

    public function getFieldsToSave($isUpdate = false)
    {
        $fields = [
            'title',
            'description',
            'location',
            'start_date',
            'end_date',
            'organizer_name',
            'organizer_description',
            'facebook_link',
            'twitter_link',
            'type',
            'latitude',
            'longitude',
            'logo'
        ];
        if($isUpdate){
            $fields[]= 'status';
        }
        return $this->only($fields);
    }
}

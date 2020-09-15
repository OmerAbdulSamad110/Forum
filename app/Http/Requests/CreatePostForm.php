<?php

namespace App\Http\Requests;

use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use Illuminate\Foundation\Http\FormRequest;

class CreatePostForm extends FormRequest
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
            'body' => ['required', new SpamFree]
        ];
    }

    public function persist($obj)
    {
        if ($obj instanceof Thread) {
            return $obj->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        } elseif ($obj instanceof Reply) {
            $obj->body = request('body');
            return !!$obj->save();
        }
    }
}

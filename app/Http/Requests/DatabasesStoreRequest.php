<?php

namespace App\Http\Requests;

use App\Enums\DatabasesType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DatabasesStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'host' => ['required'],
            'port' => ['required', 'integer'],
            'type' => ['required', new EnumValue(DatabasesType::class)],
            'dbname' => ['required'],
            'user' => ['required'],
            'password' => ['required'],
            'keterangan' => ['max:255'],
        ];
    }
}

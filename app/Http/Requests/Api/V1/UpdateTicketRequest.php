<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTicketRequest extends BaseTicketRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $authorIdRule = ['sometimes', 'integer', 'numeric', 'min:1', 'exists:users,id'];
        $rules = [
            'data.attributes.title' => ['sometimes', 'string'],
            'data.attributes.description' => ['sometimes', 'string'],
            'data.attributes.status' => ['sometimes', 'string', 'in:A,C,H,X'],
            'data.relationships.author.data.id' => ['prohibited'],
        ];

        if (Auth::user()->tokenCan(Abilities::UpdateAnyTicket)) {
            $rules['data.relationships.author.data.id'] = $authorIdRule;
        }

        return $rules;
    }
}

<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class StoreTicketRequest extends BaseTicketRequest
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
        $isTicketsController = $this->routeIs('tickets.store');
        $authorIdAttr = $isTicketsController ? 'data.relationships.author.data.id' : 'author';
        $user = Auth::user();
        $authorIdRule = ['required', 'integer', 'numeric', 'min:1', 'exists:users,id'];

        $rules = [
            'data' => ['required', 'array'],
            'data.attributes' => ['required', 'array'],
            'data.attributes.title' => ['required', 'string'],
            'data.attributes.description' => ['required', 'string'],
            'data.attributes.status' => ['required', 'string', 'in:A,C,H,X'],
        ];

        if($isTicketsController) {
            $rules['data.relationships'] = ['required', 'array'];
            $rules['data.relationships.author'] = ['required', 'array'];
            $rules['data.relationships.data'] = ['required', 'array'];
        }

        $rules[$authorIdAttr] = [...$authorIdRule, Rule::in([$user->id])];

        if( $user->tokenCan(Abilities::CreateTicket) ) {
            $rules[$authorIdAttr] = $authorIdRule;
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        if($this->routeIs('authors.tickets.store')) {
            $this->merge([
                'author' => $this->route('author'),
            ]);
        }
    }
}

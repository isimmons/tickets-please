<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
use Illuminate\Contracts\Validation\ValidationRule;
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
        $authorIdAttr = $this->routeIs('tickets.store') ? 'data.relationships.author.data.id' : 'author';
        $user = $this->user();
        $authorIdRule = ['required', 'integer', 'numeric', 'min:1', 'exists:users,id'];

        $rules = [
            'data.attributes.title' => ['required', 'string'],
            'data.attributes.description' => ['required', 'string'],
            'data.attributes.status' => ['required', 'string', 'in:A,C,H,X'],
            $authorIdAttr => [...$authorIdRule, Rule::in([$user->id])],
        ];

        $user = $this->user();

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

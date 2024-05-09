<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
use Illuminate\Foundation\Http\FormRequest;

class BaseTicketRequest extends FormRequest
{
    public function mappedAttributes(array $additionalAttributes = []): array
    {
        $attributeMap = array_merge([
            'data.attributes.title' => 'title',
            'data.attributes.description' => 'description',
            'data.attributes.status' => 'status',
            'data.attributes.createdAt' => 'created_at',
            'data.attributes.updatedAt' => 'updated_at',
            'data.relationships.author.data.id' => 'user_id'
        ], $additionalAttributes);

        if (!$this->user()->tokenCan(Abilities::UpdateOwnTicket))

        $attributesToUpdate = [];

        foreach ($attributeMap as $key => $attribute) {
            if($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
    }
    public function messages(): array
    {
        return [
            'data.attributes.status' => 'data.attributes.status must be capital A,C,H, or X.',
        ];
    }
}

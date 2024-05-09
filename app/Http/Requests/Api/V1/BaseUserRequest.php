<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

use function strtoupper;

class BaseUserRequest extends FormRequest
{
    public function mappedAttributes(array $additionalAttributes = []): array
    {
        $attributeMap = array_merge([
            'data.attributes.name' => 'name',
            'data.attributes.email' => 'email',
            'data.attributes.isAdmin' => 'is_admin',
            'data.attributes.password' => 'password',
            'data.attributes.createdAt' => 'created_at',
            'data.attributes.updatedAt' => 'updated_at',
        ], $additionalAttributes);

        if (!$this->user()->tokenCan(Abilities::UpdateOwnTicket))

        $attributesToUpdate = [];

        foreach ($attributeMap as $key => $attribute) {
            if($this->has($key)) {
                $value = $this->input($key);

                if ($attribute === 'password') {
                    $value = bcrypt($value);
                }

                $attributesToUpdate[$attribute] = $value;
            }
        }

        return $attributesToUpdate;
    }
}

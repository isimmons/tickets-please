<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property String $id
 * @property String $name
 * @property String $email
 * @property mixed $email_verified_at
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                $this->mergeWhen($request->routeIs('users.*'), [
                  'emailVerifiedAt' => $this->email_verified_at,
                  'createdAt' => $this->created_at,
                  'updatedAt' => $this->updated_at,
                ])
            ]
        ];
    }
}

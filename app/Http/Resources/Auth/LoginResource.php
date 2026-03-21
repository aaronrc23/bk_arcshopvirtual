<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'token' => $this['accessToken'],
            'refreshToken' => $this['refreshToken'],
            'user' => [
                'id' => $this['user']['id'],
                'email' => $this['user']['email'],
                'name' => trim(
                    data_get($this, 'user.profile.name', '') . ' ' .
                        data_get($this, 'user.profile.apellidos', '')
                ),
            ],
        ];
    }
}

<?php

namespace App\Http\Resources;

use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Resources\Json\JsonResource;

class ChirpResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "message" => $this->message,
            'user' => $this->user,
            'created_at' => $this->created_at,
        ];
    }
}

<?php

namespace Mojtaba\Chatable\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        $this->load('sender');

        return parent::toArray($request);
    }
}
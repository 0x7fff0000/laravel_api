<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\User;
use App\Models\Message;
use App\Http\Resources\UserResource;
use App\Http\Resources\MessageUserResource;


class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $senderResource = new MessageUserResource(User::findOrFail($this->sender_id));
        $recieverResource = new MessageUserResource(User::findOrFail($this->reciever_id));
        return [
            'id' => $this->id,
            'sender' => $senderResource,
            'reciever' => $recieverResource,
            'text' => $this->text,
            'viewed' => $this->viewed,
            'updated_at' => $this->updated_at
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\User;
use App\Http\Resources\UserResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $postedUser = User::findOrFail($this->user_id);
        $postedUserResource = new UserResource($postedUser);

        $postLike = $request->user()->getPostLike($this->id);
        $postLikeResource = $postLike ? new PostLikeResource($postLike) : [];

        return [
            'id' => $this->id,
            'user' => $postedUserResource,
            'self-liked' => $postLikeResource,
            'likes' => $this->getTotalLikesAttribute(),
            'text' => $this->text,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at
        ];
    }
}

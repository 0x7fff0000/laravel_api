<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

use Laravel\Sanctum\HasApiTokens;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\Message;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function hashPassword($plainText)
    {
        $this->password = Hash::make($plainText);
    }

    public function getPosts()
    {
        return $this->hasMany(Post::class);
    }

    public function addPost($text)
    {
        return Post::create([
            'user_id' => $this->id,
            'text' => $text
        ]);
    }

    public function isPostAuthor($postId)
    {
        return Post::where(['id' => $postId, 'user_id' => $this->id])->count();
    }

    public function togglePostLike($postId)
    {
        if (Post::find($postId) == null) {
            return false;
        }

        $postLikeList = $this->getPostLike($postId);

        if ($postLikeList != null) {
            $postLikeList->first()->delete();
        } else {
            PostLike::create([
                'post_id' => $postId,
                'user_id' => $this->id
            ]);
        }

        return true;
    }

    public function getPostLike($postId)
    {
        return PostLike::where(['post_id' => $postId, 'user_id' => $this->id])->first();
    }

    public function getMessages()
    {
        return Message::where('sender_id', $this->id)->orWhere('reciever_id', $this->id)->get();
    }

    public function isMessageSender(Message $message)
    {
        return $message->sender_id == $this->id;
    }

    public function isMessageReciever(Message $message)
    {
        return $message->reciever_id == $this->id;
    }

    public function isInterlocutor(Message $message)
    {
        return $this->isMessageSender($message) || $this->isMessageReciever($message);
    }
}

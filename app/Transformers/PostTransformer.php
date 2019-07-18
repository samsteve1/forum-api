<?php

namespace App\Transformers;

use App\Post;
use League\Fractal\TransformerAbstract;
use App\Transformers\UserTransformer;
class PostTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user'];
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Post $post)
    {
        return [
            'id' => $post->id,
            'body'  =>  $post->body,
            'created_at' => $post->created_at->toDateTimeString(),
            'created_at_human'  =>  $post->created_at->diffForHumans(),
        ];
    }
    public function includeUser(Post $post)
    {
        return $this->item($post->user, new UserTransformer);
    }
}

<?php

namespace App\Policies;

use App\User;
use App\Topic;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    public function touch(User $user, Topic $topic)
    {
        return $user->id === $topic->user->id;
    }
}

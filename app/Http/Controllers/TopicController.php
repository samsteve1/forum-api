<?php

namespace App\Http\Controllers;

use App\{Post, Topic};
use Illuminate\Http\Request;
use App\Http\Requests\{StoreTopicRequest, UpdateTopicRequest};
use App\Transformers\TopicTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::latestFirst()->paginate(2);

        $topicsCollection = $topics->getCollection();

        return fractal()
            ->collection($topics)
            ->parseIncludes(['user'])
            ->transformWith(new TopicTransformer)
            ->paginateWith(new IlluminatePaginatorAdapter($topics))
            ->toArray();
    }

    public function show(Topic $topic)
    {
        return fractal()
            ->item($topic)
            ->parseIncludes(['users', 'posts', 'posts.user'])
            ->transformWith(new TopicTransformer)
            ->toArray();
    }

    public function store(StoreTopicRequest $request)
    {
        $topic = new Topic;

        $topic->title = $request->title;
        $topic->user()->associate($request->user());

        $post = new Post;

        $post->body = $request->body;
        $post->user()->associate($request->user());

        $topic->save();

        $topic->posts()->save($post);

        return fractal()
            ->item($topic)
            ->parseIncludes(['user'])
            ->transformWith(new TopicTransformer)
            ->toArray();
    }

    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        $this->authorize('touch', $topic);
        $topic->title = $request->get('title', $topic->title);
        $topic->save();

        return fractal()
            ->item($topic)
            ->parseIncludes(['users', 'posts', 'posts.user'])
            ->transformWith(new TopicTransformer)
            ->toArray();
    }
    public function destroy(Topic $topic)
    {
        $this->authorize('touch', $topic);

        $topic->delete();
        
        return response(null, 204);
    }
}

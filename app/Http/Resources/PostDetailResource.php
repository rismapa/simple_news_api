<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PostDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'content_post' => $this->content_post,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'author' => $this->author,
            'writer' => $this->whenLoaded('writer'),
            'comments' => $this->whenLoaded('comments', function () {
                return collect($this->comments)->each(function ($e) {
                    $e->comments;
                    return $e;
                });
            }),
        ];
    }

    // public function withResponse($request, $response)
    // {
    //     $response->setData([
    //         'message' => 'Post created successfully',
    //         'data' => $response->getData(),
    //     ]);
    // }
}

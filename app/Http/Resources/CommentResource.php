<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'post_id' => $this->post_id,
            'author' => $this->author,
            'comment_content' => $this->comment_content,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'comments' => $this->whenLoaded('comments'),
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setData([
            'message' => 'Comment created successfully',
            'data' => $response->getData(),
        ]);
    } 
}

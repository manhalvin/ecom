<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    private $statusText;
    private $statusCode;

    public function __construct($resource, $statusText='success', $statusCode = '200')
    {
        parent::__construct($resource);
        $this->statusText = $statusText;
        $this->statusCode = $statusCode;
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'users' => $this->collection,
            'status' => $this->statusText,
            'status_code' => $this->statusCode,
            'count' => $this->collection->count(),
        ];
    }
}

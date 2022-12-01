<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class APIPaginateCollection extends ResourceCollection
{
    private $resourceClass;
    public $resource;
    public function __construct($resource,$resourceClass)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->resourceClass = $resourceClass;
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
            'data' => $this->resourceClass::collection($this->collection),
            'pagination' => [
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
                'limit' => $this->resource->perPage(),
                'total' => $this->resource->total()
            ]
        ];
    }
}

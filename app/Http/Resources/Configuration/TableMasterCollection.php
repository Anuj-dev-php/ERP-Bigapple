<?php

namespace App\Http\Resources\Configuration;

use Illuminate\Http\Resources\Json\JsonResource;

class TableMasterCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'table_label' => $this->table_label ?? "",
        ];
    }
}

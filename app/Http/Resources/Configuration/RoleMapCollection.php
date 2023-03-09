<?php

namespace App\Http\Resources\Configuration;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleMapCollection extends JsonResource
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
            'Insert_Roles' => $this->Insert_Roles ?? "",
            'Edit_Roles' => $this->Edit_Roles ?? "",
            'Delete_Roles' => $this->Delete_Roles ?? "",
            'View_Roles' => $this->View_Roles ?? "",
            'Print_Roles' => $this->Print_Roles ?? "",
            'masters' => $this->masters ?? "",
            'history' => $this->history ?? "",
            'amend' => $this->amend ?? "",
            'copy' => $this->copy ?? "",
        ];
    }
}

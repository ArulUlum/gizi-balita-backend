<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatistikResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $parent = parent::toArray($request);
        $statistik = [
            "berat" => $this->kategoriBerat(),
            "tinggi" => $this->kategoriTinggi(),
            "lingkar_kepala" => $this->kategoriLingkarKepala(),
            "gizi" => $this->kategoriGizi(),

        ];
        $parent['statistik'] = $statistik;
        return $parent;
    }
}

<?php

namespace Hanafalah\ModuleMedicalTreatment\Resources\MedicalServiceTreatment;

class ShowMedicalServiceTreatment extends ViewMedicalServiceTreatment
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request): array
  {
    $arr = [
    ];
    $arr = $this->mergeArray(parent::toArray($request),$arr);
    return $arr;
  }
}

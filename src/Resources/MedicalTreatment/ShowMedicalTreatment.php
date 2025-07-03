<?php

namespace Hanafalah\ModuleMedicalTreatment\Resources\MedicalTreatment;

use Hanafalah\LaravelSupport\Resources\ApiResource;

class ShowMedicalTreatment extends ViewMedicalTreatment
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
      'service_label'     => $this->relationValidation('serviceLabel',function(){
        return $this->serviceLabel->toShowApi()->resolve();
      }, $this->prop_service_label),
    ];
    $arr = $this->mergeArray(parent::toArray($request),$arr);
    return $arr;
  }
}

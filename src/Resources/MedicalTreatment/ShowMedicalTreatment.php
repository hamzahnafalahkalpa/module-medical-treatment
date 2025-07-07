<?php

namespace Hanafalah\ModuleMedicalTreatment\Resources\MedicalTreatment;

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
      'treatment'         => $this->relationValidation('treatment',function(){
        return $this->treatment->toShowApi()->resolve();
      }),
      'medical_service_treatments' => $this->relationValidation('medicalServiceTreatments',function(){
        return $this->medicalServiceTreatments->transform(function($medicalServiceTreatment){
          return $medicalServiceTreatment->toShowApi()->resolve();
        });
      }),
      'service_label'     => $this->relationValidation('serviceLabel',function(){
        return $this->serviceLabel->toShowApi()->resolve();
      }, $this->prop_service_label),
    ];
    $arr = $this->mergeArray(parent::toArray($request),$arr);
    return $arr;
  }
}

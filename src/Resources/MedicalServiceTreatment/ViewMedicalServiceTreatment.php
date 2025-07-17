<?php

namespace Hanafalah\ModuleMedicalTreatment\Resources\MedicalServiceTreatment;

use Hanafalah\LaravelSupport\Resources\ApiResource;

class ViewMedicalServiceTreatment extends ApiResource
{
  public function toArray(\Illuminate\Http\Request $request): array
  {
    $arr = [
      'id' => $this->id, 
      'medical_treatment_id' => $this->medical_treatment_id, 
      'service_id' => $this->service_id,
      'service' => $this->prop_service
    ];
    return $arr;
  }
}

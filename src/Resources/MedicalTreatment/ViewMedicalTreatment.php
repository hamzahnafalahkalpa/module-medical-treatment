<?php

namespace Hanafalah\ModuleMedicalTreatment\Resources\MedicalTreatment;

use Hanafalah\LaravelSupport\Resources\ApiResource;

class ViewMedicalTreatment extends ApiResource
{
  public function toArray(\Illuminate\Http\Request $request): array
  {
    $arr = [
      'id'                => $this->id,
      'name'              => $this->name,
      'treatment_code'    => $this->treatment_code ?? $this->medical_treatment_code,
      'treatment'         => $this->prop_treatment,
      'service_label_id'  => $this->service_label_id,
      'service_label'     => $this->prop_service_label,
      'created_at'        => $this->created_at,
      'updated_at'        => $this->updated_at
    ];
    return $arr;
  }
}

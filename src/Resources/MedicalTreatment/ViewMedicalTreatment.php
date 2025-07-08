<?php

namespace Hanafalah\ModuleMedicalTreatment\Resources\MedicalTreatment;

use Hanafalah\ModuleExamination\Resources\ExaminationStuff\ViewExaminationStuff;

class ViewMedicalTreatment extends ViewExaminationStuff
{
  public function toArray(\Illuminate\Http\Request $request): array
  {
    $arr = [
      'id'                => $this->id,
      'name'              => $this->name,
      'treatment_code'    => $this->treatment_code ?? $this->medical_treatment_code,
      'treatment'         => $this->prop_treatment,
    ];
    $arr = $this->mergeArray(parent::toArray($request),$arr);
    return $arr;
  }
}

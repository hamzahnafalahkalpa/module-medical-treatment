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
      'tariff_components' => $this->relationValidation('priceComponents', function () {
        $priceComponents = $this->priceComponents;
        return $priceComponents->transform(function ($priceComponent) {
          return  [
            "id"    => $priceComponent->tariff_component_id,
            "price" => $priceComponent->price ?? $this->treatment->price ?? 0,
            "name"  => $priceComponent->tariffComponent->name ?? "Name is invalid",
          ];
        });
      }),
      'medic_services' => $this->prop_medic_services ?? [],
      'created_at'     => $this->created_at,
      'updated_at'     => $this->updated_at
    ];
    return $arr;
  }
}

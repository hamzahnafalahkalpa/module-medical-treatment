<?php

namespace Hanafalah\ModuleMedicalTreatment\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleMedicalTreatment\Contracts\Data\MedicalServiceTreatmentData as DataMedicalServiceTreatmentData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class MedicalServiceTreatmentData extends Data implements DataMedicalServiceTreatmentData
{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('medical_treatment_id')]
    #[MapName('medical_treatment_id')]
    public mixed $medical_treatment_id;

    #[MapInputName('service_id')]
    #[MapName('service_id')]
    public mixed $service_id;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];

    public static function after(self $data): self{
        $new = static::new();

        $props = &$data->props;
        
        $service = $new->ServiceModel();
        if (isset($service)) $service = $service->findOrFail($data->service_id);
        $props['prop_service'] = $service->toViewApi()->resolve();
        $props['prop_service_reference'] = $service->reference->toViewApi()->resolve();
        return $data;
    }
}

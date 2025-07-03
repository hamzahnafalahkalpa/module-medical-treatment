<?php

namespace Hanafalah\ModuleMedicalTreatment\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleMedicalTreatment\Contracts\Data\MedicalTreatmentData as DataMedicalTreatmentData;
use Hanafalah\ModuleTreatment\Contracts\Data\TreatmentData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class MedicalTreatmentData extends Data implements DataMedicalTreatmentData
{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('name')]
    #[MapName('name')]
    public string $name;

    #[MapInputName('service_label_id')]
    #[MapName('service_label_id')]
    public mixed $service_label_id = null;

    #[MapInputName('medical_service_treatments')]
    #[MapName('medical_service_treatments')]
    #[DataCollectionOf(MedicalServiceTreatmentData::class)]
    public ?array $medical_service_treatments = [];

    #[MapInputName('treatment')]
    #[MapName('treatment')]
    public TreatmentData $treatment;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];

    public static function before(array &$attributes){
        $new = self::new();

        $attributes['treatment']['name'] = $attributes['name'];

        $service_label = $new->ServiceLabelModel();
        if (isset($attributes['service_label_id'])) $service_label = $service_label->findOrFail($attributes['service_label_id']);
        $attributes['prop_service_label'] = $service_label->toViewApi()->resolve();
    }
}

<?php

namespace Hanafalah\ModuleMedicalTreatment\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleMedicalTreatment\Contracts\Data\MedicalTreatmentData as DataMedicalTreatmentData;
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

    #[MapInputName('treatment')]
    #[MapName('treatment')]
    public string $treatment;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];
}

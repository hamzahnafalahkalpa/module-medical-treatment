<?php

namespace Hanafalah\ModuleMedicalTreatment\Data;

use Hanafalah\ModuleExamination\Data\ExaminationStuffData;
use Hanafalah\ModuleMedicalTreatment\Contracts\Data\MedicalTreatmentData as DataMedicalTreatmentData;
use Hanafalah\ModuleTreatment\Contracts\Data\TreatmentData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class MedicalTreatmentData extends ExaminationStuffData implements DataMedicalTreatmentData
{
    #[MapInputName('medical_service_treatments')]
    #[MapName('medical_service_treatments')]
    #[DataCollectionOf(MedicalServiceTreatmentData::class)]
    public ?array $medical_service_treatments = [];

    #[MapInputName('treatment')]
    #[MapName('treatment')]
    public TreatmentData $treatment;

    public static function before(array &$attributes){
        $new = self::new();
        $attributes['treatment']['name'] = $attributes['name'];
        $attributes['flag'] ??= 'MedicalTreatment';
        parent::before($attributes);
    }
}

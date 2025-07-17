<?php

namespace Hanafalah\ModuleMedicalTreatment\Schemas;

use Hanafalah\ModuleMedicalTreatment\Contracts;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleMedicalTreatment\Contracts\Data\MedicalServiceTreatmentData;

class MedicalServiceTreatment extends PackageManagement implements Contracts\Schemas\MedicalServiceTreatment
{
    protected string $__entity = 'MedicalServiceTreatment';
    public static $medical_service_treatment_model;

    protected array $__cache = [
        'index' => [
            'name'     => 'medical_service_treatment',
            'tags'     => ['medical_service_treatment', 'medical_service_treatment-index'],
            'duration' => 24*60
        ]
    ];

    public function prepareStoreMedicalServiceTreatment(MedicalServiceTreatmentData $medical_service_treatment_dto): Model{        
        if (isset($medical_service_treatment_dto->id)) {
            $guard = ['id' => $medical_service_treatment_dto->id];
        } else {
            $guard = [
                'service_id'           => $medical_service_treatment_dto->service_id,
                'medical_treatment_id' => $medical_service_treatment_dto->medical_treatment_id
            ];
        }
        $model = $this->usingEntity()->updateOrCreate($guard);
        $model->name = $medical_service_treatment_dto->name ?? null;

        $this->fillingProps($model, $medical_service_treatment_dto->props);
        $model->save();
        return static::$medical_service_treatment_model = $model;
    }
}

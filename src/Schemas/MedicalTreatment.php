<?php

namespace Hanafalah\ModuleMedicalTreatment\Schemas;

use Hanafalah\ModuleMedicalTreatment\Contracts;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleMedicalTreatment\Contracts\Data\MedicalTreatmentData;

class MedicalTreatment extends PackageManagement implements Contracts\Schemas\MedicalTreatment
{
    protected string $__entity = 'MedicalTreatment';
    public static $medical_treatment_model;

    public function prepareStoreMedicalTreatment(MedicalTreatmentData $medical_treatment_dto): Model
    {
        $model = $this->usingEntity()->updateOrCreate(['id' => $medical_treatment_dto->id ?? null], [
            'name' => $medical_treatment_dto->name
        ]);

        if (isset($medical_treatment_dto->medical_service_treatments) && count($medical_treatment_dto->medical_service_treatments) > 0) {
            $keep_service_treatment_ids = [];
            $medic_service_schema = $this->schemaContract('medical_service_treatment');
            foreach ($medical_treatment_dto->medical_service_treatments as $dto) {
                $medical_service_treatment    = $medic_service_schema->prepareStoreMedicalServiceTreatment($dto);
                $keep_service_treatment_ids[] = $medical_service_treatment->getKey();
            }
            $this->MedicalServiceTreatmentModel()->withoutGlobalScopes()
                ->where('medical_treatment_id', $model->getKey())
                ->whereNotIn('id', $keep_service_treatment_ids)
                ->forceDelete();
        } else {
            throw new \Exception('medical_service_treatment is required');
        }

        $model->load('treatment');
        $treatment_dto = &$medical_treatment_dto->treatment;
        $treatment_dto->id = $model->treatment->getKey();
        $treatment_dto->reference_type = $model->getMorphClass();
        $treatment_dto->reference_id = $model->getKey();

        if (isset($medical_treatment_dto->service_prices) && count($medical_treatment_dto->service_prices) > 0) {
            foreach ($medical_treatment_dto->service_prices as $service_price) {
                $service_price->service_id     = $treatment_dto->id;
                $service_price = $this->schemaContract('service_price')->prepareStorePriceComponent($service_price);
                $treatment_dto->price = $service_price->price;
                $treatment_dto->cogs  = $service_price->cogs;
            }
        }

        $this->schemaContract('treatment')->prepareStoreTreatment($treatment_dto);
        $this->fillingProps($model,$medical_treatment_dto->props);
        $model->save();

        return static::$medical_treatment_model = $model;
    }
}

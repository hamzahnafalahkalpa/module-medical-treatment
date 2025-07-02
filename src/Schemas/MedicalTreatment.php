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
    protected static $__service_label_model;

    public function prepareStoreMedicalTreatment(MedicalTreatmentData $medical_treatment_dto): Model
    {
        $attributes ??= \request()->all();
        if (!isset($attributes['name'])) throw new \Exception('name is required');

        $model = $this->medicalTreatment()->updateOrCreate([
            'id' => $attributes['id'] ?? null
        ], [
            'name' => $attributes['name'],
        ]);

        if (isset($attributes['medic_services']) && count($attributes['medic_services']) > 0) {
            $medic_service_schema = $this->schemaContract('medical_service_treatment');
            $keep_service_treatment_ids = [];
            foreach ($attributes['medic_services'] as $medic_service) {
                $service = $this->ServiceModel()->findOrFail($medic_service['id']);
                $medical_service_treatment = $medic_service_schema->prepareStoreMedicalServiceTreatment([
                    'medical_treatment_id' => $model->getKey(),
                    'medic_service_id'     => $service->reference_id,
                    'name'                 => $service->name,
                    'note'                 => $service->result
                ]);
                $keep_service_treatment_ids[] = $medical_service_treatment->getKey();
            }
            $this->MedicalServiceTreatmentModel()
                ->withoutGlobalScopes()
                ->where('medical_treatment_id', $model->getKey())
                ->whereNotIn('id', $keep_service_treatment_ids)
                ->forceDelete();
        } else {
            throw new \Exception('medic_services is required');
        }

        $treatment = $model->treatment;

        if (isset($attributes['service_label_id'])) {
            $service_label = $this->ServiceLabelModel()->findOrFail($attributes['service_label_id']);
            static::$__service_label_model = $service_label;

            $treatment = $model->treatment;
            $treatment->service_label = [
                'id'   => $attributes['service_label_id'],
                'name' => $service_label->name,
                'note' => $service_label->result
            ];
            $model->service_label = $treatment->service_label;
            $model->service_label_id = $attributes['service_label_id'];
        } else {
            $treatment->service_label = null;
            $model->service_label     = null;
            $model->service_label_id  = null;
        }

        if (isset($attributes['tariff_components']) && count($attributes['tariff_components']) > 0) {
            $price_schema = $this->schemaContract('price_component');
            $attributes['model_id'] = $model->getKey();
            $attributes['model_type'] = $model->getMorphClass();
            $price_schema->prepareStorePriceComponent($attributes);

            $treatment->price = $price_schema->getPrice();

            $service_price_schema = app($this->__schema_contracts['service_price']);
            $service_price_schema->prepareStoreServicePrice([
                'service_id'         => $treatment->getKey(),
                'service_item_id'    => $treatment->reference_id,
                'service_item_type'  => $treatment->reference_type,
                'price'              => $treatment->price,
            ]);

            if (isset($attributes['margin'])) {
                $treatment->cogs = $treatment->price - $treatment->price * $attributes['margin'] / 100;
            }
        }
        if (isset($attributes['examination_stuff_id'])) {
            $examStuff = $this->ExaminationStuffModel()->findOrFail($attributes['examination_stuff_id']);

            $treatment->service_label_id   = $examStuff->getKey();
            $treatment->service_label_name = $examStuff->name;
            $treatment->service_label_flag = $examStuff->flag;
        }
        $treatment->save();
        $model->save();

        $service_price_schema = $this->schemaContract('service_price');
        $service_price_schema->prepareStoreServicePrice([
            'service_id'         => $treatment->getKey(),
            'service_item_id'    => $treatment->reference_id,
            'service_item_type'  => $treatment->reference_type,
            'price'              => $treatment->price,
        ]);
        return static::$medical_treatment_model = $model;
    }
}

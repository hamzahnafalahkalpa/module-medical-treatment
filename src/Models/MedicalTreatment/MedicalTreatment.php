<?php

namespace Hanafalah\ModuleMedicalTreatment\Models\MedicalTreatment;

use Hanafalah\ModuleService\Concerns\HasServiceItem;
use Hanafalah\ModuleMedicalTreatment\Resources\MedicalTreatment\{ViewMedicalTreatment, ShowMedicalTreatment};
use Hanafalah\ModuleEncoding\Concerns\HasEncoding;
use Hanafalah\ModuleExamination\Models\ExaminationStuff;
use Hanafalah\ModuleTreatment\Concerns\HasTreatment;

class MedicalTreatment extends ExaminationStuff
{
    use HasServiceItem, HasTreatment, HasEncoding;

    protected $table = 'examination_stuffs';

    protected $casts = [
        'name' => 'string',
        'treatment_code' => 'string',
        'service_label_name' => 'string'
    ];

    protected static function booted(): void
    {
        parent::booted();
        static::creating(function ($query) {
            $query->medical_treatment_code ??= static::hasEncoding('MEDICAL_TREATMENT');
        });
    }

    public function getViewResource(){return ViewMedicalTreatment::class;}
    public function getShowResource(){return ShowMedicalTreatment::class;}

    public function medicalServiceTreatment(){return $this->hasOneModel('MedicalServiceTreatment','medical_treatment_id');}
    public function medicalServiceTreatments(){return $this->hasManyModel('MedicalServiceTreatment','medical_treatment_id');}
}

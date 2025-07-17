<?php

namespace Hanafalah\ModuleMedicalTreatment\Models\MedicalTreatment;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\ModuleMedicalTreatment\Resources\MedicalServiceTreatment\{ShowMedicalServiceTreatment,ViewMedicalServiceTreatment};
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class MedicalServiceTreatment extends BaseModel
{
    use HasUlids, SoftDeletes, HasProps;
    
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $list = ['id', 'medical_treatment_id', 'service_id'];
    protected $show = [];

    public function viewUsingRelation():array{
        return [];
    }

    public function showUsingRelation():array{
        return [];
    }

    public function getViewResource(){return ViewMedicalServiceTreatment::class;}
    public function getShowResource(){return ShowMedicalServiceTreatment::class;}

    //EIGER SECTION
    public function medicalTreatment(){return $this->belongsToModel('MedicalTreatment');}
    public function service(){return $this->belongsToModel('Service');}

    //ENDEIGER SECTION
}

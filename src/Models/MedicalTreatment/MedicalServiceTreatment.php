<?php

namespace Hanafalah\ModuleMedicalTreatment\Models\MedicalTreatment;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class MedicalServiceTreatment extends BaseModel
{
    use HasUlids, SoftDeletes, HasProps;
    
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $list = ['id', 'medical_treatment_id', 'service_id'];
    protected $show = [];

    //EIGER SECTION
    public function medicalTreatment(){return $this->belongsToModel('MedicalTreatment');}
    public function service(){return $this->belongsToModel('Service');}

    //ENDEIGER SECTION
}

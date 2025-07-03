<?php

namespace Hanafalah\ModuleMedicalTreatment\Models\MedicalTreatment;

use Hanafalah\ModuleService\Concerns\HasServiceItem;
use Hanafalah\ModuleMedicalTreatment\Enums\MedicalTreatment\Status;
use Hanafalah\ModuleMedicalTreatment\Resources\MedicalTreatment\ViewMedicalTreatment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\ModuleEncoding\Concerns\HasEncoding;
use Hanafalah\ModuleTreatment\Concerns\HasTreatment;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class MedicalTreatment extends BaseModel
{
    use HasUlids, SoftDeletes, HasProps, HasServiceItem, 
        HasTreatment, HasEncoding;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $list = ['id', 'name', 'service_label_id', 'status', 'props'];
    protected $show = [];

    protected $casts = [
        'name' => 'string',
        'treatment_code' => 'string'
    ];

    public function getPropsQuery(): array
    {
        return [
            'treatment_code' => 'props->prop_treatment->treatment_code'
        ];
    }

    protected static function booted(): void
    {
        parent::booted();
        static::creating(function ($query) {
            $query->medical_treatment_code ??= static::hasEncoding('MEDICAL_TREATMENT');
            $query->status ??= self::getStatus('ACTIVE');
        });
    }

    public static function getStatus(string $status){
        return Status::from($status)->value;
    }


    public function getViewResource(){return ViewMedicalTreatment::class;}
    public function getShowResource(){return ViewMedicalTreatment::class;}

    public function viewUsingRelation():array{
        return [];
    }

    public function showUsingRelation():array{
        return [
            'priceComponents.tariffComponent'
        ];
    }

    //EIGER SECTION
    public function medicServices(){
        return $this->belongsToManyModel(
            'MedicService',
            'MedicalServiceTreatment',
            'medical_treatment_id',
            'medic_service_id'
        );
    }
    public function serviceLabel(){return $this->belongsToModel('ServiceLabel');}
    public function medicalServiceTreatment(){return $this->hasOneModel('MedicalServiceTreatment');}
    public function medicalServiceTreatments(){return $this->hasManyModel('MedicalServiceTreatment');}
    public function priceComponent(){return $this->morphOneModel("PriceComponent", "model");}
    public function priceComponents(){return $this->morphManyModel("PriceComponent", "model");}
    //ENDEIGER SECTION
}

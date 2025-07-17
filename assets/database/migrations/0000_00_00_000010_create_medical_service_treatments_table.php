<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Hanafalah\ModuleMedicalTreatment\Models\MedicalTreatment\{
    MedicalServiceTreatment,
    MedicalTreatment
};

use Hanafalah\ModuleMedicService\Models\MedicService;
use Hanafalah\ModuleService\Models\Service;

return new class extends Migration
{
    use Hanafalah\LaravelSupport\Concerns\NowYouSeeMe;

    private $__table, $__table_medical_treatment, $__table_medic_service;

    public function __construct()
    {
        $this->__table = app(config('database.models.MedicalServiceTreatment', MedicalServiceTreatment::class));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $table_name = $this->__table->getTable();
        if (!$this->isTableExists()) {
            Schema::create($table_name, function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->json('props')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });

            Schema::table($table_name, function (Blueprint $table) {
                $medical_treatment = app(config('database.models.MedicalTreatment', MedicalTreatment::class));
                $service           = app(config('database.models.Service', Service::class));

                $table->foreignIdFor($medical_treatment::class, 'medical_treatment_id')
                    ->nullable()->after('id')
                    ->constrained($medical_treatment->getTable(), $medical_treatment->getKeyName(), 'med_service_mt_fk')
                    ->cascadeOnUpdate()->restrictOnDelete();

                $table->foreignIdFor($service::class, 'service_id')
                    ->nullable()->after('id')
                    ->constrained($service->getTable(), $service->getKeyName(), 'service_ms_fk')
                    ->cascadeOnUpdate()->restrictOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->__table->getTable());
    }
};

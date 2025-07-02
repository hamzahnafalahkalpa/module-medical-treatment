<?php

namespace Hanafalah\ModuleMedicalTreatment\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;

/**
 * @see \Hanafalah\ModuleMedicalTreatment\Schemas\MedicalTreatment
 * @method self setParamLogic(string $logic, bool $search_value = false, ?array $optionals = [])
 * @method self conditionals(mixed $conditionals)
 * @method mixed export(string $type)
 * @method bool deleteMedicalTreatment()
 * @method bool prepareDeleteMedicalTreatment(? array $attributes = null)
 * @method mixed getMedicalTreatment()
 * @method ?Model prepareShowMedicalTreatment(?Model $model = null, ?array $attributes = null)
 * @method array showMedicalTreatment(?Model $model = null)
 * @method Collection prepareViewMedicalTreatmentList()
 * @method array viewMedicalTreatmentList()
 * @method LengthAwarePaginator prepareViewMedicalTreatmentPaginate(PaginateData $paginate_dto)
 * @method array viewMedicalTreatmentPaginate(?PaginateData $paginate_dto = null)
 * @method array storeMedicalTreatment(?MedicalTreatmentData $funding_dto = null)
 */
interface MedicalServiceTreatment extends DataManagement {}

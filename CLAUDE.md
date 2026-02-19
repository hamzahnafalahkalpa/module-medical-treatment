# CLAUDE.md - Module Medical Treatment

This file provides guidance to Claude Code when working with the `hanafalah/module-medical-treatment` package.

## Module Overview

The Module Medical Treatment package provides functionality for managing medical treatments and procedures within the Wellmed healthcare system. It handles the relationship between medical treatments and services, enabling clinics to define and manage medical procedures with associated service offerings.

**Package:** `hanafalah/module-medical-treatment`
**Namespace:** `Hanafalah\ModuleMedicalTreatment`
**Type:** Laravel Package (Library)

## Dependencies

This module depends on several other Hanafalah packages:

- `hanafalah/laravel-support` - Core support utilities and base classes
- `hanafalah/module-service` - Service management functionality
- `hanafalah/module-medic-service` - Medical service definitions
- `hanafalah/module-treatment` - Base treatment functionality
- `hanafalah/module-transaction` - Transaction handling
- `hanafalah/module-examination` - Examination stuff base class (MedicalTreatment extends ExaminationStuff)

## Directory Structure

```
src/
├── Commands/
│   ├── EnvironmentCommand.php      # Base command class
│   └── InstallMakeCommand.php      # Installation command
├── Concerns/
│   └── HasMedicalTreatment.php     # Trait for treatment relationships
├── Contracts/
│   ├── Data/
│   │   ├── MedicalServiceTreatmentData.php
│   │   └── MedicalTreatmentData.php
│   ├── Schemas/
│   │   ├── MedicalServiceTreatment.php
│   │   └── MedicalTreatment.php
│   └── ModuleMedicalTreatment.php
├── Data/
│   ├── MedicalServiceTreatmentData.php  # DTO for service-treatment link
│   └── MedicalTreatmentData.php         # DTO for medical treatment
├── Facades/
│   └── ModuleMedicalTreatment.php
├── Models/
│   └── MedicalTreatment/
│       ├── MedicalServiceTreatment.php  # Pivot model linking treatment to service
│       └── MedicalTreatment.php         # Main treatment model
├── Providers/
│   └── CommandServiceProvider.php
├── Resources/
│   ├── MedicalServiceTreatment/
│   │   ├── ShowMedicalServiceTreatment.php
│   │   └── ViewMedicalServiceTreatment.php
│   └── MedicalTreatment/
│       ├── ShowMedicalTreatment.php
│       └── ViewMedicalTreatment.php
├── Schemas/
│   ├── MedicalServiceTreatment.php      # Business logic for service-treatment
│   └── MedicalTreatment.php             # Business logic for treatment
├── Supports/
│   └── BaseModuleMedicalTreatment.php
├── ModuleMedicalTreatment.php
└── ModuleMedicalTreatmentServiceProvider.php
```

## Core Concepts

### Models

#### MedicalTreatment
- Extends `ExaminationStuff` from module-examination
- Uses table: `examination_stuffs`
- Auto-generates `medical_treatment_code` using encoding system
- Traits: `HasServiceItem`, `HasTreatment`, `HasEncoding`
- Relationships:
  - `medicalServiceTreatment()` - hasOne MedicalServiceTreatment
  - `medicalServiceTreatments()` - hasMany MedicalServiceTreatment
  - `treatment()` - morphOne Treatment (inherited)

#### MedicalServiceTreatment
- Pivot model linking MedicalTreatment to Service
- Uses ULIDs as primary key
- Soft deletes enabled
- Relationships:
  - `medicalTreatment()` - belongsTo MedicalTreatment
  - `service()` - belongsTo Service

### Schemas (Business Logic Layer)

#### MedicalTreatment Schema
- Extends `ExaminationStuff` schema
- Key method: `prepareStoreMedicalTreatment(MedicalTreatmentData $dto)`
  - Creates/updates medical treatment record
  - Manages associated medical service treatments
  - Creates/updates related Treatment entity
  - Stores treatment data in props for caching
- Implements 24-hour caching with tags: `medical_treatment`, `medical_treatment-index`

#### MedicalServiceTreatment Schema
- Key method: `prepareStoreMedicalServiceTreatment(MedicalServiceTreatmentData $dto)`
  - Uses updateOrCreate pattern
  - Guards on `id` or `service_id` + `medical_treatment_id` combination

### Data Transfer Objects (DTOs)

Uses Spatie Laravel Data for DTOs:

- `MedicalTreatmentData` - Extends ExaminationStuffData
  - Contains `medical_service_treatments` array
  - Contains `treatment` TreatmentData object
  - Auto-sets `flag` to 'MedicalTreatment'

- `MedicalServiceTreatmentData`
  - Properties: `id`, `medical_treatment_id`, `service_id`, `props`
  - Auto-populates `prop_service` and `prop_service_reference` in after hook

### API Resources

**ViewMedicalTreatment** - List view with:
- id, name, treatment_code, treatment, label, status, timestamps

**ShowMedicalTreatment** - Detail view extending ViewMedicalTreatment with:
- Full treatment relationship data
- Array of medical service treatments

## Installation

```bash
php artisan module-medical-treatment:install
```

This publishes:
- Configuration file to `config/module-medical-treatment.php`
- Migration files

## Usage Examples

### Using the Facade

```php
use Hanafalah\ModuleMedicalTreatment\Facades\ModuleMedicalTreatment;

// Store a medical treatment
ModuleMedicalTreatment::useSchema('medical_treatment')
    ->storeMedicalTreatment($data);

// Get medical treatment list
ModuleMedicalTreatment::useSchema('medical_treatment')
    ->viewMedicalTreatmentPaginate();
```

### Using Schema Directly

```php
use Hanafalah\ModuleMedicalTreatment\Data\MedicalTreatmentData;

$schema = app('medical_treatment');
$dto = MedicalTreatmentData::from($request->all());
$model = $schema->prepareStoreMedicalTreatment($dto);
```

## Important Warnings

### BaseServiceProvider registers() Method

**WARNING:** The current ServiceProvider uses `->registers(['*'])` which auto-registers all schemas, models, and contracts. This pattern should be avoided in new modules as it:

1. Can cause performance issues by loading unnecessary bindings
2. Makes it difficult to track what is being registered
3. Can lead to naming conflicts across modules

**Current implementation (to be aware of):**
```php
public function register()
{
    $this->registerMainClass(ModuleMedicalTreatment::class)
        ->registerCommandService(Providers\CommandServiceProvider::class)
        ->registers(['*']);  // Avoid this pattern in new modules
}
```

**Recommended approach for new modules:**
```php
public function register()
{
    $this->registerMainClass(ModuleMedicalTreatment::class)
        ->registerCommandService(Providers\CommandServiceProvider::class)
        ->registers([
            'schemas'   => ['MedicalTreatment', 'MedicalServiceTreatment'],
            'models'    => ['MedicalTreatment', 'MedicalServiceTreatment'],
            'contracts' => ['MedicalTreatment', 'MedicalServiceTreatment'],
        ]);
}
```

### Required Data Validation

When storing a MedicalTreatment, the `medical_service_treatments` array is **required** and must contain at least one item. The schema throws an exception if empty:

```php
if (!isset($medical_treatment_dto->medical_service_treatments) || count($medical_treatment_dto->medical_service_treatments) === 0) {
    throw new \Exception('medical_service_treatment is required');
}
```

## Configuration

Configuration key: `module-medical-treatment`

Commands can be customized via config:
```php
'commands' => [
    \Hanafalah\ModuleMedicalTreatment\Commands\InstallMakeCommand::class,
]
```

## Cache Tags

This module uses the following cache tags for invalidation:
- `medical_treatment`
- `medical_treatment-index`
- `medical_service_treatment`
- `medical_service_treatment-index`

Cache duration: 24 hours (1440 minutes)

## Testing

When testing this module:
1. Ensure dependent modules are properly mocked or installed
2. Medical treatments require at least one medical service treatment
3. Treatment codes are auto-generated via encoding system
4. Models use the shared `examination_stuffs` table

## Common Pitfalls

1. **Missing medical_service_treatments** - Always provide at least one service treatment when creating/updating
2. **Encoding dependency** - The `MEDICAL_TREATMENT` encoding must exist for auto-code generation
3. **Shared table** - MedicalTreatment uses `examination_stuffs` table (polymorphic pattern)
4. **Props caching** - Treatment data is cached in `prop_treatment` property

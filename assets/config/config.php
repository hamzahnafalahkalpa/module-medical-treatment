<?php

use Hanafalah\ModuleMedicalTreatment\{
    Models,
    Contracts,
    Commands as ModuleMedicalTreatmentCommands
};
use Hanafalah\ModuleMedicService\Models\MedicService;

return [
    'app' => [
        'contracts' => [
            //ADD YOUR CONTRACTS HERE
            'medical_treatment'          => Contracts\MedicalTreatment::class,
            'medical_service_treatment'  => Contracts\MedicalServiceTreatment::class,
            'module_medical_treatment'   => Contracts\ModuleMedicalTreatment::class
        ],
    ],
    'commands' => [
        ModuleMedicalTreatmentCommands\InstallMakeCommand::class
    ],
    'libs' => [
        'model' => 'Models',
        'contract' => 'Contracts'
    ],
    'database' => [
        'models' => [
            'MedicalTreatment'           => Models\MedicalTreatment\MedicalTreatment::class,
            'MedicalServiceTreatment'    => Models\MedicalTreatment\MedicalServiceTreatment::class,
            'MedicService'               => MedicService::class,
        ]
    ]
];

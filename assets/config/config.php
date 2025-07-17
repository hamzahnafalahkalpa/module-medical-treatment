<?php

use Hanafalah\ModuleMedicalTreatment\{
    Models,
    Contracts,
    Commands as ModuleMedicalTreatmentCommands
};

return [
    'namespace' => 'Hanafalah\\ModuleMedicalTreatment',
    'app' => [
        'contracts' => [
            //ADD YOUR CONTRACTS HERE
        ]
    ],
    'libs' => [
        'model' => 'Models',
        'contract' => 'Contracts',
        'schema' => 'Schemas',
        'database' => 'Database',
        'data' => 'Data',
        'resource' => 'Resources',
        'migration' => '../assets/database/migrations'
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
        ]
    ]
];

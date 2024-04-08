<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'declare_strict_types' => true,
        'php_unit_test_case_static_method_calls' => [
            'call_type' => 'self',
        ],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;

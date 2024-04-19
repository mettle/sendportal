<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('node_modules')
    ->exclude('bower_components')
    ->exclude('public')
    ->exclude('bootstrap')
    ->exclude('resources')
    ->exclude('storage')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php');

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => [
            'imports_order' => [
                'class',
                'function',
                'const'
            ],
            'sort_algorithm' => 'alpha'
        ],
        'no_unused_imports' => true,
        'no_empty_statement' => true,
        'no_useless_return' => true,
        'not_operator_with_successor_space' => true,
        'explicit_string_variable' => true,
        'simple_to_complex_string_variable' => true,
        'array_indentation' => true,
        'blank_line_before_statement' => [
            'statements' => [
                'case',
                'default',
                'declare',
                'do',
                'goto',
                'if',
                'include',
                'include_once',
                'require',
                'require_once',
                'switch',
                'try',
            ]
        ],
    ])
    ->setFinder($finder);

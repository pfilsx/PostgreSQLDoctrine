<?php

$projectDir = __DIR__;

$finder = PhpCsFixer\Finder::create()->in(["$projectDir/src", "$projectDir/tests/Unit"]);
$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'align_multiline_comment' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => [
            'default' => 'single_space',
        ],
        'blank_line_after_namespace' => true,
        'blank_line_before_statement' => true,
        'concat_space' => ['spacing' => 'one'],
        'doctrine_annotation_braces' => false,
        'linebreak_after_opening_tag' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'phpdoc_types_order' => true,
        'declare_strict_types' => true,
        'no_superfluous_phpdoc_tags' => false,
        'yoda_style' => ['equal' => false, 'identical'=>false, 'less_and_greater' => false],
        'no_unused_imports' => true,
        'trailing_comma_in_multiline' => [
            'after_heredoc' => true,
            'elements' => ['array_destructuring', 'arrays', 'match']
        ]
    ])
    ->setUsingCache(false)
    ->setFinder($finder)
;

return $config;
<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect()) // @TODO 4.0 no need to call this manually
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS3x0' => true,
        // Constants, properties, variables
        'no_null_property_initialization' => true,
        'explicit_string_variable' => true,
        'simple_to_complex_string_variable' => true,
        // attributes
        'attribute_empty_parentheses' => false,
        'ordered_attributes' => ['sort_algorithm' => 'alpha'],
        // Arrays and lists
        'array_syntax' => ['syntax' => 'short'],
        'no_spaces_around_offset' => ['positions' => ['inside', 'outside']],
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => ['ensure_single_space' => true],
        'no_trailing_comma_in_singleline' => ['elements' => ['array', 'array_destructuring']],
        'list_syntax' => ['syntax' => 'short'],
        // Operators
        'logical_operators' => true,
        'binary_operator_spaces' => [
            'operators' => [
                '=>'  => 'align_single_space_minimal_by_scope',
                '|'   => 'no_space',
                '&'   => 'no_space',
                '='   => 'align_single_space_minimal_by_scope',
                '+='  => 'align_single_space_minimal_by_scope',
                '-='  => 'align_single_space_minimal_by_scope',
                '*='  => 'align_single_space_minimal_by_scope',
                '===' => 'align_single_space_minimal_by_scope',
            ]
        ],
        'assign_null_coalescing_to_coalesce_equal' => true,
        'no_useless_nullsafe_operator' => true,
        'not_operator_with_successor_space' => true,
        'ternary_to_null_coalescing' => true,
        'object_operator_without_whitespace' => true,
        // Language constructs
        'declare_parentheses' => true,
        'combine_consecutive_unsets' => true,
        'combine_consecutive_issets' => true,
        'compact_nullable_type_declaration' => true,
        // Classes, file structure, namespaces
        'declare_strict_types' => true,
        'no_unused_imports' => true,
        'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],
        'ordered_imports' => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'alpha'],
        'blank_line_between_import_groups' => true,
        'no_unneeded_import_alias' => true,
        'single_import_per_statement' => true,
        // Formatting
        'no_blank_lines_after_class_opening' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'heredoc_to_nowdoc' => true,
        'heredoc_indentation' => ['indentation' => 'same_as_start'],
        'lambda_not_used_import' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline', 'attribute_placement' => 'standalone'],
        'blank_line_before_statement' => ['statements' => ['if', 'switch', 'break', 'continue', 'declare', 'return', 'throw', 'try']],
        'method_chaining_indentation' => true,
        'type_declaration_spaces' => ['elements' => ['constant', 'function', 'property']],
        'return_assignment' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line' ],
        'semicolon_after_instruction' => true,
        'space_after_semicolon' => ['remove_in_empty_for_expressions' => false],
        // PHPDoc and comments
        'multiline_comment_opening_closing' => true,
        'single_line_comment_spacing' => true,
        'align_multiline_comment' => ['comment_type' => 'all_multiline'],
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_phpdoc' => true,
        'no_superfluous_phpdoc_tags' => ['remove_inheritdoc' => true, 'allow_unused_params' => false],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_param_order' => true,
        'phpdoc_return_self_reference' => ['replacements' => ['this' => '$this', '@this' => '$this', '$self' => 'self', '@self' => 'self', '$static' => 'static', '@static' => 'static']],
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_trim' => true,
        'phpdoc_types_order' => ['sort_algorithm' => 'alpha', 'null_adjustment' => 'always_last'],
        // PHPUnit
        'php_unit_attributes' => ['keep_annotations' => false],
    ])
    // 💡 by default, Fixer looks for `*.php` files excluding `./vendor/` - here, you can groom this config
    ->setFinder(
        (new Finder())
            // 💡 root folder to check
            ->in(__DIR__)
            // 💡 additional files, eg bin entry file
            // ->append([__DIR__.'/bin-entry-file'])
            // 💡 folders to exclude, if any
            // ->exclude([/* ... */])
            // 💡 path patterns to exclude, if any
            // ->notPath([/* ... */])
            // 💡 extra configs
            // ->ignoreDotFiles(false) // true by default in v3, false in v4 or future mode
            // ->ignoreVCS(true) // true by default
    );

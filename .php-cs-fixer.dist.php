<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PSR12' => true,
        'align_multiline_comment' => [
            'comment_type' => 'all_multiline',
        ],
        'array_indentation' => true,
        'assign_null_coalescing_to_coalesce_equal' => true,
        'binary_operator_spaces' => [
            'default' => 'single_space',
        ],
        'cast_spaces' => [
            'space' => 'single',
        ],
        'class_attributes_separation' => [
            'elements' => ['const' => 'one', 'method' => 'one', 'property' => 'one', 'trait_import' => 'none', 'case' => 'none'],
        ],
        'class_reference_name_casing' => true,
        'clean_namespace' => true,
        'combine_consecutive_issets' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'declare_parentheses' => true,
        'explicit_indirect_variable' => true,
        'explicit_string_variable' => true,
        'fully_qualified_strict_types' => true,
        'type_declaration_spaces' => true,
        'general_phpdoc_tag_rename' => [
            'replacements' => ['inheritDocs' => 'inheritDoc'],
        ],
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'heredoc_indentation' => [
            'indentation' => 'same_as_start',
        ],
        'include' => true,
        'integer_literal_case' => true,
        'lambda_not_used_import' => true,
        'linebreak_after_opening_tag' => true,
        'list_syntax' => [
            'syntax' => 'short',
        ],
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'method_chaining_indentation' => true,
        'multiline_comment_opening_closing' => true,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'native_function_casing' => true,
        'native_type_declaration_casing' => true,
        'no_alias_language_construct_call' => true,
        'no_alternative_syntax' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => [
            'tokens' => ['extra'],
        ],
        'no_leading_namespace_whitespace' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_null_property_initialization' => true,
        'no_short_bool_cast' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_spaces_around_offset' => true,
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => false,
            'allow_unused_params' => false,
            'remove_inheritdoc' => false,
        ],
        'no_trailing_comma_in_singleline' => [
            'elements' => ['arguments', 'array_destructuring', 'array', 'group_import'],
        ],
        'no_unneeded_control_parentheses' => [
            'statements' => [
                'break',
                'clone',
                'continue',
                'echo_print',
                'negative_instanceof',
                'others', 'return',
                'switch_case',
                'yield',
                'yield_from',
            ],
        ],
        'no_unneeded_braces' => [
            'namespaces' => false,
        ],
        'no_unneeded_import_alias' => true,
        'no_unset_cast' => true,
        'no_unused_imports' => true,
        'no_useless_concat_operator' => true,
        'no_useless_else' => true,
        'no_useless_nullsafe_operator' => true,
        'no_useless_return' => true,
        'no_whitespace_before_comma_in_array' => [
            'after_heredoc' => false,
        ],
        'normalize_index_brace' => true,
        'not_operator_with_successor_space' => true,
        'nullable_type_declaration_for_default_null_value' => [
            'use_nullable_type_declaration' => true,
        ],
        'object_operator_without_whitespace' => true,
        'operator_linebreak' => [
            'only_booleans' => false,
            'position' => 'beginning',
        ],
        'ordered_interfaces' => [
            'direction' => 'ascend',
            'order' => 'alpha',
        ],
        'php_unit_method_casing' => [
            'case' => 'snake_case',
        ],
        'phpdoc_align' => [
            'align' => 'vertical',
            'tags' => ['method', 'param', 'property', 'property-read', 'property-write', 'return', 'throws', 'type', 'var'],
        ],
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_indent' => true,
        'phpdoc_inline_tag_normalizer' => [
            'tags' => ['example', 'id', 'internal', 'inheritdoc', 'inheritdocs', 'link', 'source', 'toc', 'tutorial'],
        ],
        'phpdoc_line_span' => [
            'const' => 'single',
            'method' => 'single',
            'property' => 'single',
        ],
        'phpdoc_no_alias_tag' => [
            'replacements' => ['type' => 'var', 'link' => 'see'],
        ],
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_order' => [
            'order' => ['param', 'return', 'throws'],
        ],
        'phpdoc_order_by_value' => [
            'annotations' => [
                'author',
                'covers',
                'coversNothing',
                'dataProvider',
                'depends',
                'group',
                'internal',
                'method',
                'mixin',
                'property',
                'property-read',
                'property-write',
                'requires',
                'throws',
                'uses',
            ],
        ],
        'phpdoc_return_self_reference' => [
            'replacements' => [
                'this' => 'self',
                '@this' => 'self',
                '$self' => 'self',
                '@self' => 'self',
                '$static' => 'static',
                '@static' => 'static',
            ],
        ],
        'phpdoc_scalar' => [
            'types' => ['boolean', 'callback', 'double', 'integer', 'real', 'str'],
        ],
        'phpdoc_separation' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_summary' => true,
        'phpdoc_tag_casing' => [
            'tags' => ['inheritDoc'],
        ],
        'phpdoc_tag_type' => true,
        'phpdoc_to_comment' => [
            'ignored_tags' => ['var'],
        ],
        'phpdoc_trim' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types' => [
            'groups' => ['alias', 'meta', 'simple'],
        ],
        'phpdoc_var_annotation_correct_order' => true,
        'phpdoc_var_without_name' => true,
        'protected_to_private' => true,
        'return_assignment' => true,
        'self_static_accessor' => true,
        'semicolon_after_instruction' => true,
        'simple_to_complex_string_variable' => true,
        'simplified_if_return' => true,
        'simplified_null_return' => true,
        'single_line_comment_spacing' => true,
        'single_line_comment_style' => [
            'comment_types' => ['asterisk', 'hash'],
        ],
        'single_quote' => [
            'strings_containing_single_quote_chars' => false,
        ],
        'single_space_around_construct' => true,
        'space_after_semicolon' => [
            'remove_in_empty_for_expressions' => true,
        ],
        'standardize_increment' => true,
        'standardize_not_equals' => true,
        'switch_case_semicolon_to_colon' => true,
        'switch_continue_to_break' => true,
        'ternary_to_null_coalescing' => true,
        'trailing_comma_in_multiline' => true,
        'trim_array_spaces' => true,
        'types_spaces' => [
            'space' => 'none',
            'space_multiple_catch' => null,
        ],
        'unary_operator_spaces' => true,
        'whitespace_after_comma_in_array' => true,
        'yoda_style' => [
            'always_move_variable' => false,
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
    ])
    ->setFinder($finder)
;

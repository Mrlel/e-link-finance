<?php

return [
    'exports' => [
    'chunk_size' => 1000,
    'pre_calculate_formulas' => false,
    'csv' => [
        'delimiter' => ',',
        'enclosure' => '"',
        'line_ending' => PHP_EOL,
        'use_bom' => true,
        'include_separator_line' => false,
        'excel_compatibility' => false,
    ],
    'properties' => [
        'creator' => '',
        'lastModifiedBy' => '',
        'title' => '',
        'description' => '',
        'subject' => '',
        'keywords' => '',
        'category' => '',
        'manager' => '',
        'company' => '',
    ],
],
];

<?php

return [
    'mode'                     => '', // Leave empty for default mode
    'format'                   => 'A4', // Set the format to A4
    'default_font_size'        => '18', // Default font size
    'default_font'             => 'scheherazade_new', // Default font
    'margin_left'              => 5,
    'margin_right'             => 5,
    'margin_top'               => 5,
    'margin_bottom'            => 5,
    'margin_header'            => 5, // Header margin in millimeters
    'margin_footer'            => 5, // Footer margin in millimeters
    'orientation'              => 'P', // Portrait orientation ('P' for Portrait, 'L' for Landscape)
    'title'                    => 'Laravel mPDF', // Document title
    'subject'                  => '', // Document subject
    'author'                   => '', // Document author
    'watermark'                => '', // Watermark text
    'show_watermark'           => false, // Show watermark text
    'show_watermark_image'     => false, // Show watermark image
    'watermark_font'           => 'sans-serif', // Watermark font
    'display_mode'             => 'default', // Display mode
    'watermark_text_alpha'     => 0.1, // Watermark text transparency
    'watermark_image_path'     => '', // Path to watermark image
    'watermark_image_alpha'    => 0.2, // Watermark image transparency
    'watermark_image_size'     => 'D', // Watermark image size
    'watermark_image_position' => 'P', // Watermark image position
    'custom_font_dir'          => public_path('asset/fonts'), // Path to the custom fonts directory
    'custom_font_data'         => [
        'amiri' => [
            'R'  => 'amiri/Amiri-Regular.ttf',
            'B'  => 'amiri/Amiri-Bold.ttf',
            'I'  => 'amiri/Amiri-Italic.ttf',
            'BI' => 'amiri/Amiri-BoldItalic.ttf',
            'useKashida' => 75,
        ],
        'scheherazade_new' => [
            'R'  => 'scheherazade_new/ScheherazadeNew-Regular.ttf',
            'B'  => 'scheherazade_new/ScheherazadeNew-Bold.ttf',
            'M'  => 'scheherazade_new/ScheherazadeNew-Medium.ttf',
            'useKashida' => 75,
        ],
    ],
    'auto_language_detection'  => true, // Auto language detection
    'temp_dir'                 => storage_path('app'), // Temporary directory
    'pdfa'                     => false, // Enable PDF/A compliance
    'pdfaauto'                 => false, // Auto PDF/A compliance
    'use_active_forms'         => false, // Enable active forms
];

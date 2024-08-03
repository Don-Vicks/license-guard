<?php

return [
'show_custom_fields' => true,
    'custom_fields' => [
        'business_name' => [
            'type' => 'text',
            'label' => 'Input Business Name',
            'placeholder' => 'TeenDev',
            'required' => true,
            'rules' => 'required|string|max:255',
        ],
    ],
    'custom_fields2' => [
        'phone_number' => [
            'type' => 'number',
            'label' => 'Input Phone Number',
            'placeholder' => '0911........',
            'required' => true,
            'rules' => 'required|string|max:11',
        ],
    ]
];

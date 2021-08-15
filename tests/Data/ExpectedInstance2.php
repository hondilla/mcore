<?php declare(strict_types=1);

return [
    'class' => [
        'key' => 'class-one',
        'relations' => [
            [
                'key' => 'relation-key1',
                'classes' => [
                    'class-two',
                    'class-three',
                ],
            ], [
                'key' => 'relation-key2',
                'classes' => [
                    'class-four',
                    'class-five',
                ],
            ],
        ],
    ],
    'metadata' => [
        'key' => 'soy-la-key-de-la-instancia',
        'id' => 1,
        'publication' => [
            'status' => 'in-revision',
            'startPublishingDate' => '1989-03-08 09:00:00',
            'endPublishingDate' => '2021-07-27 14:30:00',
        ],
    ],
    'attributes' => [
        [
            'key' => 'default-attribute',
            'type' => 'string',
            'values' => [
                [
                    'id' => 1,
                    'language' => 'es',
                    'rules' => [],
                    'configuration' => [],
                    'value' => 'hola',
                ], [
                    'id' => 2,
                    'language' => 'en',
                    'rules' => [],
                    'configuration' => [],
                    'value' => 'adios',
                ],
            ],
            'attributes' => [
                [
                    'key' => 'sub-attribute',
                    'type' => 'string',
                    'values' => [
                        [
                            'id' => 3,
                            'language' => 'es',
                            'rules' => [],
                            'configuration' => [],
                            'value' => 'hola',
                        ], [
                            'id' => 4,
                            'language' => 'en',
                            'rules' => [],
                            'configuration' => [],
                            'value' => 'adios',
                        ],
                    ],
                    'attributes' => [],
                ],
            ],
        ], [
            'key' => 'global-attribute',
            'type' => 'textarea',
            'values' => [
                [
                    'id' => 5,
                    'language' => 'es',
                    'rules' => [
                        'required' => true,
                    ],
                    'configuration' => [
                        'cols' => 10,
                        'rows' => 10,
                    ],
                    'value' => 'hola',
                ], [
                    'id' => 6,
                    'language' => 'en',
                    'rules' => [
                        'required' => true,
                    ],
                    'configuration' => [
                        'cols' => 10,
                        'rows' => 10,
                    ],
                    'value' => 'adios',
                ],
            ],
            'attributes' => [],
        ], [
            'key' => 'specific-attribute',
            'type' => 'string',
            'values' => [
                [
                    'id' => 8,
                    'language' => 'es',
                    'rules' => [
                        'required' => true,
                    ],
                    'configuration' => [
                        'cols' => 10,
                        'rows' => 10,
                    ],
                    'value' => 'hola',
                ], [
                    'id' => 9,
                    'language' => 'en',
                    'rules' => [
                        'required' => false,
                    ],
                    'configuration' => [
                        'cols' => 20,
                        'rows' => 20,
                    ],
                    'value' => 'adios',
                ], [
                    'id' => 7,
                    'language' => '+',
                    'rules' => [
                        'required' => true,
                    ],
                    'configuration' => [
                        'cols' => 30,
                        'rows' => 30,
                    ],
                    'value' => 'default',
                ],
            ],
            'attributes' => [],
        ], [
            'key' => 'all-languages-attribute',
            'type' => 'lookup',
            'values' => [
                [
                    'id' => 10,
                    'language' => '*',
                    'rules' => [
                        'required' => false,
                    ],
                    'configuration' => [
                        'options' => [
                            'key1',
                            'key2',
                        ],
                    ],
                    'value' => 'key1',
                ],
            ],
            'attributes' => [],
        ],
    ],
    'relations' => [
        [
            'key' => 'relation-key1',
            'instances' => [
                1 => 'class-two',
                2 => 'class-two',
                3 => 'class-two',
                4 => 'class-three',
                5 => 'class-three',
                6 => 'class-three',
            ],
        ], [
            'key' => 'relation-key2',
            'instances' => [
                7 => 'class-four',
                8 => 'class-four',
                9 => 'class-four',
                10 => 'class-five',
                11 => 'class-five',
                12 => 'class-five',
            ],
        ],
    ],
];

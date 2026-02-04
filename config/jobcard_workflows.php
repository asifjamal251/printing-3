<?php

return [

    'workflows' => [

        // 1
        [
            'rules' => [
                'coating'       => ['Gloss Lamination','Velvet Lamination'],
                'other_coating' => 'None',
                'embossing'     => 'No',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Dye Cutting','Pasting'],
        ],

        // 2
        [
            'rules' => [
                'coating'       => ['Gloss Lamination','Matt Lamination'],
                'other_coating' => 'Spot UV',
                'embossing'     => 'No',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Spot UV','Dye Cutting','Pasting'],
        ],

        // 3
        [
            'rules' => [
                'coating'       => ['Gloss Lamination','Matt Lamination'],
                'other_coating' => 'Spot UV',
                'embossing'     => 'Yes',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Spot UV','Embossing','Dye Cutting','Pasting'],
        ],

        // 4
        [
            'rules' => [
                'coating'       => ['Gloss Lamination','Velvet Lamination'],
                'other_coating' => 'Spot UV',
                'embossing'     => 'No',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Leafing','Spot UV','Dye Cutting','Pasting'],
        ],

        // 5
        [
            'rules' => [
                'coating'       => ['Gloss Lamination','Velvet Lamination'],
                'other_coating' => 'Spot UV',
                'embossing'     => 'Yes',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Leafing','Embossing','Spot UV','Dye Cutting','Pasting'],
        ],

        // 6
        [
            'rules' => [
                'coating'       => ['Gloss Lamination','Velvet Lamination'],
                'other_coating' => 'None',
                'embossing'     => 'Yes',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Embossing','Dye Cutting','Pasting'],
        ],

        // 7
        [
            'rules' => [
                'coating'       => ['Gloss Lamination','Velvet Lamination'],
                'other_coating' => 'None',
                'embossing'     => 'No',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Leafing','Dye Cutting','Pasting'],
        ],

        // 8
        [
            'rules' => [
                'coating'       => ['Gloss Lamination','Velvet Lamination'],
                'other_coating' => 'None',
                'embossing'     => 'Yes',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Leafing','Embossing','Dye Cutting','Pasting'],
        ],

        // 9
        [
            'rules' => [
                'coating'       => 'Matt Lamination',
                'other_coating' => 'Spot UV',
                'embossing'     => 'No',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Leafing','Spot UV','Dye Cutting','Pasting'],
        ],

        // 10
        [
            'rules' => [
                'coating'       => 'Matt Lamination',
                'other_coating' => 'Spot UV',
                'embossing'     => 'Yes',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Leafing','Spot UV','Embossing','Dye Cutting','Pasting'],
        ],


        // 11
        [
            'rules' => [
                'coating'       => 'Matt Lamination',
                'other_coating' => 'None',
                'embossing'     => 'No',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Leafing','Dye Cutting','Pasting'],
        ],


        // 12
        [
            'rules' => [
                'coating'       => 'Matt Lamination',
                'other_coating' => 'None',
                'embossing'     => 'Yes',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Leafing','Embossing','Dye Cutting','Pasting'],
        ],



        // 13
        [
            'rules' => [
                'coating'       => 'Matt Lamination',
                'other_coating' => 'None',
                'embossing'     => 'Yes',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Embossing', 'Dye Cutting','Pasting'],
        ],


        // 14
        [
            'rules' => [
                'coating'       => 'Matt Lamination',
                'other_coating' => 'Spot UV',
                'embossing'     => 'Yes',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Lamination','Spot UV', 'Embossing', 'Dye Cutting','Pasting'],
        ],


        // 15
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'None',
                'embossing'     => 'No',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Coating','Dye Cutting','Pasting'],
        ],

        // 16
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'Metallic',
                'embossing'     => 'No',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Lamination','Paper Cutting','Printing','Coating','Dye Cutting','Pasting'],
        ],

        // 17
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'Spot UV',
                'embossing'     => 'No',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Coating','Spot UV','Dye Cutting','Pasting'],
        ],

        // 18
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'Spot UV + Metallic',
                'embossing'     => 'No',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Lamination','Paper Cutting','Printing','Coating','Spot UV','Dye Cutting','Pasting'],
        ],

        // 19
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'None',
                'embossing'     => 'Yes',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Coating','Embossing','Dye Cutting','Pasting'],
        ],

        // 20
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'Metallic',
                'embossing'     => 'Yes',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Lamination','Paper Cutting','Printing','Coating','Embossing','Dye Cutting','Pasting'],
        ],

        // 21
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'Spot UV + Metallic',
                'embossing'     => 'Yes',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Lamination','Paper Cutting','Printing','Coating','Spot UV','Embossing','Dye Cutting','Pasting'],
        ],

        // 22
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'None',
                'embossing'     => 'No',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Leafing','Coating','Dye Cutting','Pasting'],
        ],

        // 23
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'Metallic',
                'embossing'     => 'No',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Lamination','Paper Cutting','Printing','Leafing','Coating','Dye Cutting','Pasting'],
        ],

        // 24
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'Spot UV + Metallic',
                'embossing'     => 'No',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Lamination','Paper Cutting','Printing','Leafing','Coating','Spot UV','Dye Cutting','Pasting'],
        ],

        // 25
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'None',
                'embossing'     => 'Yes',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Leafing','Coating','Embossing','Dye Cutting','Pasting'],
        ],

        // 26
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'Metallic',
                'embossing'     => 'Yes',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Lamination','Paper Cutting','Printing','Leafing','Coating','Embossing','Dye Cutting','Pasting'],
        ],

        // 27
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'Spot UV + Metallic',
                'embossing'     => 'Yes',
                'leafing'       => 'Yes',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Lamination','Paper Cutting','Printing','Leafing','Coating','Spot UV','Embossing','Dye Cutting','Pasting'],
        ],

        // 28
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'None',
                'embossing'     => 'No',
                'leafing'       => 'No',
                'printing'      => 'Online',
            ],
            'stages' => ['Paper Cutting','Printing','Dye Cutting','Pasting'],
        ],

        // 29
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'Metallic',
                'embossing'     => 'No',
                'leafing'       => 'No',
                'printing'      => 'Online',
            ],
            'stages' => ['Paper Cutting','Lamination','Paper Cutting','Printing','Dye Cutting','Pasting'],
        ],

        // 30
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => ['Spot UV','Spot UV + Metallic'],
                'embossing'     => 'No',
                'leafing'       => 'No',
                'printing'      => 'Online',
            ],
            'stages' => ['Paper Cutting','Lamination','Paper Cutting','Printing','Spot UV','Dye Cutting','Pasting'],
        ],

        // 31
        [
            'rules' => [
                'coating'       => 'Chemical Coating',
                'other_coating' => 'None',
                'embossing'     => 'Yes',
                'leafing'       => 'No',
                'printing'      => 'Online',
            ],
            'stages' => ['Paper Cutting','Printing','Embossing','Dye Cutting','Pasting'],
        ],

        // 32
        [
            'rules' => [
                'coating'       => 'None',
                'other_coating' => 'None',
                'embossing'     => 'No',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Dye Cutting','Pasting'],
        ],

        // 33
        [
            'rules' => [
                'coating'       => 'None',
                'other_coating' => 'Spot UV',
                'embossing'     => 'No',
                'leafing'       => 'No',
                'printing'      => 'Offline',
            ],
            'stages' => ['Paper Cutting','Printing','Spot UV','Dye Cutting','Pasting'],
        ],
    ],

    'default' => ['Paper Cutting','Printing','Dye Cutting','Pasting'],
];
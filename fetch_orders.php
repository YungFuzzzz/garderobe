<?php

$orders = [
    [
        'id' => 1,
        'items' => [
            [
                'image' => 'https://via.placeholder.com/100',
                'price' => 20.00,
                'quantity' => 2
            ],
            [
                'image' => 'https://via.placeholder.com/100',
                'price' => 15.00,
                'quantity' => 1
            ]
        ]
    ],
    [
        'id' => 2,
        'items' => [
            [
                'image' => 'https://via.placeholder.com/100',
                'price' => 50.00,
                'quantity' => 1
            ]
        ]
    ]
];

header('Content-Type: application/json');
echo json_encode($orders);
?>
<?php

return [
    'label' => 'Order',
    'plural_label' => 'Orders',
    'navigation_label' => 'Orders',
    'fields' => [
        'product_id' => [
            'label' => 'Product',
        ],
        'quantity' => [
            'label' => 'Quantity',
        ],
        'total_price' => [
            'label' => 'Total Price',
        ],
        'payer_id' => [
            'label' => 'Payer',
        ],
        'receiver_id' => [
            'label' => 'Receiver',
        ],
        'payment_status' => [
            'label' => 'Payment Status',
        ],
        'order_status' => [
            'label' => 'Order Status',
        ],
        'comments' => [
            'label' => 'Comments',
        ]
    ],
    'columns' => [
        'id' => [
            'label' => 'ID',
        ],
        'created_at' => [
            'label' => 'Created at',
        ]
    ]
];

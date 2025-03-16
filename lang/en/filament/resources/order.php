<?php

return [
    'label' => 'Order',
    'plural_label' => 'Orders',
    'navigation_label' => 'Orders',
    'section_name' => [
        'order_details' => 'Order Details',
        'payment_details' => 'Payment Details',
        'additional_details' => 'Additional Details',
    ],
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
    ],
    'infolist' => [
        'section_name' => [
            'component_list' => 'Component List',
        ],
        'fields' => [
            'component_name' => [
                'label' => 'Component Name',
            ],
            'product_price' => [
                'label' => 'Product Price',
            ],
            'component_description' => [
                'label' => 'Component Description',
            ],
            'quantity' => [
                'label' => 'Quantity',
            ]
        ]
    ]
];

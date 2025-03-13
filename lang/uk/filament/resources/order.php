<?php

return [
    'label' => 'Замовлення',
    'plural_label' => 'Замовлення',
    'navigation_label' => 'Замовлення',
    'fields' => [
        'product_id' => [
            'label' => 'Продукт',
        ],
        'quantity' => [
            'label' => 'Кількість',
        ],
        'total_price' => [
            'label' => 'Загальна сума',
        ],
        'payer_id' => [
            'label' => 'Платник',
        ],
        'receiver_id' => [
            'label' => 'Отримувач',
        ],
        'payment_status' => [
            'label' => 'Статуса платежу',
        ],
        'order_status' => [
            'label' => 'Статус замовлення',
        ],
        'comments' => [
            'label' => 'Коментарі',
        ]
    ],
    'columns' => [
        'id' => [
            'label' => 'ID',
        ],
        'created_at' => [
            'label' => 'Створено',
        ]
    ]
];

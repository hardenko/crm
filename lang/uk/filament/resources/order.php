<?php

return [
    'label' => 'Замовлення',
    'plural_label' => 'Замовлення',
    'navigation_label' => 'Замовлення',
    'section_name' => [
        'order_details' => 'Деталі замовлення',
        'payment_details' => 'Деталі оплати',
        'additional_details' => 'Додаткові деталі',
    ],
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
            'label' => 'Статус платежу',
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
    ],
    'infolist' => [
        'section_name' => [
            'component_list' => 'Список компонентів',
        ],
        'fields' => [
            'component_name' => [
                'label' => 'Назва компонента',
            ],
            'product_price' => [
                'label' => 'Ціна',
            ],
            'component_description' => [
                'label' => 'Опис',
            ],
            'quantity' => [
                'label' => 'Кількість',
            ]
        ]
    ]
];

<?php

return [
    'order_stats_chart' => [
        'label' => 'Статистика замовлень',
        'filters' => [
            'week' => 'Поточний тиждень',
            'month' => 'Поточний місяць',
            'year' => 'Поточний рік',
        ],
        'datasets' => [
            'orders' => [
                'label' => 'Замовлення',
            ],
            'revenue' => [
                'label' => 'Дохід',
            ]
        ],
    ],
    'order_stats_overview' => [
        'total_revenue' => [
            'label' => 'Загальний дохід',
            'description' => 'Загальний дохід по всім замовленням',
        ],
        'total_orders' => [
            'label' => 'Всього замовлень',
            'description' => 'Загальна кількість замовлень',
        ],
        'pending_orders' => [
            'label' => 'Замовлення в очікуванні',
            'description' => 'Замовлення зараз в очікуванні',
        ],
        'processing_orders' => [
            'label' => 'Замовлення в обробці',
            'description' => 'Замовлення зараз в обробці',
        ],
        'shipped_orders' => [
            'label' => 'Відправленні замовлення',
            'description' => 'Вже надіслані замовлення',
        ],
        'delivered_orders' => [
            'label' => 'Доставленні замовлення',
            'description' => 'Вже доставленні замовлення',
        ],
        'cancelled_orders' => [
            'label' => 'Скасованні замовлення',
            'description' => 'Всі скасованні замовлення',
        ],
    ]
];

<?php

return [
    'order_stats_chart' => [
        'label' => 'Order Statistics',
        'filters' => [
            'week' => 'This week',
            'month' => 'This month',
            'year' => 'This year',
        ],
        'datasets' => [
            'orders' => [
                'label' => 'Orders',
            ],
            'revenue' => [
                'label' => 'Revenue',
            ]
        ],
    ],
    'order_stats_overview' => [
        'total_revenue' => [
            'label' => 'Total Revenue',
            'description' => 'Total revenue from all orders',
        ],
        'total_orders' => [
            'label' => 'Total Orders',
            'description' => 'Total number of orders',
        ],
        'pending_orders' => [
            'label' => 'Pending Orders',
            'description' => 'Orders that are currently pending',
        ],
        'processing_orders' => [
            'label' => 'Processing Orders',
            'description' => 'Orders that are currently processing',
        ],
        'shipped_orders' => [
            'label' => 'Shipped Orders',
            'description' => 'Orders that have been shipped',
        ],
        'delivered_orders' => [
            'label' => 'Delivered Orders',
            'description' => 'Orders that have been delivered',
        ],
        'cancelled_orders' => [
            'label' => 'Cancelled Orders',
            'description' => 'Orders that have been cancelled',
        ],
    ]
];

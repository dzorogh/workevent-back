<?php

return [
    'pages' => [
        'dashboard' => [
            'title' => 'Главная',
            'navigation_label' => 'Главная',
        ],
    ],
    
    'resources' => [
        'title' => 'Ресурсы',
        
        'forms' => [
            'actions' => [
                'create' => 'Создать',
                'edit' => 'Редактировать',
                'save' => 'Сохранить',
            ],
        ],
    ],
    
    'widgets' => [
        'stats' => [
            'events' => [
                'label' => 'Мероприятий',
                'upcoming' => 'Предстоящих мероприятий',
                'past' => 'Прошедших мероприятий',
            ],
            'companies' => [
                'label' => 'Компаний',
                'recent' => 'Новых компаний',
            ],
            'users' => [
                'label' => 'Пользователей',
            ],
        ],
        'upcoming_events' => [
            'title' => 'Ближайшие мероприятия',
            'empty' => 'Нет предстоящих мероприятий',
            'all' => 'Все мероприятия',
        ],
        'recent_companies' => [
            'title' => 'Новые компании',
            'empty' => 'Нет новых компаний',
            'all' => 'Все компании',
        ],
    ],
]; 
<?php

return [
    'events' => [
        'label' => 'Мероприятие',
        'plural_label' => 'Мероприятия',
        'sections' => [
            'basic_info' => 'Основная информация',
            'details' => 'Детали',
            'settings' => 'Настройки отображения',
        ],
        'fields' => [
            'title' => 'Название',
            'series_id' => 'Серия',
            'start_date' => 'Дата начала',
            'end_date' => 'Дата окончания',
            'format' => 'Формат',
            'city_id' => 'Город',
            'main_industry_id' => 'Основная отрасль',
            'tags' => 'Теги',
            'website' => 'Веб-сайт',
            'is_priority' => 'Приоритетное',
            'is_priority_help' => 'Приоритетные мероприятия будут выделяться в списке',
            'sort_order' => 'Порядок сортировки',
            'sort_order_help' => 'Чем больше значение, тем выше будет отображаться мероприятие',
            'description' => 'Описание',
            'cover' => 'Обложка мероприятия',
            'gallery' => 'Галерея',
        ],
        'formats' => [
            'forum' => 'Форум',
            'conference' => 'Конференция',
            'exhibition' => 'Выставка',
            'seminar' => 'Семинар',
            'webinar' => 'Вебинар',
        ],
        'actions' => [
            'edit' => [
                'title' => 'Редактирование мероприятия ":label"',
            ],
        ],
        'placeholders' => [
            'tags' => 'Выберите теги',
        ],
        'messages' => [
            'no_tags_found' => 'Нет подходящих тегов',
        ],
        'hints' => [
            'gallery' => 'Загрузите до 10 изображений. Поддерживается перетаскивание.',
        ],
    ],
    
    'event-series' => [
        'label' => 'Серия мероприятий',
        'plural_label' => 'Серии мероприятий',
        'fields' => [
            'title' => 'Название',
            'events_count' => 'Мероприятий',
        ],
        'actions' => [
            'create' => [
                'heading' => 'Создать серию мероприятий',
            ],
        ],
    ],
    
    'event-tags' => [
        'label' => 'Тег',
        'plural_label' => 'Теги',
        'fields' => [
            'title' => 'Название',
            'events_count' => 'Мероприятий',
        ],
        'actions' => [
            'create' => [
                'heading' => 'Создать тег',
            ],
        ],
    ],
    
    'speakers' => [
        'label' => 'Спикер',
        'plural_label' => 'Спикеры',
        'sections' => [
            'personal_info' => 'Личная информация',
            'topics' => 'Темы выступлений',
        ],
        'fields' => [
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'last_name' => 'Фамилия',
            'full_name' => 'ФИО',
            'email' => 'Email',
            'topics' => 'Темы',
            'topic_title' => 'Название темы',
            'topic_description' => 'Описание темы',
            'topics_count' => 'Тем',
            'events_count' => 'Мероприятий',
        ],
        'actions' => [
            'add_topic' => 'Добавить тему',
        ],
    ],
    
    'companies' => [
        'label' => 'Компания',
        'plural_label' => 'Компании',
        'sections' => [
            'event_participations' => 'Участие в мероприятиях',
        ],
        'fields' => [
            'title' => 'Название компании',
            'inn' => 'ИНН',
            'inn_help' => '10 цифр для организаций, 12 цифр для ИП',
            'events_count' => 'Мероприятий',
            'event_participations' => 'Участие в мероприятиях',
        ],
        'participation_types' => [
            'organizer' => 'Организатор',
            'sponsor' => 'Спонсор',
            'participant' => 'Участник',
            'partner' => 'Партнер',
        ],
    ],
    
    'cities' => [
        'label' => 'Город',
        'plural_label' => 'Города',
        'fields' => [
            'title' => 'Название',
            'events_count' => 'Мероприятий',
        ],
        'filters' => [
            'has_events' => 'С мероприятиями',
            'no_events' => 'Без мероприятий',
        ],
        'actions' => [
            'create' => [
                'heading' => 'Создать город',
            ],
        ],
    ],
    
    'industries' => [
        'label' => 'Отрасль',
        'plural_label' => 'Отрасли',
        'fields' => [
            'title' => 'Название',
            'main_events_count' => 'Мероприятий',
        ],
        'filters' => [
            'has_events' => 'С мероприятиями',
            'no_events' => 'Без мероприятий',
        ],
        'actions' => [
            'create' => [
                'heading' => 'Создать отрасль',
            ],
        ],
    ],
    
    'timestamps' => [
        'created_at' => 'Создано',
        'updated_at' => 'Обновлено',
    ],
]; 
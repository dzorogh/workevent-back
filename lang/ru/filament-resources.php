<?php

return [
    'events' => [
        'label' => 'Мероприятие',
        'plural_label' => 'Мероприятия',
        'sections' => [
            'basic_info' => 'Основная информация',
            'details' => 'Детали',
            'settings' => 'Настройки отображения',
            'contacts' => 'Контактная информация',
            'location' => 'Место проведения',
        ],
        'fields' => [
            'title' => 'Название',
            'series_id' => 'Серия',
            'start_date' => 'Дата начала',
            'end_date' => 'Дата окончания',
            'format' => 'Формат',
            'city_id' => 'Город',
            'industry_id' => 'Основная отрасль',
            'tags' => 'Теги',
            'website' => 'Веб-сайт',
            'phone' => 'Телефон',
            'email' => 'Электронная почта',
            'is_priority' => 'Приоритетное',
            'is_priority_help' => 'Приоритетные мероприятия будут выделяться в списке',
            'sort_order' => 'Порядок сортировки',
            'sort_order_help' => 'Чем больше значение, тем выше будет отображаться мероприятие',
            'description' => 'Описание',
            'cover' => 'Обложка мероприятия',
            'gallery' => 'Галерея',
            'venue' => 'Площадка',
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
        'relations' => [
            'tariffs' => [
                'title' => 'Стоимость участия',
                'fields' => [
                    'title' => 'Название тарифа',
                    'description' => 'Условия',
                    'price' => 'Стоимость',
                    'is_active' => 'Активен',
                    'sort_order' => 'Порядок сортировки',
                ],
            ],
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
            'contacts' => 'Контактная информация',
        ],
        'fields' => [
            'title' => 'Название компании',
            'inn' => 'ИНН',
            'inn_help' => 'Введите 10 цифр для юр. лица или 12 цифр для ИП',
            'event' => 'Мероприятие',
            'events_count' => 'Мероприятий',
            'description' => 'Описание компании',
            'website' => 'Веб-сайт',
            'phone' => 'Телефон',
            'email' => 'Электронная почта',
        ],
        'participation_types' => [
            'organizer' => 'Организатор',
            'partner' => 'Партнер',
            'sponsor' => 'Спонсор',
            'participant' => 'Участник',
        ],
        'relations' => [
            'events' => [
                'title' => 'Участие в мероприятиях',
                'fields' => [
                    'title' => 'Название',
                    'start_date' => 'Дата начала',
                    'format' => 'Формат',
                    'participation_type' => 'Тип участия',
                ],
                'actions' => [
                    'attach' => [
                        'label' => 'Привязать мероприятие',
                    ],
                ],
            ],
        ],
        'actions' => [
            'add_event_participation' => 'Добавить участие в мероприятии',
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
            'events_count' => 'Мероприятий',
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

    'venues' => [
        'label' => 'Площадка',
        'plural_label' => 'Площадки',

        'sections' => [
            'contacts' => 'Контактная информация',
        ],

        'fields' => [
            'title' => 'Название',
            'description' => 'Описание',
            'address' => 'Адрес',
            'website' => 'Веб-сайт',
            'phone' => 'Телефон',
            'email' => 'Электронная почта',
            'events_count' => 'Мероприятий',
        ],
    ],

    'presets' => [
        'label' => 'Подборка',
        'plural_label' => 'Подборки',

        'sections' => [
            'filters' => 'Фильтры',
        ],

        'fields' => [
            'title' => 'Название',
            'slug' => 'URL-метка',
            'is_active' => 'Активна',
            'sort_order' => 'Порядок сортировки',
        ],
    ],
];

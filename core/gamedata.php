<?php

$game_items = [ // Предметы
    1 => [ // Others: food, wood and more...
        1 => ['nm' => 'Мясо', 'eff' => ['hung' => 35, 'hp' => 10], 'move' => 'eat', 'chance' => 6, 'colvo' => 2, 'rare' => 1, 'img' => '/img/items/others/meat.png', 'type' => 'Пища'],
        2 => ['nm' => 'Вода', 'eff' => ['thirst' => 10, 'hp' => 2], 'move' => 'drink', 'chance' => 3, 'colvo' => 5, 'rare' => 1, 'img' => '/img/items/others/water.png', 'type' => 'Пища'],
        3 => ['nm' => 'Дрова', 'chance' => 2, 'colvo' => 5, 'rare' => 1, 'img' => '/img/items/others/wood.png', 'type' => 'Материал'],
        4 => ['nm' => 'Палка', 'chance' => 2, 'colvo' => 3, 'rare' => 1, 'img' => '/img/items/others/stick.png', 'type' => 'Материал'],
        5 => ['nm' => 'Камень', 'chance' => 4, 'colvo' => 4, 'rare' => 1, 'img' => '/img/items/others/rock.png', 'type' => 'Материал'],
        6 => ['nm' => 'Заточенный камень', 'rare' => 1, 'img' => '/img/items/others/grin_rock.png', 'type' => 'Материал'],
        7 => ['nm' => 'Веревка', 'chance' => 4, 'colvo' => 3, 'rare' => 1, 'img' => '/img/items/others/rope.png', 'type' => 'Материал'],
        8 => ['nm' => 'Крепкая веревка', 'rare' => 1, 'img' => '/img/items/others/dur_rope.png', 'type' => 'Материал'],
        9 => ['nm' => 'Стрела', 'rare' => 1, 'img' => '/img/items/others/arrow.png', 'type' => 'Боеприпас'],
        10 => ['nm' => 'Крафт 2', 'move' => 'read', 'chance' => 6, 'colvo' => 1, 'rare' => 2, 'craft_lvl' => 2, 'img' => '/img/items/others/craft_book2.png', 'type' => 'Книга'],
        11 => ['nm' => 'Крафт 3', 'move' => 'read', 'chance' => 15, 'colvo' => 1, 'rare' => 3, 'craft_lvl' => 3, 'img' => '/img/items/others/craft_book3.png', 'type' => 'Книга'],
        12 => ['nm' => 'Крафт 4', 'move' => 'read', 'chance' => 20, 'colvo' => 1, 'rare' => 4, 'craft_lvl' => 4, 'img' => '/img/items/others/craft_book4.png', 'type' => 'Книга'],
        13 => ['nm' => 'Хлеб', 'eff' => ['hung' => 10, 'hp' => 3], 'move' => 'eat', 'chance' => 3, 'colvo' => 5, 'rare' => 1, 'img' => '/img/items/others/bread.png', 'type' => 'Пища'],
        14 => ['nm' => 'Консервы', 'eff' => ['hung' => 20, 'hp' => 5], 'move' => 'eat', 'chance' => 4, 'colvo' => 3, 'rare' => 1, 'img' => '/img/items/others/canned.png', 'type' => 'Пища'],
        15 => ['nm' => 'Газировка', 'eff' => ['thirst' => 25, 'hp' => 5], 'move' => 'drink', 'chance' => 4, 'colvo' => 3, 'rare' => 1, 'img' => '/img/items/others/soda.png', 'type' => 'Пища'],
        16 => ['nm' => 'Обработанное дерево', 'rare' => 1, 'img' => '/img/items/others/treated_wood.png', 'type' => 'Материал']
    ],
    2 => [ // Helms
        1 => ['nm' => 'Шапка из дерева', 'move' => 'nadet', 'power' => 2, 'dmgabs' => 2, 'rare' => 1, 'img' => '/img/items/helms/wood-cap.png', 'class' => 'item-wood_cap', 'type' => 'Шлем'],
        2 => ['nm' => 'Деревянный шлем', 'move' => 'nadet', 'power' => 4, 'dmgabs' => 4, 'rare' => 1, 'img' => '/img/items/helms/wood-helm.png', 'class' => 'item-wood_helm', 'type' => 'Шлем']
    ],
    3 => [ // Armors
        1 => ['nm' => 'Накидка', 'move' => 'nadet', 'power' => 2, 'dmgabs' => 2, 'rare' => 1, 'img' => '/img/items/arms/nakidka.png', 'class' => 'item-nakidka', 'type' => 'Броня'],
        2 => ['nm' => 'Броня из дерева', 'move' => 'nadet', 'power' => 4, 'dmgabs' => 4, 'rare' => 1, 'img' => '/img/items/arms/wood-arm.png', 'class' => 'item-wood_arm', 'type' => 'Броня']
    ],
    4 => [ // Weaps
        1 => ['nm' => 'Нож', 'move' => 'nadet', 'power' => 5, 'dmgmin' => 2, 'dmgmax' => 4, 'rare' => 1, 'img' => '/img/items/weaps/knife.png', 'class' => 'item-knife', 'type' => 'Оружиe'],
        2 => ['nm' => 'Большой нож', 'move' => 'nadet', 'power' => 10, 'dmgmin' => 4, 'dmgmax' => 8, 'rare' => 1, 'img' => '/img/items/weaps/big_knife.png', 'class' => 'item-big_knife', 'type' => 'Оружие'],
        3 => ['nm' => 'Деревянный топор', 'move' => 'nadet', 'power' => 14, 'dmgmin' => 8, 'dmgmax' => 12, 'rare' => 1, 'img' => '/img/items/weaps/axe.png', 'class' => 'item-axe', 'type' => 'Оружие'],
        4 => ['nm' => 'Лук', 'move' => 'nadet', 'ammu' => [['i' => 9, 't' => 1]], 'power' => 18, 'dmgmin' => 12, 'dmgmax' => 14, 'rare' => 1, 'img' => '/img/items/weaps/bow.png', 'class' => 'item-bow', 'type' => 'Оружие'],
        5 => ['nm' => 'Арбалет', 'move' => 'nadet', 'ammu' => [['i' => 9, 't' => 1]], 'power' => 24, 'dmgmin' => 14, 'dmgmax' => 18, 'rare' => 2, 'img' => '/img/items/weaps/rebalet.png', 'class' => 'item-rebalet', 'type' => 'Оружие']
    ],
    5 => [ // Refuge items
        1 => ['nm' => 'Сундук', 'move' => 'place', 'rare' => 2, 'img' => '/img/items/refuge/chest.png', 'type' => 'Убежище'],
        2 => ['nm' => 'Печь', 'move' => 'place', 'rare' => 2, 'img' => '/img/items/refuge/pech.png', 'type' => 'Убежище'],
        3 => ['nm' => 'Верстак', 'move' => 'place', 'rare' => 2, 'img' => '/img/items/refuge/crafttable.png', 'type' => 'Убежище']
    ]
];

$items_pred = [ // Информация о предметах
    1 => [ // Others: food, wood and more...
        1 => 'Ну пищу едят как бы)',
        2 => 'Ну пищу едят как бы)',
        3 => 'Используется для крафта',
        4 => 'Используется для крафта',
        5 => 'Используется для крафта',
        6 => 'Используется для крафта',
        7 => 'Используется для крафта',
        8 => 'Используется для крафта',
        9 => 'Используется для оружия',
        10 => 'Повышает уровень крафта',
        11 => 'Повышает уровень крафта',
        12 => 'Повышает уровень крафта',
        13 => 'Ну пищу едят как бы)',
        14 => 'Ну пищу едят как бы)',
        15 => 'Ну пищу едят как бы)'
    ],
    5 => [
        1 => 'Используется для хранения предметов',
        2 => 'Используется для крафта',
        3 => 'Используется для крафта'
    ]
];

$game_crafts = [ // Рецепты крафтов
    ['item' => 1, 'type' => 2, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 3, 'type' => 1, 'colvo' => 20], ['item' => 4, 'type' => 1, 'colvo' => 5] ]],
    ['item' => 2, 'type' => 2, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 3, 'type' => 1, 'colvo' => 50], ['item' => 4, 'type' => 1, 'colvo' => 15] ]],

    ['item' => 1, 'type' => 3, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 3, 'type' => 1, 'colvo' => 25], ['item' => 4, 'type' => 1, 'colvo' => 10], ['item' => 7, 'type' => 1, 'colvo' => 5] ]],
    ['item' => 2, 'type' => 3, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 3, 'type' => 1, 'colvo' => 60], ['item' => 4, 'type' => 1, 'colvo' => 20], ['item' => 7, 'type' => 1, 'colvo' => 10] ]],

    ['item' => 1, 'type' => 4, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 4, 'type' => 1, 'colvo' => 10], ['item' => 7, 'type' => 1, 'colvo' => 5], ['item' => 6, 'type' => 1, 'colvo' => 1] ]],
    ['item' => 2, 'type' => 4, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 4, 'type' => 1, 'colvo' => 15], ['item' => 7, 'type' => 1, 'colvo' => 10], ['item' => 6, 'type' => 1, 'colvo' => 4] ]],
    ['item' => 3, 'type' => 4, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 4, 'type' => 1, 'colvo' => 25], ['item' => 8, 'type' => 1, 'colvo' => 5], ['item' => 6, 'type' => 1, 'colvo' => 6] ]],
    ['item' => 4, 'type' => 4, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 4, 'type' => 1, 'colvo' => 25], ['item' => 8, 'type' => 1, 'colvo' => 10] ]],
    ['item' => 5, 'type' => 4, 'craft_lvl' => 2, 'craft_items' => [ ['item' => 3, 'type' => 1, 'colvo' => 20], ['item' => 4, 'type' => 1, 'colvo' => 30], ['item' => 8, 'type' => 1, 'colvo' => 20] ]],

    ['item' => 6, 'type' => 1, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 5, 'type' => 1, 'colvo' => 3] ]],
    ['item' => 8, 'type' => 1, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 7, 'type' => 1, 'colvo' => 5] ]],
    ['item' => 9, 'type' => 1, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 4, 'type' => 1, 'colvo' => 1], ['item' => 6, 'type' => 1, 'colvo' => 1], ['item' => 7, 'type' => 1, 'colvo' => 1] ]],
];

$game_locs = [ // Локации
    1 => [
        'id' => 1,
        'nm' => 'Пустошь',
        'srch_items' => [
            ['i' => 3, 't' => 1], 
            ['i' => 4, 't' => 1],
            ['i' => 5, 't' => 1]
        ],
        'img' => [1 => '/img/locs/wasteland.jpg', 2 => '/img/locs/wasteland-rain.jpg', 3 => '/img/locs/wasteland-rain.jpg', 5 => '/img/locs/wasteland-snow.jpg']
    ],
    2 => [
        'id' => 2,
        'nm' => 'Небольшая деревня',
        'srch_items' => [
            ['i' => 13, 't' => 1], 
            ['i' => 14, 't' => 1],
            ['i' => 2, 't' => 1],
            ['i' => 15, 't' => 1],
            ['i' => 3, 't' => 1], 
            ['i' => 4, 't' => 1], 
            ['i' => 5, 't' => 1],
        ],
        'img' => [1 => '/img/locs/village.jpg', 2 => '/img/locs/village-rain.jpg', 3 => '/img/locs/village-rain.jpg', 5 => '/img/locs/village-snow.jpg']
    ],
    3 => [
        'id' => 3,
        'nm' => 'Большая деревня',
        'srch_items' => [
            ['i' => 1, 't' => 1], 
            ['i' => 2, 't' => 1], 
            ['i' => 13, 't' => 1], 
            ['i' => 14, 't' => 1], 
            ['i' => 3, 't' => 1], 
            ['i' => 4, 't' => 1], 
            ['i' => 5, 't' => 1], 
            ['i' => 7, 't' => 1], 
            ['i' => 10, 't' => 1]
        ],
        'img' => [1 => '/img/locs/big_village.jpg', 2 => '/img/locs/big_village-rain.jpg', 3 => '/img/locs/big_village-rain.jpg', 5 => '/img/locs/big_village-snow.jpg']
    ]
];

$game_weathers = [ // Погода
    1 => ['id' => 1, 'nm' => 'Ясно', 'img' => '/img/icons/weather-1.png', 'onchange' => 'Погода прояснилась'],
    2 => ['id' => 2, 'nm' => 'Дождь', 'img' => '/img/icons/weather-2.png', 'onchange' => 'Начался дождь'],
    3 => ['id' => 3, 'nm' => 'Гроза', 'img' => '/img/icons/weather-3.png', 'bonustime' => 500, 'onchange' => 'Началась гроза'],
    4 => ['id' => 4, 'nm' => 'Сильн. ветер', 'img' => '/img/icons/weather-4.png', 'bonustime' => 700, 'onchange' => 'Сильный ветерок подул'],
    5 => ['id' => 5, 'nm' => 'Снег', 'img' => '/img/icons/weather-5.png', 'onchange' => 'Снега тут не хватало']
];

$game_temps = [ // Температура
    1 => ['id' => 1, 'nm' => 'Тепло'],
    2 => ['id' => 2, 'nm' => 'Норма'],
    3 => ['id' => 3, 'nm' => 'Холодно', 'alert' => 'Риск потери здоровья'],
    4 => ['id' => 4, 'nm' => 'Оч. холодно', 'alert' => 'Риск потери здоровья']
];

$game_action_times = [ // Время выполнения действий (в секундах)
    'srchloc' => ['min' => 3600, 'max' => 7200],
    'srchlut' => ['min' => 600, 'max' => 1800]
];

$game_rares = [ // Рамка, цвет, слово предметов
    1 => ['border' => 'usual_border', 'class' => 'usual', 'word' => 'Обычное'],
    2 => ['border' => 'rare_border', 'class' => 'rare', 'word' => 'Редкое'],
    3 => ['border' => 'epic_border', 'class' => 'epic', 'word' => 'Эпическое'],
    4 => ['border' => 'legend_border', 'class' => 'legend', 'word' => 'Легендарное']
];

$game_refuges = [ // Убежища
    1 => ['nm' => 'Шалаш', 'img' => '/img/locs/refuges/hut.png', 'class' => 'hut', 'maxhp' => 50, 'dmgabs' => 1, 'tools' => 0, 'prot' => 0, 'pech' => false, 'craft_items' => [ ['item' => 3, 'type' => 1, 'colvo' => 20], ['item' => 4, 'type' => 1, 'colvo' => 20], ['item' => 7, 'type' => 1, 'colvo' => 5] ]],
    2 => ['nm' => 'Дом из дерева', 'img' => '/img/locs/refuges/house1.png', 'class' => 'house1', 'maxhp' => 70, 'dmgabs' => 4, 'tools' => 1, 'prot' => 0, 'pech' => false, 'craft_items' => [ ['item' => 3, 'type' => 1, 'colvo' => 100], ['item' => 4, 'type' => 1, 'colvo' => 50], ['item' => 7, 'type' => 1, 'colvo' => 25] ]],
    3 => ['nm' => 'Дом с дымоходом', 'img' => '/img/locs/refuges/house2.png', 'class' => 'house2', 'maxhp' => 100, 'dmgabs' => 7, 'tools' => 2, 'prot' => 1, 'pech' => true, 'craft_items' => [ ['item' => 3, 'type' => 1, 'colvo' => 250], ['item' => 4, 'type' => 1, 'colvo' => 100], ['item' => 8, 'type' => 1, 'colvo' => 20], ['item' => 5, 'type' => 1, 'colvo' => 20] ]],
];
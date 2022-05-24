<?php

/**
 * @var array $lot
 * @var array $categories
 * @var string $user_name
 * @var bool $is_auth
 * @var mysqli $link
 * @var string $lots_category
 */

require_once 'helpers.php';
require_once 'data.php';
require_once 'functions.php';
require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categories = get_categories($link);
    $lot = filter_input_array(INPUT_POST, [
        'name' => FILTER_DEFAULT, 'category_id' => FILTER_DEFAULT,
        'description' => FILTER_DEFAULT, 'initial_price' => FILTER_DEFAULT, 'bid_step' => FILTER_DEFAULT, 'finished_date' => FILTER_DEFAULT
    ], true);

    $required_fields = ['name', 'category_id', 'description', 'lot-img', 'initial_price', 'bid_step', 'finished_date'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    $rules = [
        'name' => function ($name) {
            return validateFilled($name);
        },
        'category_id' => function ($id) use ($categories) {
            return validateCategory($id, $categories);
        },
        'description' => function ($description) {
            return validateLength($description, 20, 200);
        },
        'initial_price' => function ($initial_price) {
            return validatePrice($initial_price);
        },
        'bid_step' => function ($bid_step) {
            return validateBidStep($bid_step);
        },
        'finished_date' => function ($finished_date) {
            return is_date_valid($finished_date);
        }
    ];
}
foreach ($_POST as $key => $value) {
    if (isset($rules[$key])) {
        $rule = $rules[$key];
        $errors[$key] = $rule($value);
    }
}


$page_content = include_template('add.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Добавление лота',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);
print($layout_content);

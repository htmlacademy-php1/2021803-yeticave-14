<?php
function price_format($price): string
{
    return number_format(ceil($price), 0, '', ' ') . ' ₽';
}
function remaining_time(string $closeTime, string $nowTime): array
{
    $dt_diff = strtotime($closeTime) - strtotime($nowTime);
    if (!is_date_valid($closeTime) || $dt_diff < 0) {
        return [];
    }
    $hours = floor($dt_diff / 3600);
    $minutes = floor($dt_diff % 3600 / 60);
    return [$hours, $minutes];
}

//Получение результата SQL-запроса: все строки
function get_query_sql_results(mysqli $link, $result): array
{
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Error MySQL: " . mysqli_error($link));
        return [];
    }
}

//Получение результата SQL-запроса: одна строки
function get_query_sql_result(mysqli $link, $result): array
{
    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        print("Error MySQL: " . mysqli_error($link));
        return [];
    }
}

//Получение списка новых лотов
function get_lots(mysqli $link): array
{
    $sql = "SELECT l.*,c.name as cat_name 
FROM lot l
JOIN category c ON c.id=l.category_id
WHERE l.finished_date > CURDATE()
ORDER by l.created_date DESC LIMIT 6";
    return get_query_sql_results($link, mysqli_query($link, $sql));
}

//Получение списка категорий
function get_categories(mysqli $link): array
{
    $sql = "SELECT * FROM category";
    return  get_query_sql_results($link, mysqli_query($link, $sql));
}
//Получение лота по его ID
function get_lot_id(mysqli $link, int $lot_id): array
{
    $sql = "SELECT l.*, c.name as cat_name,IFNULL(MAX(b.price),l.initial_price) as max_price FROM lot l
JOIN category c ON c.id=l.category_id
LEFT JOIN bid b ON l.id=b.lot_id
WHERE l.id= '" . $lot_id . "' 
GROUP BY l.id";
    return get_query_sql_result($link, mysqli_query($link, $sql));
}

//Получение списка лотов по категории
function get_lot_category(mysqli $link, string $lots_category): array
{
    $sql = "SELECT l.*,c.name as name_category,c.symbol_code FROM lot l
JOIN category c ON c.id=l.category_id
WHERE c.symbol_code= '$lots_category'";
    return get_query_sql_results($link, mysqli_query($link, $sql));
}

//Получение категории по коду
function get_categories_symbol_code(mysqli $link, string $lots_category): array
{
    $sql = "SELECT name FROM category
WHERE symbol_code='$lots_category'";
    return get_query_sql_result($link, mysqli_query($link, $sql));
}

//Получение значений из POST-запроса
function getPostVal($value): ?string
{
    return $_POST[$value] ?? "";
}

//Проверка заполненности 
function validateFilled($name)
{
    if (empty($_POST[$name])) {
        return "Это поле должно быть заполнено";
    }
}

//Проверка категории
function validateCategory($id, $categories)
{
    if (!in_array($id, $categories)) {
        return "Указана несуществующая категория";
    }

    return null;
}

//Проверка длины
function validateLength($name, $min, $max): string
{
    $len = strlen($_POST[$name]);

    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    }
}

function addLot(mysqli $link, array $lot, $files): bool
{
    $lot['finished_date'] = date("Y-m-d H:i:s", strtotime($lot['finished_date']));
    $lot['img_url'] = uploadFile($files);

    $sql = 'INSERT INTO lot (user_id,category_id,finished_date,description,img_url,initial_price,bid_step) VALUES (3,?,?,?,?,?,?)';
    $stmt = db_get_prepare_stmt($link, $sql, $lot);
    return mysqli_stmt_execute($stmt);
}

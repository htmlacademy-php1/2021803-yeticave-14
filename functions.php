<?php
function price_format($price)
{
    return number_format(ceil($price), 0, '', ' ') . ' ₽';
}
function remaining_time(string $closeTime, string $nowTime): array
{
    $dt_diff = strtotime($closeTime) - strtotime($nowTime);
    if (!is_date_valid($closeTime) || $dt_diff < 0) {
        return [0, 0];
    }
    $hours = floor($dt_diff / 3600);
    $minutes = floor($dt_diff % 3600 / 60);
    return [$hours, $minutes];
}
//Получение списка новых лотов
function get_lots(mysqli $link): array
{
    $sql = "SELECT l.*,c.name as cat_name 
FROM lot l
JOIN category c ON c.id=l.category_id
WHERE l.finished_date > CURDATE()
ORDER by l.created_date DESC LIMIT 6";
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Error MySQL: " . mysqli_error($link));
    }
}

//Получение списка категорий
function get_cat(mysqli $link): array
{
    $sql = "SELECT * FROM category";
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Error MySQL: " . mysqli_error($link));
    }
}

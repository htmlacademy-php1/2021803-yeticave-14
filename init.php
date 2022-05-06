<?php
$link = mysqli_connect("127.0.0.1", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");
if (!$link) {
    print("Error MySQL: " . mysqli_connect_error());
    die();
}

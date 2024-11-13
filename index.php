<?php

$mysqli = new mysqli('localhost', 'root', 'root', 'task2');
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$stmt = $mysqli->query('SELECT * FROM categories');
$categories = $stmt->fetch_all(MYSQLI_ASSOC);

$items = [];
$parents = [];

foreach ($categories as $category) {
    $categoriesId = $category['categories_id'];

    $parents[$categoriesId] = $category['parent_id'];
    $items[$categoriesId] = $categoriesId;
}

foreach ($items as $id => &$item) {
    $parentId = $parents[$id];

    if ($parentId == 0) {
        $tree[0][] = &$item;
    } else {
        if (!is_array($items[$parentId])) {
            $items[$parentId] = [];
        }

        $items[$parentId][$id] = &$item;
    }
}


echo "Структура дерева :\n";
print_r($items);
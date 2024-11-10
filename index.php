<?php

$mysqli = new mysqli('localhost', 'root', 'root', 'task2');
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$stmt = $mysqli->query('SELECT * FROM categories');
$items = $stmt->fetch_all(MYSQLI_ASSOC);

function buildTree(array $elements, $parentId = "0") {
    $branch = [];

    foreach ($elements as $element) {
        if ($element['parent_id'] === $parentId) {
            $children = buildTree($elements, $element['categories_id']);
            $branch[$element['categories_id']] = $children ? $children : $element['categories_id'];
        }
    }

    return $branch;
}

$tree = buildTree($items);

echo "Структура дерева :\n";
print_r($tree);
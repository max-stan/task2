<?php

$mysqli = new mysqli('localhost', 'root', 'root', 'task2');
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$stmt = $mysqli->query('SELECT * FROM categories');
$categories = $stmt->fetch_all(MYSQLI_ASSOC);

$items = [];
$tree = [];

foreach ($categories as $category) {
    $category['children'] = [];
    $items[$category['categories_id']] = $category;
}

foreach ($items as $id => &$item) {
    if ($item['parent_id'] == 0) {
        $tree[0][] = &$item;
    } else {
        $items[$item['parent_id']]['children'][] = &$item;
    }
}

function formatTree($node) {
    if (empty($node['children'])) {
        return $node['categories_id'];
    }

    $result = [];
    foreach ($node['children'] as $child) {
        $result[$child['categories_id']] = formatTree($child);
    }
    return $result;
}

$formattedTree = [];
foreach ($tree as $parent_id => $nodes) {
    foreach ($nodes as $node) {
        $formattedTree[$parent_id][$node['categories_id']] = formatTree($node);
    }
}

echo "Структура дерева :\n";
print_r($formattedTree[0]);
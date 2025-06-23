<?php
$directory = "outputs";
$keyword = isset($_GET['q']) ? $_GET['q'] : '';

$files = scandir($directory);

$matched = array_filter($files, function ($file) use ($keyword, $directory) {
    return (
        is_file("$directory/$file") &&
        strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'pdf' &&
        stripos($file, $keyword) !== false
    );
});

header('Content-Type: application/json');
echo json_encode(array_values($matched));

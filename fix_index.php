<?php
$file = 'C:\xampp\htdocs\Badminton-hall\infinityfree_build\htdocs\index.php';
if (!file_exists($file)) die("File not found");
$content = file_get_contents($file);
$content = str_replace("require __DIR__.'/../vendor/autoload.php';", "require __DIR__.'/../laravel_core/vendor/autoload.php';", $content);
$content = str_replace("\$app = require_once __DIR__.'/../bootstrap/app.php';", "\$app = require_once __DIR__.'/../laravel_core/bootstrap/app.php';", $content);
file_put_contents($file, $content);
echo "Fixed index.php";

<?php
$src = 'C:\xampp\htdocs\Badminton-hall\infinityfree_build\laravel_core\public';
$dest = 'C:\xampp\htdocs\Badminton-hall\infinityfree_build\htdocs';

if (!is_dir($dest)) {
    mkdir($dest, 0777, true);
}

function moveFiles($src, $dest) {
    $dir = opendir($src);
    @mkdir($dest);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . DIRECTORY_SEPARATOR . $file) ) {
                moveFiles($src . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
            }
            else {
                rename($src . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
            }
        }
    }
    closedir($dir);
}

moveFiles($src, $dest);

function rmdir_recursive($dir) {
    if (!is_dir($dir)) return;
    foreach(scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
        else unlink("$dir/$file");
    }
    rmdir($dir);
}
rmdir_recursive($src);

$indexPath = $dest . DIRECTORY_SEPARATOR . 'index.php';
if(file_exists($indexPath)){
    $content = file_get_contents($indexPath);
    $content = str_replace("require __DIR__.'/../vendor/autoload.php';", "require __DIR__.'/../laravel_core/vendor/autoload.php';", $content);
    $content = str_replace("\$app = require_once __DIR__.'/../bootstrap/app.php';", "\$app = require_once __DIR__.'/../laravel_core/bootstrap/app.php';", $content);
    file_put_contents($indexPath, $content);
    echo "Moved and fixed.";
} else {
    echo "index.php not found.";
}

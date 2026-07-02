<?php
$source = __DIR__;
$destination = __DIR__ . '/../Badminton-hall-production.zip';

$zip = new ZipArchive();
if ($zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Failed to create zip\n");
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

$exclude = ['node_modules', '.git', 'test_db.php', 'zip_project.php'];

foreach ($iterator as $file) {
    $path = $file->getPathname();
    $relativePath = substr($path, strlen($source) + 1);
    $relativePath = str_replace('\\', '/', $relativePath);
    
    $skip = false;
    foreach ($exclude as $ex) {
        if (strpos($relativePath, $ex) === 0) {
            $skip = true;
            break;
        }
    }
    
    if ($skip) continue;

    if ($file->isDir()) {
        $zip->addEmptyDir($relativePath);
    } else {
        $zip->addFile($path, $relativePath);
    }
}

$zip->close();
echo "Zip created at: " . $destination . "\n";

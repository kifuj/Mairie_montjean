<?php
$directory = new RecursiveDirectoryIterator(__DIR__);
$iterator = new RecursiveIteratorIterator($directory);

foreach ($iterator as $file) {

    if (!$file->isFile()) continue;

    if ($file->getExtension() !== 'php') continue;

    $path = str_replace(__DIR__, '', $file->getPathname());

    if (str_contains($path, '/admin/')) continue;

    $url = str_replace('\\', '/', $path);

    echo "<url>\n";
    echo "<loc>https://www.votre-domaine.fr".$url."</loc>\n";
    echo "<lastmod>".date('Y-m-d', filemtime($file))."</lastmod>\n";
    echo "</url>\n";
}
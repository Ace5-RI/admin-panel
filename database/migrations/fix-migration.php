<?php
$files = glob(__DIR__ . '/database/migrations/*.php');
foreach ($files as $file) {
    $content = file_get_contents($file);
    if (str_contains($content, 'Schema::create') && !str_contains($content, 'dropIfExists')) {
        preg_match("/Schema::create\('([^']+)'/", $content, $matches);
        if (!empty($matches[1])) {
            $table = $matches[1];
            $content = str_replace(
                "Schema::create('$table'",
                "Schema::dropIfExists('$table');\n        Schema::create('$table'",
                $content
            );
            file_put_contents($file, $content);
            echo "Fixed: " . basename($file) . "\n";
        }
    }
}
echo "Done!\n";
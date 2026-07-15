<?php
echo "<h3>PHP Desktop File Verification</h3>";

$files = [
    'beep.js',
    'barcode.css',
    'jquery-1.7.2.min.js',
    'jquery-ui-1.10.3.css',
    'barcode.init.js',
    'barcode.js',
    'locCallClass.js',
    'deweyCallClass.js',
    'gsheet.js'
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    $exists = file_exists($path);
    echo "$file: " . ($exists ? "✓ FOUND" : "✗ MISSING") . "<br>";
}
?>
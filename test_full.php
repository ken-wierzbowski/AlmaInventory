<?php
header('Content-Type: text/plain');

echo "=== CONFIGURATION DEBUG ===\n\n";

// Test 1: Does Alma.prop exist?
$alma_prop_path = __DIR__ . '/php/Alma.prop';
echo "1. Alma.prop path: $alma_prop_path\n";
echo "   Exists: " . (file_exists($alma_prop_path) ? "YES" : "NO") . "\n\n";

// Test 2: Can we parse Alma.prop?
$configpath = @parse_ini_file($alma_prop_path, false);
echo "2. Alma.prop parsed:\n";
print_r($configpath);
echo "\n";

// Test 3: Does resolved config file exist?
if ($configpath && isset($configpath['proppath'])) {
    $resolved_path = __DIR__ . '/' . $configpath['proppath'];
    echo "3. Resolved config path: $resolved_path\n";
    echo "   Exists: " . (file_exists($resolved_path) ? "YES" : "NO") . "\n";
    
    // Test 4: Can we parse local.prop?
    $sconfig = @parse_ini_file($resolved_path, false);
    echo "4. local.prop parsed:\n";
    print_r($sconfig);
    echo "\n";
    
    // Test 5: Is API key present?
    echo "5. API key present: " . (isset($sconfig['ALMA_APIKEY']) ? "YES" : "NO") . "\n";
    echo "   API key length: " . (isset($sconfig['ALMA_APIKEY']) ? strlen($sconfig['ALMA_APIKEY']) : 0) . " chars\n\n";
}

// Test 6: Try the Alma class
echo "=== ALMA CLASS TEST ===\n";
require_once __DIR__ . '/php/Alma.php';
$alma = new Alma();
echo "6. API Key from Alma::getApiKey():\n";
$key = $alma->getApiKey();
echo "   Value: " . (empty($key) ? "[EMPTY/NULL]" : substr($key, 0, 8) . "...") . "\n";
echo "   Length: " . strlen($key) . " chars\n\n";

// Test 7: Make actual API call
echo "=== LIVE API CALL TEST ===\n";
$param = ['apipath' => 'https://api-na.hosted.exlibrisgroup.com/almaws/v1/conf/libraries'];
$url = "{$param['apipath']}?apikey=" . urlencode($alma->getApiKey());
echo "7. Full URL: $url\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

echo "8. HTTP Code: $httpcode\n";
echo "9. cURL Error: " . ($error ?: "[NONE]") . "\n";
echo "10. Response length: " . strlen($response) . " bytes\n";
echo "11. Response preview: " . substr($response, 0, 200) . "\n\n";

echo "=== END DEBUG ===\n";
?>
<?php
$api = exec("curl -s http://127.0.0.1:9997/v3/paths/list");
$data = json_decode($api, true);
foreach ($data['items'] as $item) {
    $name = $item['name'];
    $readersTypes = [];
    if (!empty($item['readers'])) {
        foreach ($item['readers'] as $reader) {
            $readersTypes[] = $reader['type'];
        }
    }
    $results[] = [
        'name' => $name,
        'readers' => $readersTypes
    ];
}
foreach ($results as $item) {
    $name = $item['name'];
    $readers = $item['readers'];

  // Check if the name contains "russia24" or "1channel"
    if (strpos($name, 'russia24') !== false || strpos($name, '1channel') !== false) {
        continue; // Skip this item
    }

    echo "$name";
    echo " " . implode(', ', $readers) . "\n";

}

?>

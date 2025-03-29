<?php
$api = exec("curl -s http://tv.tg-gw.com:9997/v3/paths/list");
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
    echo "$name";
    echo " " . implode(', ', $readers) . "\n";

}

?>

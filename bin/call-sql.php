<?php



$sqlite = new \SQLite3(__DIR__ . '/../data/sqlite.db');

$sqlite->createFunction('sleep', 'usleep', 1);
$result = $sqlite->query('SELECT some_data, RANDOM(), sleep(2000000) FROM data;');

$data = [];

while ($row = $result->fetchArray())
{
    $data[] = $row['some_data'];
    $data[] = $row['RANDOM()'];
}

echo json_encode($data);
die;
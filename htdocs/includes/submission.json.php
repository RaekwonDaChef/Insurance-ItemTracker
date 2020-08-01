<?php

/*
    Insurance: Item Tracker
    Copyright (C) 2020 Michael Cabot
*/

/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

// returns actions information in json format, retrieved from sql database

header('Content-Type: application/json');

require_once("mysql.config.php");

if (isset($_GET["timestamp"])) {
    $timestamp = $_GET["timestamp"];
} else {
    print(json_encode([
        'success' => false,
        'errno'   => '7',
        'error'   => 'no identifier',
        'description'   => 'timestamp not specified',
    ], JSON_PRETTY_PRINT));
    die();
}

$timestamp = $conn->real_escape_string($timestamp);
$sql = "SELECT * FROM actions WHERE actionID = 1 AND timestamp = $timestamp";
$result = $conn->query($sql);

if ($result->num_rows < 1) { 
    print(json_encode([
        'success' => false,
        'errno'   => '1',
        'error'   => 'no results',
        'description'   => 'no submissions found!',
    ], JSON_PRETTY_PRINT));
    die();
}

$row = $result->fetch_assoc();
$items = json_decode($row['data']);
$itemCount = 0;

foreach($items as $item) {
    $itemCount++;
    if ($itemCount == 1) {
        $sql = "SELECT * FROM contents WHERE item = $item";
        $result2 = $conn->query($sql);
        $row2 = $result2->fetch_assoc();
        if ($row2['status'] == 4) { $status = "pending"; } else { $status = "finalized"; }
    }
}

print(json_encode([
    'timestamp' => $row['timestamp'],
    'status'   => $status,
    'items'   => json_decode($row['data']),
    'total'   => $itemCount,
], JSON_PRETTY_PRINT));

?>
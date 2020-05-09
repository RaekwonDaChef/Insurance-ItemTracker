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

// returns page information in json format, retrieved from sql database

header('Content-Type: application/json');

require_once("mysql.config.php");

/*
This was used to  create test submissions during development...
$items = array();
$result = $conn->query("SELECT * FROM contents WHERE status='4'");
while($row = $result->fetch_assoc()) { array_push($items,$row['item']); }
$items = implode(", ", $items);
$sql = "INSERT INTO `actions` (`timestamp`, `actionID`, `data`) VALUES (CURRENT_TIMESTAMP, '1', '$items')";
$result = $conn->query($sql);
*/

if (isset($_GET["order"])) { 
    $order = $_GET['order'];
    $order = $conn->real_escape_string($order);
} else {
    $order = "DESC";
}

if (isset($_GET["submission"])) { 
    $submission = $_GET['submission'];
    $submission = $conn->real_escape_string($submission);
    $sql = "SELECT * FROM actions WHERE timestamp = $submission";
} else {
    $sql = "SELECT * FROM actions WHERE actionID = 1 ORDER BY timestamp $order";
}

$result = $conn->query($sql);

if ($result->num_rows < 1) { die("Error: No submissions found!"); }

if (isset($_GET["submission"])) { 
    $row = $result->fetch_assoc();
    echo stripslashes(json_encode($row, JSON_PRETTY_PRINT));
} else {
    $pages = array();
    while ($row = $result->fetch_assoc()) { $pages[] = $row; }
    echo stripslashes(json_encode($pages, JSON_PRETTY_PRINT));
}

?>
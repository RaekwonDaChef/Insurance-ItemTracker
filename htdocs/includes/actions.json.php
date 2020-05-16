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

if (isset($_GET["order"])) { 
    $order = $_GET['order'];
    $order = $conn->real_escape_string($order);
} else {
    $order = "DESC";
}

$timestamp = $_GET["timestamp"];

if (isset($_GET["timestamp"])) { 
    $timestamp = $conn->real_escape_string($timestamp);
    $sql = "SELECT * FROM actions WHERE timestamp = $timestamp";
} elseif (isset($_GET["action"])) { 
    $action = $_GET['action'];
    $action = $conn->real_escape_string($action);
    $sql = "SELECT * FROM actions WHERE actionID = $action";
} else {
    $sql = "SELECT * FROM actions ORDER BY timestamp $order";
}

$result = $conn->query($sql);

if ($result->num_rows < 1) { 
    print(json_encode([
        'success' => false,
        'errno'   => '1',
        'error'   => 'no results',
        'description'   => 'no actions found!',
    ], JSON_PRETTY_PRINT));
    die();
}

if (isset($_GET["timestamp"])) { 
    $row = $result->fetch_assoc();
    echo stripslashes(json_encode($row, JSON_PRETTY_PRINT));
} else {
    $pages = array();
    while ($row = $result->fetch_assoc()) { $pages[] = $row; }
    echo stripslashes(json_encode($pages, JSON_PRETTY_PRINT));
}

?>
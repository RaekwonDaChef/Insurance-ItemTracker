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

//change all status 4 items to status 5

require_once("../includes/mysql.config.php"); // mysql connection

$timestamp = $_POST["timestamp"];

// add json array of item id's that will be affected by this action to table 'actions'
$items = array();
$result = $conn->query("SELECT * FROM actions WHERE timestamp='$timestamp'") or die("submission not found");
$row = $result->fetch_assoc();
$items = $row['data'];
$time = time();
$sql = "INSERT INTO `actions` (`timestamp`, `actionID`, `data`) VALUES ('$time', '2', '$items')";
$result = $conn->query($sql);
$items = explode(', ', $row['data']); // seperate comma seperated values into array
$sqlItems = "";
foreach($items as $item) { $sqlItems .= "item = " . $item . " OR "; } // build sql query string for selecting all items in submission
$sqlItems = substr_replace($sqlItems ,"",-3);
$result = $conn->query("SELECT * FROM contents WHERE $sqlItems LIMIT 1") or die();
$row = $result->fetch_assoc();
if ($row['status'] != 4) { die(); }
$conn->query("UPDATE contents SET status='5' WHERE $sqlItems"); // status 4 = submitted | status 5 = finalized
echo $conn->affected_rows; // return the number or records updated in the table

$conn->close(); // close connection

?>
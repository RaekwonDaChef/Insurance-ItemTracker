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

require_once("../includes/mysql.config.php");

// add json array of item id's that will be affected by this action to table 'actions'
$jsonData = $_POST["data"];
$time = time();
$sql = "INSERT INTO `actions` (`timestamp`, `actionID`, `data`) VALUES ('$time', '5', '$jsonData')";
$result = $conn->query($sql);

$obj = json_decode($_POST["data"], false);
$total_affected = 0;

foreach($obj as $value) {
    $itemNumber = $value[0];
    $itemNewStatus = $value[1];
    $conn->query("UPDATE contents SET status=$value[1] WHERE item=$value[0]") or die($conn->error);
    $total_affected += $conn->affected_rows;
}

echo $total_affected; // return the number or records updated in the table

$conn->close();

?>
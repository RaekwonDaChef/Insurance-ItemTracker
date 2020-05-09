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

// change all status 3 items to status 4
	
require_once("../includes/mysql.config.php"); // mysql connection

// add json array of item id's that will be affected by this action to table 'actions'
$items = array();
$result = $conn->query("SELECT * FROM contents WHERE status='3'");
while($row = $result->fetch_assoc()) { array_push($items,$row['item']); }
$items = implode(", ", $items);
$time = time();
$sql = "INSERT INTO `actions` (`timestamp`, `actionID`, `data`) VALUES ('$time', '1', '$items')";
$result = $conn->query($sql);

// change item statuses
$conn->query("UPDATE contents SET status='4' WHERE status='3'"); // status 3 = replaced | status 4 = submitted
echo $conn->affected_rows; // return the number or records updated in the table

mysqli_close($conn); // close connection

?>
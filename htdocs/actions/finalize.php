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

$conn->query("UPDATE contents SET status='5' WHERE status='4'"); // status 4 = submitted | status 5 = finalized
echo $conn->affected_rows; // return the number or records updated in the table

mysqli_close($conn); // close connection

?>
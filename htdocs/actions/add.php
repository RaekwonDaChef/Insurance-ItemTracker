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


require_once("../includes/mysql.config.php"); // mysql connection

$item = number_format($_POST["item"], 0, '.', '');
$description = $_POST["description"];
$quantity = number_format($_POST["quantity"], 0, '.', '');
$unit_price = number_format($_POST["unit_price"], 2, '.', '');
$collect_amount = number_format($_POST["collect_amount"], 2, '.', '');
$acv_paid = number_format($_POST["acv_paid"], 2, '.', '');
$spend_amount = $collect_amount + $acv_paid;
$spend_amount = number_format($spend_amount, 2, '.', '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["item"]) || empty($_POST["description"]) || empty($_POST["quantity"]) ||
        empty($_POST["unit_price"]) || empty($_POST["collect_amount"]) || empty($_POST["acv_paid"])) {
        die("critical error: empty fields");
    }
    if ($item < 1) { die("critical error: item number cannot be less than 1"); }
    if (strlen($description) < 2 || strlen($description) > 32) { die("critical error: description length"); }
    $description = trim($description);
    $description = stripslashes($description);
    $description = htmlspecialchars($description);
    if ($quantity < 1) { die("critical error: quantity number cannot be less than 1"); }
    if ($unit_price < 0 || $collect_amount < 0 || $acv_paid < 0) {
        die("critical error: money value cannot be less than 0");
    }
    
    // add item id in json format to table 'actions'
    $items = array();
    array_push($items,$item);
    $items = json_encode($items);
    $time = time();
    $sql = "INSERT INTO `actions` (`timestamp`, `actionID`, `data`) VALUES ('$time', '3', '$items')";
    $result = $conn->query($sql);

    // at this point it is safe to insert row into sql table
    $sql = "INSERT INTO `contents` (`item`, `status`, `description`, `quantity`, `unit_price`, `collect_amount`, `spend_amount`, `acv_paid`) VALUES ('$item', '1', '$description', '$quantity', '$unit_price', '$collect_amount', '$spend_amount', '$acv_paid')";
    $result = $conn->query($sql);
    echo $conn->affected_rows; // return the number or records added to the table
    //echo $conn->error;
}

$conn->close(); // close connection

?>
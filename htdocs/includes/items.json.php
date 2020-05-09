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

// returns item information in json format, retrieved from sql database

header('Content-Type: application/json');
require_once("mysql.config.php");

function loadStats() {
global $conn, $items;
		
$items = array(
    "notreplaced" => array("total" => 0, "money" => 0),
    "partial" => array("total" => 0, "money" => 0),
    "replaced" => array("total" => 0, "money" => 0),
    "submitted" => array("total" => 0, "money" => 0),
    "finalized" => array("total" => 0, "money" => 0)
);

$result = $conn->query("SELECT * FROM contents");

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        switch ($row['status']) {
            case 1: // status: not replaced
                $items['notreplaced']['total']++;
				$items['notreplaced']['money'] += $row['lost_depracation_amount'];
				break;
            case 2: // status: partial
				$items['partial']['total']++;
				$items['partial']['money'] += $row['lost_depracation_amount'];
				break;
            case 3: // status: replaced
				$items['replaced']['total']++;
				$items['replaced']['money'] += $row['lost_depracation_amount'];
				break;
            case 4: // status: receipt submitted
				$items['submitted']['total']++;
				$items['submitted']['money'] += $row['lost_depracation_amount'];
				break;
            case 5: // status: receipt finalized
				$items['finalized']['total']++;
				$items['finalized']['money'] += $row['lost_depracation_amount'];
				break;
            }
        }
    }
    return $items;
}

if (isset($_GET["type"]) && $_GET["type"] == "stats") { 
    $itemData = loadStats();
    echo json_encode($itemData);
} else {
    if (isset($_GET["i"])) { 
        $i = $_GET["i"];
        // security measures -----------------------------------------------------------
        $i = $conn->real_escape_string($i);
        if ((!ctype_digit($i)) || (strlen($i) > 5) || (($i) < 1)) {
            print(json_encode([
                'success' => false,
                'errno'   => '3',
                'error'   => 'invalid item number!',
            ], JSON_PRETTY_PRINT));
            die();
        }
        // -----------------------------------------------------------------------------
        $sql = "SELECT * FROM contents WHERE item = \"" . $i . "\"";
    } else {
        $sql = "SELECT * FROM contents";
    }

    $result = $conn->query($sql);  // execute sql query now that it is safe

    // more error checking ---------------------------------------------------------
    if ($result->num_rows < 1) { die("Error: Item not found!"); }
    if (isset($_GET["i"]) && $result->num_rows > 1) { 
        print(json_encode([
            'success' => false,
            'errno'   => '2',
            'error'   => 'collision',
            'description'   => 'item number collision! multiple items have the same number',
        ], JSON_PRETTY_PRINT));
        die();
    }
    // -----------------------------------------------------------------------------

    if (isset($_GET["i"])) { 
        $row = $result->fetch_assoc();
        echo json_encode($row, JSON_PRETTY_PRINT);
    } else {
        $items = array();
        while ($row = $result->fetch_assoc()) { $items[] = $row; }
        echo json_encode($items, JSON_PRETTY_PRINT);
    }
}

?>
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

require_once("../../mysql.config.php");

if (isset($_GET["page"])) { 
    $page = $_GET["page"];
    // security measures -----------------------------------------------------------
    $page = $conn->real_escape_string($page);
    if (!ctype_alpha($page)) { die("Error: Page name can only contain letters!"); }
    if (strlen($page) > 20) { die("Error: Page name too long!"); }
    // -----------------------------------------------------------------------------
    $sql = "SELECT * FROM pages WHERE page = \"" . $page . "\"";
} else {
    $sql = "SELECT * FROM pages";
}
    
$result = $conn->query($sql); // execute sql query now that it is safe

// more error checking ---------------------------------------------------------
if ($result->num_rows < 1) {
    print(json_encode([
        'success' => false,
        'errno'   => '1',
        'error'   => 'no page found!',
    ], JSON_PRETTY_PRINT));
    die();
}

if (isset($_GET["page"]) && ($result->num_rows > 1)) { 
    print(json_encode([
        'success' => false,
        'errno'   => '2',
        'error'   => 'collision',
        'description'   => 'page name collision! multiple pages have the same name',
    ], JSON_PRETTY_PRINT));
    die();
}
// -----------------------------------------------------------------------------

if (isset($_GET["page"])) { 
    $row = $result->fetch_assoc();
    echo json_encode($row, JSON_PRETTY_PRINT);
} else {
    $pages = array();
    while ($row = $result->fetch_assoc()) { $pages[] = $row; }
    echo json_encode($pages, JSON_PRETTY_PRINT);
}

?>
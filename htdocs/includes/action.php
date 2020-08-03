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

require_once("../../mysql.config.php");
require_once("../includes/action.class.php");

define("ERROR_MSG", "Uh Oh! Something went wrong.. ");
define('ACTIONS', ['submit', 'delete', 'add', 'finalize', 'status']);

$go = filter_var($_GET['go'], FILTER_SANITIZE_STRING);

if (!isset($_GET['go'])) { die(ERROR_MSG . "Action not specified!"); }
if (!in_array($go, ACTIONS, TRUE)) { die(ERROR_MSG . "Invalid action specified!"); }

$action = new Action();

try
{
    switch ($go) {
        case "submit":      echo $action->Submit();     break;
        case "delete":      echo $action->Delete();     break;
        case "add":         echo $action->Add();        break;
        case "finalize":    echo $action->Finalize();   break;
        case "status":      echo $action->Status();     break;
    }
}
catch (Exception $e)
{
    echo ERROR_MSG . $e->getMessage();
}

?>
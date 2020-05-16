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

if (isset($_GET['pending'])) {
    $pendingSubmissions = 0;
    $sql = "SELECT * FROM actions WHERE actionID = '1'";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $items = explode(', ', $row['data']); // seperate comma seperated values into array
        $sqlItems = "";
        foreach($items as $item) { $sqlItems .= "item = " . $item . " OR "; } // build sql query string for selecting all items in submission
        $sqlItems = substr_replace($sqlItems ,"",-3);
        $result = $conn->query("SELECT * FROM contents WHERE $sqlItems LIMIT 1") or die();
        $row = $result->fetch_assoc();
        if ($row['status'] == 4) { $pendingSubmissions++; }
    }
    print(json_encode([
        'pending' => $pendingSubmissions,
    ], JSON_PRETTY_PRINT));
    die();
}

setlocale(LC_MONETARY, 'en_US');

//$timestamp = $_GET['timestamp']; // unix timestamp is unique identifier of submission
//$timestamp = $conn->real_escape_string($timestamp); // secure data

//$sql = "SELECT * FROM actions WHERE timestamp = '$timestamp'"; // get submission from actions table
$sql = "SELECT * FROM actions WHERE actionID = '1' ORDER BY timestamp ASC"; // get submission from actions table (oldest first)
$result = $conn->query($sql);
if ($result->num_rows < 1) { die("Error: No submissions found!"); } // error: submission not found
$orderCount = 0;

while($row = $result->fetch_assoc()) {
    
    $timestamp = $row['timestamp'];
    $datetime = date("l jS \of F Y h:i:s A", $timestamp);
    $items = explode(', ', $row['data']); // seperate comma seperated values into array
    $sqlItems = "";
    foreach($items as $item) { $sqlItems .= "item = " . $item . " OR "; } // build sql query string for selecting all items in submission
    $sqlItems = substr_replace($sqlItems ,"",-3);
    
    $sql = "SELECT SUM(lost_depracation_amount) AS total_value FROM contents WHERE $sqlItems"; // final select submission items query
    $result2 = $conn->query($sql);
    $row2 = $result2->fetch_assoc();
    $total_value = number_format($row2['total_value'],2);
    
    $sql = "SELECT * FROM contents WHERE $sqlItems ORDER BY lost_depracation_amount DESC"; // final select submission items query
    $result2 = $conn->query($sql);

?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div data-toggle="collapse" data-target="#submission-<?php echo $timestamp; ?>" class="alert alert-secondary bg-gradient-light submission-header">
                <div class="d-flex flex-wrap flex-row">
                    <div class="flex-fill mr-auto">
                        <i class="icon-info" style="font-size: 1.3em; margin-right: 5px;"></i> <span class="submission-datetime"><?php echo $datetime; ?></span>
                    </div>
                    <div class="flex-fill">
                        <button onclick="finalizeReceiptsConfirm('<?php echo $timestamp; ?>')" id="finalizeItems-<?php echo $timestamp; ?>" type="button" class="btn btn-large btn-success bg-gradient-success float-right">
                            <span class="p-2">Finalize</span>
                            <span class="badge badge-light" style="font-size: 0.9em;">$<?php echo $total_value; ?></span>
                        </button>
                    </div>
                </div>
            </div>
            <table id="submission-<?php echo $timestamp; ?>" class="table table-striped table-responsive submission-table collapse">
                <colgroup>
                    <col id="item">
                    <col id="description">
                    <col id="qty">
                    <col id="unitprice">
                    <col id="moneytocollect">
                    <col id="needtospend">
                    <col id="status">
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">
                            Item
                        </th>
                        <th scope="col">
                            Description
                        </th>
                        <th class="priority-none" scope="col">
                            Qty.
                        </th>
                        <th class="priority-none" scope="col">
                            Price
                        </th>
                        <th scope="col">
                            $$
                        </th>
                        <th class="priority-none" scope="col">
                            Spend
                        </th>
                        <th scope="col">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>

<?php

    while($row2 = $result2->fetch_assoc()) {
        $itemid = $row2['item'];
        $unit_price = number_format($row2["unit_price"],2);
        $depracation = number_format($row2["lost_depracation_amount"],2);
        $spend_amount = number_format($row2['spend_amount'],2);
    
?>
                    <tr>
                        <td class="align-middle">
                            <?php echo $row2["item"]; ?>
                        </td>
                        <td class="align-middle"><?php echo $row2["description"]; ?></td>
                        <td class="align-middle priority-none"><?php echo $row2["quantity"]; ?></td>
                        <td class="align-middle priority-none">$<?php echo $unit_price; ?></td>
                        <td class="align-middle">$<?php echo $depracation; ?></td>
                        <td class="align-middle priority-none">$<?php echo $spend_amount; ?></td>
                
<?php
            
        switch ($row2['status']) {
            case 1: // status: not replaced
                echo "<td class=\"align-middle status-notreplaced\">Not Replaced</td>";
            break;
            case 2: // status: partial
                echo "<td class=\"status-partial align-middle\">Partial</td>";
            break;
            case 3: // status: replaced
                echo "<td class=\"align-middle status-replaced\">Replaced</td>";
            break;
            case 4: // status: receipt submitted
                echo "<td class=\"align-middle status-submitted\">Submitted</td>";
            break;
            case 5: // status: receipt finalized
                echo "<td class=\"align-middle status-finalized\">Finalized</td>";
            break;
        } // end switch
        echo "</tr>";
    } // end while
    
?>    
                
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
        
}
    
?>

<?php

mysqli_close($conn);

?>
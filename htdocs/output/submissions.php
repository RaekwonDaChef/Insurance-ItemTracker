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

/*
    NEEDS 3 THINGS:
    Header showing Finalize button and total cheque amount
*/

require_once("../includes/mysql.config.php");

//$timestamp = $_GET['timestamp']; // unix timestamp is unique identifier of submission
//$timestamp = $conn->real_escape_string($timestamp); // secure data

//$sql = "SELECT * FROM actions WHERE timestamp = '$timestamp'"; // get submission from actions table
$sql = "SELECT * FROM actions WHERE actionID = '1' ORDER BY timestamp ASC"; // get submission from actions table (oldest first)
$result = $conn->query($sql);
if ($result->num_rows < 1) { die("Error: No submissions found!"); } // error: submission not found

while($row = $result->fetch_assoc()) {
    
    $timestamp = $row['timestamp'];
    $datetime = date("l jS \of F Y h:i:s A", $timestamp);
    $items = explode(', ', $row['data']); // seperate comma seperated values into array
    $sqlItems = "";
    foreach($items as $item) { $sqlItems .= "item = " . $item . " OR "; } // build sql query string for selecting all items in submission
    $sqlItems = substr_replace($sqlItems ,"",-3);

    $sql = "SELECT * FROM contents WHERE $sqlItems ORDER BY lost_depracation_amount DESC"; // final select submission items query
    $result2 = $conn->query($sql);
        
?>

<div class="col-auto">
    <div class="alert alert-secondary submission-header">
        <i class="icon-info" style="font-size: 1.3em; margin-right: 5px;"></i> <?php echo $datetime; ?>
    </div>
    <table class="table table-striped table-responsive submission-table">
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
                <th class="priority-low" scope="col">
                    Qty.
                </th>
                <th class="priority-low" scope="col">
                    Price
                </th>
                <th scope="col">
                    $$
                </th>
                <th scope="col">
                    Spend
                </th>
                <th class="priority-low" scope="col">
                    Status
                </th>
            </tr>
        </thead>
        <tbody>

<?php

    setlocale(LC_MONETARY, 'en_US');
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
                <td class="align-middle priority-low"><?php echo $row2["quantity"]; ?></td>
                <td class="align-middle priority-low">$<?php echo $unit_price; ?></td>
                <td class="align-middle">$<?php echo $depracation; ?></td>
                <td class="align-middle">$<?php echo $spend_amount; ?></td>
                
<?php
            
        switch ($row2['status']) {
            case 1: // status: not replaced
                echo "<td class=\"align-middle status-notreplaced priority-low\">Not Replaced</td>";
            break;
            case 2: // status: partial
                echo "<td class=\"status-partial align-middle priority-low\">Partial</td>";
            break;
            case 3: // status: replaced
                echo "<td class=\"align-middle status-replaced priority-low\">Replaced</td>";
            break;
            case 4: // status: receipt submitted
                echo "<td class=\"align-middle status-submitted priority-low\">Submitted</td>";
            break;
            case 5: // status: receipt finalized
                echo "<td class=\"align-middle status-finalized priority-low\">Finalized</td>";
            break;
        } // end switch
        echo "</tr>";
    } // end while
    
?>    
                
        </tbody>
    </table>
</div>

<?php
        
}
    
?>

<?php

mysqli_close($conn);

?>
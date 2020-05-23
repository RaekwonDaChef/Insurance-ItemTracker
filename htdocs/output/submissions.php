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

if (isset($_GET['pending'])) { // if ?pending is set in URL ($_GET['pending'])...
    /*
        this is used to quickly output both the number of pending
        submissions, and number of all submissions, in json format
        whenever needed.
    */
    $pendingSubmissions = 0;
    $allSubmissions = 0;
    $sql = "SELECT * FROM actions WHERE actionID = '1'";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $allSubmissions++;
        $items = explode(', ', $row['data']); // seperate comma seperated values into array
        foreach($items as $item) {
            $sql = "SELECT * FROM contents WHERE item = $item";
            $result3 = $conn->query($sql);
            $row3 = $result3->fetch_assoc();
            if ($row3['status'] == 4) {
                $pendingSubmissions++;
            }
            break;
        }
    }
    print(json_encode([
        'pending' => $pendingSubmissions,
        'all' => $allSubmissions,
    ], JSON_PRETTY_PRINT));
    die();
}

setlocale(LC_MONETARY, 'en_US');

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
    
    $sql = "SELECT SUM(collect_amount) AS total_value FROM contents WHERE $sqlItems"; // selet the collect amount total of all items in the submission
    $result2 = $conn->query($sql);
    $row2 = $result2->fetch_assoc();
    $total_value = number_format($row2['total_value'],2);
    
    $sql = "SELECT * FROM contents WHERE $sqlItems ORDER BY collect_amount DESC"; // final select submission items query
    $result2 = $conn->query($sql);
    
    foreach($items as $item) { // loop that only iterates once
        /*
            take the status of the first item in the array of submissions
            and use it to assume the status of all items and the status
            of the entire submission.
            If one item in the submission was a different status than the
            others, then that would be a problem.
        */
        $sql = "SELECT * FROM contents WHERE item = $item";
        $result3 = $conn->query($sql);
        $row3 = $result3->fetch_assoc();
        $submissionStatus = $row3['status'];
        break;
    }

?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="alert alert-secondary bg-gradient-light submission-header">
                <div class="d-flex flex-wrap flex-row">
                    <div data-toggle="collapse" data-target="#submission-<?php echo $timestamp; ?>" class="flex-fill mr-auto">
                        <i class="icon-info" style="font-size: 1.3em; margin-right: 5px;"></i> <span class="submission-datetime"><?php echo $datetime; ?></span>
                    </div>
                    <div class="flex-fill">
                        <button <?php if ($submissionStatus == 4) { ?>onclick="finalizeReceiptsConfirm('<?php echo $timestamp; ?>')"<?php } ?>id="finalizeItems-<?php echo $timestamp; ?>" type="button" class="btn btn-large <?php if ($submissionStatus == 5) { ?>btn-dark bg-gradient-dark<?php } else { ?>btn-success bg-gradient-success<?php } ?> float-right" <?php if ($submissionStatus == 5) { ?>disabled<?php } ?>>
                            <?php if ($submissionStatus == 4) { ?>
                            <span class="p-2">Finalize</span>
                            <span class="badge badge-light" style="font-size: 0.9em;">$<?php echo $total_value; ?></span>
                            <?php } else { ?>
                            <span class="p-2">Finalized</span>
                            <?php } ?>
                        </button>
                    </div>
                </div>
            </div>
            <table id="submission-<?php echo $timestamp; ?>" class="table table-striped table-responsive submission-table collapse">
                <colgroup>
                    <col id="item">
                    <col id="description">
                    <col id="quantity">
                    <col id="unit_price">
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
                        <th class="" scope="col">
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
        $depracation = number_format($row2["collect_amount"],2);
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
                        <td class="align-middle">$<?php echo $spend_amount; ?></td>
                
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

$conn->close();

?>
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

if ($_GET['orderby'] == "description") {
    $order_by = "description";
} elseif ($_GET['orderby'] == "qty") {
    $order_by = "quantity";
} elseif ($_GET['orderby'] == "collectamount") {
    $order_by = "lost_depracation_amount";
} elseif ($_GET['orderby'] == "unitprice") {
    $order_by = "unit_price";
} elseif ($_GET['orderby'] == "spendamount") {
    $order_by = "spend_amount";
} elseif ($_GET['orderby'] == "status") {
    $order_by = "status";
} else {
    $order_by = "item";
}

if (isset($_GET['order']) && ($_GET['order'] == "desc")) {
    $order = "DESC";
} else {
    $order = "ASC";
}

if (isset($_GET["query"])) { $query = $conn->real_escape_string($_GET["query"]); } // get search query if there is one set
    
$table = $_GET['table'];
if (($_GET['table'] == "search") && (strlen($query)>0)) {
    $sql = "SELECT * FROM contents WHERE description LIKE '%$query%' AND status <> 5 ORDER BY $order_by $order LIMIT 15";
} elseif ($_GET['table'] == "notreplaced") {
    $sql = "SELECT * FROM contents WHERE status=1 ORDER BY $order_by $order";
} elseif ($_GET['table'] == "partial") {
    $sql = "SELECT * FROM contents WHERE status=2 ORDER BY $order_by $order";
} elseif ($_GET['table'] == "replaced") {
    $sql = "SELECT * FROM contents WHERE status=3 ORDER BY $order_by $order";
} elseif ($_GET['table'] == "submitted") {
    $sql = "SELECT * FROM contents WHERE status=4 ORDER BY $order_by $order";
} elseif ($_GET['table'] == "finalized") {
    $sql = "SELECT * FROM contents WHERE status=5 ORDER BY $order_by $order";
} else {
    $sql = "SELECT * FROM contents ORDER BY $order_by $order";
    $table = "all";
}
                    
$result = $conn->query($sql);
$cell_color = 0;
if ($result->num_rows > 0) {
?>

<div class="col-auto">
    <table id="table_<?php echo $table; ?>" class="table table-striped table-responsive">
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
                    <label class="check_container"> Item
                        <input class="selectAll" type="checkbox">
                        <span class="checkmark"></span>
                    </label>
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
                <th scope="col">
                    Status
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
                setlocale(LC_MONETARY, 'en_US');
                while($row = $result->fetch_assoc()) {
                    $itemid = $row['item'];
                    $sql2 = "SELECT SUM(acv_paid + lost_depracation_amount) FROM contents WHERE item ='" . $row["item"] . "'";
                    $result2 = $conn->query($sql2);
                    $row2 = $result2->fetch_assoc();
                    $unit_price = number_format($row["unit_price"],2);
                    $depracation = number_format($row["lost_depracation_amount"],2);
                    $spend_amount = number_format($row2['SUM(acv_paid + lost_depracation_amount)'],2);
            ?>
            <tr class="item_row_<?php echo $table; ?>">
                <td class="align-middle">
                    <label class="check_container"><?php echo $row["item"]; ?>
                        <input class="table_item item_checkbox_<?php echo $table; ?>" type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                </td>
                <td class="align-middle"><?php echo $row["description"]; ?></td>
                <td class="align-middle priority-low"><?php echo $row["quantity"]; ?></td>
                <td class="align-middle priority-low">$<?php echo $unit_price; ?></td>
                <td class="align-middle">$<?php echo $depracation; ?></td>
                <td class="align-middle">$<?php echo $spend_amount; ?></td>
                <?php
                    switch ($row['status']) {
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
                    } else { // end if for num rows not equal 0
                ?>    
        </tbody>
    </table>
</div>
<div class="alert alert-danger">
    <strong>Uh Oh!</strong> No results found for your search query.
</div>
<?php
        }
        mysqli_close($conn);
?>
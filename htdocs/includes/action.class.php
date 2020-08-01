<?php

require_once("mysql.config.php");

trait logger {
    private function SaveAction($actionID, $data) {
        global $conn;
        $time = time();
        if (!filter_var($actionID, FILTER_VALIDATE_INT, ['options' => ['min_range' => (int)1, 'max_range' => (int)5]])) {
            throw new Exception('Invalid action ID!'); 
        }
        if ((json_decode($data) === null) && (json_last_error() !== JSON_ERROR_NONE)) {
            throw new Exception('A JSON error occoured!');
        }
        $sql = "INSERT INTO `actions` (`timestamp`, `actionID`, `data`) VALUES ('$time', '$actionID', '$data')";
        $result = $conn->query($sql);
        if ($conn->error) { throw new Exception($conn->error); }
    }
}

class Action
{
    use logger;
    
    private $item;
    private $description;
    private $quantity;
    private $unit_price;
    private $collect_amount;
    private $acv_paid;
    private $spend_amount;
    private $json_data;
    
    public function __construct()
    {
        if (isset($_POST["item"])) { $this->item = filter_var($_POST["item"], FILTER_SANITIZE_NUMBER_INT); }
        if (isset($_POST["description"])) { $this->description = filter_var(trim($_POST["description"]), FILTER_SANITIZE_STRING); }
        if (isset($_POST["quantity"])) { $this->quantity = filter_var($_POST["quantity"], FILTER_SANITIZE_NUMBER_INT); }
        if (isset($_POST["unit_price"])) { $this->unit_price = filter_var(number_format($_POST["unit_price"], 2, '.', ''), FILTER_SANITIZE_NUMBER_FLOAT); }
        if (isset($_POST["collect_amount"])) { $this->collect_amount = number_format($_POST["collect_amount"], 2, '.', ''); }
        if (isset($_POST["collect_amount"])) { $this->collect_amount = filter_var($this->collect_amount, FILTER_SANITIZE_NUMBER_FLOAT); }
        if (isset($_POST["acv_paid"])) { $this->acv_paid = number_format($_POST["acv_paid"], 2, '.', ''); }
        if (isset($_POST["acv paid"])) { $this->acv_paid = filter_var($this->acv_paid, FILTER_SANITIZE_NUMBER_FLOAT); }
        $this->spend_amount = filter_var(number_format($this->collect_amount + $this->acv_paid, 2, '.', ''), FILTER_SANITIZE_NUMBER_FLOAT);
        if (isset($_POST["data"])) { $this->json_data = filter_var_array(json_decode($_POST["data"], false), FILTER_SANITIZE_NUMBER_INT); }
        if (isset($_POST["timestamp"])) { $this->timestamp = filter_var($_POST['timestamp'], FILTER_SANITIZE_NUMBER_INT); }
    }
    
    public function Add() {
        global $conn;
        // Error Checking -----------------------------------------------------
        if (!isset($this->item) || !isset($this->description) || !isset($this->quantity) || !isset($this->unit_price)
            || !isset($this->collect_amount) || !isset($this->acv_paid) || !isset($this->spend_amount)) {
            throw new Exception('Empty Fields!');
        }
        if ((filter_var($this->item, FILTER_VALIDATE_INT) === false) || (number_format($this->item) == 0)) { // if invalid integer
            throw new Exception('Invalid item number!');
        }
        $result = $conn->query("SELECT * FROM `contents` WHERE item = " . $this->item);
        if ($result->num_rows == 1) { throw new Exception('Item # already exists in database!'); }
        if (strlen($this->description) < 2) { throw new Exception('Description too short!'); }
        if (strlen($this->description) > 32) { throw new Exception('Description too long!'); }
        if ((filter_var($this->quantity, FILTER_VALIDATE_INT) === false) || (number_format($this->quantity) == 0)) { // if invalid integer
            throw new Exception('Invalid quantity number!');
        }
        if ((filter_var($this->unit_price, FILTER_VALIDATE_FLOAT) === false) || (number_format($this->unit_price) == 0)) { // if invalid float
            throw new Exception('Invalid unit price!');
        }
        if (filter_var($this->collect_amount, FILTER_VALIDATE_FLOAT) === false) { // if invalid float
            throw new Exception('Invalid collect amount!');
        }
        if (filter_var($this->acv_paid, FILTER_VALIDATE_FLOAT) === false) { // if invalid float
            throw new Exception('Invalid ACV amount!');
        }
        $this->description = $conn->real_escape_string($this->description);
        // --------------------------------------------------------------------
        // at this point it is safe to insert row into sql table
        $sql = "INSERT INTO `contents` (`item`, `status`, `description`, `quantity`, `unit_price`, `collect_amount`, `spend_amount`, `acv_paid`) VALUES ('" . $this->item . "', '1', '" . $this->description . "', '" . $this->quantity . "', '" . $this->unit_price . "', '" . $this->collect_amount . "', '" . $this->spend_amount . "', '" . $this->acv_paid . "')";
        $result = $conn->query($sql);
        if ($conn->affected_rows == 1) {
            $items = array(); array_push($items,$this->item);
            $items = json_encode($items);
            @$this->SaveAction(3, $items);
            return 1;
        } else {
            throw new Exception($conn->error);
        }
    }
    
    public function Delete() {
        global $conn;
        $total_affected = 0;
        $sqlItems = "";
        foreach($this->json_data[0] as $value) {
            if (filter_var($value, FILTER_VALIDATE_INT)) {
                array_push($items,$value);
                $sqlItems .= "item = " . $value . " OR ";
            } else {
                throw new Exception("Item # '" . $value . "' failed integer validation!");
            }
        }
        $sqlItems = substr_replace($sqlItems ,"",-3);
        if (sizeof($items) > 0) {
            $result = $conn->query("DELETE FROM `contents` WHERE $sqlItems");
            $total_affected = $conn->affected_rows;
        }
        if ($total_affected < 1) { throw new Exception('0 items deleted!'); }
        $items = json_encode($items);
        if ($total_affected > 0) { @$this->SaveAction(4, $items); }
        return $total_affected;
    }
    
    public function Finalize() {
        global $conn;
        if (!filter_var($this->timestamp, FILTER_VALIDATE_INT, ['options' => ['min_range' => (int)0, 'max_range' => (int)2147483647]])) {
            throw new Exception('Invalid submission timestamp!'); 
        }
        $result = $conn->query("SELECT * FROM `actions` WHERE timestamp='$this->timestamp'");
        if ($result->num_rows < 1) { throw new Exception('Submission not found!'); }
        $row = $result->fetch_assoc();
        if ((json_decode($row['data']) === null) && (json_last_error() !== JSON_ERROR_NONE)) {
            throw new Exception('A JSON error occoured while processing data for the submission!');
        }
        $items = json_decode($row['data']);
        $sqlItems = "";
        foreach($items as $item) {
            $sqlItems .= "item = " . $item . " OR ";
        } // build sql query string for selecting all items in submission
        $sqlItems = substr_replace($sqlItems ,"",-3);
        $result = $conn->query("SELECT * FROM contents WHERE $sqlItems AND status = 4");
        if ($result->num_rows != sizeof($items)) {
            $missing = sizeof($items) - $result->num_rows;
            throw new Exception($missing . ' item(s) in submission group are not status 4 (submitted)!');
        }
        $conn->query("UPDATE contents SET status='5' WHERE $sqlItems"); // status 4 = submitted | status 5 = finalized
        if ($conn->error) { throw new Exception($conn->error); }
        @$this->SaveAction(2, $row['data']);
        return $conn->affected_rows;
    }
    
    public function Submit() {
        global $conn;
        $result = $conn->query("SELECT * FROM contents WHERE status='3'");
        if ($conn->error) { throw new Exception($conn->error); }
        while($row = $result->fetch_assoc()) { array_push($items,$row['item']); }
        $items = json_encode($items);
        $conn->query("UPDATE contents SET status='4' WHERE status='3'"); // status 3 = replaced | status 4 = submitted
        if ($conn->error) { throw new Exception($conn->error); }
        @$this->SaveAction(1, $items);
        return $conn->affected_rows; // return the number or records updated in the table
    }
    
    public function Status() {
        global $conn;
        $total_affected = 0;
        $sqlItems = "";
        foreach($this->json_data as $value) {
            if ((!filter_var($value[0], FILTER_VALIDATE_INT)) || (number_format($value[0]) == 0)) {
                throw new Exception("Item number failed integer validation!");
            }
            if (!filter_var($value[1], FILTER_VALIDATE_INT, ['options' => ['min_range' => (int)1, 'max_range' => (int)5]])) {
                throw new Exception("Status number failed integer validation!"); 
            }
            $itemNumber = $value[0];
            $itemNewStatus = $value[1];
            $conn->query("UPDATE contents SET status=$value[1] WHERE item=$value[0]");
            $total_affected += $conn->affected_rows;
        }
        @$this->SaveAction(5, json_encode($this->json_data));
        echo $total_affected; // return the number or records updated in the table
    }
}

?>
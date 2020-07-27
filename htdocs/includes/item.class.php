<?php

require_once("mysql.config.php");

class Item
{
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
    }
    
    public function Add() {
        global $conn;
        // Error Checking -----------------------------------------------------
        if (empty($this->item) || empty($this->description) || empty($this->quantity) || empty($this->unit_price)
            || empty($this->collect_amount) || empty($this->acv_paid) || empty($this->spend_amount)) {
            throw new Exception('Empty Fields!');
        }
        if (filter_var($this->item, FILTER_VALIDATE_INT) === false) { // if invalid integer
            throw new Exception('Invalid item number!');
        }
        $result = $conn->query("SELECT * FROM contents WHERE item = " . $this->item);
        if ($result->num_rows > 0) { throw new Exception('Item # already exists in database!'); }
        if (strlen($this->description) < 2) { throw new Exception('Description too short!'); }
        if (strlen($this->description) > 32) { throw new Exception('Description too long!'); }
        if (filter_var($this->quantity, FILTER_VALIDATE_INT) === false) { // if invalid integer
            throw new Exception('Invalid quantity number!');
        }
        if (filter_var($this->unit_price, FILTER_VALIDATE_FLOAT) === false) { // if invalid float
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
            // add item id in json format to table 'actions'
            $items = array();
            array_push($items,$this->item);
            $items = json_encode($items);
            $time = time();
            $sql = "INSERT INTO `actions` (`timestamp`, `actionID`, `data`) VALUES ('$time', '3', '$items')";
            $result = $conn->query($sql);
            return 1;
        } else {
            throw new Exception($conn->error);
        }
    }
    
    public function Delete() {
        global $conn;
        $total_affected = 0;
        $items = array();
        foreach($this->json_data[0] as $value) {
            if (filter_var($value, FILTER_VALIDATE_INT)) { // if valid integer
                $conn->query("DELETE FROM contents WHERE item=$value");
                if ($conn->affected_rows == 1) { array_push($items,$value); }
                $total_affected += $conn->affected_rows;
            }
        }
        $items = json_encode($items);
        $time = time();
        $sql = "INSERT INTO `actions` (`timestamp`, `actionID`, `data`) VALUES ('$time', '3', '$items')";
        if ($total_affected > 0) { $result = $conn->query($sql); }
        return $total_affected;
    }
}

?>
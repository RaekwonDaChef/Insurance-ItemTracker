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
    
    public function __construct()
    {
        $this->item = number_format($_POST["item"], 0, '.', '');
        $this->description = trim($_POST["description"]);
        $this->description = stripslashes($this->description);
        $this->description = htmlspecialchars($this->description);
        $this->quantity = number_format($_POST["quantity"], 0, '.', '');
        $this->unit_price = number_format($_POST["unit_price"], 2, '.', '');
        $this->collect_amount = number_format($_POST["collect_amount"], 2, '.', '');
        $this->acv_paid = number_format($_POST["acv_paid"], 2, '.', '');
        $this->spend_amount = number_format($this->collect_amount + $this->acv_paid, 2, '.', '');
    }
    
    public function Add() {
        global $conn;
        // Error Checking -----------------------------------------------------
        // needs to check if the item id already exists in database!!!
        if (empty($this->item) || empty($this->description) || empty($this->quantity) || empty($this->unit_price)
            || empty($this->collect_amount) || empty($this->acv_paid) || empty($this->spend_amount)) {
            throw new Exception('Empty Fields!');
        }
        if ($this->item < 1) { throw new Exception('Item number cannot be less than 1!'); }
        if (strlen($this->description) < 2) { throw new Exception('Description too short!'); }
        if (strlen($this->description) > 32) { throw new Exception('Description too long!'); }
        if ($this->quantity < 1) { throw new Exception('Quantity number cannot be less than 1!'); }
        if ($this->unit_price < 0 || $this->collect_amount < 0 || $this->acv_paid < 0) {
            throw new Exception('Money value cannot be less than 0!');
        }
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
}

?>
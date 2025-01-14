<?php
require_once "../ProxyDP/DatabaseManagerProxy.php";
require_once "../../interfaces/IStoreObject.php";
require_once "../../interfaces/IReadObject.php";
require_once "../../interfaces/IUpdateObject.php";
require_once "../../interfaces/IDeleteObject.php";
abstract class DonationItem {

    protected $itemID;
    protected $itemName;
	protected $currency;
    protected $cost;

	public function __construct($item_id,$item_name,$currency,$cost){
	// $donorProxy = new DatabaseManagerProxy('donor');
    // $row = $donorProxy->run_select_query("SELECT * FROM donation_items WHERE item_id = $id"); // Should succeed
 //   if(isset($row)) {
        $this->itemID = $item_id;
		$this->itemName = $item_name;
		$this->currency = $currency;
		$this->cost = $cost;

//    }
	}
	

	public function setItemName($value) {
		$adminProxy = new DatabaseManagerProxy('admin');
		$adminProxy->runQuery("UPDATE donation_items SET item_name = '$value' WHERE id = '$this->itemID'"); // Should succeed
		$this->itemName = $value;
	}


	public function setCost($value) {
		$adminProxy = new DatabaseManagerProxy('admin');
		$adminProxy->runQuery("UPDATE donation_items SET cost = '$value' WHERE id = '$this->itemID'"); // Should succeed
		$this->cost = $value;

	}

	public function getItemID() {
		return $this->itemID;
	}

	public function getItemName() {
		return $this->itemName;
	}

	public function getCost() {
		return $this->cost;
	}

	public function getCurrency()
	{
		return $this->currency;
	}


	public function setCurrency($value)
	{
		$adminProxy = new DatabaseManagerProxy('admin');
		$adminProxy->runQuery("UPDATE donation_items SET currency = '$value' WHERE id = '$this->itemID'"); // Should succeed
		$this->currency = $value;
	}
}
?>


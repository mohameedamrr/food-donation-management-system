<?php

class BoxAdditionalRice extends BoxDecorator implements IUpdateObject{

    private $weight;

    public function __construct(Box $box, $weight) {
        $donorProxy = new DatabaseManagerProxy('donor');
        $row = $donorProxy->run_select_query("SELECT * FROM extra_box_items WHERE extra_item_name = 'Rice'")->fetch_assoc();
        if(isset($row)) {
            $this->weight = $weight;
            parent::__construct($box, $row["price_per_unit"]);
        }
    }

    public function addItem($item): array {
        $this->box->finalItemList[] = $item;
        $this->box->finalBoxSize += $this->weight;
        $this->setFinalItemList($this->box->finalItemList);
        $this->setFinalBoxSize($this->box->finalBoxSize);
        return $this->box->finalItemList;
    }

    public function calculateCost(): float {
        $this->additionalCost = $this->pricePerUnit * $this->weight;
        $this->box->totalCost += $this->additionalCost;
        $this->setTotalCost($this->box->totalCost);
        return $this->box->totalCost;
    }

    public function updateObject(array $data) {
        $allowedFields = [
            'extra_item_id',
            'extra_item_name',
            'price_per_unit'
        ];

        $filteredData = array_intersect_key($data, array_flip($allowedFields));

        if (empty($filteredData)) {
            return;
        }

        $updates = [];
        foreach ($filteredData as $prop => $value) {
            $this->{$prop} = $value;
            $value = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
            $updates[] = "`$prop` = $value";
        }
        $adminProxy = new DatabaseManagerProxy('admin');
        $adminProxy->runQuery("UPDATE extra_box_items SET " . implode(", ", $updates) . " WHERE extra_item_name = 'Rice'");
    }
}
?>


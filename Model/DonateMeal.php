<?php
require_once 'NonBillableDonate.php';

class DonateMeal extends NonBillableDonate {
    private $mealType;
    private $servings;
    private $ingredients; // Array of ingredient names

    public function __construct(
        string $itemName,
        float $weight,
        DateTime $expiryDate,
        $itemImage,
        string $mealType,
        int $servings,
        array $ingredients
    ) {
        parent::__construct( $itemName, $weight, $expiryDate, $itemImage);
        $this->mealType = $mealType;
        $this->servings = $servings;
        $this->ingredients = $ingredients;
    }


}
?>

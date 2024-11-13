<?php
require_once 'NonBillableDonate.php';

class DonateMeal extends NonBillableDonate {
    private $mealType;
    private $servings;
    private $ingredients; // array of strings

    public function prepareMeal(): void {
        // Prepare the meal
    }

    public function donateMeal(): bool {
        // Donate the prepared meal
    }
}
?>

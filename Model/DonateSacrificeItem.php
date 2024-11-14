<?php
require_once 'BillableDonate.php';
enum AnimalType: int {
    case Cow = 100;
    case lamb = 90;
    case chicken = 80;
    case camel = 120;
    case buffalo = 115;

    public function getPrice(): int {
        return $this->value;
    }
}
class DonateSacrificeItem extends BillableDonate {
    private AnimalType $animalType;

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location): void
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight): void
    {
        $this->weight = $weight;
    }

    public function getAnimalType(): AnimalType
    {
        return $this->animalType;
    }

    public function setAnimalType(AnimalType $animalType): void
    {
        $this->animalType = $animalType;
    }
    protected $weight;
    private $location;

    /**
     * @param AnimalType $animalType
     * @param $weight
     * @param $location
     */
    public function __construct(AnimalType $animalType, $weight, $location)
    {
        $this->animalType = $animalType;
        $this->weight = $weight;
        $this->location = $location;
    }

    public function calculateCost(): float {
        return $this->animalType->getPrice() * $this->weight;
    }

    public function executeDonation(): bool
    {
        // Get the instance of DatabaseManager
        $db = DatabaseManager::getInstance();

        // Construct the SQL query to insert the donation data

        $query1 =  "INSERT INTO donation_items (itemName, itemWeight, israwmaterial, isreadymeal, ismeal, ismoney, issacrifice, isbox) VALUES
			('Sacrifice', {$this->getWeight()}, 0, 0, 0, 0, 1, 0)";

        // Execute the query and check if it was successful
        if ($db->runQuery($query1) !== false) {
            $itemID = $db->getLastInsertId();
            $query2 = "
        INSERT INTO food_donation.billable_donations (id, animal_type, description, amount) 
        VALUES ($itemID, '{$this->animalType->name}', 'Null','{$this->calculateCost()}');
    ";
            if ($db->runQuery($query2) !== false) {

                return true;
            }
            else{
                echo "query 2 failed";
                return false;
            }


            return true; // Insertion was successful
        } else {
            echo "query 1 failed";
            return false; // Insertion failed
        }

    }

}
?>

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

        // Query to get the itemID for 'Sacrifice' from the donation_items table
        $query1 = "SELECT itemID FROM donation_items WHERE itemName = 'Sacrifice' LIMIT 1";

        // Execute the query
        $result = $db->runQuery($query1);

        // Check if the record exists
        if ($result && $row = $result->fetch_assoc()) {
            // Get the existing itemID
            $itemID = $row['itemID'];

            // Insert the donation data into the billable_donations table
            $query2 = "
            INSERT INTO food_donation.billable_donations (itemID, animal_type, description, amount) 
            VALUES (
                $itemID, 
                '{$this->animalType->name}', 
                'Donated {$this->getWeight()} kg of {$this->animalType->name} meat', 
                '{$this->calculateCost()}'
            );
        ";

            // Execute the second query
            if ($db->runQuery($query2) !== false) {
                return true; // Insertion was successful
            } else {
                echo "Query 2 failed";
                return false; // Insertion failed
            }
        } else {
            echo "Query 1 failed: 'Sacrifice' not found in donation_items table";
            return false; // No 'Sacrifice' record found
        }
    }


}
?>

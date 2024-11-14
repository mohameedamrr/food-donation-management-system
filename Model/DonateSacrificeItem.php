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
        $query = "
        INSERT INTO food_donation.billable_donations (user_id, donation_type, amount, animal_type, description) 
        VALUES (2, 'Sacrifice', {$this->calculateCost()}, '{$this->animalType->name}', NULL);
    ";

        // Execute the query and check if it was successful
        if ($db->runQuery($query) !== false) {
            return true; // Insertion was successful
        } else {
            // Log or handle the error

            return false; // Insertion failed
        }

    }
}
?>

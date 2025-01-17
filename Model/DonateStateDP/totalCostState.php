<?php

// require_once 'DonateState.php';

class TotalCostState extends DonateState
{
    static $totalCost = 0.0;
    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        $this->totalCost = 0.0;
        foreach ($donationItems as $item) {
            if($item instanceof Meal) {
                $mealCost = $item->getCost() * $item->getMealQuantity();
                $this->totalCost += $mealCost;
            }
            else if ($item instanceof Sacrifice) {
                $sacrificeCost = $item->getCost() * $item->getWeight();
                $this->totalCost += $sacrificeCost;
            } else if ($item instanceof Box) {
                $this->totalCost += $item->calculateCost();
            } else if ($item instanceof Money) {
                $this->totalCost += $item->getAmount();
            } else {
                $this->totalCost += $item->getCost();
            }
        }
        $donate->setNextState(new BillingState($this->totalCost));
    }
}

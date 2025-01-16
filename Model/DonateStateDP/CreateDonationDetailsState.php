<?php

// require_once 'DonateState.php';
// require_once '../DonationItemChildren/Meal.php';

class CreateDonationDetailsState extends DonateState
{
    private float $amountPaid;

    public function __construct(float $amountPaid)
    {
        $this->amountPaid = $amountPaid;
    }

    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        $data = [
            'total_cost' => $this->amountPaid,
            'description' => '',
            'donation_id' => $donate->getDonationID(),
        ];
        foreach ($donationItems as $donationItem) {
            if ($donationItem instanceof Meal) {
                $data['meal_id'] = $donationItem->getItemID();
                $data['meal_cost'] = $donationItem->getCost();
                $data['meal_quantity'] = $donationItem->getMealQuantity();
            } elseif ($donationItem instanceof RawMaterials) {
                $data['raw_materials_id'] = $donationItem->getItemID();
                $data['raw_materials_cost'] = $donationItem->getCost();
                $data['material_type'] = $donationItem->getMaterialType();
                $data['material_quantity'] = $donationItem->getQuantity();
                $data['material_weight'] = $donationItem->getRawMaterialWeight();
                $data['material_supplier'] = $donationItem->getSupplier();
            } elseif ($donationItem instanceof ClientReadyMeal) {
                $data['client_ready_meal_id'] = $donationItem->getItemID();
                $data['client_ready_meal_cost'] = $donationItem->getCost();
                $data['ready_meal_type'] = $donationItem->getReadyMealType();
                $data['ready_meal_expiration'] = $donationItem->getReadyMealExpiration();
                $data['ready_meal_quantity'] = $donationItem->getReadyMealQuantity();
                $data['ready_meal_packaging_type'] = $donationItem->getPackagingType();
            } elseif ($donationItem instanceof Money) {
                $data['money_id'] = $donationItem->getItemID();
                $data['money_amount'] = $donationItem->getAmount();
                $data['money_donation_purpose'] = $donationItem->getDonationPurpose();
            } elseif ($donationItem instanceof Sacrifice) {
                $data['sacrifice_id'] = $donationItem->getItemID();
                $data['sacrifice_cost'] = $donationItem->getCost();
            } elseif ($donationItem instanceof Box) {
                $data['box_id'] = $donationItem->getItemID();
                $data['box_cost'] = $donationItem->getCost();
                $data['final_box_size'] = $donationItem->getFinalBoxSize();
                $data['final_item_list'] = json_encode($donationItem->getFinalItemList());
            }
        }
        $donationDetails = DonationDetails::storeObject($data);
        $donate->setNextState(new SendMailState($donationDetails));
    }
}

<?php

require_once 'DonateState.php';

class SendMailState extends DonateState
{
    private DonationDetails $donationDetails;

    public function __construct(DonationDetails $donationDetails)
    {
        $this->donationDetails = $donationDetails;
    }

    protected function saveReportToFile($reportString) {
        // Generate the filename using today's date and report type
        $filename = date('Y-m-d') . '_' . strtolower('da') . '.html';

        // Wrap the report string in HTML tags
        $htmlContent = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>" ."</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #333; }
        h2 { color: #555; }
        p { color: #666; }
        hr { border: 1px solid #ddd; }
    </style>
</head>
<body>
    " . $reportString . "
</body>
</html>";

        // Save the report string to the file
        file_put_contents($filename, $htmlContent);
        echo "Report saved to $filename\n";
    }

    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        $mailFacade = new MailFacade();
        $user = BasicDonator::readObject($donate->getUserId());
        $mailFacade->setRecipient('ziadayman087@gmail.com', $user->getName());
        echo $user->getEmail();
        $emailContent = '<h1>Donation Receipt</h1>';
        $emailContent .= '<p>Hello, here are the details of your donation:</p>';

        $emailContent .= '<h2>Donation Summary</h2>';
        $emailContent .= '<p><strong>Total Cost:</strong> ' . $this->donationDetails->getTotalCost() . '</p>';
        $emailContent .= '<p><strong>Description:</strong> Donation for user: ' . $user->getName() . '</p>';
        $emailContent .= '<p><strong>Donation ID:</strong> ' . $donate->getDonationID() . '</p>';

        $emailContent .= '<h2>Donation Items</h2>';
        $emailContent .= '<ul>';

        foreach ($donationItems as $donationItem) {
            $emailContent .= '<li>';
            if ($donationItem instanceof Meal) {
                $emailContent .= '<strong>Meal:</strong> ' . $donationItem->getItemName() . '<br>';
                $emailContent .= '<strong>Cost:</strong> ' . $donationItem->getCost() . '<br>';
                $emailContent .= '<strong>Quantity:</strong> ' . $donationItem->getMealQuantity() . '<br>';
            } elseif ($donationItem instanceof RawMaterials) {
                $emailContent .= '<strong>Raw Materials:</strong> ' . $donationItem->getItemName() . '<br>';
                $emailContent .= '<strong>Cost:</strong> ' . $donationItem->getCost() . '<br>';
                $emailContent .= '<strong>Material Type:</strong> ' . $donationItem->getMaterialType() . '<br>';
                $emailContent .= '<strong>Quantity:</strong> ' . $donationItem->getQuantity() . '<br>';
                $emailContent .= '<strong>Weight:</strong> ' . $donationItem->getRawMaterialWeight() . '<br>';
                $emailContent .= '<strong>Supplier:</strong> ' . $donationItem->getSupplier() . '<br>';
            } elseif ($donationItem instanceof ClientReadyMeal) {
                $emailContent .= '<strong>Client Ready Meal:</strong> ' . $donationItem->getItemName() . '<br>';
                $emailContent .= '<strong>Cost:</strong> ' . $donationItem->getCost() . '<br>';
                $emailContent .= '<strong>Meal Type:</strong> ' . $donationItem->getReadyMealType() . '<br>';
                $emailContent .= '<strong>Expiration:</strong> ' . $donationItem->getReadyMealExpiration() . '<br>';
                $emailContent .= '<strong>Quantity:</strong> ' . $donationItem->getReadyMealQuantity() . '<br>';
                $emailContent .= '<strong>Packaging Type:</strong> ' . $donationItem->getPackagingType() . '<br>';
            } elseif ($donationItem instanceof Money) {
                $emailContent .= '<strong>Money Donation:</strong> ' . $donationItem->getItemName() . '<br>';
                $emailContent .= '<strong>Amount:</strong> ' . $donationItem->getAmount() . '<br>';
                $emailContent .= '<strong>Purpose:</strong> ' . $donationItem->getDonationPurpose() . '<br>';
            } elseif ($donationItem instanceof Sacrifice) {
                $emailContent .= '<strong>Sacrifice:</strong> ' . $donationItem->getItemName() . '<br>';
                $emailContent .= '<strong>Cost:</strong> ' . $donationItem->getCost() . '<br>';
                $emailContent .= '<strong>Animal Type:</strong> ' . $donationItem->getAnimalType() . '<br>';
                $emailContent .= '<strong>Weight:</strong> ' . $donationItem->getWeight() . '<br>';
            } elseif ($donationItem instanceof Box) {
                $emailContent .= '<strong>Box:</strong> ' . $donationItem->getItemName() . '<br>';
                $emailContent .= '<strong>Cost:</strong> ' . $donationItem->getCost() . '<br>';
                $emailContent .= '<strong>Box Size:</strong> ' . $donationItem->getFinalBoxSize() . '<br>';
                $emailContent .= '<strong>Items in Box:</strong> ' . implode(', ', $donationItem->getFinalItemList()) . '<br>';
            }
            $emailContent .= '</li>';
        }

        $emailContent .= '</ul>';

        $emailContent .= '<p>Thank you for your generous donation!</p>';

        $mailFacade->setContent('Donation Receipt', '<p1>'.$emailContent.'</p1>', true);

        $mailStatus = $mailFacade->send();

        $this->saveReportToFile($emailContent);

        if (!$mailStatus) {
            $donate->setNextState(new DonateFailedState());
        } else {
            $donate->setNextState(new DonateCompletedState());
        }

    }
}

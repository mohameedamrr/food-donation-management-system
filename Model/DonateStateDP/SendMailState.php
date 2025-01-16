<?php

require_once 'DonateState.php';

class sendMailState extends DonateState
{
    private DonationDetails $donationDetails;

    public function __construct(DonationDetails $donationDetails)
    {
        $this->donationDetails = $donationDetails;
    }

    /**
     * 5 - Send mail (confirmation)
     */
    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        // Send mail to user with donation summary
        $mailFacade = new MailFacade();
        $mailFacade->setFrom("donations@example.org", "Donation Service")
                   ->setRecipient("user@example.com", "User Name")  // you'd pull from $donate->getUserId() or user table
                   ->setContent(
                       "Thank you for your donation!",
                       "We appreciate your donation of " . $this->donationDetails->totalCost . " units.",
                       true
                   );
        $mailSent = $mailFacade->send();

        if (!$mailSent) {
            // If sending fails, we might either ignore or mark as fail
            $donate->setNextState(new failedState("Could not send email to user."));
            return;
        }

        // If everything is good, we might consider the donation flow complete
        // or continue to another finalization step if you desire.
        // For simplicity, let's just say we do nothing more. 
        // The donation is done successfully.
    }
}

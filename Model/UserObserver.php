<?php
// classes/UserObserver.php
require_once __DIR__ . '/../interfaces/Observer.php';
require_once 'UserEntity.php';

class UserObserver implements IObserver {
    private $notifications; // List of notifications

    public function __construct() {
        $this->notifications = array();
    }

     public function update($subject) {
         // Add notification based on subject's state
         $status = $subject->getStatus();
         $this->notifications[] = "Donation status changed to: $status";
     }

    public function notifyUser(UserEntity $user, $message) {
        // Send notification to the user
        // For simplicity, we'll just echo the message
        // echo "Notification to {$user->name}: $message\n";
    }

    // Additional methods as needed
}
?>

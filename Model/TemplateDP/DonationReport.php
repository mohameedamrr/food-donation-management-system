<?php
require_once '..\ProxyDP\DatabaseManagerProxy.php';

class DonationReport extends ReportTemplate {
    private $donations;

    public function __construct() {
        $this->donations = []; // Initialize as an empty array
    }

    protected function getReportType() {
        return "Donation Report";
    }

    protected function calculateTotalRecords() {
        return count($this->donations);
    }

    protected function prepareReportData() {
        // Create a DatabaseManagerProxy instance with the 'admin' role
        $adminProxy = new DatabaseManagerProxy('admin');

        // Fetch donations and donation details from the database
        $donationsQuery = "
            SELECT d.donation_id, d.donation_date, u.name AS doner, 
                   dh.total_cost, dh.description, dh.meal_id, dh.meal_cost, dh.meal_quantity,
                   dh.raw_materials_id, dh.raw_materials_cost, dh.material_type, dh.material_quantity,
                   dh.material_weight, dh.material_supplier, dh.client_ready_meal_id, dh.client_ready_meal_cost,
                   dh.ready_meal_type, dh.ready_meal_expiration, dh.ready_meal_quantity, dh.ready_meal_packaging_type,
                   dh.money_id, dh.money_amount, dh.money_donation_purpose, dh.sacrifice_id, dh.sacrifice_cost,
                   dh.box_id, dh.box_cost, dh.final_box_size, dh.final_item_list
            FROM food_donation.donations d
            JOIN food_donation.users u ON d.user_id = u.id
            LEFT JOIN food_donation.donation_history dh ON d.donation_id = dh.donation_id
        ";

        // Execute the query
        $result = $adminProxy->runQuery($donationsQuery);

        // Fetch the results into the $donations array
        $this->donations = [];
        while ($row = $result->fetch_assoc()) {
            $this->donations[] = $row;
        }
    }

    protected function generateBody() {
        $body = "Donation Details:\n";
        foreach ($this->donations as $donation) {
            $body .= "- Doner: " . $donation['doner'] . ", Date: " . $donation['donation_date'] . ", Total Cost: $" . $donation['total_cost'] . "\n";
            $body .= "  Description: " . $donation['description'] . "\n";

            // Add details based on donation type
            if ($donation['meal_id']) {
                $body .= "  Meal Donation: " . $donation['meal_quantity'] . " meals at $" . $donation['meal_cost'] . " each\n";
            }
            if ($donation['raw_materials_id']) {
                $body .= "  Raw Materials: " . $donation['material_quantity'] . " units of " . $donation['material_type'] . " at $" . $donation['raw_materials_cost'] . " total\n";
            }
            if ($donation['client_ready_meal_id']) {
                $body .= "  Ready Meal: " . $donation['ready_meal_quantity'] . " " . $donation['ready_meal_type'] . " meals expiring on " . $donation['ready_meal_expiration'] . "\n";
            }
            if ($donation['money_id']) {
                $body .= "  Monetary Donation: $" . $donation['money_amount'] . " for " . $donation['money_donation_purpose'] . "\n";
            }
            if ($donation['sacrifice_id']) {
                $body .= "  Sacrifice: $" . $donation['sacrifice_cost'] . " for a " . ($donation['material_type'] ?? 'animal') . "\n";
            }
            if ($donation['box_id']) {
                $body .= "  Custom Box: $" . $donation['box_cost'] . " with items: " . $donation['final_item_list'] . "\n";
            }
            $body .= "-------------------\n";
        }
        $body .= "Total Donations: " . $this->calculateTotalRecords() . "\n";
        $body .= "Most Donated Item: " . $this->getMostDonatedItem() . "\n";
        return $body;
    }

    private function getMostDonatedItem() {
        $itemCounts = [];
        foreach ($this->donations as $donation) {
            if ($donation['meal_id']) {
                $item = "Meals";
                $itemCounts[$item] = ($itemCounts[$item] ?? 0) + $donation['meal_quantity'];
            }
            if ($donation['raw_materials_id']) {
                $item = "Raw Materials (" . $donation['material_type'] . ")";
                $itemCounts[$item] = ($itemCounts[$item] ?? 0) + $donation['material_quantity'];
            }
            if ($donation['client_ready_meal_id']) {
                $item = "Ready Meals (" . $donation['ready_meal_type'] . ")";
                $itemCounts[$item] = ($itemCounts[$item] ?? 0) + $donation['ready_meal_quantity'];
            }
            if ($donation['money_id']) {
                $item = "Monetary Donations";
                $itemCounts[$item] = ($itemCounts[$item] ?? 0) + 1; // Count each monetary donation as 1
            }
            if ($donation['sacrifice_id']) {
                $item = "Sacrifices";
                $itemCounts[$item] = ($itemCounts[$item] ?? 0) + 1; // Count each sacrifice as 1
            }
            if ($donation['box_id']) {
                $item = "Custom Boxes";
                $itemCounts[$item] = ($itemCounts[$item] ?? 0) + 1; // Count each box as 1
            }
        }
        return array_search(max($itemCounts), $itemCounts);
    }
}
?>
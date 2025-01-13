<?php
class DonationReport extends ReportTemplate {
    private $donations;

    public function __construct() {
        $this->donations = [
            ["doner" => "John Doe", "item" => "Books", "quantity" => 10, "date" => "2023-10-01"],
            ["doner" => "Jane Smith", "item" => "Clothes", "quantity" => 5, "date" => "2023-10-05"],
            ["doner" => "Alice Johnson", "item" => "Food", "quantity" => 20, "date" => "2023-10-10"],
        ];
    }

    protected function getReportType() {
        return "Donation Report";
    }

    protected function calculateTotalRecords() {
        return count($this->donations);
    }

    protected function generateBody() {
        $body = "Donation Details:\n";
        foreach ($this->donations as $donation) {
            $body .= "- Doner: " . $donation['doner'] . ", Item: " . $donation['item'] . ", Quantity: " . $donation['quantity'] . ", Date: " . $donation['date'] . "\n";
        }
        $body .= "-------------------\n";
        $body .= "Total Donations: " . $this->calculateTotalRecords() . "\n";
        $body .= "Most Donated Item: " . $this->getMostDonatedItem() . "\n";
        return $body;
    }

    private function getMostDonatedItem() {
        $itemCounts = [];
        foreach ($this->donations as $donation) {
            $item = $donation["item"];
            $itemCounts[$item] = ($itemCounts[$item] ?? 0) + $donation["quantity"];
        }
        return array_search(max($itemCounts), $itemCounts);
    }
}
?>
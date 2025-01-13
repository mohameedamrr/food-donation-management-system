<?php
class FinancialReport extends ReportTemplate {
    private $transactions;

    public function __construct() {
        $this->transactions = [
            ["type" => "Donation", "amount" => 1000, "date" => "2023-10-01"],
            ["type" => "Expense", "amount" => 200, "date" => "2023-10-05"],
            ["type" => "Donation", "amount" => 500, "date" => "2023-10-10"],
        ];
    }

    protected function getReportType() {
        return "Financial Report";
    }

    protected function calculateTotalRecords() {
        return count($this->transactions);
    }

    protected function prepareReportData(){
        
    }

    protected function generateBody() {
        $body = "Financial Details:\n";
        foreach ($this->transactions as $transaction) {
            $body .= "- Type: " . $transaction['type'] . ", Amount: $" . $transaction['amount'] . ", Date: " . $transaction['date'] . "\n";
        }
        $body .= "-------------------\n";
        $body .= "Total Donations: $" . $this->calculateTotalDonations() . "\n";
        $body .= "Total Expenses: $" . $this->calculateTotalExpenses() . "\n";
        $body .= "Net Profit: $" . $this->calculateNetProfit() . "\n";
        return $body;
    }

    private function calculateTotalDonations() {
        return array_sum(array_map(function($t) {
            return $t['type'] === 'Donation' ? $t['amount'] : 0;
        }, $this->transactions));
    }

    private function calculateTotalExpenses() {
        return array_sum(array_map(function($t) {
            return $t['type'] === 'Expense' ? $t['amount'] : 0;
        }, $this->transactions));
    }

    private function calculateNetProfit() {
        return $this->calculateTotalDonations() - $this->calculateTotalExpenses();
    }
}
?>
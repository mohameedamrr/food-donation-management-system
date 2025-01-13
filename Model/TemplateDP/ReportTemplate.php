<?php
// Correct the path for the phpmailerFacade.php file
require __DIR__ . '/../FacadeDP/phpmailerFacade.php'; // Ensure the path is correct

abstract class ReportTemplate {
    protected $reportString = ""; 

    public function generateReport() {
        $this->prepareReportData();
        $this->reportString .= $this->generateHeader();
        $this->reportString .= $this->generateSummary();
        $this->reportString .= $this->generateBody();
        $this->reportString .= $this->generateFooter();

        $this->saveReportToFile();
        $this->sendReportByMail();

        //return $this->reportString; 
    }

    protected function generateHeader() {
        return "=== Admin Report ===\n" .
               "Date: " . date('Y-m-d H:i:s') . "\n" .
               "-------------------\n";
    }

    protected function generateSummary() {
        return "Summary:\n" .
               "- Report Type: " . $this->getReportType() . "\n" .
               "- Total Records: " . $this->calculateTotalRecords() . "\n" .
               "-------------------\n";
    }

    abstract protected function generateBody();

    protected function generateFooter() {
        return "-------------------\n" .
               "End of Report\n" .
               "================\n";
    }

    abstract protected function getReportType();

    abstract protected function prepareReportData();

    abstract protected function calculateTotalRecords();

    protected function sendReportByMail(){
        $mail = new MailFacade();

        $mail->setRecipient('ziadayman087@gmail.com', 'Ziad Ayman');
        $mail->setContent($this->getReportType(), '<p1>'.$this->reportString.'</p1>', true);
        //$mail ->addAttachment("C:\Users\Salah\Downloads\\etsh_horror.jpeg", "etsh_horror.jpeg");

        // Send the email
        $mail->send();
    }

    protected function saveReportToFile() {
        // Generate the filename using today's date and report type
        $filename = date('Y-m-d') . '_' . strtolower(str_replace(' ', '_', $this->getReportType())) . '.txt';

        // Save the report string to the file
        file_put_contents($filename, $this->reportString);
        echo "Report saved to $filename\n";
    }
}
?>
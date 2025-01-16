<?php
// Correct the path for the phpmailerFacade.php file
// require __DIR__ . '/../FacadeDP/phpmailerFacade.php'; // Ensure the path is correct

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
        return "<h1>Admin Report</h1>" .
               "<p>Date: " . date('Y-m-d H:i:s') . "</p>" .
               "<hr>";
    }

    protected function generateSummary() {
        return "<h2>Summary</h2>" .
               "<p><strong>Report Type:</strong> " . $this->getReportType() . "</p>" .
               "<p><strong>Total Records:</strong> " . $this->calculateTotalRecords() . "</p>" .
               "<hr>";
    }

    abstract protected function generateBody();

    protected function generateFooter() {
        return "<hr>" .
               "<p>End of Report</p>" .
               "<p>================</p>";
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
        $filename = date('Y-m-d') . '_' . strtolower(str_replace(' ', '_', $this->getReportType())) . '.html';

        // Wrap the report string in HTML tags
        $htmlContent = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>" . $this->getReportType() . "</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #333; }
        h2 { color: #555; }
        p { color: #666; }
        hr { border: 1px solid #ddd; }
    </style>
</head>
<body>
    " . $this->reportString . "
</body>
</html>";

        // Save the report string to the file
        file_put_contents($filename, $htmlContent);
        echo "Report saved to $filename\n";
    }
}
?>
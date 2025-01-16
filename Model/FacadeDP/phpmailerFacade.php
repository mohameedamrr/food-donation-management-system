
<?php

require __DIR__ . '/../../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../../PHPMailer-master/src/SMTP.php';
require __DIR__ . '/../../PHPMailer-master/src/Exception.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailFacade
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        // Hard-coded SMTP setup
        $this->mailer->isSMTP();
        $this->mailer->Host = 'mail.smtp2go.com'; // Replace with your SMTP host
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 's.gad.astromica'; // Replace with your SMTP username
        $this->mailer->Password = 'salah12345678'; // Replace with your SMTP password
        $this->mailer->Port = 80; // Replace with your SMTP port
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Replace with your SMTP encryption

        // Hard-coded sender details
        $this->mailer->setFrom('salah.gad@astromica.tech', 'Food Donation System'); // Replace with your sender email and name
    }

    /**
     * Set the sender of the email.
     *
     * @param string $email
     * @param string $name
     * @return $this
     */
    public function setFrom($email, $name = '')
    {
        $this->mailer->setFrom($email, $name);

        return $this;
    }

    /**
     * Add a recipient to the email.
     *
     * @param string $email
     * @param string $name
     */
    public function setRecipient($email, $name = '')
    {
        $this->mailer->addAddress($email, $name);
    }


    /**
     * Add a CC recipient to the email.
     *
     * @param string $email
     * @param string $name
     * @return $this
     */
    public function addCC($email, $name = '')
    {
        $this->mailer->addCC($email, $name);

        return $this;
    }

    /**
     * Add a BCC recipient to the email.
     *
     * @param string $email
     * @param string $name
     * @return $this
     */
    public function addBCC($email, $name = '')
    {
        $this->mailer->addBCC($email, $name);

        return $this;
    }

    /**
     * Set the subject and body of the email.
     *
     * @param string $subject
     * @param string $body
     * @param bool $isHtml
     */
    public function setContent($subject, $body, $isHtml = true)
    {
        $this->mailer->Subject = $subject;
        $this->mailer->isHTML($isHtml);
        $this->mailer->Body = $body;
    }

    /**
     * Add an attachment to the email.
     *
     * @param string $path
     * @param string $name
     * @return $this
     */
    public function addAttachment($path, $name = '')
    {
        $this->mailer->addAttachment($path, $name);

        return $this;
    }

    /**
     * Send the email.
     *
     * @return bool
     * @throws Exception
     */
    public function send()
    {
        try {
            return $this->mailer->send();
        } catch (Exception $e) {
           return false;
        }
    }
}

// try {
//     $mail = new MailFacade();

//     $mail->setRecipient('salahgad23@gmail.com', 'salah');
//     $mail->setContent('Your image', '<p1>hello, please checkout your image</p1>', true);
//     $mail ->addAttachment("C:\Users\Salah\Downloads\\etsh_horror.jpeg", "etsh_horror.jpeg");

//     // Send the email
//     $mail->send();

//     echo 'Email sent successfully!';
// } catch (Exception $e) {
//     echo "Error: {$e->getMessage()}";
// }


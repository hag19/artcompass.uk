<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
 use PHPMailer\PHPMailer\PHPMailer;
require '../../accuel/vendor/phpmailer/phpmailer/src/Exception.php';
require '../../accuel/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../accuel/vendor/phpmailer/phpmailer/src/SMTP.php';
require '../../accuel/vendor/autoload.php';
    include("../includes/bdd.php");
    function save($subject, $message)
{
    $log = fopen('/var/www/html/admin/users/log.txt', 'a+');
    $line = $subject . "            " . $message . "\r";
    fputs($log, $line);
    fclose($log);
}
    if(isset($_POST['subject']) && isset($_POST['message'])) {
        $subject = $_POST['subject'];
        $message = '<html>
            <body>
                <div><br>'
                    . $_POST['message'] . "<br><br>Posted " . date('Y/m/d - H:i:s') .
                '</div>
                <div>
                    ------------------------
                </div>
            </body>
        </html>';
        
    } else {
        echo "Поля subject и message не были отправлены.";
    }
$sql = "SELECT email from news";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchALL(PDO::FETCH_ASSOC);
foreach($result as $email){
    try { 
        $mail =  new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPAuth = false;
        $mail->Port = 25; // Укажите порт SMTP
        $mail->isHTML(true);
        $mail->setFrom("News@artcompass.uk");
        $mail->addAddress($email['email']);
        $mail->Subject = $subject;
        $mail->Body = '<div><img src="../../accuel/images/logo.png" width=45px></div>' . $message . '<footer>
        Click <a href="https://artcompass.uk/accuel/cancel.php?email=' . $email['email'] . '">here</a> to unsubscribe.
        </footer>';
        $mail->send();
         echo "Mail sent to " . $email['email'] . "<br>";
    } catch (Exception $e) {
        echo "Mail not sent. Error: " . $e->getMessage();
    }
}
save($subject,$message);

?>
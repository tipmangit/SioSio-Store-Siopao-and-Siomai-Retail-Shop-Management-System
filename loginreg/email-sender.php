<?php
// ✅ Always respond as JSON
header("Content-Type: application/json");
ini_set('display_errors', 0);
error_reporting(E_ALL);

// ✅ Convert PHP errors into JSON (so you never get "<br><b>")
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo json_encode([
        "success" => false,
        "message" => "PHP Error: $errstr in $errfile on line $errline"
    ]);
    exit;
});

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ Load PHPMailer (adjust path if vendor/ is outside loginreg/)
require __DIR__ . '/vendor/autoload.php';

// ✅ Read input from fetch()
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['email'], $input['name'], $input['otp'], $input['type'])) {
    echo json_encode(["success" => false, "message" => "Invalid request payload"]);
    exit;
}

$email = $input['email'];
$name  = $input['name'];
$otp   = $input['otp'];
$type  = $input['type'];

// ✅ Email subject & message
$subject = $type === "registration"
    ? "Your Registration OTP Code"
    : "Your Password Reset OTP Code";

$message = "
    Hello $name,<br><br>
    Your OTP code is: <b>$otp</b><br><br>
    Please use this code to complete your $type process.<br><br>
    Regards,<br>
    Your Ka-SioSio Team
";

$mail = new PHPMailer(true);

try {
    // ✅ Gmail SMTP settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'siosioretailstore@gmail.com';     // 🔹 your Gmail
    $mail->Password   = 'hqlw sute xjea wcmo';       // 🔹 16-digit app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->SMTPOptions = [
    'ssl' => [
        'verify_peer'       => false,
        'verify_peer_name'  => false,
        'allow_self_signed' => true,
    ]
];


    // ✅ Recipients
    $mail->setFrom('siosioretailstore@gmail.com', 'SioSio Retail Store'); // 🔹 your Gmail
    $mail->addAddress($email, $name);

    // ✅ Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $message;

    $mail->send();
    echo json_encode(["success" => true, "message" => "Email sent successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Mailer Error: {$mail->ErrorInfo}"]);
}

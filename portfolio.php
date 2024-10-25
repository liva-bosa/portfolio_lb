<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get POST data
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    // validate email
    if (empty($email)) {
        $errors[] = 'Lūdzu, ievadiet e-pasta adresi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Lūdzu, ievadiet derīgu e-pasta adresi';
    }

    // validate message
    if (empty($message)) {
        $errors[] = 'Lūdzu, ievadiet ziņu';
    } elseif (strlen($message) < 10) {
        $errors[] = 'Ziņai jābūt vismaz 10 rakstzīmes garai';
    }

    // If no errors, send email
    if (empty($errors)) {
        // Email details
        $to = "bosaliva9@gmail.com";
        $subject = "Jauna ziņa no portfolio vietnes";
        
        // Create email body
        $email_body = "Saņemta jauna ziņa no kontaktformas:\n\n";
        $email_body .= "E-pasts: " . $email . "\n";
        $email_body .= "Ziņa:\n" . $message . "\n";

        // Additional headers
        $headers = array(
            'From: ' . $email,
            'Reply-To: ' . $email,
            'X-Mailer: PHP/' . phpversion(),
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8'
        );

        // Try to send email
        if (mail($to, $subject, $email_body, implode("\r\n", $headers))) {
            $success = true;
            $_SESSION['flash_message'] = "Paldies! Jūsu ziņa ir nosūtīta.";
        } else {
            $errors[] = "Diemžēl notika kļūda sūtot ziņu. Lūdzu, mēģiniet vēlreiz.";
        }
    }

    // Return JSON response for AJAX requests
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'errors' => $errors,
            'message' => $success ? $_SESSION['flash_message'] : null
        ]);
        exit;
    }

    // Redirect after successful submission
    if ($success) {
        header("Location: index.html#C3");
        exit;
    }
} else {
    // Not a POST request
    header("HTTP/1.1 403 Forbidden");
    echo "Piekļuve liegta.";
    exit;
}
?>

<!-- Display errors if any -->
<?php if (!empty($errors)): ?>
<div class="error-messages" style="color: red; margin: 20px 0;">
    <?php foreach ($errors as $error): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>
</div>
<?php endif; ?>
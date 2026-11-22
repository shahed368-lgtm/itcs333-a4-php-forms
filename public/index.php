<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/validate.php';

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$name = '';
$email = '';
$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = is_string($_POST['name'] ?? null) ? trim($_POST['name']) : '';
    $email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
    $message = is_string($_POST['message'] ?? null) ? trim($_POST['message']) : '';

    if (!validate_required($name)) {
        $errors['name'] = 'Error: Name is required.';
    } elseif (!validate_length($name, 2, 60)) {
        $errors['name'] = 'Error: Name is too short or too long (2-60 characters).';
    }

    if (!validate_required($email)) {
        $errors['email'] = 'Error: Email is required.';
    } elseif (!validate_email($email)) {
        $errors['email'] = 'Error: Invalid email address.';
    }

    if (!validate_required($message)) {
        $errors['message'] = 'Error: Message is required.';
    } elseif (!validate_length($message, 10, 1000)) {
        $errors['message'] = 'Error: Message is too short or too long (10-1000 characters).';
    }

    if (count($errors) === 0) {
        header('Location: index.php?success=1', true, 303);
        exit;
    }
}

$success = isset($_GET['success']);
?
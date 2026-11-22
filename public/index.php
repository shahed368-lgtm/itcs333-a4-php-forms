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
        $errors['name'] = 'Name is required.';
    } elseif (!validate_length($name, 2, 60)) {
        $errors['name'] = 'Name must be between 2 and 60 characters.';
    }

    if (!validate_required($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!validate_email($email)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if (!validate_required($message)) {
        $errors['message'] = 'Message is required.';
    } elseif (!validate_length($message, 10, 1000)) {
        $errors['message'] = 'Message must be between 10 and 1000 characters.';
    }

    if (count($errors) === 0) {
        header('Location: index.php?success=1', true, 303);
        exit;
    }
}

$success = isset($_GET['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Us</title>
</head>
<body>
  <h1>Contact Us</h1>

  <?php if ($success): ?>
    <p>Thank you! Your message has been sent.</p>
    <p><a href="index.php">Send another message</a></p>
  <?php else: ?>
    <form method="post" action="index.php" novalidate>
      <label>Name <input type="text" name="name" value="<?= e($name) ?>"></label>
      <?php if (isset($errors['name'])): ?>
        <span style="color: red;"><?= e($errors['name']) ?></span>
      <?php endif; ?>
      <br>

      <label>Email <input type="email" name="email" value="<?= e($email) ?>"></label>
      <?php if (isset($errors['email'])): ?>
        <span style="color: red;"><?= e($errors['email']) ?></span>
      <?php endif; ?>
      <br>

      <label>Message <textarea name="message"><?= e($message) ?></textarea></label>
      <?php if (isset($errors['message'])): ?>
        <span style="color: red;"><?= e($errors['message']) ?></span>
      <?php endif; ?>
      <br>

      <button type="submit">Send</button>
    </form>
  <?php endif; ?>
</body>
</html>
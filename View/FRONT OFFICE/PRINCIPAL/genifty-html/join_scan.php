<?php
// join_scan.php
session_start();
require_once __DIR__ . '/../../../../config.php';
$pdo = config::getConnexion();

$code = isset($_GET['code']) ? trim($_GET['code']) : '';
$error = '';

// 1. Verify Code & Get Session ID
$session_id = 0;
if ($code) {
    $stmt = $pdo->prepare("SELECT session_id, game_state FROM online_sessions WHERE code_invitation = ?");
    $stmt->execute([$code]);
    $game = $stmt->fetch();
    
    if ($game) {
        $session_id = $game['session_id'];
    } else {
        $error = "Invalid Game Code.";
    }
}

// 2. Handle GUEST Login Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guest_name'])) {
    $guestName = trim($_POST['guest_name']) . " (Guest)";
    
    // Insert Temporary Guest User
    // Assumes your 'user' table has 'nom' and doesn't require email/password for guests
    // Adjust SQL if your table is strict
    try {
        $ins = $pdo->prepare("INSERT INTO user (nom, email, role) VALUES (?, ?, 'guest')");
        // We generate a fake email to satisfy DB constraints if needed
        $fakeEmail = uniqid('guest_') . '@temp.com';
        $ins->execute([$guestName, $fakeEmail]);
        
        $newUserId = $pdo->lastInsertId();
        
        // Log them in
        $_SESSION['user_id'] = $newUserId;
        $_SESSION['user_name'] = $guestName;
        $_SESSION['is_guest'] = true;
        
        // Redirect to Lobby
        header("Location: online_lobby.php?session=" . $session_id);
        exit;
    } catch (Exception $e) {
        $error = "Could not join as guest: " . $e->getMessage();
    }
}

// 3. Handle Regular Login Redirect
if (isset($_GET['action']) && $_GET['action'] === 'login') {
    // Redirect to your login page, passing the destination
    header("Location: ../../../../login.html?redirect=lobby&session=" . $session_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Join Game - Zitouna Quest</title>
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #005248, #00c49f);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            font-family: Arial, sans-serif; color: white;
        }
        .join-card {
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);
            padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            text-align: center; max-width: 400px; width: 100%; border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-custom { width: 100%; margin-bottom: 15px; padding: 12px; border-radius: 8px; font-weight: bold; }
        .btn-guest { background: #FFBB28; color: #333; border: none; }
        .btn-login { background: transparent; border: 2px solid white; color: white; }
        input { width: 100%; padding: 10px; border-radius: 5px; border: none; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="join-card">
    <h1 style="margin-bottom: 20px;">🚀 Join Game</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php elseif ($session_id > 0): ?>
        
        <p>You have been invited to game:</p>
        <h2 style="font-size: 3rem; letter-spacing: 5px; margin-bottom: 30px;"><?php echo htmlspecialchars($code); ?></h2>

        <form method="POST">
            <div style="text-align: left; margin-bottom: 5px; font-size: 0.9rem;">Quick Join:</div>
            <input type="text" name="guest_name" placeholder="Enter Nickname..." required>
            <button type="submit" class="btn btn-custom btn-guest">Join as Guest</button>
        </form>

        <div style="margin: 20px 0; opacity: 0.7;">— OR —</div>

        <a href="?code=<?php echo $code; ?>&action=login" class="btn btn-custom btn-login">
            Login with Account
        </a>

    <?php else: ?>
        <p>Please scan a valid QR code.</p>
    <?php endif; ?>
</div>

</body>
</html>
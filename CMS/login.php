<?php
session_start();
include "db.php";

if (isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, role, password FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] === 'admin') {
                header("Location: dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        }
    }

    $error = "Invalid username or password.";
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | Neon CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: 'Outfit', sans-serif;
            color: #1f4260;
            display: grid;
            place-items: center;
            padding: 24px;
            background:
                radial-gradient(900px 600px at 10% -10%, rgba(124, 214, 252, 0.45), transparent 65%),
                radial-gradient(800px 500px at 90% -20%, rgba(187, 232, 255, 0.45), transparent 70%),
                linear-gradient(155deg, #f8fdff, #eff8ff 56%, #e7f4ff);
        }

        .login-shell {
            width: min(980px, 100%);
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            border: 1px solid rgba(119, 186, 228, 0.35);
            border-radius: 26px;
            overflow: hidden;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.94), rgba(230, 245, 255, 0.92));
            backdrop-filter: blur(12px);
            box-shadow: 0 18px 44px rgba(73, 138, 183, 0.22);
        }

        .login-art {
            padding: 42px;
            background: linear-gradient(160deg, rgba(116, 209, 251, 0.32), rgba(218, 243, 255, 0.64));
            border-right: 1px solid rgba(119, 186, 228, 0.33);
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 18px;
        }

        .login-art h1 {
            font-size: 2.2rem;
            line-height: 1.15;
            color: #184261;
        }

        .login-art p {
            color: #4f7190;
            line-height: 1.6;
        }

        .badge {
            width: fit-content;
            border: 1px solid rgba(92, 174, 224, 0.4);
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 0.9rem;
            color: #275679;
            background: rgba(255, 255, 255, 0.7);
        }

        .login-card {
            padding: 42px 34px;
        }

        .login-card h2 {
            font-size: 1.6rem;
            margin-bottom: 8px;
            color: #184261;
        }

        .login-card p {
            color: #6182a0;
            margin-bottom: 24px;
        }

        .input-group {
            margin-bottom: 14px;
        }

        label {
            display: block;
            font-size: 0.92rem;
            margin-bottom: 6px;
            color: #2f5f83;
        }

        input {
            width: 100%;
            border: 1px solid rgba(123, 191, 229, 0.5);
            background: rgba(255, 255, 255, 0.92);
            color: #1b3f5d;
            border-radius: 12px;
            padding: 12px;
            outline: none;
        }

        input:focus {
            border-color: rgba(95, 184, 230, 0.95);
            box-shadow: 0 0 0 3px rgba(118, 203, 244, 0.22);
        }

        .login-btn {
            width: 100%;
            margin-top: 8px;
            border: none;
            border-radius: 999px;
            padding: 12px;
            color: #123a57;
            font-weight: 700;
            cursor: pointer;
            background: linear-gradient(115deg, #66ceff, #b5ebff);
            box-shadow: 0 12px 28px rgba(106, 189, 230, 0.28);
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.03);
        }

        .error {
            margin-top: 14px;
            border: 1px solid rgba(255, 147, 167, 0.55);
            background: rgba(255, 215, 224, 0.85);
            color: #9a2c48;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.9rem;
        }

        @media (max-width: 860px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .login-art {
                border-right: none;
                border-bottom: 1px solid rgba(119, 186, 228, 0.33);
            }

            .login-card {
                padding: 30px 22px;
            }
        }
    </style>
</head>
<body>

<div class="login-shell">
    <div class="login-art">
        <div class="badge">Secure Admin Access</div>
        <h1>Manage your content with a premium dashboard.</h1>
        <p>Fast publishing, modern controls, and a redesigned glossy interface built for real production workflows.</p>
    </div>

    <div class="login-card">
        <h2>Welcome Back</h2>
        <p>Enter your credentials to access the CMS panel.</p>

        <form method="POST" autocomplete="off">
            <div class="input-group">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" placeholder="Enter username" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Enter password" required>
            </div>

            <button class="login-btn" type="submit" name="login">Sign In</button>
        </form>

        <?php if ($error) : ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

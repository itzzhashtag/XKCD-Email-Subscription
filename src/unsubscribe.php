<!-- Little Styling of the Page -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>XKCD Email Un-Subscription</title>
        <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .wrapper {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            padding: 30px 35px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #222;
            font-weight: 600;
        }

        form {
            margin-top: 15px;
        }

        input[type="email"],
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 12px 14px;
            margin: 10px 0 15px 0;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
            transition: border 0.3s ease;
            box-sizing: border-box;
        }

        input[type="email"]:focus,
        input[type="text"]:focus,
        input[type="number"]:focus {
            border: 1px solid #007BFF;
            outline: none;
        }

        button {
            width: 100%;
            padding: 8px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }



        button:hover {
            background-color: #0056b3;
        }

        p.message {
            margin-top: 15px;
            font-size: 13px;
            color: #555;
        }

        .debug {
            margin-top: 15px;
            font-size: 12px;
            background-color: #f9f9f9;
            padding: 8px;
            border-radius: 8px;
            color: #666;
            text-align: left;
        }
        </style>
    </head>
<body>



<!-- MAIN Code For Unsubscribe-->
<div class="wrapper">
    <?php
        require_once 'functions.php';
        $message = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $action = $_POST['action'] ?? '';
            // Step 1: Request unsubscription code
            if ($action === 'send_unsub_code' && isset($_POST['unsubscribe_email']) && !empty($_POST['unsubscribe_email'])) 
            {
                $email = trim($_POST['unsubscribe_email']);
                $file = __DIR__ . '/registered_emails.txt';
                $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
                //echo "<p>DEBUG: Unsub email → $email</p>";                                        //Debugging reason
                if (!in_array($email, $emails)) 
                {
                    $message = "❌ This email is not subscribed.";
                } 
                else 
                {
                    $code = generateVerificationCode();
                    //echo "<p>DEBUG: Unsub code → $code</p>";                                       //Debugging reason
                    if (!is_dir(__DIR__ . '/codes')) 
                    {
                        mkdir(__DIR__ . '/codes');
                    }
                    file_put_contents(__DIR__ . "/codes/" . md5($email) . ".txt", $code);
                    sendVerificationEmail($email, $code);
                    $message = "🔸 Unsubscribe verification code sent to $email (Check Mail)";
                }
            }
            // Step 2: Confirm unsubscription
            if ($action === 'verify_unsub' && isset($_POST['unsubscribe_email']) && isset($_POST['verification_code'])) 
            {
                $email = trim($_POST['unsubscribe_email']);
                $inputCode = trim($_POST['verification_code']);
                $codeFile = __DIR__ . "/codes/" . md5($email) . ".txt";
                $file = __DIR__ . '/registered_emails.txt';
                $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
                //echo "<p>DEBUG: UnSub verify email → $email</p>";                                 //Debugging reason
                //echo "<p>DEBUG: Input code → $inputCode</p>";                                     //Debugging reason
                
                if (!in_array($email, $emails)) 
                {
                    $message = "❌ This email is not subscribed.";
                }
                else if (!file_exists($codeFile))
                {
                    $message = "❌ No verification code found for this email.";
                }
                else
                {
                    $savedCode = trim(file_get_contents($codeFile));
                    //echo "<p>DEBUG: Saved code → $savedCode</p>";                                 //Debugging reason
                    if ($inputCode === $savedCode) 
                    {
                        unsubscribeEmail($email);
                        $message = "✅ Email : $email unsubscribed successfully!";
                    } 
                    else 
                    {
                        $message = "❌ Invalid verification code.";
                    }
                } 
            }
        }
    ?>


    <h2>⭕ Unsubscribe from XKCD Comics</h2>
        <!-- Form 1: Request Unsubscribe Code -->
        <form method="post">
            <input type="email" name="unsubscribe_email" required placeholder="Enter your email to unsubscribe">
            <button id="submit-unsubscribe" name="action" value="send_unsub_code">Unsubscribe</button>
        </form>

        <!-- Form 2: Confirm Unsubscribe -->
        <form method="post">
            <input type="email" name="unsubscribe_email" required placeholder="Your email again">
            <input type="text" name="verification_code" maxlength="6" required placeholder="Enter 6-digit code">
            <button id="submit-verification" name="action" value="verify_unsub">Verify</button>
        </form>
        <p><?php echo $message; ?></p>
</div>
</body>
</html>
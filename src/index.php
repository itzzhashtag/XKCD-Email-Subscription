<!-- Little Styling of the Page -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>XKCD Email Subscription</title>
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


<!-- MAIN Code For Index-->
<div class="wrapper">
    <?php          
        require_once 'functions.php';       //Can use Include for multile Callings
        $message = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {   
            $action = $_POST['action'] ?? '';

            // Step 1: Email submission (request verification code)
            if ($action === 'send_code' && isset($_POST['email']) && !empty($_POST['email'])) 
            {
                $email = trim($_POST['email']);
                $code = generateVerificationCode();
                //echo "<p>DEBUG: Entered email → $email</p>";                      //Debugging reason
                //echo "<p>DEBUG: Generated Code → $code</p>";                      //Debugging reason

                if (!is_dir(__DIR__ . '/codes')) 
                {
                    mkdir(__DIR__ . '/codes');
                }
                file_put_contents(__DIR__ . "/codes/" . md5($email) . ".txt", $code);
                sendVerificationEmail($email, $code);
                $message = "🔸Verification code sent to $email (Check Mail)";
            }

            // Step 2: Code verification (verify & register)
            if ($action === 'verify_code' && isset($_POST['verification_code']) && isset($_POST['email'])) 
            {
                $email = trim($_POST['email']);
                $inputCode = trim($_POST['verification_code']);
                $codeFile = __DIR__ . "/codes/" . md5($email) . ".txt";
                //echo "<p>DEBUG: Using email → $email</p>";                        //Debugging reason     
                //echo "<p>DEBUG: Looking for file → $codeFile</p>";                //Debugging reason
                //echo "<p>DEBUG: Input code → $inputCode</p>";                     //Debugging reason
                if (!file_exists($codeFile)) 
                {
                    $message = "❌ No verification code found for this email.";
                }
                else
                {
                    $savedCode = trim(file_get_contents($codeFile));
                    //echo "<p>DEBUG: Saved code → $savedCode</p>";                 //Debugging reason
                    if ($inputCode === $savedCode) 
                    {
                        $file = __DIR__ . '/registered_emails.txt';
                        $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

                        if (in_array($email, $emails)) 
                        {
                            $message = "⚠️ Email : $email is already subscribed.";
                        } 
                        else 
                        {
                            registerEmail($email);
                            $message = "✅ Email : $email verified and subscribed successfully!";
                        }
                    } 
                    else 
                    {
                        $message = "❌ Invalid verification code.";
                    }
                }
            }
        }
            
    ?>
    <h2>📬 Subscribe to XKCD Comics</h2>
    <!-- Functions and buttons calls for the Page -->
    <!-- Email submission form -->
    <form method="post">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button id="submit-email" name="action" value="send_code">Send Verification Code</button>
    </form>
    <!-- Code verification form -->
    <form method="post">
        <input type="email" name="email" placeholder="Your email again" required>
        <input type="text" name="verification_code" maxlength="6" placeholder="Enter 6-digit code" required>
        <button id="submit-verification" name="action" value="verify_code">Verify & Subscribe</button>
    </form>
    
    <p class="message"><?php echo $message ?? ''; ?></p>
    <div class="debug">
        <?php
        // Can optionally put debug outputs here for testing (or hide them for screenshots) hihi XP
        ?>
    </div>
    
</div>
</body>
</html>

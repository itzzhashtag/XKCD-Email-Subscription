<?php
    // Set Mailpit SMTP configuration globally (works for browser + CLI/CRON)
    ini_set("SMTP", "localhost");
    ini_set("smtp_port", "1025");
    ini_set('sendmail_from', 'no-reply@example.com');

    //Generate a 6-digit numeric verification code.
    function generateVerificationCode(): string 
    {
        return str_pad(strval(rand(0, 999999)), 6, '0', STR_PAD_LEFT);
    }

    //Send a verification code to an email.
    function sendVerificationEmail(string $email, string $code): bool 
    {
        $email = trim($email);  // safety

        $subject = "Your Verification Code";
        $message = "<p>Your verification code is: <strong>{$code}</strong></p>";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@example.com\r\n";

        return mail($email, $subject, $message, $headers);
    }

    //Register an email by storing it in a file.
    function registerEmail(string $email): bool 
    {
        $email = trim($email);
        $file = __DIR__ . '/registered_emails.txt';

        if (!file_exists($file)) {
            file_put_contents($file, '');
        }

        $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!in_array($email, $emails)) {
            file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
        }

        return true;
    }

    //    Unsubscribe an email by removing it from the list.
    function unsubscribeEmail(string $email): bool 
    {
        $email = trim($email);
        $file = __DIR__ . '/registered_emails.txt';

        if (!file_exists($file)) {
            return false;
        }

        $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!in_array($email, $emails)) {
            return false; // already unsubscribed
        }

        $filtered = array_filter($emails, function($line) use ($email) {
            return trim($line) !== $email;
        });

        file_put_contents($file, implode(PHP_EOL, $filtered) . PHP_EOL);
        return true;
    }

    //Fetch random XKCD comic and format data as HTML.
    function fetchAndFormatXKCDData(): string 
    {
        $randomId = rand(1, 2800);
        $url = "https://xkcd.com/$randomId/info.0.json";

        $response = @file_get_contents($url);

        if ($response === FALSE) {
            // Fallback Comic
            $title = 'Family Circus';
            $img = 'https://imgs.xkcd.com/comics/family_circus.jpg';
            $alt = 'This was my friend Hashtag\'s idea';

            return "
                <h2>XKCD Comic: {$title}</h2>
                <img src=\"{$img}\" alt=\"{$alt}\" style=\"max-width:100%;\">
                <p>{$alt}</p>
            ";
        }

        $data = json_decode($response, true);

        $title = htmlspecialchars($data['title'] ?? 'Unknown');
        $img = htmlspecialchars($data['img'] ?? '');
        $alt = htmlspecialchars($data['alt'] ?? '');

        return "
            <h2>XKCD Comic: {$title}</h2>
            <img src=\"{$img}\" alt=\"{$alt}\" style=\"max-width:100%;\">
            <p>{$alt}</p>
        ";
    }

    //Send the formatted XKCD updates to registered emails.
    function sendXKCDUpdatesToSubscribers(): void 
    {
        $file = __DIR__ . '/registered_emails.txt';

        if (!file_exists($file)) return;

        $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (empty($emails)) return;

        $comicHTML = fetchAndFormatXKCDData();
        $subject = "Your XKCD Comic";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@example.com\r\n";

        foreach ($emails as $email) 
        {
            $email = trim($email);
            $unsubscribeLink = "http://localhost:8000/xkcd/src/unsubscribe.php?email=" . urlencode($email);

            $emailBody = $comicHTML . "<p><a href=\"{$unsubscribeLink}\" id=\"unsubscribe-button\">Unsubscribe</a></p>";

            mail($email, $subject, $emailBody, $headers);
        }
    }

?>

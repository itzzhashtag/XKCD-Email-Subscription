<?php
require_once __DIR__ . '/functions.php';
// Optional: Display something in terminal when running manually
echo "Running XKCD Comic Cron Job...\n";
// Call the function to send comics to subscribers
sendXKCDUpdatesToSubscribers();
echo "Cron Job completed.\n";

?>

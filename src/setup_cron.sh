#!/bin/bash
# This script should set up a CRON job to run cron.php every 24 hours.
# You need to implement the CRON setup logic here.


# Absolute path to your project src folder
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Set cron job to run every day at 9 AM (you can change time here)
CRON_TIME="0 9 * * *"

# Build full cron line
CRON_JOB="$CRON_TIME php $DIR/cron.php"

# Add cron job if not already present
(crontab -l 2>/dev/null; echo "$CRON_JOB") | sort | uniq | crontab -

echo "✅ Cron job added:"
echo "$CRON_JOB"

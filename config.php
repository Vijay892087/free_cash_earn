<?php
// ---------------- TIMEZONE ---------------- //
date_default_timezone_set('Asia/Kolkata');

// ---------------- CLAIM SETTINGS ---------------- //
// Claim amount per user (₹) - Change as per your requirement
$claim_amount = 2;

// Total allowed claims (maximum users who can claim) - Change as needed
$total_claim_limit = 500;

// CSV file where all claims will be recorded
// Use absolute path for Render to ensure it saves correctly
$csv_file = __DIR__ . "/free.csv";

// ---------------- TELEGRAM BOT CONFIG ---------------- //
// Bot token from BotFather
$telegram_bot_token = "6391372827:AAHY-gfeyHZvtaGKIr4TLyga17lr73lj86o";

// Chat ID for admin alerts (full data)
$chat_id = "969062037";

// Telegram channel ID for masked alerts
$channel_chat_id = -1003073944495; // Replace with your channel numeric ID

// Optional: Telegram relay URL if you want to use a relay
$telegram_relay_url = "https://telegram-free-alert.onrender.com/free.php";

// ---------------- DEBUG / LOGGING ---------------- //
// Enable debugging logs (Render sometimes hides errors)
$debug_mode = true;
if ($debug_mode) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// ---------------- OPTIONAL SETTINGS ---------------- //
// You can modify these to change CSV filename dynamically or claim limits easily
$csv_filename = basename($csv_file); // just the filename
?>
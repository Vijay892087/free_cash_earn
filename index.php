<?php 
include "config.php";

// ----------------- AUTO-GENERATE CSV IF NOT EXISTS -----------------
if (!file_exists($csv_file)) {
    $f = fopen($csv_file, 'w'); // create new CSV file
    // Optional: add header row
    fputcsv($f, ['Phone', 'Account Number', 'IFSC Code', 'Claim', 'Time']);
    fclose($f);
}

// ----------------- CALCULATE TOTAL CLAIMS -----------------
$total_claims = 0;
if(file_exists($csv_file)){
    $data = array_map('str_getcsv', file($csv_file));
    // Ignore header row if exists
    if (!empty($data) && $data[0][0] === 'Phone') {
        $total_claims = count($data) - 1;
    } else {
        $total_claims = count($data);
    }
}

// ----------------- CHECK IF CLAIMS AVAILABLE -----------------
$claim_available = $total_claims < $total_claim_limit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Claim ₹<?= $claim_amount ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body{margin:0;font-family:'Inter',sans-serif;background:linear-gradient(135deg,#0f1722,#0b3b2e);color:#fff;display:flex;justify-content:center;align-items:center;height:100vh;}
.container{background:rgba(0,0,0,0.7);padding:30px;border-radius:15px;width:90%;max-width:400px;text-align:center;box-shadow:0 8px 30px rgba(0,0,0,0.5);}
h1{color:#2ecc71;margin-bottom:20px;}
label{display:block;margin:10px 0 6px;font-weight:600;}
input{width:100%;padding:12px;margin-bottom:16px;border-radius:10px;border:1px solid #ccc;font-size:16px;box-sizing:border-box;}
button{width:100%;padding:14px;background:#27ae60;border:none;color:#fff;font-size:18px;font-weight:700;border-radius:10px;cursor:pointer;transition:0.3s;}
button:hover{background:#2ecc71;}
button:disabled{background:#7f8c8c;cursor:not-allowed;}
</style>
</head>
<body>
<div class="container">
<h1>Claim ₹<?= $claim_amount ?></h1>
<?php if($claim_available): ?>
<form action="submit.php" method="post">
<label>Phone Number</label>
<input type="text" name="phone" required placeholder="Enter your phone number">
<label>Account Number</label>
<input type="text" name="account_no" required placeholder="Enter account number">
<label>IFSC Code</label>
<input type="text" name="ifsc_code" required placeholder="Enter IFSC code">
<button type="submit">Claim ₹<?= $claim_amount ?></button>
</form>
<p>Total Claims So Far: <?= $total_claims ?> / <?= $total_claim_limit ?></p>
<?php else: ?>
<h2>⚠ Claim Limit Reached!</h2>
<p>Sorry, all claims have been completed.</p>
<p>Total Claims: <?= $total_claims ?> / <?= $total_claim_limit ?></p>
<?php endif; ?>
</div>
</body>
</html>
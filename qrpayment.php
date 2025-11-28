<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

// Gateway Configuration
function qrpayment_config() {
    return [
        'FriendlyName' => [
            'Type' => 'System',
            'Value' => 'QR Payment (Manual UPI)',
        ],
        'upi_id' => [
            'FriendlyName' => 'UPI ID',
            'Type' => 'text',
            'Size' => '40',
            'Description' => 'Enter your receiving UPI ID',
        ],
        'qr_image' => [
            'FriendlyName' => 'QR Code URL',
            'Type' => 'text',
            'Size' => '60',
            'Description' => 'Direct link to your QR image',
        ],
        'note' => [
            'FriendlyName' => 'Payment Note / Instructions',
            'Type' => 'textarea',
            'Description' => 'Optional message for customer',
        ],
    ];
}


// Payment Button Hook
function qrpayment_link($params) {

    $invoiceId = $params['invoiceid'];
    $amount = $params['amount'];
    $currency = $params['currency'];
    $client = $params['clientdetails']['fullname'];

    $upiId = $params['upi_id'];
    $qrImage = $params['qr_image'];
    $note = nl2br($params['note']);

    // Popup HTML
    $html = <<<HTML
<style>
.qr-popup-bg {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.65);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
}
.qr-popup {
    background: #111;
    padding: 25px;
    width: 420px;
    border-radius: 16px;
    color: white;
    font-family: 'Inter', sans-serif;
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
    animation: pop 0.25s ease;
}
@keyframes pop {
    from {transform: scale(0.85); opacity: 0;}
    to   {transform: scale(1); opacity: 1;}
}
.qr-close {
    float: right;
    cursor: pointer;
    background: #222;
    padding: 5px 12px;
    border-radius: 6px;
}
.qr-box {
    text-align: center;
}
.qr-box img {
    width: 220px;
    margin: 12px auto;
    border-radius: 12px;
}
.qr-info {
    margin-top: 15px;
    background: #1a1a1a;
    padding: 12px;
    border-radius: 10px;
    font-size: 14px;
}
</style>

<div id="qrpay" class="qr-popup-bg" style="display:none;">
    <div class="qr-popup">
        <div class="qr-close" onclick="document.getElementById('qrpay').style.display='none'">Close</div>
        <h2 style="margin-top:40px;text-align:center;">UPI Payment</h2>

        <div class="qr-box">
            <img src="$qrImage" alt="QR Code">
        </div>

        <div class="qr-info">
            <b>Invoice:</b> #$invoiceId<br>
            <b>Name:</b> $client<br>
            <b>Amount:</b> {$currency} $amount<br>
            <b>UPI ID:</b> $upiId
        </div>

        <p style="margin-top:10px; font-size:13px; opacity:0.8;">
            $note
        </p>
    </div>
</div>

<button onclick="document.getElementById('qrpay').style.display='flex'"
style="background:#111;color:white;border:0;padding:10px 18px;border-radius:10px;cursor:pointer;">
Pay Using UPI
</button>
HTML;

    return $html;
}


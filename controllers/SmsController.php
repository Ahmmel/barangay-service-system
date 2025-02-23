<?php
function sendSMS($phone, $message) {
    $api_key = "YOUR_SEMAPHORE_API_KEY"; // Replace with your Semaphore API key
    $sender = "QPila"; // Custom sender name (Optional for paid plans)

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.semaphore.co/api/v4/messages");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'apikey' => $api_key,
        'number' => $phone,
        'message' => $message,
        'sendername' => $sender
    ]));

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
?>
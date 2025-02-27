<?php
class SemaphoreAPI
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = "YOUR_SEMAPHORE_API_KEY"; // Replace with your actual API key
    }

    public function sendSMS($phoneNumber, $message)
    {
        $url = "https://semaphore.co/api/v4/messages";
        $data = [
            'apikey' => $this->apiKey,
            'number' => $phoneNumber,
            'message' => $message,
            'sendername' => "BarangayQPila"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification if necessary

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}

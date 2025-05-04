<?php
class SemaphoreAPI
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = "5dc0a197e4958f1d5d88ecf168135d96"; // Replace with your actual API key
    }

    public function sendSMS($phoneNumber, $message)
    {
        $url = "https://semaphore.co/api/v4/messages";
        $data = [
            'apikey' => $this->apiKey,
            'number' => $phoneNumber,
            'message' => $message
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);

        //Send the parameters set above with the request
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        // Receive response from server
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        var_dump($response); // Debugging line to check the response
        return $response;
    }
}

<?php
class Request
{
    private $base_url = "http://localhost:8877/PHP/wordpress/wp-json/";
    private $token = "";

    public function __construct($file_path = '') {
        if (file_exists($file_path)) {
            $this->token = file_get_contents($file_path);
        }
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function get($entry_point, $full_address = false) {
        if ($full_address === false) {
            $url = $this->base_url . $entry_point;
        }
        else {
            $url = $entry_point;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);

        if($output === false) {
            return 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);

        return json_decode($output, true);
    }

    public function post(string $entry_point, array $data = [], bool $send_token = false) {
        $ch = curl_init($this->base_url . $entry_point);
        curl_setopt($ch, CURLOPT_POST, 1);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $headers = ['Content-Type: application/json'];
        if ($send_token === true) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $output = curl_exec($ch);
        if($output === false) {
            return 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);

        return json_decode($output, true);
    }
}
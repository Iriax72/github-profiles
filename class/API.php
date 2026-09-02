<?php
class API {
    public function __construct()
    {

    }

    public function search_accounts(string $query, int $limit = 10) : array
    {
        return this->callApi('search/users?q=' . urlencode($query) . '+in:login&per_page=' . urlencode($limit));
    }

    private function callApi(string $endpoint) : ?array
    {
        $curl = curl_init('https://api.github.com/' . urlencode($endpoint));
        
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 2,
            // CURLOPT_CAINFO => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'certificate.ca'
        ]);

        $data = $curl_exec($curl);

        if ($data === false || curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
            return null;
        }

        return json_decode($data, true);
    }
}
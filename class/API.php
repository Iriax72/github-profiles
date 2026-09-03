<?php
class API {
    public function __construct()
    {

    }

    public function search_accounts(string $query, int $limit = 10) : array
    {
        return $this->callApi('search/users?q=' . urlencode($query) . '+in:login&per_page=' . urlencode($limit));
    }

    public function get_account_details(string $login) : array
    {
        return $this->callApi('users/' . urlencode($login));
    }

    private function callApi(string $endpoint) : array
    {
        $curl = curl_init('https://api.github.com/' . $endpoint);
        
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => 'github-profiles',
            // CURLOPT_CAINFO => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'certificate.ca'
        ]);

        $data = curl_exec($curl);

        if ($data === false || curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
            curl_close($curl);
            throw new Exception('API request failed: ' . curl_error($curl));
        }

        curl_close($curl);
        return json_decode($data, true);
    }
}
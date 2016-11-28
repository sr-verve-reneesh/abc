<?php
namespace ReneeshKuttan\WhitemailApi;

class WhitemailApi
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    
    /**
     * Subdomain of the whitemail client account.
     * 
     * @var string
     */
    private $apiUrl;
    
    /**
     * API key.
     * 
     * @var string
     */
    private $apiKey;
    
    
    /**
     * 
     * @param string $subdomain Subdomain of whitemail client account.
     * @param string $apiKey API key.
     * 
     * @return self
     */
    public function __construct($apiUrl, $apiKey) {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }
    
    
    public function identify($data) {
        $url = $this->apiUrl . "/subscriber";
        $method = 'POST';        
        $result = $this->doRequest($url, $method, $data);
                        
        return $result;
    }
    
    public function track($data) {
        $url = $this->apiUrl . "/subscriber/track";
        
        $method = 'POST';
        
        $result = $this->doRequest($url, $method, $data);
        
        return $result;
    }
    
    /**
     * Send an http(s) request over curl.
     * 
     * @param string $url The url
     * @param string $method GET/POST, default is GET
     * @param array  $data An associative array with the data to pass to the server
     * @throws WhitemailApiException
     * 
     * @return array An associative array containing the parsed JSON response from the server.
     */
    protected function doRequest($url, $method = self::METHOD_GET, $data = []) {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
            CURLOPT_POST => (($method == self::METHOD_POST) ? 1: 0)
        );
        
        $data['apikey'] = $this->apiKey;
        
        $paramString = http_build_query($data);
        

        if ($method == self::METHOD_POST) {
            //pass params as option
            $options[CURLOPT_POSTFIELDS] = $paramString;
        } else {
            //Append params to url
            $url .= '?' + $paramString;
        }
        
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        
        try {
            $result = curl_exec($ch);
            $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            $error = curl_error($ch);
            
            if ($result === false || $error || $httpStatus != '200')  {
                throw new WhitemailApiException('Http Status: ' . $httpStatus . ' - ' . $result);
            }
            
            $data = json_decode($result, true);
            
            if (!is_array($data)) {
                throw new WhitemailApiException('Server retunred invalid output: ' + $result);
            }
            
        } finally {
            curl_close($ch);
        }
        
        return $data;
    }
}

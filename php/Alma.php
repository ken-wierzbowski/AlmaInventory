<?php
// Author: Terry Brady, Georgetown University Libraries
class Alma {
    public $alma_apikey;
    public $alma_apiurl;
    public $alma_inst;
    
    function __construct() {
        $configpath = parse_ini_file("Alma.prop", false);
        $proppath = $configpath["proppath"];
        $sconfig = parse_ini_file($proppath, false);
        $this->alma_apikey = $sconfig["ALMA_APIKEY"];
    }

    function getApiKey() {
        return $this->alma_apikey;
    }

    function getRequest($param) {
        if (isset($param["apipath"])) {
            $apipath = $param["apipath"];
            unset($param["apipath"]);
            $param["apikey"] = $this->getApiKey();
            $url = "{$apipath}?" . http_build_query($param);
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json'
            ));
            
            // SSL CERTIFICATE VERIFICATION WITH CA BUNDLE
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/cacert.pem');
            curl_setopt($ch, CURLOPT_CAPATH, __DIR__);
            // END SSL CONFIG
            
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $jsonstr = curl_exec($ch);

            if (curl_errno($ch)) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'error' => curl_error($ch),
                    'errorsExist' => true
                ]);
                curl_close($ch);
                return;
            }

            curl_close($ch);
            echo $jsonstr;
        }
        echo "";
    }
}
?>
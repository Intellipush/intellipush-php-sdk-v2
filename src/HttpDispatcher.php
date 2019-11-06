<?php
/**
* Copyright 2015 Intellipush AS.
*
* You are hereby granted a non-exclusive, worldwide, royalty-free license to
* use, copy, modify, and distribute this software in source code or binary
* form for use in connection with the web services and APIs provided by
* Intellipush.
*
* As with any software that integrates with the Intellipush platform, your use
* of this software is subject to the Intellipush terms 
* here [https://www.intellipush.com/terms/]. This copyright notice
* shall be included in all copies or substantial portions of the software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
* THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
* FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
* DEALINGS IN THE SOFTWARE.
*
*/

namespace Intellipush;

class HttpDispatcher {

    protected $config;

    public function __construct(Config $config) {
        $this->config = $config;
    }

    protected function _encrypt($params) {

        return base64_encode(trim(openssl_encrypt(
            json_encode($params),
            'AES-256-CFB',
            $this->config->apiSecret,
            $options=OPENSSL_RAW_DATA,
            substr(md5($this->config->apiSecret), 0, openssl_cipher_iv_length('AES-256-CFB') )
        )));

        /*return base64_encode(mcrypt_encrypt(
            MCRYPT_RIJNDAEL_256,
            $this->config->apiSecret,
            json_encode($params), MCRYPT_MODE_CBC,
            $this->config->apiSecret
        ));*/
    }

    public function post($url, $params) {

        // Removing all 'null' values to reduce parameters sendt.
        foreach ($params as $key => $value) {
            if ($value == null) {
                unset( $params[$key] );
            }
        }

        $params['api_secret'] = $this->config->apiSecret;
        $params = 'enc_request=' . urlencode($this->_encrypt($params)) . '&appID=' . $this->config->appId . '&v=3.0&s=php';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config->endpoint . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Cert'. DIRECTORY_SEPARATOR .'ip_gd_bundle.crt');
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST , 2);
        curl_setopt($ch, CURLOPT_SSLVERSION, 0 );

        $response = new Response();
        $response->rawResponse = curl_exec($ch);
        $response->httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response->parse();
        curl_close($ch);  //If the session option is not set, close the session.
        return $response;
    }
}
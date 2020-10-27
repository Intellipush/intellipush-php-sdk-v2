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

    public function post($url, $params) {

        // Setting required parameters for all requests
        $params['api_secret'] = $this->config->apiSecret;
        $params['appID'] = $this->config->appId;
        $params['t'] = time();
        $params['v'] = '4.0'; // Version for 
        $params['s'] = 'php'; // SDK version 

        // Removing all 'null' values to reduce parameters sendt.
        foreach ($params as $key => $value) {
            if ($value == null) {
                unset( $params[$key] );
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config->endpoint . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = new Response();
        $response->rawResponse = curl_exec($ch);
        $response->httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response->parse();
        curl_close($ch);  //If the session option is not set, close the session.

        return $response;
    }
}
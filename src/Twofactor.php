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


use Intellipush\Request\IRequest,
    Intellipush\HttpDispatcher,
    Intellipush\Config;

class Twofactor implements IRequest {

    protected $twofactor = array(
        'contrycode' => null,
        'phonenumber' => null,
        'message_p1' => null,
        'message_p2' => null,
        'code' => ''

    );

    public function __construct() {
    }

    public function countrycode($countrycode) {
        $this->twofactor['countrycode'] = $countrycode;
        return $this;
    }
    public function phonenumber($phonenumber) {
        $this->twofactor['phonenumber'] = $phonenumber;
        return $this;
    }

    public function message_p1($message_p1) {
        $this->twofactor['message_p1'] = $message_p1;
        return $this;
    }

    public function message_p2($message_p2) {
        $this->twofactor['message_p2'] = $message_p2;
        return $this;
    }

    public function code($code) {
        $this->twofactor['code'] = $code;
        return $this;
    }


    public function create(HttpDispatcher $dispatcher, Config $config) {
        return $dispatcher->post($config->twofactor['generate'], $this->twofactor);
    }

    public function read(HttpDispatcher $dispatcher, Config $config){
        return $dispatcher->post($config->twofactor['validate'], $this->twofactor);
    }

    public function update(HttpDispatcher $dispatcher, Config $config){
        // NONE
    }

    public function delete(HttpDispatcher $dispatcher, Config $config){
        // NONE
    }


    public function toArray(){

    }

}

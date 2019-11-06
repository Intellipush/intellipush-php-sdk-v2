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

namespace Intellipush\Notification;


use Intellipush\Notification,
    Intellipush\Contact\Filter,
    Intellipush\Request\IRequest,
    Intellipush\HttpDispatcher,
    Intellipush\Config;

class Sms extends Notification {

    protected $receivers;

    public function __construct($message = null, $receivercountrycode = null, $receiverphonenumber = null, $when = null) {
        $this->notification['method'] = 'sms';
        $this->message($message);

        $receiver = array ();
        $receiver[0] = $receivercountrycode;
        $receiver[1] = $receiverphonenumber;
        $receivers = array($receiver);

        $this->receivers($receivers);
        if(!is_null($when)) {
            $when = ($when instanceof \DateTime) ? $when : new \DateTime($when);
            $this->when($when);
        }
    }

    public function build(){
        if (!empty($this->receivers)) {
            $this->notification['contactlist_filter'] = new Filter();
            $this->notification['single_target_countrycode'] = trim($this->receivers[0][0]);
            $this->notification['single_target'] = trim($this->receivers[0][1]);
            $data = $this->toArray();
            if(count($this->receivers) > 1) {
                $data = array('batch' => array());
                foreach($this->receivers as $receiver) {
                    $this->notification['single_target_countrycode'] = trim($receiver[0]);
                    $this->notification['single_target'] = trim($receiver[1]);
                    $data['batch'][] = $this->toArray();
                }
            }
        } else {
            $data = $this->toArray();
        }
        return $data;
    }

    public function message($message) {
        $this->notification['text_message'] = $message;
        return $this;
    }

/*
    public function getMessage() {
        return $this->notification['text_message'];
    }

    public function getSender() {
        return $this->notification['sender_name'];
    }   
*/

    public function create(HttpDispatcher $dispatcher, Config $config) {
        $data = $this->build();
        if (!empty($data['batch'])){
            $url = $config->notification['createBatch'];   
        } else {
            $url = $config->notification['create'];
        }
        return $dispatcher->post($url, $data);
    }

}

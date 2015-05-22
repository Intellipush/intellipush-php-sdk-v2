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

class Batch extends Notification {

    protected $batch;

    public function __construct() {
        $this->batch['batch'] = array();
    }


    public function add($notification) {
        $notification = $notification->build();

        if ( !empty( $notification['batch'] ) ) {
            foreach ($notification['batch'] as $notification ){
                $this->batch['batch'][] = $notification;
            }
        } else {
            $this->batch['batch'][] = $notification;
        }

        return $this;
    }

    public function create(HttpDispatcher $dispatcher, Config $config) {
        return $dispatcher->post($config->notification['createBatch'], $this->batch);
    }

}

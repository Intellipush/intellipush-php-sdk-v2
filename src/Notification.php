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
    Intellipush\Request\Exception,
    DateTime;

abstract class Notification implements IRequest {

    protected $notification = array(
        'notification_id' => null,
        'html_message' => null,
        'text_message' => null,
        'date' => 'now',
        'time' => 'now',
        'method' => null,
        'repeat' => null,
        'single_target_countrycode' => null,
        'single_target' => null,
        'contact_id' => null,
        'contactlist_id' => null,
        'contactlist_filter' => null,
        'two_way_session' => false
    );

    protected $receivers = array();

    public function id($id) {
        $this->notification['notification_id'] = $id;
        $this->urlsegment = 'get';
        return $this;
    }


	public function receivers(array $receivers) {
		$this->receivers = $receivers;
		return $this;
	}

    public function when(DateTime $date) {
        $this->notification['date'] = $date->format('Y-m-d');
        $this->notification['time'] = $date->format('H:i');
        return $this;
    }

    public function htmlMessage($message) {
        $this->notification['html_message'] = $message;
        return $this;
    }

    public function repeat($repeat = '') {
        $this->notification['repeat'] = $repeat;
        return $this;
    }

    public function contact($id) {
        $this->notification['contact_id'] = $id;
        return $this;
    }

    public function contactlist($id) {
        $this->notification['contactlist_id'] = $id;
        return $this;
    }

    public function contactlistFilter($filter) {
        $this->notification['contactlist_filter'] = $filter;
        return $this;
    }

    public function sendt ($sendt){
        if ($sendt == true){
            $this->notification['sendt'] = $sendt;
        }
    }

    public function received ($received){
        if ($received == true){
            $this->notification['received'] = $received;
        }
    }

    public function page ($page){
        $this->notification['page'] = $page;
    }
    
    public function items ($items){
        $this->notification['items'] = $items;
    }

    public function toArray(){
        return $this->notification;
    }


    public function send(HttpDispatcher $dispatcher, Config $config) {
        return $dispatcher->post($config->notification[$this->urlsegment], $this->notification);
    }

    public function read(HttpDispatcher $dispatcher, Config $config){
        if (!empty($this->notification['sendt'])){
            $this->urlsegment = 'getSent';
        }
        return $dispatcher->post($config->notification[$this->urlsegment], $this->notification);
    }

    public function update(HttpDispatcher $dispatcher, Config $config){
        $this->urlsegment = 'update';
        return $dispatcher->post($config->notification[$this->urlsegment], $this->notification);
    }

    public function delete(HttpDispatcher $dispatcher, Config $config){
        $this->urlsegment = 'delete';
        return $dispatcher->post($config->notification[$this->urlsegment], $this->notification);
    }

}

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
    Intellipush\Contact\Filter,
    Intellipush\HttpDispatcher,
    Intellipush\Config;

class Contactlist implements IRequest {

    protected $contactlist = array(
        'contactlist_id' => null,
        'contactlist_name' => null,
        'page' => null,
        'items' => null,
        'query' => null,
        'contactlist_filter' => null,
        'amount' => null

    );

    public function __construct($name_id = null) {
        if (is_int($name_id)) {
            $this->getContactlist($name_id);
        } else {
            $this->createContactlist($name_id);
        }
    }

    /**
     * Basic function for creating a simple contact
     *
     * @param string $name
     * @param string $phone
     * @param string $email
     * @return mixed
     */
    public function createContactlist($name) {
        $this->contactlist['contactlist_name'] = $name;
        $this->urlsegment = 'create';
    }

    public function updateContactlist($name, $id) {
        $this->contactlist['contactlist_name'] = $name;
        $this->contactlist['contactlist_id'] = $id;
        $this->urlsegment = 'update';
    }

    public function getContactlist($id) {
        $this->contactlist = array();
        $this->contactlist['contactlist_id'] = $id;
        $this->urlsegment = 'get';
    }


    public function getContactlists($items = 20, $page = 1, $query = '') {
        $this->contactlist = array();
        $this->contactlist['items'] = $items;
        $this->contactlist['page'] = $page;
        $this->contactlist['query'] = $query;
        $this->urlsegment = 'getContactlists';
    }


    public function id($id) {
        $this->contactlist['contactlist_id'] = $id;
        $this->urlsegment = 'get';
        return $this;
    }

    public function name($name) {
        $this->contactlist['contactlist_name'] = $name;
        return $this;
    }

    public function items($items) {
        $this->contactlist['items'] = $items;
        return $this;
    }

    public function page($page) {
        $this->contactlist['page'] = $page;
        return $this;
    }

    public function query($query) {
        $this->contactlist['query'] = $query;
        return $this;
    }

    public function amount($amount) {
        $this->contactlist['amount'] = $amount;
        return $this;
    }

    public function notInContactlist($bool) {
        $this->contactlist['not_in'] = $bool;
        return $this;
    }
    public function contactId($contactId) {
        $this->contactlist['contact_id'] = $contactId;
        return $this;
    }

    public function contactFilter($contactFilter) {
        $this->contactlist['contactlist_filter'] = $contactFilter;
        return $this;
    }

    private function setProperReadUrlSegment() {
        if (!empty($this->contactlist['items']) && !empty($this->contactlist['page'])){
            $this->urlsegment = 'getContactlists';
            if (!empty($this->contactlist['contactlist_id'])){
                $this->urlsegment = 'getContacts';
            }
        } else {
            $this->urlsegment = 'get';
        }
        if (!empty($this->contactlist['amount'])){
            $this->urlsegment = 'getContactsAmount';
        }
        if (!empty($this->contactlist['not_in'])){
            $this->urlsegment = 'getContactsNotIn';   
        }
    }


    public function create(HttpDispatcher $dispatcher, Config $config) {
        if ( !empty($this->contactlist['contact_id']) ) {
            $this->urlsegment = 'addContact';
        }
        return $dispatcher->post($config->contactlist[$this->urlsegment], $this->contactlist);
    }

    public function read(HttpDispatcher $dispatcher, Config $config){
        $this->setProperReadUrlSegment();
        return $dispatcher->post($config->contactlist[$this->urlsegment], $this->contactlist);
    }

    public function update(HttpDispatcher $dispatcher, Config $config){
        $this->urlsegment = 'update';
        return $dispatcher->post($config->contactlist[$this->urlsegment], $this->contactlist);
    }

    public function delete(HttpDispatcher $dispatcher, Config $config){
        if ( !empty($this->contactlist['contact_id']) ) {
            $this->urlsegment = 'removeContact';
        } else {
            $this->urlsegment = 'delete';
        }
        return $dispatcher->post($config->contactlist[$this->urlsegment], $this->contactlist);
    }


    public function toArray(){

    }

    public function validate() {

    }
}

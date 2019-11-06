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

class Contact implements IRequest {

    protected $contact = array(
        'contact_id' => null,
        'name' => null,
        'company' => null,
        'dob' => null,
        'sex' => null,
        'zipcode' => null,
        'country' => null,
        'countrycode' => null,
        'phonenumber' => null,
        'email' => null,
        'facebook' => null,
        'twitter' => null,
        'param1' => null,
        'param2' => null,
        'param3' => null,
        'order' => 'name',
        'sort' => 'ASC'
    );

    public function __construct($name_id = null, $countrycode = null, $phonenumber = null, $email = null) {
        if (is_int($name_id)) {
            $this->getContact($name_id);
        } else {
            $this->createContact($name_id, $countrycode, $phonenumber, $email);
        }
    }

    /**
     * Basic function for creating a simple contact
     *
     * @param string $name
     * @param string $countrycode
     * @param string $phonenumber
     * @param string $email
     * @return mixed
     */
    public function createContact($name, $countrycode = null, $phonenumber = null, $email = null) {
        $this->contact['name'] = $name;
        $this->contact['countrycode'] = $countrycode;
        $this->contact['phonenumber'] = $phonenumber;
        $this->contact['email'] = $email;
        $this->urlsegment = 'create';
    }

    public function getContact($id) {
        $this->contact = array();
        $this->contact['contact_id'] = $id;
        $this->urlsegment = 'get';
    }


    public function getContacts($items = 20, $page = 1, $query = '', $filter = null) {
        $this->contact = array();
        $this->contact['items'] = $items;
        $this->contact['page'] = $page;
        $this->contact['query'] = $query;
        $this->contact['contactFilter'] = $filter;
        $this->urlsegment = 'getContacts';
    }


    public function id($id) {
        $this->contact['contact_id'] = $id;
        $this->urlsegment = 'get';
        return $this;
    }

    public function name($name) {
        $this->contact['name'] = $name;
        return $this;
    }

    public function company($company) {
        $this->contact['company'] = $company;
        return $this;
    }

    public function dob($dob) {
        $this->contact['dob'] = $dob;
        return $this;
    }

    public function sex($sex) {
        $this->contact['sex'] = $sex;
        return $this;
    }

    public function zipcode($zipcode) {
        $this->contact['zipcode'] = $zipcode;
        return $this;
    }

    public function country($country) {
        $this->contact['country'] = $country;
        return $this;
    }

    public function countrycode($countrycode) {
        if(is_string($countrycode) !== true){
            die('Countrycode has to be a string');
        }
        $this->contact['countrycode'] = $countrycode;
        return $this;
    }

    public function phone($phonenumber) {
        if(is_string($phonenumber) !== true){
            die('Countrycode has to be a string');
        }
        $this->contact['phonenumber'] = (string) $phonenumber;
        return $this;
    }

    public function email($email) {
        $this->contact['email'] = $email;
        return $this;
    }

    public function facebook($facebook) {
        $this->contact['facebook'] = (string) $facebook;
        return $this;
    }

    public function twitter($twitter) {
        $this->contact['twitter'] = $twitter;
        return $this;
    }

    public function param1($param1) {
        $this->contact['param1'] = $param1;
        return $this;
    }

    public function param2($param2) {
        $this->contact['param2'] = $param2;
        return $this;
    }

    public function param3($param3) {
        $this->contact['param3'] = $param3;
        return $this;
    }

    public function items($items) {
        $this->contact['items'] = $items;
        return $this;
    }

    public function page($page) {
        $this->contact['page'] = $page;
        return $this;
    }

    public function query($query) {
        $this->contact['query'] = $query;
        return $this;
    }

    public function order($order) {
        $this->contact['order'] = $order;
        return $this;
    }

    public function sort($sort) {
        $this->contact['sort'] = $sort;
        return $this;
    }

    public function contactFilter($contactFilter) {
        $this->contact['contactFilter'] = $contactFilter;
        return $this;
    }

    private function setProperReadUrlSegment() {
        if (!empty($this->contact['items']) && !empty($this->contact['page'])){
            $this->urlsegment = 'getContacts';
        } else {
            if (!empty($this->contact['countrycode']) && !empty($this->contact['phonenumber'])){
                $this->urlsegment = 'getByPhonenumber';
            } else {
                $this->urlsegment = 'get';
            }
        }
    }


    public function build(){
        $data = $this->toArray();
        return $data;
    }

    public function create(HttpDispatcher $dispatcher, Config $config) {
        return $dispatcher->post($config->contact[$this->urlsegment], $this->contact);
    }

    public function read(HttpDispatcher $dispatcher, Config $config){
        $this->setProperReadUrlSegment();
        return $dispatcher->post($config->contact[$this->urlsegment], $this->contact);
    }

    public function update(HttpDispatcher $dispatcher, Config $config){
        $this->urlsegment = 'update';
        return $dispatcher->post($config->contact[$this->urlsegment], $this->contact);
    }

    public function delete(HttpDispatcher $dispatcher, Config $config){
        $this->urlsegment = 'delete';
        return $dispatcher->post($config->contact[$this->urlsegment], $this->contact);
    }


    public function toArray(){
        return $this->contact;
    }

    public function validate() {

    }
}

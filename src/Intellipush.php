<?php namespace Intellipush;
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


use Intellipush\Environment,
    Intellipush\Notification,
    Intellipush\Contact,
    Intellipush\Contactlist,
    Intellipush\User,
    Intellipush\Statistics,
    Intellipush\Notification\Sms,
    Intellipush\Request\IRequest,
    Intellipush\HttpDispatcher,
    Intellipush\Config;

class Intellipush {

    protected $config;

    protected $httpDispatcher;

    public function __construct($appId, $apiSecret) {

        //Validate the environment before proceeding
        $environment = new Environment();
        $environment->check();

        $this->config = new Config();
        $this->config->appId = $appId;
        $this->config->apiSecret = $apiSecret;

        $this->httpDispatcher = new HttpDispatcher($this->config);
    }

    static public function auth($appId, $apiSecret) {
        return new \Intellipush\Intellipush($appId, $apiSecret);
    }


    public function user() {
        return $this->read(new User());
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
    public function contact($name, $countrycode = null, $phonenumber = null, $email = null) {
        return $this->create(new Contact($name, $countrycode, $phonenumber, $email));
    }

    /**
     * Helper method to help get contact
     *
     * @param int $id
     * @return mixed
     */
    public function getContact($id) {
        return $this->read(new Contact($id));
    }

    /**
     * Helper method to help getting contacts simplistic
     *
     * @param int $items
     * @param int $page
     * @return mixed
     */
    public function getContacts($items, $page) {
        $contact = new Contact();
        $contact->getContacts($items, $page);
        return $this->read($contact);
    }


    /**
     * Helper method to help getting contacts simplistic
     *
     * @param int $items
     * @param int $page
     * @return mixed
     */
    public function deleteContact($id) {
        $contact = new Contact();
        $contact->id($id);
        return $this->delete($contact);
    }

    /**
     * Helper method to help creating contactlist simplistic
     *
     * @param string $name
     * @return mixed
     */
    public function contactlist($name) {
        return $this->create(new Contactlist($name));
    }

    /**
     * Helper method to help get contactlist simplistic
     *
     * @param int $id
     * @return mixed
     */
    public function getContactlist($id) {
        return $this->read(new Contactlist($id));
    }

    /**
     * Helper method to help getting contactlists simplistic
     *
     * @param int $items
     * @param int $page
     * @return mixed
     */
    public function getContactlists($items, $page) {
        $contactlist = new Contactlist();
        $contactlist->getContactlists($items, $page);
        return $this->read($contactlist);
    }

    /**
     * Helper method to help getting user statistics 
     *
     * @return mixed
     */
    public function getStatistics() {
        return $this->read(new Statistics());
    }

    /**
     * Helper method to help sending simplistic sms
     *
     * @param string $message
     * @param string|array $receivers
     * @param string $sender
     * @return mixed
     */
    public function sms($message, $receivers, $sender = null) {
        return $this->create(new Sms($message, $receivers, $sender));
    }


    public function create(IRequest $request) {
        return $request->create($this->httpDispatcher, $this->config);
    }

    public function read(IRequest $request) {
        return $request->read($this->httpDispatcher, $this->config);
    }

    public function update(IRequest $request) {
        return $request->update($this->httpDispatcher, $this->config);
    }

    public function delete(IRequest $request) {
        return $request->delete($this->httpDispatcher, $this->config);
    }
}

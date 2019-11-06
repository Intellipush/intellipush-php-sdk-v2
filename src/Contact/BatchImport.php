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

namespace Intellipush\Contact;


use Intellipush\Contact,
    Intellipush\Contact\Filter,
    Intellipush\Request\IRequest,
    Intellipush\HttpDispatcher,
    Intellipush\Config;

class BatchImport extends Contact {

    protected $batch;

    public function __construct() {
        $this->batch['batch'] = array();
        $this->batch['import_type'] = 'contactlist_new';
        $this->batch['contactlist_name'] = 'Import ' . date('d M Y H:i');
        $this->batch['contactlist_id'] = '';
    }

    public function add($contact) {
        $contact = $contact->build();
        foreach ($contact as $k => $v){
            if ( !isset($v) ) {
                unset ($contact[$k]);
            }
        }
        $this->batch['batch'][] = $contact;

        return $this;
    }

    public function importToContactlistId($contactlist_id) {
        $this->batch['import_type'] = 'contactlist_existing';
        $this->batch['contactlist_id'] = $contactlist_id;
        return $this;
    }
    public function importToNewContactlist($contactlist_name) {
        $this->batch['import_type'] = 'contactlist_new';
        $this->batch['contactlist_name'] = $contactlist_name;
        return $this;
    }


    public function create(HttpDispatcher $dispatcher, Config $config) {
        return $dispatcher->post($config->contact['createBatch'], $this->batch);
    }

}

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


class Config {

    public $endpoint = 'https://www.intellipush.com/api/';

    public $user          = array();
    public $notification  = array();
    public $contact       = array();
    public $contactlist   = array();
    public $url           = array();

    public function __construct (){
        $this->user['get']                     = 'user';

        $this->notification['create']          = 'notification/createNotification';
        $this->notification['createBatch']     = 'notification/createBatch';
        $this->notification['update']          = 'notification/updateNotification';
        $this->notification['delete']          = 'notification/deleteNotification';
        $this->notification['get']             = 'notification/getNotification';
        $this->notification['getUnsent']       = 'notification/getUnsendtNotifications';
        $this->notification['getSent']         = 'notification/getSendtNotifications';
        $this->notification['getReceived']     = 'notification/getReceivedNotifications';
        $this->notification['status']          = 'notification/getNotificationStatus';

        $this->contact['create']               = 'contact/createContact';
        $this->contact['createBatch']          = 'contact/batchCreateContacts';
        $this->contact['update']               = 'contact/updateContact';
        $this->contact['delete']               = 'contact/deleteContact';
        $this->contact['get']                  = 'contact/getContact';
        $this->contact['getByPhonenumber']     = 'contact/getContactByPhonenumber';
        $this->contact['getContacts']          = 'contact/getContacts';

        $this->contactlist['create']           = 'contactlist/createContactlist';
        $this->contactlist['update']           = 'contactlist/updateContactlist';
        $this->contactlist['delete']           = 'contactlist/deleteContactlist';
        $this->contactlist['get']              = 'contactlist/getContactlist';
        $this->contactlist['getContactlists']  = 'contactlist/getContactlists';
        $this->contactlist['getContacts']      = 'contactlist/getContactsInContactlist';
        $this->contactlist['getContactsAmount']= 'contactlist/getNumberOfFilteredContactsInContactlist';
        $this->contactlist['getContactsNotIn'] = 'contactlist/searchContactsNotInContactlist';
        $this->contactlist['addContact']       = 'contactlist/addContactToContactlist';
        $this->contactlist['removeContact']    = 'contactlist/removeContactFromContactlist';

        $this->url['create']                   = 'url/generateShortUrl';
        $this->url['createChild']              = 'url/generateChildUrl';
        $this->url['get']                      = 'url/getUrlDetailsById';
        $this->url['getByShorturl']            = 'url/getDetailsByShortUrl';
        $this->url['getAll']                   = 'url/getAll';

        $this->statistics['get']               = 'statistics';

        $this->twofactor['generate']           = 'twofactor/send2FaCode';
        $this->twofactor['validate']           = 'twofactor/check2FaCode';


    }

} 
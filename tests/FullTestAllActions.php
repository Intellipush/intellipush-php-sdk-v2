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


/**
        RENDERING HTML START
**/
include_once 'html/start.html';


/**
        LOADING SDK AND SETTING UP INTELLIPUSH OBJECT
**/

use Intellipush\Intellipush;
use Intellipush\Notification\Sms;
use Intellipush\Notification\Batch;
use Intellipush\Notification\Status;
use Intellipush\Notification\Notifications;
use Intellipush\Contact;
use Intellipush\Contact\Filter;

use Intellipush\Contactlist;
use Intellipush\User;
use Intellipush\Url;

$applicationPath = __DIR__ . '/../../../../';

require_once $applicationPath . 'vendor/autoload.php';


// ###########################################################################
// ------------------------        WARNING

// THIS SCRIPT WILL SEND A FEW MEESAGES INSTANTLY, AND A FEW MORE WITHIN THE NEST 20 MINUTES.

// ------------------------        WARNING
// ###########################################################################

$key = 'xxxxxxx';
$secret = 'zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz';

$countrycodeToTest = '0047';
$phonenumberToTest = 'yyyyyyyy';

$intellipush = new Intellipush($key, $secret);


date_default_timezone_set('CET'); // CHANGE TO YOUR TIMEZONE


/**
        CREATING SMS
**/

$sms = new Sms();

$sms->receivers(
    array(
        array($countrycodeToTest,$phonenumberToTest),
        array($countrycodeToTest,$phonenumberToTest)
    )
)
->message('Hei hei! :)')
->when(new \DateTime('+10 minutes'));

$response = $intellipush->create($sms);

if ($response->httpStatusCode != 200){
    echo '<h3>You have to whitelist your IP, and doublecheck your API key and security credentials.</h3>';
    die();
}

renderSectionOutput($response, 'Creating SMS');

$notification_id = $response->response[0]->data->id;



/**
        GETTING NOTIFICATION STATUS
**/

$status = new Status();

$status->id([$notification_id]);

$response = $intellipush->read($status);

renderSectionOutput($response, 'Getting Notification Status');


/**
        CREATING SMS WITH NO MESSAGE
**/

$sms = new Sms();

$sms->receivers(
    array(
        array($countrycodeToTest,$phonenumberToTest)
    )
)
->when(new \DateTime('+20 minutes'));

$response = $intellipush->create($sms);

renderSectionOutput($response, 'Creating SMS with no message', true, 1005);



/**
        CREATING SMS WITH NO SPECIFIED TIME
**/

$sms = new Sms();

$sms->receivers(
    array(
        array($countrycodeToTest,$phonenumberToTest)
    )
)
->message('Hei hei! :)');

$response = $intellipush->create($sms);

renderSectionOutput($response, 'Creating SMS with no set time');



/**
        CREATING SMS WITH REPEAT
**/

$sms = new Sms();

$sms->receivers(
    array(
        array($countrycodeToTest,$phonenumberToTest)
    )
)
->message ('Hei hei! :)')
->when(new \DateTime('+10 minutes'))
->repeat ('daily');

$response = $intellipush->create($sms);

renderSectionOutput($response, 'Creating SMS with repeat');

$repeat_notification_id = $response->response->data->id;

/**
        CLEANING UP AND DELETING REPEATING MESSAGE
**/
$sms = new Sms();

$sms->id( $repeat_notification_id );

$response = $intellipush->delete($sms);

renderSectionOutput($response, 'Deleting SMS with repeat');

/**
        CREATING SMS WITH REPEAT NO DATE
**/

$sms = new Sms();

$sms->receivers(
    array(
        array($countrycodeToTest,$phonenumberToTest)
    )
)
->message ('Hei hei! :)')
->repeat ('daily');

$response = $intellipush->create($sms);

renderSectionOutput($response, 'Creating SMS with repeat no date', true, 407);





/**
        GETTING SMS
**/

$sms = new Sms();

$sms->id($notification_id);

$response = $intellipush->read($sms);


renderSectionOutput($response, 'Reading SMS');




/**
        GETTING NOTIFICATION YOU DO NOT OWN
**/

$sms = new Sms();

$sms->id(1234567890);

$response = $intellipush->read($sms);


renderSectionOutput($response, 'Reading notification you do not own', true, 401);




/**
        UPDATE SMS
**/

$sms = new Sms();

$sms->id( $notification_id )
->message('Hello! This is an updated notification!')
->repeat()
->when(new \DateTime('+50 minutes'));

$response = $intellipush->update($sms);

renderSectionOutput($response, 'Updating SMS');




/**
        DELETE SMS
**/

$sms = new Sms();

$sms->id( $notification_id );

$response = $intellipush->delete($sms);

renderSectionOutput($response, 'Deleting SMS');




/**
        CREATE CONTACT
**/

$contact = new Contact();

$contact->name('Girly Girl')
->countrycode($countrycodeToTest)
->phone($phonenumberToTest)
->company('Intellipush')
->sex('female');


$response = $intellipush->create($contact);

$contact_id = $response->response->data->id;


renderSectionOutput($response, 'Creating Contact');



/**
        CREATE ANOTHER CONTACT
**/

$contact = new Contact();

$contact->name('Manly Man')
->countrycode($countrycodeToTest)
->phone($phonenumberToTest)
->company('Intellipush')
->sex('male');


$response = $intellipush->create($contact);

$contact_id2 = $response->response->data->id;


renderSectionOutput($response, 'Creating Another Contact');




/**
        READING CONTACT
**/

$contact = new Contact();

$contact->id($contact_id);


$response = $intellipush->read($contact);

renderSectionOutput($response, 'Reading Contact');




/**
        UPDATING CONTACT
**/

$contact = new Contact();

$contact->id($contact_id2)
->name('Updated Manly Man');


$response = $intellipush->update($contact);

renderSectionOutput($response, 'Updating Contact');




/**
        READING FEMALE CONTACTS
*/

$contact = new Contact();

$contactfilter = new Filter();
$contactfilter->sex('female');

$contact->items(20)->page(1)->query('')->contactFilter($contactfilter);

$response = $intellipush->read($contact);


renderSectionOutput($response, 'Reading female contacts');



/**
        CREATING CONTACTLIST
**/

$contactlist = new Contactlist();

$contactlist->name('newList');

$response = $intellipush->create($contactlist);

$contactlist_id = $response->response->data->id;

renderSectionOutput($response, 'Creating Contactlist');




/**
        READ CONTACTLIST
**/
$contactlist = new Contactlist();

$contactlist->id( $contactlist_id );

$response = $intellipush->read($contactlist);

renderSectionOutput($response, 'Reading Contactlist');




/**
        UPDATING CONTACTLIST
**/

$contactlist = new Contactlist();

$contactlist->id( $contactlist_id )->name('Advanced Contactlist');

$response = $intellipush->update($contactlist);

renderSectionOutput($response, 'Updating Contactlist');





/**
        GETTING CONTACTS IN CONTACTLIST 1
**/

$contactlist = new Contactlist();

$contactfilter = new Filter();
//$contactfilter->sex('female');
//$contactfilter->age('18,20,23,30-40,45');

$contactlist->id( $contactlist_id )->items('1')->page('1')->query('')->contactFilter($contactfilter);

$response = $intellipush->read($contactlist);

renderSectionOutput($response, 'Getting Contacts in Contactlist 1');




/**
        GETTING CONTACTS IN CONTACTLIST 2
**/

$contactlist = new Contactlist();

$contactfilter = new Filter();
$contactfilter->sex('female');

$contactlist->id( $contactlist_id )->items('2')->page('2')->query('')->contactFilter($contactfilter);

$response = $intellipush->read($contactlist);

renderSectionOutput($response, 'Getting Contacts in Contactlist 2');




/**
        GET NUMBER OF CONTACTS IN CONTACTLIST
**/

$contactlist = new Contactlist();

$contactfilter = new Filter();
//$contactfilter->sex('female');
//$contactfilter->age('18,20,23,30-40,45');

$contactlist->id ( $contactlist_id )->amount(true)->contactFilter($contactfilter);

$response = $intellipush->read($contactlist);

renderSectionOutput($response, 'Getting Number of Contacts in Contactlist');




/**
        SEARCH CONTACTS NOT IN CONTACTLIST
**/

$contactlist = new Contactlist();

$contactlist->id ( $contactlist_id )->items(2)->page(1)->query('Upd')->notInContactlist(true);

$response = $intellipush->read($contactlist);

renderSectionOutput($response, 'Search Contact not in Contactlist');




/**
        ADD CONTACT TO CONTACTLIST
**/

$contactlist = new Contactlist();

$contactlist->id ( $contactlist_id )->contactId( $contact_id );

$response = $intellipush->create($contactlist);

renderSectionOutput($response, 'Add Contact to Contactlist');






/**
        READ THE USER ACCOUNT
**/

$user = new User();

$response = $intellipush->read($user);


renderSectionOutput($response, 'Get User');





/**
        CREATING BATCH SUCCESS
**/



$batch = new Batch();

    $sms = new Sms();

    $sms->receivers(
        array(
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
        )
    )
    ->message('Hei hei! :)')
    ->when(new \DateTime('+20 minutes'));

$batch->add($sms);



    $sms = new Sms();

    $sms->receivers(
        array(
            array($countrycodeToTest,$phonenumberToTest)
        )
    )
    ->message('Hei på deg! :)')
    ->when(new \DateTime('+10 minutes'));

$batch->add($sms);


$response = $intellipush->create($batch);



renderSectionOutput($response, 'Creating Batch SMS 1');




/**
        CREATING BATCH SUCCESS 2
**/


$batch = new Batch();

$sms = new Sms();

$sms->receivers(
    array(
        array($countrycodeToTest,$phonenumberToTest),
        array($countrycodeToTest,$phonenumberToTest)
    )
)
->message('Første melding. Sendes til 2 telefonnummer')
->when(new \DateTime('+20 minutes')); // Må være i formatet 2013-04-20 22:15:00

$batch->add($sms);


$sms = new Sms();

$sms->receivers(
    array(
        array($countrycodeToTest,$phonenumberToTest),
        array($countrycodeToTest,$phonenumberToTest)
    )
)
//->repeat('daily')
->message('Andre melding. Sendes til 2 nye telefonnummer og kan gjentas hver dag.')
->when(new \DateTime('+20 minutes')); // Må være i formatet 2013-04-20 22:15:00

$batch->add($sms);

$response = $intellipush->create($batch);


renderSectionOutput($response, 'Creating Batch SMS 2');



/**
        CREATING BATCH TOO MANY
**/



$batch = new Batch();

    $sms = new Sms();

    $sms->receivers(
        array(
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest),
            array($countrycodeToTest,$phonenumberToTest)
        )
    )
    ->message('Hei hei! :)')
    ->when(new \DateTime('+20 minutes'));

$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);
$batch->add($sms);


    $sms = new Sms();

    $sms->receivers(
        array(
            array($countrycodeToTest,$phonenumberToTest)
        )
    )
    ->message('Hei på deg! :)')
    ->when(new \DateTime('+10 minutes'));

$batch->add($sms);


$response = $intellipush->create($batch);

renderSectionOutput($response, 'Creating Too many in Batch', true, 410);



/**
        GETTING UNSENDT NOTIFICATIONS
**/

$notifications = new Notifications();

$notifications->items(2)->page(1);

$response = $intellipush->read($notifications);

renderSectionOutput($response, 'Getting unsendt Notifications');





/**
        GETTING SENDT NOTIFICATIONS
**/

$notifications = new Notifications();

$notifications->items(2)->page(1)->sendt(true);

$response = $intellipush->read($notifications);

renderSectionOutput($response, 'Getting sendt Notifications');


/**
        GETTING RECEIVED NOTIFICATIONS
**/

$notifications = new Notifications();

$notifications->items(2)->page(1)->keyword('')->secondKeyword('')->received(true);

$response = $intellipush->read($notifications);

renderSectionOutput($response, 'Getting received Notifications');






/**
        CREATING SMS TO CONTACT WITH REPEAT
**/

$sms = new Sms();

$sms->message ('Hei hei! :)')
    ->contact($contact_id )
    ->when(new \DateTime('+10 minutes'))
    ->repeat ('daily');

$response = $intellipush->create($sms);

renderSectionOutput($response, 'Creating SMS to contact with repeat');

$repeat_notification_id2 = $response->response->data->id;

/**
        CLEANING UP AND DELETING SMS TO CONTACT WITH REPEATING MESSAGE
**/
$sms = new Sms();

$sms->id( $repeat_notification_id2 );

$response = $intellipush->delete($sms);

renderSectionOutput($response, 'Deleting SMS to contact with repeat');


/**
        CREATING SMS TO CONTACTLIST
**/

$sms = new Sms();

$sms->message ('Hei hei! :)')
    ->contactlist($contactlist_id );

$response = $intellipush->create($sms);

renderSectionOutput($response, 'Creating SMS to contactlist');


/**
        CREATING SMS TO CONTACTLIST WITH FILTER
**/

$sms = new Sms();

$contactfilter = new Filter();
$contactfilter->sex('female');

$sms->message ('Hei hei! :)')
->contactlist($contactlist_id )
->contactlistFilter($contactfilter);

$response = $intellipush->create($sms);

renderSectionOutput($response, 'Creating SMS to contactlist with filter');




/**
        REMOVE CONTACT FROM CONTACTLIST
**/

$contactlist = new Contactlist();

$contactlist->id ( $contactlist_id )->contactId( $contact_id );

$response = $intellipush->delete($contactlist);

renderSectionOutput($response, 'Remove Contact from Contactlist');





/**
        DELETING CONTACT
**/

$contact = new Contact();

$contact->id($contact_id);


$response = $intellipush->delete($contact);

renderSectionOutput($response, 'Deleting Contact');




/**
        DELETING CONTACTLIST
**/

$contactlist = new Contactlist();

$contactlist->id( $contactlist_id );

$response = $intellipush->delete($contactlist);

renderSectionOutput($response, 'Deleting Contactlist');




/**
        CREATING SHORT URL
**/

$url = new Url();

$url->longUrl("https://www.intellipush.com");
 
$response = $intellipush->create($url);

renderSectionOutput($response, 'Creating short URL');

$parentUrlId = $response->response->data->id;




/**
        CREATING CHILD SHORT URL
**/

$url = new Url();
$url->parentUrlId($parentUrlId); // You must have first created a parent short url to be able to reference it.
 
$target = []; 
$target["contact_id"] = ""; // Optional
$target["email"] = "";  // Optional
$target["countrycode"] = $countrycodeToTest; // Optional
$target["phonenumber"] = $phonenumberToTest; // Optional
 
$url->target($target); // Optional, and only if you want to be able to track a users 
 
$response = $intellipush->create($url);

$childUrlId = $response->response->data->id;

renderSectionOutput($response, 'Creating child short URL');




/**
        GET URL DETAILS
**/

$url = new Url();
$url->id($childUrlId); 

$response = $intellipush->read($url);

$childUrl = $response->response->data->short_url;

renderSectionOutput($response, 'Getting child URL details');




/**
        GET URL DETAILS
**/

$url = new Url();
 
$url->includeChildren(true); // Skip this if you only want to list parent URLs.

$target = []; 
$target["contact_id"] = ""; // Optional
$target["email"] = "";  // Optional
$target["countrycode"] = $countrycodeToTest; // Optional
$target["phonenumber"] = $phonenumberToTest; // Optional
 
$url->target($target);
 
 
$url->items(50);
$url->page(1);
 
 
$response = $intellipush->read($url);

renderSectionOutput($response, 'Getting all URLs to your phonenumber');




/**
        CREATING SMS WITH SHORT URL FOR YOU TO TEST FROM MOBILE
**/

$sms = new Sms();

$sms->receivers(
    array(
        array($countrycodeToTest,$phonenumberToTest)
    )
)
->message('Woah, check this: ' . $childUrl . ' <- This should lead you to https://www.intellipush.com.');

$response = $intellipush->create($sms);

renderSectionOutput($response, 'Creating SMS with short URL.');
















/**
        RENDERING HTML END
**/


function renderSectionOutput ($response, $name, $expectError = false, $expectedErrorID = 0){

$techname = preg_replace('/\s+/', '', $name);
$errorMessage = '';
if (is_array($response->success)){

    $error = false;
    foreach ( $response->success as $success ) {
        if ( $success != true){
            $error = true;
        } else {
            $error = false;
        }
    }
} else {
    if ( $response->success != true){
        $error = true;
    } else {
        $error = false;
    }
}

if ($expectError == true && $error == true) {
    if ($response->errorcode = $expectedErrorID){
        $error = false;
    }
}

if (empty($response->message) == true){
    $error = true;
    $errorMessage = 'Missing message';
}


if ($error == true){
        echo '              <button class="btn btn-danger btn-ip btn-lg" type="button" data-toggle="collapse" data-target="#collapse'.$techname.'" aria-expanded="false" aria-controls="collapse'.$techname.'">
  '.$name.'
</button>
';
    } else {
        echo '              <button class="btn btn-success btn-ip btn-lg" type="button" data-toggle="collapse" data-target="#collapse'.$techname.'" aria-expanded="false" aria-controls="collapse'.$techname.'">
  '.$name.'
</button>
';
}

echo '
<div class="collapse pull-left" id="collapse'.$techname.'">
  <div class="well">
    <pre>
';
if ($error == true) {
    echo '<br />';
    echo $errorMessage;
    echo '<br /><br /><br />';
}
print_r($response);
echo '
    </pre>
  </div>
</div>
';

/*
    echo '          </div>
';*/
    echo '          <script>updateProgressBar();</script>
';
if ($error == true){
    echo '          <script>updateErrorBar();</script>
';
} else {
    echo '          <script>updateSuccessBar();</script>
';
}


    ob_flush();
    flush();
    usleep(10000); // Waiting 10 Milliseconds

}

include_once 'html/end.html';
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


class Filter {

    public $age = '';
    public $sex = '';
    public $country = '';
    public $company = '';
    public $param1 = '';
    public $param2 = '';
    public $param3 = '';

    public function sex($value) {
    	$this->sex = $value;
    }

    public function age($value) {
    	$this->age = $value;
    }

    public function country($value) {
    	$this->country = $value;
    }

    public function company($value) {
    	$this->company = $value;
    }

    public function param1($value) {
    	$this->param1 = $value;
    }

    public function param2($value) {
    	$this->param2 = $value;
    }

    public function param3($value) {
    	$this->param2 = $value;
    }


} 
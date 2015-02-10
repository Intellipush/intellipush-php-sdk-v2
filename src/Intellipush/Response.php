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

class Response {

    public $httpStatusCode;
    public $rawResponse;
    public $response;

    public $id;

    #Error values
    public $errorId;
    public $success;
    public $message;

    public function parse() {
        $data = json_decode($this->rawResponse);
        if(json_last_error() === JSON_ERROR_NONE) {
            $this->errorId = $this->_extractValues('errorcode', $data);
            $this->success = $this->_extractValues('success', $data);
            $this->message = $this->_extractValues('status_message', $data);
            if (!empty ($data->data)){
                $this->id = $this->_extractValues('id', $data->data);
            }
            $this->response = $data;
        }
    }

    protected function _extractValues($key, $response) {
        $result = '';
        if(is_array($response)) {
            $result = array();
            foreach($response as $item) {
                if(isset($item->{$key})) {
                    $result[] = $item->{$key};
                }
            }
            return $result;
        }

        if(isset($response->{$key})) {
            return $response->{$key};
        }
        return null;
    }
} 
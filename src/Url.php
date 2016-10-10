<?php
/**
* Copyright 2016 Intellipush AS.
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
    Intellipush\Request\Exception;

class Url implements IRequest {

    protected $url = array(
        'url_id' => null,
        'long_url' => null,
        'short_url' => null,
        'parent_url_id' => null,
        'target' => null,
        'items' => '50',
        'page' => '1'
    );

    public function id($id) {
        $this->url['url_id'] = $id;
        $this->urlsegment = 'get';
        return $this;
    }



    public function parentUrlId($parent_url_id) {
        $this->url['parent_url_id'] = $parent_url_id;
        return $this;
    }

    public function includeChildren($include_children) {
        $this->url['include_children'] = $include_children;
        return $this;
    }

    public function target($target) {
        $this->url['target'] = $target;
        return $this;
    }

    public function longUrl ($long_url){
        $this->url['long_url'] = $long_url;
        $this->urlsegment = 'get';
        return $this;
    }

    public function shortUrl ($short_url){
        $this->url['short_url'] = $short_url;
        return $this;
    }

    public function page ($page){
        $this->url['page'] = $page;
        return $this;
    }
    
    public function items ($items){
        $this->url['items'] = $items;
        return $this;
    }

    public function toArray(){
        return $this->url;
    }

    public function create(HttpDispatcher $dispatcher, Config $config) {
        $this->urlsegment = 'create';
        if (!empty($this->url['parent_url_id'])){
            $this->urlsegment = 'createChild';
        }

        return $dispatcher->post($config->url[$this->urlsegment], $this->url);
    }

    public function read(HttpDispatcher $dispatcher, Config $config){
        $this->urlsegment = 'getAll';

        if (!empty($this->url['url_id'])){
            $this->urlsegment = 'get';
        }
        if (!empty($this->url['short_url'])){
            $this->urlsegment = 'getByShorturl';
        }
        
        return $dispatcher->post($config->url[$this->urlsegment], $this->url);
    }

    public function update(HttpDispatcher $dispatcher, Config $config){
        $this->urlsegment = 'update';
        return $dispatcher->post($config->url[$this->urlsegment], $this->url);
    }

    public function delete(HttpDispatcher $dispatcher, Config $config){
        $this->urlsegment = 'delete';
        return $dispatcher->post($config->url[$this->urlsegment], $this->url);
    }
}
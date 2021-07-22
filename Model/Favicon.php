<?php

namespace Inwebo\Favicon\Model;

class Favicon
{
    public string $url;
    public string $mimeType;

    /**
     * Html Base 64 image representation
     *
     * @var string https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/Data_URIs
     */
    public string $data;

    public function getSrc(): string
    {
        /**
        $image = 'http://images.itracki.com/2011/06/favicon.png';
        $imageData = base64_encode(file_get_contents($image));
        $src = 'data: '.mime_content_type($image).';base64,'.$imageData;
        echo '<img src="'.$src.'">';
         **/

        return sprintf('data:%s;base64,%s', $this->mimeType, $this->data);
    }
}
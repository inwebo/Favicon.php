<?php

namespace Inwebo\Favicon\Model;


class FaviconFactory
{
    protected string $domain;

    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }

    protected function getMimeType(string $url): string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);

        return curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    }

    public function getData(string $url): string
    {
        return base64_encode(file_get_contents($url));
    }

    public function build(\DOMNode $node): ?Favicon
    {
        $url = $node->getAttribute('href');

        $urlResolver = new UrlResolver($this->domain, $url);
        $resolved = $urlResolver->resolve();

        $mime = $this->getMimeType($resolved);
        $data = $this->getData($resolved);

        $favicon = new Favicon();
        $favicon->mimeType = $mime;
        $favicon->data     = $data;
        $favicon->url      = $url;

        return $favicon;
    }
}
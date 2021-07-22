<?php

namespace Inwebo\Favicon\Model;

use \DOMNode;

class UrlResolver
{
    protected string   $domain;
    protected string   $iconUrl;
    protected ?DOMNode $baseHref;

    public function setBaseHref(?DOMNode $baseHref): void
    {
        $this->baseHref = $baseHref;
    }

    public function __construct(string $domain, string $iconUrl)
    {
        $this->domain   = $domain;
        $this->iconUrl  = $iconUrl;

        $this->baseHref = null;
    }

    protected function isAbsoluteUrl(): bool
    {
        return (stripos($this->iconUrl, 'http://') === 0) || (stripos($this->iconUrl, 'https://') === 0);
    }

    protected function isAbsoluteWithRelativeSchemeUrl(): bool
    {
        return (stripos($this->iconUrl, '//') === 0);
    }

    protected function isAbsolutPathUrl(): bool
    {
        return !$this->isAbsoluteUrl() && !$this->isAbsoluteWithRelativeSchemeUrl() && !$this->isRelativeUrl();
    }

    protected function isRelativeUrl(): bool
    {
        return (stripos($this->iconUrl, '../') === 0);
    }

    public function resolve(): ?string
    {
        // Absolute URL eg : http://www.domain.com/images/fav.ico
        if ($this->isAbsoluteUrl()) {
            return $this->iconUrl;
        }

        // absolute URL with relative scheme eg : //www.domain.com/images/fav.ico
        if ($this->isAbsoluteWithRelativeSchemeUrl()) {
            $scheme = parse_url($this->domain, PHP_URL_SCHEME);

            return sprintf('%s:%s', $scheme, $this->iconUrl);
        }

        // absolute path eg : /images/fav.ico
        if ($this->isAbsolutPathUrl()) {
            $parse = parse_url($this->domain);
            return sprintf('%s:%s%s', $parse['scheme'], $parse['host'], $this->iconUrl);
        }

        // relative URL eg : ../images/fav.ico
        return null;
    }
}
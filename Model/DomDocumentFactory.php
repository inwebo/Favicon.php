<?php

namespace Inwebo\Favicon\Model;

use Symfony\Component\Console\Output\OutputInterface;

class DomDocumentFactory
{
    private string           $url;
    private ?OutputInterface $output;
    private \DOMDocument     $document;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDocument(): \DOMDocument
    {
        return $this->document;
    }

    public function __construct(string $url, ?OutputInterface $output = null)
    {
        $this->url      = $url;
        $this->output   = $output;
        $this->document = new \DOMDocument();
        $this->build();
    }

    private function build()
    {
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            if (0 === error_reporting()) {
                return false;
            }

            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        $content = file_get_contents($this->url);

        $this->document->loadHTML($content, \LIBXML_NOERROR);
        restore_error_handler();
    }
}
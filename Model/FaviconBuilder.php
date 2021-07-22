<?php

namespace Inwebo\Favicon\Model;

use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

class FaviconBuilder
{
    protected string             $url;
    protected DomDocumentFactory $documentFactory;
    protected Finder             $finder;
    protected ?OutputInterface   $output;

    public function getFinder(): Finder
    {
        return $this->finder;
    }

    public function __construct(string $url, ?OutputInterface $output = null)
    {
        $this->url             = $url;
        $this->output          = $output;

        $this->documentFactory = new DomDocumentFactory($url);
        $this->finder          = new Finder($this->documentFactory->getDocument());
    }

    public function build(): array
    {
        echo 'build';
        $nodeArray = $this->finder->find();
        $favicons  = [];

        if (!is_null($this->output)) {
            $this->output(sprintf('Found %s favicons', count($nodeArray)));
        }

die('ko');

        foreach ($nodeArray as $node) {
            echo 'node';
//            $iconFactory = new FaviconFactory();
//            $favicon     = $iconFactory->build($node);
//
//            if (!is_null($favicon)) {
//                $favicons[] = $favicon;
//            }
        }

        return $favicons;
    }
}
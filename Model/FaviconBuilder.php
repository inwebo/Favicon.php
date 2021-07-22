<?php

namespace Inwebo\Favicon\Model;

use Inwebo\Favicon\Model\Queries\BasePathQuery;
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
        $nodeArray = $this->finder->find();
        $favicons  = [];

        if (!is_null($this->output)) {
            $this->output(sprintf('Found %s favicons', count($nodeArray)));
        }

        foreach ($nodeArray as $node) {
            $iconFactory = new FaviconFactory($this->url);
            $favicon     = $iconFactory->build($node);

            if (!is_null($favicon)) {
                $favicons[] = $favicon;
            }
        }

        return $favicons;
    }
}
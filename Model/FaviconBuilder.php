<?php

namespace Inwebo\Favicon\Model;

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

        $this->documentFactory = new DomDocumentFactory($url, $this->output);
        $this->finder          = new Finder($this->documentFactory->getDocument());
    }

    public function build(): array
    {
        $nodeArray = $this->finder->find();
        $favicons  = [];

        if (!is_null($this->output)) {
            if (count($nodeArray) > 0) {
                $this->output->writeln(sprintf('    Found %s favicons', count($nodeArray)));
            } else {
                $this->output->writeln(sprintf('    No icon found'));

                return $favicons;
            }
        }

        foreach ($nodeArray as $node) {
            $iconFactory = new FaviconFactory($this->url, $this->output);
            $favicon     = $iconFactory->build($node);

            if (!is_null($favicon)) {
                $favicons[] = $favicon;
            }
        }

        return $favicons;
    }
}
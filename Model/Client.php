<?php

namespace Inwebo\Favicon\Model;
use \DOMDocument;
use Inwebo\Favicon\Model\Strategies\StrategyInterface;

class Client
{
    protected string $url;

    protected DOMDocument $document;

    protected \SplObjectStorage $strategies;

    public function getUrl(): string
    {
        return $this->url;
    }

    protected function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param \SplObjectStorage<StrategyInterface> $strategies
     * @return $this
     */
    public function setStrategies(\SplObjectStorage $strategies): self
    {
        $this->strategies = $strategies;

        return $this;
    }

    public function setDocument(): self
    {
        $this->document = new DOMDocument();

        return  $this;
    }

    public function getDocument(): DOMDocument
    {
        return $this->document;
    }

    protected function buildDocument(string $url): void
    {
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            if (0 === error_reporting()) {
                return false;
            }

            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        $content = file_get_contents($url);

        $this->document->loadHTML($content, \LIBXML_NOERROR);
        echo $url;
        echo "\n";
        restore_error_handler();
    }

    public function __construct(string $url)
    {
        $this->setUrl($url);

        try  {
            $this
                ->setDocument()
                ->buildDocument($this->url);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function execute()
    {
        while ($this->strategies->valid()) {
            $this->strategies->current()->execute();
        }
    }
}
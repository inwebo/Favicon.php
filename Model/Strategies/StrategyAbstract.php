<?php

namespace Inwebo\Favicon\Model\Strategies;



use Inwebo\Favicon\Dto\Favicon;

class StrategyAbstract implements StrategyInterface
{
    protected string    $query;
    protected \DOMXPath $domXpath;

    public function __construct(\DOMDocument $document, ?string $query = null)
    {
        $this->domXpath = new \DOMXPath($document);
        $this->query    = $query;
    }

    public function execute(): \ArrayObject
    {
        $arrayObject = new \ArrayObject();

        /** @var \DOMNodeList $domNodeList */
        $domNodeList = $this->domXpath->query($this->query);

        if (false === $domNodeList) {
            throw new \Exception('the expression is malformed');
        }

        if(!empty($domNodeList->length)) {
            /** @var \DOMNode $node */
            foreach ($domNodeList as $node) {
                $href = $node->getAttribute('href');

                # the request
                $ch = curl_init($href);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_exec($ch);

                $mimeType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

                $icon = new Favicon();
                $icon->data = base64_encode(file_get_contents($href));
                $icon->mimeType = $mimeType;
                $icon->url = $href;

                echo($icon->getData());die();
            }
        }

        return $arrayObject;
    }
}
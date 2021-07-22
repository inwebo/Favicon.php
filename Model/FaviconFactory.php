<?php

namespace Inwebo\Favicon\Model;

use Symfony\Component\Console\Output\OutputInterface;

class FaviconFactory
{
    protected string           $domain;
    protected ?OutputInterface $output;

    public function __construct(string $domain, ?OutputInterface $output = null)
    {
        $this->domain = $domain;
        $this->output = $output;
    }

    protected function getMimeType(string $url): string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $infos = curl_getinfo($ch);
        $httpCode = $infos['http_code'];
        $mime = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

        if ($httpCode === 301 && (strpos($mime, 'html') !== false)) {
            throw new \Exception(('Icon doesnt exists anymore'));
        }

        return $mime;
    }

    public function getData(string $url): string
    {
        return base64_encode(file_get_contents($url));
    }

    public function build(\DOMNode $node): ?Favicon
    {
        $url         = $node->getAttribute('href');
        $urlResolver = new UrlResolver($this->domain, $url);
        $resolved     = $urlResolver->resolve();


        if (is_null($resolved)) {
            return null;
        }

        try {
            $mime = $this->getMimeType($resolved);

            if (!is_null($this->output)) {
                $this->output->writeln(sprintf('        %s : %s <info>✔</info>', $mime, $resolved));
            }

            // DO NOT ENCODE file with image/svg+xml mime-type
            if('image/svg+xml' !== $mime) {
                $data = $this->getData($resolved);
            } else {
                $data = file_get_contents($url);
            }


            $favicon = new Favicon();
            $favicon->mimeType = $mime;
            $favicon->data     = $data;
            $favicon->url      = $url;

            return $favicon;

        } catch (\Exception $e) {
            if (!is_null($this->output)) {
                $this->output->writeln(sprintf('        <error>Icon doesnt exist anymore</error>'));

                return null;
            }
        }

        return null;
    }
}
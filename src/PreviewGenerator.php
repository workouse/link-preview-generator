<?php


namespace Workouse\LinkPreviewGenerator;


use DOMDocument;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PreviewGenerator implements PreviewGeneratorInterface
{
    /** @var EngineInterface */
    private $templating;

    /** @var string */
    private $templateName;

    /** @var \Symfony\Contracts\HttpClient\HttpClientInterface */
    private $client;

    /** @var string */
    private $cssSelector;

    public function __construct($templating, string $templateName, string $cssSelector)
    {
        $this->templating = $templating;
        $this->templateName = $templateName;
        $this->client = HttpClient::create();
        $this->cssSelector = $cssSelector;
    }

    public function generatePreview(string $html): string
    {
        $anchors = $this->findAnchors($html);
        foreach ($anchors as $anchor) {
            $newTemplate = $this->getNewTemplate($this->getLink($anchor));
            if ($newTemplate) {
                $html = str_replace($anchor, $this->getNewTemplate($this->getLink($anchor)), $html);
            }
        }
        return $html;
    }

    private function findAnchors(string $html): array
    {
        preg_match_all('/((<a href="([^"]*)"?([^\>]+)>(.*)<\/a>))/', $html, $matches);

        return array_filter(array_values(array_unique($matches[0])), function ($anchor) {
            if (strstr($anchor, $this->cssSelector)) {
                return $anchor;
            }
        });
    }

    private function getLink(string $anchor): string
    {
        $dom = new DOMDocument;
        $dom->loadHTML($anchor);
        $link = $dom->getElementsByTagName('a')->item(0);
        return $link->getAttribute('href');
    }

    private function getNewTemplate(string $url): string
    {
        try {
            $response = $this->client->request('GET', $url);
            $headers = $response->getHeaders();
        } catch (TransportExceptionInterface $e) {
            return false;
        }

        $doc = new DomDocument();
        @$doc->loadHTML($response->getContent());
        $xpath = new \DOMXPath($doc);
        $query = '//*/meta';
        $metas = $xpath->query($query);
        $rmetas = [];
        foreach ($metas as $meta) {
            $property = $meta->getAttribute('property');
            $content = $meta->getAttribute('content');
            if (!empty($property) && preg_match('#^og:#', $property)) {
                $rmetas[str_replace(':', '_', $property)] = $content;
            }
        }

        return $this->templating->render($this->templateName, ['tag' => $rmetas]);

    }

}

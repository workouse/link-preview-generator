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

    public function findAnchors(string $html): array
    {
        preg_match_all('/((<a ?([^\>]+)href="([^"]*)"?([^\>]+)>(.*)<\/a>))/', $html, $matches);

        return array_filter(array_values(array_unique($matches[0])), function ($anchor) {
            if (strstr($anchor, $this->cssSelector)) {
                return $anchor;
            }
        });
    }

    public function getLink(string $anchor): string
    {
        $dom = new DOMDocument;
        $dom->loadHTML($anchor);
        $link = $dom->getElementsByTagName('a')->item(0);
        return $link->getAttribute('href');
    }

    public function getNewTemplate(string $url): string
    {
        try {
            $response = $this->client->request('GET', $url);
            $headers = $response->getHeaders();
        } catch (TransportExceptionInterface $e) {
            syslog(LOG_ERR,$e->getMessage());
            return false;
        }

        $dom = new DomDocument();
        @$dom->loadHTML($response->getContent());
        $metas = $dom->getElementsByTagName('meta');
        $rmetas = [];
        foreach ($metas as $meta) {
            $property = !empty($meta->getAttribute('property')) ? $meta->getAttribute('property') : $meta->getAttribute('itemprop');
            $content = $meta->getAttribute('content');
            if (!empty($property)) {
                $rmetas[str_replace(':', '_', $property)] = $content;
            }
        }

        $pageTitle = $dom->getElementsByTagName('title')->item(0);
        if ($pageTitle) {
            $rmetas['title'] = $dom->getElementsByTagName('title')->item(0)->textContent;
        }

        if (!array_key_exists('image', $rmetas) && !array_key_exists('og_image', $rmetas)) {
            $firstImg = $dom->getElementsByTagName('img')->item(0);
            if ($firstImg) {
                $rmetas['image'] = $firstImg->getAttribute('src');
            }
        }

        $rmetas['url'] = $url;

        return $this->templating->render($this->templateName, ['tag' => $rmetas]);

    }

}

<?php


namespace Workouse\LinkPreviewGenerator;

use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;

final class LinkPreviewGenerator
{
    public static function create($templating = null, string $templateName = 'preview.php', string $cssSelector = 'generated-previews')
    {
        if (!$templating) {
            $filesystemLoader = new FilesystemLoader(__DIR__ . '/templates/%name%');
            $templating = new PhpEngine(new TemplateNameParser(), $filesystemLoader);
        }
        return new PreviewGenerator($templating, $templateName, $cssSelector);
    }
}

<?php


namespace Workouse\LinkPreviewGenerator;


interface PreviewGeneratorInterface
{
    public function generatePreview(string $html): string;
}

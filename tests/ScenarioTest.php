<?php

use PHPUnit\Framework\TestCase;

class ScenarioTest extends TestCase
{
    public function testGeneratePreview()
    {

        $html = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <h1>Hello World</h1>
        <a href="https://github.com/workouse/link-preview-generator" class="generated-previews">link-preview-generator</a>
    </body>
</html>
HTML;

        $expected = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <h1>Hello World</h1>
        <div style="border: 1px solid blue">
    <img height="100" width="100" src="https://avatars3.githubusercontent.com/u/55496985?s=400&v=4"/>
    <h1>workouse/link-preview-generator</h1>
    <p>Contribute to workouse/link-preview-generator development by creating an account on GitHub.</p>
    <a href="https://github.com/workouse/link-preview-generator">Visit page</a>
</div>
    </body>
</html>
HTML;

        $this->assertEquals(
            $expected,
            Workouse\LinkPreviewGenerator\LinkPreviewGenerator::create()->generatePreview($html)
        );
    }

    public function testFindAnchors()
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <a href="https://github.com/workouse/link-preview-generator" class="generated-previews">link-preview-generator</a>
    </body>
</html>
HTML;

        $expected = ['<a href="https://github.com/workouse/link-preview-generator" class="generated-previews">link-preview-generator</a>'];

        $this->assertEquals(
            $expected,
            Workouse\LinkPreviewGenerator\LinkPreviewGenerator::create()->findAnchors($html)
        );
    }

    public function testGetLink()
    {
        $anchor = '<a href="https://github.com/workouse/link-preview-generator" class="generated-previews">link-preview-generator</a>';
        $expected = 'https://github.com/workouse/link-preview-generator';

        $this->assertEquals(
            $expected,
            Workouse\LinkPreviewGenerator\LinkPreviewGenerator::create()->getLink($anchor)
        );
    }

    public function testGetNewTemplate()
    {
        $expected = <<<'HTML'
<div style="border: 1px solid blue">
    <img height="100" width="100" src="https://avatars3.githubusercontent.com/u/55496985?s=400&v=4"/>
    <h1>workouse/link-preview-generator</h1>
    <p>Contribute to workouse/link-preview-generator development by creating an account on GitHub.</p>
    <a href="https://github.com/workouse/link-preview-generator">Visit page</a>
</div>
HTML;

        $anchor = 'https://github.com/workouse/link-preview-generator';

        $this->assertEquals(
            $expected,
            Workouse\LinkPreviewGenerator\LinkPreviewGenerator::create()->getNewTemplate($anchor)
        );
    }

}
<h1 align="center">Welcome to link-preview-generator 👋</h1>
<p>
  <a href="https://twitter.com/workousecom" target="_blank">
    <img alt="Twitter: workouse" src="https://img.shields.io/twitter/follow/workousecom.svg?style=social" />
  </a>
  <a href="https://travis-ci.com/workouse/link-preview-generator" target="_blank">
      <img alt="Travis CI: workouse" src="https://travis-ci.com/workouse/link-preview-generator.svg?branch=master" />
  </a>
</p>

## Install

```sh
composer require workouse/link-preview-generator
```

## Usage

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Workouse\LinkPreviewGenerator\LinkPreviewGenerator;

$p_generator = LinkPreviewGenerator::create();
$html = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <h1>Hello World</h1>
        <a href="https://github.com/workouse/link-preview-generator" class="generated-previews">link-preview-generator</a>
    </body>
</html>
HTML;

echo $p_generator->generatePreview($html);
```

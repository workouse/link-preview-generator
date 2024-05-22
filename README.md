# Link Preview Generator
Its a simple library to generate link preview from html content. Its find all links in html content and generate preview for each link targeted with class name `generated-previews`. It will replace anchor tag with content of preview.php file. You can customize preview.php file to change preview design.

> the project built by an intern at workouse (@bykclk), its not for production use.

## Features
- Generate link preview from html content
- Customize preview design
- Easy to use

## Install

```sh
composer require workouse/link-preview-generator
```

## Usage

### Default usage

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

### Custom preview design
- Create your preview.php file in your project root directory
```php
<div style="border: 1px solid blue">
    <img height="100" width="100" src="<?= $tag['og_image'] ? $tag['og_image'] : $tag['image'] ?>"/>
    <h1><?= $tag['og_title'] ? $tag['og_title'] : $tag['title'] ?></h1>
    <p><?= $tag['og_description'] ? $tag['og_description'] : $tag['description'] ?></p>
    <a href="<?= $tag['og_url'] ?>">Visit page</a>
</div>
```

- `$tag` variable will be passed your template file with following keys
    - `title`
    - `description`
    - `image`
    - `url`
    - `og_title`
    - `og_description`
    - `og_image`
    - `og_url`

- Use `generatePreview` method with second argument as path of your preview.php file
```php
<?php
//you can use your template engine, i will use PhpEngine in here
$filesystemLoader = new FilesystemLoader(__DIR__ . '/templates/%name%');
$templating = new PhpEngine(new TemplateNameParser(), $filesystemLoader);
$p_generator = LinkPreviewGenerator::create($templating,'new_preview.php');//it will be placed inside templates folder
$html = ... 
echo $p_generator->generatePreview($html);
```


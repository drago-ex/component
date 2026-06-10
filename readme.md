# Drago Component

Bootstrap components such as modal and offcanvas.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/component/blob/main/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fcomponent.svg)](https://badge.fury.io/ph/drago-ex%2Fcomponent)
[![Coding Style](https://github.com/drago-ex/component/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/component/actions/workflows/coding-style.yml)

## Requirements
- PHP >= 8.3
- Nette Framework
- Composer
- Bootstrap
- Naja
- Node.js

## Installation
```bash
composer require drago-ex/component
```

## Project files
File copying is handled automatically by [drago-ex/project-tools](https://github.com/drago-ex/project-tools),
which must be installed in your project. Without it, copy the files manually according to the `copy` section
in this package's `composer.json`. To skip this package, set `"skip": true` under
`extra.drago-tools.packages.<package-name>` in your root `composer.json`.

## Examples
In the `Control` component, use the `Drago\Component\Component` trait.

Passing variables to the template:
```php
$template->offcanvasId = $this->getUniqueIdComponent(self::Offcanvas);
$template->modalId = $this->getUniqueIdComponent(self::Modal);
```

You can then use the `Drago\Component\ModalHandle` and `Drago\Component\OffcanvasHandle` implementations:
```php
#[Requires(ajax: true)] public function handleOpenModal(): void
{
	$this->modalComponent(self::Modal);
}


#[Requires(ajax: true)] public function handleOpenOffcanvas(): void
{
	$this->offCanvasComponent(self::Offcanvas);
}
```


You can pass the snippet name that should be redrawn, or create your own signal handler and redraw the related snippet manually.

```php
#[Requires(ajax: true)] public function handleOpenModalWindow(): void
{
	$this->modalComponent();
	$this->redrawControl('...');
}
```

Use the component templates in Latte. If you need to redraw multiple blocks, add additional snippets inside the embedded template.
```latte
<a n:href="openOffcanvas!" class="ajax" data-naja-history="off">Open Offcanvas</a>
{embed 'path/to/@offcanvas.latte', offcanvasId: $offcanvasId}
	{block title}Title{/block}
	{block body}
		{snippet offcanvas}
			...
		{/snippet}
	{/block}
{/embed}

<a n:href="openModal!" class="ajax" data-naja-history="off">Open Modal</a>
{embed 'path/to/@modal.latte', modalId: $modalId}
	{block title}Title{/block}
	{block body}
		{snippet modal}
			...
		{/snippet}
	{/block}
	{block footer}
		{import 'path/to/@dismiss.button.latte'}
		{include buttonDismiss, type: 'offcanvas'}
	{/block}
{/embed}
```

## JavaScript setup
Since the package is installed via Composer, add the following to your `package.json` to make the `drago-component` alias available in your bundler:
```json
{
  "type": "module",
  "dependencies": {
    "drago-component": "file:vendor/drago-ex/component"
  }
}
```
Then run `npm install`.

```JavaScript
import BootstrapComponents from 'drago-component/bootstrap-component';
```

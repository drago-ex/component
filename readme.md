
## Drago Components
Bootstrap components such as modal and offcanvas.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/components/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fcomponents.svg)](https://badge.fury.io/ph/drago-ex%2Fcomponents)
[![Coding Style](https://github.com/drago-ex/components/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/components/actions/workflows/coding-style.yml)


## Technology
- PHP 8.3 or higher
- composer
- node.js

## Installation
```
composer require drago-ex/components
```

## Usage
In the `Control` component we will use Trait `Drago\Components\Components`

Passing variables to the template:
```php
$template->offcanvasId = $this->getUniqueIdComponent(self::Offcanvas);
$template->modalId = $this->getUniqueIdComponent(self::Modal);
```

And according to needs, we can use the implementations `Drago\Components\ModalHandle` and `Drago\Components\OffcanvasHandle`
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


Where do we insert the name of the snippet to override the component or can we write our own snippet handler and wrap the appropriate component in it.

```php
#[Requires(ajax: true)] public function handleOpenModalWindow(): void
{
	$this->modalComponent();
	$this->redrawControl('...');
}
```

We will then use the templates of individual components and if we want to redraw multiple blocks, we need to add additional snippets.
```latte
<a n:href="openOffcanvas!" class="ajax" data-naja-history="off">Open Offcanvas</a>
{embed '../../../../vendor/drago-ex/components/src/Drago/assets/@offcanvas.latte', offcanvasId: $offcanvasId}
	{block title}Title{/block}
	{block body}
		{snippet offcanvas}
			...
		{/snippet}
	{/block}
{/embed}

<a n:href="openModal!" class="ajax" data-naja-history="off">Open Modal</a>
{embed '../../../../vendor/drago-ex/components/src/Drago/assets/@modal.latte', modalId: $modalId}
	{block title}Title{/block}
	{block body}
		{snippet modal}
			...
		{/snippet}
	{/block}
	{block footer}
		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	{/block}
{/embed}
```

Next, it is necessary to import javascript to operate the component.
```js
import "../vendor/drago-ex/components/src/Drago/assets/naja.components"
```


For demonstration, the component is used in the project: https://github.com/drago-ex/project

# Drago Component

Bootstrap components such as modal, offcanvas, dropdown, and tabs.

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

## Modal and offcanvas
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
		{import 'path/to/@dismiss-button.latte'}
		{include buttonDismiss, type: 'modal'}
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

## Dropdown widget
The dropdown widget is a small Latte wrapper for Bootstrap-like dropdown menus. It is useful for compact
navigation actions, language switchers, user menus, or theme controls.

Import the JavaScript initializer and SCSS in your frontend entry point:
```JavaScript
import { initAppDropdowns } from 'drago-component/dropdown';
import 'drago-component/styles/dropdown';

initAppDropdowns();
```

You can also import it from the main package entry:
```JavaScript
import { initAppDropdowns } from 'drago-component';
```

Use the widget in Latte:
```latte
{embed 'path/to/@dropdown.latte', name: 'Menu', icon: 'fa-solid fa-bars', class: 'app-dropdown-navbar', end: true}
	{block menu}
		{include item, name: 'Homepage', link: ':Front:Home:'}
		{include item, name: 'Administration', link: 'Admin:'}
		{include divider}
		{include item, name: 'Sign out', link: 'Sign:out'}
	{/block}
{/embed}
```

Available parameters:
- `name`: dropdown toggle label.
- `icon`: optional Font Awesome icon class.
- `class`: optional class added to the dropdown wrapper.
- `end`: aligns the dropdown menu to the right.

Color variants:
- Default behavior inherits the toggle color from the surrounding layout.
- `app-dropdown-navbar`: uses Bootstrap navbar color variables.
- `app-dropdown-body`: uses Bootstrap body color variables.

You can also set custom colors with CSS variables:
```scss
.my-dropdown {
	--app-dropdown-toggle-color: var(--bs-light);
	--app-dropdown-toggle-hover-color: var(--bs-white);
	--app-dropdown-menu-bg: var(--bs-dark);
	--app-dropdown-item-color: var(--bs-light);
	--app-dropdown-item-hover-color: var(--bs-white);
}
```

The widget provides helper blocks:
- `item`: renders a translated dropdown link.
- `divider`: renders a dropdown divider.

## Tabs widget
The tabs widget renders Bootstrap tabs from a small configuration array and keeps the content blocks in the
same template. Bootstrap tab JavaScript must be available in the project.

```latte
{embed 'path/to/@tabs.latte',
	tabs: [
		[
			id: 'profile',
			label: 'Profile information',
			heading: 'Profile information',
			description: 'Update the name and email address used for your account.',
			block: 'profileInfo',
		],
		[
			id: 'password',
			label: 'Change password',
			heading: 'Change password',
			description: 'Use a strong password to keep your account secure.',
			block: 'changePassword',
		],
	],
	active: 'profile',
	class: 'card',
	headerClass: 'px-3 pt-3',
	contentClass: 'p-4'
}
	{block profileInfo}
		{control profile}
	{/block}

	{block changePassword}
		{control password}
	{/block}
{/embed}
```

Each tab item supports:
- `id`: unique tab identifier.
- `label`: translated tab label.
- `block`: block name rendered as tab content.
- `class`: optional class for the tab pane.
- `heading`: optional translated heading above the tab content.
- `description`: optional translated description below the heading.
- `headingClass`: optional heading class.
- `descriptionClass`: optional description class.

Widget parameters:
- `tabs`: list of tab definitions.
- `active`: active tab id; when omitted, the first tab is active.
- `class`: optional wrapper class.
- `headerClass`: optional class for the tabs header.
- `contentClass`: optional class for the tab content wrapper.

# Drago Components

Bootstrap modal and offcanvas opened by Nette signals over Naja, Latte widgets for dropdowns and tabs, and a CSS
tooltip for icon buttons.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/component/blob/main/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fcomponent.svg)](https://badge.fury.io/ph/drago-ex%2Fcomponent)
[![Tests](https://github.com/drago-ex/component/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/component/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/component/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/component/actions/workflows/coding-style.yml)

## Requirements

- PHP >= 8.3, < 9
- Nette Framework
- Composer
- Bootstrap
- Naja
- Node.js

## Installation

```bash
composer require drago-ex/component
```

[Project Tools](https://github.com/drago-ex/project-tools) copies the Latte widgets `@modal`, `@offcanvas`, `@tabs`,
`@dropdown` and `@dismiss-button` into `app/Presentation/Accessory/Widget`, where you adapt them.

## Modal and offcanvas

In the `Control` component, use the `Drago\Component\Component` trait.

Passing variables to the template:
```php
$template->offcanvasId = $this->getUniqueIdComponent(self::Offcanvas);
$template->modalId = $this->getUniqueIdComponent(self::Modal);
```

You can then implement the `Drago\Component\ModalHandle` and `Drago\Component\OffcanvasHandle` interfaces.
The optional argument of `modalComponent()` and `offCanvasComponent()` is the name of a snippet to redraw;
here it is the `modal` and `offcanvas` snippets from the templates below:
```php
#[Requires(ajax: true)] public function handleOpenModal(): void
{
	$this->modalComponent('modal');
}


#[Requires(ajax: true)] public function handleOpenOffcanvas(): void
{
	$this->offCanvasComponent('offcanvas');
}
```

Attributes on interface methods are not inherited, so repeat `#[Requires(ajax: true)]` in your implementation.
`closeComponent()` closes the open modal or offcanvas (e.g. after a saved form). You can also write your own signal handler and redraw the related snippets manually.

```php
#[Requires(ajax: true)] public function handleOpenModalWindow(): void
{
	$this->modalComponent();
	$this->redrawControl('...');
}
```

Use the component templates in Latte. If you need to redraw multiple blocks, add additional snippets inside the embedded template.
The offcanvas is 61rem wide; pass `size` to change it, e.g. `size: '100vw'` for the whole screen.
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

## Dropdown widget

The dropdown widget is a small Latte wrapper for Bootstrap dropdown menus. It is useful for compact
navigation actions, language switchers, user menus, or theme controls.

Use the widget in Latte:
```latte
{embed 'path/to/@dropdown.latte', name: 'Menu', icon: 'fa-solid fa-bars', menu: 'end'}
	{block menu}
		{include item, name: 'Homepage', link: ':Front:Home:'}
		{include item, name: 'Administration', link: 'Admin:'}
		{include divider}
		{include item, name: 'Sign out', link: 'Sign:out'}
	{/block}
{/embed}
```

Available parameters:
- `name`: dropdown toggle label (required, translated).
- `icon`: optional Font Awesome icon class.
- `menu`: optional Bootstrap menu alignment suffix, for example `end`, `lg-start`, or `lg-end`.

The `menu` value is appended to the Bootstrap `dropdown-menu-*` class, so `menu: 'end'` renders
`dropdown-menu-end` and `menu: 'lg-start'` renders `dropdown-menu-lg-start`.

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

## Tooltip

A tooltip of pure CSS for buttons with an icon only: the text of `aria-label` is shown on hover and keyboard focus,
in the colors of the button, and screen readers read it as the name of the button. It needs no JavaScript, so it
works in AJAX snippets too.

```html
<a href="..." class="btn btn-xs btn-danger" aria-label="Delete" data-tooltip>
	<i class="fa-solid fa-trash" aria-hidden="true"></i>
</a>
```

`data-tooltip="left"` shows it on the left, e.g. at the right edge of a table. The actions of
[drago-ex/datagrid](https://github.com/drago-ex/datagrid) with an icon use it.

## Assets

Add the Composer package as a local npm dependency in `package.json` (Drago Project installs it by itself), so the bundler knows `drago-component`:
```json
{
  "type": "module",
  "dependencies": {
    "drago-component": "file:vendor/drago-ex/component"
  }
}
```
Then run `npm install`.

### Modal and offcanvas

```javascript
import { BootstrapComponents } from 'drago-component';

naja.registerExtension(new BootstrapComponents());
```

### Dropdown widget

Register the dropdown helper in your frontend entry point:
```javascript
import { BootstrapDropdowns } from 'drago-component';

naja.registerExtension(new BootstrapDropdowns());
```

### Tooltip

```scss
@import 'drago-component/styles/tooltip';
```

# Drago Components

[English](readme.md) | Čeština

Komponenty Bootstrapu jako modální okno, offcanvas, dropdown a záložky.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/component/blob/main/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fcomponent.svg)](https://badge.fury.io/ph/drago-ex%2Fcomponent)
[![Tests](https://github.com/drago-ex/component/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/component/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/component/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/component/actions/workflows/coding-style.yml)

## Požadavky

- PHP >= 8.3, < 9
- Nette Framework
- Composer
- Bootstrap
- Naja
- Node.js

## Instalace

```bash
composer require drago-ex/component
```

## Soubory projektu

Kopírování souborů obstarává automaticky [drago-ex/project-tools](https://github.com/drago-ex/project-tools),
který musí být v projektu nainstalovaný. Bez něj zkopírujte soubory ručně podle sekce `copy`
v `composer.json` tohoto balíčku. Pro přeskočení balíčku nastavte `"skip": true` v
`extra.drago-tools.packages.<package-name>` v kořenovém `composer.json`.

## Modální okno a offcanvas

V komponentě `Control` použijte trait `Drago\Component\Component`.

Předání proměnných do šablony:
```php
$template->offcanvasId = $this->getUniqueIdComponent(self::Offcanvas);
$template->modalId = $this->getUniqueIdComponent(self::Modal);
```

Potom můžete implementovat rozhraní `Drago\Component\ModalHandle` a `Drago\Component\OffcanvasHandle`.
Volitelný argument `modalComponent()` a `offCanvasComponent()` je název snippetu k překreslení;
zde jsou to snippety `modal` a `offcanvas` z šablon níže:
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

Atributy metod rozhraní se nedědí, proto `#[Requires(ajax: true)]` zopakujte ve své implementaci.
Můžete si také napsat vlastní obsluhu signálu a související snippety překreslit ručně.

```php
#[Requires(ajax: true)] public function handleOpenModalWindow(): void
{
	$this->modalComponent();
	$this->redrawControl('...');
}
```

V Latte použijte šablony komponent. Pokud potřebujete překreslit více bloků, přidejte do vložené šablony další snippety.
Offcanvas je široký 61rem; pro změnu předejte `size`, např. `size: '100vw'` pro celou obrazovku.
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

## Nastavení JavaScriptu

Balíček se instaluje přes Composer, proto přidejte do `package.json` následující, aby byl v bundleru dostupný alias `drago-component`:
```json
{
  "type": "module",
  "dependencies": {
    "drago-component": "file:vendor/drago-ex/component"
  }
}
```
Potom spusťte `npm install`.

```javascript
import { BootstrapComponents } from 'drago-component';

naja.registerExtension(new BootstrapComponents());
```

## Widget dropdown

Widget dropdown je malý obal Latte pro rozbalovací menu Bootstrapu. Hodí se pro kompaktní
navigační akce, přepínače jazyků, uživatelská menu nebo ovládání motivu.

Zaregistrujte pomocníka pro dropdown ve vstupním bodě frontendu:
```javascript
import { BootstrapDropdowns } from 'drago-component';

naja.registerExtension(new BootstrapDropdowns());
```

Použijte widget v Latte:
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

Dostupné parametry:
- `name`: popisek tlačítka dropdownu (povinný, překládá se).
- `icon`: volitelná třída ikony Font Awesome.
- `menu`: volitelná přípona zarovnání menu Bootstrapu, například `end`, `lg-start` nebo `lg-end`.

Hodnota `menu` se připojí ke třídě Bootstrapu `dropdown-menu-*`, takže `menu: 'end'` vykreslí
`dropdown-menu-end` a `menu: 'lg-start'` vykreslí `dropdown-menu-lg-start`.

Chování dropdownu zůstává na Bootstrapu.

Pro vlastní barvy použijte raději utility a proměnné Bootstrapu ve vlastním layoutu než přepisování
widgetu dropdown.

Widget poskytuje pomocné bloky:
- `item`: vykreslí přeložený odkaz v dropdownu.
- `divider`: vykreslí oddělovač v dropdownu.

## Widget záložek

Widget záložek vykreslí záložky Bootstrapu z malého konfiguračního pole a bloky obsahu nechává ve
stejné šabloně. V projektu musí být dostupný JavaScript záložek Bootstrapu.

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

Každá záložka podporuje:
- `id`: jedinečný identifikátor záložky.
- `label`: přeložený popisek záložky.
- `block`: název bloku vykresleného jako obsah záložky.
- `class`: volitelná třída panelu záložky.
- `heading`: volitelný přeložený nadpis nad obsahem záložky.
- `description`: volitelný přeložený popis pod nadpisem.
- `headingClass`: volitelná třída nadpisu.
- `descriptionClass`: volitelná třída popisu.

Parametry widgetu:
- `tabs`: seznam definic záložek.
- `active`: id aktivní záložky; když chybí, je aktivní první záložka.
- `class`: volitelná třída obalu.
- `headerClass`: volitelná třída hlavičky záložek.
- `contentClass`: volitelná třída obalu obsahu záložek.

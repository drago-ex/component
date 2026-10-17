<?php

/**
 * Test: bundled Latte widgets
 */

declare(strict_types=1);

use Latte\Engine;
use Latte\Loaders\StringLoader;
use Tester\Assert;
use Tester\DomQuery;

require __DIR__ . '/../bootstrap.php';


function createLatte(string $page): Engine
{
	$templates = ['page' => $page];
	foreach (glob(__DIR__ . '/../../src/assets/latte/*.latte') as $file) {
		$templates[basename($file)] = file_get_contents($file);
	}

	$latte = new Engine;
	$latte->setLoader(new StringLoader($templates));
	$latte->addExtension(new Nette\Bridges\ApplicationLatte\UIExtension(null));
	$latte->addExtension(new Latte\Essential\TranslatorExtension(fn(string|Stringable $message): string => (string) $message));
	return $latte;
}


test('All bundled templates compile', function () {
	$latte = createLatte('');
	foreach (glob(__DIR__ . '/../../src/assets/latte/*.latte') as $file) {
		Assert::noError(fn() => $latte->compile(basename($file)));
	}
});


test('The modal renders with its id, label and blocks', function () {
	$html = createLatte('{embed "@modal.latte", modalId: "editModal", options: [header: "danger"]}{block title}Edit{/block}{block body}Body{/block}{/embed}')
		->renderToString('page');

	$dom = DomQuery::fromHtml($html);
	Assert::true($dom->has('div#editModal.modal[aria-labelledby=editModalLabel]'));
	Assert::same('Edit', (string) $dom->find('#editModalLabel')[0]);
	Assert::true($dom->has('.modal-header.bg-danger .btn-close.btn-close-white'));
});


test('The offcanvas renders with its id and label', function () {
	$html = createLatte('{embed "@offcanvas.latte", offcanvasId: "panel"}{block title}Panel{/block}{/embed}')
		->renderToString('page');

	$dom = DomQuery::fromHtml($html);
	Assert::true($dom->has('div#panel.offcanvas[aria-labelledby=panelLabel]'));
	Assert::same('Panel', (string) $dom->find('#panelLabel')[0]);
});


test('Tabs activate the first tab when none is given', function () {
	$html = createLatte('{embed "@tabs.latte", tabs: [[id: "one", label: "One", block: "first"], [id: "two", label: "Two", block: "second"]]}{block first}A{/block}{block second}B{/block}{/embed}')
		->renderToString('page');

	$dom = DomQuery::fromHtml($html);
	Assert::true($dom->has('button#one-tab.nav-link.active[aria-selected=true]'));
	Assert::true($dom->has('button#two-tab[aria-selected=false]'));
	Assert::true($dom->has('#one-pane.tab-pane.show.active'));
	Assert::same('B', trim((string) $dom->find('#two-pane')[0]));
});


test('The dismiss button closes the given element type', function () {
	$html = createLatte('{import "@dismiss-button.latte"}{include buttonDismiss, type: "modal"}')->renderToString('page');
	Assert::true(DomQuery::fromHtml($html)->has('button.btn.btn-secondary[data-bs-dismiss=modal]'));
});

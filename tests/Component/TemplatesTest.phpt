<?php declare(strict_types=1);

/**
 * Test: bundled Latte widgets
 */

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


test('The modal renders with its id, label, blocks and the line of its theme', function () {
	$html = createLatte('{embed "@modal.latte", modalId: "editModal", options: [theme: "danger"]}{block title}Edit{/block}{block body}Body{/block}{/embed}')
		->renderToString('page');

	$dom = DomQuery::fromHtml($html);
	Assert::true($dom->has('div#editModal.modal[aria-labelledby=editModalLabel]'));
	Assert::same('Edit', (string) $dom->find('#editModalLabel')[0]);
	Assert::true($dom->has('.modal-dialog.theme-danger .modal-status'));
	Assert::true($dom->has('.modal-header .btn-close'));
	Assert::same('Body', trim((string) $dom->find('.modal-body')[0]));
});


test('The modal has no theme without a known one', function () {
	foreach (['', ', options: [theme: "unknown"]'] as $options) {
		$html = createLatte('{embed "@modal.latte", modalId: "m"' . $options . '}{/embed}')->renderToString('page');
		$dom = DomQuery::fromHtml($html);
		Assert::true($dom->has('div.modal-dialog[class="modal-dialog"]'), $options);
		Assert::false($dom->has('.modal-status'), $options);
	}
});


test('A confirmation is a centered modal with the icon of its theme', function () {
	$html = createLatte('{embed "@modal.latte", modalId: "m", options: [theme: "danger", confirm: true]}{block title}Delete?{/block}{block body}Text{/block}{/embed}')
		->renderToString('page');

	$dom = DomQuery::fromHtml($html);
	Assert::true($dom->has('.modal-dialog.modal-dialog-centered.modal-confirm.theme-danger'));
	Assert::true($dom->has('.modal-body i.bi-exclamation-triangle.modal-confirm-icon'));
	Assert::same('Delete?', (string) $dom->find('h2#mLabel')[0]);
	Assert::false($dom->has('.modal-header'));
});


test('The offcanvas renders with its id and label', function () {
	$html = createLatte('{embed "@offcanvas.latte", offcanvasId: "panel"}{block title}Panel{/block}{/embed}')
		->renderToString('page');

	$dom = DomQuery::fromHtml($html);
	Assert::true($dom->has('div#panel.offcanvas[aria-labelledby=panelLabel]'));
	Assert::same('Panel', (string) $dom->find('#panelLabel')[0]);
	Assert::true($dom->has('div#panel[style="--bs-offcanvas-width: 61rem"]'));
});


test('The block header of the offcanvas stands between its title and the close button', function () {
	$html = createLatte('{embed "@offcanvas.latte", offcanvasId: "panel"}{block title}Panel{/block}{block header}<div class="tabs"></div>{/block}{/embed}')
		->renderToString('page');

	Assert::true(DomQuery::fromHtml($html)->has('.offcanvas-header h5#panelLabel + .tabs + .btn-close'));
});


test('The offcanvas takes its width from the size', function () {
	$html = createLatte('{embed "@offcanvas.latte", offcanvasId: "panel", size: "100vw"}{/embed}')
		->renderToString('page');

	Assert::true(DomQuery::fromHtml($html)->has('div#panel[style="--bs-offcanvas-width: 100vw"]'));
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


test('The dismiss button closes the given element type, with Close or its label', function () {
	$html = createLatte('{import "@dismiss-button.latte"}{include buttonDismiss, type: "modal"}')->renderToString('page');
	$dom = DomQuery::fromHtml($html);
	Assert::true($dom->has('button.btn.btn-secondary[data-bs-dismiss=modal]'));
	Assert::same('Close', trim((string) $dom->find('button')[0]));

	$html = createLatte('{import "@dismiss-button.latte"}{include buttonDismiss, type: "offcanvas", label: "Cancel"}')->renderToString('page');
	Assert::same('Cancel', trim((string) DomQuery::fromHtml($html)->find('button[data-bs-dismiss=offcanvas]')[0]));
});

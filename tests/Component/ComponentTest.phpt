<?php

/**
 * Test: Drago\Component\Component
 */

declare(strict_types=1);

use Drago\Component\Component;
use Drago\Component\ModalHandle;
use Drago\Component\OffcanvasHandle;
use Nette\Application\UI\Control;
use Nette\Application\UI\Presenter;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestPresenter extends Presenter
{
}


class TestControlWithComponent extends Control implements ModalHandle, OffcanvasHandle
{
	use Component;

	/** @var list<?string> */
	public array $redrawn = [];


	public function handleOpenModal(): void
	{
		$this->modalComponent('modal');
	}


	public function handleOpenOffcanvas(): void
	{
		$this->offCanvasComponent();
	}


	public function redrawControl(?string $snippet = null, bool $redraw = true): void
	{
		$this->redrawn[] = $snippet;
	}
}


function createControl(): array
{
	$presenter = new TestPresenter;
	$control = new TestControlWithComponent;
	$presenter->addComponent($control, 'myWidget');
	return [$presenter, $control];
}


test('Component constants', function () {
	Assert::same('offcanvas', TestControlWithComponent::Offcanvas);
	Assert::same('modal', TestControlWithComponent::Modal);
});


test('Unique ids are derived from the control id', function () {
	[, $control] = createControl();
	Assert::same('myWidgetmodal', $control->getUniqueIdComponent(TestControlWithComponent::Modal));
	Assert::same('myWidgetoffcanvas', $control->getUniqueIdComponent(TestControlWithComponent::Offcanvas));
});


test('Opening sends the element id in the payload and redraws the given snippet', function () {
	[$presenter, $control] = createControl();

	$control->handleOpenModal();
	Assert::same('myWidgetmodal', $presenter->getPayload()->modal);
	Assert::same(['modal'], $control->redrawn);

	$control->handleOpenOffcanvas();
	Assert::same('myWidgetoffcanvas', $presenter->getPayload()->offcanvas);
	Assert::same(['modal'], $control->redrawn, 'nothing is redrawn without a snippet name');
});


test('Closing sends the close flag in the payload', function () {
	[$presenter, $control] = createControl();
	$control->closeComponent();
	Assert::same('close', $presenter->getPayload()->close);
});

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

class MockPresenter extends Presenter
{
	public bool $redrawCalled = false;


	public function redrawControl(?string $snippet = null, bool $redraw = true): void
	{
		$this->redrawCalled = true;
	}
}

class TestControlWithComponent extends Control implements ModalHandle, OffcanvasHandle
{
	use Component;

	public function handleOpenModal(): void
	{
		$this->modalComponent();
	}


	public function handleOpenOffcanvas(): void
	{
		$this->offCanvasComponent();
	}
}

test('Component constants', function () {
	Assert::same('offcanvas', TestControlWithComponent::Offcanvas);
	Assert::same('modal', TestControlWithComponent::Modal);
});

test('Component modal and offcanvas payload generation', function () {
	$presenter = new MockPresenter;
	$control = new TestControlWithComponent;
	$presenter->addComponent($control, 'myWidget');

	Assert::same('myWidgetmodal', $control->getUniqueIdComponent(TestControlWithComponent::Modal));
	Assert::same('myWidgetoffcanvas', $control->getUniqueIdComponent(TestControlWithComponent::Offcanvas));

	$control->modalComponent();
	Assert::same('myWidgetmodal', $presenter->getPayload()->modal);

	$control->offCanvasComponent();
	Assert::same('myWidgetoffcanvas', $presenter->getPayload()->offcanvas);

	$control->closeComponent();
	Assert::same('close', $presenter->getPayload()->close);
});

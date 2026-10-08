<?php

declare(strict_types=1);

namespace Drago\Component;


/** Bootstrap components such as modal and offcanvas. */
trait Component
{
	public const string
		Offcanvas = 'offcanvas',
		Modal = 'modal';


	public function getUniqueIdComponent(string $name): string
	{
		return $this->getUniqueId() . $name;
	}


	public function offCanvasComponent(?string $snippet = null): void
	{
		$component = $this->getUniqueIdComponent(self::Offcanvas);
		$this->payloadComponent(self::Offcanvas, $component);

		if ($snippet) {
			$this->redrawControl($snippet);
		}
	}


	public function modalComponent(?string $snippet = null): void
	{
		$component = $this->getUniqueIdComponent(self::Modal);
		$this->payloadComponent(self::Modal, $component);

		if ($snippet) {
			$this->redrawControl($snippet);
		}
	}


	public function closeComponent(): void
	{
		$this->getPresenter()->payload->close = 'close';
	}


	private function payloadComponent(string $name, string $component): void
	{
		$this->getPresenter()->payload->{$name} = $component;
	}
}

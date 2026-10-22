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
		$this->openComponent(self::Offcanvas, $snippet);
	}


	public function modalComponent(?string $snippet = null): void
	{
		$this->openComponent(self::Modal, $snippet);
	}


	public function closeComponent(): void
	{
		$this->getPresenter()->payload->close = 'close';
	}


	/** Sends the element id in the payload, so the JavaScript extension shows it, and redraws the snippet. */
	private function openComponent(string $name, ?string $snippet): void
	{
		$this->getPresenter()->payload->{$name} = $this->getUniqueIdComponent($name);
		if ($snippet !== null) {
			$this->redrawControl($snippet);
		}
	}
}

<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Components;

use Nette\Bridges\ApplicationLatte\Template;


trait Components
{
	public const string
		Offcanvas = 'offcanvas',
		Modal = 'modal';


	/**
	 * Returns a unique ID for the component.
	 */
	public function getUniqueIdComponent(string $name): string
	{
		return $this->getUniqueId() . $name;
	}


	private function payloadComponent(string $name, string $component): void
	{
		$this->getPresenter()->payload->{$name} = $component;
	}


	/**
	 * Calls the offcanvas component.
	 */
	public function offCanvasComponent(?string $snippet = null): void
	{
		$component = $this->getUniqueIdComponent(self::Offcanvas);
		$this->payloadComponent(self::Offcanvas, $component);

		if ($snippet) {
			$this->redrawControl($snippet);
		}
	}


	/**
	 * Calls the modal component.
	 */
	public function modalComponent(?string $snippet = null): void
	{
		$component = $this->getUniqueIdComponent(self::Modal);
		$this->payloadComponent(self::Modal, $component);

		if ($snippet) {
			$this->redrawControl($snippet);
		}
	}


	/**
	 * Closes modal or offcanvas component.
	 */
	public function closeComponent(): void
	{
		$this->getPresenter()->payload
			->close = 'close';
	}
}

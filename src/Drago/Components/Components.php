<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Components;

use Nette\Application\UI\Component;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;


/**
 * @property-read Component $component
 * @property-read ComponentsTemplate $template
 * @property-read Presenter $presenter
 */
trait Components
{
	public const string
		Offcanvas = 'offcanvas',
		Modal = 'modal';


	/**
	 * Returns a unique ID for the component.
	 *
	 * @param string $name The name to append to the unique ID.
	 * @return string The unique ID of the component.
	 */
	public function getUniqueIdComponent(string $name): string
	{
		return $this->component->getUniqueId() . $name;
	}


	/**
	 * Calls the offcanvas component.
	 */
	public function offCanvasComponent(): void
	{
		$component = $this->getUniqueIdComponent(self::Offcanvas);
		$this->presenter->payload->{self::Offcanvas} = $component;
	}


	/**
	 * Calls the modal component.
	 */
	public function modalComponent(): void
	{
		$component = $this->getUniqueIdComponent(self::Modal);
		$this->presenter->payload->{self::Modal} = $component;
	}


	/**
	 * Closes modal or offcanvas component.
	 */
	public function closeComponent(): void
	{
		$this->presenter->payload
			->close = 'close';
	}


	public function defaultTemplate(): Template
	{
		$template = $this->template;
		$template->uniqueComponentOffcanvas = $this->getUniqueIdComponent(self::Offcanvas);
		$template->uniqueComponentModal = $this->getUniqueIdComponent(self::Modal);
		return $template;
	}
}

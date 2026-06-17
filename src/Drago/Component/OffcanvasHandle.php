<?php

declare(strict_types=1);

namespace Drago\Component;

use Nette\Application\Attributes\Requires;


interface OffcanvasHandle
{
	/** Opens the offcanvas component. */
	#[Requires(ajax: true)]
	public function handleOpenOffcanvas(): void;
}

<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Component;

use Nette\Application\Attributes\Requires;


interface OffcanvasHandle
{
	#[Requires(ajax: true)]
	public function handleOpenOffcanvas(): void;
}

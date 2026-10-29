<?php declare(strict_types=1);

namespace Drago\Component;

use Nette\Application\Attributes\Requires;


interface OffcanvasHandle
{
	#[Requires(ajax: true)]
	function handleOpenOffcanvas(): void;
}

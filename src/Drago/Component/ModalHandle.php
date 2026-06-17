<?php

declare(strict_types=1);

namespace Drago\Component;

use Nette\Application\Attributes\Requires;


interface ModalHandle
{
	/** Opens the modal component. */
	#[Requires(ajax: true)]
	public function handleOpenModal(): void;
}

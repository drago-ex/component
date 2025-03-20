<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Components;

use Nette\Bridges\ApplicationLatte\Template;


class ComponentsTemplate extends Template
{
	public string $uniqueComponentOffcanvas;
	public string $uniqueComponentModal;
}

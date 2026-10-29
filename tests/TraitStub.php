<?php declare(strict_types=1);

use Drago\Component\Component;
use Nette\Application\UI\Control;


/** Lets PHPStan analyse the Component trait, which no class in the package uses. */
class TraitStub extends Control
{
	use Component;
}

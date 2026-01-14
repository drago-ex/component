<?php

declare(strict_types=1);

namespace Drago\Component;

use Latte\Extension;


class ComponentExtension extends Extension
{
	private const array OffCanvasSize = [
		'sm', 'md', 'lg', 'xl', 'xxl',
	];
	private const array OffCanvasPosition = [
		'start', 'end', 'top', 'bottom',
	];

	private const array ModalSize = [
		'sm', 'lg', 'xl',
	];
	private const array ModalFullscreen = [
		'sm', 'md', 'lg', 'xl', 'xxl',
	];


	public function getFunctions(): array
	{
		return [
			'offCanvasClass' => [$this, 'offCanvasClass'],
			'modalDialogClass' => [$this, 'modalDialogClass'],
		];
	}


	// Offcanvas
	public function offCanvasClass(array $options = []): array
	{
		$class = ['offcanvas'];

		if (!empty($options['size']) && in_array($options['size'], self::OffCanvasSize, true)) {
			$class[] = "offcanvas-{$options['size']}";
		}

		if (!empty($options['position']) && in_array($options['position'], self::OffCanvasPosition, true)) {
			$class[] = "offcanvas-{$options['position']}";
		}

		return $class;
	}


	// Modal
	public function modalDialogClass(array $dialog = []): array
	{
		$class = ['modal-dialog'];

		if (!empty($dialog['size']) && in_array($dialog['size'], self::ModalSize, true)) {
			$class[] = "modal-{$dialog['size']}";
		}

		if (!empty($dialog['centered'])) {
			$class[] = 'modal-dialog-centered';
		}

		if (!empty($dialog['scrollable'])) {
			$class[] = 'modal-dialog-scrollable';
		}

		if (!empty($dialog['fullscreen'])) {
			$class[] = is_string($dialog['fullscreen'])
			&& in_array($dialog['fullscreen'], self::ModalFullscreen, true)
				? "modal-fullscreen-{$dialog['fullscreen']}-down"
				: 'modal-fullscreen';
		}

		return $class;
	}
}

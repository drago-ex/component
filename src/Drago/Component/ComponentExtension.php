<?php

declare(strict_types=1);

namespace Drago\Component;

use Latte\Extension;


class ComponentExtension extends Extension
{
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
			'offCanvasSize' => [$this, 'offCanvasSize'],
			'modalDialogClass' => [$this, 'modalDialogClass'],
		];
	}


	// Offcanvas position
	public function offCanvasClass(array $options = []): array
	{
		$class = ['offcanvas'];

		if (!empty($options['position'])) {
			$class[] = 'offcanvas-' . $options['position'];
		}

		return $class;
	}


	// Offcanvas style width
	public function offCanvasSize(array $options = []): ?string
	{
		return !empty($options['size']) ? "width: {$options['size']}" : null;
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

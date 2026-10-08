import Dropdown from "bootstrap/js/dist/dropdown";

export default class BootstrapDropdowns {

	initialize(naja) {
		initBootstrapDropdowns();

		document.addEventListener("DOMContentLoaded", () => {
			initBootstrapDropdowns();
		});

		naja.snippetHandler.addEventListener("afterUpdate", (e) => {
			initBootstrapDropdowns(e.detail.snippet);
		});
	}
}

export function initBootstrapDropdowns(root = document) {
	root
		.querySelectorAll('[data-bs-toggle="dropdown"]')
		.forEach((toggle) => {
			if (toggle.dataset.bootstrapDropdownInitialized) {
				return;
			}

			toggle.dataset.bootstrapDropdownInitialized = "1";
			toggle.addEventListener("click", (event) => {
				event.preventDefault();
				event.stopPropagation();

				if (!toggle.classList.contains("show")) {
					hideOtherDropdowns(toggle);
				}

				Dropdown.getOrCreateInstance(toggle).toggle();
			});
		});
}

function hideOtherDropdowns(currentToggle) {
	document
		.querySelectorAll('[data-bs-toggle="dropdown"]')
		.forEach((toggle) => {
			if (toggle !== currentToggle) {
				Dropdown.getInstance(toggle)?.hide();
			}
		});
}

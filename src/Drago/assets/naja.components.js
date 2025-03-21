import naja from "naja";
import { Modal, Offcanvas } from "bootstrap";

// Function to initialize Bootstrap components (Modal and Offcanvas)
function initBsComponents(el) {
	// Modal
	el.querySelectorAll('.modal').forEach(modal => {
		if (!modal._bsModal) {
			modal._bsModal = new Modal(modal, {
				keyboard: false
			});
		}
	});

	// Offcanvas
	el.querySelectorAll('.offcanvas').forEach(offCanvas => {
		if (!offCanvas._bsOffcanvas) {
			offCanvas._bsOffcanvas = new Offcanvas(offCanvas);
		}
	});
}

document.addEventListener('DOMContentLoaded', () => initBsComponents(document));
naja.snippetHandler.addEventListener('afterUpdate', (e) => {
	initBsComponents(e.detail.snippet);
});

naja.addEventListener('complete', (e) => {
	const payload = e.detail.payload;
	const doc = document;

	if (payload) {
		if (payload.modal) {
			let modal = doc.querySelector('#' + payload.modal);
			if (!modal.classList.contains('modal')) {
				modal = modal.querySelector('.modal');
			}

			if (modal?._bsModal) {
				modal._bsModal.show();
			}
		}

		if (payload.offcanvas) {
			let offCanvas = doc.querySelector('#' +  payload.offcanvas);
			if (!offCanvas.classList.contains('offcanvas')) {
				offCanvas = offCanvas.querySelector('.offcanvas');
			}

			if (offCanvas?._bsOffcanvas) {
				offCanvas._bsOffcanvas.show();
			}
		}

		if (payload.close){
			doc.querySelector('.offcanvas.show')?._bsOffcanvas?.hide();
			doc.querySelector('.modal.show')?._bsModal?.hide();
		}
	}
});

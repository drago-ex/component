import { Modal, Offcanvas } from "bootstrap";

export default class BootstrapComponents {

	initialize(naja) {
		this.init(document);

		document.addEventListener('DOMContentLoaded', () => {
			this.init(document);
		});

		naja.snippetHandler.addEventListener('afterUpdate', (e) => {
			this.init(e.detail.snippet);
		});

		naja.addEventListener('complete', (e) => {
			this.handlePayload(e.detail.payload);
		});
	}

	init(el) {
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

	handlePayload(payload) {
		if (!payload) return;

		const doc = document;

		if (payload.modal) {
			let modal = doc.querySelector('#' + payload.modal);
			modal = modal?.classList.contains('modal')
				? modal
				: modal?.querySelector('.modal');

			modal?._bsModal?.show();
		}

		if (payload.offcanvas) {
			let offCanvas = doc.querySelector('#' + payload.offcanvas);
			offCanvas = offCanvas?.classList.contains('offcanvas')
				? offCanvas
				: offCanvas?.querySelector('.offcanvas');

			offCanvas?._bsOffcanvas?.show();
		}

		if (payload.close) {
			doc.querySelector('.offcanvas.show')?._bsOffcanvas?.hide();
			doc.querySelector('.modal.show')?._bsModal?.hide();
		}
	}
}

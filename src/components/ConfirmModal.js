import { __ } from '@wordpress/i18n';
import { Modal, Button } from '@wordpress/components';

export default function ConfirmModal({ open, title, message, confirmLabel, cancelLabel, onClose, onConfirm }) {
	if (!open) return null;

	const finalTitle = title || __('Confirm', 'planet4-child-theme-switzerland');
	const finalMessage = message || __('Are you sure?', 'planet4-child-theme-switzerland');
	const finalConfirm = confirmLabel || __('Remove', 'planet4-child-theme-switzerland');
	const finalCancel = cancelLabel || __('Cancel', 'planet4-child-theme-switzerland');

	return (
		<Modal title={finalTitle} onRequestClose={onClose}>
			<p>{finalMessage}</p>
			<div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
				<Button variant="secondary" onClick={onClose}>
					{finalCancel}
				</Button>
				<Button isDestructive onClick={onConfirm}>
					{finalConfirm}
				</Button>
			</div>
		</Modal>
	);
}

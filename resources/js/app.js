import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuBtn = document.getElementById('kp-mobile-menu-btn');
    const mobileMenuPanel = document.getElementById('kp-mobile-menu-panel');
    if (mobileMenuBtn && mobileMenuPanel) {
        mobileMenuBtn.addEventListener('click', () => {
            const isHidden = mobileMenuPanel.classList.toggle('hidden');
            mobileMenuBtn.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
        });
    }

    const adminSidebarBtn = document.getElementById('kp-admin-sidebar-btn');
    const adminSidebar = document.getElementById('kp-admin-sidebar');
    const adminOverlay = document.getElementById('kp-admin-overlay');
    if (adminSidebarBtn && adminSidebar) {
        const toggleSidebar = () => {
            adminSidebar.classList.toggle('-translate-x-full');
            if (adminOverlay) {
                adminOverlay.classList.toggle('hidden');
            }
        };
        adminSidebarBtn.addEventListener('click', toggleSidebar);
        if (adminOverlay) {
            adminOverlay.addEventListener('click', toggleSidebar);
        }
    }

    const amountInput = document.getElementById('donation-amount-input');
    const quickAmountBtns = document.querySelectorAll('[data-quick-amount]');
    if (amountInput && quickAmountBtns.length > 0) {
        quickAmountBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                const value = btn.getAttribute('data-quick-amount');
                amountInput.value = value;
                quickAmountBtns.forEach((b) => {
                    b.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-600', 'shadow-xs');
                    b.classList.add('bg-white', 'text-[#122620]', 'border-[#d7e2da]');
                });
                btn.classList.remove('bg-white', 'text-[#122620]', 'border-[#d7e2da]');
                btn.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600', 'shadow-xs');
            });
        });
    }

    const openModal = (modal) => {
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    const closeModal = (modal) => {
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    const editUpdateModal = document.getElementById('kp-edit-update-modal');
    const editUpdateForm = document.getElementById('kp-edit-update-form');
    if (editUpdateModal && editUpdateForm) {
        document.querySelectorAll('[data-edit-update]').forEach((btn) => {
            btn.addEventListener('click', () => {
                editUpdateForm.setAttribute('action', btn.getAttribute('data-action') || '');
                document.getElementById('kp-edit-update-campaign').textContent = btn.getAttribute('data-campaign-title') || '';
                document.getElementById('edit_update_title').value = btn.getAttribute('data-title') || '';
                document.getElementById('edit_update_content').value = btn.getAttribute('data-content') || '';
                document.getElementById('edit_update_published_at').value = btn.getAttribute('data-published-at') || '';
                document.getElementById('edit_update_image').value = '';
                openModal(editUpdateModal);
            });
        });
    }

    const editDisbursementModal = document.getElementById('kp-edit-disbursement-modal');
    const editDisbursementForm = document.getElementById('kp-edit-disbursement-form');
    if (editDisbursementModal && editDisbursementForm) {
        document.querySelectorAll('[data-edit-disbursement]').forEach((btn) => {
            btn.addEventListener('click', () => {
                editDisbursementForm.setAttribute('action', btn.getAttribute('data-action') || '');
                document.getElementById('kp-edit-disbursement-campaign').textContent = btn.getAttribute('data-campaign-title') || '';
                document.getElementById('edit_disbursement_title').value = btn.getAttribute('data-title') || '';
                document.getElementById('edit_disbursement_amount').value = btn.getAttribute('data-amount') || '';
                document.getElementById('edit_disbursement_recipient').value = btn.getAttribute('data-recipient-name') || '';
                document.getElementById('edit_disbursement_purpose').value = btn.getAttribute('data-purpose') || '';
                document.getElementById('edit_disbursement_date').value = btn.getAttribute('data-disbursed-at') || '';
                document.getElementById('edit_disbursement_description').value = btn.getAttribute('data-description') || '';
                document.getElementById('edit_disbursement_document').value = '';
                openModal(editDisbursementModal);
            });
        });
    }

    document.querySelectorAll('[data-close-modal]').forEach((btn) => {
        btn.addEventListener('click', () => {
            closeModal(document.getElementById(btn.getAttribute('data-close-modal')));
        });
    });

    [editUpdateModal, editDisbursementModal].forEach((modal) => {
        if (!modal) return;
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal(modal);
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal(editUpdateModal);
            closeModal(editDisbursementModal);
        }
    });
});

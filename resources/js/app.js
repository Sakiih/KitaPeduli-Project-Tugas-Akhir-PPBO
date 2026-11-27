import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuBtn = document.getElementById('kp-mobile-menu-btn');
    const mobileMenuPanel = document.getElementById('kp-mobile-menu-panel');
    if (mobileMenuBtn && mobileMenuPanel) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenuPanel.classList.toggle('hidden');
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
});

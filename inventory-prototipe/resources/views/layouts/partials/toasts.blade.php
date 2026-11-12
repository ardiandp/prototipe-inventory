<!-- Global Toast Notification Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;" id="globalToastContainer"></div>

<script>
  /**
   * Reusable Toast Notification Helper for Inventory Prototype
   * @param {string} message - Isi pesan
   * @param {string} type - 'success' | 'danger' | 'warning' | 'info'
   * @param {string} title - Judul notifikasi
   */
  window.showToast = function(message, type = 'success', title = 'Notifikasi') {
    const container = document.getElementById('globalToastContainer');
    if (!container) return;

    const iconMap = {
      success: 'bi-check-circle-fill text-success',
      danger: 'bi-x-circle-fill text-danger',
      warning: 'bi-exclamation-triangle-fill text-warning',
      info: 'bi-info-circle-fill text-info'
    };
    const icon = iconMap[type] || iconMap.info;

    const toastId = 'toast-' + Date.now();
    const toastHtml = `
      <div id="${toastId}" class="toast shadow-lg border" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
        <div class="toast-header border-bottom">
          <i class="bi ${icon} me-2 fs-6"></i>
          <strong class="me-auto">${title}</strong>
          <small class="text-secondary">Baru saja</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body small text-body">
          ${message}
        </div>
      </div>
    `;

    container.insertAdjacentHTML('beforeend', toastHtml);
    const toastEl = document.getElementById(toastId);
    if (toastEl && typeof bootstrap !== 'undefined') {
      const bsToast = new bootstrap.Toast(toastEl);
      bsToast.show();
      toastEl.addEventListener('hidden.bs.toast', () => {
        toastEl.remove();
      });
    }
  };
</script>


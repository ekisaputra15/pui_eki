import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getDatabase, ref, onValue, onChildAdded } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";

/**
 * Firebase Real-time Notification Client
 */
export function initFirebaseNotifications(config, userRole, latestOrderId, initialStatus) {
    if (!config.apiKey || !config.databaseURL) {
        console.warn("Firebase config missing. Notifications disabled.");
        return;
    }

    // Initialize Firebase
    const app = initializeApp(config);
    const db = getDatabase(app);

    console.log("Firebase Monitoring Started:", { userRole, latestOrderId, initialStatus });

    let lastStatus = initialStatus;

    /**
     * Helper to refresh only a specific part of the DOM via AJAX
     */
    async function refreshDynamicContent(containerSelector) {
        console.log(`Refreshing container: ${containerSelector}...`);
        try {
            const response = await fetch(window.location.href);
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector(containerSelector);
            const oldContainer = document.querySelector(containerSelector);
            
            if (newContent && oldContainer) {
                oldContainer.innerHTML = newContent.innerHTML;
                console.log(`Container ${containerSelector} updated successfully.`);
                
                // Play sound for customer on status change
                if (userRole === 'customer') playNotificationSound();
            }
        } catch (error) {
            console.error("AJAX Refresh failed:", error);
        }
    }

    // --- 1. Staff Listener (Role-based) ---
    if (userRole && userRole !== 'customer') {
        const notificationsRef = ref(db, `notifications/${userRole}`);
        let firstLoad = true;
        
        onChildAdded(notificationsRef, (snapshot) => {
            if (firstLoad) return;
            
            const data = snapshot.val();
            showToast(data.message, data.type || 'info');
            playNotificationSound();

            if (document.getElementById('dashboard-main-content')) {
                refreshDynamicContent('#dashboard-main-content');
            }
        });
        
        setTimeout(() => { firstLoad = false; }, 2000);
    }

    // --- 2. Customer Order Tracker ---
    if (latestOrderId) {
        const orderRef = ref(db, `order_updates/${latestOrderId}`);
        let trackerInitialized = false;

        onValue(orderRef, (snapshot) => {
            const data = snapshot.val();
            if (data && data.status) {
                const firebaseStatus = data.status.toString().toLowerCase().trim();
                const localStatus = (lastStatus || "").toString().toLowerCase().trim();

                console.log("Tracking Update:", { firebaseStatus, localStatus });

                // If status from Firebase is different from what we have locally
                if (firebaseStatus !== localStatus) {
                    console.log(`Status changed detectable: ${localStatus} -> ${firebaseStatus}`);
                    lastStatus = data.status;

                    // Update UI if we are on the status page
                    if (document.getElementById('status-main-content')) {
                        showToast(`Status Pesanan: ${data.status}`, 'success');
                        refreshDynamicContent('#status-main-content');
                    } else {
                        // If not on status page, just show notification
                        showToast(`Update Pesanan: ${data.status}`, 'success');
                    }
                }
            }
            trackerInitialized = true;
        });
    }

    /**
     * UI: Toast Notifications
     */
    function showToast(message, type = 'info') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl transform transition-all duration-300 translate-y-10 opacity-0`;
        toast.style.background = 'white';
        toast.style.border = '1px solid var(--color-border-subtle)';
        toast.style.zIndex = '9999';

        let icon = 'fa-info-circle';
        let iconColor = 'text-blue-500';
        if (type === 'success') { icon = 'fa-check-circle'; iconColor = 'text-brand-deep'; }
        else if (type === 'warning') { icon = 'fa-exclamation-triangle'; iconColor = 'text-orange-500'; }

        toast.innerHTML = `
            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 bg-surface">
                <i class="fa-solid ${icon} ${iconColor}"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-wider text-ink-faint">Update Sistem</p>
                <p class="text-sm font-medium text-ink leading-tight mt-0.5">${message}</p>
            </div>
        `;

        container.appendChild(toast);
        setTimeout(() => toast.classList.remove('translate-y-10', 'opacity-0'), 10);
        setTimeout(() => {
            toast.classList.add('opacity-0', 'scale-95');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    function playNotificationSound() {
        const audio = document.getElementById('notification-sound');
        if (audio) audio.play().catch(() => {});
    }
}

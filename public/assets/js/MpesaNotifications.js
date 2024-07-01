document.addEventListener('DOMContentLoaded', function() {
    function fetchNotifications() {
        fetch('/notifications')
            .then(response => response.json())
            .then(data => {
                const notificationList = document.getElementById('notification-list');
                notificationList.innerHTML = '';
                data.forEach(notification => {
                    const notificationItem = document.createElement('a');
                    notificationItem.href = '#!';
                    notificationItem.classList.add('flex', 'gap-3', 'p-4', 'product-item', 'hover:bg-slate-50', 'dark:hover:bg-zink-500', 'mention');
                    notificationItem.innerHTML = `
                        <div class="w-10 h-10 bg-yellow-100 rounded-md shrink-0">
                            <img src="assets/images/inboxCash.jpeg" alt="" class="rounded-md">
                        </div>
                        <div class="grow">
                            <h6 class="mb-1 font-medium"><b>${notification.phone_number}</b> payment made</h6>
                            <p class="mb-3 text-sm text-slate-500 dark:text-zink-300"><i data-lucide="clock" class="inline-block w-3.5 h-3.5 mr-1"></i> <span class="align-middle">${new Date(notification.transaction_time).toLocaleString()}</span></p>
                        </div>
                        <div class="flex items-center self-start gap-2 text-xs text-slate-500 shrink-0 dark:text-zink-300">
                            <div class="w-1.5 h-1.5 bg-custom-500 rounded-full"></div> ${new Date(notification.created_at).toLocaleTimeString()}
                        </div>
                    `;
                    notificationList.appendChild(notificationItem);
                });
            });
    }

    fetchNotifications();
    setInterval(fetchNotifications, 30000); // Fetch notifications every 30 seconds
});
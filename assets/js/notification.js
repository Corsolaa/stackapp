const notification_center = document.querySelector('.notification_center');

function getNotificationBubble(message, type = '') {
    const notification = document.createElement('div');
    notification.className = 'notification ' + type;
    notification.textContent = message;

    notification.addEventListener('click', () => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 200);
    });

    notification_center.appendChild(notification);

    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 200);
        }
    }, 5000);
}

function notificationBad(message) {
    getNotificationBubble(message, 'bad');
}

function notificationGood(message) {
    getNotificationBubble(message, 'good');
}

function notificationInfo(message) {
    getNotificationBubble(message, 'info');
}

function notificationWarning(message) {
    getNotificationBubble(message);
}
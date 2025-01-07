const publicVapidKey = 'BAOO_MfCdczWnVkAc90mWVNkGULis50nhp0p_E1LQKDBCXTl7BVdIjwhB3Wh5xZlJCxwa_xY2pzgByepTJUYLsY';

function registerServiceWorker(button) {
    let user_id = button.parentElement.querySelector('#username').value;
    let name = button.parentElement.querySelector('#name').value;

    if ('serviceWorker' in navigator && 'PushManager' in window) {
        navigator.serviceWorker.register('/assets/js/service_worker.js')
            .then(registration => {
                console.log('Service Worker registered');

                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        subscribeUser(user_id, name, registration);
                    } else {
                        console.error('Notification permission denied');
                    }
                });
            })
            .catch(err => console.error('Service Worker registration failed:', err));
    }
}

function subscribeUser(user_id, name, registration) {
    registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(publicVapidKey)
    })
        .then(async subscription => {
            console.log('Push subscription:', subscription);
            console.log("Pretty Subscription Object:", JSON.stringify(subscription, null, 2));
            console.log("-----------------------")
            console.log(subscription.getKey('p256dh'))

            const filteredSubscription = {
                user_id: user_id,
                name: name,
                endpoint: subscription.endpoint,
                p256dh: btoa(String.fromCharCode.apply(
                    null, new Uint8Array(subscription.getKey('p256dh'))
                )),
                auth: btoa(String.fromCharCode.apply(
                    null, new Uint8Array(subscription.getKey('auth'))
                ))
            };

            console.table(filteredSubscription)

            // await fetch('https://app.stacksats.ai/subscribe?register', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json'
            //     },
            //     body: JSON.stringify(filteredSubscription)
            // })
            //     .then(response => response.text())
            //     .then(data => {
            //         console.log(data);
            //     });
        })
        .catch(err => console.error('Failed to subscribe user:', err));
}
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

function openSendSiteNewTab() {
    window.open("https://app.stacksats.ai/subscribe?send", "_blank");
}

function shake(element) {
    console.log(element);
    element.classList.add('shake');

    element.addEventListener('animationend', () => {
        element.classList.remove('shake');
    }, { once: true })
}
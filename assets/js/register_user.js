function register_user(button) {
    const parentElement = button.parentElement;

    const requestBody = {
        email: parentElement.querySelector("#email").value,
        username: parentElement.querySelector("#username").value,
        password: parentElement.querySelector("#password").value,
    };

    console.log("Collected Data:", requestBody);

    fetch("https://app.stacksats.ai/api/user?register", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(requestBody),
    })
        .then(response => {
            return response.json();
        })
        .then(result => {
            processResult(result);
        });
}

function processResult(api_response) {
    const keys_to_check = ['success', 'message'];

    if (keys_to_check.every(key => Object.hasOwn(api_response, key)) === false) {
        notificationBad(';( Wrong return type, contact support')
        console.log(api_response);
        return;
    }

    if (api_response['success']) {
        notificationGood(api_response['message'])
    } else {
        notificationBad(api_response['message'])
    }
}
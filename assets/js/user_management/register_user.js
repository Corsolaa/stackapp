function register_user(button) {
    const parentElement = button.parentElement;

    const requestBody = {
        email: parentElement.querySelector("#register_email").value,
        username: parentElement.querySelector("#register_username").value,
        password: parentElement.querySelector("#register_password").value,
    };

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
            if (processResult(result) === false) {
                console.log('hello');
                shake(button);
            }
        });
}

function processResult(api_response) {
    const keys_to_check = ['success', 'message'];

    if (keys_to_check.every(key => Object.hasOwn(api_response, key)) === false) {
        notificationBad(';( Wrong return type, contact support');
    }

    if (api_response['success']) {
        notificationGood(api_response['message']);
        return true;
    } else {
        notificationBad(api_response['message']);
        return false;
    }
}
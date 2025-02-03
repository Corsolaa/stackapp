function password_reset(button) {
    const parentElement = button.parentElement;

    const requestBody = {
        password: parentElement.querySelector("#forgot_password").value,
        token: parentElement.querySelector("#forgot_token").value,
    };

    fetch("https://app.stacksats.ai/api/user?process_password_reset", {
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
                shake(button);
            }
        });

    function processResult(api_response) {
        const keys_to_check = ['success', 'data'];

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
}
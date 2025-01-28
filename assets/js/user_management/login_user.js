function login_user(button) {
    const parentElement = button.parentElement;

    const requestBody = {
        email: parentElement.querySelector("#login_email").value,
        password: parentElement.querySelector("#login_password").value,
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
            if (processResult(result) === false) {
                console.log('hello');
                shake(button);
            }
        });

    function processResult(api_response) {
        const keys_to_check = ['success', 'data'];

        if (keys_to_check.every(key => Object.hasOwn(api_response, key)) === false) {
            notificationBad(';( Wrong return type, contact support');
            console.log(api_response);
        }

        if (api_response['success']) {
            notificationGood('login as successful');
            return true;
        } else {
            notificationBad('Login failed 🥲');
            return false;
        }
    }
}
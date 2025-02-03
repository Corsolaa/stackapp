function login_user(button) {
    const parentElement = button.parentElement;

    const requestBody = {
        email: parentElement.querySelector("#login_email").value,
        password: parentElement.querySelector("#login_password").value,
    };

    fetch("https://app.stacksats.ai/api/user?login", {
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
            window.location.href = '/user';
            return true;
        } else {
            notificationBad('Login failed 🥲');
            return false;
        }
    }
}

function request_password_reset(button) {
    const parentElement = button.parentElement;
    const valueElement = parentElement.querySelector("#login_email");

    if (checkContainerHasValue(valueElement) === false) {
        shake(button)
        notificationBad('Please fill in the red container')
        return;
    }

    const requestBody = {
        email: valueElement.value
    };

    fetch("https://app.stacksats.ai/api/user?password_reset", {
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
            notificationInfo('If account is present, password reset link is send to email');
        });
}
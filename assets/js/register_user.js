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
            console.log("Result from Register API:", result);
        });
}
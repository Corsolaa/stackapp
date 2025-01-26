function password_eye(eye) {
    const password_input = eye.parentElement.querySelector('#password');
    console.log(password_input);

    if (password_input.type === 'password') {
        eye.innerText = '🔒';
        password_input.type = 'text';
    } else {
        eye.innerText = '👁️';
        password_input.type = 'password';
    }
}
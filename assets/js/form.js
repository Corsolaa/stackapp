function password_eye(eye) {
    const password_input = eye.parentElement.querySelector('input');

    if (password_input.type === 'password') {
        eye.innerText = '🔒';
        password_input.type = 'text';
    } else {
        eye.innerText = '👁️';
        password_input.type = 'password';
    }
}

function checkContainerHasValue(element) {
    if (element.value === '') {
        element.classList.add('empty');
        return false;
    }

    element.classList.remove('empty');
}
function select_register(button) {
    select_button('register');
    select_form(button.parentElement.parentElement, 'user_register');
}

function select_login(button) {
    select_button('login');
    select_form(button.parentElement.parentElement, 'user_login');
}

function select_password_forgot(button) {
    select_button('password_reset');
    select_form(button.parentElement.parentElement, 'user_password_reset');
}

function select_button(button_class) {
    const button_selection = document.querySelector('.selection_buttons');
    const buttons = button_selection.querySelectorAll('div');

    buttons.forEach(button => {
        button.classList.remove("selected");

        if (button.classList.contains(button_class)) {
            button.classList.add("selected");
        }
    });
}

function select_form(parent_element, form_class) {
    const forms = parent_element.querySelectorAll('.form');

    forms.forEach(form => {
        form.classList.add("hidden");

        if (form.classList.contains(form_class)) {
            form.classList.remove("hidden");
        }
    });
}
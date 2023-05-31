const form = document.getElementById('form');
const username = document.getElementById('username');
const login =document.getElementById('login');
const email = document.getElementById('email');
const password = document.getElementById('password');
const passwordtwo = document.getElementById('passwordtwo');

username.addEventListener('input', checkUsername);
login.addEventListener('input', checkLogin);
email.addEventListener('input', checkEmail);
password.addEventListener('input', checkPassword);
passwordtwo.addEventListener('input', checkPasswordTwo);

function checkUsername() {
    const usernameValue = username.value.trim();

    if (usernameValue === '') {
        setErrorFor(username, 'Preencha esse campo');
    } else {
        setSuccessFor(username);
    }
}

function checkLogin() {
    const loginValue = login.value.trim();

    if (loginValue === '') {
        setErrorFor(login, 'Preencha esse campo');
    } else {
        setSuccessFor(login);
    }
}

function checkEmail() {
    const emailValue = email.value.trim();

    if (emailValue === '') {
        setErrorFor(email, 'Preencha esse campo');
    } else if (!isEmail(emailValue)) {
        setErrorFor(email, 'Email inválido');
    } else {
        setSuccessFor(email);
    }
}

function checkPassword() {
    const passwordValue = password.value.trim();

    if (passwordValue === '') {
        setErrorFor(password, 'Preencha esse campo');
    } else if (passwordValue.length < 8) {
        setErrorFor(password, 'Senha deve ter mais que 8 caracteres');
    } else {
        setSuccessFor(password);
    }
}

function checkPasswordTwo() {
    const passwordValue = password.value.trim();
    const passwordtwoValue = passwordtwo.value.trim();

    if (passwordtwoValue === '') {
        setErrorFor(passwordtwo, 'Preencha esse campo');
    } else if (passwordValue !== passwordtwoValue) {
        setErrorFor(passwordtwo, 'Senhas não tão iguais');
    } else {
        setSuccessFor(passwordtwo);
    }
}

function setErrorFor(input, message) {
    const formControl = input.parentElement;
    const small = formControl.querySelector('small');

    small.innerText = message;
    formControl.className = 'form-control error';
}

function setSuccessFor(input) {
    const formControl = input.parentElement;
    formControl.className = 'form-control success';
}

function isEmail(email) {
    return /^(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/.test(email);
}

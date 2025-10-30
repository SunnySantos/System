import './bootstrap';


function showHidePassword(config = []) {
    if (!Array.isArray(config) || config.length === 0) {
        return;
    }

    config.forEach(({
        fieldId,
        toggleId
    }) => {
        const passwordField = document.getElementById(fieldId);
        const toggle = document.getElementById(toggleId);

        console.log(passwordField, toggle);


        if (toggle && passwordField) {
            toggle.addEventListener('change', function (e) {
                passwordField.type = e.target.checked ? 'text' : 'password';
            });
        }
    });
}

window.showHidePassword = showHidePassword;
const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const btnPopup = document.querySelector('.btnLogin-popup');
const iconClose = document.querySelector('.icon-close');

registerLink.addEventListener('click', ()=> {
    wrapper.classList.add('active');
})

loginLink.addEventListener('click', ()=> {
    wrapper.classList.remove('active');
})

btnPopup.addEventListener('click', ()=> {
    wrapper.classList.add('active-popup');
})

iconClose.addEventListener('click', ()=> {
    wrapper.classList.remove('active-popup');
})

document.addEventListener("DOMContentLoaded", function () {
    const neptunInputFields = document.querySelectorAll('input[name="neptun"]');
    const passwordInputFields = document.querySelectorAll('input[name="pwd"], input[name="password"]');
    
    neptunInputFields.forEach(input => {
        input.addEventListener('input', function () {

            if (this.value.length !== 6) {
                this.setCustomValidity("A Neptun-kódnak pontosan 6 karakter hosszúnak kell lennie!");
            } else {
                this.setCustomValidity("");
            }
        });
    });

    // Jelszó validáció
    passwordInputFields.forEach(input => {
        input.addEventListener('input', function () {
            const value = this.value;
            const passwordPattern = /^(?=.*\d\d$).{8,}$/; 

            if (!passwordPattern.test(value)) {
                this.setCustomValidity("A jelszónak legalább 8 karakter hosszúnak kell lennie, és az utolsó két karakter szám legyen!");
            } else {
                this.setCustomValidity("");
            }
        });
    });
});
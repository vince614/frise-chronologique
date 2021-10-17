class register {
    __construct() {
        this.passwordMinLength = 10;
        this.usernameInput = $('#username');
        this.emailInput = $('#email');
        this.passwordInput = $('#password');
        this.comfirmPasswordInput = $('#comfirm-password');
        this.error = null;
    }

    /**
     * Get all value in selectors
     */
    getValues() {
        this.username = this.usernameInput.val();
        this.email = this.emailInput.val();
        this.password = this.passwordInput.val();
        this.comfirmPassword = this.comfirmPasswordInput.val();
    }

    /**
     * Validate email
     *
     * @param email
     * @return {boolean}
     */
    validateEmail(email) {
        let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    /**
     * Validate password
     *
     * @param password
     * @return {boolean}
     */
    validatePassword(password) {
        return password.length >= this.passwordMinLength;
    }

    /**
     * Check both passwords
     *
     * @return {boolean}
     */
    checkPasswords() {
        return this.password === this.comfirmPassword;
    }

    /**
     * Send data
     */
    send() {
        $.post('/contact', {
            type: 'register',
            username: this.username,
            email: this.email,
            password: this.password
        }).done(function (datas) {
            let data = JSON.parse(datas);
            if (data.error) {
                return swalError(data.error);
            } else if(data.success) {
                return swalSuccess(data.success);
            }
        });
    }

    run() {
        this.getValues();
        if (this.validateEmail(this.email)) {
            if (this.validatePassword(this.password)) {
                if (this.checkPasswords()) {
                    this.send();
                } else {
                    this.error = "Vos mot de passes ne correspodent pas.";
                }
            } else {
                this.error = "Votre mot de passe dois contenir plus de " + this.passwordMinLength + " caractères.";
            }
        } else {
            this.error = "Merci de rentrez un email valide.";
        }
    }

}

const registerButton = $('#register-button');
registerButton.click(function () {
    let register = new register();
    register.run();
});
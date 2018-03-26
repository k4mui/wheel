function disable_button(form_name) {
    if (document[form_name]) {
        document[form_name].submit.disabled = true;
    }
}
function enable_button(form_name) {
    if (document[form_name]) {
        document[form_name].submit.disabled = false;
    }
}
function test_alert() {
    alert(1);
}
function valid_password(p) {
    if (p.length < 8) {
        return 'Password too short';
    }
}
function ID(id) {
    return document.getElementById(id);
}
function check_login_form() {
    ID('side_error_pw').innerHTML = '';
    ID('side_error_ea').innerHTML = '';
    e = document.login_form.email_address.value;
    p = document.login_form.password.value;
    ec = 0;

    if (p.length < 8) {
        ec++;
        ID('side_error_pw').innerHTML = '* Password too short';
    }
    if (e.length < 1) {
        ec++;
        ID('side_error_ea').innerHTML = '* Email cannot be empty';
    } else if (e.indexOf('@') < 1 || e.indexOf('@') > e.length - 2) {
        ec++;
        ID('side_error_ea').innerHTML = '* Email looks invalid';
    }

    if (ec) {
        disable_button('login_form');
    } else {
        enable_button('login_form');
    }
}
function check_reg_form() {
    ID('side_error_pw').innerHTML = '';
    ID('side_error_cpw').innerHTML = '';
    ID('side_error_ea').innerHTML = '';
    e = document.reg_form.email_address.value;
    p = document.reg_form.password.value;
    cp = document.reg_form.confirm_password.value;
    ec = 0;

    if (p.length < 8) {
        ec++;
        ID('side_error_pw').innerHTML = '* Password too short';
    }
    if (p !== cp) {
        ec++;
        ID('side_error_cpw').innerHTML = '* Password does not match';
    }
    if (e.length < 1) {
        ec++;
        ID('side_error_ea').innerHTML = '* Email cannot be empty';
    } else if (e.indexOf('@') < 1 || e.indexOf('@') > e.length - 2) {
        ec++;
        ID('side_error_ea').innerHTML = '* Email looks invalid';
    }

    if (ec) {
        disable_button('reg_form');
    } else {
        enable_button('reg_form');
    }
}
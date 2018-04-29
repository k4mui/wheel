function serialize_object(o) {
    var q = '';
    for(var k in o) {
        if (q != '') {
            q += '&';
        }
        q += (k + '=' + encodeURIComponent(o[k]));
    }
    return q;
}

function ID(id) {
    return document.getElementById(id);
}
function ajax(method, url, onReadyStateChange) {
    var x;
    if (window.XMLHttpRequest) {
        x = new XMLHttpRequest();
    } else {
        x = new ActiveXObject('Microsoft.XMLHTTP');
    }
    x.onreadystatechange = onReadyStateChange;
    x.open(method, url, true);
    x.send();
}

function api(path, params, callback) {
    ajax('GET', '/api/v1/' + path + '.php?' + serialize_object(params), callback);
}
function append_html(id, html_to_append) {
    ID(id).insertAdjacentHTML('beforeend', html_to_append);
}
function inner_html(id, html) {
    ID(id).innerHTML = html;
}

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
function check_username() {
    un = document.form.un.value;
    unl = un.length;
    if (unl < 3) {
        document._errors.u = 'Username too short.';
    } else if (unl > 32) {
        document._errors.u = 'Username too long.';
    } else {
        document._errors.u = false;
    }
    process_errors();
}
function check_email() {
    ue = document.form.ue.value;
    uel = ue.length;
    if (uel < 3) {
        document._errors.e = 'Email too short.';
    } else if (uel > 254) {
        document._errors.e = 'Email too long.';
    } else {
        document._errors.e = false;
    }
    process_errors();
}
function check_password() {
    us = document.form.us.value;
    usl = us.length;
    if (usl < 8) {
        document._errors.p = 'Password too short.';
    } else {
        document._errors.p = false;
    }
    process_errors();
}
function check_confirm_password() {
    cus = document.form.cus.value;
    us = document.form.us.value;
    if (cus!=us) {
        document._errors.cp = 'Passwords do not match.';
    } else {
        document._errors.cp = false;
    }
    process_errors();
}
function CN(cn) {
    return document.getElementsByClassName(cn);
}
function process_errors() {
    var error_str = '';
    for(var error in document._errors) {
        if (document._errors[error] && typeof(document._errors[error]) !== 'boolean') {
            if (error_str.length==0) {
                error_str = "<div class='card card-error'><div class='card-body'>";
            }
            error_str += "<div class='list'>"+ document._errors[error] +"</div>";
        }
    }
    if (error_str.length!=0) {
        error_str += '</div></div>';
        ID('submit-button').disabled = true;
    } else {
        ID('submit-button').disabled = false;
    }
    ID('errors-section').innerHTML = error_str;
}
function enable_submit(ref) {
    ref.submit.disabled = false;
}
function disable_submit(ref) {
    ref.submit.disabled = true;
}
function check_reply() {
    r = document.reply_form;
    l = r.dc.value.length;
    if (l > 8) {
        enable_submit(r);
    } else {
        disable_submit(r);
    }
}
window.onload = function () {
    const value = get_cookie('res-msg');
    let msg = null;
    let msg_color = 'red';
    switch (value) {
        case "invalid_username":           msg = 'Username must be at least 3 characters.';                                            break;
        case "password_mismatch":          msg = 'Passwords do not match.';                                                            break;
        case "invalid_password":           msg = 'Password must be at least 6 characters, contain 1 number, and 1 special character.'; break;
        case "taken_username":             msg = 'Username already in use.';                                                           break;
        case "taken_email":                msg = 'Email already in use.';                                                              break;
        case "invalid_login":              msg = 'Invalid username/email or password.';                                                break;
        case "invalid_password_login":     msg = 'Incorrect password.';                                                                break;
        case "register_successful":        msg = 'Registration successful! Log in to continue.'; msg_color = 'green';                  break;
        case "email_change_successful":    msg = 'Email has been successfully changed.';         msg_color = 'green';                  break;
        case "password_change_successful": msg = 'Password has been successfully changed.';      msg_color = 'green';                  break;
        case "account_delete_successful":  msg = 'Account has been deleted.';                    msg_color = 'green';                  break;
        case "unknown_error":              msg = 'An unknown error has occurred.';                                                     break;
    }
    if (msg != null) {
        ohSnap(msg, {color: msg_color, duration: '4000'});
        erase_cookie('res-msg');
    }
};

function get_cookie (cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function erase_cookie (cname) {
    document.cookie = cname +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}
$(document).ready(function() {
    $('#admin-login.login').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'php/auth/verify.php?do=1',
            data: $(this).serialize(),
            success: function(response)
            {
                var jsonData = JSON.parse(response);

                if (jsonData.success == "1")
                {
                    $('#password').val("");
                    $('#email').val("");
                    console.log("logged in ");
                    location.href = '/dashboard';
                }
                else
                {
                    $('#password').val("");
                    $('#password').addClass('form-wrong');
                    $('#form-wrong-message').html('username en of wachtwoord is fout');
                }
           	}
		});	
	});

    $('#admin-login.register').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'php/auth/verify.php?do=2',
            data: $(this).serialize(),
            success: function(response)
            {
                var jsonData = JSON.parse(response);
                if (jsonData.success == "1")
                {
                    console.log("succesfully created your account");
                    location.href = '/dashboard';
                }
                else
                {
                switch(jsonData.error) {
                    case'username':
                        $('#username').addClass('form-wrong');
                        $('#form-wrong-message').html('username has been taken');
                        break;
                    case'email':
                        $('#email').addClass('form-wrong');
                        $('#form-wrong-message').html('email has been taken');
                        break;
                    case'both':
                        $("#username, #email").addClass('form-wrong');
                        $('#form-wrong-message').html('email and username have been taken');
                        break;
                    default:
                        console.log(jsonData.error);
                        break;
                    }
                }
           	}
		});	
	});

    $('#admin-login input').blur(function() {
      if($(this).val() != "") {
        if(!regex($(this).attr('regex'), $(this).val())) {
            $(this).addClass('form-wrong');
            errorsRegex = '';
            switch($(this).attr('regex')) {
                case'none':
                    break;
                case'name':
                    errorsRegex = 'naam moet 4 characters zijn';
                    break;
                case'username':
                    errorsRegex = 'gebruikersnaam moet 4+ characters zijn geen cijfers';
                    break;
                case'dob':
                    errorsRegex = 'vul een correcte leeftijd in (13+)';
                    break;
                case'email':
                    errorsRegex = 'vul een geldig email in';
                    $('#form-wrong-message').html('vul een geldig email in');
                    break;
                case'password':
                    errorsRegex = 'wachtwoord moet 8+ characters lang zijn';
                    break;

            }
        $('#form-wrong-message').html(errorsRegex);


        } else {
            $(this).removeClass('form-wrong');
        }
    }
        if($(this).attr('id') == "repeat-password") {
            if($('#password').val() != $('#repeat-password').val()) {
                $('#repeat-password').addClass('form-wrong');
            }
        }

      });
});



function regex(type, check) {
    let regex;
    switch(type) {
        case'none':
            regex = /^.+$/;
            break
        case'password':
            regex = /^.{8,}$/;
            break;
        case'email':
            regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            break;
        case'email':
            regex = /^[a-zA-Z]{4,}$/;
            break;
        case'username':
            regex = /^[a-zA-Z]{4,}$/;
            break;
        case'dob':
            regex = /^(19[2-9]\d|20\d{2})-(0?[1-9]|1[0-2])-(0?[1-9]|[12]\d|3[01])$/;
            break;
        case'name':
            regex = /^[\p{L}\p{Ll}\p{Lu}]+$/u;
            break;
    }
    if (regex.test(check)) {
        return true;
    } else {
        return false;
    }
}
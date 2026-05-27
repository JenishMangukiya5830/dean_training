$('#contactForm').submit(function () {
    // we stoped it
    event.preventDefault();
    var name = $('#name').val();
    var email = $("#email").val();
    var number = $("#number").val();
    var message = $("#message").val();

    // needs for recaptacha ready
    grecaptcha.ready(function () {
        // do request for recaptcha token
        // response is promise with passed token
        grecaptcha.execute('6LfXscIUAAAAAE1anUk2VLhxzVPN1zpI89WVemJO', {action: 'create_contact'})
            .then(function (token) {
                // add token to form
                $('#contactForm').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                $.post(SITE_URL + "/contact/request", {
                    name: name,
                    email: email,
                    number: number,
                    message: message,
                    token: token
                }, function (result) {
                    if (result.success) {
                        location.reload();
                    } else {
                        alert('Something Went Wrong')
                    }
                });
            });
        ;
    });
});
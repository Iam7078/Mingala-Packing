$(document).ready(function () { // DONEEE
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        iconColor: 'white',
        showConfirmButton: false,
        customClass: {
            popup: 'colored-toast',
        },
        timer: 1500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    
    document.getElementById('btn-login').addEventListener('click', function () {
        
        event.preventDefault();
        var email = $("#InputEmail").val();
        var password = $("#InputPassword").val();

        $.ajax({
            url: '/login-user',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify({ email: email, password: password }),
            contentType: 'application/json',
            success: function (response) {
                if (response.status === 'success') {
                    Toast.fire({
                        icon: 'success',
                        title: "Signed in successfully"
                    }).then(() => {
                        window.location.href = response.redirect;
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message,
                    });
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                Toast.fire({
                    icon: 'error',
                    title: 'An error occurred while sending the request.'
                });
            }
        });
    });
});
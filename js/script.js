function replaceUrlParams(key, value) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set(key, value);
    const newUrl = window.location.pathname + '?' + urlParams.toString();
    window.history.replaceState({}, '', newUrl);
}

function removeUrlParams(key) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.delete(key);
    const newUrl = window.location.pathname + '?' + urlParams.toString();
    window.history.replaceState({}, '', newUrl);
}

function create_account() {

    const loader = document.getElementById('loader');
    const text = document.getElementById('registerBtn');

    const load = () => {
        loader.className = 'loader d-block';
        text.className = 'd-none';
    }

    const hide = () => {
        loader.className = "d-none";
        text.className = "text-white d-block";
    }

    load();

    const fname = document.getElementById("first_name");
    const lname = document.getElementById("last_name");
    const mobile = document.getElementById("mobile");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const agree = document.getElementById("agree");

    const form = new FormData();

    form.append("first_name", fname.value);
    form.append("last_name", lname.value);
    form.append("mobile", mobile.value);
    form.append("email", email.value);
    form.append("password", password.value);
    form.append("agree", agree.checked);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {

        if (request.readyState === 4 && request.status === 200) {

            const response = request.responseText;
            const response_box = document.getElementById("response");
            const alert = document.getElementById("alert");

            if (response == 200) {

                setTimeout(() => {

                    hide();
                    response_box.innerHTML = 'Your account has been created successfully!';
                    response_box.className = "alert alert-success  border-0  rounded-1";
                    alert.className = "d-block";
                    window.location = "sign-in.php";

                }, 1000);

            } else {

                setTimeout(() => {
                    hide();
                    response_box.innerHTML = response;
                    response_box.className = "alert alert-danger border-0 rounded-1";
                    alert.className = "d-block";
                }, 1000);

            }
        }
    }

    request.open("POST", "./back-end/register.php", true);
    request.send(form);
}

function authenticate() {

    const loader = document.getElementById('loader');
    const text = document.getElementById('authBtnSpan');

    const load = () => {
        loader.className = 'loader d-block';
        text.className = 'd-none';
    }

    const hide = () => {
        loader.className = 'd-none';
        text.className = 'text-white d-block';
    }

    load();

    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const rememberMe = document.getElementById("rememberMe");

    const form = new FormData();

    form.append("email", email.value);
    form.append("password", password.value);
    form.append("rememberMe", rememberMe.checked);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {

        if (request.readyState === 4 && request.status === 200) {

            const response = request.responseText;
            const response_box = document.getElementById('response');
            const alert = document.getElementById('alert');

            if (response == 200) {

                setTimeout(() => {

                    hide();

                    response_box.innerHTML = 'Welcome back! You’re now logged in';
                    response_box.className = "alert alert-success border-0 rounded-1";
                    alert.className = "d-block";
                    window.location.href = 'index.php';

                }, 2000);

            } else {

                setTimeout(() => {
                    hide();
                    response_box.innerHTML = response;
                    response_box.className = "alert alert-danger border-0 rounded-1";
                    alert.className = "d-block";
                }, 2000);
            }


        }
    }
    request.open("POST", "./back-end/authenticate.php", true);
    request.send(form);
}

function save_reset_password() {

    let otp = "";

    resetPasswordInputs.forEach((input) => {
        otp += input.value;
    });

    const email = localStorage.getItem('email');

    const form = new FormData();
    form.append('code', otp);
    form.append('email', email);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {

        if (request.readyState === 4 && request.status === 200) {

            const response = request.responseText;
            let message = document.getElementById('verification_response');

            if (response == 200) {

                message.className = 'text-success';
                message.innerHTML = 'Verification successful!';

                document.getElementById('mailVerify').className = 'd-none';
                document.getElementById('sendMail').className = 'd-none';

                const params = new URLSearchParams(window.location.search);
                params.set('stt', 'mss');
                window.history.replaceState({}, '', `${window.location.pathname}?${params}`);

                localStorage.removeItem('otp-email');

                document.getElementById('createPassword').className = 'd-block';

            } else {
                message.className = 'text-danger';
                message.innerHTML = response;
            }

        }
    }

    request.open("POST", "back-end/user-profile-password-reset-process.php", true);
    request.send(form);

}

const resetPasswordInputs = document.querySelectorAll(".reset-otp-field input");

resetPasswordInputs.forEach((input, index) => {
    input.dataset.index = index;
    input.addEventListener("keyup", handleResetOtp);
    input.addEventListener("paste", handleOnPasteResetOtp);
});

function handleResetOtp(e) {
    const input = e.target;
    let value = input.value;
    let isValidInput = value.match(/[0-9a-z]/gi);
    input.value = "";
    input.value = isValidInput ? value[0] : "";

    let fieldIndex = input.dataset.index;
    if (fieldIndex < resetPasswordInputs.length - 1 && isValidInput) {
        input.nextElementSibling.focus();
    }

    if (e.key === "Backspace" && fieldIndex > 0) {
        input.previousElementSibling.focus();
    }

    if (fieldIndex === resetPasswordInputs.length - 1 && isValidInput) {
        setTimeout(() => {
            save_reset_password();
        }, 1000);
    }
}

function handleOnPasteResetOtp(e) {
    const data = e.clipboardData.getData("text");
    const value = data.split("");
    if (value.length === resetPasswordInputs.length) {
        resetPasswordInputs.forEach((input, index) => (input.value = value[index]));
        setTimeout(() => {
            save_reset_password();
        }, 1000);
    }
}

function send_password_reset_code() {

    const email = document.getElementById('email').value;
    const loader = document.getElementById('loader');
    const text = document.getElementById('emailSendBtn');

    const load = () => {
        loader.className = 'loader d-block';
        text.className = 'd-none';
    }

    const hide = () => {
        loader.className = 'd-none';
        text.className = 'text-white d-block';
    }

    load();

    const request = new XMLHttpRequest();

    const form = new FormData();
    form.append('email', email);

    request.onreadystatechange = () => {

        if (request.readyState == 4 && request.status == 200) {

            const response = request.responseText;
            const response_box = document.getElementById('response');

            const mailSendingStep = document.getElementById('sendMail');
            const mailVerifyStep = document.getElementById('mailVerify');

            if (response == 200) {

                response_box.innerHTML = 'Verification code has been sent successfully';
                response_box.className = 'text-success';

                const params = new URLSearchParams(window.location.search);
                params.set('stt', 'vcs');
                window.history.replaceState({}, '', `${window.location.pathname}?${params}`);

                localStorage.setItem('email', email);

                hide();

                setTimeout(() => {

                    mailSendingStep.className = 'd-none';
                    mailVerifyStep.className = 'd-block';

                    document.getElementById('showEmail').innerHTML = "Reset Link Send to : " + email;

                }, 1000);

            } else {

                setTimeout(() => {

                    response_box.innerHTML = response;
                    response_box.className = 'text-danger';
                    hide();

                }, 1000);

            }
        }

    }

    request.open('POST', './back-end/send-reset-password-code-process.php', true);
    request.send(form);

}

function check_reset_password_page_status() {

    const mailSendingStep = document.getElementById('sendMail');
    const mailVerifyStep = document.getElementById('mailVerify');
    const createPasswordStep = document.getElementById('createPassword');

    const params = new URLSearchParams(window.location.search);

    const value = localStorage.getItem('email');

    if (params.get('stt') === 'vcs') {

        if (value) {
            document.getElementById('showEmail').innerHTML = "Reset Link Send to : " + value;
            mailSendingStep.className = 'd-none';
            createPasswordStep.className = 'd-none';
            mailVerifyStep.className = 'd-block';
        }


    } else if (params.get('stt') === 'mss') {


        mailVerifyStep.className = 'd-none';
        mailSendingStep.className = 'd-none';
        createPasswordStep.className = 'd-block';

    } else {
        mailSendingStep.className = 'd-block';
        mailVerifyStep.className = 'd-none';
        createPasswordStep.className = 'd-none';

    }
}

function reset_password() {

    const loader = document.getElementById('createPasswordLoader');
    const text = document.getElementById('changePasswordBtn');
    const email = localStorage.getItem('email');
    const password = document.getElementById('password').value;
    const retypedPassword = document.getElementById('retypedPassword').value;


    const load = () => {
        loader.className = 'loader d-block';
        text.className = 'd-none';
    }

    load();

    const hide = () => {
        loader.className = 'd-none';
        text.className = 'text-white d-block';
    }

    const form = new FormData();

    form.append('email', email);
    form.append('password', password);
    form.append('retypedPassword', retypedPassword);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {

        if (request.readyState === 4 && request.status === 200) {

            const response = request.responseText;
            const response_box = document.getElementById('createPasswordResponse');

            if (response == 200) {

                setInterval(() => {

                    hide();
                    response_box.className = 'text-success';
                    response_box.innerHTML = 'Password changed successfully';
                    localStorage.removeItem('email');

                    setInterval(() => {
                        window.location = 'sign-in.php';
                    }, 1000);

                }, 2000);

            } else {
                setInterval(() => {
                    hide();
                    response_box.className = 'text-danger';
                    response_box.innerHTML = response;
                }, 2000);
            }

        }

    }

    request.open('POST', './back-end/reset-password-process.php', true);
    request.send(form);

}




/* Password visible invisible function */

function ShowPassword() {

    const password = document.getElementById("password");
    const eye = document.getElementById('eye');

    password.type === 'password' ? password.type = 'text' : password.type = 'password';
    eye.className === 'fa-regular fa-eye-slash' ? eye.className = 'fa-regular fa-eye' : eye.className = 'fa-regular fa-eye-slash';

}

function ShowResetPassword() {

    let password = document.getElementById("password");
    let secondPassword = document.getElementById("retypedPassword");

    let eye = document.getElementById('eye');
    let secondEye = document.getElementById('reEye');

    password.type === 'password' ? password.type = 'text' : password.type = 'password';
    eye.className === 'fa-regular fa-eye-slash' ? eye.className = 'fa-regular fa-eye' : eye.className = 'fa-regular fa-eye-slash';

    secondPassword.type === 'password' ? secondPassword.type = 'text' : secondPassword.type = 'password';
    secondEye.className === 'fa-regular fa-eye-slash' ? secondEye.className = 'fa-regular fa-eye' : secondEye.className = 'fa-regular fa-eye-slash';
}

function ModelShowPasswordFirst() {
    const password = document.getElementById("newPassword");

    if (password.type === "password") {
        password.type = "text";
        document.getElementById("eye-first").className = "fa-regular fa-eye";
    } else {
        password.type = "password";
        document.getElementById("eye-first").className = "fa-regular fa-eye-slash";
    }
}

function ModelShowPasswordSecond() {
    const password = document.getElementById("rePassword");

    if (password.type === "password") {
        password.type = "text";
        document.getElementById("eye-second").className = "fa-regular fa-eye";
    } else {
        password.type = "password";
        document.getElementById("eye-second").className = "fa-regular fa-eye-slash";
    }
}

/* Password visible invisible function */





function log_out() {
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {

            const response = request.responseText;

            if (response === "success") {
                window.location.reload();
            }
        }
    }

    request.open("POST", "./back-end/customer-log-out-process.php", true);
    request.send();
}

function select_district() {
    const province_id = document.getElementById("province").value;
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            const response = request.responseText;
            document.getElementById("district").innerHTML = response;
        }
    }
    request.open("GET", "./back-end/district-select-process.php?id=" + province_id, true);
    request.send();
}

function select_city() {
    const district_id = document.getElementById("district").value;
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            const response = request.responseText;
            document.getElementById("city").innerHTML = response;
        }
    }
    request.open("GET", "./back-end/city-select-process.php?id=" + district_id, true);
    request.send();
}

function change_profile_image() {
    const userSelectImage = document.getElementById("imageChoose");

    userSelectImage.onchange = function () {
        const file = this.files[0];
        const url = window.URL.createObjectURL(file);
        document.getElementById("image").src = url;
    }
}

function save_profile_changes() {

    const fname = document.getElementById("fname");
    const lname = document.getElementById("lname");
    const mobile = document.getElementById("mobile");
    const line1 = document.getElementById("line01");
    const line2 = document.getElementById("line02");
    const city = document.getElementById("city");
    const postalCode = document.getElementById("pcode");
    const image = document.getElementById("imageChoose");

    console.log(image);
    console.log(city.value)


    const form = new FormData();

    form.append("fname", fname.value);
    form.append("lname", lname.value);
    form.append("mobile", mobile.value);
    form.append("line01", line1.value);
    form.append("line02", line2.value);
    form.append("city", city.value);
    form.append("pcode", postalCode.value);
    form.append("image", image.files[0]);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {

        if (request.readyState === 4 && request.status === 200) {

            const response = request.responseText;
            const response_box = document.getElementById('response');
            const alert = document.getElementById('alert');

            window.location.reload();
            console.log(response);


            if (response === "update" || response === "insert") {

                window.location.reload();
                response_box.innerHTML = "Your Profile Successfully Changed";
                response_box.className = "alert alert-success border-0 rounded-1";
                alert.className = "d-block";

            }

        }
    }

    request.open("POST", "update-profile-process.php", true);
    request.send(form);

}

function register_book() {

    const bookTitle = document.getElementById("book_title");
    const pageCount = document.getElementById("book_page_count");
    const newAuthorName = document.getElementById("new_author_name");
    const authorAbout = document.getElementById("author_about");
    const currentAuthor = document.getElementById("author_value");
    const newCategoryName = document.getElementById("new_category");
    const currentCategory = document.getElementById("category_value");
    const SKU = document.getElementById("sku");
    const ISBN = document.getElementById("isbn");
    const description = document.getElementById("description");
    const qty = document.getElementById("qty");
    const bookPrice = document.getElementById("book_price");
    const deliveryFeeColombo = document.getElementById("delivery_colombo");
    const DeliveryOtherFee = document.getElementById("delivery_other");
    const image = document.getElementById("imageUpload");
    const publishedDate = document.getElementById("published_date");
    const language = document.getElementById("language_value");

    const form = new FormData();
    form.append("title", bookTitle.value);
    form.append("pageCount", pageCount.value);
    form.append("newAuthorName", newAuthorName.value);
    form.append("authorAbout", authorAbout.value);
    form.append("currentAuthor", currentAuthor.value);
    form.append("newCategoryName", newCategoryName.value);
    form.append("currentCategory", currentCategory.value);
    form.append("SKU", SKU.value);
    form.append("ISBN", ISBN.value);
    form.append("description", description.value);
    form.append("qty", qty.value);
    form.append("price", bookPrice.value);
    form.append("deliveryColombo", deliveryFeeColombo.value);
    form.append("deliveryOther", DeliveryOtherFee.value);
    form.append("image", image.files[0]);
    form.append("publishedDate", publishedDate.value);
    form.append("language", language.value);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {

        if (request.readyState == 4 && request.status == 200) {

            const response = request.responseText;
            const response_box = document.getElementById('response');
            const alert = document.getElementById('alert');

            if (response == "success") {

                response_box.innerHTML = "Book successfully added";
                response_box.className = "alert alert-success border-0 rounded-0";
                alert.className = "d-block";
                window.location.reload();

            } else {
                response_box.innerHTML = response;
                response_box.className = "alert alert-danger border-0 rounded-0";
                alert.className = "d-block";
            }
        }
    }

    request.open("POST", "/ReadHaven/register-book-process.php", true);
    request.send(form);


}

function upload_image() {

    const image = document.getElementById("imageUpload");

    image.onchange = function () {
        var fileCount = this.files.length;

        if (fileCount <= 1) {

            for (let x = 0; x < fileCount; x++) {
                const file = this.files[x];
                const url = window.URL.createObjectURL(file);
                document.getElementById("book_image").src = url;
            }

        } else {
            alert(fileCount + "Files selected, You can upload only 1 file");
        }
    }
}

function admin_authenticate() {

    const loader = document.getElementById('loader');
    const text = document.getElementById('authBtnSpan');

    const admin_username = document.getElementById("username");
    const admin_password = document.getElementById("password");


    const load = () => {
        loader.className = 'loader d-block';
        text.className = 'd-none';
    }

    const hide = () => {
        loader.className = 'd-none';
        text.className = 'text-white d-block';
    }

    load();

    const form = new FormData();

    form.append("a_username", admin_username.value);
    form.append("a_password", admin_password.value);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {

        if (request.readyState == 4 && request.status == 200) {

            const response = request.responseText;
            const response_box = document.getElementById("response_msg");

            if (response == 'success') {
                setInterval(() => {
                    response_box.innerHTML = 'Verification code has been sent successfully';
                    response_box.className = 'text-success';
                    hide();
                    window.location = 'credentials-verification.php?otp-sent=true&admin=' + admin_username.value;
                }, 2000);

            } else {
                setTimeout(() => {
                    hide();
                    response_box.innerHTML = response;
                }, 1000);
            }
        }

    }

    request.open("POST", "../back-end/admin-authenticate-process.php", true);
    request.send(form);
}

function verify_credentials() {

    let otp = "";

    inputs.forEach((input) => {
        otp += input.value;
        input.disabled = true;
        input.classList.add("disabled");
    });

    const form = new FormData();
    form.append('otp', otp);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {

            let message = document.getElementById('message');
            const response = request.responseText;

            if (response == 'success') {

                const params = new URLSearchParams(window.location.search);
                const admin_username = params.get("admin");

                document.getElementById('admin-name').innerHTML = "Welcome, " + admin_username;

                message.className = 'text-success';
                message.innerHTML = 'Verification successful! Redirecting...';

                document.getElementById('otp-div').className = "d-none";
                document.getElementById('welcome-div').className = "d-block";

                window.location.href = '../admin-panel/main-panel.php';

            } else {
                message.className = 'text-danger';
                message.innerHTML = response;
            }

        }
    }
    request.open("POST", "../back-end/verify-admin-credentials-process.php", true);
    request.send(form);


}

const inputs = document.querySelectorAll(".otp-field input");

inputs.forEach((input, index) => {
    input.dataset.index = index;
    input.addEventListener("keyup", handle_otp);
    input.addEventListener("paste", handle_on_paste_otp);
});

function handle_otp(e) {

    const input = e.target;
    let value = input.value;
    let isValidInput = value.match(/[0-9a-z]/gi);
    input.value = "";
    input.value = isValidInput ? value[0] : "";

    let fieldIndex = input.dataset.index;
    if (fieldIndex < inputs.length - 1 && isValidInput) {
        input.nextElementSibling.focus();
    }

    if (e.key === "Backspace" && fieldIndex > 0) {
        input.previousElementSibling.focus();
    }

    if (fieldIndex == inputs.length - 1 && isValidInput) {
        verify_credentials();
    }
}

function handle_on_paste_otp(e) {
    const data = e.clipboardData.getData("text");
    const value = data.split("");
    if (value.length === inputs.length) {
        inputs.forEach((input, index) => (input.value = value[index]));
        verify_credentials();
    }
}


function otp_code_resend(ADMIN) {

    const form = new FormData();
    form.append('ADMIN_UID', ADMIN);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {

        if (request.readyState == 4 && request.status == 200) {

            let message = document.getElementById('message');
            const response = request.responseText;

            if (response != 'success') {
                message.className = 'text-danger';
                message.innerHTML = response;
            } else {
                message.className = 'text-success';
                message.innerHTML = "Verification code has been resent successfully";
            }

        }
    }
    request.open("POST", "back-end/otp-code-resend-process.php", true);
    request.send(form);
}

function register_new_author() {

    const author_name = document.getElementById("new_author_name");
    const author_about = document.getElementById("author_about");

    const form = new FormData();
    form.append("author_name", author_name.value);
    form.append("author_about", author_about.value);

    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response == "success") {
                document.getElementById("response").innerHTML = "Author successfully added";
                document.getElementById("response").className = "alert alert-success border-0 rounded-0";
                document.getElementById("alert").className = "d-block";
                window.location.reload();
            } else {
                document.getElementById("response").innerHTML = response;
                document.getElementById("response").className = "alert alert-danger border-0 rounded-0";
                document.getElementById("alert").className = "d-block";
            }
        }
    }

    request.open("POST", "/ReadHaven/back-end/register-new-book-author-process.php", true);
    request.send(form);
}

function register_new_category() {
    const category = document.getElementById("new_category");

    const form = new FormData();
    form.append("category", category.value);

    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response == 'success') {
                document.getElementById("response").innerHTML = "Category successfully added";
                document.getElementById("response").className = "alert alert-success border-0 rounded-0";
                document.getElementById("alert").className = "d-block";
                window.location.reload();
            } else {
                document.getElementById("response").innerHTML = response;
                document.getElementById("response").className = "alert alert-danger border-0 rounded-0";
                document.getElementById("alert").className = "d-block";
            }
        }
    }
    request.open("POST", "/ReadHaven/back-end/regitser-new-book-category-process.php", true);
    request.send(form);
}

function register_new_language() {
    const lang = document.getElementById("new_language");
    const form = new FormData();
    form.append("lang", lang.value);

    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response == 'success') {
                document.getElementById("response").innerHTML = "Language successfully added";
                document.getElementById("response").className = "alert alert-success border-0 rounded-0";
                document.getElementById("alert").className = "d-block";
                window.location.reload();
            } else {
                document.getElementById("response").innerHTML = response;
                document.getElementById("response").className = "alert alert-danger border-0 rounded-0";
                document.getElementById("alert").className = "d-block";
            }
        }
    }
    request.open("POST", "/ReadHaven/back-end/register-new-book-language-process.php", true);
    request.send(form);
}

function book_add_to_wishlist(id) {

    const aduio = new Audio('./sounds/mouse-click-sound-233951.mp3');
    aduio.play();

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {

        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response == "Added") {
                document.getElementById('response-text').innerHTML = 'Book added to Watchlist';
                const toastLive = document.getElementById('liveToast');
                const toast = new bootstrap.Toast(toastLive);
                toast.show();

            } else if (response == "Removed") {
                document.getElementById('response-text').innerHTML = 'Book removed from Watchlist';
                const toastLive = document.getElementById('liveToast');
                const toast = new bootstrap.Toast(toastLive);
                toast.show();
            } else {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    animation: false,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "warning",
                    title: response,
                });
            }
        }

    }
    request.open("GET", "back-end/book-add-to-wishlist.php?id=" + id, true);
    request.send();
}

function book_remove_from_wishlist(x) {

    const aduio = new Audio('./sounds/mouse-click-sound-233951.mp3');
    aduio.play();

    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response == "deleted") {
                document.getElementById('response-text').innerHTML = 'Book Removed to Watchlist';
                const toastLive = document.getElementById('liveToast');
                const toast = new bootstrap.Toast(toastLive);
                toast.show();

                window.location.reload();
            }
        }
    }
    request.open("GET", "back-end/book-remove-from-wishlist-process.php?id=" + x, true);
    request.send();
}


function check_quantity(qty) {

    const input = document.getElementById("qty_cnt");
    if (input.value <= 0) {
        const err = document.getElementById("cnt_err");
        err.innerHTML = "Quantity must be one or more!";
        input.value = 1;
    } else if (input.value > qty) {
        const err = document.getElementById("cnt_err");
        err.innerHTML = "Insufficient Quantity";
        input.value = qty;
    }
}

function book_quantity_increase(qty) {

    const input = document.getElementById("qty_cnt");
    if (input.value < qty) {
        const new_val = parseInt(input.value) + 1;
        input.value = new_val;
    } else {
        const err = document.getElementById("cnt_err");
        err.innerHTML = "Maximum quantity reached!";
        input.value = qty;
    }

}

function book_quantity_decrease() {
    const input = document.getElementById("qty_cnt");
    if (input.value > 1) {
        const new_val = parseInt(input.value) - 1;
        input.value = new_val;
    } else {
        const err = document.getElementById("cnt_err");
        err.innerHTML = "Minumum quantity reached!";
        input.value = 1;
    }
}

function quantity_increase(qty, id) {

    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response == 'success') {
                window.location.reload();
            } else {
                alert(response);
            }
        }
    }
    request.open("GET", "back-end/quantity-increase-process.php?id=" + id + "&qty=" + qty, true);
    request.send();

}


function quantity_decrease(id) {

    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response == 'success') {
                window.location.reload();
            }
        }
    }
    request.open("GET", "back-end/quantity-decrease-process.php?id=" + id, true);
    request.send();
}


function book_status_toggle(id) {
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response == 'Deactive' || response == 'Active') {
                window.location.reload();
            }
        }
    }
    request.open("GET", "/ReadHaven/back-end/change-book-status-process.php?id=" + id, true);
    request.send();
}


function update_book(book_id) {

    const title = document.getElementById("title");
    const pages = document.getElementById("pages");
    const publishedDate = document.getElementById("published_date");
    const authorValue = document.getElementById("author_value");
    const categoryValue = document.getElementById("category_value");
    const languageValue = document.getElementById("language_value");
    const sku = document.getElementById("sku");
    const isbn = document.getElementById("isbn");
    const description = document.getElementById("description");
    const qty = document.getElementById("qty");
    const bookPrice = document.getElementById("book_price");
    const deliveryColombo = document.getElementById("delivery_colombo");
    const deliveryOther = document.getElementById("delivery_other");
    const bookImage = document.getElementById("imageUpload");

    const form = new FormData();
    form.append("title", title.value);
    form.append("pageCount", pages.value);
    form.append("publishedDate", publishedDate.value);
    form.append("authorValue", authorValue.value);
    form.append("categoryValue", categoryValue.value);
    form.append("languageValue", languageValue.value);
    form.append("sku", sku.value);
    form.append("isbn", isbn.value);
    form.append("description", description.value);
    form.append("qty", qty.value);
    form.append("bookPrice", bookPrice.value);
    form.append("deliveryColombo", deliveryColombo.value);
    form.append("deliveryOther", deliveryOther.value);
    form.append("imge", bookImage.files[0]);
    form.append("book_id", book_id);


    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response == "success") {
                document.getElementById("response_msg").innerHTML = "Changes saved successfully";
                document.getElementById("response_msg").className = "alert alert-success border-0 rounded-0";
                document.getElementById("msgBox").className = "d-block";
                setInterval(() => {
                    window.location.reload();
                }, 2000);
            } else {
                document.getElementById("response_msg").innerHTML = response;
                document.getElementById("response_msg").className = "alert alert-danger border-0 rounded-0";
                document.getElementById("msgBox").className = "d-block";
            }
        }
    }
    request.open("POST", "/ReadHaven/update-book-process.php", true);
    request.send(form);
}

function book_add_cart(book, qty) {

    const aduio = new Audio('./sounds/mouse-click-sound-233951.mp3');
    aduio.play();

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {

        if (request.readyState == 4 && request.status == 200) {

            const response = request.responseText;
            if (response == 'added') {
                document.getElementById('response-text').innerHTML = 'Book Added to Cart';
                const toastLive = document.getElementById('liveToast');
                const toast = new bootstrap.Toast(toastLive);
                toast.show();
            }

        }
    }
    request.open("GET", "back-end/book-add-cart-process.php?bid=" + book + "&qty=" + qty, true);
    request.send();

}

function visible_auth_model() {
    new bootstrap.Modal(document.getElementById('sign-in-model')).show();
}

function book_remove_from_cart(book) {

    const aduio = new Audio('./sounds/mouse-click-sound-233951.mp3');
    aduio.play();

    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response == 'removed') {
                document.getElementById('response-text').innerHTML = 'Book Removed to Cart';
                const toastLive = document.getElementById('liveToast');
                const toast = new bootstrap.Toast(toastLive);
                toast.show();
                window.location.reload();
            } else {
                document.getElementById("response_msg").innerHTML = response;
                document.getElementById("response_msg").className = "alert alert-danger border-0 rounded-0";
                document.getElementById("msgBox").className = "d-block";
            }
        }
    }
    request.open("GET", "back-end/book-remove-from-cart-process.php?bid=" + book, true);
    request.send();

}


function payment(id) {

    const qty = document.getElementById("qty_cnt").value;

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {

        if (request.readyState == 4 && request.status == 200) {

            const response = request.responseText;

            const obj = JSON.parse(response);

            const mail = obj['email'];
            const total = obj['amount'];
            const amount = obj['subtotal'];
            const shipping = obj['shipping'];

            if (response == 1) {
                new bootstrap.Modal(document.getElementById('sign-in-model')).show();
            } if (response == 2) {
                new bootstrap.Modal(document.getElementById('profile')).show();
            } else {

                payhere.onCompleted = function onCompleted(orderId) {
                    save_invoice(orderId, id, mail, qty, total, amount, shipping);
                };

                payhere.onDismissed = function onDismissed() {
                    window.location = window.location.origin + window.location.pathname + window.location.search + '?state=cancel';
                };

                payhere.onError = function onError(error) {
                    console.log("Error : " + error);
                };

                const payment = {
                    "sandbox": true,
                    "merchant_id": obj["mid"],
                    "return_url": "http://localhost/ReadHaven/payment-cancel.php?stt=cancel",
                    "cancel_url": "http://localhost/ReadHaven/payment-cancel.php?stt=cancel",
                    "notify_url": "http://sample.com/notify",
                    "order_id": obj["id"],
                    "items": obj["name"],
                    "amount": obj["amount"] + ".00",
                    "currency": obj["currency"],
                    "hash": obj["hash"],
                    "first_name": obj["fname"],
                    "last_name": obj["lname"],
                    "email": obj["user"],
                    "phone": obj["mobile"],
                    "address": obj["address"],
                    "city": obj["city"],
                    "country": "Sri Lanka",
                    "delivery_address": obj["address"],
                    "delivery_city": obj["city"],
                    "delivery_country": "Sri Lanka",
                    "custom_1": "",
                    "custom_2": ""
                };

                payhere.startPayment(payment);

            }
        }
    }
    request.open("GET", "back-end/payment-process.php?id=" + id + "&qty=" + qty, true);
    request.send();
}

function save_invoice(orderId, id, mail, qty, total, amount, shipping) {

    const form = new FormData();

    form.append("order_id", orderId);
    form.append("book_id", id);
    form.append("user", mail);
    form.append("amount", amount);
    form.append("qty", qty);

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            const response = request.responseText;
            if (response == "success") {
                window.location = 'payment-success.php?oid=' + orderId + '&ship=' + shipping + '&ttl=' + total + '&amnt=' + amount;
            } else {
                alert(response);
            }

        }
    }
    request.open("POST", "back-end/save-invoice-process.php", true);
    request.send(form);
}

function print_invoice() {
    const restorePage = document.body.innerHTML;
    const page = document.getElementById("page").innerHTML;
    document.body.innerHTML = page;
    window.print();
    document.body.innerHTML = restorePage;
}

let stock_checked;

function available(checkbox, page) {
    const checkboxes = document.getElementsByName(checkbox.name);
    checkboxes.forEach((item) => {
        if (item !== checkbox) item.checked = false;
    });
    find_in_stock_book(checkbox, page);
}

function select_review_category(checkbox) {
    const checkboxes = document.getElementsByName(checkbox.name)
    checkboxes.forEach((item) => {
        if (item !== checkbox) item.checked = false
    })
}

function is_empty(value) {
    if (value == null || value == undefined || value == '') return true;
    else return false;
}

function sort_by_prices(x) {

    const category = document.getElementById('category').value;
    const keyword = document.getElementById("keyword").value;
    const sort = document.getElementById('sort_price').value;
    const is_stock = document.getElementById('in_stock').checked;

    const form = new FormData();

    form.append("category", !is_empty(category) ? category : '');
    form.append("keyword", !is_empty(keyword) ? keyword : '');
    form.append("sort_by_prices", sort != 0 ? sort : 0);
    form.append("page", x);
    form.append("stock", is_stock);

    sort == 1
        ? replaceUrlParams('sort', 'high_to_low')
        : sort == 2 ?
            replaceUrlParams('sort', 'low_to_high') : replaceUrlParams('sort', '');

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {

        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            document.getElementById('results').innerHTML = response;
        }

    }
    request.open("POST", "back-end/sort-price-process.php", true);
    request.send(form);

}

// ---------------------------------------------------- Contact form Submission

function contact() {

    const loader = document.getElementById('loader');
    const text = document.getElementById('contact-btn');

    const load = () => {
        loader.className = 'loader d-block';
        text.className = 'd-none';
    }

    const hide = () => {
        loader.className = "d-none";
        text.className = "text-white d-block";
    }

    load();

    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const mobile = document.getElementById('mobile');
    const subject = document.getElementById('subject');
    const message = document.getElementById('message');

    const form = new FormData();

    form.append('name', name.value);
    form.append('email', email.value);
    form.append('mobile', mobile.value);
    form.append('subject', subject.value);
    form.append('msg', message.value);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {

        if (request.readyState == 4 && request.status == 200) {

            const res = request.responseText;

            if (res == "success") {

                setTimeout(() => {
                    hide();
                }, 1000);

                document.getElementById("response_msg").innerHTML = "Your message has been sent successfully.";
                document.getElementById("response_msg").className = "alert alert-success border-0 rounded-2";
                document.getElementById("msgBox").className = "d-block";

                name.innerText = "";
                email.innerText = "";
                mobile.innerText = "";
                subject.innerText = "";
                message.innerText = "";

            } else {
                setTimeout(() => {
                    hide();
                }, 1000);
                document.getElementById("response_msg").innerHTML = res;
                document.getElementById("response_msg").className = "alert alert-danger border-0 rounded-2";
                document.getElementById("msgBox").className = "d-block";

            }
        }
    }

    request.open("POST", "back-end/contact-us-process.php", true);
    request.send(form);
}

// ---------------------------------------------------- Book review function

function write_book_review(id) {

    let customerMode = 0;

    if (document.getElementById('smile').checked) {
        customerMode = 1;
    } else if (document.getElementById('normal').checked) {
        customerMode = 2;
    } else if (document.getElementById('bad').checked) {
        customerMode = 3;
    }

    let rating = 0;

    if (document.getElementById('star1').checked) {
        rating = 1;
    } else if (document.getElementById('star2').checked) {
        rating = 2;
    } else if (document.getElementById('star3').checked) {
        rating = 3;
    } else if (document.getElementById('star4').checked) {
        rating = 4;
    } else if (document.getElementById('star5').checked) {
        rating = 5;
    }

    let features = 0;

    if (document.getElementById('feature1').checked) {
        features = 1;
    } else if (document.getElementById('feature2').checked) {
        features = 2;
    } else if (document.getElementById('feature3').checked) {
        features = 3;
    } else if (document.getElementById('feature4').checked) {
        features = 0;
    }

    const feedbackKeyword = document.getElementById('feedback');

    const form = new FormData();
    form.append('customerMode', customerMode);
    form.append('rating', rating);
    form.append('features', features);
    form.append('feedback', feedbackKeyword.value);
    form.append('book_id', id);


    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const res = request.responseText;
            if (res == 'success') {
                document.getElementById('feedView').className = 'd-none';
                document.getElementById('feedscc').className = 'd-block';
                window.location = 'order-history.php';
            } else {
                document.getElementById('review-error').innerHTML = res;
            }
        }
    }
    request.open("POST", "back-end/feedback-process.php", true);
    request.send(form);
}

// ---------------------------------------------------- Customer status using search function ( admin panel -> admins only can search customers )

function customer_status_using_search() {

    const userStatus = document.getElementById('userStatus');

    const request = new XMLHttpRequest();
    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const res = request.responseText;
            document.getElementById('userRslt').innerHTML = res;
        }
    }
    request.open("GET", "/ReadHaven/back-end/customer-status-using-search-process.php?state=" + userStatus.value, true);
    request.send();

}

// ---------------------------------------------------- Customer search function ( admin panel -> admins only can search customers )

function search_customer(page) {

    const form = new FormData();
    form.append('keyword', document.getElementById('keyword').value)
    form.append('page', page);

    const request = new XMLHttpRequest();
    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const res = request.responseText;
            document.getElementById('userRslt').innerHTML = res;

        }
    }
    request.open("POST", "/ReadHaven/back-end/customer-search-process.php", true);
    request.send(form);
}

// ---------------------------------------------------- Customer status toggle function ( 'Block' or 'Unblock' )

function customer_status_toggle(email) {

    const form = new FormData();
    form.append('email', email);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const res = request.responseText;
            if (res == 'blocked') {
                document.getElementById('userMsg').innerHTML = email + ' user blocked';
                setInterval(() => {
                    window.location.reload();
                }, 1000);
            } else if (res == 'unblocked') {
                document.getElementById('userMsg').innerHTML = email + ' user unblocked';
                document.getElementById('userMsg').className = 'text-success';
                setInterval(() => {
                    window.location.reload();
                }, 1000);
            }
        }
    }
    request.open("POST", "/ReadHaven/back-end/customer-status-toggle-process.php", true);
    request.send(form);
}

// ---------------------------------------------------- Find invoice function

function find_invoice(page) {

    const form = new FormData();
    form.append('value', document.getElementById('invcValue').value);
    form.append('page', page)

    const request = new XMLHttpRequest();
    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const res = request.responseText;
            document.getElementById('findInvoiceRslt').innerHTML = res;
        }
    }
    request.open("POST", "/ReadHaven/back-end/find-invoice-process.php", true);
    request.send(form);
}

// ---------------------------------------------------- Search books in Admin panel function

function search_books(page) {

    console.log('admin panel side books searching....');

    const form = new FormData();
    form.append('category', document.getElementById('category').value);
    form.append('author', document.getElementById('author').value);
    form.append('status', document.getElementById('status').value);
    form.append('stock', document.getElementById('stock').value);
    form.append('keyword', document.getElementById('keyword').value);
    form.append('page', page);

    const request = new XMLHttpRequest();
    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const res = request.responseText;
            document.getElementById('rslt').innerHTML = res;
        }
    }
    request.open("POST", "/ReadHaven/back-end/search-books-process.php", true);
    request.send(form);
}

// ---------------------------------------------------- Delete review function

function delete_review(id) {

    const form = new FormData();

    form.append('id', id);
    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response === 'success') {
                document.getElementById('response-text').innerHTML = 'Review is Deleted';
                const toastLive = document.getElementById('liveToast');
                const toast = new bootstrap.Toast(toastLive);
                toast.show();
            }
        }
    }
    request.open('POST', './back-end/review-delete-process.php', true);
    request.send(form);
}

// ---------------------------------------------------- Delete order history book function

function delete_order_history_book(order_id) {

    const form = new FormData();
    form.append('id', order_id);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response === 'success') {
                document.getElementById('response-text').innerHTML = 'Order History Book is removed';
                const toastLive = document.getElementById('liveToast');
                const toast = new bootstrap.Toast(toastLive);
                toast.show();
            }
        }
    }
    request.open('POST', './back-end/order-history-delete-process.php', true);
    request.send(form);
}

// ---------------------------------------------------- Check out payment function

function checkout(shipping, total, amount) {

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {

        if (request.status === 200 && request.readyState === 4) {

            const response = request.responseText;

            const object = JSON.parse(response);

            payhere.onCompleted = function onCompleted(orderId) {
                save_check_out_invoice(orderId, shipping, total, amount);
            };

            payhere.onDismissed = function onDismissed() {
                window.location = window.location.origin + window.location.pathname + window.location.search + '?state=cancel';
            };

            payhere.onError = function onError(error) {
                console.log("Error:" + error);
            };

            const payment = {
                "sandbox": true,
                "merchant_id": object['merchant_id'],
                "return_url": "http://localhost/ReadHaven/payment-cancel.php?stt=cancel",
                "cancel_url": "http://localhost/ReadHaven/payment-cancel.php?stt=cancel",
                "notify_url": "http://sample.com/notify",
                "order_id": object['order_id'],
                "items": object['items'],
                "amount": object['amount'],
                "currency": object['currency'],
                "hash": object['hash'],
                "first_name": object['fname'],
                "last_name": object['lname'],
                "email": object['email'],
                "phone": object['mobile'],
                "address": object['address'],
                "city": object['city'],
                "country": "Sri Lanka",
                "delivery_address": object['address'],
                "delivery_city": object['city'],
                "delivery_country": "Sri Lanka",
                "custom_1": "",
                "custom_2": ""
            };

            payhere.startPayment(payment);
        }
    }
    request.open('GET', './back-end/check-out-payment-process.php', true);
    request.send();
}

// ---------------------------------------------------- Order history return url save and return function

function save_return_url() {
    sessionStorage.setItem('orderHistory', window.location.href);
}

function return_to_order_history_url() {

    const returnUrl = sessionStorage.getItem('orderHistory');
    sessionStorage.removeItem('orderHistory');

    if (returnUrl) {
        window.location.href = returnUrl;
    } else {
        window.location.href = 'order-history.php';
    }
}

// ---------------------------------------------------- Check out invoice save function

function save_check_out_invoice(order_id, shipping, total, amount) {

    const form = new FormData();
    form.append('order_id', order_id)

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response == 200) {
                window.location = 'payment-success.php?oid=' + order_id + '&ship=' + shipping + '&ttl=' + total + '&amnt=' + amount;
            } else {
                console.log(response);
            }
        }
    }

    request.open('POST', './back-end/check-out-invoice-save-process.php', true);
    request.send(form);
}

// ---------------------------------------------------- Chat bot toggle function

function chat_bot_visible() {
    const view = document.getElementById('chat-view');
    view.classList.toggle("d-none")
}

// ---------------------------------------------------- Highlight category function

function highlight_category(element, category_name, page) {

    console.log('selected category: ' + category_name);

    replaceUrlParams('category', category_name);

    const categories = document.querySelectorAll('.highlighted-category');
    categories.forEach(function (category) {
        category.classList.remove('highlighted-category');
    });

    element.classList.add('highlighted-category');
    document.getElementById('category').value = category_name;

    search_by_category(category_name, page);
}

window.highlight_category = highlight_category;

// ---------------------------------------------------- Search by category function

var category_name;

function search_by_category(category, page) {

    category_name = category;

    const form = new FormData();

    form.append('category', category);
    form.append('page', page);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const res = request.responseText;
            document.getElementById('results').innerHTML = res;
        }
    }

    request.open('POST', './back-end/search-by-category-process.php', true);
    request.send(form);

}

// ---------------------------------------------------- Find in stock books function
function find_in_stock_book(checkbox, page) {

    console.log('available books loading.....');

    let keyword = document.getElementById('keyword').value;
    let category = document.getElementById('category').value;
    let stock = document.getElementById('in_stock');

    stock.checked ?
        replaceUrlParams('stock', 'available')
        : replaceUrlParams('stock', '');

    const form = new FormData();
    form.append('page', page);
    form.append('keyword', keyword);
    form.append('category', category);
    form.append('stock', stock.checked);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {

        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            document.getElementById('results').innerHTML = response;
        }
    }
    request.open('POST', './back-end/find-in-stock-books-process.php', true);
    request.send(form);
}

// ---------------------------------------------------- Books price range display function

function update_price_range() {
    const minPrice = document.getElementById('priceRangeMin').value;
    const maxPrice = document.getElementById('priceRangeMax').value;
    document.getElementById('minPrice').innerText = 'Rs. ' + minPrice;
    document.getElementById('maxPrice').innerText = 'Rs. ' + maxPrice;
}

function update_price_range_model() {
    const minPrice = document.getElementById('priceRangeMinModel').value;
    const maxPrice = document.getElementById('priceRangeMaxModel').value;
    document.getElementById('minPriceModel').innerText = 'Rs. ' + minPrice;
    document.getElementById('maxPriceModel').innerText = 'Rs. ' + maxPrice;
}

// ---------------------------------------------------- Books price range Filter function
function filter(page) {

    const minimum_price = document.getElementById('priceRangeMin').value;
    const maximum_price = document.getElementById('priceRangeMax').value;
    const keyword = document.getElementById('keyword').value;
    const category = document.getElementById('category').value;
    const in_stock = document.getElementById('in_stock').checked;

    keyword != '' ? replaceUrlParams('keyword', keyword) : null;
    category != '' ? replaceUrlParams('category', category) : null;

    const form = new FormData();
    form.append('stock', in_stock);
    form.append('category', category);
    form.append('min', minimum_price);
    form.append('max', maximum_price);
    form.append('keyword', keyword);
    form.append('page', page);

    const request = new XMLHttpRequest();
    request.onreadystatechange = () => {
        if (request.readyState === 4 && request.status === 200) {
            document.getElementById('results').innerHTML = request.responseText;
        }
    }
    request.open('POST', './back-end/filter-price-range-process.php', true);
    request.send(form);
}
// ---------------------------------------------------- Find reviews by book function
function find_reviews_by_book(page) {
    const form = new FormData();
    form.append('value', document.getElementById('bookValue').value);
    form.append('page', page);

    const request = new XMLHttpRequest();
    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const res = request.responseText;
            document.getElementById('reviewsSection').innerHTML = res;
        }
    }
    request.open("POST", "/ReadHaven/back-end/find-reviews-by-book-process.php", true);
    request.send(form);
}
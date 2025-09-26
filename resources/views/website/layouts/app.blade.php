<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title')
    </title>
    <link rel="shortcut icon" type="image/png" href="{{asset('logo.png')}}" />
    @yield('meta')
    @livewireStyles()
    @include('website.layouts.styles')
</head>

<body class="body">

    @include('website.layouts.header')

    <div class="site-content">

        @yield('pages')

    </div>

    @include('website.layouts.footer')

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sign In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background:none; border:none;">
                        <i class="mdi mdi-close" style="font-size: 1.5rem;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" class="form">
                        @csrf
                        <div id="email-field" class="field-wrapper input mb-3">
                            <label for="remail">EMAIL</label>
                            <input id="remail" name="email" type="email" value="" class="form-control gmz-validation"
                                data-validation="required" placeholder="Email" />
                        </div>

                        <div id="password-field" class="field-wrapper input mb-3">
                            <div class="d-flex justify-content-between">
                                <label for="rpassword">PASSWORD</label>
                                <a href="#" onclick="switchToForgotPassword()" class="forgot-pass-link text-primary fw-bold">Forgot Password?</a>
                            </div>
                            <div style="position: relative;">
                                <input id="rpassword" name="password" type="password" class="form-control gmz-validation"
                                    data-validation="required" placeholder="Password" style="padding-right: 40px;" />
                                <span class="view-password" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor:pointer;" onclick="togglePassword()">
                                    <i class="fal fa-eye view" id="show-eye"></i>
                                    <i class="fal fa-eye-slash not-view" id="hide-eye" style="display:none;"></i>
                                </span>
                            </div>
                        </div>
                        <script>
                            function togglePassword() {
                                var input = document.getElementById('rpassword');
                                var showEye = document.getElementById('show-eye');
                                var hideEye = document.getElementById('hide-eye');
                                if (input.type === "password") {
                                    input.type = "text";
                                    showEye.style.display = "none";
                                    hideEye.style.display = "inline";
                                } else {
                                    input.type = "password";
                                    showEye.style.display = "inline";
                                    hideEye.style.display = "none";
                                }
                            }

                            function toggleSignupPassword() {
                                var input = document.getElementById('signup-password');
                                var showEye = document.getElementById('signup-show-eye');
                                var hideEye = document.getElementById('signup-hide-eye');
                                if (input.type === "password") {
                                    input.type = "text";
                                    showEye.style.display = "none";
                                    hideEye.style.display = "inline";
                                } else {
                                    input.type = "password";
                                    showEye.style.display = "inline";
                                    hideEye.style.display = "none";
                                }
                            }

                            function toggleCurrentPassword() {
                                var input = document.getElementById('current-password');
                                var showEye = document.getElementById('current-show-eye');
                                var hideEye = document.getElementById('current-hide-eye');
                                if (input.type === "password") {
                                    input.type = "text";
                                    showEye.style.display = "none";
                                    hideEye.style.display = "inline";
                                } else {
                                    input.type = "password";
                                    showEye.style.display = "inline";
                                    hideEye.style.display = "none";
                                }
                            }

                            function toggleNewPassword() {
                                var input = document.getElementById('new-password');
                                var showEye = document.getElementById('new-show-eye');
                                var hideEye = document.getElementById('new-hide-eye');
                                if (input.type === "password") {
                                    input.type = "text";
                                    showEye.style.display = "none";
                                    hideEye.style.display = "inline";
                                } else {
                                    input.type = "password";
                                    showEye.style.display = "inline";
                                    hideEye.style.display = "none";
                                }
                            }

                            function toggleConfirmNewPassword() {
                                var input = document.getElementById('confirm-new-password');
                                var showEye = document.getElementById('confirm-new-show-eye');
                                var hideEye = document.getElementById('confirm-new-hide-eye');
                                if (input.type === "password") {
                                    input.type = "text";
                                    showEye.style.display = "none";
                                    hideEye.style.display = "inline";
                                } else {
                                    input.type = "password";
                                    showEye.style.display = "inline";
                                    hideEye.style.display = "none";
                                }
                            }

                            // Modal switching functions
                            function switchToSignup() {
                                // Close login modal
                                var loginModal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                                if (loginModal) {
                                    loginModal.hide();
                                }
                                
                                // Open signup modal
                                setTimeout(function() {
                                    var signupModal = new bootstrap.Modal(document.getElementById('Signup'));
                                    signupModal.show();
                                }, 300);
                            }

                            function switchToLogin() {
                                // Close current modal
                                var currentModal = document.querySelector('.modal.show');
                                if (currentModal) {
                                    var modalInstance = bootstrap.Modal.getInstance(currentModal);
                                    if (modalInstance) {
                                        modalInstance.hide();
                                    }
                                }
                                
                                // Open login modal
                                setTimeout(function() {
                                    var loginModal = new bootstrap.Modal(document.getElementById('exampleModal'));
                                    loginModal.show();
                                }, 300);
                            }

                            function switchToForgotPassword() {
                                // Close login modal
                                var loginModal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                                if (loginModal) {
                                    loginModal.hide();
                                }
                                
                                // Open forgot password modal
                                setTimeout(function() {
                                    var forgotModal = new bootstrap.Modal(document.getElementById('ForgotPassword'));
                                    forgotModal.show();
                                }, 300);
                            }

                            // Button loading state helper functions
                            function setButtonLoading(buttonId, isLoading) {
                                const button = document.getElementById(buttonId);
                                const btnText = button.querySelector('.btn-text');
                                const btnLoading = button.querySelector('.btn-loading');
                                
                                if (isLoading) {
                                    button.disabled = true;
                                    btnText.style.display = 'none';
                                    btnLoading.style.display = 'inline';
                                } else {
                                    button.disabled = false;
                                    btnText.style.display = 'inline';
                                    btnLoading.style.display = 'none';
                                }
                            }

                            // Form submission handlers
                            document.addEventListener('DOMContentLoaded', function() {
                                // Login form
                                document.getElementById('loginForm').addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    handleLogin();
                                });

                                // Signup form
                                document.getElementById('signupForm').addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    handleSignup();
                                });

                                // Forgot password form
                                document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    handleForgotPassword();
                                });

                                // Change password form
                                document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    handleChangePassword();
                                });
                            });

                            function handleLogin() {
                                const form = document.getElementById('loginForm');
                                const formData = new FormData(form);
                                
                                // Set loading state
                                setButtonLoading('loginSubmitBtn', true);
                                
                                fetch('{{ route("login") }}', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        showToast(data.message, 'success');
                                        setTimeout(() => {
                                            window.location.href = data.redirect;
                                        }, 1000);
                                    } else {
                                        showToast(data.message, 'error');
                                        setButtonLoading('loginSubmitBtn', false);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showToast('An error occurred. Please try again.', 'error');
                                    setButtonLoading('loginSubmitBtn', false);
                                });
                            }

                            function handleSignup() {
                                const form = document.getElementById('signupForm');
                                const formData = new FormData(form);
                                
                                // Set loading state
                                setButtonLoading('signupSubmitBtn', true);
                                
                                fetch('{{ route("register") }}', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        showToast(data.message, 'success');
                                        setTimeout(() => {
                                            window.location.href = data.redirect;
                                        }, 1000);
                                    } else {
                                        showToast(data.message, 'error');
                                        if (data.errors) {
                                            Object.values(data.errors).forEach(error => {
                                                showToast(error[0], 'error');
                                            });
                                        }
                                        setButtonLoading('signupSubmitBtn', false);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showToast('An error occurred. Please try again.', 'error');
                                    setButtonLoading('signupSubmitBtn', false);
                                });
                            }

                            function handleForgotPassword() {
                                const form = document.getElementById('forgotPasswordForm');
                                const formData = new FormData(form);
                                
                                // Set loading state
                                setButtonLoading('forgotPasswordSubmitBtn', true);
                                
                                fetch('{{ route("forgot-password") }}', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        showToast(data.message, 'success');
                                        setTimeout(() => {
                                            switchToLogin();
                                        }, 2000);
                                    } else {
                                        showToast(data.message, 'error');
                                        if (data.errors) {
                                            Object.values(data.errors).forEach(error => {
                                                showToast(error[0], 'error');
                                            });
                                        }
                                        setButtonLoading('forgotPasswordSubmitBtn', false);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showToast('An error occurred. Please try again.', 'error');
                                    setButtonLoading('forgotPasswordSubmitBtn', false);
                                });
                            }

                            function handleChangePassword() {
                                const form = document.getElementById('changePasswordForm');
                                const formData = new FormData(form);
                                
                                // Set loading state
                                setButtonLoading('changePasswordSubmitBtn', true);
                                
                                fetch('{{ route("change-password") }}', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        showToast(data.message, 'success');
                                        setTimeout(() => {
                                            // Close the modal
                                            var changePasswordModal = bootstrap.Modal.getInstance(document.getElementById('ChangePassword'));
                                            if (changePasswordModal) {
                                                changePasswordModal.hide();
                                            }
                                            // Clear the form
                                            form.reset();
                                        }, 1500);
                                    } else {
                                        showToast(data.message, 'error');
                                        if (data.errors) {
                                            Object.values(data.errors).forEach(error => {
                                                showToast(error[0], 'error');
                                            });
                                        }
                                        setButtonLoading('changePasswordSubmitBtn', false);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showToast('An error occurred. Please try again.', 'error');
                                    setButtonLoading('changePasswordSubmitBtn', false);
                                });
                            }

                            // Website-specific functions
                            function handleWebsiteLogout() {
                                if (confirm('Are you sure you want to logout?')) {
                                    fetch('{{ route("logout") }}', {
                                        method: 'POST',
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            showToast(data.message, 'success');
                                            setTimeout(() => {
                                                window.location.href = data.redirect;
                                            }, 1000);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        // Fallback to page reload
                                        window.location.reload();
                                    });
                                }
                            }

                            function openChangePasswordModal() {
                                var changePasswordModal = new bootstrap.Modal(document.getElementById('ChangePassword'));
                                changePasswordModal.show();
                            }
                        </script>

                        <div class="my-2">
                            <button type="submit" class="btn btn-primary w-100" id="loginSubmitBtn" value="">
                                <span class="btn-text">SIGN IN</span>
                                <span class="btn-loading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin me-2"></i>SIGNING IN...
                                </span>
                            </button>
                        </div>

                        <p class="signup-link">
                            Don't have an account?
                            <a href="#" onclick="switchToSignup()" class="text-primary fw-bold">Sign up</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Signup Modal -->
    <div class="modal fade" id="Signup" tabindex="-1" aria-labelledby="SignupLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="SignupLabel">Sign Up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background:none; border:none;">
                        <i class="mdi mdi-close" style="font-size: 1.5rem;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="form">
                        @csrf
                        <input type="hidden" name="isfr" value="1" />
                        <div class="row">
                            <div class="col-lg-6">
                                <div id="signup-first_name-field" class="field-wrapper input mb-3">
                                    <label for="signup-first_name">FIRST NAME</label>
                                    <input id="signup-first_name" name="first_name" type="text"
                                        class="form-control gmz-validation" data-validation="required"
                                        placeholder="First Name" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div id="signup-last_name-field" class="field-wrapper input mb-3">
                                    <label for="signup-last_name">LAST NAME</label>
                                    <input id="signup-last_name" name="last_name" type="text"
                                        class="form-control gmz-validation" data-validation="required"
                                        placeholder="Last Name" />
                                </div>
                            </div>
                        </div>

                        <div id="signup-email-field" class="field-wrapper input mb-3">
                            <label for="signup-email">EMAIL</label>
                            <input id="signup-email" name="email" type="email" value="" class="form-control gmz-validation"
                                data-validation="required" placeholder="Email" />
                        </div>

                        <div id="signup-password-field" class="field-wrapper input mb-3">
                            <label for="signup-password">PASSWORD</label>
                            <div style="position: relative;">
                                <input id="signup-password" name="password" type="password" class="form-control gmz-validation"
                                    data-validation="required" placeholder="Password" style="padding-right: 40px;" />
                                <span class="view-password" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor:pointer;" onclick="toggleSignupPassword()">
                                    <i class="fal fa-eye view" id="signup-show-eye"></i>
                                    <i class="fal fa-eye-slash not-view" id="signup-hide-eye" style="display:none;"></i>
                                </span>
                            </div>
                        </div>

                        <div id="signup-password-confirmation-field" class="field-wrapper input mb-3">
                            <label for="signup-password-confirmation">CONFIRM PASSWORD</label>
                            <div style="position: relative;">
                                <input id="signup-password-confirmation" name="password_confirmation" type="password" class="form-control gmz-validation"
                                    data-validation="required" placeholder="Confirm Password" style="padding-right: 40px;" />
                            </div>
                        </div>

                        <div id="signup-referral_code-field" class="field-wrapper input mb-3">
                            <label for="signup-referral_code">REFERRAL CODE
                                <small class="text-muted">(Optional)</small></label>
                            <input id="signup-referral_code" name="referral_code" type="text" class="form-control"
                                placeholder="Enter referral code (optional)" />
                        </div>

                        <div class="field-wrapper terms_condition">
                            <div class="n-chk">
                                <label class="new-control new-checkbox checkbox-primary">
                                    <input type="checkbox" name="agree_field" value="1" id="signup-agree-term"
                                        class="new-control-input gmz-validation" data-validation="required" checked />
                                    <span class="new-control-indicator"></span><span class="text-primary">I agree to the
                                        <a href="#" class="text-primary fw-bold">
                                            terms and conditions
                                        </a></span>
                                </label>
                            </div>
                        </div>

                        <div class="gmz-message"></div>

                        <div class="my-2">
                            <button type="submit" class="btn btn-primary w-100" id="signupSubmitBtn" value="">
                                <span class="btn-text">REGISTER</span>
                                <span class="btn-loading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin me-2"></i>REGISTERING...
                                </span>
                            </button>
                        </div>

                        <p class="signup-link">
                            Already have an account?
                            <a href="#" onclick="switchToLogin()" class="text-primary fw-bold">Sign In</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div class="modal fade" id="ForgotPassword" tabindex="-1" aria-labelledby="ForgotPasswordLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ForgotPasswordLabel">Forgot Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background:none; border:none;">
                        <i class="mdi mdi-close" style="font-size: 1.5rem;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="forgotPasswordForm" class="form">
                        @csrf
                        <div class="mb-3">
                            <p class="text-muted">Enter your email address and we'll send you a link to reset your password.</p>
                        </div>
                        
                        <div id="forgot-email-field" class="field-wrapper input mb-3">
                            <label for="forgot-email">EMAIL</label>
                            <input id="forgot-email" name="email" type="email" class="form-control gmz-validation"
                                data-validation="required" placeholder="Enter your email address" />
                        </div>

                        <div class="gmz-message"></div>

                        <div class="my-2">
                            <button type="submit" class="btn btn-primary w-100" id="forgotPasswordSubmitBtn" value="">
                                <span class="btn-text">SEND RESET LINK</span>
                                <span class="btn-loading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin me-2"></i>SENDING...
                                </span>
                            </button>
                        </div>

                        <p class="signup-link">
                            Remember your password?
                            <a href="#" onclick="switchToLogin()" class="text-primary fw-bold">Sign In</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="ChangePassword" tabindex="-1" aria-labelledby="ChangePasswordLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ChangePasswordLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background:none; border:none;">
                        <i class="mdi mdi-close" style="font-size: 1.5rem;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm" class="form">
                        @csrf
                        <div class="mb-3">
                            <p class="text-muted">Enter your current password and choose a new password.</p>
                        </div>
                        
                        <div id="current-password-field" class="field-wrapper input mb-3">
                            <label for="current-password">CURRENT PASSWORD</label>
                            <div style="position: relative;">
                                <input id="current-password" name="current_password" type="password" class="form-control gmz-validation"
                                    data-validation="required" placeholder="Enter current password" style="padding-right: 40px;" />
                                <span class="view-password" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor:pointer;" onclick="toggleCurrentPassword()">
                                    <i class="fal fa-eye view" id="current-show-eye"></i>
                                    <i class="fal fa-eye-slash not-view" id="current-hide-eye" style="display:none;"></i>
                                </span>
                            </div>
                        </div>

                        <div id="new-password-field" class="field-wrapper input mb-3">
                            <label for="new-password">NEW PASSWORD</label>
                            <div style="position: relative;">
                                <input id="new-password" name="password" type="password" class="form-control gmz-validation"
                                    data-validation="required" placeholder="Enter new password" style="padding-right: 40px;" />
                                <span class="view-password" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor:pointer;" onclick="toggleNewPassword()">
                                    <i class="fal fa-eye view" id="new-show-eye"></i>
                                    <i class="fal fa-eye-slash not-view" id="new-hide-eye" style="display:none;"></i>
                                </span>
                            </div>
                        </div>

                        <div id="confirm-new-password-field" class="field-wrapper input mb-3">
                            <label for="confirm-new-password">CONFIRM NEW PASSWORD</label>
                            <div style="position: relative;">
                                <input id="confirm-new-password" name="password_confirmation" type="password" class="form-control gmz-validation"
                                    data-validation="required" placeholder="Confirm new password" style="padding-right: 40px;" />
                                <span class="view-password" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor:pointer;" onclick="toggleConfirmNewPassword()">
                                    <i class="fal fa-eye view" id="confirm-new-show-eye"></i>
                                    <i class="fal fa-eye-slash not-view" id="confirm-new-hide-eye" style="display:none;"></i>
                                </span>
                            </div>
                        </div>

                        <div class="gmz-message"></div>

                        <div class="my-2">
                            <button type="submit" class="btn btn-primary w-100" id="changePasswordSubmitBtn" value="">
                                <span class="btn-text">CHANGE PASSWORD</span>
                                <span class="btn-loading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin me-2"></i>CHANGING...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts()
    @include('website.layouts.js')
    @stack('scripts')

    <!-- Toast Notification System -->
    <div id="toast-container" style="position: fixed; top: 100px; right: 20px; z-index: 999999;"></div>

    <script>
        // Toast notification system
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toastId = 'toast-' + Date.now();
            
            // Toast HTML
            const toastHtml = `
                <div id="${toastId}" class="toast-notification toast-${type}" style="
                    background: ${type === 'success' ? '#28a745' : '#dc3545'};
                    color: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    margin-bottom: 10px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    transform: translateX(400px);
                    transition: transform 0.3s ease-in-out;
                    min-width: 300px;
                    max-width: 400px;
                    position: relative;
                    cursor: pointer;
                    z-index: 999999;
                ">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center;">
                            <i class="${type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'}" 
                               style="margin-right: 10px; font-size: 18px;"></i>
                            <span style="font-weight: 500;">${message}</span>
                        </div>
                        <button onclick="closeToast('${toastId}')" style="
                            background: none;
                            border: none;
                            color: white;
                            font-size: 18px;
                            cursor: pointer;
                            margin-left: 15px;
                            opacity: 0.8;
                        ">&times;</button>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', toastHtml);
            
            // Animate in
            setTimeout(() => {
                const toast = document.getElementById(toastId);
                if (toast) {
                    toast.style.transform = 'translateX(0)';
                }
            }, 100);
            
            // Auto close after 5 seconds
            setTimeout(() => {
                closeToast(toastId);
            }, 5000);
        }
        
        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.transform = 'translateX(400px)';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }
        
        // Show toasts from Laravel session messages
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
        
        @if(session('warning'))
            showToast('{{ session('warning') }}', 'warning');
        @endif
        
        @if(session('info'))
            showToast('{{ session('info') }}', 'info');
        @endif
        
        // Handle validation errors
        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast('{{ $error }}', 'error');
            @endforeach
        @endif
        
        // Add click to close functionality
        document.addEventListener('click', function(e) {
            if (e.target.closest('.toast-notification')) {
                const toast = e.target.closest('.toast-notification');
                closeToast(toast.id);
            }
        });
    </script>

    <style>
        /* Additional toast styles for warning and info */
        .toast-warning {
            background: #ffc107 !important;
            color: #212529 !important;
        }

        .toast-info {
            background: #17a2b8 !important;
            color: white !important;
        }

        /* Toast animation improvements */
        #toast-container {
            z-index: 999999 !important;
            position: fixed !important;
        }

        .toast-notification {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 999999 !important;
            position: relative !important;
        }

        /* Ensure Bootstrap modals appear above header */
        .modal {
            z-index: 1055 !important;
        }

        .modal-backdrop {
            z-index: 1050 !important;
        }

        /* Ensure existing popups appear above header */
        .gmz-popup-form {
            z-index: 1055 !important;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            #toast-container {
                left: 10px;
                right: 10px;
                top: 10px;
            }

            .toast-notification {
                min-width: auto !important;
                max-width: none !important;
            }
        }

        /* Modal link styling */
        .signup-link a,
        .forgot-pass-link {
            color: #007bff !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            transition: all 0.3s ease !important;
        }

        .signup-link a:hover,
        .forgot-pass-link:hover {
            color: #0056b3 !important;
            text-decoration: underline !important;
            transform: translateY(-1px);
        }

        .signup-link a:active,
        .forgot-pass-link:active {
            transform: translateY(0);
        }

        /* Button loading states */
        .btn-loading {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</body>

</html>
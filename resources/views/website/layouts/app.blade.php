<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

    <div class="white-popup mfp-with-anim mfp-hide gmz-popup-form" id="gmz-login-popup">
        <div class="popup-inner">
            <h4 class="popup-title">Sign In</h4>
            <div class="popup-content">
                <form class="text-left gmz-form-action account-form" action="https://www.zanzibarbookings.com/login"
                    method="POST">
                    <div class="gmz-loader">
                        <div class="loader-inner">
                            <div class="spinner-grow text-info align-self-center loader-lg"></div>
                        </div>
                    </div>
                    <div class="form">
                        <input type="hidden" name="isfr" value="1" />
                        <div id="username-field" class="field-wrapper input">
                            <label for="lusername">EMAIL</label>
                            <i class="fal fa-user-alt"></i>
                            <input id="lusername" name="email" type="text" class="form-control gmz-validation"
                                data-validation="required" placeholder="Your email" />
                        </div>

                        <div id="password-field" class="field-wrapper input mb-2">
                            <div class="d-flex justify-content-between">
                                <label for="lpassword">PASSWORD</label>
                                <a href="#gmz-reset-popup" class="forgot-pass-link gmz-box-popup"
                                    data-effect="mfp-zoom-in">Forgot Password?</a>
                            </div>
                            <i class="fal fa-lock"></i>
                            <input id="lpassword" name="password" type="password" class="form-control gmz-validation"
                                data-validation="required" placeholder="Your password" />
                            <div class="view-password">
                                <i class="fal fa-eye view"></i>
                                <i class="fal fa-eye-slash not-view"></i>
                            </div>
                        </div>

                        <div class="gmz-message"></div>

                        <div class="d-sm-flex justify-content-center mb-5">
                            <div class="field-wrapper">
                                <button type="submit" class="btn btn-primary w-100" style="
                      text-align: center;
                      display: flex;
                      align-items: center;
                      justify-content: center;
                    " value="">
                                    Log In
                                </button>
                            </div>
                        </div>

                        <p class="signup-link">
                            Not registered ?
                            <a href="#gmz-register-popup" class="gmz-box-popup" data-effect="mfp-zoom-in">Create an
                                account</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="white-popup mfp-with-anim mfp-hide gmz-popup-form" id="gmz-register-popup">
        <div class="popup-inner">
            <h4 class="popup-title">Sign Up</h4>
            <div class="popup-content">
                <form class="text-left gmz-form-action account-form" action="https://www.zanzibarbookings.com/register"
                    method="POST">
                    <div class="gmz-loader">
                        <div class="loader-inner">
                            <div class="spinner-grow text-info align-self-center loader-lg"></div>
                        </div>
                    </div>
                    <div class="form">
                        <input type="hidden" name="isfr" value="1" />
                        <div class="row">
                            <div class="col-lg-6">
                                <div id="first_name-field" class="field-wrapper input">
                                    <label for="rfirst_name">FIRST NAME</label>
                                    <i class="fal fa-user-alt"></i>
                                    <input id="rfirst_name" name="first_name" type="text"
                                        class="form-control gmz-validation" data-validation="required"
                                        placeholder="First Name" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div id="last_name-field" class="field-wrapper input">
                                    <label for="rlast_name">LAST NAME</label>
                                    <i class="fal fa-user-alt"></i>
                                    <input id="rlast_name" name="last_name" type="text"
                                        class="form-control gmz-validation" data-validation="required"
                                        placeholder="Last Name" />
                                </div>
                            </div>
                        </div>

                        <div id="email-field" class="field-wrapper input">
                            <label for="remail">EMAIL</label>
                            <i class="fal fa-at"></i>
                            <input id="remail" name="email" type="text" value="" class="form-control gmz-validation"
                                data-validation="required" placeholder="Email" />
                        </div>

                        <div id="password-field" class="field-wrapper input mb-2">
                            <div class="d-flex justify-content-between">
                                <label for="rpassword">PASSWORD</label>
                                <a href="#gmz-reset-popup" class="forgot-pass-link gmz-box-popup"
                                    data-effect="mfp-zoom-in">Forgot Password?</a>
                            </div>
                            <i class="fal fa-lock"></i>
                            <input id="rpassword" name="password" type="password" class="form-control gmz-validation"
                                data-validation="required" placeholder="Password" />
                            <div class="view-password">
                                <i class="fal fa-eye view"></i>
                                <i class="fal fa-eye-slash not-view"></i>
                            </div>
                        </div>

                        <div id="referral_code-field" class="field-wrapper input">
                            <label for="rreferral_code">REFERRAL CODE
                                <small class="text-muted">(Optional)</small></label>
                            <i class="fal fa-gift"></i>
                            <input id="rreferral_code" name="referral_code" type="text" class="form-control"
                                placeholder="Enter referral code (optional)" />
                        </div>

                        <div class="field-wrapper terms_condition">
                            <div class="n-chk">
                                <label class="new-control new-checkbox checkbox-primary">
                                    <input type="checkbox" name="agree_field" value="1" id="agree-term"
                                        class="new-control-input gmz-validation" data-validation="required" />
                                    <span class="new-control-indicator"></span><span>I agree to the
                                        <a href="page/terms-conditions.html">
                                            terms and conditions
                                        </a></span>
                                </label>
                            </div>
                        </div>

                        <div class="gmz-message"></div>

                        <div class="d-sm-flex justify-content-center">
                            <div class="field-wrapper">
                                <button type="submit" class="btn btn-primary w-100" style="
                      text-align: center;
                      display: flex;
                      align-items: center;
                      justify-content: center;
                    " value="">
                                    REGISTER
                                </button>
                            </div>
                        </div>

                        <p class="signup-link">
                            Already have an account?
                            <a href="#gmz-login-popup" class="gmz-box-popup" data-effect="mfp-zoom-in">Login</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="white-popup mfp-with-anim mfp-hide gmz-popup-form" id="gmz-reset-popup">
        <div class="popup-inner">
            <h4 class="popup-title">Password Recovery</h4>
            <div class="popup-content">
                <form class="text-left gmz-form-action account-form"
                    action="https://www.zanzibarbookings.com/password/email" method="POST">
                    <div class="gmz-loader">
                        <div class="loader-inner">
                            <div class="spinner-grow text-info align-self-center loader-lg"></div>
                        </div>
                    </div>
                    <div class="form">
                        <div id="email-field" class="field-wrapper input">
                            <div class="d-flex justify-content-between">
                                <label for="femail">EMAIL</label>
                            </div>
                            <i class="fal fa-at"></i>
                            <input id="femail" name="email" type="text" class="form-control gmz-validation"
                                data-validation="required" value="" placeholder="Email" />
                        </div>

                        <div class="gmz-message"></div>

                        <div class="d-sm-flex justify-content-between pb-2">
                            <div class="field-wrapper">
                                <button type="submit" class="btn btn-primary" value="">
                                    RESET
                                </button>
                            </div>
                        </div>

                        <p>Enter your email and instructions will sent to you!</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@livewireScripts()
    @include('website.layouts.js')
</body>

</html>
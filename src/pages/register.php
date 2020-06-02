<?php

session_start();
include_once "../assets/php/auth.php";
include_once "../assets/php/HeadBuilder.php";

$headTags = [
    "link" => [
        [
            "rel" => "stylesheet",
            "href" => "https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
        ],
        [
            "rel" => "stylesheet",
            "href" => "../assets/libraries/bootstrap/bootstrap.min.css",
            "type" => "text/css"
        ],
        [
            "rel" => "stylesheet",
            "href" => "../assets/dist/planningpoker.min.css",
            "type" => "text/css"
        ],
        [
            "rel" => "stylesheet",
            "href" => "//cdn.materialdesignicons.com/5.1.45/css/materialdesignicons.min.css",
            "type" => "text/css"
        ],
        [
            "rel" => "stylesheet",
            "href" => "../assets/css/materialdesignicons.helper.css",
            "type" => "text/css"
        ]
    ],
    "script" => [
        [
            "src" => "../assets/libraries/jquery/jquery-3.5.1.min.js",
        ],
        [
            "src" => "../assets/libraries/bootstrap/bootstrap.bundle.min.js"
        ],
        [
            "src" => "../assets/libraries/lodash/lodash.min.js"
        ]
    ],
    "title" => "register"
];
$head = new HeadBuilder($headTags);

include_once "../layouts/nav.php";
$navArr = [
    "container" => true,
    "brand" => [
        "title" => "Nav",
        "href" => "#",
        "src" => "",
        "alt" => "",
        "width" => 0,
        "height" => 0
    ],
    "routes" => [],
    "icons" => [
        [
            "href" => "",
            "class" => "mdi mdi-24px mdi-facebook",
            "tooltip" => [
                "title" => "is this facebook"
            ]
        ],
        [
            "href" => "",
            "class" => "mdi mdi-24px mdi-google",
            "tooltip" => [
                "title" => "is this google"
            ]
        ]
    ],
    "profile" => [
        "name" => "",
        "items" => []
    ]
];
$nav = new NavBuilder($navArr);
?>

<!DOCTYPE html>
<html lang="en">
<?php $head->setTags() ?>

<body>
    <?php $nav->setNav() ?>
    <div class="container view d-flex justify-content-center align-items-center px-4 py-4">
        <div id="register-box" class="main-box main-box-open main-box-static">
            <div class="main-box-header">
                <div class="main-box-header-content">Register</div>
            </div>
            <div class="main-box-content">
                <form class="_form" action="../assets/php/register.php" method="GET">
                    <div class="_form-field _form-group _form-sm-group-col">
                        <div class="_input-single">
                            <input id="name" class="_input" type="text" placeholder="Username" name="name"></input>
                            <div class="_input-icon _icon-left">
                                <i class="mdi mdi-24px mdi-account-outline"></i>
                            </div>
                        </div>
                        <div class="_input-single">
                            <input id="email" class="_input" type="text" placeholder="E-Mail" name="email"></input>
                            <div class="_input-icon _icon-left">
                                <i class="mdi mdi-24px mdi-email-outline"></i>
                            </div>
                        </div>
                    </div>
                    <div class="_form-field _form-group _form-sm-group-col">
                        <div class="_input-single">
                            <input id="password1" class="_input" type="password" placeholder="Password" name="password1"></input>
                            <div class="_input-icon _icon-left">
                                <i class="mdi mdi-24px mdi-lock-outline"></i>
                            </div>
                            <div class="_input-icon _icon-right">
                                <i id="revealPassword1" class="mdi mdi-24px mdi-eye-outline"></i>
                            </div>
                        </div>
                        <div class="_input-single">
                            <input id="password2" class="_input" type="password" placeholder="Repeat Password" name="password2"></input>
                            <div class="_input-icon _icon-left">
                                <i class="mdi mdi-24px mdi-lock-outline"></i>
                            </div>
                            <div class="_input-icon _icon-right">
                                <i id="revealPassword2" class="mdi mdi-24px mdi-eye-outline"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input id="submit" type="submit" value="submit" class="btn _button-default btn-lg-block" style="width: 200px;" disabled></input>
                    </div>
                </form>
            </div>
            <div class="main-box-footer">
                <div class="main-box-footer-content">
                    <a href="./login.php">Already have an account? Login here!</a>
                </div>
                <div class="main-box-expand">
                    <i class="mdi mdi-36px"></i>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (sizeof($_SESSION['LASTERROR']) > 0)
        include_once "../components/modal.php";
    ?>

    <?php include_once "../assets/php/planningpoker.js.php"; ?>
    <script>
        function revealPassword(id) {
            if ($(`#${id}`).hasClass('mdi-eye-outline')) {
                $(`#${id}`).removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
                $(`#${id}`).parents('._input-single').children('._input').attr('type', "text");
            } else {
                $(`#${id}`).removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
                $(`#${id}`).parents('._input-single').children('._input').attr('type', 'password');
            }
        }
        $('i#revealPassword1').click(function() {
            revealPassword(this.id);
        });
        $('i#revealPassword2').click(function() {
            revealPassword(this.id);
        });
    </script>
    <script>
        var inputChecksums = {
            checksums: {
                'name': false,
                'email': false,
                'password1': false,
                'password2': false
            },
            get get() {
                return this.checksums;
            },
            set set(args) {
                this.checksums[args.id] = args.bool;
                handleSubmit('submit', this.checksums);
            }
        };

        addAfter('name', 'info', 'Requirements: 3+ characters');

        $('#name').keyup(function() {
            let check = usernameCheck($(this).val());

            inputChecksums.set = {
                id: this.id,
                bool: check == true ? true : false
            };

            if (check == true)
                return changeAfter('name', 'Requirements: 3+ characters', 'success');

            return changeAfter('name', check, 'error');
        });

        addAfter('email', 'info', 'Requirements: E-Mail must be valid');

        $('#email').keyup(function() {
            let check = emailCheck($(this).val());

            inputChecksums.set = {
                id: this.id,
                bool: check == true ? true : false
            };

            if (check == true)
                return changeAfter('email', 'Requirements: E-Mail must be valid', 'success');

            return changeAfter('email', check, 'error');
        });

        addAfter('password1', 'info', 'Requirements: 8+ characters, 1+ digit, 1+ special character');

        $('#password1').keyup(function() {
            let check = passwordCheck($(this).val());

            inputChecksums.set = {
                id: this.id,
                bool: check == true ? true : false
            };

            if (check == true)
                return changeAfter('password1', 'Requirements: 8+ characters, 1+ digit, 1+ special character', 'success');

            return changeAfter('password1', check, 'error');
        });

        addAfter('password2', 'info', 'Requirements: Passwords must match');

        $('#password2').keyup(function() {
            let check = passwordCompare($('#password1').val(), $(this).val());

            inputChecksums.set = {
                id: this.id,
                bool: check == true ? true : false
            };

            if (check == true)
                return changeAfter('password2', 'Requirements: Passwords must match', 'success');

            return changeAfter('password2', check, 'error');
        });
    </script>
</body>

</html>

<!--
	info: mdi-information-outline
	error: mdi-alert-octagon-outline
	warning: mdi-alert-outline
	success: mdi-check-outline
 -->
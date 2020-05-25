<?php

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

?>

<!DOCTYPE html>
<html lang="en">
<?php $head->setTags() ?>

<body>
    <?php
    require_once "../layouts/nav.php";
    ?>
    <div class="container view d-flex justify-content-center align-items-center px-4 py-4">
        <div id="register-box" class="main-box main-box-open main-box-static">
            <div class="main-box-header">
                <div class="main-box-header-content">Register</div>
            </div>
            <div class="main-box-content">
                <form class="_form" action="../assets/php/register.php" method="GET">
                    <div class="_form-field _form-group _form-sm-group-col">
                        <div class="_input-single">
                            <input class="_input" type="text" placeholder="Username" name="name"></input>
                            <div class="_input-icon _icon-left">
                                <i class="mdi mdi-24px mdi-account-outline"></i>
                            </div>
                        </div>
                        <div class="_input-single">
                            <input class="_input" type="text" placeholder="E-Mail" name="email"></input>
                            <div class="_input-icon _icon-left">
                                <i class="mdi mdi-24px mdi-email-outline"></i>
                            </div>
                        </div>
                    </div>
                    <div class="_form-field _form-group _form-sm-group-col">
                        <div class="_input-single">
                            <input class="_input" type="password" placeholder="Password" name="password1"></input>
                            <div class="_input-icon _icon-left">
                                <i class="mdi mdi-24px mdi-lock-outline"></i>
                            </div>
                            <div class="_input-icon _icon-right">
                                <i id="clearPassword" class="mdi mdi-24px mdi-eye-outline"></i>
                            </div>
                        </div>
                        <div class="_input-single">
                            <input class="_input" type="password" placeholder="Repeat Password" name="password2"></input>
                            <div class="_input-icon _icon-left">
                                <i class="mdi mdi-24px mdi-lock-outline"></i>
                            </div>
                            <div class="_input-icon _icon-right">
                                <i id="clearPassword_" class="mdi mdi-24px mdi-eye-outline"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input type="submit" value="submit" class="btn _button-default btn-lg-block" style="width: 200px;"></input>
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

    <script src="../assets/js/PlanningPoker.js"></script>
    <script>
        //$('._input').after('<div class="_input-after _input-error"><i class="mdi mdi-24px mdi-alert-octagon-outline"></i>Username already taken</div>');
        $('i#clearPassword').click(function() {
            if ($(this).hasClass('mdi-eye-outline')) {
                $(this).removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
                $(this).parents('._input-single').children('._input').attr('type', "text");
            } else {
                $(this).removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
                $(this).parents('._input-single').children('._input').attr('type', 'password');
            }
        });
        $('i#clearPassword_').click(function() {
            if ($(this).hasClass('mdi-eye-outline')) {
                $(this).removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
                $(this).parents('._input-single').children('._input').attr('type', "text");
            } else {
                $(this).removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
                $(this).parents('._input-single').children('._input').attr('type', 'password');
            }
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
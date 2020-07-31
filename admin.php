<?php
require_once "controler/userloginController.php";
require_once "views/view.main.php";
?>

<body scroll="no" style="overflow-x:hidden;overflow-y:hidden">
    <div id="container">
        <div class="lyear-login-box">
            <div class="lyear-login-left">
                <ul class="w3lsg-bubbles">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
                <div class="lyear-overlay"></div>
                <div class="lyear-featured">
                    <h4>
                        <?php echo $mingyan_contents; ?>
                        <small>
                            - <?php echo $mingyan_author; ?>
                        </small>
                    </h4>
                </div>
            </div>
            <div class="lyear-login-right form__content">
                <div class="lyear-logo text-center">
                    <a href="index.php">
                        <img src="views/images/logo-sidebar.png">
                    </a>
                </div>
                <form id="LoginForm" method="post">
                    <div class="form-group has-feedback feedback-left">
                        <input type="text" placeholder="请输入您的用户名" class="form-control" name="username"
                        id="username" />
                        <span class="mdi mdi-account form-control-feedback" aria-hidden="true">
                        </span>
                    </div>
                    <div class="form-group has-feedback feedback-left">
                        <input type="password" placeholder="请输入密码" class="form-control" id="password"
                        name="password" />
                        <span class="mdi mdi-lock form-control-feedback" aria-hidden="true">
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="lyear-checkbox checkbox-primary pull-left m-b-10">
                            <input type="checkbox" name="rememberpass">
                            <span>
                                记住7天
                            </span>
                        </label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary" id="login_key_enter">
                            进入后台
                        </button>
                    </div>
                </form>
                <footer class="text-center">
                    <p class="m-b-0">
                        Copyright © 2020
                        <a href="http://www.luo2888.cn">
                            luo2888.cn
                        </a>
                        . All right reserved
                    </p>
                </footer>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        // 消息提示示例
        $('#login_key_enter').on('click',
        function() {
            lightyear.loading('show');
        });
    </script>
</body>
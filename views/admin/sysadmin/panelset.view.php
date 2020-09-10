<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4>修改管理员账号</h4>
                <button type="button" class="btn btn-sm btn-primary pull-right" onclick="document.newuserform.submit()">修改</button>
            </div>
            <div class="card-body">
                <form method="post" name="newuserform">
                    <div class="example-box">
                        <input class="form-control" type="hidden" name="olduser" value="<?php echo $admin; ?>">
                        <label class="btn-block">新用户名</label>
                        <input class="form-control" type="text" name="newuser" value="" size="80"><br>
                        <label class="btn-block">确认新用户名</label>
                        <input class="form-control" type="text" name="newuser_confirm" value="" size="80"><br>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-header">
                <h4>修改管理员密码</h4>
                <button type="button" class="btn btn-sm btn-primary pull-right" onclick="document.newpassform.submit()">修改</button>
            </div>
            <div class="card-body">
                <form method="post" name="newpassform">
                    <div class="example-box">
                    <label class="btn-block">旧密码</label>
                        <input class="form-control" type="password" name="oldpassword" value="" size="80"><br>
                    <label class="btn-block">新密码</label>
                        <input class="form-control" type="password" name="newpassword" value="" size="80"><br>
                    <label class="btn-block">确认新密码</label>
                        <input class="form-control" type="password" name="newpassword_confirm" value="" size="80"><br>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-header">
                <h4>安全入口设置</h4>
                <form method="post">
                    <button type="button" class="btn btn-sm btn-primary pull-right" onclick="document.skeyform.submit()">保存</button>
                    <button class="btn btn-sm btn-danger pull-right m-r-5" type="submit" name="closesecret_key" value="" <?php if(empty($secret_key)){echo 'disabled';} ?>><?php if (empty($secret_key)){echo '未设置安全码';} else {echo '关闭安全码';} ?></button>
                </form>
            </div>
            <div class="card-body">
                <form method="post" name="skeyform">
                    <div class="form-group">
                        <label class="btn-block">新安全码</label>
                        <input class="form-control" type="password" name="newskey" value=""><br>
                        <label class="btn-block">确认新安全码</label>
                        <input class="form-control" type="password" name="newskey_confirm" value="">
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-header">
                <h4>支付宝接口设置</h4>
                <button type="button" class="btn btn-sm btn-primary pull-right" onclick="document.alipayform.submit()">保存</button>
            </div>
            <div class="card-body">
                <form method="post" name="alipayform">
                    <div class="form-group">
                        <label class="btn-block">应用ID</label>
                        <input class="form-control" type="text" name="alipay_appid" value="<?php echo $alipay_appid; ?>">
                        <small class="help-block"><a href="https://docs.open.alipay.com/200/105310" target="_blank">应用生成教程</a></small>
                        <label class="btn-block">应用私钥</label>
                        <textarea class="form-control" rows="5" name="alipay_privatekey"><?php echo $alipay_privatekey; ?></textarea>
                        <small class="help-block"><a href="https://docs.open.alipay.com/291/105971" target="_blank">应用私钥生成教程</a></small>
                        <label class="btn-block">支付宝公钥</label>
                        <textarea class="form-control" rows="4" name="alipay_publickey"><?php echo $alipay_publickey; ?></textarea>
                        <small class="help-block"><a href="https://docs.open.alipay.com/291/105972" target="_blank">支付宝公钥获取教程</a></small>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-header"><h4>数据库操作</h4></div>
            <div class="card-body">
                <div class="input-group">
                    <div class="input-group-btn">
                    <form method="post">
                        <button type="submit" name="update_rankey" class="btn btn-info m-r-5" onclick="return confirm('确认更新随机密钥吗？')">更新随机密钥</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once "../view.section.php";
require_once "../../controler/agentadminController.php"
?>

<!--页面主要内容-->
<main class="lyear-layout-content">
	<div class="container-fluid">
		<div class='main-content'>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>系统公告</h4>
            				<button type="button" class="btn btn-sm btn-primary pull-right" onclick="document.noticeform.submit()">保存</button>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <form method="post" name="noticeform">
                                    <input type="hidden" name="name" value="<?php echo $user ?>"/>
                                    <div class="form-group">
                                        <label>滚动公告</label>
                                        <textarea class="form-control" rows="5" name="adtext" placeholder="请输入公告内容" ><?php echo $db->mGet("luo2888_agents", "adtext", "where name='$user'") ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>预留文字</label>
                                        <textarea class="form-control" rows="5" name="adinfo" placeholder="请输入文字内容" ><?php echo $db->mGet("luo2888_agents", "adinfo", "where name='$user'");?></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card">
                        <div class="card-header">
                            <h4>修改密码</h4>
                            <button type="button" class="btn btn-sm btn-primary pull-right" onclick="document.newpassform.submit()">修改</button>
                        </div>
                        <div class="card-body">
                            <form method="post" name="newpassform">
                                <input type="hidden" name="name" value="<?php echo $user ?>"/>
                                <div class="example-box">
                                    <label class="btn-block">新密码</label>
                                    <input class="form-control" type="password" name="newpass" value="" size="80"><br>
                                    <label class="btn-block">确认新密码</label>
                                    <input class="form-control" type="password" name="newpass_confirm" value="" size="80"><br>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</main>
<!--End 页面主要内容-->
</div>
</div>
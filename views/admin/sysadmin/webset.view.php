<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4>网页端设置</h4>
                <button type="button" class="btn btn-sm btn-primary pull-right" onclick="document.websetform.submit()">保存</button>
            </div>
            <div class="tab-content">
                <div class="tab-pane active">
                    <form method="post" name="websetform">
                        <div class="form-group">
                            <label class="btn-block">网页标题</label>
                            <input class="form-control" type="text" name="web_title" value="<?php echo $web_title; ?>">
                        </div>
                        <div class="form-group">
                            <label class="btn-block">主页描述</label>
                            <textarea  class="form-control" name="web_description" placeholder="请输入内容" rows="6" autofocus><?php echo $web_description; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="btn-block">网盘下载地址</label>
                            <input class="form-control" type="text" name="panurl" value="<?php echo $panurl; ?>">
                        </div>
                        <div class="form-group">
                            <label class="btn-block">下载页描述(HTML格式)</label>
                            <textarea  class="form-control" name="web_appinfo" placeholder="请输入内容" rows="6" autofocus><?php echo $web_appinfo; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="btn-block">关于页描述(HTML格式)</label>
                            <textarea  class="form-control" name="web_about" placeholder="请输入内容" rows="6" autofocus><?php echo $web_about; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="btn-block">版权描述(HTML格式)</label>
                            <input class="form-control" type="text" name="web_copyright" value='<?php echo $web_copyright; ?>'>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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
                            <textarea class="form-control" rows="5" name="adtext" placeholder="请输入公告内容" ><?php echo $adtext ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>预留文字</label>
                            <textarea class="form-control" rows="5" name="adinfo" placeholder="请输入文字内容" ><?php echo $adinfo;?></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
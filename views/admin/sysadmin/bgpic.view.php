<form name="splashform" method="post" style="display: none;" enctype="multipart/form-data">
    <input type="file" name="splash" accept="image/png" onchange="document.splashform.submitsplash.click()"/>
    <input type="submit" name="submitsplash" value="">
</form>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4>背景图片</h4>
                <button type="button" class="btn btn-sm btn-primary pull-right" onclick="document.splashform.splash.click()">上传</button>
            </div>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="table-responsive" >
                        <table class="table table-hover table-vcenter">
                            <thead>
                                <tr>
                                    <th>图片名称</th>
                                    <th>文件时间</th>
                                    <th>图片大小</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (empty($files)) {
                                    echo '
                                        <tr align="center">
                                            <td colspan="5">暂无图片</td>
                                        </tr>';
                                }
                                foreach ($files as $file) {
                                    $fctime=date("Y-m-d H:i:s",filectime($file));
                                    $fsize=filesize($file);
                                    $url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]; 
                                    $splashurl=dirname($url).'/'.$file;
                                    $file=basename($file);
                                    if($fsize>=1024){
                                        $fsize=round($fsize / 1024 * 100) / 100 . ' KB';
                                    }else{
                                        $fsize=$fsize ." B";
                                    }
                                    echo "
                                <tr>
                                    <td>$file</td>
                                    <td>$fctime</td>
                                    <td>$fsize</td>
                                    <td>
                                        <form method='post'>
                                            <button class=\"btn btn-sm btn-secondary\" type='button' onclick=\"javascript:window.open('$splashurl')\">预览</button>
                                            <input type='hidden' name='file' value='$file'>
                                            <button class=\"btn btn-sm btn-danger\" type='submit' name='submitdelbg' onclick=\"return confirm('确认删除？')\" >删除</button>
                                        </form>
                                    </td>
                                </tr>";
                                }
                                unset($files);
                                ?>
                                <tr>
                                    <td colspan="5">
                                        <div class="form-group">
                                            <small class="help-block">提示：图片仅支持PNG格式，不超过800KB，多张图片为随机显示。</small>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#submit').on('click', function(){
        lightyear.loading('show');
    });
</script>
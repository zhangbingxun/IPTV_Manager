<?php include_once "view.section.php";include_once "../apps/sysadminController.php" ?>
<script type="text/javascript">
var showindex=0;
function showli(index){
	$(".main-content li").hide();
	$($(".main-content li")[index]).fadeIn();
	showindex=index;
}
</script>
    <!--页面主要内容-->
    <main class="lyear-layout-content">
		<div class="container-fluid">
			<div class='main-content'>
				<ul class='list-unstyled'>
					<li>
						<?php include "./sysadmin/notice.view.php" ?>
					</li>
					<li>
						<?php include "./sysadmin/backup.view.php" ?>
					</li>
					<li>
						<?php include "./sysadmin/appset.view.php" ?>
					</li>
					<li>
						<?php include "./sysadmin/bgpic.view.php" ?>
					</li>
					<li>
						<?php include "./sysadmin/adminrec.view.php" ?>
					</li>
					<li>
						<?php include "./sysadmin/password.view.php" ?>
					</li>
					<li>
						<?php include "./sysadmin/admins.view.php" ?>
					</li>
				</ul>
			</div>
		</div>
    </main>
    <!--End 页面主要内容-->
  </div>
</div>

<script type="text/javascript">
showli(showindex);
</script>
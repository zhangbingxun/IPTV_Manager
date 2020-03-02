<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header"><h4>系统备份</h4></div>
			<div class="tab-content">
				<div class="tab-pane active">
					<form method="post">
						<div class="form-group">
							<label>用户数据</label>
							<textarea class="form-control" style="height: 350px;" rows="5" name="userdata" ><?php echo $userdata;?></textarea>
						</div>
						<div class="form-group">
							<label>授权天数</label>
							<input class="form-control" type="text" name="exp" value="365" >
						</div>
						<div class="form-group">
							<label>备注</label>
							<input class="form-control" type="text" name="marks" value="" >
						</div>
						<div class="form-group">
							<button type="submit" name="submitimport" class="btn btn-default">导入用户数据</button>
							<button type="submit" name="submitexport" class="btn btn-default">导出用户数据</button>
							<a target="_blank" href="../apps/dbbackup.php"><button type="button" class="btn btn-primary m-r-5">备份数据库</button></a>
							<a target="_blank" href="../apps/dbrestore.php" onclick="return confirm('确认请全部数据恢复到上次备份的状态？恢复过程中不要进行任何管理操作。')"><button type="button" class="btn btn-danger m-r-5">还原数据库</button></a>
							<a target="_blank" href="../apps/randkey.php" onclick="return confirm('确认更新randkey吗？')"><button type="button" class="btn btn-info m-r-5">更新randkey</button></a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
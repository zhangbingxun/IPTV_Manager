<? include "apps/secretkeyController.php" ?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title>欢迎登录IPTV管理系统</title>
<link rel="icon" href="views/images/favicon.ico" type="image/ico">
<meta name="keywords" content="小肥米,后台管理系统">
<meta name="description" content="小肥米IPTV后台管理系统">
<meta name="author" content="luo2888">
<link href="views/css/bootstrap.min.css" rel="stylesheet">
<link href="views/css/materialdesignicons.min.css" rel="stylesheet">
<link href="views/css/style.min.css" rel="stylesheet">
<link href="views/css/login.css" rel="stylesheet" >
<style>
</style>
</head>
  
<body scroll="no" style="overflow-x:hidden;overflow-y:hidden">
	<div id="container" class="row lyear-wrapper">
		<div class="lyear-login">
			<div id="bg">
				<div id="anitOut"></div>
			</div>
			<div class="login-center form__content">
				<div class="login-header text-center">
					<a href="index.php"> <img alt="light year admin" src="views/images/logo-sidebar.png"> </a>
				</div>
				<?php if($_SESSION['secret_key_status']=='1'){include "views/userlogin.php";}?>
				<form id="secret_keyForm" method="post">
					<div class="form-group has-feedback feedback-left">
						<input type="password" name="secret_key" class="form-control" placeholder="请输入安全验证码">
						<span class="mdi mdi-check-all form-control-feedback" aria-hidden="true"></span>
					</div>
					<div class="form-group has-feedback feedback-left">
						<label class="lyear-checkbox checkbox-primary pull-left m-b-10">
							<input type="checkbox" name="remembersecret_key" value="1">
							<span>记住7天</span>
						</label>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-block btn-primary" name="secret_key_enter" id="secret_key_enter">立即登陆</button>
					</div>
				</form>
				<hr>
				<footer class="col-sm-12 text-center">
					<p class="m-b-0">Copyright © 2020 <a href="http://www.luo2888.cn">luo2888.cn</a>. All right reserved</p>
				</footer>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="views/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="views/js/perfect-scrollbar.min.js"></script>
	<script type="text/javascript" src="views/js/lightyear.js"></script>
	<script type="text/javascript" src="views/js/main.min.js"></script>
	<script type="text/javascript" src="views/js/jquery.min.js"></script>
	<script src="views/js/login.js"></script>
	<script type="text/javascript">
	// 消息提示示例
	$('#secret_key_enter').on('click', function(){
	    lightyear.loading('show');
	});
	$('#login_key_enter').on('click', function(){
	    lightyear.loading('show');
	});
	</script>
	<script type="text/javascript">
	$(function () {
		if (!window.ActiveXObject && !!document.createElement("canvas").getContext) {
			$.getScript("views/js/cav.js",
					function () {
						var t = {
							width: 1.5,
							height: 1.5,
							depth: 10,
							segments: 12,
							slices: 6,
							xRange: 0.8,
							yRange: 0.1,
							zRange: 1,
							ambient: "#525252",
							diffuse: "#FFFFFF",
							speed: 0.0002
						};
						var G = {
							count: 2,
							xyScalar: 1,
							zOffset: 100,
							ambient: "#002c4a",
							diffuse: "#33cabb",
							speed: 0.001,
							gravity: 1200,
							dampening: 0.95,
							minLimit: 10,
							maxLimit: null,
							minDistance: 20,
							maxDistance: 400,
							autopilot: false,
							draw: false,
							bounds: CAV.Vector3.create(),
							step: CAV.Vector3.create(Math.randomInRange(0.2, 1), Math.randomInRange(0.2, 1), Math.randomInRange(0.2, 1))
						};
						var m = "canvas";
						var E = "svg";
						var x = {
							renderer: m
						};
						var i, n = Date.now();
						var L = CAV.Vector3.create();
						var k = CAV.Vector3.create();
						var z = document.getElementById("container");
						var w = document.getElementById("anitOut");
						var D, I, h, q, y;
						var g;
						var r;

						function C() {
							F();
							p();
							s();
							B();
							v();
							K(z.offsetWidth, z.offsetHeight);
							o()
						}

						function F() {
							g = new CAV.CanvasRenderer();
							H(x.renderer)
						}

						function H(N) {
							if (D) {
								w.removeChild(D.element)
							}
							switch (N) {
								case m:
									D = g;
									break
							}
							D.setSize(z.offsetWidth, z.offsetHeight);
							w.appendChild(D.element)
						}

						function p() {
							I = new CAV.Scene()
						}

						function s() {
							I.remove(h);
							D.clear();
							q = new CAV.Plane(t.width * D.width, t.height * D.height, t.segments, t.slices);
							y = new CAV.Material(t.ambient, t.diffuse);
							h = new CAV.Mesh(q, y);
							I.add(h);
							var N, O;
							for (N = q.vertices.length - 1; N >= 0; N--) {
								O = q.vertices[N];
								O.anchor = CAV.Vector3.clone(O.position);
								O.step = CAV.Vector3.create(Math.randomInRange(0.2, 1), Math.randomInRange(0.2, 1), Math.randomInRange(0.2, 1));
								O.time = Math.randomInRange(0, Math.PIM2)
							}
						}

						function B() {
							var O, N;
							for (O = I.lights.length - 1; O >= 0; O--) {
								N = I.lights[O];
								I.remove(N)
							}
							D.clear();
							for (O = 0; O < G.count; O++) {
								N = new CAV.Light(G.ambient, G.diffuse);
								N.ambientHex = N.ambient.format();
								N.diffuseHex = N.diffuse.format();
								I.add(N);
								N.mass = Math.randomInRange(0.5, 1);
								N.velocity = CAV.Vector3.create();
								N.acceleration = CAV.Vector3.create();
								N.force = CAV.Vector3.create()
							}
						}

						function K(O, N) {
							D.setSize(O, N);
							CAV.Vector3.set(L, D.halfWidth, D.halfHeight);
							s()
						}

						function o() {
							i = Date.now() - n;
							u();
							M();
							requestAnimationFrame(o)
						}

						function u() {
							var Q, P, O, R, T, V, U, S = t.depth / 2;
							CAV.Vector3.copy(G.bounds, L);
							CAV.Vector3.multiplyScalar(G.bounds, G.xyScalar);
							CAV.Vector3.setZ(k, G.zOffset);
							for (R = I.lights.length - 1; R >= 0; R--) {
								T = I.lights[R];
								CAV.Vector3.setZ(T.position, G.zOffset);
								var N = Math.clamp(CAV.Vector3.distanceSquared(T.position, k), G.minDistance, G.maxDistance);
								var W = G.gravity * T.mass / N;
								CAV.Vector3.subtractVectors(T.force, k, T.position);
								CAV.Vector3.normalise(T.force);
								CAV.Vector3.multiplyScalar(T.force, W);
								CAV.Vector3.set(T.acceleration);
								CAV.Vector3.add(T.acceleration, T.force);
								CAV.Vector3.add(T.velocity, T.acceleration);
								CAV.Vector3.multiplyScalar(T.velocity, G.dampening);
								CAV.Vector3.limit(T.velocity, G.minLimit, G.maxLimit);
								CAV.Vector3.add(T.position, T.velocity)
							}
							for (V = q.vertices.length - 1; V >= 0; V--) {
								U = q.vertices[V];
								Q = Math.sin(U.time + U.step[0] * i * t.speed);
								P = Math.cos(U.time + U.step[1] * i * t.speed);
								O = Math.sin(U.time + U.step[2] * i * t.speed);
								CAV.Vector3.set(U.position, t.xRange * q.segmentWidth * Q, t.yRange * q.sliceHeight * P, t.zRange * S * O - S);
								CAV.Vector3.add(U.position, U.anchor)
							}
							q.dirty = true
						}

						function M() {
							D.render(I)
						}

						function J(O) {
							var Q, N, S = O;
							var P = function (T) {
								for (Q = 0, l = I.lights.length; Q < l; Q++) {
									N = I.lights[Q];
									N.ambient.set(T);
									N.ambientHex = N.ambient.format()
								}
							};
							var R = function (T) {
								for (Q = 0, l = I.lights.length; Q < l; Q++) {
									N = I.lights[Q];
									N.diffuse.set(T);
									N.diffuseHex = N.diffuse.format()
								}
							};
							return {
								set: function () {
									P(S[0]);
									R(S[1])
								}
							}
						}

						function v() {
							window.addEventListener("resize", j)
						}

						function A(N) {
							CAV.Vector3.set(k, N.x, D.height - N.y);
							CAV.Vector3.subtract(k, L)
						}

						function j(N) {
							K(z.offsetWidth, z.offsetHeight);
							M()
						}

						C();
					})
		} else {
			alert('调用cav.js失败');
		}
	});
    lightyear.loading('show');
    setTimeout(function() {
        lightyear.loading('hide');
    }, 1e3)
	</script>
	<?php if($_SESSION['secret_key_status']=='1'){
		echo '<script type="text/javascript">$("#secret_keyForm").hide;$("#secret_keyForm").hide(0);</script>';
		}
	?>
</body>
</html>
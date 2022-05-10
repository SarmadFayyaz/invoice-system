<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Letter Head</title>
	<style>
		.logo {
			width: 30%;
			top: 0;
			position: absolute;
			left: 0;
		}

		.right {
			width: 40%;
			top: 0;
			position: absolute;
			right: 0;
		}

		.top {
			width: 100%;
		}

		.bottom {
			position: absolute;
			bottom: 0;
			width: 100%;
			/* background-image: url("img/bg-print.png");
			background-color: #ff9c03;
			font-size: 20px;
			padding: 10px 20px;
			border-top: 5px solid; */
		}
	</style>
</head>

<body style="margin: 0;">

	<div>
		<div class="top">
			<img src="img/logo.png" alt="" class="logo">
			<img src="img/right.png" alt="" class="right">
		</div>
	</div>

	<div class="bottom">
			<img src="img/bottom.png" alt="" style="width: 100%;">
		<!-- <p>
			Office # PD-74/1, Saidpur Road, Pindora, Rawalpindi. Ph: 0514840039
			<br>
			Email: btsengineering.isb@gmail.com
		</p> -->
	</div>

	<script type="text/javascript">
		window.onload = function() {
			window.print();
		}
	</script>

</body>

</html>
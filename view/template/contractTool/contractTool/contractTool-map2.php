<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="GBK" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Sticky Table Headers Revisited | Demo 1</title>
		<meta name="description" content="Sticky Table Headers Revisited: Creating functional and flexible sticky table headers" />
		<meta name="keywords" content="Sticky Table Headers Revisited" />
		<meta name="author" content="Codrops" />
		<link rel="stylesheet" type="text/css" href="../css/newstyle/normalize.css" />
		<link rel="stylesheet" type="text/css" href="../css/newstyle/demo.css" />
		<link rel="stylesheet" type="text/css" href="../css/newstyle/component.css" />
		<!--[if IE]>
  		<script src="../js/html5.js"></script>
		<![endif)-->
		<style>
			.box_nav {
				line-height: 10em;
				margin: 0 auto;
				padding: 0;
				width: 96%;
				max-width: 1200px;
				overflow: hidden;
			}
			.box_table td,.box_table th {
				align: center;
				padding: 0;
				align : middle;
			}
			.box {
				background-color: #6699CC;
				margin: 5px 2px 5px 2px;
				padding: 2px;
				float: left;
				-webkit-border-radius: 1px;
				-moz-border-radius: 1px;
				border-radius: 1px;
			    text-align:left;
    			color: #fff;
				font-size: 1.8em;
				text-align:left;
				width : 95%;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="box_nav" align="center">
				<table class="box_table">
					<tr>
						<td rowspan="2" width="20%">
							<div class="box" style="height:280px;">
								&nbsp;筹备合同
								<span style="color:#2ECCFA;cursor:pointer;"
								title="点击查看源单"
								onclick="showModalWin(\'?model=contractTool_contractTool_contractTool&action=buildContract\')">(23)</span>
								>>
							</div>
						</td>
						<td width="20%">
							<div class="box">
								&nbsp;合同交付( 10 )
								>>
							</div>
						</td>
						<td width="20%">
							<div class="box">
								&nbsp;合同验收( 1 )
								>>
							</div>
						</td>
						<td width="20%">
							<div class="box">
								&nbsp;开票收款( 21 )
								>>
							</div>
						</td>
						<td width="20%" rowspan="2">
							<div class="box" style="height:280px;">
								&nbsp;合同关闭( 56 )
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<div class="box" style="width:98.2%;">
								&nbsp;合同文本( 7 ]
								>>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div><!-- /container -->
		<div class="container">
			<div class="component">
				<table class="stickyheader">
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Mobile</th>
						</tr>
					</thead>
					<tbody>
						<tr><td class="user-name">王禅林</td><td class="user-email">gary.coleman21@example.com</td><td class="user-phone">(398)-332-5385</td><td class="user-mobile">(888)-677-3719</td></tr>
						<tr><td class="user-name">rose parker</td><td class="user-email">rose.parker16@example.com</td><td class="user-phone">(293)-873-2247</td><td class="user-mobile">(216)-889-4933</td></tr>
						<tr><td class="user-name">chloe nelson</td><td class="user-email">chloe.nelson18@example.com</td><td class="user-phone">(957)-213-3499</td><td class="user-mobile">(207)-516-4474</td></tr>
						<tr><td class="user-name">eric bell</td><td class="user-email">eric.bell16@example.com</td><td class="user-phone">(897)-762-9782</td><td class="user-mobile">(565)-627-3002</td></tr>
						<tr><td class="user-name">douglas hayes</td><td class="user-email">douglas.hayes92@example.com</td><td class="user-phone">(231)-391-6269</td><td class="user-mobile">(790)-838-2130</td></tr>
						<tr><td class="user-name">cameron brown</td><td class="user-email">cameron.brown32@example.com</td><td class="user-phone">(204)-488-5204</td><td class="user-mobile">(508)-463-6811</td></tr>
						<tr><td class="user-name">nevaeh diaz</td><td class="user-email">nevaeh.diaz99@example.com</td><td class="user-phone">(436)-578-2946</td><td class="user-mobile">(906)-412-3302</td></tr>
						<tr><td class="user-name">kathy miller</td><td class="user-email">kathy.miller62@example.com</td><td class="user-phone">(724)-705-3555</td><td class="user-mobile">(764)-841-2531</td></tr>
						<tr><td class="user-name">susan king</td><td class="user-email">susan.king88@example.com</td><td class="user-phone">(774)-205-7754</td><td class="user-mobile">(639)-267-9728</td></tr>
						<tr><td class="user-name">jeffery ramirez</td><td class="user-email">jeffery.ramirez83@example.com</td><td class="user-phone">(723)-243-7706</td><td class="user-mobile">(172)-597-3422</td></tr>
						<tr><td class="user-name">gary coleman</td><td class="user-email">gary.coleman21@example.com</td><td class="user-phone">(398)-332-5385</td><td class="user-mobile">(888)-677-3719</td></tr>
						<tr><td class="user-name">rose parker</td><td class="user-email">rose.parker16@example.com</td><td class="user-phone">(293)-873-2247</td><td class="user-mobile">(216)-889-4933</td></tr>
						<tr><td class="user-name">chloe nelson</td><td class="user-email">chloe.nelson18@example.com</td><td class="user-phone">(957)-213-3499</td><td class="user-mobile">(207)-516-4474</td></tr>
						<tr><td class="user-name">eric bell</td><td class="user-email">eric.bell16@example.com</td><td class="user-phone">(897)-762-9782</td><td class="user-mobile">(565)-627-3002</td></tr>
						<tr><td class="user-name">douglas hayes</td><td class="user-email">douglas.hayes92@example.com</td><td class="user-phone">(231)-391-6269</td><td class="user-mobile">(790)-838-2130</td></tr>
						<tr><td class="user-name">cameron brown</td><td class="user-email">cameron.brown32@example.com</td><td class="user-phone">(204)-488-5204</td><td class="user-mobile">(508)-463-6811</td></tr>
						<tr><td class="user-name">nevaeh diaz</td><td class="user-email">nevaeh.diaz99@example.com</td><td class="user-phone">(436)-578-2946</td><td class="user-mobile">(906)-412-3302</td></tr>
						<tr><td class="user-name">kathy miller</td><td class="user-email">kathy.miller62@example.com</td><td class="user-phone">(724)-705-3555</td><td class="user-mobile">(764)-841-2531</td></tr>
						<tr><td class="user-name">susan king</td><td class="user-email">susan.king88@example.com</td><td class="user-phone">(774)-205-7754</td><td class="user-mobile">(639)-267-9728</td></tr>
						<tr><td class="user-name">jeffery ramirez</td><td class="user-email">jeffery.ramirez83@example.com</td><td class="user-phone">(723)-243-7706</td><td class="user-mobile">(172)-597-3422</td></tr>
						<tr><td class="user-name">gary coleman</td><td class="user-email">gary.coleman21@example.com</td><td class="user-phone">(398)-332-5385</td><td class="user-mobile">(888)-677-3719</td></tr>
						<tr><td class="user-name">rose parker</td><td class="user-email">rose.parker16@example.com</td><td class="user-phone">(293)-873-2247</td><td class="user-mobile">(216)-889-4933</td></tr>
						<tr><td class="user-name">chloe nelson</td><td class="user-email">chloe.nelson18@example.com</td><td class="user-phone">(957)-213-3499</td><td class="user-mobile">(207)-516-4474</td></tr>
						<tr><td class="user-name">eric bell</td><td class="user-email">eric.bell16@example.com</td><td class="user-phone">(897)-762-9782</td><td class="user-mobile">(565)-627-3002</td></tr>
						<tr><td class="user-name">douglas hayes</td><td class="user-email">douglas.hayes92@example.com</td><td class="user-phone">(231)-391-6269</td><td class="user-mobile">(790)-838-2130</td></tr>
						<tr><td class="user-name">cameron brown</td><td class="user-email">cameron.brown32@example.com</td><td class="user-phone">(204)-488-5204</td><td class="user-mobile">(508)-463-6811</td></tr>
						<tr><td class="user-name">nevaeh diaz</td><td class="user-email">nevaeh.diaz99@example.com</td><td class="user-phone">(436)-578-2946</td><td class="user-mobile">(906)-412-3302</td></tr>
						<tr><td class="user-name">kathy miller</td><td class="user-email">kathy.miller62@example.com</td><td class="user-phone">(724)-705-3555</td><td class="user-mobile">(764)-841-2531</td></tr>
						<tr><td class="user-name">susan king</td><td class="user-email">susan.king88@example.com</td><td class="user-phone">(774)-205-7754</td><td class="user-mobile">(639)-267-9728</td></tr>
						<tr><td class="user-name">jeffery ramirez</td><td class="user-email">jeffery.ramirez83@example.com</td><td class="user-phone">(723)-243-7706</td><td class="user-mobile">(172)-597-3422</td></tr>
					</tbody>
				</table>
			</div>
		</div><!-- /container -->
		<script src="../js/jquery/jquery-1.6.2.min.js"></script>
		<script src="../js/jquery/jquery.ba-throttle-debounce.min.js"></script>
		<script src="../js/jquery/jquery.stickyheader.js"></script>
		<!-- 核心组件 -->
		<script type="text/javascript" src="js/common/businesspage.js"></script>
		<script type="text/javascript" src="js/jquery/woo.js"></script>
		<script type="text/javascript" src="js/jquery/component.js"></script>
		<script type="text/javascript" src="js/jquery/dump.js"></script>

		<!-- 弹窗组件 -->
		<script type="text/javascript" src="js/thickbox.js"></script>
	</body>
</html>
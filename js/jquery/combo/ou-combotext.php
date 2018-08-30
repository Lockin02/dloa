<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>
    jquery combogrid demo
</title>
<meta http-equiv="content-type" content="text/html; charset=gbk" />
<script type="text/javascript" src="../jquery-1.4.2.js"></script>
<script type="text/javascript" src="../woo.js"></script>
<script type="text/javascript" src="../component.js"></script>
<script type="text/javascript" src="yxcombo.js"></script>
<script type="text/javascript" src="yxcombotext.js"></script>

<script type="text/javascript" src="../dump.js"></script>

<script type="text/javascript" src="../../../js/businesspage.js"></script>

<link type="text/css" media="screen" rel="stylesheet" href="../../../css/yxstyle.css" />
<link type="text/css" media="screen" rel="stylesheet" href="../style/yxmenu.css" />
<link type="text/css" media="screen" rel="stylesheet" href="../style/yxmenu.theme.css" />
<link type="text/css" media="screen" rel="stylesheet" href="../style/zTreeStyle.css" />
<script language="JavaScript" type="text/javascript">

$(function(){
	$("#ctext").yxcombo({
			html : '',					//html
			content : null,				//数据内容来源
			width : 400,				//下拉面板默认宽度
			height : 400,				//下拉面板默认高度
			positionOpts : {			//下拉面板位置相关设置
				posX : 'left',
				posY : 'bottom',
				offsetX : 0,
				offsetY : 0,
				directionH : 'right',	//下拉面板往左展开
				directionV : 'down',  	//下拉面板往下展开
				detectH : true,  		//是否进行水平溢出检测，如果设置下拉面板往右展开，当在浏览器右边展开的时候到达屏幕边界，自动往左展开
				detectV : false  		//是否进行垂直溢出检测，如果设置下拉面板往下展开，当在浏览器下边展开的时候到达屏幕边界，自动往上展开
			},
			showSpeed : 200  //速度显示/隐藏下拉面板毫秒
	});

	$("#ctext").yxcombotext({
		"hidId" : "ctexth"
		,"checkable" : true
	});
});

</script>
</head>
<body>
<br>
<br>
  <table align="center">
    <tr>
      <td>选择下拉表格信息</td>
      <td>
      	<input type="text" id="ctext" value="" name="tName" >
      	<input type="hidden" id="ctexth" name="ctexth" >
      </td>
      <td><input  type="hidden" id="parentId" value="1"/></td>
    </tr>
    <tr>
      <td>t10</td><td>t11</td><td>t12</td>
    </tr>
    <tr>
      <td>t20</td><td>t21</td><td>t22</td>
    </tr>
  </table>
    </body>

</html>
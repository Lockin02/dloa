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
			content : null,				//����������Դ
			width : 400,				//�������Ĭ�Ͽ��
			height : 400,				//�������Ĭ�ϸ߶�
			positionOpts : {			//�������λ���������
				posX : 'left',
				posY : 'bottom',
				offsetX : 0,
				offsetY : 0,
				directionH : 'right',	//�����������չ��
				directionV : 'down',  	//�����������չ��
				detectH : true,  		//�Ƿ����ˮƽ�����⣬������������������չ��������������ұ�չ����ʱ�򵽴���Ļ�߽磬�Զ�����չ��
				detectV : false  		//�Ƿ���д�ֱ�����⣬������������������չ��������������±�չ����ʱ�򵽴���Ļ�߽磬�Զ�����չ��
			},
			showSpeed : 200  //�ٶ���ʾ/��������������
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
      <td>ѡ�����������Ϣ</td>
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
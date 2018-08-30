<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>
            jquery woo menu demo
        </title>
        <meta http-equiv="content-type" content="text/html; charset=gbk" />
        <script type="text/javascript" src="../jquery-1.4.2.js"></script>
        <script type="text/javascript" src="../woo.js"></script>
        <script type="text/javascript" src="../component.js"></script>
        <script type="text/javascript" src="yxcombo.js"></script>
        <script type="text/javascript" src="../ztree/yxtree.js"></script>
        <script type="text/javascript" src="yxcombotree.js"></script>

        <link type="text/css" href="../style/yxmenu.css" media="screen" rel="stylesheet"/>
        <!-- 主题css，可切换 -->
        <link type="text/css" href="../style/yxmenu.theme.css" media="screen" rel="stylesheet"/>
        <link rel="stylesheet" href="../style/yxtree.css" type="text/css">
        <script type="text/javascript">

	$(document).ready(function(){

		$("#aaa").yxcombotree({
			hiddenId : 'bbb',//隐藏控件id
			treeOptions : {
				checkable : true,//多选
				url : "http://localhost/ioae/index1.php?model=stock_productinfo_producttype&action=getChildren",//获取数据url
				param : ["name", "id"]//传递树参数属性
			}
		});
		var a=$("#aaa").data('yxcombotree');

	});


        </script>
        <!-- theme switcher button <script type="text/javascript" src="../src/theme.js"></script>
        <script type="text/javascript"> $(function(){ $('<div style="position: absolute; top: 20px; right: 300px;" />').appendTo('body').themeswitcher(); }); </script>
        -->
    </head>

    <body>

    	<center><input type="text" id="aaa" class="aaa" / ><input type="hidden" id="bbb" class="aaa" / ></center>

    </body>

</html>
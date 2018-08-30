<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title></title>
		<script language="javascript" src="prototype.js"></script>
		<script language="javascript" src="richeditor.js"></script>
<style type="text/css">
body {margin:0px}

.ico {vertical-align:middle; width:24px; height:24px; text-align:center}
.ico2 {vertical-align:middle; width:27px; height:24px; text-align:center}
.ico3 {vertical-align:middle; width:25px; height:24px; text-align:center}
.ico4 {vertical-align:middle; width:8px; height:24px; text-align:center}

.icons{width:20px;height:20px;background-image:url(image/icons.gif);background-repeat:no-repeat;}
.iconRemoveFormat{background-position:-343px 0}
.iconFontFamily{width:25px;background-position:-293px 0}
.iconFontSize{width:25px;background-position:-318px 0}
.iconBold{background-position:-4px 0}
.iconItalic{background-position:-24px 0}
.iconUnderline{background-position:-44px 0}
.iconJustifyLeft{background-position:-64px 0}
.iconJustifyCenter{background-position:-84px 0}
.iconJustifyRight{background-position:-104px 0}
.iconInsertOrderedList{background-position:-124px 0}
.iconInsertUnorderedList{background-position:-144px 0}
.iconIndent{background-position:-164px 0}
.iconOutdent{background-position:-184px 0}
.iconInsertHyperlink{background-position:-204px 0}
.iconInsertImage{background-position:-227px 0}
.iconForeColor{background-position:-246px 0}
.iconBackColor{background-position:-269px 0}
.iconFontFamily{background-position:-293px 0}
.iconFontSize{background-position:-318px 0}
.iconInsertTable{background-position:-363px 0}
</style>
	</head>
	<body>
		<table style="border-right:#c5c5c5 1px solid;border-top:#c5c5c5 1px solid;border-left:#c5c5c5 1px solid;border-bottom:#c5c5c5 1px solid" cellSpacing=0 cellPadding=0 width="100%" background="image/bg.gif" border=0>
			<tbody>
			<tr>
				<td style="padding-left:5px" height="30">
					<table cellspacing="0" cellpadding="0" border="0">
						<tbody>
						<tr>
							<td class="ico"><div class="icons iconRemoveFormat"><img src="image/place.gif"  width="100%" height="100%" title="清除格式" onclick="javascript:_RichEditor.removeFormat();" /></td>
							<td class="ico2"><div class="icons iconFontFamily"><img src="image/place.gif" height="100%" width="100%" title="字体" onclick="javascript:_RichEditor.fontFamily(event);" /></div></td>
							<td class="ico2"><div class="icons iconFontSize"><img src="image/place.gif" height="100%" width="100%" title="字号" onclick="javascript:_RichEditor.fontSize(event);" /></div></td>
							<td class="ico"><div class="icons iconBold"><img src="image/place.gif" height="100%" width="100%" title="加粗" onclick="javascript:_RichEditor.format('Bold');" /></div></td>
							<td class="ico"><div class="icons iconItalic"><img src="image/place.gif" height="100%" width="100%" title="斜体" onclick="javascript:_RichEditor.format('Italic');" /></div></td>
							<td class="ico"><div class="icons iconUnderline"><img src="image/place.gif" height="100%" width="100%" title="下划线" onclick="javascript:_RichEditor.format('Underline');" /></div></td>
							<td class="ico"><div class="icons iconJustifyLeft"><img src="image/place.gif" height="100%" width="100%" title="靠左对齐" onclick="javascript:_RichEditor.format('Justifyleft');" /></div></td>
							<td class="ico"><div class="icons iconJustifyCenter"><img src="image/place.gif" height="100%" width="100%" title="居中对齐" onclick="javascript:_RichEditor.format('Justifycenter');" /></div></td>
							<td class="ico"><div class="icons iconJustifyRight"><img src="image/place.gif" height="100%" width="100%" title="靠右对齐" onclick="javascript:_RichEditor.format('Justifyright');" /></div></td>
							<td class="ico"><div class="icons iconInsertOrderedList"><img src="image/place.gif" height="100%" width="100%" title="数字编号" onclick="javascript:_RichEditor.format('Insertorderedlist');" /></div></td>
							<td class="ico"><div class="icons iconInsertUnorderedList"><img src="image/place.gif" height="100%" width="100%" title="项目编号" onclick="javascript:_RichEditor.format('Insertunorderedlist');" /></div></td>
							<td class="ico"><div class="icons iconIndent"><img src="image/place.gif" height="100%" width="100%" title="增加缩进" onclick="javascript:_RichEditor.format('Indent');" /><div></td>
							<td class="ico"><div class="icons iconOutdent"><img src="image/place.gif" height="100%" width="100%" title="减少缩进" onclick="javascript:_RichEditor.format('Outdent');" /></div></td>
							<td class="ico"><img height="20" src="image/line.gif" width="4"></td>
							<td class="ico2"><div class="icons iconForeColor"><img src="image/place.gif" height="100%" width="100%" title="字体颜色" onclick="javascript:_RichEditor.foreColor(event);" /></div></td>
							<td class="ico2"><div class="icons iconBackColor"><img src="image/place.gif" height="100%" width="100%" title="背景颜色" onclick="javascript:_RichEditor.backColor(event);" /></div></td>
							<td class="ico"><img height="20" src="image/line.gif" width="4"></td>
							<td class="ico2"><div class="icons iconInsertHyperlink"><img src="image/place.gif" height="100%" width="100%" title="插入超链接" onclick="javascript:_RichEditor.createHyperlink();" /></div></td>
							<td class="ico2"><div class="icons iconInsertImage"><img src="image/place.gif" height="100%" width="100%" title="插入图片" onclick="javascript:_RichEditor.createImage();" /></div></td>
							<td class="ico2"><div class="icons iconInsertTable"><img src="image/place.gif" height="100%" width="100%" title="插入表格" onclick="javascript:_RichEditor.createTable(event);" /></div></td>
							<td class="ico"><img height="20" src="image/line.gif" width="4"></td>
							<td><input id="editMode" type="checkbox" onclick="javascript:this.value=(this.value=='HTML'?'Source':'HTML');_RichEditor.switchEditMode(this.value);" value="HTML" />
							<label for="editMode" style="font-size:12px;font-family:'Arial Black';">HTML</label></td>
						</tr>
						</tbody>
					</table>
				</td>
			</tr>
			</tbody>
		</table>
<div id="htmlEditorPanel" style="border:1px solid #bbb;border-top:0px;"><table width="100%" height="<?php echo $height-34;?>" border="0" cellspacing="0" cellpadding="0"><tr><td style="border:0px;"><iframe class="HtmlEditor" id="HtmlEditor" name="HtmlEditor" style="height:<?php echo $height-34;?>px;width:100%" frameborder="0" marginHeight="0" marginwidth="0" src="about:blank"></iframe></td></tr></table></div>
<div id="sourceEditorPanel" style="border:1px solid #bbb;border-top:0px;display:none"><textarea id="sourceEditor" style="height:<?php echo $height-34;?>px;width:100%;background-color:#FDFCEE;border:0px;padding:0px 0px 0px 0px;"></textarea></div>
<script language="javascript">
<!--
var inputId = window.location.href.toQueryParams()['Id'];
var _RichEditor = new RichEditor({htmlEditorPanel:'htmlEditorPanel', editareaId:'HtmlEditor',
htmlHiddenVaueId:inputId,
sourceEditorPanel:'sourceEditorPanel', sourceEditorId:'sourceEditor'});
$$('.icons').each(function(icon){ _RichEditor.decorateIcon(icon);});

function getContent(){
	parent.document.getElementById(inputId).value = _RichEditor.getContent();
}

// 每隔一定时间更新内容到隐藏输入框
new PeriodicalExecuter( function(pe){
		parent.document.getElementById(inputId).value = _RichEditor.getContent();
	}.bind(this), 1);
-->
</script>
	</body>
</html>
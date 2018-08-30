//Ext.onReady(function() {
//	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
//	Ext.QuickTips.init();
//	var tree = new Ext.ux.tree.MyTree({
//		url : 'index1.php?model=stock_producttype_producttype&action=listByParent&parentId=',
//		rootId : Ext.get("stockId").getValue(),
//		rootText : '产品类型',
//		rootVisible : false,
//		listeners : {
//			click : function(node) {
//				Ext.Ajax.request({
//					url : 'index1.php?model=stock_producttype_producttype&action=getProTypeCodeById',// 调用后台对于controller的shenHeQuanBuTongGuoXueLiShuJv'方法
//					success : function(result, request) {
//						var json = result.responseText;
//						var o = eval("(" + json + ")");
//						// alert(o["proTypeCode"])
//						// document.getElementById("proTypeCode").value=o["proTypeCode"];
//						setElReadAttr(false);
//						changeFormDisplay(o["proTypeCode"]);
//					},
//					params : {// 参数列表
//						id : node.id
//					}
//				})
//
//			}
//
//		}
//
//	});
//	new Ext.ux.combox.ComboBoxTree({
//				applyTo : 'proType',
//				hiddenField : 'proTypeId',
//
//				tree : tree,
//				listeners : {
//					select : function(c, r, i) {
//					}
//
//				}
//			});
//});

/**
 * 改变表单信息的只读属性
 *
 * @param {}
 *            readAttr
 */
function setElReadAttr(readAttr) {
	// 在未选择类型前，产品信息其他输入框设置为只读
	$("#sequence").attr("readOnly", readAttr);
	$("#productName").attr("readOnly", readAttr);
	$("#versonNum").attr("readOnly", readAttr);
	$("#pattern").attr("readOnly", readAttr);
	$("#stoProNum").attr("readOnly", readAttr);
	$("#stockSafeNum").attr("readOnly", readAttr);
	$("#priCost").attr("readOnly", readAttr);
	$("#licenseType").attr("readOnly", readAttr);
	$("#remark").attr("readOnly", readAttr);

}
/**
 * 根据不同产品类型展现不同的表单界面
 *
 * @param {}
 *            wareType
 */
function changeFormDisplay(wareType) {
	var patternEL = document.getElementById("pattern");
	var patternTextEL = document.getElementById("patternText");
	var licenseTypeEL = document.getElementById("licenseType");
	var licenseTypeTextEL = document.getElementById("licenseTypeText");
	var configurationsEL = document.getElementById("configurations");

	if (wareType == 'software') {
		// var versonNumEL=document.getElementById("versonNum");
		patternEL.style.display = 'none';
		patternTextEL.style.display = 'none';

		licenseTypeEL.style.display = '';
		licenseTypeTextEL.style.display = '';

	} else {
		patternEL.style.display = '';
		patternTextEL.style.display = '';

		licenseTypeEL.style.display = 'none';
		licenseTypeTextEL.style.display = 'none';

	}

	if (wareType == 'hardware') {
		//addConfigurationsEL();
		 configurationsEL.style.display='';
	} else
	{
		//$("#configurations").html("*");
		 configurationsEL.style.display='none'
		//$("tr#configurations").apend("<td>dddd</td>");
		//$("#configurations").html('<tr id="configurations"></tr>');
	}
}

/**
 * 动态添加从表数据
 */
function add() {
	mycount = document.getElementById("coutNumb").value * 1 + 1;
	var itemtable=document.getElementById("itemtable");
	i = itemtable.rows.length;
	oTR = itemtable.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "28px";
	oTL1 = oTR.insertCell([0]);

	oTL1.innerHTML = '<input type="text" size="20" value="" name="inventory[configurations]['
			+ mycount + '][configName]" >';
	oTL2 = oTR.insertCell([1]);
	oTL2.innerHTML = '<input type="text" size="15" value="" name="inventory[configurations]['
			+ mycount + '][configPattern]" />';
	oTL3 = oTR.insertCell([2]);
	oTL3.innerHTML = '<input type="text" size="10" value="" name="inventory[configurations]['
			+ mycount
			+ '][configNum]" />';
	oTL4 = oTR.insertCell([3]);
	oTL4.innerHTML = '<input type="text" size="30" value="" name="inventory[configurations]['
			+ mycount + '][explains]" />';
	oTL5 = oTR.insertCell([4]);
	oTL5.innerHTML = '<img src="images/closeDiv.gif" onclick="mydel(this);" title="删除行">';
	document.getElementById("coutNumb").value = document
			.getElementById("coutNumb").value
			* 1 + 1;
}
function mydel(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		itemtable.deleteRow(rowNo);
	}
}

/**
 * 获取url参数的值
 * @return {}
 */
function getArgs() {
	// 加上substring的意义是去掉查询字符串中的？号。
	// var query = window.location.search.substring(1);

	// 定义一个数组，用于存放取出来的字符串参数。
	var argsArr = new Object();

	// 获取URL中的查询字符串参数
	var query = window.location.search;
	query = query.substring(1);

	// 这里的pairs是一个字符串数组
	var pairs = query.split("&");// name=myname&password=1234&sex=male&address=nanjing

	for (var i = 0; i < pairs.length; i++) {
		var sign = pairs[i].indexOf("=");
		// 如果没有找到=号，那么就跳过，跳到下一个字符串（下一个循环）。
		if (sign == -1) {
			continue;
		}

		var aKey = pairs[i].substring(0, sign);
		var aValue = pairs[i].substring(sign + 1);

		argsArr[aKey] = aValue;
	}

	return argsArr;
}

/**
 * 添加库存信息的从表信息的表单
 */
var addConfigurationsEL=function() {
	var configEl='<tr class="TableHeader" height="28">'
		 	+'<td colspan="4">'
			 	+'<table border="0" cellspacing="1" width="100%" class="small" bgcolor="" align="center" rules="none">'
					+'<tr class="TableHeader" height="20">'
		 				+'<td colspan="4" align="left" width="95%">'
		 				+'配置'
		 				+'</td>'
		 				+'<td align="right" width="5%">'
		 					+'<input type="hidden" id="coutNumb" name="coutNumb" value="1"/>'
		 					+'<img src="images/collapsed.gif" onclick="add()"  title="添加行">'
		 				+'</td>'
		 			+'</tr>'
		 		+'</table>'
				+'<table id="configtable" border="0" cellspacing="1" width="100%" class="small" bgcolor="" align="center" rules="none">'
					+'<tr align="center" class="TableHeader">'
						+'<td width="25%">'
							+'配置名称'
						+'</td>'
						+'<td width="20%">'
							+'型号'
						+'</td>'
						+'<td width="20%">'
							+'数量'
						+'</td>'
						+'<td width="30%">'
							+'说明'
						+'</td>'
						+'<td width="5%" height="28">'
							+'删除'
						+'</td>'
					+'</tr>'
					+'<tr align="center" class="TableData" height="28">'
						+'<td>'
							+'<input type="text" size="20" value="" name="configuration[configName]" />'
						+'</td>'
						+'<td>'
							+'<input type="text" size="15" value="" name="configuration[configPattern]" />'
						+'</td>'
						+'<td>'
							+'<input type="text" size="10" value="" name="configuration[configNum]" onkeypress="return event.keyCode>=48&&event.keyCode<=57" />'
						+'</td>'
						+'<td>'
							+'<input type="text" size="30" value="" name="configuration[explains]" />'
						+'</td>'
						+'<td nowrap class="TableData" align="center" width="5%" />'
				 			+'<img src="images/closeDiv.gif" onclick="mydel(this);" title="删除行" />'
				 		+'</td>'
					+'</tr>'
				+'</table>'
			+'</td>'
		 +'</tr>';
	$("#configurations")
			.html(configEl);
}
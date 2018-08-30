//Ext.onReady(function() {
//	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
//	Ext.QuickTips.init();
//	var tree = new Ext.ux.tree.MyTree({
//		url : 'index1.php?model=stock_producttype_producttype&action=listByParent&parentId=',
//		rootId : Ext.get("stockId").getValue(),
//		rootText : '��Ʒ����',
//		rootVisible : false,
//		listeners : {
//			click : function(node) {
//				Ext.Ajax.request({
//					url : 'index1.php?model=stock_producttype_producttype&action=getProTypeCodeById',// ���ú�̨����controller��shenHeQuanBuTongGuoXueLiShuJv'����
//					success : function(result, request) {
//						var json = result.responseText;
//						var o = eval("(" + json + ")");
//						// alert(o["proTypeCode"])
//						// document.getElementById("proTypeCode").value=o["proTypeCode"];
//						setElReadAttr(false);
//						changeFormDisplay(o["proTypeCode"]);
//					},
//					params : {// �����б�
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
 * �ı����Ϣ��ֻ������
 *
 * @param {}
 *            readAttr
 */
function setElReadAttr(readAttr) {
	// ��δѡ������ǰ����Ʒ��Ϣ�������������Ϊֻ��
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
 * ���ݲ�ͬ��Ʒ����չ�ֲ�ͬ�ı�����
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
 * ��̬��Ӵӱ�����
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
	oTL5.innerHTML = '<img src="images/closeDiv.gif" onclick="mydel(this);" title="ɾ����">';
	document.getElementById("coutNumb").value = document
			.getElementById("coutNumb").value
			* 1 + 1;
}
function mydel(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		itemtable.deleteRow(rowNo);
	}
}

/**
 * ��ȡurl������ֵ
 * @return {}
 */
function getArgs() {
	// ����substring��������ȥ����ѯ�ַ����еģ��š�
	// var query = window.location.search.substring(1);

	// ����һ�����飬���ڴ��ȡ�������ַ���������
	var argsArr = new Object();

	// ��ȡURL�еĲ�ѯ�ַ�������
	var query = window.location.search;
	query = query.substring(1);

	// �����pairs��һ���ַ�������
	var pairs = query.split("&");// name=myname&password=1234&sex=male&address=nanjing

	for (var i = 0; i < pairs.length; i++) {
		var sign = pairs[i].indexOf("=");
		// ���û���ҵ�=�ţ���ô��������������һ���ַ�������һ��ѭ������
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
 * ��ӿ����Ϣ�Ĵӱ���Ϣ�ı�
 */
var addConfigurationsEL=function() {
	var configEl='<tr class="TableHeader" height="28">'
		 	+'<td colspan="4">'
			 	+'<table border="0" cellspacing="1" width="100%" class="small" bgcolor="" align="center" rules="none">'
					+'<tr class="TableHeader" height="20">'
		 				+'<td colspan="4" align="left" width="95%">'
		 				+'����'
		 				+'</td>'
		 				+'<td align="right" width="5%">'
		 					+'<input type="hidden" id="coutNumb" name="coutNumb" value="1"/>'
		 					+'<img src="images/collapsed.gif" onclick="add()"  title="�����">'
		 				+'</td>'
		 			+'</tr>'
		 		+'</table>'
				+'<table id="configtable" border="0" cellspacing="1" width="100%" class="small" bgcolor="" align="center" rules="none">'
					+'<tr align="center" class="TableHeader">'
						+'<td width="25%">'
							+'��������'
						+'</td>'
						+'<td width="20%">'
							+'�ͺ�'
						+'</td>'
						+'<td width="20%">'
							+'����'
						+'</td>'
						+'<td width="30%">'
							+'˵��'
						+'</td>'
						+'<td width="5%" height="28">'
							+'ɾ��'
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
				 			+'<img src="images/closeDiv.gif" onclick="mydel(this);" title="ɾ����" />'
				 		+'</td>'
					+'</tr>'
				+'</table>'
			+'</td>'
		 +'</tr>';
	$("#configurations")
			.html(configEl);
}
/**
 *
 * ����ȷ�Ϲ���js(�������༭��������鿴)
 *
 * @type
 */
var equConfig = {
	type: ''// add edit change view
};// ��������

// �����Ϣ

/**
 * ת�ɱ����ɫ����
 */
var tranToChangeShow = function (oldVal, newVal) {
	var newHtml = "<font color='red'><span class='oldValue' style='display:none'>"
		+ oldVal
		+ "</span><span class='compare' style='display:none'>=></span><span class='newValue'>"
		+ newVal + "</span></font>";
	return newHtml;
}

/**
 * ���ɱ༭�����ϱ����ʶͼ��
 */
var addGridChangeShow = function (g, rowNum, colName, rowData, tr) {
	if (equConfig.type == "view") {
		var $cmp = g.getCmpByRowAndCol(rowNum, colName);
		if (rowData.changeTips == '1') {
			// tr.css("background-color", "#F8846B");
			$cmp.prepend('<img title="����༭�Ĳ�Ʒ" src="images/changeedit.gif" />');
		} else if (rowData.changeTips == '2') {
			$cmp.prepend('<img title="��������Ĳ�Ʒ" src="images/new.gif" />');
		} else if (rowData.changeTips == '3' && rowData.isDel == '1') {
			$cmp.prepend('<img title="���ɾ���Ĳ�Ʒ" src="images/changedelete.gif" />');
			tr.css("color", "#8B9CA4");
		} else if (rowData.changeTips == '0' && rowData.isDel == '1') {
			tr.css("color", "#8B9CA4");
		}
	}
}

/**
 * ��������ϸ
 */
var beforeAddRow = function (e, rowNum, rowData, g, tr) {
	if (equConfig.type == "view") {
		var key = rowData.id;
		if ($("#isTemp").val() == 1) {
			key = rowData.originalId;
		}
		var detail = changeDetailObj["d" + key];
		if (detail) {
			for (var i = 0; i < detail.length; i++) {
				var changeField = detail[i].changeField;
				rowData[changeField] = tranToChangeShow(detail[i].oldValue,
					rowData[detail[i].changeField]);
			}
		}
	}
}

// �ص������Ʒ��Ϣ �� ����
function getCacheInfo(cacheId, rowNum) {
	$.ajax({
		type: "POST",
		url: "?model=goods_goods_goodscache&action=getCacheConfig",
		data: {
			"id": cacheId
		},
		async: false,
		success: function (data) {
			if (data != "") {
				$("#productInfo table tr[rowNum=" + rowNum + "]").after(data);
			}
		}
	});
}

// ����ҳ��ʱ��Ⱦ��Ʒ������Ϣ
function initCacheInfo() {
	// ���������
	var thisGrid = $("#productInfo");

	var colObj = thisGrid.yxeditgrid("getCmpByCol", "deploy");
	colObj.each(function (i, n) {
		getCacheInfo(this.value, i);
	});
}

/**
 * ����ĳ����Ʒ�����������Ϣ
 *
 * @param {}
 *            goodsId ��Ʒ
 * @param {}
 *            goodsRowNum
 */
function initProductConfigs(goodsId, goodsRowNum) {
	// ���������
	var $productGrid = $("#productInfo" + goodsRowNum);
	// thisGrid.html("");
	var equIdObj = $productGrid.yxeditgrid("getCmpByCol", "id");// �嵥id
	var productObj = $productGrid.yxeditgrid("getCmpByCol", "productId");// ����id
	var numObj = $productGrid.yxeditgrid("getCmpByCol", "number");// ��������
	var equSize = equIdObj.size();
	if(productObj.length == 0){
		var productId = goodsId;
		if (equSize > 0) {
			for(var i = 0;i < equSize;i++){
				getProductConfig(goodsId, productId, 0, goodsRowNum,
					i, equIdObj[i].value);
			}
		}
	}else{
		productObj.each(function (i, n) {
			var productId = this.value;
			if (equSize > 0) {
				getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
					i, equIdObj[i].value);
			} else {
				getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
					i);
			}
		});
	}
	if($("#isForSales").val() != undefined && $("#isForSales").val() == 1){
		$("input").each(function(){
			if(!$(this).hasClass("readOnlyTxtItem")){
				$(this).attr("class","readOnlyTxtItem");
				$(this).attr("readonly",true);
			}
		})
	}
}

// ��������
function numberChk(elmObj,number) {
    var pass = true;
    if(isNaN(number) || number.indexOf(".")!=-1){
        pass = false;
    }else if(Number(number) <= 0){
		pass = false;
	}

    if(pass){
        return true;
    }else{
        alert("�������������0������!");
        elmObj.val(1);
        return false;
    }
}

function countAmount(id) {
	var price = $('#inner_price' + id).val();
	var number = $('#inner_number' + id).val();
	if (number && numberChk($('#inner_number' + id),number)) {
        $('#inner_money' + id).val(number * price);
	}

	curPageEquEstimate();
}
/**
 * ����ĳ�����ϵ����(��������ȷ��)
 *
 * @param {}
 *            goodsId ��Ʒid
 * @param {}
 *            productId ����id
 * @param {}
 *            productNum ��������
 * @param {}
 *            goodsRowNum ��Ʒ��
 * @param {}
 *            productRowNum ������
 *
 */
function getProductConfig(goodsId, productId, productNum, goodsRowNum,
	productRowNum, equId, isGetPro) {
	var url = "";
	var param = {};
	var linkId = $("#linkId").val();
	// �ж��Ǳ༭�����������
	if (!isGetPro) {
		param.parentEquId = equId;
		if (equConfig.type != "view" || $("#isShowDel").val() == "false") {
			param.isDel = 0;
		}else if($("#originalId").val() > 0){
			param.temp = 1;
		}
		url = "?model=projectmanagent_borrow_borrowequ&action=listJson&linkId="+linkId;
	} else {
		param.productId = productId;
		url = "?model=stock_productinfo_configuration&action=getAccessForPro";
	}
	if ($('#changeView').val() == '1') {
		param.temp = 1;
	} else {
		if($("#borrowOldId").val() > 0 && $("#borrowOldId").val() != $("#borrowId").val()){
			param.temp = 1;
		}else{
			param.isTemp = 0;
		}
	}
	var data = $.ajax({
		type: "POST",
		url: url,
		data: param,
		async: false
	}).responseText;

	$("#goodsDetail_" + goodsId + "_" + productRowNum).remove();
	// productIdArr[rowNum]=productId;
	// tr.empty();
	var obj = eval("(" + data + ")");
	if (obj.length) {
		var $table = $("<table class='form_in_table' id='contractequ_$id'></table>");
		var $td = $("<td class='innerTd' colspan='20'></td>");
		var $tr = $("<tr id='goodsDetail_" + goodsId + "_" + productRowNum
			+ "'></tr>")
		$tr.append($td)
		$td.append($table);

		// var $th = $('<tr class="main_tr_header1"><th width="30px"><img src="images/add_item.png" onclick="addConfig(' + goodsId + ',' + productRowNum + ',' + goodsRowNum + ',' + productId + ')" title="�����"></th><th width="35px">���</th><th>�������</th>'
		// 	+ '<th>�������</th><th>�汾�ͺ�</th><th>����</th><th>������</th></tr>');
		var $th = ($("#isForSales").val() == undefined || $("#isForSales").val() != 1)? $('<tr class="main_tr_header1"><th width="30px"></th><th width="35px">���</th><th>�������</th>'
			+ '<th>�������</th><th>�汾�ͺ�</th><th>����</th><th>������</th></tr>') : $('<tr class="main_tr_header1"><th width="35px">���</th><th>�������</th>'
			+ '<th>�������</th><th>�汾�ͺ�</th><th>����</th><th>������</th></tr>');
		$table.append($th);
		var configtr = $("#productInfo" + goodsRowNum + " table tr[rowNum="
			+ productRowNum + "]");
		configtr.after($tr);
		var nametr = 'borrow[detail][' + goodsRowNum + ']';
		$tr.attr("name", nametr);
		for (var i = 0; i < obj.length; i++) {
			var index = i + (productRowNum + 1) * 100;
			var name = 'borrow[detail][' + goodsRowNum + '][' + index + ']';
			var p = obj[i];
			$ctr = $("<tr  class='tr_inner'></tr>");
			var imgStr = "";
			if (equConfig.type == 'view') {
				var key = p.id;
				if ($("#isTemp").val() == 1) {
					key = p.originalId;
				}
				var detail = changeDetailObj["d" + key];
				if (detail) {
					for (var ii = 0; ii < detail.length; ii++) {
						var changeField = detail[i].changeField;
						p[changeField] = tranToChangeShow(detail[ii].oldValue,
							p[detail[ii].changeField]);
					}
				}

				if (p.changeTips == '1') {
					imgStr = '<img title="���±���༭������" src="images/changeedit.gif" />';
				} else if (p.changeTips == '2') {

					imgStr = '<img title="���±������������" src="images/new.gif" />';
				} else if (p.changeTips == '3' && p.isDel == '1') {
					imgStr = '<img title="���±��ɾ��������" src="images/changedelete.gif" />';
					$ctr.css("color", "#8B9CA4");
				} else if (p.changeTips == '0' && p.isDel == '1') {
					$ctr.css("color", "#8B9CA4");
				}
			}
			$ctr.attr("index", index);
			if (equConfig.type != 'view') {
				if($("#isForSales").val() == undefined || $("#isForSales").val() != 1){
					$ctr.append("<td name='"
						+ name
						+ "'><img src='images/removeline.png' onclick='delConfig(this," + p.executedNum + "," + p.issuedShipNum + ")' title='ɾ����'></td>");
				}
			} else {
				$ctr.append("<td></td>");
			}
			$ctr.append("<td>" + (i + 1) + "</td>");
			if (!p.productNo) {
				p.productNo = p.productCode;
			}
			var num = 0;
			if (p.configNum) {
				num = productNum * p.configNum;
			} else if (p.number) {
				num = p.number;
			}
			var cnum = num;
			if (!p.priCost && p.priCost != 0 && p.priCost != '') {
				var priCost = p.price;
			} else if (p.priCost == '') {
				var priCost = 0
			} else {
				var priCost = p.priCost;
			}

			var configId = (p.id && p.id != undefined)? p.id : '';
			var hide = '<input type="hidden" name="' + name
				+ '[parentRowNum]" value="'
				+ (productId + "_" + productRowNum)
				+ '"/><input type="hidden" name="' + name
				+ '[productId]" value="' + p.productId
				+ '"/><input type="hidden" name="' + name
				+ '[id]" value="' + configId
				+ '"/><input type="hidden" name="' + name
				+ '[productNo]"  value="' + p.productNo
				+ '"/><input type="hidden" name="' + name
				+ '[productName]"  value="' + p.productName
				+ '"/><input type="hidden" name="' + name
				+ '[conProductId]"  value="' + goodsId
				+ '"/><input type="hidden" name="' + name
				+ '[unitName]"  value="' + p.unitName
				+ '"/><input type="hidden" name="' + name
				+ '[price]"  value="' + priCost
				+ '" id="inner_price'
				+ (productId + "_" + productRowNum + "_" + index)
				+ '"/><input type="hidden" name="' + name
				+ '[money]"  value="' + priCost * num
				+ '" id="inner_money'
				+ (productId + "_" + productRowNum + "_" + index) + '"/>';
			if (equConfig.type != 'add') {
				hide += '<input type="hidden" name="' + name + '[id]" value="'
					+ (p.id ? p.id : "") + '"/>';
			}

			var td = $("<td>" + p.productNo + "</td>");
			td.append(hide);
			$ctr.append(td);
			$ctr.append("<td>" + imgStr + p.productName + "</td>");
			var pattern = (typeof (p.pattern) != 'undefined'
				? p.pattern
				: p.productModel);
			if (equConfig.type != "view") {
				pattern = $("<input class='txtshort' type='text' name='" + name
					+ "[productModel]' value='" + pattern + "'/>");
			}
			var $td = $("<td></td>");
			$td.append(pattern);
			$ctr.append($td);
			if (equConfig.type != "view") {
				cnum = $('<input type="text" class="txtshort"  name="' + name
					+ '[number]" value="' + num
					+ '" onblur="countAmount(' + "'" + productId + "_" + productRowNum + "_" + index + "'" + ')" id="inner_number'
					+ (productId + "_" + productRowNum + "_" + index) + '"/>');
			}
			var $td = $("<td></td>");
			$td.append(cnum);
			$ctr.append($td);
			var cperiod = p.arrivalPeriod;
			if (equConfig.type != "view") {
				cperiod = $('<input type="text" class="txtshort"  name="'
					+ name + '[arrivalPeriod]" value="' + p.arrivalPeriod
					+ '" onblur="countStander()" id="inner_arrivalPeriod'
					+ (productId + "_" + productRowNum + "_" + index) + '"/>');
			}
			var $td = $("<td></td>");
			$td.append(cperiod);
			$ctr.append($td);
			$table.append($ctr);
			countStander();
		}
	};
}

//�������
function addConfig(goodsId, productRowNum, goodsRowNum, productId) {
	var tr = $("#goodsDetail_" + goodsId + "_" + productRowNum);
	var index = accAdd(tr.find("tr:last").attr("index"), 1);
	var name = 'borrow[detail][' + goodsRowNum + '][' + index + ']';
	var mycount = tr.find("tr").length;
	var itemtable = document.getElementById("contractequ_$id");
	i = itemtable.rows.length;
	oTR = itemtable.insertRow([i]);
	oTR.className = "tr_inner";
	oTR.index = index;
	oTR.height = "28px";
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png" onclick="delConfig(this,0,0);" title="ɾ����">';
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = mycount;
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = '<input type="text" name="' + name
		+ '[productNo]" class="txtshort" />'
		+ '<input type="hidden" name="' + name
		+ '[parentRowNum]" value="' + productId + '_' + productRowNum + '"/>'
		+ '<input type="hidden" name="' + name
		+ '[productId]" />'
		+ '<input type="hidden" name="' + name
		+ '[conProductId]" value="' + goodsId + '"/>'
		+ '<input type="hidden" name="' + name
		+ '[unitName]" />';
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = '<input type="text" name="' + name
		+ '[productName]" class="txtshort" />';
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<input type="text" name="' + name
		+ '[productModel]" class="txtshort" />';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" name="' + name
		+ '[number]" class="txtshort" onblur="countAmount(' + "'" + productId + "_" + productRowNum + "_" + index + "'" + ');" id="inner_number' + (productId + "_" + productRowNum + "_" + index) + '"/>';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = '<input type="text" name="' + name
		+ '[arrivalPeriod]" onblur="countStander()" class="txtshort" id="inner_arrivalPeriod' + (productId + "_" + productRowNum + "_" + index) + '"/>';
	//��������tr��ɾ����ťtd����name��ɾ����ʱ����õ�
	tr.find("tr:last").find("td:first").attr("name", name);
	reloadConfigProduct(goodsId, productRowNum, name);
}


/**
 * ��Ⱦ���������Ϣcombogrid
 */
function reloadConfigProduct(goodsId, productRowNum, name) {
	$("input[name='" + name + "[productNo]']").yxcombogrid_product("remove").yxcombogrid_product({// �����ϱ��
		nameCol: 'productNo',
		event: {
			'clear': function () {
				$("input[name='" + name + "[productId]']").val('');
				$("input[name='" + name + "[productName]']").val('');
				$("input[name='" + name + "[unitName]']").val('');
				$("input[name='" + name + "[productModel]']").val('');
				$("input[name='" + name + "[number]']").val('');
				$("input[name='" + name + "[arrivalPeriod]']").val('');
			}
		},
		gridOptions: {
			showcheckbox: false,
			event: {
				'row_dblclick': function (i) {
					return function (e, row, data) {
						$("input[name='" + name + "[productId]']").val(data.id);
						$("input[name='" + name + "[productName]']").val(data.productName);
						$("input[name='" + name + "[productNo]']").val(data.productCode);
						$("input[name='" + name + "[unitName]']").val(data.unitName);
						$("input[name='" + name + "[productModel]']").val(data.pattern);
						$("input[name='" + name + "[number]']").val('1');
						$("input[name='" + name + "[arrivalPeriod]']").val(data.arrivalPeriod);
					}
				}(i)
			}
		}
	});
	$("input[name='" + name + "[productName]']").yxcombogrid_product("remove").yxcombogrid_product({// ����������
		nameCol: 'productName',
		event: {
			'clear': function () {
				$("input[name='" + name + "[productId]']").val('');
				$("input[name='" + name + "[productNo]']").val('');
				$("input[name='" + name + "[unitName]']").val('');
				$("input[name='" + name + "[productModel]']").val('');
				$("input[name='" + name + "[number]']").val('');
				$("input[name='" + name + "[arrivalPeriod]']").val('');
			}
		},
		gridOptions: {
			showcheckbox: false,
			event: {
				'row_dblclick': function (i) {
					return function (e, row, data) {
						$("input[name='" + name + "[productId]']").val(data.id);
						$("input[name='" + name + "[productNo]']").val(data.productCode);
						$("input[name='" + name + "[unitName]']").val(data.unitName);
						$("input[name='" + name + "[productModel]']").val(data.pattern);
						$("input[name='" + name + "[number]']").val('1');
						$("input[name='" + name + "[arrivalPeriod]']").val(data.arrivalPeriod);
					}
				}(i)
			}
		}
	});
}

// ���㷽��
function countAll(rowNum) {
	var beforeStr = "productInfo_cmp_";
	if ($("#" + beforeStr + "number" + rowNum).val() == ""
		|| $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
		return false;
	} else {
		// ��ȡ��ǰ��
		thisNumber = $("#" + beforeStr + "number" + rowNum).val();
		// alert(thisNumber)

		// ��ȡ��ǰ����
		thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
		// alert(thisPrice)

		// ���㱾�н�� - ����˰
		thisMoney = accMul(thisNumber, thisPrice, 2);
		setMoney(beforeStr + "money" + rowNum, thisMoney);
	}
}

// ������
function hideList(listId) {
	var temp = document.getElementById(listId);
	var tempH = document.getElementById(listId + ".H");
	if (temp.style.display == '') {
		temp.style.display = "none";
		tempH.style.display = "";
	} else if (temp.style.display == "none") {
		temp.style.display = '';
		tempH.style.display = 'none';
	}
}
/** *****************���ؼƻ�******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}

// ֱ���ύ����
function toAudit() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_borrow_borrowequ&action=equEdit&act=audit";
}

function countStander() {
	var cmp_arrivalPeriod = new Array();
	var inner_arrivalPeriod = new Array();
	var cmp_flag = false;
	var inner_flag = false;
	// ���ϵĽ�����
	$('input[id*=cmp_arrivalPeriod]').each(function (i) {
		if (checkNum(this)) {
			if ($(this).parent().parent().children("input").val() != 1) {
				cmp_arrivalPeriod[i] = $(this).val() * 1
				cmp_flag = true;
			} else {
				cmp_arrivalPeriod[i] = 0
			}
		} else {
			alert('������ֻ��Ϊ��������');
			return false;
		}
	});
	// �������õĽ�����
	$('input[id*=inner_arrivalPeriod]').each(function (i) {
		if (checkNum(this)) {
			if ($(this).parent().parent().children("input").val() != 1) {
				inner_arrivalPeriod[i] = $(this).val() * 1
				inner_flag = true;
			} else {
				inner_arrivalPeriod[i] = 0
			}
		} else {
			alert('������ֻ��Ϊ��������');
			return false;
		}
	});
	var maxArrival = 0
	if (cmp_flag && inner_flag) {// �����Ϻ������������棬ѡ��󽻻���
		maxArrival = Math.max(Math.max.apply(Math, cmp_arrivalPeriod), Math.max
			.apply(Math, inner_arrivalPeriod));
	} else if (cmp_flag) {// �������ѡ��󽻻���
		maxArrival = Math.max.apply(Math, cmp_arrivalPeriod);
	}
	// �����׼�����ڣ���ǰ����+������󽻻���
	if ($('#standardDate_v').val()) {
		newDate = stringToDate($('#standardDate_v').val(), maxArrival)
	} else {
		newDate = stringToDate(formatDate(new Date()), maxArrival)
	}
	//	alert(formatDate(newDate))
	$('#standardDate').val(formatDate(newDate))

}

function countStanderAfterRemove(arrivalPeriod) {
	var cmp_arrivalPeriod = new Array();
	var inner_arrivalPeriod = new Array();
	var cmp_flag = false;
	var inner_flag = false;
	// ���ϵĽ�����
	$('input[id*=cmp_arrivalPeriod]').each(function (i) {
		if (checkNum(this)) {
			if ($(this).parent().parent().children("input").val() != 1) {
				cmp_arrivalPeriod[i] = $(this).val() * 1
				cmp_flag = true;
			} else {
				cmp_arrivalPeriod[i] = 0
			}
		} else {
			alert('������ֻ��Ϊ��������');
			return false;
		}
	});
	//	alert(cmp_arrivalPeriod.length);
	if (cmp_arrivalPeriod.length > 0) {
		for (var i = 0; i < cmp_arrivalPeriod.length; i++) {
			if (arrivalPeriod == cmp_arrivalPeriod[i]) {
				cmp_arrivalPeriod.splice(i, 1)
				break;
			}
		}
	}
	// �������õĽ�����
	$('input[id*=inner_arrivalPeriod]').each(function (i) {
		if (checkNum(this)) {
			if ($(this).parent().parent().children("input").val() != 1) {
				inner_arrivalPeriod[i] = $(this).val() * 1
				inner_flag = true;
			} else {
				inner_arrivalPeriod[i] = 0
			}
		} else {
			alert('������ֻ��Ϊ��������');
			return false;
		}
	});
	var maxArrival = 0
	if (cmp_flag && inner_flag) {// �����Ϻ������������棬ѡ��󽻻���
		maxArrival = Math.max(Math.max.apply(Math, cmp_arrivalPeriod), Math.max
			.apply(Math, inner_arrivalPeriod));
	} else if (cmp_flag) {// �������ѡ��󽻻���
		maxArrival = Math.max.apply(Math, cmp_arrivalPeriod);
	}
	// �����׼�����ڣ���ǰ����+������󽻻���
	if ($('#standardDate_v').val()) {
		newDate = stringToDate($('#standardDate_v').val(), maxArrival)
	} else {
		newDate = stringToDate(formatDate(new Date()), maxArrival)
	}
	//	alert(formatDate(newDate))
	$('#standardDate').val(formatDate(newDate))
}

/** ********************ɾ�����************************ */
function delConfig(obj, executedNum, issuedShipNum) {
	if (parseInt(executedNum) > 0) {//������ѳ��ⲻ��ɾ
		alert("�������Ѳ��ֳ��⣬��ֱֹ��ɾ���������˻����̣�");
	} else if (parseInt(issuedShipNum) > 0) {//���û���ѳ���,�����з����ƻ���Ҳ����ɾ
		alert("���������´﷢���ƻ�����ֱֹ��ɾ����");
	} else {
		if (confirm('ȷ��Ҫɾ�����У�')) {
			var $td = $(obj).parent();
			var name = $td.attr("name");
			var $tr = $td.parent();
			if (equConfig.type == "change") {// ����Ǳ��
				$tr.append("<input type='hidden' name='" + name
					+ "[isDelTag]' value='1'>");
				$tr.hide();
			} else {
				$tr.remove();
			}
			var rowNum = $tr.parent().children(":visible").size();
			if (rowNum == 1) {
				$tr.parent().hide();
			}
			countStander();
			setTimeout(function(){
				curPageEquEstimate();
			},100);
		}
	}
}

/**
 * ���ݲ�Ʒ��Ϣ����������Ϣ
 */
function getGoodsProducts(goodsRowNum, url) {
	var docType = $('#docType').val();
	var selectAssetFn = function (g, rowNum, rowData) {
		var $productName = g.getCmpByRowAndCol(rowNum, 'productName');// ��������
		$productName.val(rowData.productName);
		var $arrivalPeriod = g.getCmpByRowAndCol(rowNum, 'arrivalPeriod');// ���Ͻ�����
		$arrivalPeriod.val(rowData.arrivalPeriod);
		var $pattern = g.getCmpByRowAndCol(rowNum, 'productModel');// ���Ϲ���ͺ�
		$pattern.val(rowData.pattern);
		var $productNo = g.getCmpByRowAndCol(rowNum, 'productNo');// ���ϱ���
		$productNo.val(rowData.productCode);
		var $productId = g.getCmpByRowAndCol(rowNum, 'productId');// ����id
		$productId.val(rowData.id);
		var num = getInventoryInfos(docType, rowData.id)
		var $exeNum = g.getCmpByRowAndCol(rowNum, 'exeNum');// ����id
		$exeNum.html(num);
		var $priCost = g.getCmpByRowAndCol(rowNum, 'price');// ���ϵ���
		$priCost.val(rowData.priCost);
		var $money = g.getCmpByRowAndCol(rowNum, 'money');// ���ϵ���
		$money.val(rowData.priCost * 1);
		var $warrantyPeriod = g.getCmpByRowAndCol(rowNum, 'warrantyPeriod');// ������
		$warrantyPeriod.val(rowData.warranty);
	};

	var goodsId = $("#conProductId" + goodsRowNum).val();
	var linkId = $("#linkId").val();
	var number = $("#number" + goodsRowNum).val();
	var changeView = $("#changeView").val();
	var param = {
		conProductId: goodsId,
		parentEquId: 0,
		// isTemp: ($("#borrowOldId").val() > 0 && $("#borrowOldId").val() != $("#borrowId").val())? 1 : 0
		isDel : 0
	};

	if($("#borrowOldId").val() > 0 && $("#borrowOldId").val() != $("#borrowId").val()){
		param.temp = 1;
	}else{
		param.isTemp = 0;
	}

	//	if (linkId) {
	//		param.linkId = linkId;
	//	}
	if ($('#borrowId').val() != undefined) {
		param.borrowId = $('#borrowId').val();
	}
	if (changeView) {
		param.isTemp = 1;
		delete param.isTemp;
	}
	//	if (url) {
	//		param.number = number;
	//	}
	if (equConfig.type == "view" && $("#isShowDel").val() == "true") {
		param.temp = 1;
		delete param.isDel;
	}

	if($("#originalId").val() <= 0){
		delete param.temp;
	}

	var isForSales = ($("#isForSales").val() != undefined && $("#isForSales").val() == 1)? 1 : '';
	var needSalesConfirm = ($("#needSalesConfirm").val() != undefined)? $("#needSalesConfirm").val()  : '';
	var salesConfirmId = ($("#salesConfirmId").val() != undefined)? $("#salesConfirmId").val()  : '';
	if(isForSales == 1 && needSalesConfirm == 3 && salesConfirmId > 0){
		param.isTemp = 1;
		param.linkId = salesConfirmId;
	}

	var isAddAndDel = ($("#isForSales").val() != undefined && $("#isForSales").val() == 1)? false : true;
	var equColConfig = ($("#isForSales").val() != undefined && $("#isForSales").val() == 1)?
		[
		{
			display: 'isDel',
			name: 'isDel',
			type: 'hidden'
		}, {
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '���ϱ��',
			name: 'productNo',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '��������',
			name: 'productName',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '�ͺ�/�汾',
			name: 'productModel',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '������',
			name: 'warrantyPeriod',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '��λ',
			name: 'unitName',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '����',
			name: 'number',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '���ϵ���',
			name: 'price',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '���Ͻ��',
			name: 'money',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		},{
			display: '������',
			name: 'arrivalPeriod',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}] : [
		{
			display: 'isDel',
			name: 'isDel',
			type: 'hidden'
		}, {
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '���ϱ��',
			name: 'productNo',
			tclass: 'txtmiddle',
			validation: {
				required: true
			},
			readonly: true,
			process: function ($input, rowData) {
				if ((equConfig.type != "change" && equConfig.type != "view")
					|| !rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_product({
						isDown: true,
						//						closeCheck : true,
						closeAndStockCheck: true,
						hiddenId: 'productInfo' + goodsRowNum + '_cmp_product'
							+ rowNum,
						height: 250,
						event: {
							'clear': function () {
								g.clearRowValue(rowNum);
							}
						},
						gridOptions: {
							showcheckbox: false,
							isTitle: true,
							event: {
								"row_dblclick": (function (rowNum, rowData) {
									return function (e, row, productData) {
										selectAssetFn(g, rowNum, productData);
										if (confirm("�Ƿ�������������")) {
											var productNum = rowData
												? rowData.number
												: 1;
											var conProId = rowData
												? rowData.productId
												: 0;
											getProductConfig(goodsId,
												productData.id, productNum,
												goodsRowNum, rowNum,
												conProId, true);
										}
										countStander();
										curPageEquEstimate();
									}
								})(rowNum, rowData)
							}
						}
					});
				} else if (equConfig.type != "view") {
					$input.attr("readonly", true);
				}
				return $input;
			}
		}, {
			display: '��������',
			name: 'productName',
			tclass: 'txt',
			readonly: true,
			validation: {
				required: true
			},
			process: function ($input, rowData) {
				// ���ҳ�治�ܸ�������
				if ((equConfig.type != "change" && equConfig.type != "view")
					|| !rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_product({
						isDown: true,
						//						closeCheck : true,
						closeAndStockCheck: true,
						hiddenId: 'productInfo_cmp_productId' + rowNum,
						nameCol: 'productName',
						height: 250,
						event: {
							'clear': function () {
								g.clearRowValue(rowNum);
							}
						},
						gridOptions: {
							showcheckbox: false,
							isTitle: true,
							event: {
								"row_dblclick": (function (rowNum, rowData) {
									return function (e, row, productData) {
										selectAssetFn(g, rowNum, productData);
										if (confirm("�Ƿ�������������")) {
											var productNum = rowData
												? rowData.number
												: 1;
											var conProId = rowData
												? rowData.productId
												: 0;
											getProductConfig(goodsId,
												productData.id, productNum,
												goodsRowNum, rowNum,
												conProId, true);
										}
										countStander();
										setTimeout(function(){
											curPageEquEstimate();
										},100);
									}
								})(rowNum, rowData)
							}
						}
					});
				} else if (equConfig.type != "view") {
					$input.attr("readonly", true);
				}
				return $input;
			}
		}, {
			display: '����Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '���ϵ���',
			name: 'price'
			, type: 'hidden'
		}, {
			display: '���Ͻ��',
			name: 'money'
			, type: 'hidden'
		}, {
			display: '��ƷId',
			name: 'conProductId',
			staticVal: goodsId,
			type: 'hidden'
		}, {
			display: '�ͺ�/�汾',
			name: 'productModel',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '������',
			name: 'warrantyPeriod',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '��λ',
			name: 'unitName',
			type: 'hidden'
		}, {
			display: '����',
			name: 'number',
			tclass: 'txtshort',
			value: 1,
			event: {
				blur: function () {
					var rowNum = $(this).data("rowNum");
					var issuedShipNum = $("#productInfo" + goodsRowNum + "_cmp_issuedShipNum" + rowNum).val();
					if($(this).val() < issuedShipNum){
						alert("��������С�����´﷢���ƻ�����!");
						$(this).val(issuedShipNum);
					}else if(numberChk($(this),$(this).val())){
						var price = $("#productInfo" + goodsRowNum + "_cmp_price" + rowNum).val();
						$("#productInfo" + goodsRowNum + "_cmp_money" + rowNum).val(price * $(this).val())
						curPageEquEstimate();
					}
				}
			}
		}, {
			display: '�������',
			name: 'exeNum',
			//			tclass : 'txtshort',
			type: 'statictext'
		}, {
			display: '������',
			name: 'arrivalPeriod',
			tclass: 'txtshort',
			event: {
				blur: function () {
					//					var rowNum = $(this).data("rowNum");
					//					// ���㽻����
					//					var conProductId = $("#productInfo" + goodsRowNum
					//							+ "_cmp_arrivalPeriod" + rowNum).val();
					//					var newDate = stringToDate($('#standardDate').val(),
					//							conProductId * 1)
					//					$('#standardDate').val(formatDate(newDate))
					countStander();
				}
			}
		}, {
			display: '���ϼ�������Id',
			name: 'license',
			type: 'hidden'
		}, {
			display: '��Ʒ��������Id',
			name: 'parentLicenseId',
			type: 'hidden'
		}, {
			name: 'licenseButton',
			display: '��������',
			type: 'statictext',
			process: function (html, rowData, $tr, g) {
				if (equConfig.type == "view" && rowData.license == '') {
					return '��';
				} else {
					return html;
				}
			},
			event: {
				'click': function (e) {
					var rowNum = $(this).data("rowNum");
					// ��ȡ��Ʒ��������id
					var productLicense = $("#productInfo" + goodsRowNum
						+ "_cmp_parentLicenseId" + rowNum).val();

					// ��ȡ���ϼ�������id
					var thisLicense = $("#productInfo" + goodsRowNum
						+ "_cmp_license" + rowNum).val();
					if (equConfig.type != 'view' && (!thisLicense || thisLicense == productLicense)) {
						url = "?model=yxlicense_license_tempKey&action=toSelectChange&licenseId="
							+ productLicense

						var returnValue = showModalDialog(url, '',
							"dialogWidth:1000px;dialogHeight:600px;");
						// alert(returnValue)
						if (returnValue) {
							$("#productInfo" + goodsRowNum + "_cmp_license"
								+ rowNum).val(returnValue);
						}
					} else if (equConfig.type !== 'view') {
						var licenseUrl = '';
						if (thisLicense != 0) {
							licenseUrl = '&licenseId=' + thisLicense
						}
						url = "?model=yxlicense_license_tempKey&action=toSelectWin" + licenseUrl
						var returnValue = showModalDialog(url, '',
							"dialogWidth:1000px;dialogHeight:600px;");
						if (returnValue) {
							$("#productInfo" + goodsRowNum + "_cmp_license" + rowNum).val(returnValue);
						}
					} else {
						showLicense(thisLicense);
					}
				}
			},
			html: '<input type="button"  value="��������"  class="txt_btn_a"  />'
		}, {
			display: '',
			name: 'configCode',
			type: 'hidden',
			staticVal: 1
		}, {
			display: '���´﷢���ƻ�����',
			name: 'issuedShipNum',
			type: 'hidden'
		}, {
			name: 'delButton',
			display: '����',
			type: 'statictext',
			process: function (html, rowData, $tr, g) {
				if (equConfig.type == "view" || (rowData && rowData.isDel == '0')) {
					return '��';
				} else {
					if (!rowData)
						return '��';
					else
						return html;
				}
			},
			event: {
				'click': function (e) {
					var i = $(this).data("rowNum");
					var isDel = $("#productInfo" + goodsRowNum + "_cmp_isDel" + i).val();
					if (isDel == '1') {
						if (confirm('�Ƿ�ȷ���������ϣ�')) {
							$("#productInfo" + goodsRowNum + "_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#fffffb;");
							$("#productInfo" + goodsRowNum + "_cmp_productNo" + i).attr("style", "background:#fffffb;");
							$("#productInfo" + goodsRowNum + "_cmp_productName" + i).attr("style", "background:#fffffb;");
							$("#productInfo" + goodsRowNum + "_cmp_warrantyPeriod" + i).attr("style", "background:#fffffb;");
							$("#productInfo" + goodsRowNum + "_cmp_productModel" + i).attr("style", "background:#fffffb;");
							$("#productInfo" + goodsRowNum + "_cmp_number" + i).attr("style", "background:#fffffb;");
							$("#productInfo" + goodsRowNum + "_cmp_number" + i).attr("readonly", false);
							$("#productInfo" + goodsRowNum + "_cmp_arrivalPeriod" + i).attr("style", "background:#fffffb;");
							$("#productInfo" + goodsRowNum + "_cmp_arrivalPeriod" + i).attr("readonly", false);
							$("#productInfo" + goodsRowNum + "_cmp_delButton" + i).html("��");
							$("#productInfo" + goodsRowNum + "_cmp_isDel" + i).val("0");
						}
					}
				}
			},
			html: '<input type="button"  value="����ɾ������"  class="txt_btn_a"  />'
		}];

	url = url
		? url
		: '?model=projectmanagent_borrow_borrowequ&action=getConEqu';// ��Ʒ����������Դ
	//	alert(url)
	$("#productInfo" + goodsRowNum).yxeditgrid({
		objName: 'borrow[detail][' + goodsRowNum + ']',
		url: url,
		isAddAndDel: isAddAndDel,
		param: param,
		type: equConfig.type,
		colModel: equColConfig,
		event: {
			beforeAddRow: beforeAddRow,
			addRow: function (e, rowNum, rowData, g, tr) {
				addGridChangeShow(g, rowNum, 'productName', rowData, tr);
				countStander();
				curPageEquEstimate();
			},
			beforeRemoveRow: function (e, rowNum, rowData, g) {
				var dataParam = {
					"borrowId": $("#borrowId").val(),
					"isDel": 0,
					"conProductId": (rowData)? rowData.conProductId : '',
					"advCondition": (rowData)? " AND parentEquId='" + rowData.id + "'" : ""
				};
				if($("#borrowOldId").val() > 0 && $("#borrowOldId").val() != $("#borrowId").val()){
					dataParam.temp = 1;
				}else{
					dataParam.isTemp = 0;
				}
				if (equConfig.type == 'change' && rowData) {
					$.ajax({
						type: "POST",
						url: "?model=projectmanagent_borrow_borrowequ&action=getRelativeEqu",
						data: dataParam,
						async: false,
						success: function (data) {
							var configExecutedNum = configIssuedShipNum = 0;
							if (data != "") {
								data = eval("(" + data + ")");
								$.each(data, function (i, item) {
									configExecutedNum += parseInt(item.executedNum);
									configIssuedShipNum += parseInt(item.issuedShipNum);
								});
							}
							if (configExecutedNum > 0) {
								alert("�����ϲ�������ѳ��⣬��ֱֹ��ɾ���������˻����̣�");
								g.isRemoveAction = false;
								return false;
							} else if (configIssuedShipNum > 0) {
								alert("�����ϲ���������´﷢���ƻ�����ֱֹ��ɾ����");
								g.isRemoveAction = false;
								return false;
							} else if (rowData.executedNum > 0) {//������ѳ��ⲻ��ɾ
								alert("�������Ѳ��ֳ��⣬��ֱֹ��ɾ���������˻����̣�");
								g.isRemoveAction = false;
								return false;
							} else if (rowData.issuedShipNum > 0) {//���û���ѳ���,�����з����ƻ���Ҳ����ɾ
								alert("���������´﷢���ƻ�����ֱֹ��ɾ����");
								g.isRemoveAction = false;
								return false;
							}

						}
					});
				}
			},
			reloadData: function (event, g, data) {
				//if (equConfig.type != 'add') {// ��������id
					// �����������
					initProductConfigs(goodsId, goodsRowNum);
				// }
				// �����׼������
				countStander();
				curPageEquEstimate();
				if (data) {
					var rowCount = g.getCurRowNum();
					for (var i = 0; i < rowCount; i++) {
						var isDel = $("#productInfo" + goodsRowNum + "_cmp_isDel" + i).val();
						if (isDel == '1') {
							$("#productInfo" + goodsRowNum + "_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#a1a3a6;");
							$("#productInfo" + goodsRowNum + "_cmp_productNo" + i).attr("style", "background:#a1a3a6;");
							$("#productInfo" + goodsRowNum + "_cmp_productName" + i).attr("style", "background:#a1a3a6;");
							$("#productInfo" + goodsRowNum + "_cmp_warrantyPeriod" + i).attr("style", "background:#a1a3a6;");
							$("#productInfo" + goodsRowNum + "_cmp_productModel" + i).attr("style", "background:#a1a3a6;");
							$("#productInfo" + goodsRowNum + "_cmp_number" + i).attr("style", "background:#a1a3a6;");
							$("#productInfo" + goodsRowNum + "_cmp_number" + i).attr("readonly", true);
							$("#productInfo" + goodsRowNum + "_cmp_arrivalPeriod" + i).attr("style", "background:#a1a3a6;");
							$("#productInfo" + goodsRowNum + "_cmp_arrivalPeriod" + i).attr("readonly", true);
						}
					}
				}
			},
			removeRow: function (goodsRowNum) {
				return function (e, rowNum, rowData, index, g) {
					var goodsId = $("#conProductId" + goodsRowNum).val();
					var $tr = $("#goodsDetail_" + goodsId + "_" + rowNum);
					if (confirm("ȷ��ɾ�����������ã�")) {
						if (equConfig.type == "change") {// ����Ǳ��
							$tr.find("tr").each(function (i) {
								if (i != 0) {
									var $tr_d = $(this).find("td").parent();
									var $td = $(this).find("td").eq(0);
									var name = $td.attr("name");
									$tr_d.append("<input type='hidden' name='"
										+ name + "[isDelTag]' value='1'>");
								}
							});
							$tr.hide();
						} else {
							$tr.remove();
						}
					} else {
						// var name = $tr.attr("name");
						$tr.find("tbody>tr").each(function () {
							var $td = $(this).find("td");
							var name = $td.attr("name");
							if (name) {
								var $configCode = $("<input type='hidden' name='"
									+ name
									+ "[configCode]' value='1'></input>");

								if ($td.size() > 0) {
									$($td.get(0)).append($configCode);
								}
							}
						});
					}
					countStander();
					arrivalPeriod = g.getCmpByRowAndCol(rowNum, "arrivalPeriod").val();
					countStanderAfterRemove(arrivalPeriod);
					setTimeout(function(){
						curPageEquEstimate();
					},100);
				}
			}(goodsRowNum)
		}
	});

	// �������б������ɺ�Ž���ͳ��
	setTimeout(curPageEquEstimate,500);
}

$(function () {
	/**
	 * ��֤��Ϣ
	 */
	validate({}, { disableButton: true });
});
function getNoGoodsProducts(newRowNum) {
	var docType = $('#docType').val();
	// ��������
	var param = {};
	var changeView = $("#changeView").val();
	var linkId = $("#linkId").val();
	param.borrowId = $('#borrowId').val();
	//	param.notDel = 1;
	if (changeView == '1') {
		param.temp = 1;
	} else {
		if($("#borrowOldId").val() > 0 && $("#borrowOldId").val() != $("#borrowId").val()){
			param.temp = 1;
		}else{
			param.isTemp = 0;
		}
	}
	if (equConfig.type == "view" && $("#isShowDel").val() == "true") {
		delete param.notDe4l;
	}
	var isAddAndDel = ($("#isForSales").val() != undefined && $("#isForSales").val() == 1)? false : true;
	var equColConfig = ($("#isForSales").val() != undefined && $("#isForSales").val() == 1)?
		[
			{
				display: 'isDel',
				name: 'isDel',
				type: 'hidden'
			}, {
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '���ϱ��',
			name: 'productNo',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '��������',
			name: 'productName',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '�ͺ�/�汾',
			name: 'productModel',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '������',
			name: 'warrantyPeriod',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '��λ',
			name: 'unitName',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '����',
			name: 'number',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '���ϵ���',
			name: 'price',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '���Ͻ��',
			name: 'money',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		},{
			display: '������',
			name: 'arrivalPeriod',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}] : [
		{
			display: 'isDel',
			name: 'isDel',
			type: 'hidden'
		}, {
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '���ϱ��',
			name: 'productNo',
			tclass: 'txtmiddle',
			validation: {
				required: true
			},
			readonly: true,
			process: function ($input, rowData) {
				if ((equConfig.type != "change" && equConfig.type != "view")
					|| !rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_product({
						isDown: true,
						// closeCheck : true,
						closeAndStockCheck: true,
						hiddenId: 'productInfo_cmp_productId' + rowNum,
						height: 250,
						event: {
							'clear': function () {
								g.clearRowValue(rowNum);
							}
						},
						gridOptions: {
							showcheckbox: false,
							isTitle: true,
							event: {
								"row_dblclick": (function (rowNum, rowData) {
									return function (e, row, productData) {
										g.getCmpByRowAndCol(rowNum,
											'productName')
											.val(productData.productName);
										g.getCmpByRowAndCol(rowNum,
											'productModel')
											.val(productData.pattern);
										g.getCmpByRowAndCol(rowNum,
											'arrivalPeriod')
											.val(productData.arrivalPeriod);
										g.getCmpByRowAndCol(rowNum,
											'warrantyPeriod')
											.val(productData.warranty);
										var num = getInventoryInfos(docType, productData.id)
										var $exeNum = g.getCmpByRowAndCol(rowNum, 'exeNum');// ����id
										$exeNum.html(num);
										var $priCost = g.getCmpByRowAndCol(rowNum, 'price');// ���ϵ���
										$priCost.val(productData.priCost);
										var $money = g.getCmpByRowAndCol(rowNum, 'money');// ���ϵ���
										$money.val(productData.priCost * 1);
										countStander();
										curPageEquEstimate();
										//										if (confirm("�Ƿ�������������")) {
										//											var productNum = productData
										//													? productData.number
										//													: 1;
										//											var conProId = productData
										//													? productData.productId
										//													: 0;
										//										}
									}
								})(rowNum, rowData)
							}
						}
					});
				} else if (equConfig.type != "view") {
					$input.attr("readonly", true);
				}
				return $input;
			}
		}, {
			display: '��������',
			name: 'productName',
			tclass: 'txt',
			validation: {
				required: true
			},
			readonly: true,
			process: function ($input, rowData) {
				// ���ҳ�治�ܸ�������
				if ((equConfig.type != "change" && equConfig.type != "view")
					|| !rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_product({
						isDown: true,
						//						closeCheck : true,
						closeAndStockCheck: true,
						hiddenId: 'productInfo_cmp_productId' + rowNum,
						nameCol: 'productName',
						height: 250,
						event: {
							'clear': function () {
								g.clearRowValue(rowNum);
							}
						},
						gridOptions: {
							showcheckbox: false,
							isTitle: true,
							event: {
								"row_dblclick": (function (rowNum, rowData) {
									return function (e, row, productData) {
										g.getCmpByRowAndCol(rowNum,
											'productNo')
											.val(productData.productCode);
										g.getCmpByRowAndCol(rowNum,
											'productModel')
											.val(productData.pattern);
										g.getCmpByRowAndCol(rowNum,
											'arrivalPeriod')
											.val(productData.arrivalPeriod);
										g.getCmpByRowAndCol(rowNum,
											'warrantyPeriod')
											.val(productData.warranty);
										var num = getInventoryInfos(docType, productData.id)
										var $exeNum = g.getCmpByRowAndCol(rowNum, 'exeNum');// ����id
										$exeNum.html(num);
										var $priCost = g.getCmpByRowAndCol(rowNum, 'price');// ���ϵ���
										$priCost.val(productData.priCost);
										var $money = g.getCmpByRowAndCol(rowNum, 'money');// ���ϵ���
										$money.val(productData.priCost * 1);
										countStander();
										curPageEquEstimate();
										//										if (confirm("�Ƿ�������������")) {
										//											var productNum = rowData
										//													? rowData.number
										//													: 1;
										//											var conProId = rowData
										//													? rowData.productId
										//													: 0;
										//										}
									}
								})(rowNum, rowData)
							}
						}
					});
				} else if (equConfig.type != "view") {
					$input.attr("readonly", true);
				}
				return $input;
			}
		}, {
			display: '����Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '�ͺ�/�汾',
			name: 'productModel',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '������',
			name: 'warrantyPeriod',
			readonly: true,
			tclass: 'readOnlyTxtItem'
		}, {
			display: '��λ',
			name: 'unitName',
			type: 'hidden'
		}, {
			display: '����',
			name: 'number',
			tclass: 'txtshort',
			value: 1,
			event: {
				blur: function () {
					var rowNum = $(this).data("rowNum");
					var issuedShipNum = $("#productInfo" + goodsRowNum + "_cmp_issuedShipNum" + rowNum).val();
					if($(this).val() < issuedShipNum){
						alert("��������С�����´﷢���ƻ�����!");
						$(this).val(issuedShipNum);
					}else if(numberChk($(this),$(this).val())){
						var price = $("#productInfo_cmp_price" + rowNum).val();
						$("#productInfo_cmp_money" + rowNum).val(price * $(this).val());
						curPageEquEstimate();
					}
				}
			}
		}, {
			display: '���ϵ���',
			name: 'price'
			, type: 'hidden'
		}, {
			display: '���Ͻ��',
			name: 'money'
			, type: 'hidden'
		}, {
			display: '�������',
			name: 'exeNum',
			type: 'statictext'
		}, {
			display: '������',
			name: 'arrivalPeriod',
			tclass: 'txtshort',
			event: {
				blur: function () {
					countStander();
				}
			}
		}, {
			display: '���ϼ�������Id',
			name: 'license',
			type: 'hidden'
		}, {
			name: 'licenseButton',
			display: '��������',
			type: 'statictext',
			process: function (html, rowData, $tr, g) {
				if (equConfig.type == "view" && rowData.license == '') {
					return '��';
				} else {
					return html;
				}
			},
			event: {
				'click': function (e) {
					var rowNum = $(this).data("rowNum");
					// ��ȡ���ϼ�������id
					var thisLicense = $("#productInfo_cmp_license" + rowNum)
						.val();
					if (equConfig.type != "view") {
						url = "?model=yxlicense_license_tempKey&action=toSelectChange&licenseId="
							+ thisLicense

						var returnValue = showModalDialog(url, '',
							"dialogWidth:1000px;dialogHeight:600px;");
						if (returnValue) {
							$("#productInfo_cmp_license" + rowNum)
								.val(returnValue);
						}
					} else {
						showLicense(thisLicense);
					}
				}
			},
			html: '<input type="button"  value="��������"  class="txt_btn_a"  />'
		}, {
			display: '',
			name: 'configCode',
			type: 'hidden',
			staticVal: 1
		}, {
			display: '���´﷢���ƻ�����',
			name: 'issuedShipNum',
			type: 'hidden'
		}, {
			name: 'delButton',
			display: '����',
			type: 'statictext',
			process: function (html, rowData, $tr, g) {
				if (rowData) {
					if (rowData.isDel == '0') {
						return '��';
					} else {
						return html;
					}
				}
			},
			event: {
				'click': function (e) {
					var i = $(this).data("rowNum");
					var isDel = $("#productInfo_cmp_isDel" + i).val();
					if (isDel == '1') {
						if (confirm('�Ƿ�ȷ���������ϣ�')) {
							$("#productInfo_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#fffffb;");
							$("#productInfo_cmp_productNo" + i).attr("style", "background:#fffffb;");
							$("#productInfo_cmp_productName" + i).attr("style", "background:#fffffb;");
							$("#productInfo_cmp_warrantyPeriod" + i).attr("style", "background:#fffffb;");
							$("#productInfo_cmp_productModel" + i).attr("style", "background:#fffffb;");
							$("#productInfo_cmp_number" + i).attr("style", "background:#fffffb;");
							$("#productInfo_cmp_number" + i).attr("readonly", false);
							$("#productInfo_cmp_arrivalPeriod" + i).attr("style", "background:#fffffb;");
							$("#productInfo_cmp_arrivalPeriod" + i).attr("readonly", false);
							$("#productInfo_cmp_delButton" + i).html("��");
							$("#productInfo_cmp_isDel" + i).val("0");
						}
					}
				}
			},
			html: '<input type="button"  value="����ɾ������"  class="txt_btn_a"  />'
		}];


	var isForSales = ($("#isForSales").val() != undefined && $("#isForSales").val() == 1)? 1 : '';
	var needSalesConfirm = ($("#needSalesConfirm").val() != undefined)? $("#needSalesConfirm").val()  : '';
	var salesConfirmId = ($("#salesConfirmId").val() != undefined)? $("#salesConfirmId").val()  : '';
	if(isForSales == 1 && needSalesConfirm == 3 && salesConfirmId > 0){
		param.isTemp = 1;
		linkId = salesConfirmId;
	}

	$("#productInfo").yxeditgrid({
		objName: 'borrow[detail][' + newRowNum + ']',
		tableClass: 'form_in_table',
		url: '?model=projectmanagent_borrow_borrowequ&action=getNoProductEqu&linkId=' + linkId,
		param: param,
		isAddAndDel: isAddAndDel,
		type: equConfig.type,
		// isAddOneRow:false,
		// isAdd : false,
		colModel: equColConfig,
		event: {
			beforeAddRow: beforeAddRow,
			addRow: function (e, rowNum, rowData, g, tr) {
				addGridChangeShow(g, rowNum, 'productName', rowData, tr);
				countStander()
			},
			beforeRemoveRow: function (e, rowNum, rowData, g) {
				if (equConfig.type == 'change' && rowData) {
					if (rowData.executedNum > 0) {
						alert("�������Ѳ��ֳ��⣬��ֱֹ��ɾ���������˻����̣�");
						g.isRemoveAction = false;
						return false;
					}
				}
			},
			removeRow: function (e, rowNum, rowData, index, g) {
				countStander();
				setTimeout(function(){
					curPageEquEstimate();
				},100);
				arrivalPeriod = g.getCmpByRowAndCol(rowNum, "arrivalPeriod").val();
				countStanderAfterRemove(arrivalPeriod)
			},
			reloadData: function (event, g, data) {
				if (data) {
					var rowCount = g.getCurRowNum();
					for (var i = 0; i < rowCount; i++) {
						var isDel = $("#productInfo_cmp_isDel" + i).val();
						if (isDel == '1') {
							$("#productInfo_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#a1a3a6;");
							$("#productInfo_cmp_productNo" + i).attr("style", "background:#a1a3a6;");
							$("#productInfo_cmp_productName" + i).attr("style", "background:#a1a3a6;");
							$("#productInfo_cmp_warrantyPeriod" + i).attr("style", "background:#a1a3a6;");
							$("#productInfo_cmp_productModel" + i).attr("style", "background:#a1a3a6;");
							$("#productInfo_cmp_number" + i).attr("style", "background:#a1a3a6;");
							$("#productInfo_cmp_number" + i).attr("readonly", true);
							$("#productInfo_cmp_arrivalPeriod" + i).attr("style", "background:#a1a3a6;");
							$("#productInfo_cmp_arrivalPeriod" + i).attr("readonly", true);
						}
					}
				}
			}
		}
	});
}

// ץȡҳ����������Ч������
function catchAllAvlbEquInpages(){
	var equArr = [];
	// ͳ����Ч���ϵ�ID�Լ�����
	for(var i = 1;i<=$("#invbody input[id^='conProductId']").length;i++){
		currentNum = 0;
		// console.log($("#productInfo"+i+" input[id^='productInfo"+i+"_cmp_productId']"));
		$("input[id^='productInfo"+i+"_cmp_productId']").each(function(equIndex,item){
			// console.log("#productInfo"+i+"_cmp_number"+equIndex);
			var currentIndex = $(item).parent("td").parent("tr").attr("rowNum");
			var equConId = $("#productInfo"+i+"_cmp_id"+currentIndex).val();
			var equNum = ($("#productInfo"+i+"_cmp_number"+currentIndex).children("font").children(".newValue").text() != '')? $("#productInfo"+i+"_cmp_number"+currentIndex).children("font").children(".newValue").text() : $("#productInfo"+i+"_cmp_number"+currentIndex).val();
			var isDel = $("#productInfo"+i+"_cmp_isDel"+currentIndex).val();
			var isDelTag = $("#productInfo"+i+"_cmp_isDelTag"+currentIndex).val();
			var arrObj = {
				"equConId" : equConId,
				"equId" : $(item).val(),
				"equNum" : equNum
			};
			if($(item).val() != '' && isDel != 1 && isDelTag != 1){
				equArr.push(arrObj);
			}
		});
	}

	// ͳ����Ч���������ID�Լ�����
	var configArr = [];
	if($(".tr_inner").length > 0){
		$.each($(".tr_inner"),function(i,item){
			var index = $(item).attr("index");
			var trName = $(item).parents("tr").attr("name");
			var equConId = $("input[name='"+trName+"["+index+"][id]']").val();
			var productId = $("input[name='"+trName+"["+index+"][productId]']").val();
			var productNum = ($("input[name='"+trName+"["+index+"][number]']").children("font").children(".newValue").text() != '')? $("input[name='"+trName+"["+index+"][number]']").children("font").children(".newValue").text() : $("input[name='"+trName+"["+index+"][number]']").val();
			// if($(item).css("display") != "none" && productId && productId != ''){
			if(($(item).css("display") != "none" && $(item).parents("table").parents("tr").css("display") != "none") && productId && productId != ''){
				// console.log(productId + " <-> "+productNum);
				var arrObj = {
					"equConId" : equConId,
					"equId" : productId,
					"equNum" : productNum
				};
				configArr.push(arrObj);
			}
		});
	}


	// ͳ���޲�Ʒ����������Ϣ
	$("input[id^='productInfo_cmp_productId']").each(function(equIndex,item){
		var currentIndex = $(item).parent("td").parent("tr").attr("rowNum");
		var equConId = $("#productInfo_cmp_id"+currentIndex).val();
		var equNum = ($("#productInfo_cmp_number"+currentIndex).children("font").children(".newValue").text() != '')? $("#productInfo_cmp_number"+currentIndex).children("font").children(".newValue").text() : $("#productInfo_cmp_number"+currentIndex).val();
		var isDel = $("#productInfo_cmp_isDel"+currentIndex).val();
		var isDelTag = $("#productInfo_cmp_isDelTag"+currentIndex).val();

		var arrObj = {
			"equConId" : equConId,
			"equId" : $(item).val(),
			"equNum" : equNum
		};
		if($(item).val() != '' && isDel != 1 && isDelTag != 1){
			equArr.push(arrObj);
		}
	});

	equArr = $.merge(equArr,configArr);
	// console.log(equArr);
	return equArr;
}

// ����ҳ�����ϳɱ�����
function curPageEquEstimate(equArr){
	equArr = (equArr && equArr != undefined)? equArr : catchAllAvlbEquInpages();
	var responseText = $.ajax({
		url : '?model=projectmanagent_borrow_borrowequ&action=curEqusEstimate',
		type : 'POST',
		data : {
			equs : equArr
		},
		async : false,
		dataType : 'json'
	}).responseText;
	var result = eval("("+responseText+")");
	var equEstimate = result.totalEstimate;
	var equEstimateWithTax = result.totalEstimateTax;
	equEstimate = Number(equEstimate);
	equEstimateWithTax = Number(equEstimateWithTax);
	var equEstimateWithTaxShow = moneyFormat2(equEstimateWithTax,2);
	$("#equEstimate").children("span").text(equEstimateWithTaxShow);
	$("#equEstimateVal").val(equEstimate);
	$("#equEstimateTaxVal").val(equEstimateWithTax);
}
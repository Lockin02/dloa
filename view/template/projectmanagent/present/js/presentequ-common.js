/**
 *
 * ����ȷ�Ϲ���js(�������༭��������鿴)
 *
 * @type
 */
var equConfig = {
	type : ''// add edit change view
};// ��������

var proArr = {};
var proNumArr = [];

// �����Ϣ

/**
 * ת�ɱ����ɫ����
 */
var tranToChangeShow = function(oldVal, newVal) {
	var newHtml = "<font color='red'><span class='oldValue' style='display:none'>"
			+ oldVal
			+ "</span><span class='compare' style='display:none'>=></span><span class='newValue'>"
			+ newVal + "</span></font>";
	return newHtml;
}

/**
 * ���ɱ༭�����ϱ����ʶͼ��
 */
var addGridChangeShow = function(g, rowNum, colName, rowData, tr) {
	if (equConfig.type == "view") {
		var $cmp = g.getCmpByRowAndCol(rowNum, colName);
		if (rowData.changeTips == '1') {
			// tr.css("background-color", "#F8846B");
			$cmp.prepend('<img title="����༭�Ĳ�Ʒ" src="images/changeedit.gif" />');
		} else if (rowData.changeTips == '2') {
			$cmp.prepend('<img title="��������Ĳ�Ʒ" src="images/new.gif" />');
		} else if (rowData.changeTips == '3'&&rowData.isDel=='1') {
			$cmp.prepend('<img title="���ɾ���Ĳ�Ʒ" src="images/changedelete.gif" />');
			tr.css("color", "#8B9CA4");
		} else if (rowData.changeTips == '0'&&rowData.isDel=='1') {
			tr.css("color", "#8B9CA4");
		}
	}
}

/**
 * ��������ϸ
 */
var beforeAddRow = function(e, rowNum, rowData, g, tr) {
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
		type : "POST",
		url : "?model=goods_goods_goodscache&action=getCacheConfig",
		data : {
			"id" : cacheId
		},
		async : false,
		success : function(data) {
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
	colObj.each(function(i, n) {
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
	productObj.each(function(i, n) {
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
	// �ж��Ǳ༭�����������
	if (equConfig.type != 'add' && !isGetPro) {
		param.parentEquId = equId;
		if (equConfig.type != "view" || $("#isShowDel").val() == "false") {
			param.isDel = 0;
		}
		url = "?model=projectmanagent_present_presentequ&action=getEquByParentEquId";
	} else {
		param.productId = productId;
		url = "?model=stock_productinfo_configuration&action=getAccessForPro";
	}
	if ($('#changeView').val() == '1') {
		param.temp = 1;
	}else if($("#isChangeTip").val() == '1'){
		param.temp = 1;
		delete param.isTemp;
	}else {
		param.isTemp = 0;
	}
	var data = $.ajax({
		type : "POST",
		url : url,
		data : param,
		async : false
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

		var $th = $('<tr class="main_tr_header1"><th width="30px"></th><th width="35px">���</th><th>�������</th>'
				+ '<th>�������</th><th>�汾�ͺ�</th><th>����</th><th>������</th></tr>');
		$table.append($th);
		var configtr = $("#productInfo" + goodsRowNum + " table tr[rowNum="
				+ productRowNum + "]");
		configtr.after($tr);
		var nametr = 'present[detail][' + goodsRowNum + ']';
		$tr.attr("name", nametr);
		for (var i = 0; i < obj.length; i++) {
			var index = i + (productRowNum + 1) * 100;
			var name = 'present[detail][' + goodsRowNum + '][' + index + ']';
			var p = obj[i];

			// �������ϸ�������
			var infoStr = '';
			if(p.isDel != 1 || !p.isDel){
				var pNumber = (p.number)? p.number : 0;
				infoStr = "data-goodsRow='"+goodsRowNum+"' data-productRows='"+productRowNum+"' data-configNum='"+i+"'";
				setProArr(goodsRowNum,productRowNum+"_detail"+i,p.productId,pNumber);
			}

			$ctr = $("<tr  class='tr_inner'></tr>");
			var imgStr = "";
			if (equConfig.type == 'view') {
				var key = p.id;
				if ($("#isTemp").val() == 1) {
					key = p.originalId;
				}
				var detail = changeDetailObj["d" + key];
				if (detail) {
					for (var i = 0; i < detail.length; i++) {
						var changeField = detail[i].changeField;
						p[changeField] = tranToChangeShow(detail[i].oldValue,
								p[detail[i].changeField]);
					}
				}
				if (p.changeTips == '1') {
					imgStr = '<img title="���±���༭������" src="images/changeedit.gif" />';
				} else if (p.changeTips == '2') {
					imgStr = '<img title="���±������������" src="images/new.gif" />';
				} else if (p.changeTips == '3'&&p.isDel=='1') {
					imgStr = '<img title="���±��ɾ��������" src="images/changedelete.gif" />';
					$ctr.css("color", "#8B9CA4");
				} else if (p.changeTips == '0'&&p.isDel=='1') {
					$ctr.css("color", "#8B9CA4");
				}
			}
			$ctr.attr("index", index);
			if (equConfig.type != 'view') {
				$ctr
						.append("<td name='"
								+ name
								+ "'><img src='images/removeline.png' "+infoStr+" onclick='delConfig(this,"+p.executedNum+","+p.issuedShipNum+")' title='ɾ����'></td>");
			} else {
				$ctr.append("<td></td>");
			}
			$ctr.append("<td>" + (i + 1) + "</td>");
			if (!p.productNo) {
				p.productNo = p.productCode;
			}
			var hide = '<input type="hidden" name="' + name
					+ '[parentRowNum]" value="'
					+ (productId + "_" + productRowNum)
					+ '"/><input type="hidden" name="' + name
					+ '[productId]" value="' + p.productId
					+ '"/><input type="hidden" name="' + name
					+ '[productNo]"  value="' + p.productNo
					+ '"/><input type="hidden" name="' + name
					+ '[productName]"  value="' + p.productName
					+ '"/><input type="hidden" name="' + name
					+ '[conProductId]"  value="' + goodsId
					+ '"/><input type="hidden" name="' + name
					+ '[unitName]"  value="' + p.unitName + '"/>';
			if (equConfig.type != 'add') {
				hide += '<input type="hidden" name="' + name + '[id]" value="'
						+ (p.id ? p.id : "") + '"/>';
			}

			var td = $("<td>" + p.productNo + "</td>");
			td.append(hide);
			$ctr.append(td);
			$ctr.append("<td>" + imgStr + p.productName + "</td>");
			var pattern = (typeof(p.pattern) != 'undefined'
					? p.pattern
					: p.productModel);
			if (equConfig.type != "view") {
				pattern = $("<input class='txtshort' type='text' name='" + name
						+ "[productModel]' value='" + pattern + "'/>");
			}
			var $td = $("<td></td>");
			$td.append(pattern);
			$ctr.append($td);
			var num = 0;
			if (p.configNum) {
				num = productNum * p.configNum;
			} else if (p.number) {
				num = p.number;
			}
			var cnum = num;
			if (equConfig.type != "view") {
				cnum = $('<input type="text" class="txtshort"  name="' + name
						+ '[number]"'+infoStr+' value="' + num + '" onblur="countConfig(this)"/>');
			}
			var $td = $("<td></td>");
			$td.append(cnum);
			$ctr.append($td);
			var cperiod = p.arrivalPeriod;
			if (equConfig.type != "view") {
					cperiod = $('<input type="text" class="txtshort"  name="'
							+ name + '[arrivalPeriod]" value="' + p.arrivalPeriod
							+ '" onblur="countStander()" id="inner_arrivalPeriod'
							+ (productId + "_" + productRowNum)+'"/>');
			}
			var $td = $("<td></td>");
			$td.append(cperiod);
			$ctr.append($td);
			$table.append($ctr);
		}
	};
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
	document.getElementById('form1').action = "index1.php?model=projectmanagent_present_presentequ&action=equEdit&act=audit";
}

function countStander() {
	var cmp_arrivalPeriod = new Array();
	var inner_arrivalPeriod = new Array();
	var cmp_flag = false;
	var inner_flag = false;
	// ���ϵĽ�����
	$('input[id*=cmp_arrivalPeriod]').each(function(i) {
		if (checkNum(this)) {
			if( $(this).parent().parent().children("input").val()!=1 ){
				cmp_arrivalPeriod[i] = $(this).val() * 1
				cmp_flag = true;
			}else{
				cmp_arrivalPeriod[i] = 0
			}
		} else {
			alert('������ֻ��Ϊ��������');
			return false;
		}
	});
	// �������õĽ�����
	$('input[id*=inner_arrivalPeriod]').each(function(i) {
		if (checkNum(this)) {
			if( $(this).parent().parent().children("input").val()!=1 ){
				inner_arrivalPeriod[i] = $(this).val() * 1
				inner_flag = true;
			}else{
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
	if( $('#standardDate_v').val() ){
		newDate = stringToDate($('#standardDate_v').val(), maxArrival)
	}else{
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
	$('input[id*=cmp_arrivalPeriod]').each(function(i) {
		if (checkNum(this)) {
			if( $(this).parent().parent().children("input").val()!=1 ){
				cmp_arrivalPeriod[i] = $(this).val() * 1
				cmp_flag = true;
			}else{
				cmp_arrivalPeriod[i] = 0
			}
		} else {
			alert('������ֻ��Ϊ��������');
			return false;
		}
	});
//	alert(cmp_arrivalPeriod.length);
	if( cmp_arrivalPeriod.length>0 ){
		for (var i=0;i<cmp_arrivalPeriod.length;i++){
			if( arrivalPeriod==cmp_arrivalPeriod[i] ){
				cmp_arrivalPeriod.splice(i,1)
				break;
			}
		}
	}
	// �������õĽ�����
	$('input[id*=inner_arrivalPeriod]').each(function(i) {
		if (checkNum(this)) {
			if( $(this).parent().parent().children("input").val()!=1 ){
				inner_arrivalPeriod[i] = $(this).val() * 1
				inner_flag = true;
			}else{
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
	if( $('#standardDate_v').val() ){
		newDate = stringToDate($('#standardDate_v').val(), maxArrival)
	}else{
		newDate = stringToDate(formatDate(new Date()), maxArrival)
	}
//	alert(formatDate(newDate))
	$('#standardDate').val(formatDate(newDate))
}

/** ********************ͳ�����************************ */
function countConfig(obj){
	if($(obj).attr('data-goodsRow') != undefined && $(obj).attr('data-productRows') != undefined && $(obj).attr('data-configNum') != undefined){
		setProArr($(obj).attr('data-goodsRow'),$(obj).attr('data-productRows')+"_detail"+$(obj).attr('data-configNum'),'',$(obj).val(),'numChange');
		countEquCost();
	}
}

/** ********************ɾ�����************************ */
function delConfig(obj,executedNum,issuedShipNum) {
	if( parseInt(executedNum) > 0 ){//������ѳ��ⲻ��ɾ
		alert("�������Ѳ��ֳ��⣬��ֱֹ��ɾ���������˻����̣�");
	}else if(parseInt(issuedShipNum) > 0){//���û���ѳ���,�����з����ƻ���Ҳ����ɾ
		alert("���������´﷢���ƻ�����ֱֹ��ɾ����");
	}else if (confirm('ȷ��Ҫɾ�����У�')) {
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
		
		// �������ϸ�������
		if($(obj).attr('data-goodsRow') != undefined && $(obj).attr('data-productRows') != undefined && $(obj).attr('data-configNum') != undefined){
			setProArr($(obj).attr('data-goodsRow'),$(obj).attr('data-productRows')+"_detail"+$(obj).attr('data-configNum'),'',0,'del');
			countEquCost();
		}
	}
}

/**
 * ���ݲ�Ʒ��Ϣ����������Ϣ
 */
function getGoodsProducts(goodsRowNum, url) {
	var docType=$('#docType').val();
	var selectAssetFn = function(g, rowNum, rowData) {
		var $productName = g.getCmpByRowAndCol(rowNum, 'productName');// ��������
		$productName.val(rowData.productName);
		var $arrivalPeriod = g.getCmpByRowAndCol(rowNum, 'arrivalPeriod');// ���Ͻ�����
		$arrivalPeriod.val(rowData.arrivalPeriod);
		var $pattern = g.getCmpByRowAndCol(rowNum, 'productModel');// ���Ϲ���ͺ�
		$pattern.val(rowData.pattern);
		var $productNo = g.getCmpByRowAndCol(rowNum, 'productNo');// ���ϱ���
		$productNo.val(rowData.productCode);
		var $unitName = g.getCmpByRowAndCol(rowNum, 'unitName');// ���ϱ���
		$unitName.val(rowData.unitName);
		var $productId = g.getCmpByRowAndCol(rowNum, 'productId');// ����id
		$productId.val(rowData.id);
		var num = getInventoryInfos(docType,rowData.id)
		var $exeNum = g.getCmpByRowAndCol(rowNum, 'exeNum');// ����id
		$exeNum.html(num);
		var $warrantyPeriod = g.getCmpByRowAndCol(rowNum, 'warrantyPeriod');// ����id
		$warrantyPeriod.val(rowData.warranty);
	};

	var goodsId = $("#conProductId" + goodsRowNum).val();
	var linkId = $("#linkId").val();
	var number = $("#number" + goodsRowNum).val();
	var changeView = $("#changeView").val();
	var param = {
		conProductId : goodsId,
		parentEquId : 0,
		// isTemp : 0
//		isDel : 0
//		,number : number
	};
//	if (linkId) {
//		param.linkId = linkId;
//	}
	if (changeView) {
		param.isTemp = 1;
		delete param.isTemp;
	}
	// else if($("#isChangeTip").val() == '1'){
	// 	param.temp = 1;
	// 	delete param.isTemp;
	// }
//	if (url) {
//		param.number = number;
//	}
	if (equConfig.type == "view" && $("#isShowDel").val() == "true") {
		delete param.isDel;
	}
	url = url
			? url
			: '?model=projectmanagent_present_presentequ&action=getConEqu';// ��Ʒ����������Դ
	$("#productInfo" + goodsRowNum).yxeditgrid({
		objName : 'present[detail][' + goodsRowNum + ']',
		url : url,
		param : param,
		type : equConfig.type,
		colModel : [{
			display : 'isDel',
			name : 'isDel',
			type : 'hidden'
		},{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '���ϱ��',
			name : 'productNo',
			tclass : 'txtmiddle',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				if ((equConfig.type != "change" && equConfig.type != "view")
						|| !rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_product({
//						closeCheck : true,
						closeAndStockCheck : true,
						hiddenId : 'productInfo' + goodsRowNum + '_cmp_product'
								+ rowNum,
						height : 250,
						event : {
							'clear' : function() {
								g.clearRowValue(rowNum);
							}
						},
						gridOptions : {
							showcheckbox : false,
							isTitle : true,
							event : {
								"row_dblclick" : (function(rowNum, rowData) {
									return function(e, row, productData) {
										selectAssetFn(g, rowNum, productData);
										var number = $("#productInfo"+ goodsRowNum+"_cmp_number" + rowNum).val();
										setProArr(goodsRowNum,rowNum,productData.id,number);
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
										countEquCost();
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
			display : '��������',
			name : 'productName',
			tclass : 'txt',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				// ���ҳ�治�ܸ�������
				if ((equConfig.type != "change" && equConfig.type != "view")
						|| !rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_product({
//						closeCheck : true,
						closeAndStockCheck : true,
						hiddenId : 'productInfo' + goodsRowNum + '_cmp_productId' + rowNum,
						nameCol : 'productName',
						height : 250,
						event : {
							'clear' : function() {
								g.clearRowValue(rowNum);
							}
						},
						gridOptions : {
							showcheckbox : false,
							isTitle : true,
							event : {
								"row_dblclick" : (function(rowNum, rowData) {
									return function(e, row, productData) {
										selectAssetFn(g, rowNum, productData);
										var number = $("#productInfo"+ goodsRowNum+"_cmp_number" + rowNum).val();
										setProArr(goodsRowNum,rowNum,productData.id,number);
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
										countEquCost();
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
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '��ƷId',
			name : 'conProductId',
			staticVal : goodsId,
			type : 'hidden'
		}, {
			display : '�ͺ�/�汾',
			name : 'productModel',
			readonly : true,
			tclass : 'readOnlyTxtItem'
		}, {
			display : '������',
			name : 'warrantyPeriod',
			readonly : true,
			tclass : 'readOnlyTxtItem'
		}, {
			display : '��λ',
			name : 'unitName',
			type : 'hidden'
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			value : 1,
			event : {
				blur : function(a,b,c) {
					var rowNum = $(this).data("rowNum");
					setProArr(goodsRowNum,rowNum,'',$(this).val(),'numChange');
					countEquCost();
				}
			}
		},{
			display : '�������',
			name : 'exeNum',
//			tclass : 'txtshort',
			type : 'statictext'
		}, {
			display : '������',
			name : 'arrivalPeriod',
			tclass : 'txtshort',
			value : 0,
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					// ���㽻����
					var conProductId = $("#productInfo" + goodsRowNum
							+ "_cmp_arrivalPeriod" + rowNum).val();
					var newDate = stringToDate($('#standardDate').val(),
							conProductId * 1)
					$('#standardDate').val(formatDate(newDate))
					countStander();
				}
			}
		}, {
			display : '��Ʒ����Id',
			name : 'deploy',
			type : 'hidden'
		}, {
			display : '���ϼ�������Id',
			name : 'license',
			type : 'hidden'
		}, {
			display : '��Ʒ��������Id',
			name : 'parentLicenseId',
			type : 'hidden'
		}, {
			name : 'licenseButton',
			display : '��������',
			type : 'statictext',
			process : function(html, rowData, $tr, g){
				if(equConfig.type == "view" && rowData.license==''){
					return '��';
				}else{
					return html;
				}
			},
			event : {
				'click' : function(e) {
					var rowNum = $(this).data("rowNum");
					// ��ȡ��Ʒ��������id
					var productLicense = $("#productInfo" + goodsRowNum
							+ "_cmp_parentLicenseId" + rowNum).val();

					// ��ȡ���ϼ�������id
					var thisLicense = $("#productInfo" + goodsRowNum
							+ "_cmp_license" + rowNum).val();
					if (equConfig.type!= 'view'&&(!thisLicense|| thisLicense == productLicense)) {
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
						if( thisLicense!=0 ){
							licenseUrl = '&licenseId='+thisLicense
						}
						url = "?model=yxlicense_license_tempKey&action=toSelectWin"+licenseUrl
						var returnValue = showModalDialog(url, '',
								"dialogWidth:1000px;dialogHeight:600px;");if (returnValue) {
							$("#productInfo" + goodsRowNum + "_cmp_license"
									+ rowNum).val(returnValue);
						}
					} else {
						showLicense(thisLicense);
					}
				}
			},
			html : '<input type="button"  value="��������"  class="txt_btn_a"  />'
		}, {
			display : '',
			name : 'configCode',
			type : 'hidden',
			staticVal : 1
		}, {
			name : 'delButton',
			display : '����',
			type : 'statictext',
			process : function(html, rowData, $tr, g){
				if(rowData && rowData.isDel=='0'){
					return '��';
				}else{
					if(!rowData)
					  return '��';
					else
					  return html;
				}
			},
			event : {
				'click' : function(e) {
					var i = $(this).data("rowNum");
					var isDel = $("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).val();
				   if(isDel == '1'){
				   	if (confirm('�Ƿ�ȷ���������ϣ�')) {
							$('#productInfo'+goodsRowNum+'_cmp_removeBn' + i).show();
					       	$("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#fffffb;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_productNo" + i).attr("style", "background:#fffffb;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_productName" + i).attr("style", "background:#fffffb;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_warrantyPeriod" + i).attr("style", "background:#fffffb;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_productModel" + i).attr("style", "background:#fffffb;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("style", "background:#fffffb;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("readonly", false);
						   	$("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("style", "background:#fffffb;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("readonly", false);
						   	$("#productInfo"+ goodsRowNum+"_cmp_delButton" + i).html("��");
						   	$("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).val("0");
							setProArr(goodsRowNum,i,$("#productInfo"+ goodsRowNum+"_cmp_productId" + i).val(),$("#productInfo"+ goodsRowNum+"_cmp_number" + i).val());
							countEquCost();
					 }
				   }
				}
			},
			html : '<input type="button"  value="����ɾ������"  class="txt_btn_a"  />'
		}],
		event : {
			beforeAddRow : beforeAddRow,
			addRow : function(e, rowNum, rowData, g, tr) {
				addGridChangeShow(g, rowNum, 'productName', rowData, tr);
				countStander()
			},
			beforeRemoveRow : function(e, rowNum, rowData, g) {
					if( equConfig.type=='change' && rowData){
						var equConfigData = $.ajax({
							type : "POST",
							url : "?model=projectmanagent_present_presentequ&action=getEquByParentEquId",
							data : {
								parentEquId:rowData.id,
								isDel:0,
								isTemp:0,
							},
							async : false
						}).responseText;
						var configExecutedNum = configIssuedShipNum = 0;
						if (equConfigData != "") {
							equConfigData = eval("(" + equConfigData + ")");
							$.each(equConfigData,function(i,item){
								configExecutedNum += parseInt(item.executedNum);
								configIssuedShipNum += parseInt(item.issuedShipNum);
							});
						}
						if(configExecutedNum > 0){
							alert("�����ϲ�������ѳ��⣬��ֱֹ��ɾ���������˻����̣�");
							g.isRemoveAction=false;
							return false;
						}else if(configIssuedShipNum > 0){
							alert("�����ϲ���������´﷢���ƻ�����ֱֹ��ɾ����");
							g.isRemoveAction=false;
							return false;
						}else if( rowData.executedNum>0 ){
							alert("�������Ѳ��ֳ��⣬��ֱֹ��ɾ���������˻����̣�");
							g.isRemoveAction=false;
							return false;
						}else if( rowData.issuedShipNum > 0){
							alert("�������Ѳ����´﷢���ƻ�����ֱֹ��ɾ����");
							g.isRemoveAction=false;
							return false;
						}
					}
			},
			reloadData : function(event, g,data) {
				if (equConfig.type != 'add') {// ��������id
				// �����������
					initProductConfigs(goodsId, goodsRowNum);
				}
				// �����׼������
				countStander()
				if(data){
					var rowCount = g.getCurRowNum();
					for (var i = 0; i < rowCount; i++) {
						var isDel = $("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).val();
						if(isDel == '1'){
							$('#productInfo'+goodsRowNum+'_cmp_removeBn' + i).hide();
						   	$("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#a1a3a6;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_productNo" + i).attr("style", "background:#a1a3a6;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_productName" + i).attr("style", "background:#a1a3a6;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_warrantyPeriod" + i).attr("style", "background:#a1a3a6;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_productModel" + i).attr("style", "background:#a1a3a6;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("style", "background:#a1a3a6;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("readonly", true);
						   	$("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("style", "background:#a1a3a6;");
						   	$("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("readonly", true);
						}else{
							setProArr(goodsRowNum,i,$("#productInfo"+ goodsRowNum+"_cmp_productId" + i).val(),$("#productInfo"+ goodsRowNum+"_cmp_number" + i).val());
						}
					}
					countEquCost();
				}
			},
			removeRow : function(goodsRowNum) {
				return function(e, rowNum, rowData, index, g) {
					var goodsId = $("#conProductId" + goodsRowNum).val();
					var $tr = $("#goodsDetail_" + goodsId + "_" + rowNum);
					if(rowData != undefined){
						setProArr(goodsRowNum,rowNum,rowData.productId,rowData.number,'del');
						if (confirm("ȷ��ɾ�����������ã�")) {
							if (equConfig.type == "change") {// ����Ǳ��
								$tr.find("tr").each(function(i) {
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
								// ɾ�������,������������Ӧ���������
								for(var i = 0;i<$tr.length;i++){
									setProArr(goodsRowNum,rowNum+"_detail"+i,'',0,'del');
								}
								$tr.remove();
							}
						} else {
							// var name = $tr.attr("name");
							$tr.find("tbody>tr").each(function() {
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
					}
					countStander();
					countEquCost();
					arrivalPeriod=g.getCmpByRowAndCol(rowNum,"arrivalPeriod").val();
					countStanderAfterRemove(arrivalPeriod);
				}
			}(goodsRowNum)
		}
	});
}

// ͳ�����ϸ���
function countEquCost(){
	// console.log(proNumArr);
	// console.log(proArr);
	$.ajax({
		type : "POST",
		url : "?model=projectmanagent_present_presentequ&action=getEquCost",
		data : {
			"equArr" : proNumArr
		},
		async : false,
		success : function(data) {
			if (data != "") {
				// console.log(eval("("+data+")"));
				var backData = eval("("+data+")");
				if(backData['msg'] == 'ok' && backData['data'] != []){
					$("#costEstimates_view").html(backData['data']['costEstimatesTax']);
					$("#costEstimates").val(backData['data']['costEstimates']);
					$("#costEstimatesTax").val(backData['data']['costEstimatesTax']);
				}else{
					$("#costEstimates_view").html(0.00);
					$("#costEstimates").val(0.00);
					$("#costEstimatesTax").val(0.00);
				}
			}

		}
	});
}
function setProArr(productRowNum,equRowNum,equProductId,equNum,act){
	var finalArr = [];
	if(act == 'del'){
		if(productRowNum == 'null'){
			proArr["product_equ"+equRowNum]['number'] = 0;
		}else{
			proArr["product"+productRowNum+"_equ"+equRowNum]['number'] = 0;
		}
	}else if(act == 'numChange'){
		if(productRowNum == 'null'){
			proArr["product_equ"+equRowNum]['number'] = equNum;
		}else{
			proArr["product"+productRowNum+"_equ"+equRowNum]['number'] = equNum;
		}
	}else{
		if(productRowNum == 'null'){
			proArr["product_equ"+equRowNum] = {"productId" : equProductId,"number":equNum};
		}else{
			proArr["product"+productRowNum+"_equ"+equRowNum] = {"productId" : equProductId,"number":equNum};
		}
	}

	$.each(proArr,function(){
		if(parseInt(this.number) > 0){
			finalArr.push(this);
		}
	});

	proNumArr = finalArr;
}

$(function() {
	/**
	 * ��֤��Ϣ
	 */
	 validate({},{disableButton:true});
});

function getNoGoodsProducts(newRowNum) {
	var docType=$('#docType').val();
	// ��������
	var param = {};
	var changeView = $("#changeView").val();
	param.presentId = $('#presentId').val();
//	param.notDel = 1;
	if (changeView == '1') {
		param.temp = 1;
	}else if($("#isChangeTip").val() == '1'){
		param.temp = 1;
		delete param.isTemp;
	}else {
		param.isTemp = 0;
	}

	if (equConfig.type == "view" && $("#isShowDel").val() == "true") {
		delete param.notDel;
	}
	$("#productInfo").yxeditgrid({
		objName : 'present[detail][' + newRowNum + ']',
		tableClass : 'form_in_table',
		url : '?model=projectmanagent_present_presentequ&action=getNoProductEqu',
		param : param,
		type : equConfig.type,
		// isAddOneRow:false,
		// isAdd : false,
		colModel : [{
			display : 'isDel',
			name : 'isDel',
			type : 'hidden'
		},{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '���ϱ��',
			name : 'productNo',
			tclass : 'txtmiddle',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				if ((equConfig.type != "change" && equConfig.type != "view")
						|| !rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_product({
//						closeCheck : true,
						closeAndStockCheck : true,
						hiddenId : 'productInfo_cmp_productId' + rowNum,
						height : 250,
						event : {
							'clear' : function() {
								g.clearRowValue(rowNum);
							}
						},
						gridOptions : {
							showcheckbox : false,
							isTitle : true,
							event : {
								"row_dblclick" : (function(rowNum, rowData) {
									return function(e, row, productData) {
										var number = $("#productInfo_cmp_number" + rowNum).val();
										setProArr('null',rowNum,productData.id,number);

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
										var num = getInventoryInfos(docType,productData.id)
										var $exeNum = g.getCmpByRowAndCol(rowNum, 'exeNum');// ����id
										$exeNum.html(num);
										countStander();
										countEquCost();
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
			display : '��������',
			name : 'productName',
			tclass : 'txt',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				// ���ҳ�治�ܸ�������
				if ((equConfig.type != "change" && equConfig.type != "view")
						|| !rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_product({
//						closeCheck : true,
						closeAndStockCheck : true,
						hiddenId : 'productInfo_cmp_productId' + rowNum,
						nameCol : 'productName',
						height : 250,
						event : {
							'clear' : function() {
								g.clearRowValue(rowNum);
							}
						},
						gridOptions : {
							showcheckbox : false,
							isTitle : true,
							event : {
								"row_dblclick" : (function(rowNum, rowData) {
									return function(e, row, productData) {
										var number = $("#productInfo_cmp_number" + rowNum).val();
										setProArr('null',rowNum,productData.id,number);

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
										var num = getInventoryInfos(docType,productData.id)
										var $exeNum = g.getCmpByRowAndCol(rowNum, 'exeNum');// ����id
										$exeNum.html(num);
										countStander();
										countEquCost();
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
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '�ͺ�/�汾',
			name : 'productModel',
			readonly : true,
			tclass : 'readOnlyTxtItem'
		}, {
			display : '��λ',
			name : 'unitName',
			type : 'hidden'
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			value : 1,
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					setProArr('null',rowNum,'',$(this).val(),'numChange');
					countEquCost();
				}
			}
		}, {
			display : '�������',
			name : 'exeNum',
//			tclass : 'txtshort',
			type : 'statictext'
		}, {
			display : '������',
			readonly : true,
			name : 'warrantyPeriod',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '������',
			name : 'arrivalPeriod',
			tclass : 'txtshort',
			event : {
				blur : function() {
					countStander();
				}
			}
		}, {
			display : '���ϼ�������Id',
			name : 'license',
			type : 'hidden'
		}, {
			name : 'licenseButton',
			display : '��������',
			type : 'statictext',
			process : function(html, rowData, $tr, g){
				if(equConfig.type == "view" && rowData.license==''){
					return '��';
				}else{
					return html;
				}
			},
			event : {
				'click' : function(e) {
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
			html : '<input type="button"  value="��������"  class="txt_btn_a"  />'
		}, {
			display : '',
			name : 'configCode',
			type : 'hidden',
			staticVal : 1
		}, {
			name : 'delButton',
			display : '����',
			type : 'statictext',
			process : function(html,rowData, $tr, g){
					return html;
			},
			event : {
				'click' : function(e) {
					var i = $(this).data("rowNum");
					var isDel = $("#productInfo_cmp_isDel" + i).val();
				   if(isDel == '1'){
				   	if (confirm('�Ƿ�ȷ���������ϣ�')) {
							$('#productInfo_cmp_removeBn' + i).show();
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
							setProArr('null',i,$("#productInfo_cmp_productId" + i).val(),$("#productInfo_cmp_number" + i).val());
							countEquCost();
					 }
				   }
				}
			},
			html : '<input type="button"  value="����ɾ������"  class="txt_btn_a"  />'
		}],
		event : {
			beforeAddRow : beforeAddRow,
			addRow : function(e, rowNum, rowData, g, tr) {
				addGridChangeShow(g, rowNum, 'productName', rowData, tr);
			},
			beforeRemoveRow : function(e, rowNum, rowData, g) {
					if( equConfig.type=='change' && rowData){
						if( rowData.executedNum>0 ){
							alert("�������Ѳ��ֳ��⣬��ֱֹ��ɾ���������˻����̣�");
							g.isRemoveAction=false;
							return false;
						}else if( rowData.issuedShipNum>0 ){
							alert("�������Ѳ����´﷢���ƻ�����ֱֹ��ɾ����");
							g.isRemoveAction=false;
							return false;
						}
					}
			},
			removeRow : function(e, rowNum, rowData, index, g){
				countStander();
				arrivalPeriod=g.getCmpByRowAndCol(rowNum,"arrivalPeriod").val();
				countStanderAfterRemove(arrivalPeriod);
				if(rowData != undefined){
					setProArr('null',rowNum,rowData.productId,rowData.number,'del');
					countEquCost();
				}
			},
			reloadData : function(event, g,data) {
				if(data){
					var rowCount = g.getCurRowNum();
					for (var i = 0; i < rowCount; i++) {
						var isDel = $("#productInfo_cmp_isDel" + i).val();
						if(isDel == '1'){
							$('#productInfo_cmp_removeBn' + i).hide();
						   $("#productInfo_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_productNo" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_productName" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_warrantyPeriod" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_productModel" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_number" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_number" + i).attr("readonly", true);
						   $("#productInfo_cmp_arrivalPeriod" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_arrivalPeriod" + i).attr("readonly", true);
						}else{
							setProArr('null',i,$("#productInfo_cmp_productId" + i).val(),$("#productInfo_cmp_number" + i).val());
						}
					}
					countEquCost();
				}
			}
		}

			// event : {
			// 'reloadData' : function(e){
			// alert('reloadData')
			// // initCacheInfo();
			// },
			// 'removeRow' : function(e, rowNum, rowData){
			// alert('removeRow')
			// }
			//		}
	});
}
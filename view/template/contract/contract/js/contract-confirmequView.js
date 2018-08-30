
// ����ȷ�ϲ鿴
var changeDetailObj = {};
$(function() {
	equConfig.type = "view";
	var rowNum = $('#rowNum').val();
	var contractId = $("#contractId").val();
	var key = "detailId";
	for (var i = 1; i <= rowNum; i++) {
		getGoodsProducts(i);
	}
	var newEquRowNum = rowNum*1+1;
	getNoGoodsProducts(newEquRowNum);

});



/**
 *
 * ����ȷ�Ϲ���js(�������༭��������鿴)
 */
var equConfig = {
	type : ''// add edit change view
};// ��������


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
function initProductConfigs(goodsId, goodsRowNum,contractId) {
	// ���������
	if(goodsId == '0'){//���ڽ���ת��������
		var $productGrid = $("#productInfo");
	}else{
		var $productGrid = $("#productInfo" + goodsRowNum);
	}
	// thisGrid.html("");
	var isSubAppChange = $("#isSubAppChange").val();
	if(isSubAppChange == '1'){
	    var equIdObj = $productGrid.yxeditgrid("getCmpByCol", "originalId");// �嵥id
	    var equIdObjA = $productGrid.yxeditgrid("getCmpByCol", "id");// �嵥id
	    var equIdArr = [];//��Ա��ҵ�����������Ѿ���Ⱦ����������������е�id
	}else{
	    var equIdObj = $productGrid.yxeditgrid("getCmpByCol", "id");// �嵥id
	}
	var productObj = $productGrid.yxeditgrid("getCmpByCol", "productId");// ����id
	var numObj = $productGrid.yxeditgrid("getCmpByCol", "number");// ��������
	var equSize = equIdObj.size();
	productObj.each(function(i, n) {
		var productId = this.value;
		if (equSize > 0) {
			if(equIdObj[i].value != '0' && equIdObj[i].value != ''){
			   getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
					i, equIdObj[i].value,false,contractId);
			   if(isSubAppChange == '1'){
				   equIdArr.push(this.id);
			   }
			}
		} else {
			getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
					i,"",false,contractId);
		}

	});
    //��ʾ���������-������bug�������Ż�
	if(isSubAppChange == '1'){
		var equSizeA = equIdObjA.size();
		productObj.each(function(i, n) {
			if($.inArray(this.id,equIdArr) == -1){//û����Ⱦ����������ϲ�����Ⱦ
				var productId = this.value;
				if (equSizeA > 0) {
					if(equIdObjA[i].value != '0'){
					   getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
							i, equIdObjA[i].value,false,contractId);
					}
				} else {
					getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
							i,"",false,contractId);
				}
			}
		});
	}
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
		productRowNum, equId, ifGetPro,contractId) {
	var url = "";
	var param = {};
	// �ж��Ǳ༭�����������
	if (equConfig.type != 'add' && !ifGetPro && equId != '') {
		param.parentEquId = equId;
		param.contractId = contractId;
		if (equConfig.type != "view" || $("#isShowDel").val() == "false") {
			param.isDel = 0;
//			param.isTemp = 1;  //edit by zzx 2014��1��11�� 13:59:04 �����ʾ������
		}
		url = "?model=contract_contract_equ&action=listJson";
	} else {
		param.productId = productId;
		url = "?model=stock_productinfo_configuration&action=getAccessForPro";
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
	if(strTrim(data)){
		var obj = eval("(" + data + ")");
		if (obj.length) {
			var $table = $("<table class='form_in_table' id='contractequ_$id'></table>");
			var $td = $("<td class='innerTd' colspan='20'></td>");
			var $tr = $("<tr id='goodsDetail_" + goodsId + "_" + productRowNum
					+ "'></tr>")
			$tr.append($td)
			$td.append($table);

			var $th = $('<tr class="main_tr_header1"><th width="30px"></th><th width="35px">���</th><th>�������</th>'
					+ '<th>�������</th><th>�汾�ͺ�</th><th>����</th><th>������</th><th>������</th></tr>');
			$table.append($th);
			if(goodsId == '0'){//���ڽ���ת��������
				var configtr = $("#productInfo table tr[rowNum="
						+ productRowNum + "]");
			}else{
				var configtr = $("#productInfo" + goodsRowNum + " table tr[rowNum="
						+ productRowNum + "]");
			}
			configtr.after($tr);
			var nametr = 'contract[detail][' + goodsRowNum + ']';
			$tr.attr("name", nametr);
			for (var i = 0; i < obj.length; i++) {
				var index = i + (productRowNum + 1) * 100;
				var name = 'contract[detail][' + goodsRowNum + '][' + index + ']';
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
									+ "'><img src='images/removeline.png' onclick='delConfig(this)' title='ɾ����'></td>");
				} else {
					$ctr.append("<td></td>");
				}
				$ctr.append("<td>" + (i + 1) + "</td>");

				var hide = '<input type="hidden" name="' + name
						+ '[parentRowNum]" value="'
						+ (productId + "_" + productRowNum)
						+ '"/><input type="hidden" name="' + name
						+ '[productId]" value="' + p.productId
						+ '"/><input type="hidden" name="' + name
						+ '[productCode]"  value="' + p.productCode
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

				var td = $("<td>" + p.productCode + "</td>");
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
							+ '[number]" value="' + num + '"/>');
				}
				var $td = $("<td></td>");
				$td.append(cnum);
				$ctr.append($td);
				var cwarranty = p.warranty;
				if (equConfig.type != "view") {
					cwarranty = $('<input type="text" class="txtshort"  name="'
							+ name + '[warranty]" value="' + p.warranty + '"/>');
				}
				var $td = $("<td></td>");
				$td.append(cwarranty);
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
				countStander();
			}
		};
	}
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
	document.getElementById('form1').action = "index1.php?model=contract_contract_equ&action=equEdit&act=audit";
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
/** ********************ɾ�����************************ */
function delConfig(obj) {
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
		var $productCode = g.getCmpByRowAndCol(rowNum, 'productCode');// ���ϱ���
		$productCode.val(rowData.productCode);
		var $productId = g.getCmpByRowAndCol(rowNum, 'productId');// ����id
		$productId.val(rowData.id);
		var num = getInventoryInfos(docType,rowData.id)
		var $exeNum = g.getCmpByRowAndCol(rowNum, 'exeNum');// ����id
		$exeNum.html(num);
		var $warrantyPeriod = g.getCmpByRowAndCol(rowNum, 'warrantyPeriod');// ������
		$warrantyPeriod.val(rowData.warranty);
	};
//	var flag=false;
//	alert(url)
//	if(!url){
//		var flag=true;
//	}
	url = url ? url : '?model=contract_contract_equ&action=getConEqu';// ��Ʒ����������Դ

	var isSubAppChange = $("#isSubAppChange").val();
	if(isSubAppChange == '1'){
      var goodsId = $("#originalId" + goodsRowNum).val();
      if(goodsId == '0'){
        var goodsId = $("#conProductId" + goodsRowNum).val();
      }
	}else{
	  var goodsId = $("#conProductId" + goodsRowNum).val();
	}
	var linkId = $("#linkId").val();
	var number = $("#number" + goodsRowNum).val();
	var changeView = $("#changeView").val();
	var contractId = $("#contractId").val();
   if(isSubAppChange == '1'){
     	var param = {
     	contractId : contractId,
		conProductId : goodsId,
		parentEquId : 0,
//		isTemp : 1,
		isDel : 0
//		,number : number
	};
   }else{
   	  var param = {
   	  	contractId : contractId,
		conProductId : goodsId,
		parentEquId : 0,
		isTemp : 0,
		isDel : 0
//		,number : number
	};
   }
//	if(flag){
//		alert(2)
//		delete param.number;
//	}
//	if (linkId) {
//		param.linkId = linkId;
//	}
	if (changeView) {
		param.temp = 1;
		delete param.isTemp;
	}

	if (equConfig.type == "view" && $("#isShowDel").val() == "true") {
		delete param.isDel;
	}
	$("#productInfo" + goodsRowNum).yxeditgrid({
		objName : 'contract[detail][' + goodsRowNum + ']',
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
		},{
			display : 'originalId',
			name : 'originalId',
			type : 'hidden'
		}, {
			display : '���ϱ��',
			name : 'productCode',
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
						hiddenId : 'productInfo' + goodsRowNum + '_cmp_productId'
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
													conProId, true,contractId);
										}
										countStander()
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
						hiddenId : 'productInfo' + goodsRowNum + '_cmp_productId'
								+ rowNum,
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
													conProId, true,contractId);
										}
										countStander()
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
			tclass : 'readOnlyTxtNormal'
		}, {
			display : '������',
			name : 'warrantyPeriod',
			readonly : true,
			tclass : 'txtshort'
		}, {
			display : '��λ',
			name : 'unitName',
			type : 'hidden'
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			value : 1
		}, {
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
//			display : '����',
//			name : 'price'
//		}, {
//			display : '�ܽ��',
//			name : 'money'
//		}, {
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
					       $("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_productCode" + i).attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_productName" + i).attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_warrantyPeriod" + i).attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_productModel" + i).attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("readonly", false);
						   $("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("readonly", false);
						   $("#productInfo"+ goodsRowNum+"_cmp_delButton" + i).html("��");
						   $("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).val("0");
					 }
				   }
				}
			},
			html : '<input type="button"  value="����ɾ������"  class="txt_btn_a"  />'
		}],
		event : {
			addRow : function(e, rowNum, rowData, g, tr) {
				countStander()
			},
			beforeRemoveRow : function(e, rowNum, rowData, g) {
					if( equConfig.type=='change' && rowData){
						if( rowData.executedNum>0 ){
							alert("�������Ѳ��ֳ��⣬��ֱֹ��ɾ���������˻����̣�");
							g.isRemoveAction=false;
							return false;
						}
					}
			},
			reloadData : function(event, g,data) {
				var deploy = $("#deploy" + goodsRowNum).val();
				if (equConfig.type == 'add') {
					g.setColValue('deploy', deploy);
				}else{

				// �����������
				initProductConfigs(goodsId, goodsRowNum,contractId);
				}
				// �����׼������
				countStander()
				if(data){
					var rowCount = g.getCurRowNum();
					for (var i = 0; i < rowCount; i++) {
						var isDel = $("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).val();
						if(isDel == '1'){
						   $("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_productCode" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_productName" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_warrantyPeriod" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_productModel" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("readonly", true);
						   $("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("readonly", true);
						}
					}
				}
			},
			removeRow : function(goodsRowNum) {
				return function(e, rowNum, rowData, index, g) {
					var goodsId = $("#conProductId" + goodsRowNum).val();
					var $tr = $("#goodsDetail_" + goodsId + "_" + rowNum);
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
					countStander();
					arrivalPeriod=g.getCmpByRowAndCol(rowNum,"arrivalPeriod").val();
					countStanderAfterRemove(arrivalPeriod)
				}
			}(goodsRowNum)
		}
	});
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
	var contractId = $("#contractId").val();
	param.contractId = contractId;
	param.parentEquId = 0;
//	param.linkId = $('#linkId').val();
//	param.notDel = 1;
	param.isDel = 0;
	var isSubAppChange = $("#isSubAppChange").val();
//	if (isSubAppChange == '1') {
//		param.temp = 1;
//	} else {
//		param.isTemp = 0;
//	}
	if (equConfig.type == "view" && $("#isShowDel").val() == "true") {
//		delete param.notDel;
		delete param.isDel;
	}
	$("#productInfo").yxeditgrid({
		objName : 'contract[detail][' + newRowNum + ']',
		tableClass : 'form_in_table',
		url : '?model=contract_contract_equ&action=getNoProductEqu',
		param : param,
		type : equConfig.type,
		// isAddOneRow:false,
		// isAdd : false,
		colModel : [{
            display : '������Ʒ',
            name : 'conProduct',
            tclass : 'txt'
        },{
			display : 'isDel',
			name : 'isDel',
			type : 'hidden'
		},{
			display : 'istemp',
			name : 'isTemp',
			type : 'hidden'
		}, {
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'originalId',
			name : 'originalId',
			type : 'hidden'
		}, {
			display : '���ϱ��',
			name : 'productCode',
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
										// if (confirm("�Ƿ�������������")) {
										// var productNum = productData
										// ? productData.number
										// : 1;
										// var conProId = productData
										// ? productData.productId
										// : 0;
										// }
										countStander()
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
										g.getCmpByRowAndCol(rowNum,
												'productCode')
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
										// if (confirm("�Ƿ�������������")) {
										// var productNum = productData
										// ? productData.number
										// : 1;
										// var conProId = productData
										// ? productData.productId
										// : 0;
										// }
										countStander()
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
			tclass : 'readOnlyTxtNormal'
		}, {
			display : '��λ',
			name : 'unitName',
			type : 'hidden'
		}, {
//			display : '����',
//			name : 'price'
//		}, {
//			display : '�ܽ��',
//			name : 'money'
//		}, {
			display : '������',
			readonly : true,
			name : 'warrantyPeriod'
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			value : 1,
			event : {
				blur : function() {
				}
			}
		}, {
			display : '�������',
			name : 'exeNum',
//			tclass : 'txtshort',
			type : 'statictext'
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
						showLicense(thisLicense)
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
					var isDel = $("#productInfo_cmp_isDel" + i).val();
				   if(isDel == '1'){
				   	if (confirm('�Ƿ�ȷ���������ϣ�')) {
					       $("#productInfo_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#fffffb;");
						   $("#productInfo_cmp_productCode" + i).attr("style", "background:#fffffb;");
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
			html : '<input type="button"  value="����ɾ������"  class="txt_btn_a"  />'
		}],
		event : {
			addRow : function(e, rowNum, rowData, g, tr) {
				countStander()
			},
			removeRow : function(e, rowNum, rowData, index, g){
				countStander();
				arrivalPeriod=g.getCmpByRowAndCol(rowNum,"arrivalPeriod").val();
				countStanderAfterRemove(arrivalPeriod)
			},
			reloadData : function(event, g,data) {
				var deploy = $("#deploy" + newRowNum).val();
				if (equConfig.type == 'add') {
					g.setColValue('deploy', deploy);
				}else{
					// �����������
					initProductConfigs('0',newRowNum,contractId);
				}
				// �����׼������
				countStander();
				if(data){
					var rowCount = g.getCurRowNum();
					for (var i = 0; i < rowCount; i++) {
						var isDel = $("#productInfo_cmp_isDel" + i).val();
						if(isDel == '1'){
						   $("#productInfo_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_productCode" + i).attr("style", "background:#a1a3a6;");
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

function toBack() {
	document.getElementById('form1').action = "index1.php?model=contract_contract_contract&action=confirmEqu&act=back";
}

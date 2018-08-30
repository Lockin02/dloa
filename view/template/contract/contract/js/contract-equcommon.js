
/**
 *
 * ����ȷ�Ϲ���js(�������༭��������鿴)
 */
var equConfig = {
	type : ''// add edit change view
};// ��������

$(function() {
	//������������id���飬���ڴ�������ɱ�
	specialProIdArr = $("#specialProId").val().split(",");
});

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
function initProductConfigs(goodsId,goodsRowNum,contractId) {
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
	var alreadyDelObj = $productGrid.yxeditgrid("getCmpByCol", "alreadyDel");// ɾ���Ŀ�����������
	var equSize = equIdObj.size();
	productObj.each(function(i, n) {
		var productId = this.value;
		if (equSize > 0) {
			if(equIdObj[i].value != '0' && equIdObj[i].value != ''){
			   getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
					i, equIdObj[i].value,false,contractId,alreadyDelObj[i].value);
			   if(isSubAppChange == '1'){
				   equIdArr.push(this.id);
			   }
			}
		} else {
			getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
					i,"",false,contractId,alreadyDelObj[i].value);
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
							i, equIdObjA[i].value,false,contractId,alreadyDelObj[i].value);
					}
				} else {
					getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
							i,"",false,contractId,alreadyDelObj[i].value);
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
		productRowNum, equId, ifGetPro,contractId,isDel) {
	var url = "";
	var param = {};
	// �ж��Ǳ༭�����������
	if (equConfig.type != 'add' && !ifGetPro) {
		param.parentEquId = equId;
		param.contractId = contractId;
		if (equConfig.type != "view" || $("#isShowDel").val() == "false") {
			param.isDel = typeof(isDel) == undefined ? 0 : isDel;
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
			var $table = $("<table class='form_in_table' id='contractequ_" + goodsId + "_" + productRowNum + "'></table>");
			var $td = $("<td class='innerTd' colspan='20'></td>");
			var $tr = $("<tr id='goodsDetail_" + goodsId + "_" + productRowNum
					+ "'></tr>")
			$tr.append($td)
			$td.append($table);

			var $th = $('<tr class="main_tr_header1"><th width="30px"><img src="images/add_item.png" onclick="addConfig(' + goodsId + ',' + productRowNum + ','+ goodsRowNum + ','+ productId + ')" title="�����"></th><th width="35px">���</th><th>�������</th>'
					+ '<th>�������</th><th>�汾�ͺ�</th><th>����</th><th>������</th><th>������</th><th>�ɱ�</th></tr>');
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
                var executedN = parseInt(p.executedNum);//�ѷ�������
				var shipedN = parseInt(p.issuedShipNum);//�´﷢���ƻ�����
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
					$ctr.append("<td name='"
									+ name
									+ "'><img src='images/removeline.png' onclick='delConfig(this," + goodsId + "," + productRowNum + ","+ executedN +","+shipedN+");' title='ɾ����'></td>");
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
						+ '[unitName]"  value="' + p.unitName
						+ '"/><input type="hidden" name="' + name
						+ '[originalId]"  value="' + p.originalId
						+ '"/> ';
				if (equConfig.type != 'add') {
					hide += '<input type="hidden" name="' + name + '[id]" value="'
							+ (p.id ? p.id : "") + '"/>';
				}
				if(goodsId == '0'){//���ڽ���ת��������
					hide += '<input type="hidden" name="' + name + '[isBorrowToorder]" value="1"/>';
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
                var excdNum = executedN;
				if (equConfig.type != "view") {
					cnum = $('<input type="text" class="txtshort"  name="' + name
							+ '[number]" value="' + num + '" onchange="chkNumber('+ excdNum +',' + goodsRowNum + ','+ index +',' + goodsId + ','+ productRowNum +', this)" />');
				}
				var $td = $("<td></td>");
				$td.append(cnum);
				$ctr.append($td);
				var cwarranty = p.warranty;
				if (equConfig.type != "view") {
					cwarranty = $('<input type="text" class="txtshort"  name="'
							+ name + '[warrantyPeriod]" value="' + p.warranty + '"/>');
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
				var price = 0;
				if (p.configNum) {
					price = p.priCost;
				} else if (p.number) {
					price = p.price;
				}
				var cost = "";
				if (equConfig.type != "view") {
					cost = $('<input type="text" class="readOnlyTxtShort"  name="' + name
							+ '[cost]" value="' + num * price + '" readonly/>');
				}
				var $td = $("<td></td>");
				$td.append(cost);
				$ctr.append($td);
				var price = $('<input type="hidden" name="' + name
						+ '[price]" value="' + price + '" />');
				$td.append(price);
				$ctr.append($td);
				$table.append($ctr);
				countStander();
				countEquCost(goodsId,productRowNum);
			}
		}
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
		var thisNumber = $("#" + beforeStr + "number" + rowNum).val();
		// alert(thisNumber)

		// ��ȡ��ǰ����
		var thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
		// alert(thisPrice)

		// ���㱾�н�� - ����˰
		var thisMoney = accMul(thisNumber, thisPrice, 2);
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
	var cmp_arrivalPeriod = [];
	var inner_arrivalPeriod = [];
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
	var cmp_arrivalPeriod = [];
	var inner_arrivalPeriod = [];
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
				cmp_arrivalPeriod.splice(i,1);
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
function delConfig(obj,goodsId,productRowNum,exeNum,shipedN) {
    if(exeNum > 0){//��������ѷ���������ɾ����ֻ�ܸ�����
        alert("������ѷ���"+exeNum+"��,����ɾ��,ֻ�ܸ�������");
    }else if(shipedN > 0) {
		alert("��������´﷢���ƻ�"+shipedN+"��,����ɾ��,ֻ�ܸ�������");
    }else{
        if (confirm('ȷ��Ҫɾ�����У�')) {
            var $td = $(obj).parent();
            var name = $td.attr("name");
            var $tr = $td.parent();
            $tr.append("<input type='hidden' name='" + name
                    + "[isDelTag]' value='1'>");
            $tr.hide();
            var rowNum = $tr.parent().children(":visible").size();
            if (rowNum == 1) {
                $tr.parent().hide();
            }
            countStander();
            countEquCost(goodsId,productRowNum);
			cal();
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
		parentEquId : 0
//		isTemp : 1,
//		isDel : 0
//		,number : number
	};
   }else{
   	  var param = {
   	  	contractId : contractId,
		conProductId : goodsId,
		parentEquId : 0,
		isTemp : 0
//		isDel : 0
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

    // todo ��Ʒ��Ӧ����
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
		},{
			display : 'originalTempId',
			name : 'originalTempId',
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
										countStander();
										if(specialProIdArr.indexOf(productData.id) != -1){
											// $("#productInfo"+ goodsRowNum+"_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
											// 	.attr("readonly",false).val('10');
											$("#productInfo"+ goodsRowNum+"_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
												.attr("readonly",false).val('0');
											countEquCost(goodsId,rowNum);
										}
                                        // �������ϼ���
                                        cal();
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
										countStander();
										if(specialProIdArr.indexOf(productData.id) != -1){
											// $("#productInfo"+ goodsRowNum+"_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
											// 	.attr("readonly",false).val(10);
											$("#productInfo"+ goodsRowNum+"_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
												.attr("readonly",false).val(0);
											countEquCost(goodsId,rowNum);
										}
                                        // �������ϼ���
                                        cal();
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
		},{
            display : '��ִ������',
            name : 'executedNum',
            type : 'hidden'
        },{
			display : '�´﷢���ƻ�����',
			name : 'issuedShipNum',
			type : 'hidden'
		},{
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			value : 1,
			event : {
				change : function(e){
                    var selfId = $(this).attr('id');
                    var exeNumId =selfId.replace('number','executedNum');
                    var exeNum = parseInt($('#'+exeNumId).val());//��ִ������
					if(!isNum($(this).val())){
						alert("������������");
						$(this).val(1);
						// �������ϼ���
						cal();
						return false;
					}else if( parseInt($(this).val()) < exeNum ){
                        alert("�����������������ѷ�������");
                        $(this).val(exeNum);
						// �������ϼ���
						cal();
                        return false;
                    }else{
						// �������ϼ���
						cal();
					}
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
			value : 0,
			event : {
				blur : function() {
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
						   $("#productInfo"+ goodsRowNum+"_cmp_originalTempId" + i).val($("#productInfo"+ goodsRowNum+"_cmp_id" + i).val());
						   // �����������
						   if (confirm('�Ƿ��������������ã�')) {
							   initProductConfigs(goodsId, goodsRowNum,contractId);
						   }
					   }
				   }
				}
			},
			html : '<input type="button"  value="����ɾ������"  class="txt_btn_a"  />'
		}, {
			display : '�ɱ�������%��',
			name : 'proportion',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			process : function($input, rowData) {
				$input.val('');
				if (equConfig.type != "view" || !rowData) {
					var proportionVal = (!rowData || rowData['proportion'] == '' || rowData['proportion'] < 0)? '0' : rowData['proportion'];
					var rowNum = $input.data("rowNum");
					var grid = $input.data("grid");
					var productId = grid.getCmpByRowAndCol(rowNum, 'productId').val();
					if(specialProIdArr.indexOf(productId) != -1){
						$input.attr("style", "background:#fffffb;").attr("readonly",false).val(proportionVal);
					}
				}
			},
			event : {
				change : function(e){
					if(!isNum($(this).val())){
						if($(this).val() != 0){
							alert("������������");
							$(this).val(0);
							return false;
						}
					}
					var rowNum = $(this).data('rowNum');// �ڼ���
					var grid = $(this).data('grid');// ������
					var productId = grid.getCmpByRowAndCol(rowNum, 'productId').val();
					if(specialProIdArr.indexOf(productId) != -1){
						countEquCost(goodsId,rowNum);
                        // �������ϼ���
                        cal();
					}
				}
			}
		}, {
			display : '�ɱ�����',
			name : 'costEstimate',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '',
			name : 'alreadyDel',
			type : 'hidden',
			staticVal : '0'
		}],
		event : {
			beforeAddRow : beforeAddRow,
			addRow : function(e, rowNum, rowData, g, tr) {
				addGridChangeShow(g, rowNum, 'productName', rowData, tr);
				countStander();
			},
//			beforeRemoveRow : function(goodsRowNum){
//				return function(e, rowNum, rowData, index, g) {
//			},
			beforeRemoveRow : function(e, rowNum, rowData, g) {
                if( equConfig.type=='change' && rowData){
					$.ajax({
						type : "POST",
						url : "?model=contract_contract_equ&action=getRelativeEqu",
						data : {
							"isTemp" : 0,
							"isDel" : 0,
							"advCondition" : " AND parentEquId='"+rowData.id+"'"

						},
						async : false,
						success : function(data) {
							var configExecutedNum = configIssuedShipNum = 0;
							if (data != "") {
								data = eval("(" + data + ")");
								$.each(data,function(i,item){
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
							}else if( rowData.executedNum>0 ){//������ѳ��ⲻ��ɾ
								alert("�������Ѳ��ֳ��⣬��ֱֹ��ɾ���������˻����̣�");
								g.isRemoveAction=false;
								return false;
							}else if(rowData.issuedShipNum > 0){//���û���ѳ���,�����з����ƻ���Ҳ����ɾ
								alert("���������´﷢���ƻ�����ֱֹ��ɾ����");
								g.isRemoveAction=false;
								return false;
							}

						}
					});
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
						   $("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("style", "background:#a1a3a6;").attr("readonly", true);
						   $("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("style", "background:#a1a3a6;").attr("readonly", true);
						   $("#productInfo"+ goodsRowNum+"_cmp_alreadyDel" + i).val("1")
						}
					}

                    // �������ϼ���
                    cal();
				}
			},
			removeRow : function(goodsRowNum) {
				return function(e, rowNum, rowData, index, g) {
					if (equConfig.type == "change"){
					  var goodsId = $("#originalId" + goodsRowNum).val();
					  if(goodsId == '0' || goodsId == ''){
						  var goodsId = $("#conProductId" + goodsRowNum).val();
					  }
					}else{
					  var goodsId = $("#conProductId" + goodsRowNum).val();
					}
					var $tr = $("#goodsDetail_" + goodsId + "_" + rowNum);
					//���뽻��ȷ�ϣ�ɾ�����Ϻ�Ĭ��ɾ���������
//					if (confirm("ȷ��ɾ�����������ã�")) {
						if (equConfig.type == "change" || equConfig.type == "edit") {// ����Ǳ�����Ǳ༭
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
//					} else {
//						// var name = $tr.attr("name");
//						$tr.find("tbody>tr").each(function() {
//							var $td = $(this).find("td");
//							var name = $td.attr("name");
//							if (name) {
//								var $configCode = $("<input type='hidden' name='"
//										+ name
//										+ "[configCode]' value='1'></input>");
//
//								if ($td.size() > 0) {
//									$($td.get(0)).append($configCode);
//								}
//							}
//						});
//					}
					countStander();
					arrivalPeriod=g.getCmpByRowAndCol(rowNum,"arrivalPeriod").val();
					countStanderAfterRemove(arrivalPeriod)

                    // �������ϼ���
                    cal('del',g.el['selector'],index);
				}
			}(goodsRowNum)
		}
	});
}

// �ݽ�ѭ����ȡ������������Ϣ
var jsyequ_arr = [];
function cacheJsyEqu(n,id_str,num_str,conProductId_str,cost_str,isdel_str,product_Count,product_Num){
	var conId_str = id_str.replace("productId","id");
	var originalId_str = id_str.replace("productId","originalId");
	var isNotDel = true;
	if(typeof isdel_str != 'undefined'){
		isNotDel = ( $(isdel_str+n).val() != 1 );
	}
	// ���ʱɾ�������ݲ�ȡ
	if(isNotDel){
		// ��ɾ�������ݲ�ȡ
		if(typeof $(id_str+n).val() != 'undefined' && $(id_str+n).parent().parent().is(":visible")){
			var id = $(id_str+n).val();
			var conId = $(conId_str+n).val();
			var originalId = $(originalId_str+n).val();
			originalId = Number(originalId);
			var num = parseInt($(num_str + n).val());

			if (specialProIdArr.indexOf(id) != -1) {
				// �ɵ����ɱ����������ݣ�����ֱ�ӻ�ȡ
				jsyequ_arr.push({
					rownum : n,
					productId: id,
					number : num,
					conId  : (originalId === 0)? conId : originalId,
					amount : $(cost_str + n).val()
				});
			} else {
				jsyequ_arr.push({
					rownum : n,
					productId: id,
					conId  : (originalId === 0)? conId : originalId,
					number: num
				});
			}

			// ��ȡ�����µ����ID�Լ����� PMS2364 2017-01-06
			if(conProductId_str != '' && specialProIdArr.indexOf(id) == -1){// pms2522 �������ϵ����������������
				var conProductId = $(conProductId_str+n).val();
				if($("#contractequ_"+conProductId+"_"+n).children('tbody').children('tr').length > 0){
					$("#contractequ_"+conProductId+"_"+n).children('tbody').children('tr').each(function(){
						if($(this).attr('index') != undefined){
							var index = $(this).attr('index');
							var name = $("#goodsDetail_"+conProductId+"_"+n).attr('name')+"["+index+"]";
							var productId_name = name+"[productId]";
							var originalId_name = name+"[originalId]";
							var conId_name = name+"[id]";
							var productNum_name = name+"[number]";
							var isDelTag_name = name+"[isDelTag]";
							// console.log($("input[name='"+productId_name+"']").val());
							var productId = $("input[name='"+productId_name+"']").val();
							var originalId = $("input[name='"+originalId_name+"']").val();
							originalId = Number(originalId);
							var conId = $("input[name='"+conId_name+"']").val();
							var productNum = $("input[name='"+productNum_name+"']").val();
							var isDelTag = $("input[name='"+isDelTag_name+"']").val();
							if(isDelTag != 1){
								jsyequ_arr.push({
									rownum : n,
									productId: productId,
									conId: (originalId === 0)? conId : originalId,
									number: productNum
								});
							}
						}
					});
				}
			}

			product_Num += 1;
			return cacheJsyEqu((n+1),id_str,num_str,conProductId_str,cost_str,isdel_str,product_Count,product_Num);
		}else{
			// ���ռ����������������ڲ�Ʒ�µ���������ʱ�����ռ�����ֹ����ID�������Ų�����ʱ���Bug��
			if( typeof product_Num != 'undefined' && product_Num < product_Count ){
				product_Count -= 1;
				return cacheJsyEqu((n+1),id_str,num_str,conProductId_str,cost_str,isdel_str,product_Count,product_Num);
			}else{
				return jsyequ_arr;
			}
		}
	}else{
		return cacheJsyEqu((n+1),id_str,num_str,conProductId_str,cost_str,isdel_str,product_Count,product_Num);
	}
}

// ��������ë���ʵķ���
function cal(act,productId,equIndex) {
    var rowNum = $("#rowNum").val();
    var productArr = [];
	jsyequ_arr = [];

    //��ɾ������ʱ�ռ�������Ϣ
    var delProductArr = [];
    if( act == "del" ){
		delProductArr['rownum'] = $(productId+"_cmp_productId"+equIndex).parent("td").parent("tr").attr("rownum");
		delProductArr['id'] = $(productId+"_cmp_productId"+equIndex).val();
		delProductArr['num'] = $(productId+"_cmp_number"+equIndex).val();
    }

    // ͨ����Ʒ����ѭ����ȡ������Ϣ
    for (var i = 1; i <= rowNum; i++) {
		// ��Ʒ����������
		var product_Count = $("#productInfo"+i+" table tbody").children('tr').length;
		// ��Ʒ��ɾ���˵����ݲ�ȡ
		if($("#conProductId"+i).parent().prev().children().attr('title') != '���ɾ���Ĳ�Ʒ'){//��Ϊ�Ҳ����ɲ���Ԫ�أ���ʱ�ñ�ɾ����Ʒ���ǰ��ͼ���title�����ж�
			cacheJsyEqu(0,"#productInfo" + i + "_cmp_productId","#productInfo" + i + "_cmp_number","#productInfo" + i + "_cmp_conProductId","#productInfo" + i + "_cmp_costEstimate","#productInfo" + i + "_cmp_isDel",product_Count,0);
		}
    }
	// ��ȡ������������Ϣ
	cacheJsyEqu(0,"#productInfo_cmp_productId","#productInfo_cmp_number",'',"#productInfo_cmp_costEstimate");
	if(jsyequ_arr.length > 0){
		for (var i = 0; i < jsyequ_arr.length; i++) {
			productArr.push(jsyequ_arr[i]);
		}
	}

	// ��̬�ų���ɾ��������
	var del_num = 0;
	if( act == "del" ){
		for (var i = 0; i < productArr.length; i++) {
			// if (productArr[i].productId == delProductArr['id'] && productArr[i].number == delProductArr['num'] && del_num == 0) {
			// 	productArr.splice(i,1);
			// 	del_num += 1;
			// }
			if(productArr[i].rownum == delProductArr['rownum']){
				productArr.splice(i,1);
				del_num += 1;
			}
		}
	}

	//console.log(productArr);
	//var ids = '';
	//for (var i = 0; i < productArr.length; i++) {
	//	ids += productArr[i].productId + ',';
	//}
	//console.log(ids);
	//console.log("here: "+specialProIdArr);

    // ����
	var contractId = (typeof $("#oldId").val() != 'undefined' && $("#oldId").val() != "undefined")? $("#oldId").val() : $("#contractId").val();//�ֱ��������ͬ��ɺ�ͬ
	var isChangeVal =  ($("#dealStatus").val() == 1 || $("#dealStatus").val() == 3)? 1 : $("#isSubAppChange").val();
    $.ajax({
        url: "?model=contract_contract_contract&action=getCostByEqu",
        data: {
            cid: contractId,
            equArr: productArr,
			isChange: isChangeVal
        },
        async: false,
        type: 'post',
        dataType: 'json',
        success: function(msg) {
            if (msg.result == "ok") {
                $("#calArea").html("���㣺" + msg.costEstimates + ", Ԥ��ë���ʣ�" + msg.exgross + " %");
            } else {
                $("#calArea").html(msg.msg);
            }
        }
    });
}

$(function() {
	/**
	 * ��֤��Ϣ
	 */
	 validate({},{disableButton:true});
});

// ���ݽ���������ID��ѯ��Ӧ��ִ������
function chkBorrowEquExeNum(borrowEquId){
	var returnValue = $.ajax({
		type: 'POST',
		url: "?model=projectmanagent_borrow_borrow&action=getOriginalBorrowEquInfo",
		data: {
			id: borrowEquId
		},
		async: false,
		success: function (data) {
		}
	}).responseText;
	returnValue = eval("(" + returnValue + ")");
	return returnValue[0];
}

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
	var isSubAppChange = $("#isSubAppChange").val();
	if (isSubAppChange == '1') {
		param.temp = 1;
	} else {
		param.isTemp = 0;
	}
	if (equConfig.type == "view" && $("#isShowDel").val() == "true") {
		delete param.notDel;
	}
	$("#productInfo").yxeditgrid({
		objName : 'contract[detail][' + newRowNum + ']',
		tableClass : 'form_in_table',
		url : '?model=contract_contract_equ&action=getNoProductEqu',
		param : param,
		type : equConfig.type,
		// isAddOneRow:false,
		isAdd : false,
		colModel : [{
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
		},{
			display : 'originalTempId',
			name : 'originalTempId',
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
										if (confirm("�Ƿ�������������")) {
											var productNum = rowData
												? rowData.number
												: 1;
											var conProId = rowData
												? rowData.productId
												: 0;
											getProductConfig('0',
													productData.id, productNum,
													newRowNum, rowNum,
													conProId, true,contractId);
										}
										if(specialProIdArr.indexOf(productData.id) != -1){
											// $("#productInfo_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
											// 	.attr("readonly",false).val('10');
											$("#productInfo_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
												.attr("readonly",false).val('0');
											countEquCost('0',rowNum);
										}
										countStander();
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
										if (confirm("�Ƿ�������������")) {
											var productNum = rowData
												? rowData.number
												: 1;
											var conProId = rowData
												? rowData.productId
												: 0;
											getProductConfig('0',
													productData.id, productNum,
													newRowNum, rowNum,
													conProId, true,contractId);
										}
										if(specialProIdArr.indexOf(productData.id) != -1){
											// $("#productInfo_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
											// 	.attr("readonly",false).val('10');
											$("#productInfo_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
												.attr("readonly",false).val('0');
											countEquCost('0',rowNum);
										}
										countStander();
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
			display : '�´﷢���ƻ�����',
			name : 'issuedShipNum',
			type : 'hidden'
		}, {
			display : '����������ID',
			name : 'toBorrowequId',
			type : 'hidden'
		},{
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			value : 1,
			event : {
				blur : function() {//chkBorrowEquExeNum(borrowEquId)
					var borrowEquId = $("#productInfo_cmp_toBorrowequId"+$(this).data("rowNum")).val();
					var borrowEqu = chkBorrowEquExeNum(borrowEquId);
					var executedNum = borrowEqu.executedNum - borrowEqu.backNum;
					if(isNaN($(this).val()) || parseInt($(this).val()) <= 0){
						alert("���������0��������");
						$(this).val(executedNum);
					}else if(parseInt($(this).val()) > parseInt(executedNum)){
						alert("ת��������������ڿ�����������Χ�ڡ�");
						$(this).val(executedNum);
					}else{
						// �������ϼ���
						cal();
					}
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
			display : 'isBorrowToorder',
			name : 'isBorrowToorder',
			type : 'hidden'
		}, {
			display : '',
			name : 'configCode',
			type : 'hidden',
			staticVal : 1
		}, {
			name : 'delButton',
			display : '����',
			type : 'hidden',
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
						    $("#productInfo_cmp_originalTempId" + i).val($("#productInfo_cmp_id" + i).val());
						   // �����������
						   if (confirm('�Ƿ��������������ã�')) {
							   initProductConfigs('0',newRowNum,contractId);
						   }
					   }
				   }
				}
			},
			html : '<input type="button"  value="����ɾ������"  class="txt_btn_a"  />'
		}, {
			display : '�ɱ�������%��',
			name : 'proportion',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			process : function($input, rowData) {
				$input.val('');
				if (equConfig.type != "view" || !rowData) {
					var proportionVal = (!rowData || rowData['proportion'] == '' || rowData['proportion'] < 0)? '0' : rowData['proportion'];
					var rowNum = $input.data("rowNum");
					var grid = $input.data("grid");
					var productId = grid.getCmpByRowAndCol(rowNum, 'productId').val();
					var isDel = grid.getCmpByRowAndCol(rowNum, 'isDel').val();
					if(specialProIdArr.indexOf(productId) != -1 && isDel == '0'){
						$input.attr("style", "background:#fffffb;").attr("readonly",false).val(proportionVal);
					}
				}
			},
			event : {
				change : function(e){
					if(!isNum($(this).val())){
						if($(this).val() != 0){
							alert("������������");
							$(this).val(0);
							return false;
						}
					}
					var rowNum = $(this).data('rowNum');// �ڼ���
					var grid = $(this).data('grid');// ������
					var productId = grid.getCmpByRowAndCol(rowNum, 'productId').val();
					if(specialProIdArr.indexOf(productId) != -1){
						countEquCost('0',rowNum);
						// �������ϼ���
						cal();
					}
				}
			}
		}, {
			display : '�ɱ�����',
			name : 'costEstimate',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '',
			name : 'alreadyDel',
			type : 'hidden',
			staticVal : '0'
		}],
		event : {
			beforeAddRow : beforeAddRow,
			addRow : function(e, rowNum, rowData, g, tr) {
				addGridChangeShow(g, rowNum, 'productName', rowData, tr);
				countStander()
			},
			beforeRemoveRow : function(e, rowNum, rowData, g) {
				if( equConfig.type=='change' && rowData){
					if( rowData.executedNum>0 ){//������ѳ��ⲻ��ɾ
						alert("�������Ѳ��ֳ��⣬��ֱֹ��ɾ���������˻����̣�");
						g.isRemoveAction=false;
						return false;
					}else if(rowData.issuedShipNum > 0){//���û���ѳ���,�����з����ƻ���Ҳ����ɾ
						alert("���������´﷢���ƻ�����ֱֹ��ɾ����");
						g.isRemoveAction=false;
						return false;
					}
				}
			},
			removeRow : function(e, rowNum, rowData, index, g){
				var $tr = $("#goodsDetail_0_" + rowNum);
				//���뽻��ȷ�ϣ�ɾ�����Ϻ�Ĭ��ɾ���������
				if (equConfig.type == "change" || equConfig.type == "edit") {// ����Ǳ�����Ǳ༭
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
				countStander();
				arrivalPeriod=g.getCmpByRowAndCol(rowNum,"arrivalPeriod").val();
				countStanderAfterRemove(arrivalPeriod);
				cal();
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
						   $("#productInfo_cmp_alreadyDel" + i).val("1")
						}
					}
				}
				cal();
			}
		}
	});
}

//�޸��������ʱ����������Ƿ��ѷ���
function chkNumber(executedNum,goodsRowNum,index,goodsId,productRowNum,self){
    if(parseInt(executedNum)>0 && parseInt($(self).val()) < parseInt(executedNum)){
        alert("�ѷ���"+executedNum+"������д���������������ѷ���������");
        $(self).val(executedNum);
    }else{
        setEquConfigCost(goodsRowNum , index);
        countEquCost(goodsId,productRowNum);
		cal();
    }
}

//��������ɱ�
function setEquConfigCost(goodsRowNum,rowNum){
	var price = $("input[name='contract[detail][" + goodsRowNum + "][" + rowNum + "][price]']").val();
	var number = $("input[name='contract[detail][" + goodsRowNum + "][" + rowNum + "][number]']").val();
	$("input[name='contract[detail][" + goodsRowNum + "][" + rowNum + "][cost]']").val(accMul(price,number));
}

//�������ϳɱ�����
function countEquCost(goodsId,productRowNum){
	var tr = $("#goodsDetail_" + goodsId + "_" + productRowNum);
	var configObj = tr.find("input[name*='cost']:visible");
	var cost = 0;
	configObj.each(function(){
		cost = accAdd(cost,$(this).val());
	});
	var proportion = tr.prev("tr").find("input[id*='proportion']").val();
//	var number = tr.prev("tr").find("input[id*='number']").val();
//	tr.prev("tr").find("input[id*='costEstimate']").val(accMul(accMul(cost,accAdd(1,accDiv(proportion,100))),number));
	if(proportion == 0){
		tr.prev("tr").find("input[id*='costEstimate']").val(cost);
	}else if(proportion > 0){
		tr.prev("tr").find("input[id*='costEstimate']").val(accMul(cost,accAdd(1,accDiv(proportion,100))));
	}
}

//�������
function addConfig(goodsId,productRowNum,goodsRowNum,productId){
	var tr = $("#goodsDetail_" + goodsId + "_" + productRowNum);
	var index = accAdd(tr.find("tr:last").attr("index"),1);
	var name = 'contract[detail]['+ goodsRowNum +']['+ index +']';
	var mycount = tr.find("tr").length;
	var itemtable = document.getElementById("contractequ_" + goodsId + "_" + productRowNum);
	i = itemtable.rows.length;
    oTR = itemtable.insertRow([i]);
    oTR.className = "tr_inner";
    oTR.index = index;
    oTR.height = "28px";
    var oTL0 = oTR.insertCell([0]);
    oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png" onclick="delConfig(this,' + goodsId + ',' + productRowNum + ');" title="ɾ����">';
    var oTL1 = oTR.insertCell([1]);
    oTL1.innerHTML = mycount;
    var oTL2 = oTR.insertCell([2]);
    oTL2.innerHTML = '<input type="text" name="' + name
    + '[productCode]" class="txtshort" />'
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
    + '[number]" class="txtshort" onchange="setEquConfigCost('+ goodsRowNum + ',' + index + ');countEquCost('+ goodsId + ',' + productRowNum + ')" />';
    var oTL6 = oTR.insertCell([6]);
    oTL6.innerHTML = '<input type="text" name="' + name
    + '[warrantyPeriod]" class="txtshort" />';
    var oTL7 = oTR.insertCell([7]);
    oTL7.innerHTML = '<input type="text" name="' + name
        + '[arrivalPeriod]" class="txtshort" onblur="countStander()" id="inner_arrivalPeriod' + productId + '_' + productRowNum + '"/>';
    var oTL8 = oTR.insertCell([8]);
    oTL8.innerHTML = '<input type="text" name="' + name
    + '[cost]" class="readOnlyTxtShort" readonly="readonly" />'
    + '<input type="hidden" name="' + name
    + '[price]" />';
    //��������tr��ɾ����ťtd����name��ɾ����ʱ����õ�
    tr.find("tr:last").find("td:first").attr("name",name);
    reloadConfigProduct(goodsId,productRowNum,name);
}

/**
 * ��Ⱦ���������Ϣcombogrid
 */
function reloadConfigProduct(goodsId,productRowNum,name) {
    $("input[name='" + name + "[productCode]']").yxcombogrid_product("remove").yxcombogrid_product({// �����ϱ��
        nameCol: 'productCode',
		event : {
			'clear' : function() {
            	$("input[name='" + name + "[productId]']").val('');
            	$("input[name='" + name + "[productName]']").val('');
            	$("input[name='" + name + "[unitName]']").val('');
            	$("input[name='" + name + "[productModel]']").val('');
            	$("input[name='" + name + "[number]']").val('');
            	$("input[name='" + name + "[warrantyPeriod]']").val('');
            	$("input[name='" + name + "[arrivalPeriod]']").val('');
            	$("input[name='" + name + "[price]']").val('');
            	$("input[name='" + name + "[cost]']").val('');
            	countEquCost(goodsId,productRowNum);
			}
		},
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (i) {
                    return function (e, row, data) {
                    	$("input[name='" + name + "[productId]']").val(data.id);
                    	$("input[name='" + name + "[productName]']").val(data.productName);
                    	$("input[name='" + name + "[unitName]']").val(data.unitName);
                    	$("input[name='" + name + "[productModel]']").val(data.pattern);
                    	$("input[name='" + name + "[number]']").val('1');
                    	$("input[name='" + name + "[warrantyPeriod]']").val(data.warranty);
                    	$("input[name='" + name + "[arrivalPeriod]']").val(data.arrivalPeriod);
                    	$("input[name='" + name + "[price]']").val(data.priCost);
                    	$("input[name='" + name + "[cost]']").val(data.priCost);
                    	countEquCost(goodsId,productRowNum);
                    }
                }(i)
            }
        }
    });
    $("input[name='" + name + "[productName]']").yxcombogrid_product("remove").yxcombogrid_product({// ����������
        nameCol: 'productName',
		event : {
			'clear' : function() {
            	$("input[name='" + name + "[productId]']").val('');
            	$("input[name='" + name + "[productCode]']").val('');
            	$("input[name='" + name + "[unitName]']").val('');
            	$("input[name='" + name + "[productModel]']").val('');
            	$("input[name='" + name + "[number]']").val('');
            	$("input[name='" + name + "[warrantyPeriod]']").val('');
            	$("input[name='" + name + "[arrivalPeriod]']").val('');
            	$("input[name='" + name + "[price]']").val('');
            	$("input[name='" + name + "[cost]']").val('');
            	countEquCost(goodsId,productRowNum);
			}
		},
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (i) {
                    return function (e, row, data) {
                    	$("input[name='" + name + "[productId]']").val(data.id);
                    	$("input[name='" + name + "[productCode]']").val(data.productCode);
                    	$("input[name='" + name + "[unitName]']").val(data.unitName);
                    	$("input[name='" + name + "[productModel]']").val(data.pattern);
                    	$("input[name='" + name + "[number]']").val('1');
                    	$("input[name='" + name + "[warrantyPeriod]']").val(data.warranty);
                    	$("input[name='" + name + "[arrivalPeriod]']").val(data.arrivalPeriod);
                    	$("input[name='" + name + "[price]']").val(data.priCost);
                    	$("input[name='" + name + "[cost]']").val(data.priCost);
                    	countEquCost(goodsId,productRowNum);
                    }
                }(i)
            }
        }
    });
}
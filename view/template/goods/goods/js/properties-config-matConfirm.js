//����ѡ������
function saveSetting() {
	var itemArr = $("#productInfo").yxeditgrid("getCmpByCol", "productCode");
	//��Ʒ����
	var outJson = {
		goodsId: $("#goodsId").val(),
		number: $("#number").val(),
		price: $("#price").val(),
		money: $("#money").val(),
		warrantyPeriod: $("#warrantyPeriod").val(),
		licenseId: '',
		cacheId: $("#cacheId").val(),
		onlyProductId: $("#onlyProductId").val()
	};

	// ��ȡ��Ʒ����
	var obj = $.ajax({
		type: "POST",
		url: 'index1.php?model=goods_goods_goodsbaseinfo&action=getProType',
		data: {id: outJson.goodsId},
		async: false
	}).responseText;
	if (obj != "") {
		var proType = eval("(" + obj + ")");
		outJson.proType = proType.proType;
		outJson.proTypeId = proType.proTypeId;
		outJson.proExeDeptName = proType.proExeDeptName;
		outJson.proExeDeptId = proType.proExeDeptId;
		outJson.goodsName = proType.goodsName;
		outJson.newExeDeptName = proType.exeDeptName;
		outJson.newExeDeptCode = proType.exeDeptCode;
	}

	if (itemArr.length > 0) {
		var returnArr = [];
		//ѭ��
		itemArr.each(function() {
			var rowNum = $(this).data("rowNum");
			var rowArr = $("#productInfo").yxeditgrid("getRowByRowNum", rowNum);
			rowArr.each(function() {
				var beforeStr = "productInfo_cmp_";
				if($("#" + beforeStr + "productCode" + rowNum).val()){
					var equJson = {
						productCode: $("#" + beforeStr + "productCode" + rowNum).val(),
						productName: $("#" + beforeStr + "productName" + rowNum).val(),
						productId: $("#" + beforeStr + "productId" + rowNum).val(),
						conProductId: $("#" + beforeStr + "conProductId" + rowNum).val(),
						productModel: $("#" + beforeStr + "productModel" + rowNum).val(),
						number: $("#" + beforeStr + "number" + rowNum).val(),
						onlyProductId: $("#onlyProductId").val(),
						price: $("#" + beforeStr + "price" + rowNum).val(),
						money: $("#" + beforeStr + "money" + rowNum).val()
					};
					returnArr.push(equJson);
				}
			})
		});
	}

	var reArr = [];
	reArr[0] = outJson;
	reArr[1] = returnArr;
	parent.window.opener.setData(reArr, $("#componentId").val(), $("#rowNum").val());
	parent.window.close();
}

$(function() {
	$.post("?model=contract_contract_product&action=uuid",
		function(data) {
			$("#onlyProductId").val(data);
		}
	);
	var goodsId = $("#goodsId").val();
	var deloy = $("#cacheId").val();
	$("#productInfo").yxeditgrid({
		objName: 'contract[material]',
		url: "?model=common_contract_allsource&action=newGetProductEqu",
		param: {
			deloy: deloy,
			parentEquId: 0
		},
		colModel: [{
			display: 'isDel',
			name: 'isDel',
			type: 'hidden'
		}, {
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: 'originalId',
			name: 'originalId',
			type: 'hidden'
		}, {
			display: '���ϱ��',
			name: 'productCode',
			tclass: 'txtmiddle',
			validation: {
				required: true
			},
			process: function($input, rowData) {

				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
//						closeCheck : true,
					closeAndStockCheck: true,
					hiddenId: 'productInfo_cmp_productId' + rowNum,
					height: 250,
					event: {
						'clear': function() {
							g.clearRowValue(rowNum);
						}
					},
					gridOptions: {
						showcheckbox: false,
						isTitle: true,
						event: {
							row_dblclick: (function(rowNum, rowData) {
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
									var price = productData.priCost;
									var number = g.getCmpByRowAndCol(rowNum,'number').val();
									g.getCmpByRowAndCol(rowNum,
										'price')
										.val(price);
									g.getCmpByRowAndCol(rowNum,
										'money')
										.val(price*number);
								}
							})(rowNum, rowData)
						}
					}
				});

				return $input;
			}
		}, {
			display: '��������',
			name: 'productName',
			tclass: 'txt',
			validation: {
				required: true
			},
			process: function($input, rowData) {

				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
//						closeCheck : true,
					closeAndStockCheck: true,
					hiddenId: 'productInfo_cmp_productId' + rowNum,
					nameCol: 'productName',
					height: 250,
					event: {
						'clear': function() {
							g.clearRowValue(rowNum);
						}
					},
					gridOptions: {
						showcheckbox: false,
						isTitle: true,
						event: {
							row_dblclick: (function(rowNum, rowData) {
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
									var price = productData.priCost;
									var number = g.getCmpByRowAndCol(rowNum,'number').val();
									g.getCmpByRowAndCol(rowNum,
										'price')
										.val(price);
									g.getCmpByRowAndCol(rowNum,
										'money')
										.val(price*number);
								}
							})(rowNum, rowData)
						}
					}
				});

				return $input;
			}
		}, {
			display: '����Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '��ƷId',
			name: 'conProductId',
			staticVal: goodsId,
			type: 'hidden'
		}, {
			display: '�ͺ�/�汾',
			name: 'productModel',
			readonly: true,
			tclass: 'readOnlyTxtNormal'
		}, {
			display: '����',
			name: 'number',
			tclass: 'txtshort',
			value: 1,
			event: {
				change: function() {
					var number = $(this).val();
					if(!isNum(number)){
						alert("��������������");
						$(this).val(1);
						return false;
					}
					var g = $(this).data("grid");
					var rowNum = $(this).data("rowNum");
					g.getCmpByRowAndCol(rowNum,'money').val(g.getCmpByRowAndCol(rowNum,'price').val()*number);
				}
			}
		}, {
			display: '����',
			name: 'price',
			type: 'hidden'
		}, {
			display: '���',
			name: 'money',
			type: 'hidden'
		}]
	});

});

function getProductConfig(goodsId, productId, productNum, goodsRowNum,
						  productRowNum, equId, ifGetPro, contractId) {
	var url = "";
	var param = {};
	// �ж��Ǳ༭�����������
	if (equConfig.type != 'add' && !ifGetPro) {
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
		type: "POST",
		url: url,
		data: param,
		async: false
	}).responseText;

	$("#goodsDetail_" + goodsId + "_" + productRowNum).remove();
	// productIdArr[rowNum]=productId;
	// tr.empty();
	if (strTrim(data)) {
		var obj = eval("(" + data + ")");
		if (obj.length) {
			var $table = $("<table class='form_in_table' id='contractequ_$id'></table>");
			var $td = $("<td class='innerTd' colspan='20'></td>");
			var $tr = $("<tr id='goodsDetail_" + goodsId + "_" + productRowNum
			+ "'></tr>");
			$tr.append($td);
			$td.append($table);

			var $th = $('<tr class="main_tr_header1"><th width="30px"></th><th width="35px">���</th><th>�������</th>'
			+ '<th>�������</th><th>�汾�ͺ�</th><th>����</th><th>������</th><th>������</th></tr>');
			$table.append($th);
			var configtr = $("#productInfo table tr[rowNum="
			+ productRowNum + "]");
			configtr.after($tr);
			var nametr = 'contract[detail]';
			$tr.attr("name", nametr);
			for (var i = 0; i < obj.length; i++) {
				var index = i + (productRowNum + 1) * 100;
				var name = 'contract[detail][' + index + ']';
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
					} else if (p.changeTips == '3' && p.isDel == '1') {
						imgStr = '<img title="���±��ɾ��������" src="images/changedelete.gif" />';
						$ctr.css("color", "#8B9CA4");
					} else if (p.changeTips == '0' && p.isDel == '1') {
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
					+ (productId + "_" + productRowNum) + '"/>');
				}
				var $td = $("<td></td>");
				$td.append(cperiod);
				$ctr.append($td);
				$table.append($ctr);
				countStander();
			}
		}
	}
}

function countStander() {
	var cmp_arrivalPeriod = [];
	var inner_arrivalPeriod = [];
	var cmp_flag = false;
	var inner_flag = false;
	// ���ϵĽ�����
	$('input[id*=cmp_arrivalPeriod]').each(function(i) {
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
	$('input[id*=inner_arrivalPeriod]').each(function(i) {
		if (checkNum(this)) {
			if ($(this).parent().parent().children("input").val() != 1) {
				inner_arrivalPeriod[i] = $(this).val() * 1;
				inner_flag = true;
			} else {
				inner_arrivalPeriod[i] = 0
			}
		} else {
			alert('������ֻ��Ϊ��������');
			return false;
		}
	});
	var maxArrival = 0;
	if (cmp_flag && inner_flag) {// �����Ϻ������������棬ѡ��󽻻���
		maxArrival = Math.max(Math.max.apply(Math, cmp_arrivalPeriod), Math.max
			.apply(Math, inner_arrivalPeriod));
	} else if (cmp_flag) {// �������ѡ��󽻻���
		maxArrival = Math.max.apply(Math, cmp_arrivalPeriod);
	}
	// �����׼�����ڣ���ǰ����+������󽻻���
	if ($('#standardDate_v').val()) {
		var newDate = stringToDate($('#standardDate_v').val(), maxArrival)
	} else {
		var newDate = stringToDate(formatDate(new Date()), maxArrival)
	}
//	alert(formatDate(newDate))
	$('#standardDate').val(formatDate(newDate))

}

function getInventoryInfos(docType, productId) {
	var num = 0;
	$.ajax({
		type: 'POST',
		url: '?model=stock_inventoryinfo_inventoryinfo&action=getExeNumFromDefault',
		data: {
			docType: docType,
			id: productId
		},
		async: false,
		success: function(data) {
			num = data;
		}
	});
	return num;
}

function toBack() {
	window.location = "?model=goods_goods_properties&action=toChooseStep&isMoney=" + $("#isMoney").val()
		+ "&isSale=" + $("#isSale").val()
		+ "&rowNum=" + $("#rowNum").val()
		+ "&componentId=" + $("#componentId").val()
		+ "&goodsId=" + $("#goodsId").val()
		+ "&goodsName=" + $("#goodsName").val()
		+ "&isEncrypt=" + $("#isEncrypt").val()
		+ "&number=" + $("#number").val()
		+ "&price=" + $("#price").val()
        + "&typeId=" + $("#typeId").val()
		+ "&money=" + $("#money").val()
		+ "&isCon=1&cacheId=" + $("#cacheId").val()
		+ "&exeDeptName=" + $("#exeDeptName").val()
		+ "&exeDeptCode=" + $("#exeDeptCode").val()
		+ "&auditDeptCode=" + $("#auditDeptCode").val()
		+ "&auditDeptName=" + $("#auditDeptName").val();
}
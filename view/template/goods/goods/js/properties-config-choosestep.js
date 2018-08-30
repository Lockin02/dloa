
// ����ѡ������
function saveSetting() {

	//��Ʒ��ѡ��֤
	var checkResult = checkForm();
	if (checkResult == false) {
		return false;
	}

	//���ù������ϼ�������֤
	$("#settingInfo :checked").each(function(i) {
		var licenseObj = $("#license" + this.value);
		//��ѡ�е������д��ڼ������Ĺ������ϣ��������д��������
		if (licenseObj.attr("encryptionlock") == '1') {
			var licenseId = licenseObj.attr("licenseid");
			if (licenseId == undefined || licenseId == '0') {
				alert("���ֽС�" + $(this).attr("name") + "������������ڼ������Ĺ������ϣ�������д�������ã�");
				checkResult = false;
			}
		}
	});
	if (checkResult == false) {
		return false;
	}

	//��ȡҳ��
	var goodsHtml = $("#settingInfo").formhtml();

	var goodsId = $("#goodsId").val();
	var goodsName = $("#goodsName").val();

	var goodsValue = getGoodsValue();
	var goodsValueChanged = $.obj2json(goodsValue);

	var onlyProductId = $.ajax({
		url: '?model=contract_contract_product&action=uuid',
		dataType: 'html',
		type: 'post',
		async: false
	}).responseText;

	// ���ݵ���̨������
	var dataJson = {
		goodsId: goodsId,
		goodsName: goodsName,
		goodsCache: goodsHtml,
		goodsValue: goodsValueChanged,
		onlyProductId : onlyProductId
	};

	var cacheId = $("#cacheId").val();
	if (cacheId != '') {
		dataJson.id = cacheId;
	}

	$.ajax({
		type: "POST",
		url: "?model=goods_goods_goodscache&action=saveCache",
		data: {
			dataArr: dataJson
		},
		async: false,
		success: function(data) {
			if (data != 0) {
				var cacheId = strTrim(data);
				// ���json
				var outJson = {
					goodsId: $("#goodsId").val(),
					number: $("#number").val(),
					price: $("#price").val(),
					money: $("#money").val(),
					warrantyPeriod: $("#warrantyPeriod").val(),
					exeDeptName: $("#exeDeptName").val(),
					exeDeptCode: $("#exeDeptCode").val(),
					newExeDeptCode : $("#exeDeptCode").val(),
					newExeDeptName : $("#exeDeptName").val(),
					auditDeptName: $("#auditDeptName").val(),
					auditDeptCode: $("#auditDeptCode").val(),
					licenseId: '',
					onlyProductId: dataJson.onlyProductId,
					cacheId: cacheId
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
				}

				var returnArr = [];
				// ��ȡҳ����ѡ�еĵ�������Ϣ
				if(cacheId != ''){
					var cacheEqu = $.ajax({
						type: "POST",
						url: 'index1.php?model=common_contract_allsource&action=newGetProductEqu',
						data: {
							deloy: cacheId,
							parentEquId: 0
						},
						async: false
					}).responseText;
					var cacheEquObj = eval("("+cacheEqu+")");
					if (cacheEquObj){
						if(cacheEquObj.length > 0) {
							$.each(cacheEquObj,function (i,item){
								var equJson = {
									id : '',
									productCode: item.productCode,
									productName: item.productName,
									productId: item.productId,
									conProductId: $("#goodsId").val(),
									productModel: item.productModel,
									number: item.number,
									isAddFromConfig : 1,
									onlyProductId: dataJson.onlyProductId,
									price: item.price,
									money: item.money
								};
								returnArr.push(equJson);
							})
						}
					}
				}

				// ��ȡҳ���������������б�
				var itemArr = $("#productInfo").yxeditgrid("getCmpByCol", "productCode");
				if (itemArr.length > 0) {
					//ѭ��
					itemArr.each(function() {
						var rowNum = $(this).data("rowNum");
						var rowArr = $("#productInfo").yxeditgrid("getRowByRowNum", rowNum);
						rowArr.each(function() {
							var beforeStr = "productInfo_cmp_";
							if($("#" + beforeStr + "productCode" + rowNum).val()){
								var equJson = {
									id : '',
									productCode: $("#" + beforeStr + "productCode" + rowNum).val(),
									productName: $("#" + beforeStr + "productName" + rowNum).val(),
									productId: $("#" + beforeStr + "productId" + rowNum).val(),
									conProductId: $("#" + beforeStr + "conProductId" + rowNum).val(),
									productModel: $("#" + beforeStr + "productModel" + rowNum).val(),
									number: $("#" + beforeStr + "number" + rowNum).val(),
									isAddFromConfig : 0,
									onlyProductId: dataJson.onlyProductId,
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

				parent.opener.setData(reArr, $("#componentId").val(), $("#rowNum").val());
				parent.window.close();

				// var isEncrypt = $("#isEncrypt").val();
				// if (isEncrypt == 'on') {
				// 	window.location = '?model=yxlicense_license_tempKey&action=toSelectStep'
				// 	+ '&goodsId=' + $("#goodsId").val()
				// 	+ '&goodsName=' + $("#goodsName").val()
				// 	+ '&isEncrypt=' + $("#isEncrypt").val()
				// 	+ '&number=' + $("#number").val()
				// 	+ '&price=' + $("#price").val()
				// 	+ '&money=' + $("#money").val()
				// 	+ "&rowNum=" + $("#rowNum").val()
                 //        + "&typeId=" + $("#typeId").val()
				// 	+ "&componentId=" + $("#componentId").val()
				// 	+ '&exeDeptName=' + $("#exeDeptName").val()
				// 	+ '&exeDeptCode=' + $("#exeDeptCode").val()
				// 	+ "&auditDeptCode=" + $("#auditDeptCode").val()
				// 	+ "&auditDeptName=" + $("#auditDeptName").val()
				// 	+ '&cacheId=' + strTrim(data)
				// 	;
				// } else {
				// 	if ($("#isCon").val() == 1) {
				// 		window.location = "?model=goods_goods_properties&action=toMatConfirm"
				// 		+ '&goodsId=' + $("#goodsId").val()
				// 		+ '&goodsName=' + $("#goodsName").val()
				// 		+ '&isEncrypt=' + $("#isEncrypt").val()
				// 		+ '&number=' + $("#number").val()
				// 		+ '&price=' + $("#price").val()
				// 		+ '&money=' + $("#money").val()
				// 		+ "&rowNum=" + $("#rowNum").val()
                 //            + "&typeId=" + $("#typeId").val()
				// 		+ "&componentId=" + $("#componentId").val()
				// 		+ '&exeDeptName=' + $("#exeDeptName").val()
				// 		+ '&exeDeptCode=' + $("#exeDeptCode").val()
				// 		+ "&auditDeptCode=" + $("#auditDeptCode").val()
				// 		+ "&auditDeptName=" + $("#auditDeptName").val()
				// 		+ '&cacheId=' + strTrim(data)
				// 	} else {
				// 		// ���json
				// 		var outJson = {
				// 			goodsId: $("#goodsId").val(),
				// 			number: $("#number").val(),
				// 			price: $("#price").val(),
				// 			money: $("#money").val(),
				// 			warrantyPeriod: $("#warrantyPeriod").val(),
				// 			exeDeptName: $("#exeDeptName").val(),
				// 			exeDeptCode: $("#exeDeptCode").val(),
				// 			auditDeptName: $("#auditDeptName").val(),
				// 			auditDeptCode: $("#auditDeptCode").val(),
				// 			licenseId: '',
				// 			cacheId: strTrim(data)
				// 		};
                //
				// 		// ��ȡ��Ʒ����
				// 		var obj = $.ajax({
				// 			type: "POST",
				// 			url: 'index1.php?model=goods_goods_goodsbaseinfo&action=getProType',
				// 			data: {id: outJson.goodsId},
				// 			async: false
				// 		}).responseText;
				// 		if (obj != "") {
				// 			var proType = eval("(" + obj + ")");
				// 			outJson.proType = proType.proType;
				// 			outJson.proTypeId = proType.proTypeId;
				// 			outJson.proExeDeptName = proType.proExeDeptName;
				// 			outJson.proExeDeptId = proType.proExeDeptId;
				// 			outJson.goodsName = proType.goodsName;
				// 		}
                //
				// 		parent.opener.setData(outJson, $("#componentId").val(), $("#rowNum").val());
				// 		parent.window.close();
				// 	}
				// }
			} else {
				alert('�������');
			}
		}
	});

}

//ҳ���ʼ��
$(function() {
	// if ($("#isCon").val() == 1) {
	// 	$("#toNext").val("��һ��");
	// }
	if($("#notEquSlt").val() == '1'){
		$(".productInfoRow").hide();
	}else{
		$(".productInfoRow").show();
	}

	var cacheId = $("#cacheId").val();
	if (cacheId == '') {
		$("span.option").each(function() {
			var id = this.id;
			var parentId = $(this).attr('parentid');
			if (parentId != "none") {
				allSelect[id] = parentId;
			}
		});
		$(".tipTrigger").each(function() {
			if ($(this).attr("checked") && !$(this).is(":hidden")) {
				$(this).trigger("click");
			}
		})
	}

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
			process: function($input, rowData) {
				var productNum = $("#number").val();
				$input.val(productNum);
			},
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


//������һ��
function toNext() {
	var goodsValue = getGoodsValue();
	var goodsValueChanged = $.obj2json(goodsValue);
	window.location = '?model=goods_goods_properties&action=toMatConfirm'
	+ '&goodsId=' + $("#goodsId").val()
	+ '&goodsName=' + $("#goodsName").val()
	+ '&isEncrypt=' + $("#isEncrypt").val()
	+ '&number=' + $("#number").val()
	+ '&price=' + $("#price").val()
        + "&typeId=" + $("#typeId").val()
	+ '&money=' + $("#money").val()
	+ '&goodsValueChanged' + goodsValueChanged
	+ "&rowNum=" + $("#rowNum").val()
	+ "&componentId=" + $("#componentId").val()
	+ "&exeDeptName=" + $("#exeDeptName").val()
	+ "&exeDeptCode=" + $("#exeDeptCode").val()
	+ "&auditDeptCode=" + $("#auditDeptCode").val()
	+ "&auditDeptName=" + $("#auditDeptName").val();
}

function toBack() {
	window.location = "?model=contract_contract_product&action=toSetProductInfo&isMoney="
	+ $("#isMoney").val() + "&isSale=" + $("#isSale").val() + "&goodsId=" + $("#goodsId").val()
	+ "&goodsName=" + $("#goodsName").val()
	+ "&isEncrypt=" + $("#isEncrypt").val()
	+ "&number=" + $("#number").val()
	+ "&price=" + $("#price").val()
	+ "&money=" + $("#money").val()
	+ "&isCon=" + $("#isCon").val()
        + "&typeId=" + $("#typeId").val()
		+ "&notEquSlt=" + $("#notEquSlt").val()
	+ "&rowNum=" + $("#rowNum").val()
	+ "&componentId=" + $("#componentId").val()
	+ "&exeDeptName=" + $("#exeDeptName").val()
	+ "&exeDeptCode=" + $("#exeDeptCode").val()
	+ "&auditDeptCode=" + $("#auditDeptCode").val()
	+ "&auditDeptName=" + $("#auditDeptName").val();
}
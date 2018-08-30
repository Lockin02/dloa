// 表单验证
function checkform() {
	var objGrid = $("#productInfo");
	var isOK = true;
	var productIdArr = []; // 缓存查询序列号的物料Id
	// 循环获取数量
	objGrid.yxeditgrid("getCmpByCol", "number").each(function() {
		var rowNum = $(this).data('rowNum');
		// 数量验证
		if ($(this).val() * 1 == "0" || strTrim($(this).val()) == '') {
			alert('归还物料不能含有数量为0或者空的行');
			isOK = false;
			return false;
		}

		// 序列号数量验证
		var serialId = objGrid.yxeditgrid('getCmpByRowAndCol', rowNum, 'serialId').val();
		var arr = serialId.split(",");
		if (serialId != "" && $(this).val() * 1 != arr.length) {
			alert("申请归还数量【" + $(this).val() + "】与选择的序列号数量【" + arr.length + "】不相等");
			isOK = false;
			return false;
		}
		// 当序列号为空的时候,尝试去查询序列号是否为空
		if (serialId == "") {
			productIdArr.push(objGrid.yxeditgrid('getCmpByRowAndCol', rowNum, 'productId').val());
		}
	});
	// 序列号验证
	if (productIdArr.length > 0 && isOK == true) {
		$.ajax({
			url: '?model=stock_serialno_serialno&action=checkHasSerialNo',
			data: {
				'productIdArr': productIdArr.toString(), 'relDocCode': $("#borrowCode").val(),
				'relDocId': $("#borrowId").val(), 'relDocType': 'oa_borrow_borrow'
			},
			type: 'POST',
			async: false,
			success: function(data) {
				if (data != "0") {
					var obj = eval("(" + data + ")");
					alert('物料【' + obj.productName + "】含有序列号信息，请选择!");
					isOK = false;
				}
			}
		});
	}
	if (isOK == true) {
		return confirm('确认提交单据？');
	} else {
		return false;
	}
}

$(function() {
	// 产品清单
	$("#productInfo").yxeditgrid({
		objName: 'borrowreturn[product]',
		url: '?model=projectmanagent_borrow_borrowequ&action=listJsonReturn',
		tableClass: 'form_in_table',
		realDel: true,
		param: {
			'borrowId': $("#borrowId").val(),
			'isTemp': '0',
			'isDel': '0'
		},
		event: {
			reloadData: function(event, g, data) {
				if (data) {
					var rowCount = g.getCurRowNum();
					for (var i = 0; i < rowCount; i++) {
						var productId = $("#productInfo_cmp_productId" + i).val();
						if (productId != "-1") {
							var num = $("#productInfo_cmp_maxNum" + i).val();
							$("#productInfo_cmp_number" + i).val(num);
						}
					}
				} else {
					alert('已全部申请归还,不能继续申请');
					closeFun();
				}
			}
		},
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '物料编号',
			name: 'productNo',
			tclass: 'readOnlyTxtShort',
			readonly: true,
            process: function ($input, rowData) {
            	if(rowData == undefined){
                    var rowNum = $input.data("rowNum");
                    var g = $input.data("grid");
                    $input.yxcombogrid_product({
    					isFocusoutCheck : false,
                        nameCol: 'productCode',
                        width: 600,
    					event : {
    						'clear' : function() {
    							g.setRowColValue(rowNum,'productName','');
    							g.setRowColValue(rowNum,'productModel','');
    						}
    					},
                        gridOptions: {
                            event: {
                                row_dblclick: (function (rowNum) {
                                    return function (e, row, rowData) {
                                        g.getCmpByRowAndCol(rowNum, 'productName').val(rowData.productName);
                                        g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
                                    }
                                })(rowNum)
                            }
                        }
                    });
                    g.getCmpByRowAndCol(rowNum, 'productNo').removeAttr('readonly').removeClass('readOnlyTxtShort').addClass('txtShort');
            	}
            }
		}, {
			display: '物料名称',
			name: 'productName',
			tclass: 'readOnlyTxt',
			readonly: true,
			width: 180,
            process: function ($input, rowData) {
            	if(rowData == undefined){
                    var rowNum = $input.data("rowNum");
                    var g = $input.data("grid");
                    $input.yxcombogrid_product({
    					isFocusoutCheck : false,
                        nameCol: 'productName',
                        width: 600,
    					event : {
    						'clear' : function() {
    							g.setRowColValue(rowNum,'productNo','');
    							g.setRowColValue(rowNum,'productModel','');
    						}
    					},
                        gridOptions: {
                            event: {
                                row_dblclick: (function (rowNum) {
                                    return function (e, row, rowData) {
                                        g.getCmpByRowAndCol(rowNum, 'productNo').val(rowData.productCode);
                                        g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
                                    }
                                })(rowNum)
                            }
                        }
                    });
                    g.getCmpByRowAndCol(rowNum, 'productName').removeAttr('readonly').removeClass('readOnlyTxt').addClass('txt');
            	}
            }
		}, {
			display: '物料Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '规格型号',
			name: 'productModel',
			tclass: 'readOnlyTxtMiddle',
			readonly: true
		}, {
			display: '单位',
			name: 'unitName',
			tclass: 'readOnlyTxtMin',
			readonly: true,
			width: 50
		}, {
			display: '可归还数量',
			name: 'maxNum',
			tclass: 'readOnlyTxtItem',
			readonly: true,
			width: 50
		}, {
			display: '归还数量',
			name: 'number',
			tclass: 'txtshort',
			event: {
				blur: function(e, rowNum, g) {
					var rowNum = $(this).data("rowNum");
					var thisNumber = $("#productInfo_cmp_number" + rowNum).val();
					var maxNum = $("#productInfo_cmp_maxNum" + rowNum).val();
					if (thisNumber < 0 || eval(thisNumber) > eval(maxNum)) {
						alert("数量不能大于" + maxNum + ",或小于0 ");
						var g = $(this).data("grid");
						g.setRowColValue(rowNum, "number", maxNum, true);
					}

				}
			},
			width: 50
		}, {
			name: 'serialId',
			display: '序列号ID',
			type: 'hidden'
		}, {
			name: 'ignoreSerialnoId',
			display: '不带出的序列号ID',
			type: 'hidden'
		}, {
			name: 'serialName',
			display: '序列号',
			tclass: 'readOnlyTxtNormal',
			readonly: 'readonly',
			process: function($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
				if(rowData){
					if (rowData.productId == "-1") {
						$("#productInfo_cmp_serialName" + rowNum).val("物料配置中的内容");
						return false;
					}
					var $img = $("<img src='images/add_snum.png' align='absmiddle' title='选择序列号'>");
					var borrowId = $("#borrowId").val();
					$img.click(function(productId, rowNum) {
						return function() {
							serialNum(productId, rowNum);
						}
					}(rowData.productId, rowNum));
					$input.before($img);
					//自动获取该物料对应的序列号信息
					var responseText = $.ajax({	//已申请归还的序列号信息
						type: 'POST',
						url: '?model=projectmanagent_borrowreturn_borrowreturnequ&action=listJson',
						data: {
							'borrowId': borrowId,
							'productId': rowData.productId
						},
						async: false
					}).responseText;
					var existData = eval("(" + responseText + ")");
					var existDataLen = existData.length;
					var existSerialIdStr = '';
					for (var i = 0; i < existDataLen; i++) {
						if (existSerialIdStr == '') {
							existSerialIdStr = existData[i].serialId;
						} else {
							existSerialIdStr += ',' + existData[i].serialId;
						}
					}
					//不带出已经填写了归还单的序列号
					$("#productInfo_cmp_ignoreSerialnoId" + rowNum).val(existSerialIdStr);
					var existSerialIdArr = existSerialIdStr.split(',');
					var responseText = $.ajax({	//序列号台帐中对应的序列号信息
						type: 'POST',
						url: '?model=stock_serialno_serialno&action=listJson',
						data: {
							'productId': rowData.productId,
							'relDocItemId': rowData.id,
							'relDocId': borrowId,
							'relDocCode': $("#borrowCode").val(),
							'relDocType': 'oa_borrow_borrow',
							'seqStatus': '0',
							'stockName': '借出仓'
						},
						async: false
					}).responseText;
					var allData = eval("(" + responseText + ")");
					var allDataLen = allData.length;
					var serialIdArr = [];
					var serialNameArr = [];
					for (var i = 0; i < allDataLen; i++) {//过滤掉已经申请归还的序列号
						if (existSerialIdArr.indexOf(allData[i].id) == -1) {
							serialIdArr.push(allData[i].id);
							serialNameArr.push(allData[i].sequence);
						}
					}
					var serialId = serialIdArr.join();
					var serialName = serialNameArr.join();
					$("#productInfo_cmp_serialId" + rowNum).val(serialId);
					$("#productInfo_cmp_serialName" + rowNum).val(serialName);
				}else{
                    g.getCmpByRowAndCol(rowNum, 'productId').val('-1');
                    g.getCmpByRowAndCol(rowNum, 'number').val("1");
                    g.getCmpByRowAndCol(rowNum, 'maxNum').val("99");
                    g.getCmpByRowAndCol(rowNum, 'serialName').val("物料配置中的内容");
                    g.getCmpByRowAndCol(rowNum, 'productName').width(160)
				}
			}
		}],
		isAddOneRow: false
	});
});

// 选择序列号
function serialNum(productId, rownum) {
	showThickboxWin('?model=stock_serialno_serialno&action=toChooseFrameForRe'
	+ '&productId=' + productId
	+ '&elNum=' + rownum
	+ '&serialId=' + $("#productInfo_cmp_serialId" + rownum).val()
	+ '&serialName=' + $("#productInfo_cmp_serialName" + rownum).val()
	+ '&relDocCode=' + $("#borrowCode").val()
	+ '&relDocId=' + $("#borrowId").val()
	+ '&relDocItemId=' + $("#productInfo_cmp_equId" + rownum).val()
	+ '&ignoreSerialnoId=' + $("#productInfo_cmp_ignoreSerialnoId" + rownum).val()
	+ '&relDocType=oa_borrow_borrow'
	+ "&placeValuesBefore&TB_iframe=true&modal=false&height=550&width=700");
}
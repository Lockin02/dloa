// ����֤
function checkform() {
	var objGrid = $("#productInfo");
	var isOK = true;
	var productIdArr = []; // �����ѯ���кŵ�����Id
	// ѭ����ȡ����
	objGrid.yxeditgrid("getCmpByCol", "number").each(function() {
		var rowNum = $(this).data('rowNum');
		// ������֤
		if ($(this).val() * 1 == "0" || strTrim($(this).val()) == '') {
			alert('�黹���ϲ��ܺ�������Ϊ0���߿յ���');
			isOK = false;
			return false;
		}

		// ���к�������֤
		var serialId = objGrid.yxeditgrid('getCmpByRowAndCol', rowNum, 'serialId').val();
		var arr = serialId.split(",");
		if (serialId != "" && $(this).val() * 1 != arr.length) {
			alert("����黹������" + $(this).val() + "����ѡ������к�������" + arr.length + "�������");
			isOK = false;
			return false;
		}
		// �����к�Ϊ�յ�ʱ��,����ȥ��ѯ���к��Ƿ�Ϊ��
		if (serialId == "") {
			productIdArr.push(objGrid.yxeditgrid('getCmpByRowAndCol', rowNum, 'productId').val());
		}
	});
	// ���к���֤
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
					alert('���ϡ�' + obj.productName + "���������к���Ϣ����ѡ��!");
					isOK = false;
				}
			}
		});
	}
	if (isOK == true) {
		return confirm('ȷ���ύ���ݣ�');
	} else {
		return false;
	}
}

$(function() {
	// ��Ʒ�嵥
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
					alert('��ȫ������黹,���ܼ�������');
					closeFun();
				}
			}
		},
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '���ϱ��',
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
			display: '��������',
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
			display: '����Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '����ͺ�',
			name: 'productModel',
			tclass: 'readOnlyTxtMiddle',
			readonly: true
		}, {
			display: '��λ',
			name: 'unitName',
			tclass: 'readOnlyTxtMin',
			readonly: true,
			width: 50
		}, {
			display: '�ɹ黹����',
			name: 'maxNum',
			tclass: 'readOnlyTxtItem',
			readonly: true,
			width: 50
		}, {
			display: '�黹����',
			name: 'number',
			tclass: 'txtshort',
			event: {
				blur: function(e, rowNum, g) {
					var rowNum = $(this).data("rowNum");
					var thisNumber = $("#productInfo_cmp_number" + rowNum).val();
					var maxNum = $("#productInfo_cmp_maxNum" + rowNum).val();
					if (thisNumber < 0 || eval(thisNumber) > eval(maxNum)) {
						alert("�������ܴ���" + maxNum + ",��С��0 ");
						var g = $(this).data("grid");
						g.setRowColValue(rowNum, "number", maxNum, true);
					}

				}
			},
			width: 50
		}, {
			name: 'serialId',
			display: '���к�ID',
			type: 'hidden'
		}, {
			name: 'ignoreSerialnoId',
			display: '�����������к�ID',
			type: 'hidden'
		}, {
			name: 'serialName',
			display: '���к�',
			tclass: 'readOnlyTxtNormal',
			readonly: 'readonly',
			process: function($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
				if(rowData){
					if (rowData.productId == "-1") {
						$("#productInfo_cmp_serialName" + rowNum).val("���������е�����");
						return false;
					}
					var $img = $("<img src='images/add_snum.png' align='absmiddle' title='ѡ�����к�'>");
					var borrowId = $("#borrowId").val();
					$img.click(function(productId, rowNum) {
						return function() {
							serialNum(productId, rowNum);
						}
					}(rowData.productId, rowNum));
					$input.before($img);
					//�Զ���ȡ�����϶�Ӧ�����к���Ϣ
					var responseText = $.ajax({	//������黹�����к���Ϣ
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
					//�������Ѿ���д�˹黹�������к�
					$("#productInfo_cmp_ignoreSerialnoId" + rowNum).val(existSerialIdStr);
					var existSerialIdArr = existSerialIdStr.split(',');
					var responseText = $.ajax({	//���к�̨���ж�Ӧ�����к���Ϣ
						type: 'POST',
						url: '?model=stock_serialno_serialno&action=listJson',
						data: {
							'productId': rowData.productId,
							'relDocItemId': rowData.id,
							'relDocId': borrowId,
							'relDocCode': $("#borrowCode").val(),
							'relDocType': 'oa_borrow_borrow',
							'seqStatus': '0',
							'stockName': '�����'
						},
						async: false
					}).responseText;
					var allData = eval("(" + responseText + ")");
					var allDataLen = allData.length;
					var serialIdArr = [];
					var serialNameArr = [];
					for (var i = 0; i < allDataLen; i++) {//���˵��Ѿ�����黹�����к�
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
                    g.getCmpByRowAndCol(rowNum, 'serialName').val("���������е�����");
                    g.getCmpByRowAndCol(rowNum, 'productName').width(160)
				}
			}
		}],
		isAddOneRow: false
	});
});

// ѡ�����к�
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
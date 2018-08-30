$(function() {
	// /**
	// * ���Ψһ����֤
	// */
	//
	// var url = "?model=asset_daily_keep&action=checkRepeat";
	// $("#billNo").ajaxCheck({
	// url : url,
	// alertText : "* �ñ���Ѵ���",
	// alertTextOk : "* �ñ�ſ���"
	// });
	/**
	 * ѡ��Ƭ���Զ��������ԭֵ����Ϣ
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		g.getCmpByRowAndCol(rowNum, 'assetId').val(rowData.id);
		g.getCmpByRowAndCol(rowNum, 'assetCode').val(rowData.assetCode);
		g.getCmpByRowAndCol(rowNum, 'assetName').val(rowData.assetName);
		g.getCmpByRowAndCol(rowNum, 'userId').val(rowData.userId);
		g.getCmpByRowAndCol(rowNum, 'userName').val(rowData.userName);
	}

	$("#purchaseProductTable").yxeditgrid({
		objName : 'keep[item]',
		// url:'?model_asset_daily_keepitem',
		title : '��Ƭ��Ϣ',
		isAddOneRow : false,
		event : {
			removeRow : function(t, rowNum, rowData) {
				check_all();
			}
		},
		colModel : [{
			display : '��Ƭ���',
			name : 'assetCode',
			tclass : 'txt',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : {
							'isDel' : '0',
							'isScrap':'0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.assetCode) {
											alert("�벻Ҫѡ����ͬ���ʲ�.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetName = g.getCmpByRowAndCol(
												rowNum, 'assetName');
										$assetName.val(rowData.assetName);
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '�ʲ�����',
			name : 'assetName',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
					gridOptions : {
						param : {
							'isDel' : '0',
							'isScrap':'0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.assetCode) {
											alert("�벻Ҫѡ����ͬ���ʲ�.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetCode = g.getCmpByRowAndCol(
												rowNum, 'assetCode');
										$assetCode.val(rowData.assetCode);
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '�ʲ�Id',
			name : 'assetId',
			type : 'hidden'
		}, {
			display : 'ά�޽��',
			name : 'amount',
			tclass : 'txtmiddle',
			type : 'money',
			event : {
				blur : function() {
					check_all();
				}
			}
		}, {
			display : 'ʹ����Id',
			name : 'userId',
			type : 'hidden'
		}, {
			display : 'ʹ����',
			name : 'userName',
			tclass : 'txtshort'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}],

	});
	//����ѡ��Ƭ��Ϣ��ť
	$("#purchaseProductTable").find("tr:first td").append("<input type='button' value='ѡ��Ƭ��Ϣ' class='txt_btn_a' style='margin-left:10px;' onclick='selectCard();'/>");
	// ѡ����Ա���
	$("#keeper").yxselect_user({
		hiddenId : 'keeperId',
		isGetDept : [true, "deptId", "deptName"],
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#applyCompanyCode').val(returnValue.companyCode)
					$('#applyCompanyName').val(returnValue.companyName)
				}
			}
		}
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"billNo" : {
			required : true
		},
		"keeper" : {
			required : true
		},
		"keepDate" : {
			custom : ['date']
		}
	});

});

// ���ݴӱ��ά�޽�̬����ά���ܽ��
function check_all() {
	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "amount");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#keepAmount").val(rowAmountVa);
	$("#keepAmount_v").val(moneyFormat2(rowAmountVa));
	return true;
}

/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_daily_keep&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
//ѡ��Ƭ��Ϣ
function selectCard(){
	showOpenWin("?model=asset_assetcard_assetcard"
			+ "&action=selectCard"
			,1,500,900);
}
//���ÿ�Ƭ����
function setDatas(rows){
	var objGrid = $("#purchaseProductTable");
	for(var i = 0; i < rows.length ; i++){
		//�жϿ�Ƭ�����Ƿ��Ѵ���
		var assetIdArr = objGrid.yxeditgrid("getCmpByCol","assetCode");
		var isExist = false;
		if(assetIdArr.length > 0){
			assetIdArr.each(function(){
				if(this.value == rows[i].assetCode){
					isExist = true;
					alert("�벻Ҫѡ����ͬ���ʲ�" );
					return false;
				}
			});
		}
		//����Ѿ��ظ��ˣ��Ͳ��ܼ���ѡ��
		if(isExist){
			return false;
		}
		//���»�ȡ����
		var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");
		//������
		objGrid.yxeditgrid("addRow",tbRowNum);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetCode",rows[i].assetCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetName",rows[i].assetName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"userId",rows[i].userId);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"userName",rows[i].userName);
	}
}

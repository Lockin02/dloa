$(function() {
	//�ʼ���Ⱦ
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	/**
	 * ѡ��Ƭ���Զ��������ԭֵ����Ϣ
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		var $spec = g.getCmpByRowAndCol(rowNum, 'spec');
		$spec.val(rowData.spec);
		// ����ֻ�����˹���������Կ���Ա���մ�ģʽ���
		// ��ƬrowData.origina���ԭֵ��(rowNum, 'origina')Ϊ�ӱ��ԭֵ.
		var $origina = g.getCmpByRowAndCol(rowNum, 'origina');
		$origina.val(rowData.origina);

		var $originav = $("#" + $origina.attr('id') + "_v");
		$originav.val(moneyFormat2(rowData.origina));

		var $salvage = g.getCmpByRowAndCol(rowNum, 'salvage');
		$salvage.val(rowData.salvage);

		var $salvagev = $("#" + $salvage.attr('id') + "_v");
		$salvagev.val(moneyFormat2(rowData.salvage));

		var $buyDate = g.getCmpByRowAndCol(rowNum, 'buyDate');
		$buyDate.val(rowData.buyDate);

		var $depreciation = g.getCmpByRowAndCol(rowNum, 'depreciation');
		$depreciation.val(rowData.depreciation);

		var $depreciationv = $("#" + $depreciation.attr('id') + "_v");
		$depreciationv.val(moneyFormat2(rowData.depreciation));

	}

	$("#purchaseProductTable").yxeditgrid({
		objName : 'scrap[item]',
		url : '?model=asset_assetcard_assetcard&action=listJson',
		isAddAndDel : false,
		param : {
			'id' : $('#assetId').val(),
			'useStatusCode' : 'SYZT-XZ',
			'isDel' : '0',
			'isScrap':'0'

		},
		event : {
			reloadData : function(data) {
				countAmount();
			}
		},
		colModel : [{
			display : '��Ƭ���',
			name : 'assetCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true
//			,process : function($input, rowData) {
//				var rowNum = $input.data("rowNum");
//				var g = $input.data("grid");
//				$input.yxcombogrid_asset({
//					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
//					nameCol : 'assetCode',
//					gridOptions : {
//						param : {
//							'useStatusCode' : 'SYZT-XZ',
//							'isDel' : '0',
//							'isScrap':'0'
//						},
//						event : {
//							row_dblclick : (function(rowNum) {
//								return function(e, row, rowData) {
//									var $cmps = g.getCmpByCol('assetId');
//									var isReturn = false;
//									$cmps.each(function() {
//										if ($(this).val() == rowData.id) {
//											alert("�벻Ҫѡ����ͬ���ʲ�.");
//											isReturn = true;
//										}
//									});
//									if (!isReturn) {
//										var $assetName = g.getCmpByRowAndCol(
//												rowNum, 'assetName');
//										$assetName.val(rowData.assetName);
//										selectAssetFn(g, rowNum, rowData);
//									} else {
//										return false;
//									}
//									var $salvage = g.getCmpByRowAndCol(
//											rowNum, 'salvage');
//									$salvage.focus(function(){
//										countAmount();
//									});
//									$salvage.focus()
//								}
//							})(rowNum)
//						}
//					}
//				});
//			},
//			// blur ʧ������������������ķ���
//			event : {
//				blur : function() {
//					countAmount();
//				}
//			}
		}, {
			display : '�ʲ�����',
			name : 'assetName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
//			,process : function($input, rowData) {
//				var rowNum = $input.data("rowNum");
//				var g = $input.data("grid");
//				$input.yxcombogrid_asset({
//					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
//					gridOptions : {
//						param : {
//							'useStatusCode' : 'SYZT-XZ',
//							'isDel' : '0',
//							'isScrap':'0'
//						},
//						searchId : '',// ����idֵ������������
//						event : {
//							row_dblclick : (function(rowNum) {
//								return function(e, row, rowData) {
//									var $cmps = g.getCmpByCol('assetId');
//									var isReturn = false;
//									$cmps.each(function() {
//										if ($(this).val() == rowData.id) {
//											alert("�벻Ҫѡ����ͬ���ʲ�.");
//											isReturn = true;
//										}
//									});
//									if (!isReturn) {
//										var $assetCode = g.getCmpByRowAndCol(
//												rowNum, 'assetCode');
//										$assetCode.val(rowData.assetCode);
//										selectAssetFn(g, rowNum, rowData);
//									} else {
//										return false;
//									}
//									$salvage = g.getCmpByRowAndCol(
//												rowNum, 'salvage');
//									$salvage.focus(function(){
//										countAmount();
//									});
//									$salvage.focus();
//								}
//							})(rowNum)
//						}
//					}
//				});
//			},
//			// blur ʧ������������������ķ���
//			event : {
//				blur : function() {
//					countAmount();
//				}
//			}
		}, {
			display : '�ʲ�Id',
			name : 'assetId',
			process : function($input,row){
				var assetId = row.id;
				$input.val(assetId);
			},
			type : 'hidden'
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '��������',
			name : 'buyDate',
			// type : 'date',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '�ʲ�ԭֵ',
			name : 'origina',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '��ֵ',
			name : 'salvage',
			tclass : 'txtshort',
			readonly : true,
			type : 'money'
		}, {
			display : '��ֵ',
			name : 'netValue',
			tclass : 'txtshort',
			readonly : true,
			type : 'money'
		}, {
			display : '�����۾�',
			name : 'depreciation',
			tclass : 'txtshort',
			readonly : true,
			type : 'money'
//		}, {
//			display : '����״̬',
//			name : 'sellStatus',
//			value : 'δ����',
//			readonly : true,
//			type : 'hidden'
		}, {
			display : '����',
			name : 'deploy',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]

	});
	// ѡ����Ա���
	$("#proposer").yxselect_user({
		hiddenId : 'proposerId',
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
	// ѡ����Ա���
	$("#payer").yxselect_user({
		hiddenId : 'payerId',
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#TO_NAME').val($('#payer').val());
					$('#TO_ID').val($('#payerId').val());
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
		"proposer" : {
			required : true
		},
		"scrapNum" : {
			required : true,
			custom : ['onlyNumber']
		},
		"amount" : {
			required : true,
			custom : ['money']
		},
		"reason" : {
			required : true
		},
		"scrapDeal" : {
			required : true
		},
//		"hasAccount" : {
//			required : true
//		},
		"payer" : {
			required : true
		},
		"amount_v" : {
			required : true,
			custom : ['money']
		}
	});

});

// ���ݴӱ�Ĳ�ֵ��̬����Ӧ���ܽ��
function countAmount() {
	// ��ȡ��ǰ����������Ƭ���ʲ���
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurShowRowNum");
	//��������
	$("#scrapNum").val(curRowNum);
	var rowsalvageVa = 0;
	var rownetValueVa = 0;
	var salvages = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "salvage");
	salvages.each(function() {
		//$(this).val()��ȡ����ֵ
		rowsalvageVa = accAdd(rowsalvageVa, $("#"+$(this).attr('id')+"_v").val(), 2);
	});
	var netValues = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "netValue");
	netValues.each(function() {
		//$(this).val()��ȡ����ֵ
		rownetValueVa = accAdd(rownetValueVa, $("#"+$(this).attr('id')+"_v").val(), 2);
	});
	//�ܲ�ֵ
	$("#salvage").val(rowsalvageVa);
	$("#salvage_v").val(moneyFormat2(rowsalvageVa));
	//�ܾ�ֵ
	$("#netValue").val(rownetValueVa);
	$("#netValue_v").val(moneyFormat2(rownetValueVa));
	return true;
}
/*
 * �ύ����ȷ��
 */
function confirmAudit() {
	if (confirm("ȷ��Ҫ�ύ����ȷ����?")) {
		checkCardStatus();//��鿨Ƭ״̬
		$("#form1").attr("action",
				"?model=asset_disposal_scrap&action=add&actType=finance");
		$("#form1").submit();

	} else {
		return false;
	}
}
//����ύ����ȷ�ϵĿ�Ƭ���Ƿ���ڷ�����״̬,���ڲ������ύ
function checkCardStatus(){
	var assetIds = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "assetId");
	var assetIdArr = [];
	assetIds.each(function() {
		assetIdArr.push($(this).val());
	});
	var responseText = $.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=checkCardStatus',
		data : {
			'assetIdArr' : assetIdArr
		},
		async : false
	}).responseText;
	var data = eval("(" + responseText + ")");
	if(data.length != 0){
		alert("��Ƭ���Ϊ��"+data+"���Ŀ�Ƭ���������ϴ������������ύ��");
		exit();
	}
}
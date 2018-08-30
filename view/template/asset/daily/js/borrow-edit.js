function checkAsset(assetCode){
	var flag=0
	 $.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=checkAsset',
		data : {
			assetCode : assetCode
		},
	    async: false,
		success : function(data) {
			if(data==0){
				return flag=0;
			}else{
				return flag=1;
			}
		}
	})
	return flag
}
$(function() {

	/**
	 * ѡ��Ƭ���Զ��������ԭֵ����Ϣ
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		var $spec = g.getCmpByRowAndCol(rowNum, 'spec');
		$spec.val(rowData.spec);

		var $buyDate = g.getCmpByRowAndCol(rowNum, 'buyDate');
		$buyDate.val(rowData.buyDate);
		var $alreadyDay = g.getCmpByRowAndCol(rowNum, 'alreadyDay');
		$alreadyDay.val(rowData.alreadyDay);
		var $estimateDay = g.getCmpByRowAndCol(rowNum, 'estimateDay');
		$estimateDay.val(rowData.estimateDay);

		var $residueYears = g.getCmpByRowAndCol(rowNum, 'residueYears');
		$residueYears.val(rowData.estimateDay - rowData.alreadyDay);

	}
	$("#borrowTable").yxeditgrid({
		objName : 'borrow[borrowitem]',
		url : '?model=asset_daily_borrowitem&action=listJson',
		param : {
			borrowId : $("#borrowId").val()
		},
		colModel : [{
			display : '��������Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '��Ӧ��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_assetrequireitem({
					hiddenId : 'borrowTable_cmp_productId' + rowNum,
					nameCol : 'productName',
					gridOptions : {
						// ���˿�Ƭ,ֻ��ʾ����״̬�Ŀ�Ƭ
						 param : {
							 'mainId' : $('#requireId').val()
						 },
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $productId = g.getCmpByRowAndCol(
											rowNum, 'productId');
									$productId.val(rowData.productId);
									var $productName = g.getCmpByRowAndCol(
											rowNum, 'productName');
									$productName.val(rowData.description);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
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
					hiddenId : 'borrowTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						// ���˿�Ƭ,ֻ��ʾ����״̬�Ŀ�Ƭ
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'machineCodeSearch':'0',
							'isDel' : '0',
							'idle' : '0',
							'belongTo' : '0',
							'isScrap':'0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
//									if(checkAsset(rowData.assetCode)!=0){
//										alert("���ʲ��ѽ������ѡ�������ʲ�.");
//										return false;
//									}
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
					hiddenId : 'borrowTable_cmp_assetId' + rowNum,
					gridOptions : {
						// ���˿�Ƭ,ֻ��ʾ����״̬�Ŀ�Ƭ
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'machineCodeSearch':'0',
							'isDel' : '0',
							'idle' : '0',
							'belongTo' : '0',
							'isScrap':'0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
//									if(checkAsset(rowData.assetCode)!=0){
//										alert("���ʲ��ѽ������ѡ�������ʲ�.");
//										return false;
//									}
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
			display : '��������',
			name : 'buyDate',
			// type:'date',
			readonly : true
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : 'Ԥ��ʹ���ڼ���',
			name : 'estimateDay',
			tclass : 'txtshort',
			readonly : true
		}, {
//			display : '�Ѿ�ʹ���ڼ���',
//			name : 'alreadyDay',
//			tclass : 'txtshort',
//			readonly : true
//		}, {
			display : 'ʣ��ʹ���ڼ���',
			name : 'residueYears',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	// ѡ��������Ա�������
//	$("#chargeMan").yxselect_user({
//		hiddenId : 'chargeManId',
//		mode : 'single'
//	});
	// ѡ�����������
	$("#reposeMan").yxselect_user({
		hiddenId : 'reposeManId',
		isGetDept : [true, "deptId", "deptName"],
		mode : 'single'
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"billNo" : {
			required : true
		},
//		"borrowCustome" : {
//			required : true
//		},
		"deptName" : {
			required : true
		},
		"chargeMan" : {
			required : true
		},
		"borrowDate" : {
			required : true
		},
		"predictDate" : {
			required : true
		},
		"reposeMan" : {
			required : true

		}
	});
	// �����ͻ����
	$("#borrowCustome").yxcombogrid_customer({
		hiddenId : 'borrowCustomeId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
				}
			}
		}
	});

});

/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_daily_borrow&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}

/**
 *
 * ����֤
 */
function checkForm(obj) {
	var s = plusDateInfo('borrowDate', 'predictDate');

	if (s <= 0) {
		alert("Ԥ�ƹ黹���ڲ������ڽ������ڣ�"); // ʱ����֤
		$(obj).val('');
	}

}
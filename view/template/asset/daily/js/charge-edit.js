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
		var $assetId = g.getCmpByRowAndCol(rowNum, 'assetId');
		$assetId.val(rowData.id);
		var $buyDate = g.getCmpByRowAndCol(rowNum, 'buyDate');
		$buyDate.val(rowData.buyDate);
		var $alreadyDay = g.getCmpByRowAndCol(rowNum, 'alreadyDay');
		$alreadyDay.val(rowData.alreadyDay);
		var $spec = g.getCmpByRowAndCol(rowNum, 'spec');
		$spec.val(rowData.spec);
		var $estimateDay = g.getCmpByRowAndCol(rowNum, 'estimateDay');
		$estimateDay.val(rowData.estimateDay);
		var $residueYears = g.getCmpByRowAndCol(rowNum, 'residueYears');
		$residueYears.val(rowData.estimateDay - rowData.alreadyDay);
	}

	$("#purchaseProductTable").yxeditgrid({
		objName : 'charge[item]',
		url : '?model=asset_daily_chargeitem&action=listJson',
		param : {
			allocateID : $("#allocateID").val(),
			assetId : $("#assetId").val()
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
					hiddenId : 'purchaseProductTable_cmp_productId' + rowNum,
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
					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						// ���˿�Ƭ,ֻ��ʾ����״̬�Ŀ�Ƭ
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'isDel' : '0',
							'machineCodeSearch':'0',
							'belongTo' : '0',
							'idle' : '0',
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
					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
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
		},
				// {
				// display:'sequence',
				// name : 'sequence',
				// type:'hidden'
				// },
				{
					display : '�ʲ�Id',
					name : 'assetId',
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
					display : 'Ԥ��ʹ���ڼ���',
					name : 'estimateDay',
					tclass : 'txtshort',
					readonly : true
				}, {
//					display : '�Ѿ�ʹ���ڼ���',
//					name : 'alreadyDay',
//					tclass : 'txtshort',
//					readonly : true
//				}, {
					display : 'ʣ��ʹ���ڼ���',// ���ڿ�Ƭ��Ԥ��ʹ���ڼ�����ȥ��ʹ���ڼ���
					name : 'residueYears',
					tclass : 'txtshort',
					readonly : true
				// event : {
				// blur : function(e) {
				// //����ʣ��ʹ������:��Ƭ��Ԥ��ʹ���ڼ�����ȥ��ʹ���ڼ���
				// var rownum = $(this).data('rowNum');// �ڼ���
				// var colnum = $(this).data('colNum');// �ڼ���
				// var grid = $(this).data('grid');// ������
				// var estimateDay =
				// grid.getCmpByRowAndCol(rownum,'estimateDay').val();
				// var alreadyDay =
				// grid.getCmpByRowAndCol(rownum,'alreadyDay').val();
				// var $residueYears = grid.getCmpByRowAndCol(rownum,
				// 'residueYears');
				// $residueYears.val(accSub(estimateDay,alreadyDay));
				// }
				// }
				}, {
					display : '��ע',
					name : 'remark',
					tclass : 'txt'
				}]

	});
	// ѡ����Ա���
	$("#chargeMan").yxselect_user({
		hiddenId : 'chargeManId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"billNo" : {
			required : true

		},
		"chargeMan" : {
			required : true
		},
		"chargeDate" : {
			required : true,
			custom : ['date']
		}
	});

});
/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_daily_charge&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}

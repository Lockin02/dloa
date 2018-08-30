$(function() {
	/**
	 * ѡ��Ƭ���Զ��������ԭֵ����Ϣ
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		var $assetId = g.getCmpByRowAndCol(rowNum, 'assetId');
		$assetId.val(rowData.id);

		var $spec = g.getCmpByRowAndCol(rowNum, 'spec');
		$spec.val(rowData.spec);
		// ����ֻ�����˹���������Կ���Ա���մ�ģʽ���
		// ��ƬrowData.origina���ԭֵ��(rowNum, 'original')Ϊ�ӱ��ԭֵ.
		// var $original = g.getCmpByRowAndCol(rowNum, 'original');
		// $original.val(rowData.origina);
		var $englishName = g.getCmpByRowAndCol(rowNum, 'englishName');
		$englishName.val(rowData.englishName);

		var $buyDate = g.getCmpByRowAndCol(rowNum, 'buyDate');
		$buyDate.val(rowData.buyDate);

		var $alreadyDay = g.getCmpByRowAndCol(rowNum, 'alreadyDay');
		$alreadyDay.val(rowData.alreadyDay);

		var $beforeUse = g.getCmpByRowAndCol(rowNum, 'beforeUse');
		$beforeUse.val(rowData.useType);

		if(rowData.salvage){
			g.setRowColValue(rowNum,'salvage',rowData.salvage,true);
		}else{
			g.setRowColValue(rowNum,'salvage',0,true);
		}
		if(rowData.depreciation){
			g.setRowColValue(rowNum,'depreciation',rowData.depreciation,true);
		}else{
			g.setRowColValue(rowNum,'depreciation',0,true);
		}

		var $equip = g.getCmpByRowAndCol(rowNum, 'equip');
		$equip.children().unbind("click");
		$equip.unbind("click");
		$equip.click((function(assetCode) {
			return function() {
				window
						.open('?model=asset_assetcard_equip&action=toPage&assetCode='
								+ assetCode);
			}
		})(rowData.assetCode));
	}

	$("#purchaseProductTable").yxeditgrid({
		objName : 'sell[item]',
		url : '?model=asset_disposal_sellitem&action=listJson',
		event : {
			removeRow : function(t, rowNum, rowData) {
				countAmount();
			}
		},
		param : {
			sellID : $("#sellID").val(),
			assetId : $("#assetId").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��Ƭ���',
			name : 'assetCode',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : {
							'isDel' : '0',
							'isSell' : '0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetId');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.id) {
											alert("�벻Ҫѡ����ͬ���ʲ�.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetName = g.getCmpByRowAndCol(
												rowNum, 'assetName');
										$assetName.val(rowData.assetName);
										var $assetCode = g.getCmpByRowAndCol(
												rowNum, 'assetCode');
										$assetCode.val(rowData.assetCode);
										selectAssetFn(g, rowNum, rowData);
										countAmount();
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
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'cmp_assetId' + rowNum,
					gridOptions : {
						param : {
							'isDel' : '0',
							'isSell' : '0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetId');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.id) {
											alert("�벻Ҫѡ����ͬ���ʲ�.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetCode = g.getCmpByRowAndCol(
												rowNum, 'assetCode');
										$assetCode.val(rowData.assetCode);
										var $assetName = g.getCmpByRowAndCol(
												rowNum, 'assetName');
										$assetName.val(rowData.assetName);
										selectAssetFn(g, rowNum, rowData);
										countAmount();
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
			display : 'Ӣ������',
			name : 'englishName',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '��������',
			name : 'buyDate',
			type : 'date',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '�����豸',
			name : 'equip',
			type : 'statictext',
			process : function(e, data) {
				if (data) {
					var $href = $("<a>��ϸ</a>");
					$href.attr("href", "#");
					$href.click(function() {
						window
								.open('?model=asset_assetcard_equip&action=toPage&assetCode='
										+ data.assetCode);
					})
					return $href;
				} else {
					return '<a href="#" >��ϸ</a>';
				}
			}

		},
				// {
				// display:'��������',
				// name : 'estimateDay',
				// tclass : 'txtshort'
				// },
				{
					display : '�Ѿ�ʹ���ڼ���',// ��ʹ���ڼ���alreadyDay
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly : true
				}, {
					display : '�۳�����',// ����
					name : 'deptName',
					tclass : 'txtshort'
				}, {
					display : '�۳�ǰ��;',// ��;useType
					name : 'beforeUse',
					tclass : 'txtshort',
					readonly : true
				}, {
					display : '���۾ɽ��',// �ۼ��۾�depreciation
					name : 'depreciation',
					tclass : 'txtshort',
					type : 'money',
					readonly : true
				}, {
					display : '�����ֵ',// Ԥ�ƾ���ֵsalvage
					name : 'salvage',
					tclass : 'txtshort',
					type : 'money',
					readonly : true
				}
				// , {
				// display:'���۾ɶ�',//��������������ġ�
				// name : 'monthDepr',
				// tclass : 'txtshort'
				// }
				, {
					display : '��ע',// �������ɸ�
					name : 'remark',
					tclass : 'txt'
				}]

	});
	// ѡ����Ա���
	$("#seller").yxselect_user({
		hiddenId : 'sellerId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * ��֤��Ϣ
	 */

	validate({
		"billNo" : {
			required : true
		},
		"seller" : {
			required : true
		},
		"sellNum" : {
			required : true,
			custom : ['onlyNumber']
		},
		"sellAmount" : {
			required : true,
			custom : ['money']
		},
		"donationDate" : {
			required : true
		}
	});

});
// ���ݴӱ�Ĳ�ֵ��̬����Ӧ���ܽ��
function countAmount() {
	// ��ȡ��ǰ����������Ƭ���ʲ���
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurShowRowNum")
	$("#sellNum").val(curRowNum);
	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "salvage");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#sellAmount").val(rowAmountVa);
	$("#sellAmount_v").val(moneyFormat2(rowAmountVa));
	return true;
}
/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_disposal_sell&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
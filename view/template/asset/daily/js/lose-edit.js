$(function() {
	/**
	 * ѡ��Ƭ���Զ��������ԭֵ����Ϣ
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		var $assetId = g.getCmpByRowAndCol(rowNum, 'assetId');
		$assetId.val(rowData.id);
		// //����ֻ�����˹���������Կ���Ա���մ�ģʽ���
		// //��ƬrowData.origina���ԭֵ��(rowNum, 'original')Ϊ�ӱ��ԭֵ.
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

		var $alreadyDay = g.getCmpByRowAndCol(rowNum, 'alreadyDay');
		$alreadyDay.val(rowData.alreadyDay);

		var $depreciation = g.getCmpByRowAndCol(rowNum, 'depreciation');
		$depreciation.val(rowData.depreciation);

		var $depreciationv = $("#" + $depreciation.attr('id') + "_v");
		$depreciationv.val(moneyFormat2(rowData.depreciation));

		var $spec = g.getCmpByRowAndCol(rowNum, 'spec');
		$spec.val(rowData.spec);

		var $orgId = g.getCmpByRowAndCol(rowNum, 'orgId');
		$orgId.val(rowData.orgId);

		var $useOrgId = g.getCmpByRowAndCol(rowNum, 'useOrgId');
		$useOrgId.val(rowData.useOrgId);

		var $orgName = g.getCmpByRowAndCol(rowNum, 'orgName');
		$orgName.val(rowData.orgName);

		var $useOrgName = g.getCmpByRowAndCol(rowNum, 'useOrgName');
		$useOrgName.val(rowData.useOrgName);

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
		objName : 'lose[item]',
		url : '?model=asset_daily_loseitem&action=listJson',
				event : {
			removeRow : function(t, rowNum, rowData) {
				countAmount();
			}
		},
		param : {
			loseId : $("#loseId").val(),
			assetId : $("#assetId").val()
		},
		colModel : [{
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
			},
			// blur ʧ������������������ķ���
			event : {
				blur : function() {
					countAmount();
				}
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
			},
			// blur ʧ������������������ķ���
			event : {
				blur : function() {
					countAmount();
				}
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
					display : '��������Id',
					name : 'orgId',
					tclass : 'txt',
					type : 'hidden'
				}, {
					display : '��������',// orgName
					name : 'orgName',
					tclass : 'txtmiddle',
					readonly : true
				}, {
					display : 'ʹ�ò���Id',
					name : 'useOrgId',
					tclass : 'txtshort',
					type : 'hidden'
				}, {
					display : 'ʹ�ò���',// useOrgName
					name : 'useOrgName',
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
				}, {
					display : '����ԭֵ',
					name : 'origina',
					tclass : 'txtmiddle',
					readonly : true,
					type : 'money'
				}, {
					display : '�Ѿ�ʹ���ڼ���',
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly : true
				}, {
					display : '�ۼ��۾ɽ��',
					name : 'depreciation',
					tclass : 'txtmiddle',
					readonly : true,
					type : 'money'
				}, {
					display : '��ֵ',
					name : 'salvage',
					tclass : 'txtmiddle',
					readonly : true,
					type : 'money'
				}, {
					display : '��ע',
					name : 'remark',
					tclass : 'txt'
				}, {
					display : '�Ƿ񱨷�',
					name : 'isScrap',
					tclass : 'txt',
					value : '0',
					type : 'hidden'
				}]

	});
	// ѡ����Ա���
	$("#applicat").yxselect_user({
		hiddenId : 'applicatId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"billNo" : {
			required : true

		},
		"applicat" : {
			required : true
		},
		"loseDate" : {
			custom : ['date']
		},
		"loseAmount" : {
			custom : ['money']
		},
//		"realAmount" : {
//			custom : ['money']
//		},
		"loseNum" : {
			custom : ['onlyNumber']
		}
	});

});

// ���ݴӱ�Ĳ�ֵ��̬����Ӧ���ܽ��
function countAmount() {
	// ��ȡ��ǰ����������Ƭ���ʲ���
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurShowRowNum")
	$("#loseNum").val(curRowNum);

	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "salvage");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#loseAmount").val(rowAmountVa);
	$("#loseAmount_v").val(moneyFormat2(rowAmountVa));
	return true;
}
/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_daily_lose&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}

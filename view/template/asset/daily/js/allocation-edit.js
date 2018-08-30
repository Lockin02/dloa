$(function() {
	//���ݵ���������ʾ/���ز�����Ϣ��������Ϣ
	switch ($('#alloType').val()) {
		case 'DTD' :
			$(".outDeptType").show();
			$(".inDeptType").show();
			$(".outAgencyType").hide();
			$(".inAgencyType").hide();
			break;
		case 'ATA' :
			$(".outAgencyType").show();
			$(".inAgencyType").show();
			$(".outDeptType").hide();
			$(".inDeptType").hide();
			break;
	}
	
	$("#allocationTable").yxeditgrid({
		objName : 'allocation[allocationitem]',
		url : '?model=asset_daily_allocationitem&action=listJson',
		param : {
			allocateID : $("#allocateID").val(),
			// sequence : $("#sequence").val(),
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
					hiddenId : 'allocationTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'isDel' : '0',
							'isScrap' : '0',
							'belongTo' : '0',
							'machineCodeSearch':'0',
							'orgId' : $('#outDeptId').val(),
							'useProId' : $('#outProId').val()
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
										g.setRowColValue(rowNum,'assetName',rowData.assetName);
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
					hiddenId : 'allocationTable_cmp_assetId' + rowNum,
					gridOptions : {
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'isDel' : '0',
							'isScrap' : '0',
							'belongTo' : '0',
							'machineCodeSearch':'0',
							'orgId' : $('#outDeptId').val(),
							'useProId' : $('#outProId').val()
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
										g.setRowColValue(rowNum,'assetCode',rowData.assetCode);
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
			display : 'Ӣ������',
			name : 'englishName',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'buyDate',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 80
		}, {
			display : 'ԭֵ',
			name : 'origina',
			type : 'money',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 80
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '������',
			name : 'sequence',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '����',
			name : 'deploy',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '�����豸',
			name : 'equip',
			type : 'statictext',
			process : function(e, data) {
				if (data) {
					var $href = $("<a>��ϸ</a>");
					$href.attr("href", "#");
					$href.click(function() {
						window.open('?model=asset_assetcard_equip&action=toPage&assetId='
										+ data.assetId);
					})
					return $href;
				} else {
					return '<a href="#" >��ϸ</a>';
				}
			}
		}, {
			display : '��������',
			name : 'estimateDay',
			type : 'hidden'
		}, {
			display : '�Ѿ�ʹ���ڼ���',
			name : 'alreadyDay',
			type : 'hidden'
		}, {
			display : '���۾ɶ�',
			name : 'monthDepr',
			type : 'hidden'
		}, {
			display : '���۾ɽ��',
			name : 'depreciation',
			type : 'hidden'
		}, {
			display : '�����ֵ',
			name : 'salvage',
			type : 'hidden'
		}, {
			display : '����ǰ��;',
			name : 'beforeUse',
			type : 'hidden'

		}, {
			display : '�������;',
			name : 'afterUse',
			tclass : 'txtshort'

		}, {
			display : '����ǰ��ŵص�',
			name : 'beforePlace',
			type : 'hidden'

		}, {
			display : '������ŵص�',
			name : 'afterPlace',
			tclass : 'txtshort'

		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	// // ѡ������������
	// $("#outDeptName").yxselect_dept({
	// hiddenId : 'outDeptId',
	// mode : 'single'
	// });
	// // ѡ����벿�����
	// $("#inDeptName").yxselect_dept({
	// hiddenId : 'inDeptId',
	// mode : 'single'
	// });

	// ѡ��������������
	$("#proposer").yxselect_user({
		hiddenId : 'proposerId',
		mode : 'single'
	});
	// ѡ�����ȷ�������
	$("#recipient").yxselect_user({
		hiddenId : 'recipientId',
		mode : 'single'
	});
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"billNo" : {
			required : true
		},
		"moveDate" : {
			required : true
		},
//		"outDeptName" : {
//			required : true
//		},
//		"inDeptName" : {
//			required : true
//		},
		"proposer" : {
			required : true
		},
		"recipient" : {
			required : true
		}
	});

});

//ѡ��Ƭʱ������Ƭ��Ϣ
function selectAssetFn (g, rowNum, rowData) {
	g.setRowColValue(rowNum,'englishName',rowData.englishName);
	g.setRowColValue(rowNum,'spec',rowData.spec);
	g.setRowColValue(rowNum,'deploy',rowData.deploy);
	g.setRowColValue(rowNum,'buyDate',rowData.buyDate);
	g.setRowColValue(rowNum,'estimateDay',rowData.estimateDay);
	g.setRowColValue(rowNum,'alreadyDay',rowData.alreadyDay);
	g.setRowColValue(rowNum,'monthDepr',rowData.monthlyDepreciation);
	g.setRowColValue(rowNum,'depreciation',rowData.depreciation);
	g.setRowColValue(rowNum,'origina',rowData.origina,true);
	g.setRowColValue(rowNum,'salvage',rowData.salvage,true);
	g.setRowColValue(rowNum,'beforeUse',rowData.useType);
	g.setRowColValue(rowNum,'beforePlace',rowData.place);

	var $equip = g.getCmpByRowAndCol(rowNum, 'equip');
	$equip.children().unbind("click");
	$equip.unbind("click");
	$equip.click((function(id) {
		return function() {
			window.open('?model=asset_assetcard_equip&action=toPage&assetId='
				+ id);
		}
	})(rowData.id));
}

//���ȷ��
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_daily_allocation&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
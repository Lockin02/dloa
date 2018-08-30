$(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName : 'scrap[item]',
		title : '��Ƭ��Ϣ',
		isAddOneRow : false,
		event : {
			removeRow : function(t, rowNum, rowData) {
				countAmount();
			}
		},
		colModel : [{
			display : '��Ƭ���',
			name : 'assetCode',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					isFocusoutCheck : false,
					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'isDel' : '0',
							'isScrap' : '0'
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
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
									countAmount();
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
							'useStatusCode' : 'SYZT-XZ',
							'isDel' : '0',
							'isScrap' : '0'
						},
						searchId : '',// ����idֵ������������
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
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
									countAmount();
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
			// }, {
			// display : '����״̬',
			// name : 'sellStatus',
			// value : 'δ����',
			// readonly : true,
			// type : 'hidden'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
});
//�ύ����ʱ��֤
function checkForm(thisVal){
	if(checkCard()){//��֤��Ƭ��Ϣ�Ϸ���
		if(thisVal == 'audit'){
			if (confirm("ȷ��Ҫ�ύ����ȷ����?")) {
				$("#form1").attr("action","?model=asset_disposal_scrap&action=add&actType=finance");
			}else{
				return false;
			}
		}else{
			$("#form1").attr("action","?model=asset_disposal_scrap&action=add");
		}
		$("#form1").submit();
	}
}
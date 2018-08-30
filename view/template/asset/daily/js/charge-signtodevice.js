$(document).ready(function() {
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"billNo" : {
			required : true
		}
	});
	
	$("#itemTable").yxeditgrid({
		objName:'charge[item]',
		url:'?model=asset_daily_chargeitem&action=listJson',
		title : '�豸�嵥',
		param:{
			allocateID : $("#id").val()
		},
		isAdd : false,
		colModel : [{
				display : 'id',
				name : 'id',
				type : 'hidden'
		},{
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			width : 80
		}, {
			display : '�豸����',
			name : 'resourceName',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_esmdevice({
					hiddenId : g.el.attr('id')+ '_cmp_resourceId' + rowNum,
					width : 600,
					isFocusoutCheck : false,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'resourceTypeName').val(rowData.deviceType);
									g.getCmpByRowAndCol(rowNum,'resourceTypeId').val(rowData.typeid);
								}
							})(rowNum)
						}
					}
				}).attr("readonly",false);
			},
			width : 150
		}, {
			display : '��Ƭid',
			name : 'assetId',
			type : 'hidden'
		}, {
			display:'��Ƭ���',
			name : 'assetCode',
			tclass : 'readOnlyTxtNormal',
			readonly:true,
			width : 150
		}, {
			display:'�ʲ�����',
			name : 'assetName',
			tclass : 'readOnlyTxtNormal',
			readonly:true,
			width : 150
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'readOnlyTxtNormal',
			readonly:true,
			width : 150
		}, {
			display : '������',
			name : 'machineCode',
			tclass : 'readOnlyTxtNormal',
			readonly:true,
			width : 150
		}, {
			display : '���ű���',
			name : 'dpcoding',
			tclass : 'txt',
			width : 150
		}, {
			display : '�۾�',
			name : 'depreciation',
			tclass : 'txt',
			width : 80
		}, {
			display : '�۾�����',
			name : 'depreciationYear',
			tclass : 'txt',
			width : 80
		}]
   })
});
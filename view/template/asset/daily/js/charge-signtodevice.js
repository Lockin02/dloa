$(document).ready(function() {
	/**
	 * 验证信息
	 */
	validate({
		"billNo" : {
			required : true
		}
	});
	
	$("#itemTable").yxeditgrid({
		objName:'charge[item]',
		url:'?model=asset_daily_chargeitem&action=listJson',
		title : '设备清单',
		param:{
			allocateID : $("#id").val()
		},
		isAdd : false,
		colModel : [{
				display : 'id',
				name : 'id',
				type : 'hidden'
		},{
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '分类id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '设备分类',
			name : 'resourceTypeName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			width : 80
		}, {
			display : '设备名称',
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
			display : '卡片id',
			name : 'assetId',
			type : 'hidden'
		}, {
			display:'卡片编号',
			name : 'assetCode',
			tclass : 'readOnlyTxtNormal',
			readonly:true,
			width : 150
		}, {
			display:'资产名称',
			name : 'assetName',
			tclass : 'readOnlyTxtNormal',
			readonly:true,
			width : 150
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'readOnlyTxtNormal',
			readonly:true,
			width : 150
		}, {
			display : '机身码',
			name : 'machineCode',
			tclass : 'readOnlyTxtNormal',
			readonly:true,
			width : 150
		}, {
			display : '部门编码',
			name : 'dpcoding',
			tclass : 'txt',
			width : 150
		}, {
			display : '折旧',
			name : 'depreciation',
			tclass : 'txt',
			width : 80
		}, {
			display : '折旧年限',
			name : 'depreciationYear',
			tclass : 'txt',
			width : 80
		}]
   })
});
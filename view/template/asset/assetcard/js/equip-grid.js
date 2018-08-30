// 用于新增/修改后回调刷新表格

var show_page = function(page) {
	$("#equipGrid").yxgrid('reload');
};

$(function() {
	$("#equipGrid").yxgrid({
		model : 'asset_assetcard_equip',
		param : {
			'assetId' : $("#assetId").val()
		},
		title : '附属设备信息',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '设备编号',
			name : 'equipId',
			sortable : true
		}, {
			display : '设备名称',
			name : 'equipName',
			sortable : true,
			// 特殊处理字段函数
			process : function(v, row) {
				return row.equipName;
			}
		}, {
			display : '登记日期',
			name : 'regDate',
			sortable : true
		}, {
			display : '规格型号',
			name : 'spec',
			sortable : true
		}, {
			display : '计量单位',
			name : 'unit',
			sortable : true
		}, {
			display : '数量',
			name : 'num',
			sortable : true
		}, {
			display : '金额',
			name : 'account',
			sortable : true
		}, {
			display : '存放地点id',
			name : 'placeId',
			sortable : true,
			hide : true
		}, {
			display : '存放地点',
			name : 'place',
			sortable : true
		}, {
			display : '备注',
			name : 'remark',
			sortable : true
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '创建人id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建日期',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '录入人',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '修改人id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改日期',
			sortable : true,
			hide : true
		}],

		toViewConfig : {
			formWidth : 900,
			/**
			 * 新增表单默认高度
			 */
			formHeight : 380
		},



		sortorder : "ASC"

	});

});
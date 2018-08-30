$(document).ready(function() {
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_resourceapplydet&action=listJson',
//		title : '设备需求明细',
		param : {
			'mainId' : $("#id").val(),
			'status' : '2'
		},
		objName : 'resourceapply[detail]',
		tableClass : 'form_in_table',
		isAdd : false,
		async : false,
		colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden'
        },{
			name : 'status',
			display : '状态',
			process : function(v){
				if(v == 0){
					return '待确认';
				}else if(v == 1){
					return '已确认';
				}else if(v == 2){
					return '撤回待确认';
				}
			},
			width : 60,
			type : 'statictext'
		}, {
			name : 'resourceTypeName',
			display : '设备类型',
			process : function(v,row){
				if(row.resourceId == "0"){
					return "<span class='red'>-- 新设备 --</span>";
				}else{
					return v;
				}
			},
			width : 80,
			type : 'statictext'
		}, {
			name : 'resourceName',
			display : '设备名称',
			width : 80,
			type : 'statictext'
		}, {
			name : 'number',
			display : '数量',
			width : 40,
			type : 'statictext',
			isSubmit : true
		}, {
			name : 'exeNumber',
			display : '已下达数量',
			width : 40,
			type : 'statictext',
			isSubmit : true
		}, {
			name : 'backNumber',
			display : '撤回数量',
			width : 40,
			type : 'statictext',
			isSubmit : true
		}, {
			name : 'unit',
			display : '单位',
			width : 40,
			type : 'statictext'
		}, {
			name : 'planBeginDate',
			display : '领用日期',
			width : 70,
			type : 'statictext'
		}, {
			name : 'planEndDate',
			display : '归还日期',
			width : 70,
			type : 'statictext'
		}, {
			name : 'useDays',
			display : '使用天数',
			width : 40,
			type : 'statictext'
		}, {
			name : 'price',
			display : '单设备折旧',
			align : 'right',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 60,
			type : 'statictext'
		}, {
			name : 'amount',
			display : '设备成本',
			align : 'right',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80,
			type : 'statictext'
		}, {
			name : 'remark',
			display : '备注',
			type : 'statictext'
		}, {
			name : 'feeBack',
			display : '预计筹备天数反馈',
			width : 80,
			type : 'statictext'
		}]
	});
});
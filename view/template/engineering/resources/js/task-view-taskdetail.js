$(document).ready(function() {
	//控制类型显示
	var taskType = $("#taskType").val();
	$("#"+taskType+"list").show();

	$("#ZK").yxeditgrid({
		isAddAndDel : false,
		isAddOneRow : false,
		url : '?model=engineering_resources_taskdetail&action=listJson',
		type:'view',
		param : {
			'taskId' : $("#id").val(),
			'taskType' : 'ZK',
			'areaPrincipalId' : $("#userId").val()
		},
		objName : 'task[ZK]',
		colModel : [{
			display : '设备id',
			name : 'proResourceId',
			type : 'hidden'
		},{
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '设备编码',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '分类id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '设备分类',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备名称',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '负责人ID',
			name : 'areaPrincipalId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '单位',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '预计借用日期',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '预计归还日期',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '使用天数',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备折价',
			name : 'price',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '预计成本',
			name : 'amount',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '任务id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '任务名称',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '项目id',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '项目名称',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '项目编号',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '是否接收',
			name : 'isRe',
			type : 'hidden'
		}, {
			display : '数量',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '库存地',
			name : 'area',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '负责人',
			name : 'areaPrincipal',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'rowNum',
			name : 'rowNum',
			type : 'hidden'
		}, {
			display : '进展',
			name : 'makeProgress',
			type : 'statictext',
			width : 150
		}, {
			display : '是否接收',
			name : 'isRe',
			type : 'statictext',
			process : function(v){
				if(v=='0'){
				   return "×";
				}else if(v=='1'){
				   return "√";
				}else{
				   return "";
				}
			}
		}]
	})

	//待申购/生产设备
	$("#DSG").yxeditgrid({
		objName : 'task[DSG]',
		isAddAndDel : false,
		isAddOneRow : false,
		url : '?model=engineering_resources_taskdetail&action=listJson',
		type:'view',
		param : {
			'taskId' : $("#id").val(),
			'taskType' : 'DSG',
			'areaPrincipalId' : $("#userId").val()
		},
		colModel : [{
			display : '设备id',
			name : 'proResourceId',
			type : 'hidden'
		},{
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '设备编码',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '分类id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '设备分类',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备名称',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '负责人ID',
			name : 'areaPrincipalId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '单位',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '预计借用日期',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '预计归还日期',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '使用天数',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备折价',
			name : 'price',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '预计成本',
			name : 'amount',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '任务id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '任务名称',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '项目id',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '项目名称',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '项目编号',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '是否接收',
			name : 'isRe',
			type : 'hidden'
		}, {
			display : '数量',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '库存地',
			name : 'area',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '负责人',
			name : 'areaPrincipal',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'rowNum',
			name : 'rowNum',
			type : 'hidden'
		}, {
			display : '进展',
			name : 'makeProgress',
			type : 'statictext',
			width : 150
		}, {
			display : '是否接收',
			name : 'isRe',
			type : 'statictext',
			process : function(v){
				if(v=='0'){
				   return "×";
				}else if(v=='1'){
				   return "√";
				}else{
				   return "";
				}
			}
		}]
	})
//无法调配
	$("#WFDP").yxeditgrid({
		objName : 'task[WFDP]',
		isAddAndDel : false,
		isAddOneRow : false,
		url : '?model=engineering_resources_taskdetail&action=listJson',
		type:'view',
		param : {
			'taskId' : $("#id").val(),
			'taskType' : 'WFDP',
			'areaPrincipalId' : $("#userId").val()
		},
		colModel : [{
			display : '设备id',
			name : 'proResourceId',
			type : 'hidden'
		},{
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '设备编码',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '分类id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '设备分类',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备名称',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '负责人ID',
			name : 'areaPrincipalId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '单位',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '预计借用日期',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '预计归还日期',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '使用天数',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备折价',
			name : 'price',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '预计成本',
			name : 'amount',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '任务id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '任务名称',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '项目id',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '项目名称',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '项目编号',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '是否接收',
			name : 'isRe',
			type : 'hidden'
		}, {
			display : '数量',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '库存地',
			name : 'area',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '负责人',
			name : 'areaPrincipal',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'rowNum',
			name : 'rowNum',
			type : 'hidden'
		}, {
			display : '进展',
			name : 'makeProgress',
			type : 'statictext',
			width : 150
		}, {
			display : '是否接收',
			name : 'isRe',
			type : 'statictext',
			process : function(v){
				if(v=='0'){
				   return "×";
				}else if(v=='1'){
				   return "√";
				}else{
				   return "";
				}
			}
		}]
	})

});



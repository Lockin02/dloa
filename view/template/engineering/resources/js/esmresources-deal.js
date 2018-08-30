$(document).ready(function() {

	$("#esmresourcesTable").yxeditgrid({
		objName : 'esmresources',
		url : '?model=engineering_resources_esmresources&action=listJson',
		param : {
			'ids' : $("#ids").val()
		},
		// type:'edit',
		isAddAndDel : false,
		param : {
			projectId : $("#projectId").val(),
			ids : $("#ids").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden',
			readonly : true
		},{
			display : '资源名称',
			name : 'resourceName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '数量',
			name : 'number',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '单位',
			name : 'unit',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '需求开始日期',
			name : 'planBeginDate',
			readonly : true,
			tclass : 'readOnlyTxtItem'
		}, {
			display : '需求结束日期',
			name : 'planEndDate',
			readonly : true,
			tclass : 'readOnlyTxtItem'
		}, {
			display : '使用天数',
			name : 'useDays',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '处理结果',
			name : 'dealStatus',
			type : 'select',
			datacode : 'GCZYCLZT'
		}, {
			display : '处理反馈',
			name : 'dealResult',
			tclass : 'txt'
		}]
	})

	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({

	});
});

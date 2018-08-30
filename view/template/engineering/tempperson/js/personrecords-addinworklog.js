$(document).ready(function() {
	//初始化临聘人员记录
	initPersonrecords();

	validate();
})

//临聘人员记录
function initPersonrecords(){
	$("#importTable").yxeditgrid({
		url : '?model=engineering_tempperson_personrecords&action=listjson',
		param : {
			'worklogId' : $("#worklogId").val()
		},
		objName : 'personrecords',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '人员Id',
			name : 'personId',
			type : 'hidden'
		}, {
			display : '人员名称',
			name : 'personName',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_tempperson({
					hiddenId : 'importTable_cmp_personId' + rowNum,
					nameCol : 'personName',
					width : 600,
					gridOptions : {
						isTitle : true,
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'idCardNo').val(rowData.idCardNo);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '身份证号',
			name : 'idCardNo',
			readonly : true,
			tclass : 'readOnlyTxtNormal',
			type : 'hidden'
		}, {
			display : '工资及补助',
			name : 'money',
			type : 'money',
			validation : {
				required : true
			},
			tclass : 'txtmiddle'
		}, {
			display : '日期',
			name : 'thisDate',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			value : $("#executionDate").val()
		}, {
			display : '工作内容',
			name : 'workContent',
			tclass : 'txtlong'
		}, {
			display : '日志Id',
			name : 'worklogId',
			type : 'hidden',
			value : $("#worklogId").val()
		}, {
			display : '项目Id',
			name : 'projectId',
			type : 'hidden',
			value : $("#projectId").val()
		}, {
			display : '项目编号',
			name : 'projectCode',
			type : 'hidden',
			value : $("#projectCode").val()
		}, {
			display : '项目名称',
			name : 'projectName',
			type : 'hidden',
			value : $("#projectName").val()
		}, {
			display : '任务id',
			name : 'activityId',
			type : 'hidden',
			value : $("#activityId").val()
		}, {
			display : '任务名称',
			name : 'activityName',
			type : 'hidden',
			value : $("#activityName").val()
		}]
	})
}
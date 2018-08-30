$(document).ready(function() {
	//初始化测试卡信息
	initCardrecords();

	validate();
});

//初始化测试卡信息
function initCardrecords(){
	$("#importTable").yxeditgrid({
		url : '?model=cardsys_cardrecords_cardrecords&action=listjson',
		param : {
			'worklogId' : $("#worklogId").val()
		},
		objName : 'cardrecords',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '测试卡id',
			name : 'cardId',
			type : 'hidden'
		}, {
			display : '测试卡号',
			name : 'cardNo',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_cardsinfo({
					hiddenId : 'importTable_cmp_cardId' + rowNum,
					nameCol : 'cardNo',
					height : 300,
					gridOptions : {
						isTitle : true,
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
								}
							})(rowNum)
						}
					}
				});
			},
			tclass : 'txt'
		}, {
			display : '日期',
			name : 'useDate',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			value : $("#executionDate").val()
		}, {
			display : '办卡',
			name : 'openMoney',
			tclass : 'txtmiddle'
		}, {
			display : '充值',
			name : 'rechargerMoney',
			validation : {
				required : true
			},
			tclass : 'txtmiddle'
		}, {
			display : '使用人id',
			name : 'ownerId',
			type : 'hidden'
		}, {
			display : '使用人',
			name : 'ownerName',
			type : 'hidden'
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
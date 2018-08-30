$(document).ready(function() {
	//初始化临聘人员记录
	initCarRental();
	validate({
		"projectName" : {
			required : true
		},
		"carNo" : {
			required : true
		},
		"carType" : {
			required : true
		},
		"driver" : {
			required : true
		},
		"linkPhone" : {
			required : true
		}
	});
})



//临聘人员记录
function initCarRental(){
	$("#importTable").yxeditgrid({
		url : '?model=carrental_records_carrecordsdetail&action=listjson',
		param : {
			'worklogId' : $("#worklogId").val()
		},
		objName : 'carrecordsdetail',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '日期',
			name : 'useDate',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			value : $("#executionDate").val()
		}, {
			display : '租车性质',
			name : 'rentalType',
			datacode : 'GCZCXZ',
			type : 'select',
			validation : {
				required : true
			}
		}, {
			display : '车辆Id',
			name : 'carId',
			type : 'hidden'
		}, {
			display : '车牌',
			name : 'carNo',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_carinfo({
					hiddenId : 'importTable_cmp_carId' + rowNum,
					nameCol : 'carNo',
					width : 600,
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
			}
		}, {
			display : '里程(km)',
			name : 'mileage',
			tclass : 'txtshort'
		}, {
			display : '用车时长(小时)',
			name : 'useHours',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '租车费(元)',
			name : 'travelFee',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '油费(元)',
			name : 'fuelFee',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '路桥费（元）',
			name : 'roadFee',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '停车费（元）',
			name : 'parkingFee',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '有效LOG(小时)',
			name : 'effectiveLog',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '用途',
			name : 'useReson',
			tclass : 'txtmiddle'
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
$(document).ready(function() {
	//��ʼ����Ƹ��Ա��¼
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



//��Ƹ��Ա��¼
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
			display : '����',
			name : 'useDate',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			value : $("#executionDate").val()
		}, {
			display : '�⳵����',
			name : 'rentalType',
			datacode : 'GCZCXZ',
			type : 'select',
			validation : {
				required : true
			}
		}, {
			display : '����Id',
			name : 'carId',
			type : 'hidden'
		}, {
			display : '����',
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
			display : '���(km)',
			name : 'mileage',
			tclass : 'txtshort'
		}, {
			display : '�ó�ʱ��(Сʱ)',
			name : 'useHours',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '�⳵��(Ԫ)',
			name : 'travelFee',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '�ͷ�(Ԫ)',
			name : 'fuelFee',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '·�ŷѣ�Ԫ��',
			name : 'roadFee',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : 'ͣ���ѣ�Ԫ��',
			name : 'parkingFee',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '��ЧLOG(Сʱ)',
			name : 'effectiveLog',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '��;',
			name : 'useReson',
			tclass : 'txtmiddle'
		}, {
			display : '��־Id',
			name : 'worklogId',
			type : 'hidden',
			value : $("#worklogId").val()
		}, {
			display : '��ĿId',
			name : 'projectId',
			type : 'hidden',
			value : $("#projectId").val()
		}, {
			display : '��Ŀ���',
			name : 'projectCode',
			type : 'hidden',
			value : $("#projectCode").val()
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			type : 'hidden',
			value : $("#projectName").val()
		}, {
			display : '����id',
			name : 'activityId',
			type : 'hidden',
			value : $("#activityId").val()
		}, {
			display : '��������',
			name : 'activityName',
			type : 'hidden',
			value : $("#activityName").val()
		}]
	})
}
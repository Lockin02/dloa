$(document).ready(function() {
	$("#importTable").yxeditgrid({
		objName : 'esmresources',
		colModel : [{
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceCode',
			type : 'hidden'
		}, {
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
			tclass : 'readOnlyTxt'
		}, {
			display : '�豸����',
			name : 'resourceName',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_esmdevice({
					hiddenId : 'importTable_cmp_budgetId' + rowNum,
                    valueCol: 'virtualId',
                    isFocusoutCheck: false,
					width : 600,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'resourceTypeName').val(rowData.deviceType);
									g.getCmpByRowAndCol(rowNum,'resourceTypeId').val(rowData.typeid);
									g.getCmpByRowAndCol(rowNum,'resourceId').val(rowData.id);

									g.getCmpByRowAndCol(rowNum,'unit').val(rowData.unit);
									setMoney("importTable_cmp_price" + rowNum,rowData.discount);
//									//�����豸���
									calResourceBatch(rowNum);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			type : 'int',
			validation : {
				required : true,
				custom : ['onlyNumber']
			},
			event : {
				blur : function() {
					//�����豸���
					calResourceBatch($(this).data("rowNum"));
				}
			}
		}, {
			display : '��λ',
			name : 'unit',
			tclass : 'txtshort',
			validation : {
				required : true
			},
			type : 'hidden'
		}, {
			display : '��������',
			name : 'planBeginDate',
			tclass : 'txtshort',
			type : 'date',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planBeginDate = $(this).val();
					var planEndDate = g.getCmpByRowAndCol(rowNum,'planEndDate').val();
					if(planBeginDate != "" && planEndDate != ""){
						var days = DateDiff(planBeginDate,planEndDate) + 1 ;
						g.getCmpByRowAndCol(rowNum,'useDays').val(days);
						calResourceBatch(rowNum);
					}
				}
			},
			value : $("#planBeginDate").val()
		}, {
			display : '�黹����',
			name : 'planEndDate',
			tclass : 'txtshort',
			type : 'date',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planBeginDate = g.getCmpByRowAndCol(rowNum,'planBeginDate').val();
					var planEndDate = $(this).val();
					if(planBeginDate != "" && planEndDate != ""){
						var days = DateDiff(planBeginDate,planEndDate) + 1 ;
						g.getCmpByRowAndCol(rowNum,'useDays').val(days);
						calResourceBatch(rowNum);
					}
				}
			},
			value : $("#planEndDate").val()
		}, {
			display : 'ʹ������',
			name : 'useDays',
			type : 'int',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			value : $("#days").val()
		}, {
			display : '�豸�ۼ�',
			name : 'price',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '�豸�ɱ�',
			name : 'amount',
			tclass : 'txtshort',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
//			display : '��������',
//			name : 'workContent',
//			tclass : 'txt'
//		}, {
			display : '��ע˵��',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : '����id',
			name : 'activityId',
			value : $("#activityId").val(),
			type : 'hidden'
		}, {
			display : '��������',
			name : 'activityName',
			value : $("#activityName").val(),
			type : 'hidden'
		}, {
			display : '��Ŀid',
			name : 'projectId',
			value : $("#projectId").val(),
			type : 'hidden'
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			value : $("#projectName").val(),
			type : 'hidden'
		}, {
			display : '��Ŀ���',
			name : 'projectCode',
			value : $("#projectCode").val(),
			type : 'hidden'
		}]
	})

	/**
	 * ��֤��Ϣ(�õ��ӱ���֤ǰ��������ʹ��validate)
	 */
	validate({

	});
});

$(document).ready(function() {
	$("#importTable").yxeditgrid({
		objName : 'esmresources',
		colModel : [{
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '设备编码',
			name : 'resourceCode',
			type : 'hidden'
		}, {
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
			tclass : 'readOnlyTxt'
		}, {
			display : '设备名称',
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
//									//计算设备金额
									calResourceBatch(rowNum);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			type : 'int',
			validation : {
				required : true,
				custom : ['onlyNumber']
			},
			event : {
				blur : function() {
					//计算设备金额
					calResourceBatch($(this).data("rowNum"));
				}
			}
		}, {
			display : '单位',
			name : 'unit',
			tclass : 'txtshort',
			validation : {
				required : true
			},
			type : 'hidden'
		}, {
			display : '领用日期',
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
			display : '归还日期',
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
			display : '使用天数',
			name : 'useDays',
			type : 'int',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			value : $("#days").val()
		}, {
			display : '设备折价',
			name : 'price',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '设备成本',
			name : 'amount',
			tclass : 'txtshort',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
//			display : '工作内容',
//			name : 'workContent',
//			tclass : 'txt'
//		}, {
			display : '备注说明',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : '任务id',
			name : 'activityId',
			value : $("#activityId").val(),
			type : 'hidden'
		}, {
			display : '任务名称',
			name : 'activityName',
			value : $("#activityName").val(),
			type : 'hidden'
		}, {
			display : '项目id',
			name : 'projectId',
			value : $("#projectId").val(),
			type : 'hidden'
		}, {
			display : '项目名称',
			name : 'projectName',
			value : $("#projectName").val(),
			type : 'hidden'
		}, {
			display : '项目编号',
			name : 'projectCode',
			value : $("#projectCode").val(),
			type : 'hidden'
		}]
	})

	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({

	});
});

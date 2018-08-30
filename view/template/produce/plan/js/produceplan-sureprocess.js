$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
	}

	var processObj = $("#processInfo");
	processObj.yxeditgrid({
		objName : 'produceplan[process]',
		realDel : true,
		colModel : [{
			display : '工  序',
			name : 'process',
			width : '15%',
			validation : {
				required : true
			}
		},{
			display : '项目名称',
			name : 'processName',
			width : '30%',
			validation : {
				required : true
			},
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_processequ({
					hiddenId : 'processInfo_cmp_processName' + rowNum,
					gridOptions : {
						event : {
							row_dblclick : function(e ,row ,data) {
								processObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"process").val(data.process);
								processObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"processTime").val(data.processTime);
								processObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"recipient").val(data.recipient);
								processObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"recipientId").val(data.recipientId);
								processObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"remark").val(data.remark);
							}
						}
					}
				});
			}
		},{
			display : '工序时间（秒）',
			name : 'processTime',
			width : '10%',
			validation : {
				required : true,
				custom : ['percentageNum']
			}
		},{
			display : '接收数量',
			name : 'recipientNum',
			width : '10%',
			validation : {
				required : true,
				custom : ['onlyNumber']
			}
		},{
			display : '接收人',
			name : 'recipient',
			width : 180,
			readonly : true,
			validation : {
				required : true
			},
			process : function($input) {
				var rowNum = $input.data("rowNum");
				$input.yxselect_user({
					mode : 'check',
					hiddenId: 'processInfo_cmp_recipientId' + rowNum
				});
			}
		},{
			display : '接收人ID',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '备注',
			name : 'remark',
			type : 'textarea',
			width : '20%'
		}]
	});

	//工序模板下拉
	$("#proModelName").yxcombogrid_process({
		hiddenId : 'proModelId',
		isFocusoutCheck : true,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e ,row ,data) {
					$("#proModelName").val(data.templateName);
					var processData = $.ajax({
											type : 'POST',
											url : '?model=manufacture_basic_processequ&action=listJson',
											data : {
												parentId : data.id,
												dir : 'ASC'
											},
											async : false
										}).responseText;
					processData = eval("(" + processData + ")");
					processObj.yxeditgrid("removeAll" ,'true').yxeditgrid("addRows" ,processData); //删除内容重新添加
				}
			}
		}
	});

	validate({
		"proSureName" : {
			required : true
		}
	});
});
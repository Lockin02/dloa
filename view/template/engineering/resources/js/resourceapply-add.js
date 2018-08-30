$(document).ready(function() {
	//人员渲染
//	$("#applyUser").yxselect_user({
//		hiddenId : 'applyUserId',
//		isGetDept : [true, "deptId", "deptName"],
//		formCode : 'resourceapply'
//	});
	
	//邮件接收人渲染
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'resourceapply'
	});

	//获取省份数组并赋值给provinceArr
	var provinceArr = getProvince();
	addDataToProvince(provinceArr,'placeId');

	//工程项目渲染
	$("#projectCode").yxcombogrid_esmproject({
		hiddenId : 'projectId',
		nameCol : 'projectCode',
		isShowButton : false,
		height : 250,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {'statusArr' : 'GCXMZT02,GCXMZT01'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectName").val(data.projectName);
					$("#place").val(data.province);
					$("#placeId").val(data.provinceId);
					$("#managerName").val(data.managerName);
					$("#managerId").val(data.managerId);
				}
			}
		},
		event : {
			'clear' : function() {
				$("#projectName").val('');
				$("#place").val('');
				$("#placeId").val('');
				$("#managerName").val('');
				$("#managerId").val('');
			}
		}
	});

	//绑定applyType的事件
	$("#applyType").bind('change',showProjectNeed);

	//申请类型设置
	function showProjectNeed(){
		if($("#applyType").val() == "GCSBSQ-01"){//项目申请
			$("#projectCodeShow").addClass("blue");
			$("#projectCode").addClass("validate[required]");
			$("#placeShow").addClass("blue");		//省份操作
			$("#placeId").addClass("validate[required]");
		}else{//个人申请
			$("#projectCodeShow").removeClass("blue");
			$("#projectCode").removeClass("validate[required]");
			$("#placeShow").removeClass("blue");
			$("#placeId").removeClass("validate[required]");
		}
	}

	//初始化
	showProjectNeed();

	//从表初始化
	$("#importTable").yxeditgrid({
		objName : 'resourceapply[resourceapplydet]',
		tableClass : 'form_in_table',
		colModel : [{
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
			tclass : 'readOnlyTxtMiddle',
			width : 80
		}, {
			display : '设备名称',
			name : 'resourceName',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_esmdevice({
					hiddenId : g.el.attr('id')+ '_cmp_resourceId' + rowNum,
					width : 600,
					isFocusoutCheck : false,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'resourceTypeName').val(rowData.deviceType);
									g.getCmpByRowAndCol(rowNum,'resourceTypeId').val(rowData.typeid);

									g.getCmpByRowAndCol(rowNum,'unit').val(rowData.unit);
									g.setRowColValue(rowNum, 'price', rowData.budgetPrice, true);
									//计算设备金额
									calResourceBatch(rowNum);
									calAmount();
								}
							})(rowNum)
						}
					}
				}).attr("readonly",false);
			}
		}, {
			display : '单位',
			name : 'unit',
			tclass : 'txtshort',
			validation : {
				required : true
			},
			width : 50
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			validation : {
				required : true,
				custom : ['onlyNumber']
			},
			event : {
				blur : function() {
					//计算设备金额
					calResourceBatch($(this).data("rowNum"));
					calAmount();
				}
			},
			width : 50
		}, {
			display : '领用日期',
			name : 'planBeginDate',
			tclass : 'txtshort Wdate',
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
						calAmount();
					}
				}
			},
			validation : {
				required : true
			},
			width : 90
		}, {
			display : '归还日期',
			name : 'planEndDate',
			tclass : 'txtshort Wdate',
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
						calAmount();
					}
				}
			},
			validation : {
				required : true
			},
			width : 90
		}, {
			display : '使用天数',
			name : 'useDays',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 60
		}, {
			display : '设备折价',
			name : 'price',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 80
		}, {
			display : '预计成本',
			name : 'amount',
			tclass : 'txtshort',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 80
		}, {
			display : '备注说明',
			name : 'remark',
			tclass : 'txtmiddle'
		}]
	});

	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({
		"applyUser" : {
			required : true
		},
		"applyDate" : {
			required : true
		},
		"applyType" : {
			required : true
		},
		"getType" : {
			required : true
		},
		"reason" : {
			required : true
		},
		"proCode" :{
			required : true
		},
		"mobile" :{
			required : true
		}
	});
	var divDocument = document.getElementById("importTable");
	var tbody = divDocument.getElementsByTagName("tbody");
	var $tbody = $(tbody)
	$tbody.after('<tr class="tr_count"><td colspan="3">合计</td>'+
			'<td></td><td></td><td></td><td></td><td></td><td></td><td></td>'
			+'<td><input type="text" id="view_amount" name="resourceapply[amount]" class="readOnlyTxtShortCount formatMoney" readonly="readonly"/></td>'
			+'<td></td></tr>');
	calAmount();
	//领用方式为快递时，显示邮寄地址框，并要求必填
	$("#getType").change(function(){
		$("#address").parents("tr:first").toggle();
		if($("#address").parents("tr:first").is(":visible")){
			$("#address").addClass("validate[required]");
		}else{
			$("#address").removeClass("validate[required]");
			$("#address").val("");
		}
	})
});
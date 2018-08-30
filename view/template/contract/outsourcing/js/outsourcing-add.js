/**
 * 获取省份数组
 */
function getProvince() {
	var responseText = $.ajax({
		url : 'index1.php?model=system_procity_province&action=getProvinceNameArr',
		type : "POST",
		async : false
	}).responseText;
	var o = eval("(" + responseText + ")");
	return o;
}

/**
 * 添加省份数组添加到下拉框
 */
function addDataToProvince(data, selectId) {
	for (var i = 0, l = data.length; i < l; i++) {
		$("#" + selectId).append("<option title='" + data[i].text
				+ "' value='" + data[i].value + "'>" + data[i].text
				+ "</option>");
	}
}
/**
* 当省份改变时对，对esmproject[proCode]的title的值赋值给esmproject[proName]
*/
function setProName(){
	$('#proName').val($("#proCode").find("option:selected").attr("title"));
}

//新增时提交审批
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=contract_outsourcing_outsourcing&action=add&act=audit";
	}else{
		document.getElementById('form1').action="?model=contract_outsourcing_outsourcing&action=add";
	}
}

var pageAttr = 'add';//配置页面操作，用于渲染整包/人员租赁信息

$(document).ready(function(){
	//获取省份数组并赋值给provinceArr
	provinceArr = getProvince();

	//把省份数组provinceArr赋值给proCode
	addDataToProvince(provinceArr,'proCode');
	
	if($('#proName').val()!=''){
		$("#proCode option").each(function() {
			if( $(this).text() == $("#proName").val() ){
				$(this).attr("selected","selected'");
			}
		}); 
	}

	//初始化省份名称
	setProName();

	//单选负责人
	$("#principalName").yxselect_user({
		hiddenId : 'principalId',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'outsourcing'
	});

	//单选部门
//	$("#deptName").yxselect_dept({
//		hiddenId : 'deptId'
//	});

	if($("#signCompanyName").val() == ''){
//		//签约单位
//		$("#signCompanyName").yxcombogrid_outsupplier({
//			hiddenId : 'signCompanyId',
//			isFocusoutCheck : false,
//			
//			gridOptions : {
//				param : {
//					suppGradeStr :'1,2,3'
//				},
//				event : {
//					'row_dblclick' : function(e, row, data) {
//						$("#proName").val(data.province);
//						$("#address").val(data.address);
//						$.ajax({
//							type : "POST",
//							url : "?model=outsourcing_supplier_basicinfo&action=getInfo",
//							data : {
//								id : data.id ,
//								provinceId : data.provinceId
//							},
//							success : function (datas){
//								var dataArr = eval("(" + datas + ")");
//								$("#proCode").val(dataArr[0]['provinceCode']);
//								$("#phone").val(dataArr[0]['phone']);
//								$("#linkman").val(dataArr[0]['linkman']);
//								$("#bank").val(dataArr[0]['bank']);
//								$("#account").val(dataArr[0]['account']);
//							}
//						});
//						
//					}
//				}
//			}
//		});
		
		$("#signCompanyName").yxcombogrid_signcompany({
			hiddenId : 'signCompanyId',
			isFocusoutCheck : false,
			gridOptions : {
				event : {
					'row_dblclick' : function(e, row, data) {
						$("#proCode").val(data.proCode);
						$("#proName").val(data.proName);
						$("#linkman").val(data.linkman);
						$("#phone").val(data.phone);
						$("#address").val(data.address);
					}
				}
			}
		});
	}
	
	
	
	//归属公司
	$("#businessBelongName").yxcombogrid_branch({
		hiddenId : 'businessBelong',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					//初始化树结构
					initTree();
					//重置责任范围
					reloadManager();
				}
			}
		}
	});

	// 验证信息
	validate({
		"orderName" : {
			required : true,
			length : [0,100]
		},
		"signCompanyName" : {
			required : true,
			length : [0,100]
		},
		"linkman" : {
			required : true,
			length : [0,100]
		},
		"phone" : {
			required : true
		},
		"adderss" : {
			required : false,
			length : [0,300]
		},
		"orderMoney_v" : {
			required : true
		},
		"principalName" : {
			required : true,
			length : [0,20]
		},
		"deptName" : {
			required : true
		},
		"payCondition" : {
			required : true,
			length : [0,300]
		},
		"signDate" : {
			required : true,
			custom : ['date']
		},
		"beginDate" : {
			required : true,
			custom : ['date']
		},
		"endDate" : {
			required : true,
			custom : ['date']
		},
		"businessBelongName" : {
			required : true
		}
	});

	//判断系统合同号生成规则
	if($("#isSysCode").val() == 0){
		$("#orderCodeNeed").html("[*]");
		$("#orderCode").attr("class","txt").attr("readonly",false);
		// 验证信息
		validate({
			"orderCode" : {
				required : true,
				length : [0,100]
			}
		});

		/**
		 * 合同号唯一性验证
		 */
		var url = "?model=contract_outsourcing_outsourcing&action=checkRepeat";
		$("#orderCode").ajaxCheck({
			url : url,
			alertText : "* 该合同号已存在",
			alertTextOk : "* 该合同号可用"
		});
	}

	//变更外包类型
	//outsourType();
});

//人员租赁
function itemDetail() {
	var obj = $("#itemTable");
	if(obj.children().length == 0){
		obj.yxeditgrid({
			objName : 'outsourcing[items]',
			tableClass : 'form_in_table',
			colModel : [{
				name : 'personLevel',
				display : '人员级别',
				type : "hidden"
			}, {
				name : 'personLevelName',
				display : '人员级别名称',
				width : 80,
				readonly : true,
				process : function($input, rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_eperson({
						hiddenId : 'importTable_cmp_budgetId' + rowNum,
						width : 600,
						height : 300,
						gridOptions : {
							showcheckbox : false,
							event : {
								row_dblclick : (function(rowNum) {
									return function(e, row, rowData) {
										g.getCmpByRowAndCol(rowNum,'personLevel').val(rowData.id);
									}
								})(rowNum)
							}
						}
					});
				}
			}, {
				name : 'pesonName',
				display : '姓名',
				width : 80
			}, {
				name : 'supplierName',
				display : '归属外包公司',
				width : 80
			}, {
				name : 'beginDate',
				display : '租赁开始日期',
				width : 80,
				type : 'date'
			}, {
				name : 'endDate',
				display : '租赁结束日期',
				width : 80,
				type : 'date'
			}, {
				name : 'selfPrice',
				display : '服务线人力成本',
				width : 80,
				type : 'money'
			}, {
				name : 'rentalPrice',
				display : '外包价格',
				width : 80,
				type : 'money'
			}, {
				name : 'skillsRequired',
				display : '工作技能要求',
				width : 80
			}, {
				name : 'interviewResults',
				display : '技术面试结果',
				width : 80
			}, {
				name : 'interviewName',
				display : '面试人员',
				width : 80
			}, {
				name : 'interviewId',
				display : '面试人员id',
				width : 80,
				type : "hidden"
			}, {
				name : 'remark',
				display : '备注',
				width : 80
			}]
		});
		tableHead();
	}
}
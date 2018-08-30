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

//编辑时提交审批
function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=contract_outsourcing_outsourcing&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=contract_outsourcing_outsourcing&action=edit";
	}
}

var pageAttr = 'edit';//配置页面操作，用于渲染整包/人员租赁信息
$(document).ready(function(){
	//付款信息渲染
	var isNeedPayapplyObj = $("#isNeedPayapply");
	if(isNeedPayapplyObj.val() == 1){
		isNeedPayapplyObj.trigger('click');
		showPayapplyInfo(isNeedPayapplyObj[0]);
	}

	//获取省份数组并赋值给provinceArr
	provinceArr = getProvince();

	//把省份数组provinceArr赋值给proCode
	addDataToProvince(provinceArr,'proCode');

	//单选负责人
	$("#principalName").yxselect_user({
		hiddenId : 'principalId',
		isOnlyCurDept : false,
		isGetDept : [true, "deptId", "deptName"]
	});

	//单选部门
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	//签约单位
//	$("#signCompanyName").yxcombogrid_outsupplier({
//		hiddenId : 'signCompanyId',
//		isFocusoutCheck : false,
//		
//		gridOptions : {
//			param : {
//				suppGradeStr :'1,2,3'
//			},
//			event : {
//				'row_dblclick' : function(e, row, data) {
//					$("#proName").val(data.province);
//					$("#address").val(data.address);
//					$.ajax({
//						type : "POST",
//						url : "?model=outsourcing_supplier_basicinfo&action=getInfo",
//						data : {
//							id : data.id ,
//							provinceId : data.provinceId
//						},
//						success : function (datas){
//							var dataArr = eval("(" + datas + ")");
//							$("#proCode").val(dataArr[0]['provinceCode']);
//							$("#phone").val(dataArr[0]['phone']);
//							$("#linkman").val(dataArr[0]['linkman']);
//							$("#bank").val(dataArr[0]['bank']);
//							$("#account").val(dataArr[0]['account']);
//						}
//					});
//					
//				}
//			}
//		}
//	});
	
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
});

function itemDetail(){
	var obj = $("#itemTable");
	if(obj.children().length == 0){
		obj.yxeditgrid({
			objName : 'outsourcing[items]',
			url : '?model=contract_personrental_personrental&action=listJson',
			isAddAndDel : true,
			param : {
				mainId : $("#id").val()
			},
			colModel : [{
				name : 'id',
				tclass : 'txt',
				display : 'id',
				sortable : true,
				type : "hidden"
			},{
				name : 'personLevel',
				display : '人员级别',
				type : "hidden"
			}, {
				name : 'personLevelName',
				display : '人员级别名称',
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
				},
				width : 100
			}, {
				name : 'pesonName',
				display : '姓名',
				width : 100
			}, {
				name : 'beginDate',
				display : '租赁开始日期',
				type : 'date',
				width : 100
			}, {
				name : 'endDate',
				display : '租赁结束日期',
				type : 'date',
				width : 100
			}, {
				name : 'selfPrice',
				display : '服务线人力成本',
				type : 'money',
				width : 100
			}, {
				name : 'rentalPrice',
				display : '外包价格',
				type : 'money',
				width : 100
			}, {
				name : 'skillsRequired',
				display : '工作技能要求',
				width : 100
			}, {
				name : 'interviewResults',
				display : '技术面试结果',
				width : 100
			}, {
				name : 'interviewName',
				display : '面试人员',
				width : 100
			}, {
				name : 'interviewId',
				display : '面试人员id',
				type : "hidden",
				width : 100
			}, {
				name : 'remark',
				display : '备注',
				width : 100
			}]
		});
		tableHead();
	}
}
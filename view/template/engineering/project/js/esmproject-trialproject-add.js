$(document).ready(function(){
	//初始化省份城市信息
	initCity();

	//相关负责人初始化
	//大区经理
	$("#areaManager").yxselect_user({
		hiddenId : 'areaManagerId',
		formCode : 'esmAreaManager'
	});
	//技术经理
	$("#techManager").yxselect_user({
		hiddenId : 'techManagerId',
		formCode : 'esmTechManager'
	});
	//销售负责人
	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		formCode : 'esmSalesman'
	});
	//研发责任人
	$("#rdUser").yxselect_user({
		hiddenId : 'rdUserId',
		formCode : 'esmRdUser'
	});
	
//	//费用归属部门
//	$("#deptName").yxselect_dept({
//		hiddenId : 'deptId'
//	});

	//工具类型渲染
	$("#toolType").yxcombogrid_datadict({
		height : 250,
		valueCol : 'dataName',
		hiddenId : 'toolTypeHidden',
		gridOptions : {
			isTitle : true,
			param : {"parentCode":"GCGJLX"},
			showcheckbox : true,
			event : {
				'row_dblclick' : function(e, row, data){

				}
			},
			// 快速搜索
			searchitems : [{
				display : '名称',
				name : 'dataName'
			}]
		}
	});

	//单选办事处
	$("#officeName").yxcombogrid_office({
		hiddenId : 'officeId',
		height : 300,
		gridOptions : {
			showcheckbox : false,
			isTitle : true,
			event : {
				'row_dblclick' : function(e, row, data){
					$("#deptId").val(data.feeDeptId);
					$("#deptName").val(data.feeDeptName);
					$("#productLine").val(data.productLine);
				}
			}
		}
	});

	//单选项目经理
	$("#managerName").yxselect_user({
		hiddenId : 'managerId',
		formCode : 'esmprojectAdd'
	});
	
	//所属部门
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	/**
	 * 验证信息
	 */
	validate({
		"officeName" : {
			required : true
		},
		"projectName" : {
			required : true
		},
		"managerName" : {
			required : true
		},
		"planBeginDate" : {
			required : true
		},
		"planEndDate" : {
			required : true
		},
		"expectedDuration" : {
			required : true
		},
		"deptName" : {
			required : true
		},
		"category" : {
			required : true
		}
	});

	/**
	 * 编号唯一性验证
	 */
	var url = "?model=engineering_project_esmproject&action=checkRe1" +
        "3peat";
	$("#projectCode").ajaxCheck({
		url : url,
		alertText : "* 该编号已存在",
		alertTextOk : "* 该编号可用"
	});
	
	// 事件绑定
	$("#productLine").bind('change', initProjectCode);
	$("#category").bind('change', initProjectCode);
	$("#workRate").bind('blur', initProjectCode);
});

//项目编号获取
function initProjectCode() {
	var productLineObj = $("#productLine");
	var moduleCode = $("#moduleCode").val();
	var category = $("#category").find("option:selected").attr('e1');
	var workRate = $("#workRate").val();
	if (category && category != '' && workRate != "" && moduleCode) {
		var projectCode = $("#contractCode").val() + moduleCode +
			category;

		$.ajax({
			type: "POST",
			url: "?model=engineering_project_esmproject&action=getProjectNum",
			data: {
				contractId: $("#contractId").val(),
				contractType: $("#contractType").val(),
				productLine: productLineObj.val()
			},
			async: false,
			success: function(data) {
				if (parseInt(workRate) != 100 && parseInt(data) == 0) {
					projectCode += 1;
				} else {
					if (parseInt(data) == 0) {
						projectCode += parseInt(data);
					} else {
						projectCode += parseInt(data) + 1;
					}
				}
			}
		});

		$("#projectCode").val(projectCode);
	}
}
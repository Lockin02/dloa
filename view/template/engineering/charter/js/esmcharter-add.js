/**
 * 初始省份区域
 */
function initOffice() {
	//获取省份对应的办事处
	$.ajax({
		type: "POST",
		url: "?model=engineering_officeinfo_range&action=getOfficeInfoForId",
		data: {
			provinceId: $("#provinceId").val(),
			businessBelong: $("#businessBelong").val(),
			productLine: $("#productLine").val()
		},
		async: false,
		success: function(data) {
			if (data != "") {
				var dataObj = eval("(" + data + ")");
				$("#officeName").val(dataObj.officeName);
				$("#officeId").val(dataObj.officeId);
				$("#deptId").val(dataObj.feeDeptId);
				$("#deptName").val(dataObj.feeDeptName);
				$("#productLine").val(dataObj.productLine);
			}
		}
	});
}

//启动与结束关闭差验证
function timeCheck($t) {
	var startDate = $('#planBeginDate').val();
	var endDate = $('#planEndDate').val();
	if (startDate == "" || endDate == "") {
		return false;
	}
	var s = DateDiff(startDate, endDate) + 1;
	if (s < 0) {
		alert("预计开始不能比预计结束时间晚！");
		$t.value = "";
		return false;
	}
}

// 获取产线可用占比
function initWorkRate() {
	$("#workRate").attr('readonly', true).removeClass('txt').addClass('readOnlyText');
	$.ajax({
		type: "POST",
		url: "?model=engineering_project_esmproject&action=getWorkrateCanUse",
		data: {
			contractId: $("#contractId").val(),
			contractType: $("#contractType").val(),
			productLine: $("#newProLine").val()
		},
		async: false,
		success: function(data) {
			var workRateObj = $("#workRate");
			if (workRateObj.val() * 1 == 0 || data * 1 < workRateObj.val() * 1) {
				workRateObj.val(data);
			}
			$("#remainWorkRate").val(data);
			workRateObj.attr('readonly', false).removeClass('readOnlyText').addClass('txt');
		}
	});

	// PK成本占比也一起更新
	var contractId = $("#contractId").val();
	var paramArr = {
		projectId : $("#id").val(),
		productLine: $("#newProLine").val(),
		contratId : contractId,
		contractType : 'GCXMYD-01',
		chkType : 'pk'
	};
	$.ajax({
		type: "POST",
		url: "?model=engineering_project_esmproject&action=getPkrateCanUse",
		data: paramArr,
		async: false,
		success: function(data) {
			var pkEstimatesRate = (isNaN(Number(data)))? 0 : Number(data);
			$("#pkEstimatesRate").val(pkEstimatesRate);
			$("#pkEstimatesRate").attr("title",pkEstimatesRate);
			$("#pkEstimatesRate").attr("data-orgval",pkEstimatesRate);;
		}
	});
}

// 项目编号获取
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

//表单验证
function checkform() {
	var workRateObj = $("#workRate");
	var remainWorkRateObj = $("#remainWorkRate");
	var pkEstimatesRate = $("#pkEstimatesRate").val();
	var pkEstimatesRateMax = $("#pkEstimatesRate").attr("data-orgval");

	if (workRateObj.val() * 1 == 0) {
		alert('工作占比不能为0');
		return false;
	}

	if (workRateObj.val() * 1 > remainWorkRateObj.val() * 1) {
		alert('工作占比已超出范围');

		workRateObj.val(remainWorkRateObj.val());
		return false;
	}

	if (workRateObj.val() * 1 == remainWorkRateObj.val() * 1 && Number(pkEstimatesRate) != Number(pkEstimatesRateMax)){
		alert('本次分配工作占比已全部被分配完成，PK成本占比必须全部分配。');

		$("#pkEstimatesRate").val(pkEstimatesRateMax);
		$("#pkEstimatesRate").focus();
		return false;
	}

	//项目编号唯一校验
	var unRepeat = true;
	$.ajax({
		type: "POST",
		url: "?model=engineering_project_esmproject&action=checkIsRepeat",
		data: {projectCode: $("#projectCode").val()},
		async: false,
		success: function(data) {
			if (data == "1") {
				alert('项目编号已存在');
				unRepeat = false;
			}
		}
	});

	return unRepeat;
}

$(document).ready(function() {
	// 设置区域
	initOffice();

	// 单选办事处
	$("#officeName").yxcombogrid_office({
		hiddenId: 'officeId',
		height: 250,
		gridOptions: {
			showcheckbox: false,
			param: {productLineArr: $("#productLineUse").val()},
			isTitle: true,
			event: {
				row_dblclick: function(e, row, data) {
					$("#deptId").val(data.feeDeptId);
					$("#deptName").val(data.feeDeptName);
					$("#productLine").val(data.productLine);
					initWorkRate();
				}
			}
		}
	});

	// 事件绑定
	$("#productLine").bind('change', initProjectCode);
	$("#category").bind('change', initProjectCode).bind('change', function() {
        // c类开票进度，AB或者其他按进度
        if ($(this).val() == 'XMLBC') {
            $("#incomeType").val('SRQRFS-02');
        } else {
            $("#incomeType").val('SRQRFS-01');
        }
    });
	$("#workRate").bind('blur', initProjectCode);

	// 初始化产线可用占比
	initWorkRate();

	// 单选项目经理
	$("#managerName").yxselect_user({
		hiddenId: 'managerId',
		mode: 'check',
		formCode: 'esmcharter'
	});

	// 所属部门
	$("#deptName").yxselect_dept({
		hiddenId: 'deptId'
	});

	// 初始化城市信息
	initCity();

	$("#country").change(function() {
		if ($(this).find("option:selected").text() != '中国') {
			//	alert('海外');
			var cityUrl = "?model=system_procity_city&action=pageJson"; // 获取市的URL
			$("#province").val('32');
			var provinceId = 32;
			$.ajax({
				type: 'POST',
				url: cityUrl,
				data: {
					provinceId: provinceId,
					pageSize: 999
				},
				async: false,
				success: function(data) {
					$('#city').children().remove("option[value!='']");
					getCitys(data);
					$('#city').find("option[text='海外']").attr("selected", true);
				}
			});
		}
	});

	// 验证信息
	validate({
		projectCode: {//项目编号
			required: true,
			length: [16, 16]
		},
		projectName: {//项目名称
			required: true,
			length: [0, 100]
		},
		officeName: {//办事处
			required: true,
			length: [0, 20]
		},
		managerName: {//项目经理
			required: true,
			length: [0, 20]
		},
		planBeginDate: {//预计启动日期
			custom: ['date']
		},
		planEndDate: {//预计结束日期
			custom: ['date']
		},
		workRate: {//工作占比
			required: true,
			custom: ['percentage']
		},
        incomeType: {
            required: true
        },
		country: {
			required: true
		},
		province: {
			required: true
		},
		city: {
			required: true
		},
		deptName: {
			required: true
		},
		category: {
			required: true
		},
        description: {
            description: true
        }
	});

	// 关闭规则
	$("#closeRules").yxeditgrid({
		objName: 'esmcharter[closedetail]',
		url: '?model=engineering_baseinfo_esmcloserule&action=listRuleJson',
		title: '关闭规则',
		tableClass: 'form_in_table',
		isAddOneRow: false,
		isAddAndDel: false,
		colModel: [{
			name : 'ruleId',
			display : "选择",
			width : 50,
			process : function(v,row){
				if(row.isNeed == "1"){
					return "<input type='checkbox' name='esmcharter[closedetail][][ruleId]' value='" + row.ruleId +"' checked='checked'" +
						"style='display:none;'/><span class='red'>必选</span>";
				}else{
					return "<input type='checkbox' name='esmcharter[closedetail][][ruleId]' value='" + row.ruleId +"'/>";
				}
			},
			type : 'statictext'
		}, {
			display: '名目',
			name: 'ruleName',
			tclass: 'readOnlyTxtNormal',
			readonly: true
		}, {
			display: '描述',
			name: 'content',
			tclass: 'readOnlyTxtLong',
			readonly: true
		}]
	});
});
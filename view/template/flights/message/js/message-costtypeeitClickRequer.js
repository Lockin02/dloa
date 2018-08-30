//初始化一些字段
var objName = 'message';
var initId = 'feeTbl_c';
var myUrl = '?model=flights_require_require&action=ajaxGet';

//费用归属部门数组
var expenseSaleDept;
var expenseContractDept;
var expenseTrialProjectFeeDept;

//初始化费用类型
function initCostType(id){
	//ajax获取费用单据
	var responseText = $.ajax({
		url:myUrl,
		data : {"id" : id},
		type : "POST",
		async : false
	}).responseText;
	var objInfo = eval("(" + responseText + ")");

	var tableStr = "";
	tableStr = '<table class="form_in_table" id="'+objName+'tbl">' +
			'<tr id="feeTypeTr">' +
				'<td class = "form_text_left"><span id="detailTypeTitle" class="red">请选择费用类型</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="radio" name="'+objName+'[detailType]" value="1" onclick="changeDetailType(this.value)" /> 部门费用 ' +
					'<input type="radio" name="'+objName+'[detailType]" value="2" onclick="changeDetailType(this.value)" /> 合同项目费用 ' +
					'<input type="radio" name="'+objName+'[detailType]" value="3" onclick="changeDetailType(this.value)" /> 研发费用 ' +
					'<input type="radio" name="'+objName+'[detailType]" value="4" onclick="changeDetailType(this.value)" /> 售前费用 ' +
					'<input type="radio" name="'+objName+'[detailType]" value="5" onclick="changeDetailType(this.value)" /> 售后费用 ' +
				'</td>' +
			'</tr>' +
		'</table>';
	$("#"+initId).html(tableStr);

	//初始化
	changeDetailType(objInfo.detailType,objInfo);
}

//选择费用类型
function changeDetailType(detailType,objInfo){
	if(detailType){
		//附选中值
		$("input[name='"+objName+"[detailType]']").each(function(i,n){
			if(this.value == detailType){
				$(this).attr("checked",this);
				return false;
			}
		});
		$("#detailTypeTitle").html('费用类型').removeClass('red').addClass('blue');
		$("#projectName").yxcombogrid_esmproject('remove');
		$("#projectName").yxcombogrid_projectall('remove');
		$("#projectName").yxcombogrid_rdprojectfordl('remove');
		$("#costBelongCom").yxcombogrid_branch('remove');
		$(".feeTypeContent").remove();
		switch(detailType){
			case '1' : initDept(objInfo);break;
			case '2' : initContractProject(objInfo);break;
			case '3' : initRdProject(objInfo);break;
			case '4' : initSale(objInfo);break;
			case '5' : initContract(objInfo);break;
			default : break;
		}
	}
}

//初始化部门
function initDept(objInfo){
	//初始值赋予
	var costBelongCom='',costBelongComId='',costBelongDeptName='',costBelongDeptId='';
	if(objInfo){
		costBelongCom = objInfo.costBelongCom;
		costBelongComId = objInfo.costBelongComId;
		costBelongDeptName = objInfo.costBelongDeptName;
		costBelongDeptId = objInfo.costBelongDeptId;
	}
	var tableStr = '<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">费用归属公司</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+costBelongCom +'" readonly="readonly"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+costBelongComId +'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">费用归属部门</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" value="'+costBelongDeptName +'" readonly="readonly"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
			'</td>' +
		'</tr>';
	$("#"+objName + "tbl").append(tableStr);
	//公司渲染
	$("#costBelongCom").yxcombogrid_branch({
		hiddenId : 'costBelongComId',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false
		}
	});
	//费用归属部门选择
	$("#costBelongDeptName").yxselect_dept({
		hiddenId : 'costBelongDeptId'
	});
}

//初始化合同项目
function initContractProject(objInfo){
	//初始值赋予
	var projectName='',projectCode='',projectId='',costBelongDeptName='',costBelongDeptId='',proManagerName='',proManagerId='';
	if(objInfo){
		projectName = objInfo.projectName;
		projectCode = objInfo.projectCode;
		projectId = objInfo.projectId;
		costBelongDeptName = objInfo.costBelongDeptName;
		costBelongDeptId = objInfo.costBelongDeptId;
		proManagerName = objInfo.proManagerName;
		proManagerId = objInfo.proManagerId;
	}
	var tableStr = '<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">项目名称</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly" value="'+projectName +'"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" value="'+projectName +'"/>' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" value="'+projectName +'"/>' +
				'<input type="hidden" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" value="'+costBelongDeptName +'"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">项目经理</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" value="'+proManagerId +'"/>' +
			'</td>' +
		'</tr>';
	$("#"+objName + "tbl").append(tableStr);

	//合同项目渲染
	$("#projectName").yxcombogrid_projectall({
		isDown : true,
		hiddenId : 'projectId',
		nameCol : 'projectName',
		searchName : 'projectNameSearch',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {'contractType' : 'GCXMYD-01'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectId").val(data.projectId);
					$("#projectCode").val(data.projectCode);
					$("#proManagerName").val(data.managerName);
					$("#proManagerId").val(data.managerId);

					//重置费用归属部门
					$("#costBelongDeptId").val(data.deptId);
					$("#costBelongDeptName").val(data.deptName);
				}
			}
		},
		event : {
			'clear' : function() {
				$("#projectCode").val('');
				$("#proManagerName").val('');
				$("#proManagerId").val('');
				$("#projectType").val('');

				//重置费用归属部门
				$("#costBelongDeptId").val('');
				$("#costBelongDeptName").val('');
			}
		}
	});
}

//初始化研发项目
function initRdProject(objInfo){
	//初始值赋予
	var projectName='',projectCode='',projectId='',costBelongDeptName='',costBelongDeptId='',proManagerName='',proManagerId='';
	if(objInfo){
		projectName = objInfo.projectName;
		projectCode = objInfo.projectCode;
		projectId = objInfo.projectId;
		costBelongDeptName = objInfo.costBelongDeptName;
		costBelongDeptId = objInfo.costBelongDeptId;
		proManagerName = objInfo.proManagerName;
		proManagerId = objInfo.proManagerId;
	}
	var tableStr = '<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">项目名称</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly" value="'+projectName +'"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" value="'+projectName +'"/>' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" value="'+projectName +'"/>' +
				'<input type="hidden" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" value="'+costBelongDeptName +'"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">项目经理</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" value="'+proManagerId +'"/>' +
			'</td>' +
		'</tr>';
	$("#"+objName + "tbl").append(tableStr);

	//研发项目渲染
	$("#projectName").yxcombogrid_rdprojectfordl({
		isDown : true,
		hiddenId : 'projectId',
		nameCol : 'projectName',
		searchName : 'searhDProjectName',
		isShowButton : false,
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : { 'is_delete' : 0 , 'project_typeNo' : '4'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectCode").val(data.projectCode);
					$("#proManagerName").val(data.managerName);
					$("#proManagerId").val(data.managerId);
					$("#projectType").val('rd');

					//重置费用归属部门
					$("#costBelongDeptId").val(data.deptId);
					$("#costBelongDeptName").val(data.deptName);
				}
			}
		},
		event : {
			'clear' : function() {
				$("#projectCode").val('');
				$("#proManagerName").val('');
				$("#proManagerId").val('');
				$("#projectType").val('');

				//重置费用归属部门
				$("#costBelongDeptId").val('');
				$("#costBelongDeptName").val('');
			}
		}
	});
}

//初始化售前
function initSale(objInfo){
	//初始值赋予
	var projectName='',projectCode='',projectId='',costBelongDeptName='',costBelongDeptId='';
	var proManagerName='',proManagerId='',chanceCode='',chanceName='';
	var chanceId='',customerName='',customerId='',province='',city='',customerType='',costBelonger='',costBelongerId='';
	if(objInfo){
		projectName = objInfo.projectName;
		projectCode = objInfo.projectCode;
		projectId = objInfo.projectId;
		costBelongDeptName = objInfo.costBelongDeptName;
		costBelongDeptId = objInfo.costBelongDeptId;
		proManagerName = objInfo.proManagerName;
		proManagerId = objInfo.proManagerId;
		chanceCode = objInfo.chanceCode;
		chanceName = objInfo.chanceName;
		chanceId = objInfo.chanceId;
		customerName = objInfo.customerName;
		customerId = objInfo.customerId;
		province = objInfo.province;
		city = objInfo.city;
		customerType = objInfo.customerType;
		costBelonger = objInfo.costBelonger;
		costBelongerId = objInfo.costBelongerId;
	}
	var tableStr = '<tr class="feeTypeContent">' +
			'<td class = "form_text_left">试用项目名称</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly" value="'+projectName+'"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" value="'+projectCode+'"/>' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" value="'+projectId+'"/>' +
			'</td>' +
			'<td class = "form_text_left">项目经理</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly" value="'+proManagerName+'"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" value="'+proManagerId+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">商机编号</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="chanceCode" name="'+objName+'[chanceCode]" onblur="getChanceInfo();" value="'+chanceCode+'"/>' +
				'<input type="hidden" id="chanceName" name="'+objName+'[chanceName]" value="'+chanceName+'"/>' +
				'<input type="hidden" id="chanceId" name="'+objName+'[chanceId]" value="'+chanceId+'"/>' +
			'</td>' +
			'<td class = "form_text_left">客户名称</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="customerName" name="'+objName+'[customerName]" value="'+customerName+'"/>' +
				'<input type="hidden" id="customerId" name="'+objName+'[customerId]" value="'+customerId+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">客户省份</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="province" name="'+objName+'[province]" style="width:202px;" value="'+province+'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">客户城市</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="city" name="'+objName+'[city]" style="width:202px;" value="'+city+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">客户类型</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="customerType" name="'+objName+'[customerType]" style="width:202px;" value="'+customerType+'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">销售负责人</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="costBelonger" name="'+objName+'[costBelonger]" style="width:202px;" value="'+costBelonger+'"/>' +
				'<input type="hidden" id="costBelongerId" name="'+objName+'[costBelongerId]" value="'+costBelongerId+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">费用归属部门</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
				'<input type="text" class="txt" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" style="width:202px;" value="'+costBelongDeptName+'"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+costBelongDeptId+'"/>' +
			'</td>'
		'</tr>';
	$("#"+objName + "tbl").append(tableStr);
	//渲染商机编号按钮
	buildInputSet('chanceCode','商机编号','chance');

	//试用项目渲染
	$("#projectName").yxcombogrid_esmproject({
		isDown : true,
		hiddenId : 'projectId',
		nameCol : 'projectName',
		searchName : 'projectName',
		height : 250,
		isFocusoutCheck : true,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {'contractType' : 'GCXMYD-04' , 'statusArr' : 'GCXMZT02,GCXMZT01'},
			event : {
				'row_dblclick' : function(e,row,data) {
					//禁用其他入口
					closeInput('trialPlan',data.id);

					$("#projectCode").val(data.projectCode);
					$("#proManagerName").val(data.managerName);
					$("#proManagerId").val(data.managerId);

					//查询使用项目信息
					var trialProjectInfo = getTrialProject(data.contractId);
					if(trialProjectInfo){
						if(trialProjectInfo.chanceCode != ''){
							$("#chanceCode").val(trialProjectInfo.chanceCode);
							getChanceInfo('trialPlan');
						}else{
							$("#chanceId").val('');
							$("#chanceCode").val('');
							$("#chanceName").val('');
							$("#customerName").val(trialProjectInfo.customerName);
							$("#customerId").val(trialProjectInfo.customerId);
							$("#province").combobox('setValue',trialProjectInfo.province);

							var customerTypeObj = $("#customerType");
							customerTypeObj.combobox('setValue',trialProjectInfo.customerTypeName);

							//销售负责人
							$('#costBelonger').combobox({
							    valueField:'text',
							    textField:'text',
	   							editable : false,
								data : [{"text":trialProjectInfo.applyName,"value": trialProjectInfo.applyNameId}],
								onSelect : function(obj){
									$("#costBelongerId").val(obj.value);
								}
							}).combobox('setValue',trialProjectInfo.applyName);
							$("#costBelongerId").val(trialProjectInfo.applyNameId);

							//重载客户城市
							reloadCity(trialProjectInfo.province);
							var cityObj = $("#city");
							cityObj.combobox('setValue',trialProjectInfo.city);
						}
					}
				}
			}
		},
		event : {
			'clear' : function() {
				$("#projectCode").val('');
				$("#proManagerName").val('');
				$("#proManagerId").val('');
				clearSale();

				//开启其他入口
				openInput('trialPlan');
			}
		}
	}).attr('readonly',false).attr('class','txt');

	//初始化客户
	initCustomer();

	//客户类型
	var CustomerTypeArr = '';
	var str;
	//客户类型渲染
	var customerObj = $('#customerType');
	customerObj.combobox({
		url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
		multiple:true,
		valueField:'text',
        textField:'text',
  		editable : false,
        formatter: function(obj){
        	//判断 如果没有初始化数组中，则选中
        	if(CustomerTypeArr.indexOf(obj.text) == -1){
        		str = "<input type='checkbox' id='customerType_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
        	}else{
        		str = "<input type='checkbox' id='customerType_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
        	}
			return str;
        },
		onSelect : function(obj){
			//checkbox设值
			$("#customerType_" + obj.text).attr('checked',true);
			//设置销售负责人
			changeCustomerType();
		},
		onUnselect : function(obj){
			//checkbox设值
			$("#customerType_" + obj.text).attr('checked',false);
			//设置销售负责人
			changeCustomerType();
		}
	});

	//省份渲染
	var provinceObj = $('#province');
	provinceObj.combobox({
		url:'index1.php?model=system_procity_province&action=listJsonSort',
		valueField:'provinceName',
        textField:'provinceName',
		editable : false,
		onSelect : function(obj){
			//设置对象下的选中项
			$("#provinceHidden").val(obj.provinceName);
			//根据省份读取城市
			reloadCity(obj.provinceName);
		}
	});

	//城市渲染
	var cityObj = $('#city');
	cityObj.combobox({
		textField:'cityName',
		valueField:'cityName',
		multiple:true,
		editable : false,
        formatter: function(obj){
    		var str = "<input type='checkbox' id='city_"+ obj.cityName +"' value='"+ obj.cityName +"'/> " + obj.cityName;
			return str;
        },
		onSelect : function(obj){
			//checkbox设值
			$("#city_" + obj.cityName).attr('checked',true);
			//设置销售负责人
			changeCustomerType();
		},
		onUnselect : function(obj){
			//checkbox设值
			$("#city_" + obj.cityName).attr('checked',false);
			//设置销售负责人
			changeCustomerType();
		}
	});

	//费用归属部门
	if(expenseSaleDept == undefined){
		//ajax获取销售负责人
		var responseText = $.ajax({
			url:'index1.php?model=finance_expense_expense&action=getSaleDept&detailType=4',
			type : "POST",
			async : false
		}).responseText;
		expenseSaleDept = eval("(" + responseText + ")");
	}
	dataArr = expenseSaleDept;
	var costBelongDept = $('#costBelongDeptName');
	costBelongDept.combobox({
		data : dataArr,
	    valueField:'text',
	    textField:'text',
		editable : false,
		onSelect : function(obj){
			$("#costBelongDeptId").val(obj.value);
		}
	});
}

//初始化售后
function initContract(objInfo){
	//初始值赋予
	var costBelongDeptName='',costBelongDeptId='',proManagerName='',proManagerId='',contractCode='',contractName='';
	var contractId='',customerName='',customerId='',province='',city='',customerType='',costBelonger='',costBelongerId='';
	if(objInfo){
		costBelongDeptName = objInfo.costBelongDeptName;
		costBelongDeptId = objInfo.costBelongDeptId;
		proManagerName = objInfo.proManagerName;
		proManagerId = objInfo.proManagerId;
		contractCode = objInfo.contractCode;
		contractName = objInfo.contractName;
		contractId = objInfo.contractId;
		customerName = objInfo.customerName;
		customerId = objInfo.customerId;
		province = objInfo.province;
		city = objInfo.city;
		customerType = objInfo.customerType;
		costBelonger = objInfo.costBelonger;
		costBelongerId = objInfo.costBelongerId;
	}
	var tableStr = '<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">合同编号</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="contractCode" name="'+objName+'[contractCode]" onblur="getContractInfo()" value="'+contractCode+'"/>' +
				'<input type="hidden" id="contractName" name="'+objName+'[contractName]" value="'+contractName+'"/>' +
				'<input type="hidden" id="contractId" name="'+objName+'[contractId]" value="'+contractId+'"/>' +
			'</td>' +
			'<td class = "form_text_left">客户名称</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="customerName" name="'+objName+'[customerName]" readonly="readonly" value="'+customerName+'"/>' +
				'<input type="hidden" id="customerId" name="'+objName+'[customerId]" value="'+customerId+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">客户省份</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="province" name="'+objName+'[province]" readonly="readonly" value="'+province+'"/>' +
			'</td>' +
			'<td class = "form_text_left">客户城市</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="city" name="'+objName+'[city]" readonly="readonly" value="'+city+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">客户类型</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="customerType" name="'+objName+'[customerType]" readonly="readonly" value="'+customerType+'"/>' +
			'</td>' +
			'<td class = "form_text_left">销售负责人</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="costBelonger" name="'+objName+'[costBelonger]" readonly="readonly" value="'+costBelonger+'"/>' +
				'<input type="hidden" id="costBelongerId" name="'+objName+'[costBelongerId]" value="'+costBelongerId+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">费用归属部门</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
				'<input type="text" class="txt" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" style="width:202px;" value="'+costBelongDeptName+'"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+costBelongDeptId+'"/>' +
			'</td>'
		'</tr>';;
	$("#"+objName + "tbl").append(tableStr);

	//费用归属部门
	if(expenseContractDept == undefined){
		//ajax获取销售负责人
		var responseText = $.ajax({
			url:'index1.php?model=finance_expense_expense&action=getSaleDept&detailType=5',
			type : "POST",
			async : false
		}).responseText;
		expenseContractDept = eval("(" + responseText + ")");
	}
	dataArr = expenseContractDept;
	var costBelongDept = $('#costBelongDeptName');
	costBelongDept.combobox({
		data : dataArr,
	    valueField:'text',
	    textField:'text',
		editable : false,
		onSelect : function(obj){
			$("#costBelongDeptId").val(obj.value);
		}
	});

	buildInputSet('contractCode','合同编号','contract');
}

//异步匹配合同信息
function getContractInfo(){
	var contractCode = $("#contractCode").val();
	if(contractCode == ""){
		return false;
	}
	$.ajax({
	    type: "POST",
	    url: "?model=contract_contract_contract&action=ajaxGetContract",
	    data: {"contractCode" : contractCode},
	    async: false,
	    success: function(data){
	   		if(data){
				var dataArr = eval("(" + data + ")");
				if(dataArr.thisLength*1 > 1){
					alert('系统中存在【' + dataArr.thisLength + '】条名称为【' + contractName + '】的合同，请通过合同编号匹配合同信息！');
					clearContract();
				}else{
					$("#contractCode").val(dataArr.contractCode);
					$("#contractId").val(dataArr.id);
					$("#contractName").val(dataArr.contractName);
					$("#customerId").val(dataArr.customerId);
					$("#customerName").val(dataArr.customerName);
					$("#customerType").val(dataArr.customerTypeName);
					$("#province").val(dataArr.contractProvince);
					$("#city").val(dataArr.contractCity);
					$("#costBelonger").val(dataArr.prinvipalName);
					$("#costBelongId").val(dataArr.prinvipalId);
				}
	   	    }else{
				alert('没有查询到相关合同信息');
				clearContract();
	   	    }
		}
	});
}

//清楚合同信息
function clearContract(){
	$("#contractCode").val('');
	$("#contractName").val('');
	$("#contractId").val('');
	$("#customerName").val('');
	$("#customerId").val('');
	$("#customerType").val('');
	$("#province").val('');
	$("#city").val('');
	$("#costBelonger").val('');
	$("#costBelongId").val('');

	//清空省市客户属性
//	clearPCC();
}

//清空客户省份、城市、客户类型系列
function clearPCC(){
	//清空省份信息
	$("#province").combobox('setValue','');
	$("#provinceHidden").val("");

	//清空客户类型信息
	var customerTypeObj = $("#CustomerType");
	mulSelectClear(customerTypeObj);

	var cityObj = $("#city");
	mulSelectClear(cityObj);
}

//多选清空 - 用于清空多选下拉的隐藏项
function mulSelectClear(thisObj){
	thisObj.combobox("setValues","");
	$("#"+ thisObj.attr('id') + "Hidden").val('');
	//清空复选框
	if(thisObj.attr("id") == 'city'){
		$("input:checkbox[id^='" + thisObj.attr("id") +"_']").attr("checked",false);
	}else{
		$("input:checkbox[id^='customerType_']").attr("checked",false);
	}
}

//构建填入渲染
function buildInputSet(thisId,thisName,thisType){
	//渲染一个匹配按钮
	var thisObj = $("#" + thisId);
	if(thisObj.attr('wchangeTag2') == 'true' || thisObj.attr('wchangeTag2') == true){
		return false;
	}
	var title = "输入完整的" + thisName +"，系统自动匹配相关信息";
	var $button = $("<span class='search-trigger' id='" + thisId + "Search' title='"+ title +"'>&nbsp;</span>");
	$button.click(function(){
		if($("#" + thisId).val() == ""){
			alert('请输入一个' + thisName);
			return false;
		}
	});

	//添加清空按钮
	var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
	$button2.click(function(){
		//如果渲染类型是商机，则加载上级的清空内容
		if(thisType == 'chance'){
			if($("#" + thisId).val() != ""){
				//清除销售信息
				clearSale();
				openInput(thisType);
			}
		}else if(thisType == 'contract'){
			clearContract();
		}
	});
	thisObj.after($button2).width(thisObj.width() - $button2.width()).after($button).width(thisObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');
}

// 禁用其他入口
function closeInput(thisType,projectId){
	//项目id获取
	if(projectId == undefined){
		var projectId = $("#projectId").val();//项目id
	}
	//如果没有填入类型，则自行判断
	if(thisType == undefined){
		var chanceId = $("#chanceId").val();//商机id
		var customerId = $("#customerId").val();//客户id
		if(projectId != "" && projectId != 0){
			thisType = 'trialPlan';
		}else if(chanceId != "" && chanceId != 0){
			thisType = 'chance';
		}else if(customerId != "" && customerId != 0){
			thisType = 'customer';
		}
	}
	if(thisType == 'trialPlan'){
		$("#chanceCode").attr("class",'readOnlyTxtNormal').attr('readonly',true);
		$("#chanceName").attr("class",'readOnlyTxtNormal').attr('readonly',true);
		$("#customerName").attr("class",'readOnlyTxtNormal').yxcombogrid_customer('remove').attr('readonly',true);

		//清除商机的渲染
		clearInputSet('chanceCode');
		clearInputSet('chanceName');
	}else if(thisType == 'customer'){
		//项目
		$("#projectCode").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');
		$("#projectName").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');

		//商机
		$("#chanceCode").attr("class",'readOnlyTxtNormal').attr('readonly',true);
		$("#chanceName").attr("class",'readOnlyTxtNormal').attr('readonly',true);

		//清除商机的渲染
		clearInputSet('chanceCode');
		clearInputSet('chanceName');
	}else if(thisType == 'chance'){
		//项目
		$("#projectCode").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');
		$("#projectName").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');
		$("#customerName").attr("class",'readOnlyTxtNormal').yxcombogrid_customer('remove').attr('readonly',true);
	}

	//如果存在入口类型，则渲染对应内容
	if(thisType != undefined){
		//隐藏省份的下拉项
//		$("#province").combobox('disable');
//		$("#city").combobox('disable');
//		$("#CustomerType").combobox('disable');
	}
}

//启用其他入口
function openInput(thisType){
	if(thisType == 'trialPlan'){
		//重新实例化客户选择
		initCustomer();

		//商机的渲染
		buildInputSet('chanceCode','商机编号','chance');
	}else if(thisType == 'customer'){
		//项目
		initTrialproject();

		$("#customerName").attr("class",'txt').attr('readonly',false);

		//商机的渲染
		buildInputSet('chanceCode','商机编号','chance');
	}else if(thisType == 'chance'){
		//项目
		initTrialproject();

		//重新实例化客户选择
		initCustomer();
	}

	//显示省份的下拉项
	$("#province").combobox('enable');
	$('#city').combobox('enable');
	$("#CustomerType").combobox('enable');
	$("#CostBelonger").combobox('enable');
}



//清除填入渲染
function clearInputSet(thisId){
	//渲染一个匹配按钮
	var thisObj = $("#" + thisId);
	//去除第一个按钮
	var $button = thisObj.next();
	thisObj.width(thisObj.width() + $button.width()).attr("wchangeTag2", false);
	$button.remove();

	//去除第二个按钮
	$button = thisObj.next();
	thisObj.width(thisObj.width() + $button.width()).attr("wchangeTag2", false);
	$button.remove();
}

//ajax获取试用项目申请信息
function getTrialProject(id){
	var obj;
	$.ajax({
	    type: "POST",
	    url: "?model=projectmanagent_trialproject_trialproject&action=ajaxGetInfo",
	    data: {"id" : id },
	    async: false,
	    success: function(data){
	   		if(data){
				obj = eval("(" + data + ")");
	   	    }
		}
	});
	return obj;
}
//选择客户类型
function changeCustomerType(thisType){
	var chanceId = $("#chanceId").val();
	var customerId = $("#customerId").val();
	if((chanceId == "" || chanceId == '0') &&(customerId == "" || customerId == '0')){

		var customerType = $('#customerType').combobox('getValues').toString();//客户类型
		var province = $('#province').combobox('getValue');//省份
		var city = $('#city').combobox('getValues').toString();//城市
		if(province && city && customerType){
			//ajax获取销售负责人
			var responseText = $.ajax({
				url : 'index1.php?model=system_saleperson_saleperson&action=getSalePerson',
				data : { "province" : province , "city" : city , 'customerTypeName' : customerType },
				type : "POST",
				async : false
			}).responseText;

			//有返回值
			if(responseText != ""){
				var dataArr = eval("(" + responseText + ")");
				$('#costBelonger').combobox({
				    valueField:'areaName',
				    textField:'areaName',
					data : dataArr,
	  				editable : false,
					onSelect : function(obj){
						$("#costBelongerId").val(obj.areaNameId);
					}
				}).combobox('setValue','');
				$("#costBelongerId").val('');
			}
		}
	}
}

//重新载入城市
function reloadCity(data) {
	var str;

	//城市渲染
	var cityObj = $('#city');
	cityObj.combobox({
		url : "?model=system_procity_city&action=listJson&tProvinceName=" + data
	});
}

//清空销售信息
function clearSale(){
	//清空省市客户属性
	clearPCC();

	$("#chanceName").val('');
	$("#chanceId").val('');
	$("#chanceCode").val('');
	$("#customerName").val('');
	$("#customerId").val('');

	//重置费用归属部门
	if(isCombobox('costBelonger') == 1){
		$("#costBelonger").combobox("setValue",'');
		$("#costBelongerId").val('');
	}else{
		$("#costBelonger").val('');
		$("#costBelongerId").val('');
	}
}

//判断对象的combobox是否已存在
function isCombobox(objCode){
	if($("#" + objCode).attr("comboname")){
		return 1;
	}else{
		return 0;
	}
}

//清空客户省份、城市、客户类型系列
function clearPCC(){
	//清空省份信息
	$("#province").combobox('setValue','');

	//清空客户类型信息
	var customerTypeObj = $("#customerType");
	mulSelectClear(customerTypeObj);

	var cityObj = $("#city");
	mulSelectClear(cityObj);
}

//初始化客户
function initCustomer(){
	//先移除
	var customerNameObj = $("#customerName");
	customerNameObj.yxcombogrid_customer('remove');
	// 客户
	customerNameObj.yxcombogrid_customer({
		hiddenId : 'customerId',
		height : 300,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					//关闭其他入口
					closeInput('customer');

					$("#province").combobox('setValue',data.Prov);
					$("#provinceHidden").val(data.Prov);

					var customerTypeName = getDataByCode(data.TypeOne);
					var customerTypeObj = $("#CustomerType");
					customerTypeObj.combobox('setValue',customerTypeName);

					//重载客户城市
					reloadCity(data.Prov);
					var cityObj = $("#city");
					cityObj.combobox('setValue',data.City);

					//销售负责人
					$('#CostBelonger').combobox({
					    valueField:'text',
					    textField:'text',
	   					editable : false,
						data : [{"text":data.AreaLeader,"value": data.AreaLeaderId}],
						onSelect : function(obj){
							$("#CostBelongerId").val(obj.value);
						}
					}).combobox('setValue',data.AreaLeader);
					$("#CostBelongerId").val(data.AreaLeaderId);
				}
			}
		},
		event : {
			'clear' : function() {
				clearSale();

				//开启其他入口
				openInput('customer');
			}
		}
	}).attr('readonly',false).attr('class','txt');
}

//获取商机信息
function getChanceInfo(thisType){
	var projectCode = $("#projectCode").val();
	if(projectCode != "" && !thisType){
		return false;
	}
	var chanceCode = $("#chanceCode").val();
	var chanceName = $("#chanceName").val();
	if(chanceCode == "" && chanceName == ""){
		return false;
	}
	$.ajax({
	    type: "POST",
	    url: "?model=projectmanagent_chance_chance&action=ajaxChanceByCode",
	    data: {"chanceCode" : chanceCode , "chanceName" : chanceName},
	    async: false,
	    success: function(data){
	   		if(data){
				var dataArr = eval("(" + data + ")");
				if(dataArr.thisLength*1 > 1){
					alert('系统中存在【' + dataArr.thisLength + '】条名称为【' + chanceName + '】的商机，请通过商机编号匹配商机信息！');
					clearSale();
				}else{
					if(!thisType){
						//关闭其他入口
						closeInput('chance');
					}

					//商机信息赋值
					chanceSetValue(dataArr,thisType);
				}
	   	    }else{
				alert('没有查询到相关商机信息');
				clearSale();
	   	    }
		}
	});
}

//商机设值信息
function chanceSetValue(dataArr,thisType){
	$("#chanceCode").val(dataArr.chanceCode);
	$("#chanceId").val(dataArr.id);
	$("#chanceName").val(dataArr.chanceName);
	$("#customerId").val(dataArr.customerId);
	$("#customerName").val(dataArr.customerName);

	$("#province").combobox('setValue',dataArr.Province);
	$("#provinceHidden").val(dataArr.Province);

	//重载客户城市
	reloadCity(dataArr.Province);
	var cityObj = $("#city");
	cityObj.combobox('setValue',dataArr.City).combobox('disable');
	mulSelectSet(cityObj);

	//客户类型
	var customerTypeObj = $("#CustomerType");
	customerTypeObj.combobox('setValue',dataArr.customerTypeName);
	mulSelectSet(customerTypeObj);

	//销售负责人
	$('#CostBelonger').combobox({
	    valueField:'text',
	    textField:'text',
	    editable : false,
		data : [{"text":dataArr.prinvipalName,"value": dataArr.prinvipalId}],
		onSelect : function(obj){
			$("#CostBelongerId").val(obj.value);
		}
	}).combobox('setValue',dataArr.prinvipalName);
	$("#CostBelongerId").val(dataArr.prinvipalId);
}

//初始化需求订票人
function initRequireDetail(requireId){
    //获取订票需求乘机人信息并缓存
	var airArr;
	var airOptions = [{'name' : '','value' : ''}];
	$.ajax({
		type : 'POST',
		url : 'index1.php?model=flights_require_require&action=requireQuerylistJson',
		data : {
			id : requireId,
			dir : 'ASC'
		},
	    async: false,
		success : function(data) {
			airArr = eval("(" + data + ")");
			if(airArr.length > 0){
				for(var i = 0; i<airArr.length;i++){
					airOptions.push({'name' : airArr[i].airName,'value' : airArr[i].airId});
				}
			}
		}
	});

	var itemTableObj = $("#itemTable");
	itemTableObj.yxeditgrid("remove").yxeditgrid( {
		objName : 'message[items]',
//		url : 'index1.php?model=flights_require_require&action=requireQuerylistJson',
//        param : {
//                id : requireId
//            },
        data : airArr,
        title : '订票详细信息',
		isAddOneRow : true,
		colModel : [{
				name : 'auditDate',
				display : '核算日期',
				width : 'txtmiddle',
				width : 80,
				type : 'date',
				validation : {
					required : true
				}
			},{
				name : 'airName',
				display : '乘机人名称',
				type : 'hidden'
			},
			{
				name : 'airId',
				readonly : true,
				display : '乘机人',
				width : 110,
				type : 'select',
				options : airOptions,
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						itemTableObj.yxeditgrid("setRowColValue",rowNum,"airName",$(this).find(":selected").text());
					}
				},
				validation : {
					required : true
				}
			},
			{
				name : 'airline',
				tclass : 'txt',
				display : '航空公司',
				width : 120,
				validation : {
					required : true
				},
				process : function($input, rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_ticket( {
						hiddenId : 'itemTable_cmp_airlineId' + rowNum,
						gridOptions : {
							param : {"findVal" : "航空"}
						}
					});
				}
			},
			{
				name : 'airlineId',
				tclass : 'txt',
				display : '航空公司ID',
				type : 'hidden'
			},
			{
				name : 'flightNumber',
				tclass : 'txt',
				display : '车次/航班号',
				width : 120,
				validation : {
					required : true
				}
			},
			{
				name : 'flightTime',
				display : '乘机时间',
				width : 130,
				event : {
					click : function() {
							WdatePicker({
								dateFmt:"yyyy-MM-dd HH:mm:00"
							});
					}
				},
				validation : {
					required : true
				}
			},
			{
				name : 'arrivalTime',
				display : '到达时间',
				width : 130,
				event : {
					click : function() {
							WdatePicker({
								dateFmt:"yyyy-MM-dd HH:mm:00"
							});
					}
				},
				validation : {
					required : true
				}
			},
			{
				name : 'departPlace',
				display : '出发地点',
				width : 100,
				validation : {
					required : true
				}
			},
			{
				name : 'arrivalPlace',
				display : '到达地点',
				width : 100,
				validation : {
					required : true
				}
			},
			{
				name : 'isLow',
				type : 'checkbox',
				display : '当天最低',
				checked : false,
				checkVal : '1',
				width : 60,
				event : {
					click : function() {
						var rowNum = $(this).data("rowNum");
						if ($(this).attr("checked") == true) {
							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"lowremark").val("").addClass("readOnlyTxtNormal").removeClass("txt").attr("readonly",true);
							cancelReason(rowNum);
						} else {
							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"lowremark").addClass("txt").removeClass("readOnlyTxtNormal").attr("readonly",false);
							setReason(rowNum)
						}
					}
				}
			},
			{
				name : 'fullFare',
				tclass : 'txt',
				display : '票面价格',
				width : 80,
				type : 'money',
				validation : {
					required : true
				},
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						calDetail(rowNum);
					}
				}
			},
			{
				name : 'constructionCost',
				tclass : 'txt',
				display : '机场建设费',
				width : 80,
				type : 'money',
				validation : {
					required : true
				},
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						calDetail(rowNum);
					}
				}
			},
			{
				name : 'serviceCharge',
				tclass : 'txt',
				display : '服务费',
				width : 80,
				type : 'money',
				validation : {
					required : true
				},
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						calDetail(rowNum);
					}
				}
			},
			{
				name : 'fuelCcharge',
				tclass : 'txt',
				display : '燃油附加费',
				width : 80,
				type : 'money',
				validation : {
					required : true
				},
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						calDetail(rowNum);
					}
				}
			},
			{
				name : 'actualCost',
				tclass : 'txt',
				readonly : true,
				tclass : 'readOnlyTxtNormal',
				display : '实际订票金额',
				type : 'money',
				width : 80,
				validation : {
					required : true
				}
			},
			{
				name : 'lowremark',
				tclass : 'txt',
				display : '未采用最低价原因',
				validation : {
					required : true
				},
				width : 150
			},
			{
				name : 'remark',
				tclass : 'txt',
				display : '备注',
				width : 120
			}
		]
	});
}
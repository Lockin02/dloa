//初始化一些字段
//var objName = ''; //业务编码
//var initId = ''; //表单个模块id
//var actionType = ''; //动作类型 add edit view create
//var myUrl = ''; //动作类型
//var isCompanyReadonly = ''; //公司是否只读
var defaultCompany = '世纪鼎利'; //默认公司值
var defaultCompanyId = 'dl'; //默认公司值

//费用归属部门数组
var expenseSaleDept;
var expenseContractDept;
var expenseTrialProjectFeeDept;

$(document).ready(function() {
	if(checkCanInit() == false){
		return false;
	}

	if(actionType != 'add'){
		//ajax获取销售负责人
		var responseText = $.ajax({
			url:myUrl,
			data : {"id" : $("#id").val()},
			type : "POST",
			async : false
		}).responseText;
		var objInfo = eval("(" + responseText + ")");
	}
	if(actionType == 'view'){
		//初始化费用内容
		initCostTypeView(objInfo);
	}else{
		if(actionType == 'add'){
			initCostType();
		}else if(actionType == 'edit'){
			initCostTypeEdit(objInfo);
		}

		//绑定表单验证方法
		$("form").bind('submit',costCheckForm);
	}
});

//验证是否可初始化
function checkCanInit(){
	//初始化时验证变量是否存在
	try{
		objName;
	}catch(e){
		alert('无法初始化费用归属信息，请先定义编码！');
		return false;
	}

	//初始化时验证变量是否存在
	try{
		initId;
	}catch(e){
		alert('无法初始化费用归属信息，请先定义表单个模块id！');
		return false;
	}

	//初始化时验证变量是否存在
	try{
		actionType;
	}catch(e){
		alert('无法初始化费用归属信息，请先定义动作类型！');
		return false;
	}
	//如果不是新增，则要检验是否有定义数据获取路径
	if(actionType != 'add'){
		//初始化时验证变量是否存在
		try{
			myUrl;
		}catch(e){
			alert('无法初始化费用归属信息，请先定义业务信息获取路径！');
			return false;
		}
	}

	//判断公司是否已设定只读
	try{
		isCompanyReadonly;
	}catch(e){
		isCompanyReadonly = false;
	}
}

//初始化费用类型
function initCostType(){
    var tableStr = '<table class="form_in_table" id="'+objName+'tbl">' +
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
}

//选择费用类型
function changeDetailType(detailType){
	if(detailType){
		$("#detailTypeTitle").html('费用类型').removeClass('red').addClass('blue');
		$("#projectName").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
		$("#costBelongCom").yxcombogrid_branch('remove');
		$(".feeTypeContent").remove();
		switch(detailType){
			case '1' : initDept();break;
			case '2' : initContractProject();break;
			case '3' : initRdProject();break;
			case '4' : initSale();break;
			case '5' : initContract();break;
			default : break;
		}
	}
}

//初始化部门
function initDept(){
	var thisClass,thisCompany,thisCompanyId;
	if(isCompanyReadonly == true){
		thisClass = "readOnlyTxtNormal";
		thisCompany = defaultCompany;
		thisCompanyId = defaultCompanyId;
	}else{
		thisClass = "txt";
		thisCompany = "";
		thisCompanyId = "";
	}

	//默认获取
	var deptIdObj = $("#deptId");
	var deptNameObj = $("#deptName");
	var deptId,deptName;
	if(deptIdObj.length == 1){
		deptId = deptIdObj.val();
	}else{
		deptId = '';
	}
	if(deptNameObj.length == 1){
		deptName = deptNameObj.val();
	}else{
		deptName = '';
	}

	var tableStr = '<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">费用归属公司</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="'+ thisClass + '" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+thisCompany+'" readonly="readonly"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+thisCompanyId+'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">费用归属部门</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" value="'+ deptName +'" readonly="readonly"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+ deptId +'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">工 作 组</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" />' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" />' +
				'<input type="hidden" id="projectType" name="'+objName+'[projectType]" />' +
			'</td>' +
			'<td class = "form_text_left">工作组经理</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" />' +
			'</td>' +
		'</tr>';
	$("#"+objName + "tbl").append(tableStr);

	if(!isCompanyReadonly == true){
		//公司渲染
		$("#costBelongCom").yxcombogrid_branch({
			hiddenId : 'costBelongComId',
			height : 250,
			isFocusoutCheck : false,
			gridOptions : {
				showcheckbox : false
			}
		});
	}
	//费用归属部门选择
	$("#costBelongDeptName").yxselect_dept({
		hiddenId : 'costBelongDeptId',
        event : {
            'selectReturn' : function(e,obj){
                //ajax获取销售负责人
                var responseText = $.ajax({
                    url:'index1.php?model=flights_require_require&action=deptNeedInfo',
                    data : { "deptId" : obj.val },
                    type : "POST",
                    async : false
                }).responseText;
                var dataArr = eval("(" + responseText + ")");
                for(k in dataArr){
                    $("#" + k).val(dataArr[k]);
                }
                //初始化
                initDeptAppendInfo();
            }
        }
	});

	//合同项目渲染
	$("#projectName").yxcombogrid_esmproject({
		isDown : true,
		hiddenId : 'projectId',
		nameCol : 'projectName',
		searchName : 'projectNameSearch',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {'productLine' : 'GCSCX-05' , 'statusArr' : 'GCXMZT02'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectCode").val(data.projectCode);
					$("#proManagerName").val(data.managerName);
					$("#proManagerId").val(data.managerId);
					$("#projectType").val('esm');
				}
			}
		},
		event : {
			'clear' : function() {
				$("#projectCode").val('');
				$("#proManagerName").val('');
				$("#proManagerId").val('');
				$("#projectType").val('');
			}
		}
	});

    //渲染省份、客户类型
    initDeptAppendInfo();
}

//渲染客户类型
function initDeptAppendInfo(objInfo){
    var deptIsNeedProvince = $("#deptIsNeedProvince").val();
    var deptIsNeedCustomerType = $("#deptIsNeedCustomerType").val();

    var province = '';
    var customerType = '';
    if(objInfo){
        province = objInfo.province;
        customerType = objInfo.customerType;
    }
    //如果需要部门检查
    if(deptIsNeedProvince == "1" || deptIsNeedCustomerType == "1"){
        if($("#deptAppendInfo").length == 1){
            $("#deptAppendInfo").remove();
        }
        var tableStr = '<tr class="feeTypeContent" id="deptAppendInfo">';
        //分别渲染
        if(deptIsNeedProvince == "1" && deptIsNeedCustomerType == "0"){
            tableStr += '<td class = "form_text_left"><span class="blue">省份</span></td>' +
                '<td class = "form_text_right" colspan="3">' +
                '<input type="text" class="txt" id="province" name="'+objName+'[province]" value="'+province+'" readonly="readonly" style="width:202px;"/>' +
                '<input type="hidden" id="customerType" name="'+objName+'[customerType]" value=""/>' +
                '</td>';
        }
        //分别渲染
        if(deptIsNeedProvince == "0" && deptIsNeedCustomerType == "1"){
            tableStr += '<td class = "form_text_left"><span class="blue">客户类型</span></td>' +
                '<td class = "form_text_right" colspan="3">' +
                '<input type="text" class="txt" id="customerType" name="'+objName+'[customerType]" value="'+customerType+'" readonly="readonly" style="width:202px;"/>' +
                '<input type="hidden" id="province" name="'+objName+'[province]" value=""/>' +
                '</td>';
        }

        //两种情况都存在
        if(deptIsNeedProvince == "1" && deptIsNeedCustomerType == "1"){
            tableStr += '<td class = "form_text_left"><span class="blue">省份</span></td>' +
                '<td class = "form_text_right">' +
                '<input type="text" class="txt" id="province" name="'+objName+'[province]" value="'+province+'" readonly="readonly" style="width:202px;"/>' +
                '</td>'+'<td class = "form_text_left"><span class="blue">客户类型</span></td>' +
                '<td class = "form_text_right">' +
                '<input type="text" class="txt" id="customerType" name="'+objName+'[customerType]" value="'+customerType+'" readonly="readonly" style="width:202px;"/>' +
                '</td>';
        }

        //页面加载
        tableStr += '</tr>';
        $("#"+objName + "tbl").append(tableStr);

        if(deptIsNeedProvince == "1" ){
            $('#province').combobox({
                url:'index1.php?model=system_procity_province&action=listJsonSort',
                valueField:'provinceName',
                textField:'provinceName',
                editable : false
            });
        }
        if(deptIsNeedCustomerType == "1" ){
            //客户类型渲染
            $('#customerType').combobox({
                url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
                valueField:'text',
                textField:'text',
                editable : false
            });
        }
    }else if(deptIsNeedProvince == "0"){
        //清除
        clearDeptAppendInfo();
    }
}

//消除需要检查的部门扩展内容
function clearDeptAppendInfo(){
    //如果需要部门检查
    if($("#deptAppendInfo").length == 1){
        //隐藏表格并却清空赋值
        $("#deptAppendInfo").hide();
        $('#清空province').combobox('setValue','');
        $('#customerType').combobox('setValue','');
    }
}

//初始化合同项目
function initContractProject(){
	var thisCompany,thisCompanyId;
	if(isCompanyReadonly == true){
		thisCompany = defaultCompany;
		thisCompanyId = defaultCompanyId;
	}else{
		thisCompany = "";
		thisCompanyId = "";
	}
	var tableStr = '<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">项目名称</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" />' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" />' +
				'<input type="hidden" id="projectType" name="'+objName+'[projectType]" />' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" />' +
				'<input type="hidden" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">项目经理</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" />' +
			'</td>' +
		'</tr>'+
		'<tr class="feeTypeContent">'+
			'<td class = "form_text_left"><span class="blue">费用归属部门</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
			'<input type="text" class="readOnlyTxtNormal" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" readonly="readonly"/>' +
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
					$("#projectType").val(data.projectType);

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
function initRdProject(){
	var thisCompany,thisCompanyId;
	if(isCompanyReadonly == true){
		thisCompany = defaultCompany;
		thisCompanyId = defaultCompanyId;
	}else{
		thisCompany = "";
		thisCompanyId = "";
	}
	var tableStr = '<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">项目名称</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" />' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" />' +
				'<input type="hidden" id="projectType" name="'+objName+'[projectType]" />' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" />' +
				'<input type="hidden" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">项目经理</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" />' +
			'</td>' +
		'</tr>'+
		'<tr class="feeTypeContent">'+
			'<td class = "form_text_left"><span class="blue">费用归属部门</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
			'<input type="text" class="readOnlyTxtNormal" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" readonly="readonly"/>' +
			'</td>' +
		'</tr>';
	$("#"+objName + "tbl").append(tableStr);

	//研发项目渲染
	$("#projectName").yxcombogrid_esmproject({
		isDown : true,
		hiddenId : 'projectId',
		nameCol : 'projectName',
		searchName : 'projectName',
		isShowButton : false,
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param: {attribute: 'GCXMSS-05', statusArr: 'GCXMZT02'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectCode").val(data.projectCode);
					$("#proManagerName").val(data.managerName);
					$("#proManagerId").val(data.managerId);
					$("#projectType").val('esm');

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
function initSale(){
	var thisCompany,thisCompanyId;
	if(isCompanyReadonly == true){
		thisCompany = defaultCompany;
		thisCompanyId = defaultCompanyId;
	}else{
		thisCompany = "";
		thisCompanyId = "";
	}
	var tableStr = '<tr class="feeTypeContent">' +
			'<td class = "form_text_left">试用项目名称</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" />' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" />' +
				'<input type="hidden" id="projectType" name="'+objName+'[projectType]" />' +
				'<input type="hidden" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
			'</td>' +
			'<td class = "form_text_left">项目经理</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" />' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">商机编号</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="chanceCode" name="'+objName+'[chanceCode]" onblur="getChanceInfo();"/>' +
				'<input type="hidden" id="chanceName" name="'+objName+'[chanceName]" />' +
				'<input type="hidden" id="chanceId" name="'+objName+'[chanceId]" />' +
			'</td>' +
			'<td class = "form_text_left">客户名称</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="customerName" name="'+objName+'[customerName]"/>' +
				'<input type="hidden" id="customerId" name="'+objName+'[customerId]" />' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">客户省份</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="province" name="'+objName+'[province]" style="width:202px;"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">客户城市</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="city" name="'+objName+'[city]" style="width:202px;"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">客户类型</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="customerType" name="'+objName+'[customerType]" style="width:202px;"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">销售负责人</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="costBelonger" name="'+objName+'[costBelonger]" style="width:202px;"/>' +
				'<input type="hidden" id="costBelongerId" name="'+objName+'[costBelongerId]" />' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">费用归属部门</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
				'<input type="text" class="txt" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" style="width:202px;"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" />' +
			'</td>' +
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
			param : {'contractType' : 'GCXMYD-04' , 'statusArr' : 'GCXMZT02'},
			event : {
				'row_dblclick' : function(e,row,data) {
					//禁用其他入口
					closeInput('trialPlan',data.id);

					$("#projectCode").val(data.projectCode);
					$("#proManagerName").val(data.managerName);
					$("#proManagerId").val(data.managerId);
					$("#projectType").val('esm');

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

							$("#customerType").combobox('setValue',trialProjectInfo.customerTypeName);

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
							$("#city").combobox('setValue',trialProjectInfo.city);
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
				$("#projectType").val('');
				clearSale();

				//开启其他入口
				openInput('trialPlan');
			}
		}
	}).attr('readonly',false).attr('class','txt');

	//初始化客户
	initCustomer();

	//客户类型渲染
	$('#customerType').combobox({
		url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
		valueField:'text',
        textField:'text',
  		editable : false,
		onSelect : function(){
			//设置销售负责人
			changeCustomerType();
		},
		onUnselect : function(){
			//设置销售负责人
			changeCustomerType();
		}
	});

	//省份渲染
	$('#province').combobox({
		url:'index1.php?model=system_procity_province&action=listJsonSort',
		valueField:'provinceName',
        textField:'provinceName',
		editable : false,
		onSelect : function(obj){
			//根据省份读取城市
			reloadCity(obj.provinceName);
		}
	});

	//城市渲染
	$('#city').combobox({
		textField:'cityName',
		valueField:'cityName',
		multiple:true,
		editable : false,
        formatter: function(obj){
			return "<input type='checkbox' id='city_"+ obj.cityName +"' value='"+ obj.cityName +"'/> " + obj.cityName;
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
	var dataArr = expenseSaleDept;
	$('#costBelongDeptName').combobox({
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
function initContract(){
	var thisCompany,thisCompanyId;
	if(isCompanyReadonly == true){
		thisCompany = defaultCompany;
		thisCompanyId = defaultCompanyId;
	}else{
		thisCompany = "";
		thisCompanyId = "";
	}
	var tableStr = '<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">合同编号</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="contractCode" name="'+objName+'[contractCode]" onblur="getContractInfo()"/>' +
				'<input type="hidden" id="contractName" name="'+objName+'[contractName]" />' +
				'<input type="hidden" id="contractId" name="'+objName+'[contractId]" />' +
				'<input type="hidden" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
			'</td>' +
			'<td class = "form_text_left">客户名称</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="customerName" name="'+objName+'[customerName]" readonly="readonly"/>' +
				'<input type="hidden" id="customerId" name="'+objName+'[customerId]" />' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">客户省份</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="province" name="'+objName+'[province]" readonly="readonly"/>' +
			'</td>' +
			'<td class = "form_text_left">客户城市</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="city" name="'+objName+'[city]" readonly="readonly"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">客户类型</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="customerType" name="'+objName+'[customerType]" readonly="readonly"/>' +
			'</td>' +
			'<td class = "form_text_left">销售负责人</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="costBelonger" name="'+objName+'[costBelonger]" readonly="readonly"/>' +
				'<input type="hidden" id="costBelongerId" name="'+objName+'[costBelongerId]" />' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">费用归属部门</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
				'<input type="text" class="txt" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" style="width:202px;"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" />' +
			'</td>' +
		'</tr>';
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
	var dataArr = expenseContractDept;
    $('#costBelongDeptName').combobox({
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

	//清空客户类型信息
	$("#customerType").combobox('setValue','');

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
		//clearInputSet('chanceName');
	}else if(thisType == 'customer'){
		//项目
		$("#projectCode").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');
		$("#projectName").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');

		//商机
		$("#chanceCode").attr("class",'readOnlyTxtNormal').attr('readonly',true);
		$("#chanceName").attr("class",'readOnlyTxtNormal').attr('readonly',true);

		//清除商机的渲染
		clearInputSet('chanceCode');
		//clearInputSet('chanceName');
	}else if(thisType == 'chance'){
		//项目
		$("#projectCode").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');
		$("#projectName").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');
		$("#customerName").attr("class",'readOnlyTxtNormal').yxcombogrid_customer('remove').attr('readonly',true);
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
	$("#customerType").combobox('enable');
	$("#costBelonger").combobox('enable');
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

		var customerType = $('#customerType').combobox('getValue');//客户类型
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
				var costBelongerObj = $('#costBelonger');
				costBelongerObj.combobox({
				    valueField:'areaName',
				    textField:'areaName',
					data : dataArr,
	  				editable : false,
					onSelect : function(obj){
						$("#costBelongerId").val(obj.areaNameId);
					}
				});

				if(thisType != 'init'){
					costBelongerObj.combobox('setValue','');
					$("#costBelongerId").val('');
				}
			}
		}else if(thisType == 'init'){
			//销售负责人
			$('#costBelonger').combobox({
			    valueField:'text',
			    textField:'text',
   				editable : false,
				data : [{"text":$("#costBelonger").val(),"value":$("#costBelongerId").val()}],
				onSelect : function(obj){
					$("#costBelongerId").val(obj.value);
				}
			});
		}
	}
}

//重新载入城市
function reloadCity(data) {
	//城市渲染
	$('#city').combobox({
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
	$("#customerType").combobox('setValue','');

	mulSelectClear($("#city"));
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

					var customerTypeName = getDataByCode(data.TypeOne);
					$("#customerType").combobox('setValue',customerTypeName);

					//重载客户城市
					reloadCity(data.Prov);
					$("#city").combobox('setValue',data.City);

					//销售负责人
					$('#costBelonger').combobox({
					    valueField:'text',
					    textField:'text',
	   					editable : false,
						data : [{"text":data.AreaLeader,"value": data.AreaLeaderId}],
						onSelect : function(obj){
							$("#costBelongerId").val(obj.value);
						}
					}).combobox('setValue',data.AreaLeader);
					$("#costBelongerId").val(data.AreaLeaderId);
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
	if($("#projectCode").val() != "" && !thisType){
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
function chanceSetValue(dataArr){
	$("#chanceCode").val(dataArr.chanceCode);
	$("#chanceId").val(dataArr.id);
	$("#chanceName").val(dataArr.chanceName);
	$("#customerId").val(dataArr.customerId);
	$("#customerName").val(dataArr.customerName);

	$("#province").combobox('setValue',dataArr.Province);

	//重载客户城市
	reloadCity(dataArr.Province);
	$("#city").combobox('setValue',dataArr.City).combobox('disable');

	//客户类型
	$("#customerType").combobox('setValue',dataArr.customerTypeName);

	//销售负责人
	$('#costBelonger').combobox({
	    valueField:'text',
	    textField:'text',
	    editable : false,
		data : [{"text":dataArr.prinvipalName,"value": dataArr.prinvipalId}],
		onSelect : function(obj){
			$("#costBelongerId").val(obj.value);
		}
	}).combobox('setValue',dataArr.prinvipalName);
	$("#costBelongerId").val(dataArr.prinvipalId);
}

//初始化费用内容
function initCostTypeView(objInfo){
	if(objInfo.detailType){
		switch(objInfo.detailType){
			case '1' : initDeptView(objInfo);break;
			case '2' : initProjectView(objInfo);break;
			case '3' : initProjectView(objInfo);break;
			case '4' : initSaleView(objInfo);break;
			case '5' : initContractView(objInfo);break;
			default : break;
		}
	}
}

//初始化部门
function initDeptView(objInfo){
	var tableStr = '<table class="form_in_table" id="'+objName+'tbl">' +
        '<tr id="feeTypeTr">' +
            '<td class = "form_text_left"><span id="detailTypeTitle">费用类型</span></td>' +
            '<td class = "form_text_right" colspan="3">' +
                '部门费用' +
            '</td>' +
        '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left">费用归属公司</td>' +
            '<td class = "form_text_right" width="30%">' +
                objInfo.costBelongCom +
            '</td>' +
            '<td class = "form_text_left">费用归属部门</td>' +
            '<td class = "form_text_right">' +
                objInfo.costBelongDeptName +
            '</td>' +
        '</tr>' +
        '<tr class="feeTypeContent">' +
            '<td class = "form_text_left">工 作 组</td>' +
            '<td class = "form_text_right" width="30%">' +
                objInfo.projectName +
            '</td>' +
            '<td class = "form_text_left">工作组经理</td>' +
            '<td class = "form_text_right">' +
                objInfo.proManagerName +
            '</td>' +
        '</tr>';
    //如果含有省份信息
    if(objInfo.province && objInfo.customerType == ""){
        //插入省份
        tableStr += "<tr><td class='form_text_left'>所属省份</td><td class='form_text_right' colspan='3'>"+objInfo.province+"</td></tr>";
    }
    if(objInfo.province == "" && objInfo.customerType){
        //插入客户类型
        tableStr += "<tr><td class='form_text_left'>客户类型</td><td class='form_text_right' colspan='3'>"+objInfo.customerType+"</td></tr>";
    }
    if(objInfo.province && objInfo.customerType){
        //插入省份和客户类型
        tableStr += "<tr><td class='form_text_left'>所属省份</td><td class='form_text_right'>"+objInfo.province+"</td>" +
            "<td class='form_text_left'>客户类型</td><td class='form_text_right'>"+objInfo.customerType+"</td>" +
            "</tr>";
    }
    tableStr += '</table>';
	$("#"+initId).html(tableStr);
}

//初始化项目查看
function initProjectView(objInfo){
    var projectView = objInfo.detailType == '2' ? '合同项目费用' : '研发费用';
	var tableStr = '<table class="form_in_table" id="'+objName+'tbl">' +
			'<tr id="feeTypeTr">' +
				'<td class = "form_text_left"><span id="detailTypeTitle">费用类型</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
                    projectView +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">项目名称</span></td>' +
				'<td class = "form_text_right" width="30%">' +
					objInfo.projectName +
				'</td>' +
				'<td class = "form_text_left">项目经理</td>' +
				'<td class = "form_text_right">' +
					objInfo.proManagerName +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">费用归属部门</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					objInfo.costBelongDeptName +
				'</td>' +
			'</tr>' +
		'</table>';
	$("#"+initId).html(tableStr);
}

//初始化售前
function initSaleView(objInfo){
	var tableStr = '<table class="form_in_table" id="'+objName+'tbl">' +
			'<tr id="feeTypeTr">' +
				'<td class = "form_text_left"><span id="detailTypeTitle">费用类型</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'售前费用' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">试用项目名称</td>' +
				'<td class = "form_text_right" width="30%">' +
					objInfo.projectName +
				'</td>' +
				'<td class = "form_text_left">项目经理</td>' +
				'<td class = "form_text_right">' +
					objInfo.proManagerName +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">商机编号</td>' +
				'<td class = "form_text_right">' +
					objInfo.chanceCode +
				'</td>' +
				'<td class = "form_text_left">客户名称</td>' +
				'<td class = "form_text_right">' +
					objInfo.customerName +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">客户省份</td>' +
				'<td class = "form_text_right">' +
					objInfo.province +
				'</td>' +
				'<td class = "form_text_left">客户城市</td>' +
				'<td class = "form_text_right">' +
					objInfo.city +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">客户类型</td>' +
				'<td class = "form_text_right">' +
					objInfo.customerType +
				'</td>' +
				'<td class = "form_text_left">销售负责人</td>' +
				'<td class = "form_text_right">' +
					objInfo.costBelonger +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">费用归属部门</td>' +
				'<td class = "form_text_right" colspan="3">' +
					objInfo.costBelongDeptName +
				'</td>' +
			'</tr>'+
		'</table>';
	$("#"+initId).html(tableStr);
}

//初始化售后
function initContractView(objInfo){
	var tableStr = '<table class="form_in_table" id="'+objName+'tbl">' +
			'<tr id="feeTypeTr">' +
				'<td class = "form_text_left"><span id="detailTypeTitle">费用类型</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'售后费用' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">合同编号</td>' +
				'<td class = "form_text_right" width="30%">' +
					objInfo.contractCode +
				'</td>' +
				'<td class = "form_text_left">客户名称</td>' +
				'<td class = "form_text_right">' +
					objInfo.customerName +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">客户省份</td>' +
				'<td class = "form_text_right">' +
					objInfo.province +
				'</td>' +
				'<td class = "form_text_left">客户城市</td>' +
				'<td class = "form_text_right">' +
					objInfo.city +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">客户类型</td>' +
				'<td class = "form_text_right">' +
					objInfo.customerType +
				'</td>' +
				'<td class = "form_text_left">销售负责人</td>' +
				'<td class = "form_text_right">' +
					objInfo.costBelonger +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">费用归属部门</td>' +
				'<td class = "form_text_right" colspan="3">' +
					objInfo.costBelongDeptName +
				'</td>' +
			'</tr>' +
		'</table>';
    $("#"+initId).html(tableStr);
}

//初始化费用类型
function initCostTypeEdit(objInfo){
	var tableStr = '<table class="form_in_table" id="'+objName+'tbl">' +
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
	changeDetailTypeEdit(objInfo.detailType,objInfo);
}

//选择费用类型
function changeDetailTypeEdit(detailType,objInfo){
	if(detailType){
		//附选中值
		$("input[name='"+objName+"[detailType]']").each(function(i,n){
			if(this.value == detailType){
				$(this).attr("checked",this);
				return false;
			}
		});
		$("#detailTypeTitle").html('费用类型').removeClass('red').addClass('blue');
		$("#projectName").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove')
			.yxcombogrid_rdprojectfordl('remove');
		$(".feeTypeContent").remove();
		switch(detailType){
			case '1' : initDeptEdit(objInfo);break;
			case '2' : initContractProjectEdit(objInfo);break;
			case '3' : initRdProjectEdit(objInfo);break;
			case '4' : initSaleEdit(objInfo);break;
			case '5' : initContractEdit(objInfo);break;
			default : break;
		}
	}
}

//初始化部门
function initDeptEdit(objInfo){
	//初始值赋予
	var costBelongCom='',costBelongComId='',costBelongDeptName='',costBelongDeptId='';
	var projectName='',projectCode='',projectId='',projectType='',proManagerName='',proManagerId='';
	if(objInfo){
		costBelongCom = objInfo.costBelongCom;
		costBelongComId = objInfo.costBelongComId;
		costBelongDeptName = objInfo.costBelongDeptName;
		costBelongDeptId = objInfo.costBelongDeptId;
		projectName = objInfo.projectName;
		projectCode = objInfo.projectCode;
		projectId = objInfo.projectId;
		projectType = objInfo.projectType;
		proManagerName = objInfo.proManagerName;
		proManagerId = objInfo.proManagerId;
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
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">工 作 组</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" value="'+projectName +'" readonly="readonly"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" value="'+projectCode +'"/>' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" value="'+projectId +'" />'+
				'<input type="hidden" id="projectType" name="'+objName+'[projectType]" value="'+projectType +'"/>' +
			'</td>' +
			'<td class = "form_text_left">工作组经理</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" value="'+proManagerName +'" readonly="readonly"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" value="'+proManagerId +'"/>' +
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
		hiddenId : 'costBelongDeptId',
        event : {
            'selectReturn' : function(e,obj){
                //ajax获取销售负责人
                var responseText = $.ajax({
                    url:'index1.php?model=flights_require_require&action=deptNeedInfo',
                    data : { "deptId" : obj.val },
                    type : "POST",
                    async : false
                }).responseText;
                var dataArr = eval("(" + responseText + ")");
                for(k in dataArr){
                    $("#" + k).val(dataArr[k]);
                }
                //初始化
                initDeptAppendInfo();
            }
        }
	});

	//合同项目渲染
	$("#projectName").yxcombogrid_esmproject({
		isDown : true,
		hiddenId : 'projectId',
		nameCol : 'projectName',
		searchName : 'projectNameSearch',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {'productLine' : 'GCSCX-05' , 'statusArr' : 'GCXMZT02'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectCode").val(data.projectCode);
					$("#proManagerName").val(data.managerName);
					$("#proManagerId").val(data.managerId);
					$("#projectType").val('esm');
				}
			}
		},
		event : {
			'clear' : function() {
				$("#projectCode").val('');
				$("#proManagerName").val('');
				$("#proManagerId").val('');
				$("#projectType").val('');
			}
		}
	});

    //初始化
    initDeptAppendInfo(objInfo);
}

//初始化合同项目
function initContractProjectEdit(objInfo){
	//初始值赋予
	var projectName='',projectCode='',projectId='',projectId='',costBelongDeptName='',costBelongDeptId='',proManagerName='',proManagerId='',projectType='';
	if(objInfo){
		projectName = objInfo.projectName;
		projectCode = objInfo.projectCode;
		projectId = objInfo.projectId;
        projectType = objInfo.projectType;
		costBelongDeptName = objInfo.costBelongDeptName;
		costBelongDeptId = objInfo.costBelongDeptId;
		proManagerName = objInfo.proManagerName;
		proManagerId = objInfo.proManagerId;
	}
	var thisCompany,thisCompanyId;
	if(isCompanyReadonly == true){
		thisCompany = defaultCompany;
		thisCompanyId = defaultCompanyId;
	}else{
		thisCompany = "";
		thisCompanyId = "";
	}
	var tableStr = '<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">项目名称</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly" value="'+projectName +'"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" value="'+projectCode +'"/>' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" value="'+projectId +'"/>' +
                '<input type="hidden" id="projectType" name="'+objName+'[projectType]" value="'+projectType +'"/>' +
				'<input type="hidden" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" value="'+costBelongDeptName +'"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
				'<input type="hidden" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">项目经理</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" value="'+proManagerId +'"/>' +
			'</td>' +
		'</tr>'+
		'<tr class="feeTypeContent">'+
			'<td class = "form_text_left"><span class="blue">费用归属部门</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
			'<input type="text" class="readOnlyTxtNormal" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" readonly="readonly"  value="'+costBelongDeptName +'"/>' +
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
				$("#projectId").val('');
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
function initRdProjectEdit(objInfo){
	//初始值赋予
	var projectName='',projectCode='',projectId='',costBelongDeptName='',costBelongDeptId='',proManagerName='',proManagerId='';
	if(objInfo){
		projectName = objInfo.projectName;
		projectCode = objInfo.projectCode;
		projectId = objInfo.projectId;
		projectType = objInfo.projectType;
		costBelongDeptName = objInfo.costBelongDeptName;
		costBelongDeptId = objInfo.costBelongDeptId;
		proManagerName = objInfo.proManagerName;
		proManagerId = objInfo.proManagerId;
	}
	var thisCompany,thisCompanyId;
	if(isCompanyReadonly == true){
		thisCompany = defaultCompany;
		thisCompanyId = defaultCompanyId;
	}else{
		thisCompany = "";
		thisCompanyId = "";
	}
	var tableStr = '<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">项目名称</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly" value="'+projectName +'"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" value="'+projectCode +'"/>' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" value="'+projectId +'"/>' +
				'<input type="hidden" id="projectType" name="'+objName+'[projectType]" value="'+projectType +'"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
				'<input type="hidden" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">项目经理</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" value="'+proManagerId +'"/>' +
			'</td>' +
		'</tr>'+
		'<tr class="feeTypeContent">'+
			'<td class = "form_text_left"><span class="blue">费用归属部门</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
			'<input type="text" class="readOnlyTxtNormal" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" readonly="readonly" value="'+costBelongDeptName+'"/>' +
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
					$("#projectId").val(data.projectId);
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
				$("#projectId").val('');
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
function initSaleEdit(objInfo){
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
			'</td>' +
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
			param : {'contractType' : 'GCXMYD-04' , 'statusArr' : 'GCXMZT02'},
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

							$("#customerType").combobox('setValue',trialProjectInfo.customerTypeName);

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
							$("#city").combobox('setValue',trialProjectInfo.city);
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

	//客户类型渲染
	$('#customerType').combobox({
		url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
		valueField:'text',
        textField:'text',
  		editable : false,
		onSelect : function(){
			//设置销售负责人
			changeCustomerType();
		},
		onUnselect : function(){
			//设置销售负责人
			changeCustomerType();
		}
	});

	//省份渲染
	$('#province').combobox({
		url:'index1.php?model=system_procity_province&action=listJsonSort',
		valueField:'provinceName',
        textField:'provinceName',
		editable : false,
		onSelect : function(obj){
			//根据省份读取城市
			reloadCity(obj.provinceName);
		}
	});

	//城市渲染
	var cityArr = city.split(',');
	$('#city').combobox({
		url : "?model=system_procity_city&action=listJson&tProvinceName=" + province,
		textField:'cityName',
		valueField:'cityName',
		multiple:true,
		editable : false,
        formatter: function(obj){
        	//判断 如果没有初始化数组中，则选中
        	if(cityArr.indexOf(obj.cityName) == -1){
    			return "<input type='checkbox' id='city_"+ obj.cityName +"' value='"+ obj.cityName +"'/> " + obj.cityName;
        	}else{
    			return "<input type='checkbox' id='city_"+ obj.cityName +"' value='"+ obj.cityName +"' checked='checked'/> " + obj.cityName;
        	}
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
	}).combobox('setValues',cityArr);

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
	var dataArr = expenseSaleDept;
	$('#costBelongDeptName').combobox({
		data : dataArr,
	    valueField:'text',
	    textField:'text',
		editable : false,
		onSelect : function(obj){
			$("#costBelongDeptId").val(obj.value);
		}
	});

	//调用一次禁用窗口
	closeInput();
	//调用一次设置销售负责人
	changeCustomerType('init');
}

//初始化售后
function initContractEdit(objInfo){
	//初始值赋予
	var costBelongDeptName='',costBelongDeptId='',contractCode='',contractName='';
	var contractId='',customerName='',customerId='',province='',city='',customerType='',costBelonger='',costBelongerId='';
	if(objInfo){
		costBelongDeptName = objInfo.costBelongDeptName;
		costBelongDeptId = objInfo.costBelongDeptId;
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
			'</td>' +
		'</tr>';
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
	var dataArr = expenseContractDept;
	$('#costBelongDeptName').combobox({
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

//表单验证方法
function costCheckForm(){
	var detailType = $("input[name='" +objName+ "[detailType]']:checked").val();
	if(detailType){
		//类型 对应特殊验证
		switch(detailType){
			case '1' :
				if($("#costBelongCom").val() == ""){
					alert("没有填写费用归属公司");
					return false;
				}
				if($("#costBelongDeptName").val() == ""){
					alert("没有填写费用归属部门");
					return false;
				}
                if($("#deptIsNeedProvince").val() == "1"){
                    var province = $("#province").combobox('getValue');
                    if(province == ""){
                        alert("请选择所属省份");
                        return false;
                    }
                }
				break;
			case '2' :
				if($("#projectCode").val() == ""){
					alert("请选择该笔费用所在工程项目");
					return false;
				}
				break;
			case '3' :
				if($("#projectCode").val() == ""){
					alert("请选择该笔费用所在研发项目");
					return false;
				}
				break;
			case '4' :
				if($("#province").combobox('getValue') == ""){
					alert("请选择客户所在省份");
					return false;
				}
				if($("#city").combobox('getValues') == ""){
					alert("请选择客户所在城市");
					return false;
				}
				if($("#customerType").combobox('getValue') == ""){
					alert("请选择客户类型");
					return false;
				}
				if($("#costBelongerId").val() == ""){
					alert("请录入销售负责人，销售负责人可由商机、客户名称自动带出，或者通过客户省份、城市、类型由系统匹配");
					return false;
				}
				if($("#costBelongDeptId").val() == "" || $("#costBelongDeptName").combobox('getValue') ==""){
					alert("请选择费用归属部门");
					return false;
				}
				break;
			case '5' :
				if($("#contractCode").val() == ""){
					alert("请选择该笔费用归属合同");
					return false;
				}
				if($("#costBelongDeptId").val() == "" || $("#costBelongDeptName").combobox('getValue') ==""){
					alert("请选择费用归属部门");
					return false;
				}
				break;
			default : break;
		}
		return true;
	}else{
		alert('请选择费用类型');
		return false;
	}
}
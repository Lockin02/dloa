//费用归属组件
(function($){
	//默认属性
	var defaults = {
		getId : 'id', //取数查询对象id
		objName : 'objName', //业务编码
		actionType : 'add', //动作类型 add edit view create,默认add
		url : '', //取数url
		data : {},//传入数据
		isCompanyReadonly : false, //公司是否只读
		company : '世纪鼎利', //默认公司值
		companyId : 'dl', //默认公司值
		isRequired : true//是否费用归属必填
	};

	//费用归属部门数组 - 用于缓存数据
	var expenseSaleDept;
	var expenseContractDept;
	var expenseTrialProjectFeeDept;

	//================== 内部方法 ====================//
	//初始化费用类型
	function initCostType(thisObj){
		var tableStr = '<table class="form_in_table" id="'+ defaults.myId +'tbl">' +
				'<tr id="feeTypeTr">' +
					'<td class = "form_text_left_three"><span id="detailTypeTitle" class="red">请选择费用类型</span></td>' +
					'<td class = "form_text_right" colspan="5">' +
						'<input type="radio" name="'+defaults.objName+'[detailType]" value="1"/> 部门费用 ' +
						'<input type="radio" name="'+defaults.objName+'[detailType]" value="2"/> 合同项目费用 ' +
						'<input type="radio" name="'+defaults.objName+'[detailType]" value="3"/> 研发费用 ' +
						'<input type="radio" name="'+defaults.objName+'[detailType]" value="4"/> 售前费用 ' +
						'<input type="radio" name="'+defaults.objName+'[detailType]" value="5"/> 售后费用 ' +
					'</td>' +
				'</tr>' +
			'</table>';
		$(thisObj).html(tableStr);
		$("input[name='"+defaults.objName+"[detailType]']").each(function(){
			$(this).bind('click',resetCombo);

			//按费用类型绑定触发事件
			switch(this.value){
				case '1' :
					$(this).bind('click',initDept);
					break;
				case '2' :
					$(this).bind('click',initContractProject);
					break;
				case '3' :
					$(this).bind('click',initRdProject);
					break;
				case '4' :
					$(this).bind('click',initSale);
					break;
				case '5' :
					$(this).bind('click',initContract);
					break;
				default : break;
			}
		});
	}

	//重置各类下拉
	function resetCombo(){
		$("#detailTypeTitle").html('费用类型').removeClass('red').addClass('blue');
		$("#projectName").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
		$("#projectCode").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
		$("#costBelongCom").yxcombogrid_branch('remove');
		$(".feeTypeContent").remove();
	}

	/****************************** 不同费用调用 ***********************************/
	//初始化部门 TODO
	function initDept(){
		var thisClass,thisCompany,thisCompanyId;
		if(defaults.isCompanyReadonly == true){
			thisClass = "readOnlyTxtNormal";
		}else{
			thisClass = "txt";
		}
		thisCompany = defaults.company;
		thisCompanyId = defaults.companyId;

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
				'<td class = "form_text_left_three"><span class="blue">费用归属公司</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="'+ thisClass + '" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+defaults.company+'" readonly="readonly"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+thisCompanyId+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" value="'+ deptName +'" readonly="readonly"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" value="'+ deptId +'"/>' +
				'</td>' +
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

		if(!defaults.isCompanyReadonly == true){
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
			hiddenId : 'costBelongDeptId'
		});
	}

	//初始化合同项目 TODO
	function initContractProject(){
		var thisCompany = defaults.company;
		var thisCompanyId = defaults.companyId;
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">项目编号</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[projectCode]" readonly="readonly"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">项目名称</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" />' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" id="projectType"/>' +
					'<input type="hidden" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" />' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" />' +
					'<input type="hidden" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">项目经理</span></td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" />' +
				'</td>' +
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

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

		//工程项目渲染
		$("#projectCode").yxcombogrid_projectall({
			isDown : true,
			hiddenId : 'projectId',
			nameCol : 'projectCode',
			height : 250,
			isFocusoutCheck : false,
			gridOptions : {
				isTitle : true,
				showcheckbox : false,
				param : {'contractType' : 'GCXMYD-01'},
				event : {
					'row_dblclick' : function(e,row,data) {
						$("#projectId").val(data.projectId);
						$("#projectName").val(data.projectName);
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
					$("#projectName").val('');
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

	//初始化研发项目 TODO
	function initRdProject(){
		var thisCompany = defaults.company;
		var thisCompanyId = defaults.companyId;
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">项目编号</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[projectCode]" readonly="readonly"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">项目名称</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" />' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" id="projectType"/>' +
					'<input type="hidden" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" />' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" />' +
					'<input type="hidden" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">项目经理</span></td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" />' +
				'</td>' +
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

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

		//研发项目渲染
		$("#projectCode").yxcombogrid_rdprojectfordl({
			isDown : true,
			hiddenId : 'projectId',
			nameCol : 'projectCode',
			isShowButton : false,
			height : 250,
			isFocusoutCheck : false,
			gridOptions : {
				isTitle : true,
				showcheckbox : false,
				param : { 'is_delete' : 0 , 'project_typeNo' : '4'},
				event : {
					'row_dblclick' : function(e,row,data) {
						$("#projectName").val(data.projectName);
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
					$("#projectName").val('');
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

	//初始化售前 TODO 售前
	function initSale(){
		var thisCompany = defaults.company;
		var thisCompanyId = defaults.companyId;
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">试用项目编号</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[projectCode]" readonly="readonly"/>' +
				'</td>' +
				'<td class = "form_text_left_three">试用项目名称</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" />' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" />' +
					'<input type="hidden" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">项目经理</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" />' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">商机编号</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="chanceCode" name="'+defaults.objName+'[chanceCode]"/>' +
					'<input type="hidden" id="chanceId" name="'+defaults.objName+'[chanceId]" />' +
				'</td>' +
				'<td class = "form_text_left_three">商机名称</td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="txt" id="chanceName" name="'+defaults.objName+'[chanceName]"/>' +
				'</td>' +
				'<td class = "form_text_left_three">客户名称</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="customerName" name="'+defaults.objName+'[customerName]"/>' +
					'<input type="hidden" id="customerId" name="'+defaults.objName+'[customerId]" />' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">客户省份</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="province" name="'+defaults.objName+'[province]" style="width:202px;"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">客户城市</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="city" name="'+defaults.objName+'[city]" style="width:202px;"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">客户类型</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="customerType" name="'+defaults.objName+'[customerType]" style="width:202px;"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">销售负责人</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="costBelonger" name="'+defaults.objName+'[costBelonger]" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongerId" name="'+defaults.objName+'[costBelongerId]" />' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" />' +
				'</td>'
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

		//商机编号
		var codeObj = $("#chanceCode");
		if(codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "输入完整的商机编号，系统自动匹配相关信息";
		var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机编号'>&nbsp;</span>");
		$button.click(function(){
			if(codeObj.val() == ""){
				alert('请输入一个商机编号');
				return false;
			}
		});

		//添加清空按钮
		var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
		$button2.click(function(){
			if(codeObj.val() != ""){
				//清除销售信息
				clearSale();
				openInput('chance');
			}
		});
		codeObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');

		//商机名称
		var nameObj = $("#chanceName");
		if(nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "输入完整的商机名称，系统自动匹配相关信息";
		var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机名称'>&nbsp;</span>");
		$button.click(function(){
			if(nameObj.val() == ""){
				alert('请输入一个商机名称');
				return false;
			}
		});

		//添加清空按钮
		var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
		$button2.click(function(){
			if(nameObj.val() != ""){
				//清除销售信息
				clearSale();
				openInput('chance');
			}
		});
		nameObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');

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
					$("#projectType").val('');
					clearSale();

					//开启其他入口
					openInput('trialPlan');
				}
			}
		}).attr('class','txt');

		//项目编号
		$("#projectCode").yxcombogrid_esmproject({
			isDown : true,
			hiddenId : 'projectId',
			nameCol : 'projectCode',
			searchName : 'projectCodeSearch',
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

						$("#projectName").val(data.projectName);
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
								$("#provinceHidden").val(trialProjectInfo.province);

								var customerTypeObj = $("#CustomerType");
								customerTypeObj.combobox('setValue',trialProjectInfo.customerTypeName);
								mulSelectSet(customerTypeObj);

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
								mulSelectSet(cityObj);
							}
						}
					}
				}
			},
			event : {
				'clear' : function() {
					$("#projectName").val('');
					$("#proManagerName").val('');
					$("#proManagerId").val('');
					$("#projectType").val('');
					clearSale();

					//开启其他入口
					openInput('trialPlan');
				}
			}
		}).attr('class','txt');

		//初始化客户
		initCustomer();

		//客户类型
		var customerTypeArr = '';
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
	        	if(customerTypeArr.indexOf(obj.text) == -1){
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
		var cityObj = $('#city');
		provinceObj.combobox({
			url:'index1.php?model=system_procity_province&action=listJsonSort',
			valueField:'provinceName',
	        textField:'provinceName',
			editable : false,
			onSelect : function(obj){
				//设置对象下的选中项
				$("#provinceHidden").val(obj.provinceName);
				//根据省份读取城市
				cityObj.combobox({
					url : "?model=system_procity_city&action=listJson&tProvinceName=" + obj.provinceName
				});
			}
		});

		//城市渲染
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
		var dataArr = expenseSaleDept;
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

	//初始化售后 TODO 售后
	function initContract(){
		var thisCompany = defaults.company;
		var thisCompanyId = defaults.companyId;
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">合同编号</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt ciClass" id="contractCode" name="'+defaults.objName+'[contractCode]"/>' +
					'<input type="hidden" class="ciClass" id="contractId" name="'+defaults.objName+'[contractId]" />' +
					'<input type="hidden" class="ciClass" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
					'<input type="hidden" class="ciClass" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">合同编号</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt ciClass" id="contractName" name="'+defaults.objName+'[contractName]"/>' +
				'</td>' +
				'<td class = "form_text_left_three">客户名称</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal ciClass" id="customerName" name="'+defaults.objName+'[customerName]" readonly="readonly"/>' +
					'<input type="hidden" class="ciClass" id="customerId" name="'+defaults.objName+'[customerId]" />' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">客户省份</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal ciClass" id="province" name="'+defaults.objName+'[province]" readonly="readonly"/>' +
				'</td>' +
				'<td class = "form_text_left_three">客户城市</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal ciClass" id="city" name="'+defaults.objName+'[city]" readonly="readonly"/>' +
				'</td>' +
				'<td class = "form_text_left_three">客户类型</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal ciClass" id="customerType" name="'+defaults.objName+'[customerType]" readonly="readonly"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">销售负责人</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="'+defaults.objName+'[costBelonger]" readonly="readonly"/>' +
					'<input type="hidden" class="ciClass" id="costBelongerId" name="'+defaults.objName+'[costBelongerId]" />' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" />' +
				'</td>'
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

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

		//编号搜索渲染
		var codeObj = $("#contractCode");
		if(codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "输入完整的合同编号，系统自动匹配相关信息";
		var $button = $("<span class='search-trigger' id='contractCodeSearch' title='合同编号'>&nbsp;</span>");
		$button.click(function(){
			if($("#" + thisId).val() == ""){
				alert('请输入一个合同编号');
				return false;
			}
		});

		//添加清空按钮
		var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
		$button2.click(function(){
			$(".ciClass").val('');
		});
		codeObj.bind('blur',getContractInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true);

		//名称搜索渲染
		var nameObj = $("#contractName");
		if(nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "输入完整的合同名称，系统自动匹配相关信息";
		var $button = $("<span class='search-trigger' id='contractCodeSearch' title='合同名称'>&nbsp;</span>");
		$button.click(function(){
			if($("#" + thisId).val() == ""){
				alert('请输入一个合同名称');
				return false;
			}
		});

		//添加清空按钮
		var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
		$button2.click(function(){
			$(".ciClass").val('');
		});
		nameObj.bind('blur',getContractInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true);
	}

	//异步匹配合同信息
	function getContractInfo(){
		var contractCode = $("#contractCode").val();
		var contractName = $("#contractName").val();
		if(contractCode == "" && contractName == ""){
			return false;
		}
		$.ajax({
		    type: "POST",
		    url: "?model=contract_contract_contract&action=ajaxGetContract",
		    data: {"contractCode" : contractCode , "contractName" : contractName},
		    async: false,
		    success: function(data){
		   		if(data){
					var dataArr = eval("(" + data + ")");
					if(dataArr.thisLength*1 > 1){
						alert('系统中存在【' + dataArr.thisLength + '】条名称为【' + contractName + '】的合同，请通过合同编号匹配合同信息！');
						$(".ciClass").val('');
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
						$("#costBelongerId").val(dataArr.prinvipalId);
					}
		   	    }else{
					alert('没有查询到相关合同信息');
					$(".ciClass").val('');
		   	    }
			}
		});
	}

	//初始化客户
	function initCustomer(){
		//先移除
		$("#customerName").yxcombogrid_customer('remove').yxcombogrid_customer({
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
						var customerTypeObj = $("#customerType");
						var valArr = [];
						valArr.push(customerTypeName);
						customerTypeObj.combobox('setValues',valArr);

						//重载客户城市
						var cityObj = $("#city");
						cityObj.combobox({
							url : "?model=system_procity_city&action=listJson&tProvinceName=" + data.Prov
						}).combobox('setValue',data.City);

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
						if(typeof(thisType) == 'object'){
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
			$("#customerName").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_customer('remove');
		}
	}

	//启用其他入口
	function openInput(thisType){
		if(thisType == 'trialPlan'){
			//重新实例化客户选择
			initCustomer();

			//商机编号
			var codeObj = $("#chanceCode");
			if(codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true){
				return false;
			}
			var title = "输入完整的商机编号，系统自动匹配相关信息";
			var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机编号'>&nbsp;</span>");
			$button.click(function(){
				if(codeObj.val() == ""){
					alert('请输入一个商机编号');
					return false;
				}
			});

			//添加清空按钮
			var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
			$button2.click(function(){
				if(codeObj.val() != ""){
					//清除销售信息
					clearSale();
					openInput('chance');
				}
			});
			codeObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');

			//商机名称
			var nameObj = $("#chanceName");
			if(nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true){
				return false;
			}
			var title = "输入完整的商机名称，系统自动匹配相关信息";
			var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机名称'>&nbsp;</span>");
			$button.click(function(){
				if(nameObj.val() == ""){
					alert('请输入一个商机名称');
					return false;
				}
			});

			//添加清空按钮
			var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
			$button2.click(function(){
				if(nameObj.val() != ""){
					//清除销售信息
					clearSale();
					openInput('chance');
				}
			});
			nameObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');
		}else if(thisType == 'customer'){
			//项目
			initTrialproject();

			$("#customerName").attr("class",'txt').attr('readonly',false);

			//商机编号
			var codeObj = $("#chanceCode");
			if(codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true){
				return false;
			}
			var title = "输入完整的商机编号，系统自动匹配相关信息";
			var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机编号'>&nbsp;</span>");
			$button.click(function(){
				if(codeObj.val() == ""){
					alert('请输入一个商机编号');
					return false;
				}
			});

			//添加清空按钮
			var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
			$button2.click(function(){
				if(codeObj.val() != ""){
					//清除销售信息
					clearSale();
					openInput('chance');
				}
			});
			codeObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');

			//商机名称
			var nameObj = $("#chanceName");
			if(nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true){
				return false;
			}
			var title = "输入完整的商机名称，系统自动匹配相关信息";
			var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机名称'>&nbsp;</span>");
			$button.click(function(){
				if(nameObj.val() == ""){
					alert('请输入一个商机名称');
					return false;
				}
			});

			//添加清空按钮
			var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
			$button2.click(function(){
				if(nameObj.val() != ""){
					//清除销售信息
					clearSale();
					openInput('chance');
				}
			});
			nameObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');
		}else if((typeof(thisType) == "object" && thisType.data== 'chance') || thisType== 'chance'){
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

	//试用项目渲染 -- 试用项目
	function initTrialproject(){
		$("#projectCode").yxcombogrid_esmproject({
			isDown : true,
			hiddenId : 'projectId',
			nameCol : 'projectCode',
			searchName : 'projectCodeSearch',
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

						$("#projectName").val(data.projectName);
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
								$("#provinceHidden").val(trialProjectInfo.province);

								var customerTypeObj = $("#customerType");
								customerTypeObj.combobox('setValue',trialProjectInfo.customerTypeName);
								mulSelectSet(customerTypeObj);

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
								mulSelectSet(cityObj);
							}
						}
					}
				}
			},
			event : {
				'clear' : function() {
					$("#projectName").val('');
					$("#proManagerName").val('');
					$("#proManagerId").val('');
					$("#projectType").val('');
					clearSale();

					//开启其他入口
					openInput('trialPlan');
				}
			}
		}).attr('class','txt');

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
								$("#provinceHidden").val(trialProjectInfo.province);

								var customerTypeObj = $("#customerType");
								customerTypeObj.combobox('setValue',trialProjectInfo.customerTypeName);
								mulSelectSet(customerTypeObj);

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
								mulSelectSet(cityObj);
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
		}).attr('class','txt');
	}

	//隐藏区域设置
	function mulSelectSet(thisObj){
		thisObj.next().find("input").each(function(i,n){
			if($(this).attr('class') == 'combo-text validatebox-text'){
				$("#"+ thisObj.attr('id') + "Hidden").val(this.value);
			}
		});
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
		cityObj.combobox('setValue',dataArr.City);

		//客户类型
		var customerTypeObj = $("#customerType");
		var valArr = [];
		valArr.push(dataArr.customerTypeName);
		customerTypeObj.combobox('setValues',valArr);

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

	//重新载入城市
	function reloadCity(data) {
		var str;

		//城市渲染
		var cityObj = $('#city');
		cityObj.combobox({
			url : "?model=system_procity_city&action=listJson&tProvinceName=" + data
		});
	}


	//*********************** TODO 查看部分 *********************/
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
		var tableStr = '<table class="form_in_table" id="'+defaults.myId+'tbl">' +
				'<tr id="feeTypeTr">' +
					'<td class = "form_text_left_three"><span id="detailTypeTitle">费用类型</span></td>' +
					'<td class = "form_text_right" colspan="5">' +
						'部门费用 ' +
					'</td>' +
				'</tr>' +
				'<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">费用归属公司</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.costBelongCom +
					'</td>' +
					'<td class = "form_text_left_three">费用归属部门</td>' +
					'<td class = "form_text_right" colspan="3">' +
						objInfo.costBelongDeptName +
					'</td>' +
				'</tr>'
			'</table>';
		$("#"+defaults.myId).html(tableStr);
	}

	//初始化合同项目
	function initProjectView(objInfo){
		var tableStr = '<table class="form_in_table" id="'+defaults.myId+'tbl">' +
				'<tr id="feeTypeTr">' +
					'<td class = "form_text_left_three"><span id="detailTypeTitle">费用类型</span></td>' +
					'<td class = "form_text_right" colspan="5">' +
						'合同项目费用 ' +
					'</td>' +
				'</tr>' +
				'<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">项目编号</span></td>' +
					'<td class = "form_text_right_three">' +
						objInfo.projectCode +
					'</td>' +
					'<td class = "form_text_left_three">项目名称</span></td>' +
					'<td class = "form_text_right_three">' +
						objInfo.projectName +
					'</td>' +
					'<td class = "form_text_left_three">项目经理</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.proManagerName +
					'</td>' +
				'</tr>'
			'</table>';
		$("#"+defaults.myId).html(tableStr);
	}

	//初始化售前
	function initSaleView(objInfo){
		var tableStr = "";
		tableStr = '<table class="form_in_table" id="'+defaults.myId+'tbl">' +
				'<tr id="feeTypeTr">' +
					'<td class = "form_text_left_three"><span id="detailTypeTitle">费用类型</span></td>' +
					'<td class = "form_text_right_three" colspan="5">' +
						'售前费用 ' +
					'</td>' +
				'</tr>' +
				'<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">试用项目编号</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.projectCode +
					'</td>' +
					'<td class = "form_text_left_three">试用项目名称</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.projectName +
					'</td>' +
					'<td class = "form_text_left_three">项目经理</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.proManagerName +
					'</td>' +
				'</tr>' +
				'<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">商机编号</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.chanceCode +
					'</td>' +
					'<td class = "form_text_left_three">商机名称</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.chanceName +
					'</td>' +
					'<td class = "form_text_left_three">客户名称</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.customerName +
					'</td>' +
				'</tr>' +
				'<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">客户省份</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.province +
					'</td>' +
					'<td class = "form_text_left_three">客户城市</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.city +
					'</td>' +
					'<td class = "form_text_left_three">客户类型</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.customerType +
					'</td>' +
				'</tr>' +
				'<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">销售负责人</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.costBelonger +
					'</td>' +
					'<td class = "form_text_left_three">费用归属部门</td>' +
					'<td class = "form_text_right" colspan="3">' +
						objInfo.costBelongDeptName +
					'</td>'
				'</tr>'
			'</table>';
		$("#"+defaults.myId).html(tableStr);
	}

	//初始化售后
	function initContractView(objInfo){
		var tableStr = "";
		tableStr = '<table class="form_in_table" id="'+defaults.myId+'tbl">' +
				'<tr id="feeTypeTr">' +
					'<td class = "form_text_left_three"><span id="detailTypeTitle">费用类型</span></td>' +
					'<td class = "form_text_right" colspan="5">' +
						'售后费用 ' +
					'</td>' +
				'</tr>' +
			'</table>';
		$("#"+defaults.myId).html(tableStr);

		tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">合同编号</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.contractCode +
				'</td>' +
				'<td class = "form_text_left_three">合同名称</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.contractCode +
				'</td>' +
				'<td class = "form_text_left_three">客户名称</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.customerName +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">客户省份</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.province +
				'</td>' +
				'<td class = "form_text_left_three">客户城市</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.city +
				'</td>' +
				'<td class = "form_text_left_three">客户类型</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.customerType +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">销售负责人</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.costBelonger +
				'</td>' +
				'<td class = "form_text_left_three">费用归属部门</td>' +
				'<td class = "form_text_right" colspan="3">' +
					objInfo.costBelongDeptName +
				'</td>'
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);
	}

	//********************* TODO 编辑部分 ************************/
	//初始化费用类型
	function initCostTypeEdit(thisObj,objInfo){
		initCostType(thisObj);
		//附选中值
		$("input[name='"+defaults.objName+"[detailType]']").each(function(i,n){
			if(this.value == objInfo.detailType){
				$(this).attr("checked",this);
				return false;
			}
		});
		$("#detailTypeTitle").html('费用类型').removeClass('red').addClass('blue');
		switch(objInfo.detailType){
			case '1' : initDeptEdit(objInfo);break;
			case '2' : initContractProjectEdit(objInfo);break;
			case '3' : initRdProjectEdit(objInfo);break;
			case '4' : initSaleEdit(objInfo);break;
			case '5' : initContractEdit(objInfo);break;
			default : break;
		}
	}

	//TODO 初始化部门
	function initDeptEdit(objInfo){
		//初始值赋予
		var costBelongCom='',costBelongComId='',costBelongDeptName='',costBelongDeptId='',id='';
		if(objInfo){
			costBelongCom = objInfo.costBelongCom;
			costBelongComId = objInfo.costBelongComId;
			costBelongDeptName = objInfo.costBelongDeptName;
			costBelongDeptId = objInfo.costBelongDeptId;
			id = objInfo.id;
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">费用归属公司</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+costBelongCom +'" readonly="readonly"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+costBelongComId +'"/>' +
					'<input type="hidden" name="'+defaults.objName+'[id]" value="'+id +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" value="'+costBelongDeptName +'" readonly="readonly"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
				'</td>' +
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);
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

	// TODO 初始化合同项目
	function initContractProjectEdit(objInfo){
		//初始值赋予
		var projectName='',projectCode='',projectId='',costBelongDeptName='',costBelongDeptId='',proManagerName='',proManagerId='',id='';
		if(objInfo){
			projectName = objInfo.projectName;
			projectCode = objInfo.projectCode;
			projectId = objInfo.projectId;
			projectType = objInfo.projectType;
			costBelongDeptName = objInfo.costBelongDeptName;
			costBelongDeptId = objInfo.costBelongDeptId;
			proManagerName = objInfo.proManagerName;
			proManagerId = objInfo.proManagerId;
			id = objInfo.id;
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">项目编号</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[projectCode]" readonly="readonly" value="'+projectCode +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">项目名称</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly" value="'+projectName +'"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" value="'+projectId +'"/>' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" value="'+projectType +'"/>' +
					'<input type="hidden" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" value="'+costBelongDeptName +'"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
					'<input type="hidden" name="'+defaults.objName+'[id]" value="'+id +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">项目经理</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" value="'+proManagerId +'"/>' +
				'</td>' +
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

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


		//工程项目渲染
		$("#projectCode").yxcombogrid_projectall({
			isDown : true,
			hiddenId : 'projectId',
			nameCol : 'projectCode',
			height : 250,
			isFocusoutCheck : false,
			gridOptions : {
				isTitle : true,
				showcheckbox : false,
				param : {'contractType' : 'GCXMYD-01'},
				event : {
					'row_dblclick' : function(e,row,data) {
						$("#projectId").val(data.projectId);
						$("#projectName").val(data.projectName);
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
					$("#projectName").val('');
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

	//TODO 初始化研发项目
	function initRdProjectEdit(objInfo){
		//初始值赋予
		var projectName='',projectCode='',projectId='',costBelongDeptName='',costBelongDeptId='',proManagerName='',proManagerId='',id='';
		if(objInfo){
			projectName = objInfo.projectName;
			projectCode = objInfo.projectCode;
			projectId = objInfo.projectId;
			projectType = objInfo.projectType;
			costBelongDeptName = objInfo.costBelongDeptName;
			costBelongDeptId = objInfo.costBelongDeptId;
			proManagerName = objInfo.proManagerName;
			proManagerId = objInfo.proManagerId;
			id = objInfo.id;
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">项目编号</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[projectCode]" readonly="readonly" value="'+projectCode +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">项目名称</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly" value="'+projectName +'"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" value="'+projectId +'"/>' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" value="'+projectType +'"/>' +
					'<input type="hidden" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" value="'+costBelongDeptName +'"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
					'<input type="hidden" name="'+defaults.objName+'[id]" value="'+id +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">项目经理</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" value="'+proManagerId +'"/>' +
				'</td>' +
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

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

		//研发项目渲染
		$("#projectCode").yxcombogrid_rdprojectfordl({
			isDown : true,
			hiddenId : 'projectId',
			nameCol : 'projectCode',
			isShowButton : false,
			height : 250,
			isFocusoutCheck : false,
			gridOptions : {
				isTitle : true,
				showcheckbox : false,
				param : { 'is_delete' : 0 , 'project_typeNo' : '4'},
				event : {
					'row_dblclick' : function(e,row,data) {
						$("#projectName").val(data.projectName);
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
					$("#projectName").val('');
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

	//TODO 初始化售前费用
	function initSaleEdit(objInfo){
		//初始值赋予
		var projectName='',projectCode='',projectId='',projectType='',costBelongDeptName='',costBelongDeptId='';
		var proManagerName='',proManagerId='',chanceCode='',chanceName='',id='';
		var chanceId='',customerName='',customerId='',province='',city='',customerType='',costBelonger='',costBelongerId='';
		if(objInfo){
			projectName = objInfo.projectName;
			projectCode = objInfo.projectCode;
			projectId = objInfo.projectId;
			projectType = objInfo.projectType;
			costBelongDeptName = objInfo.costBelongDeptName;
			costBelongDeptId = objInfo.costBelongDeptId;
			costBelongComId = objInfo.costBelongComId;
			costBelongCom = objInfo.costBelongCom;
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
			id = objInfo.id;
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">试用项目编号</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[projectCode]" readonly="readonly" value="'+projectCode+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">试用项目名称</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly" value="'+projectName+'"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" value="'+projectId+'"/>' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" value="'+projectType+'"/>' +
					'<input type="hidden" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+ costBelongCom +'"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+ costBelongComId +'"/>' +
					'<input type="hidden" name="'+defaults.objName+'[id]" value="'+id +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">项目经理</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" value="'+ proManagerName +'" readonly="readonly"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" value="'+ proManagerId +'"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">商机编号</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="chanceCode" name="'+defaults.objName+'[chanceCode]" value="'+ chanceCode +'"/>' +
					'<input type="hidden" id="chanceId" name="'+defaults.objName+'[chanceId]" value="'+ chanceId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">商机名称</td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="txt" id="chanceName" name="'+defaults.objName+'[chanceName]" value="'+ chanceName +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">客户名称</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="customerName" name="'+defaults.objName+'[customerName]" value="'+ customerName +'"/>' +
					'<input type="hidden" id="customerId" name="'+defaults.objName+'[customerId]" value="'+ customerId +'"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">客户省份</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="province" name="'+defaults.objName+'[province]" value="'+ province +'" style="width:202px;"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">客户城市</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="city" name="'+defaults.objName+'[city]" value="'+ city +'" style="width:202px;"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">客户类型</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="customerType" name="'+defaults.objName+'[customerType]" value="'+ customerType +'" style="width:202px;"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">销售负责人</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="costBelonger" name="'+defaults.objName+'[costBelonger]" value="'+ costBelonger +'" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongerId" name="'+defaults.objName+'[costBelongerId]" value="'+ costBelongerId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" value="'+ costBelongDeptName +'" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" value="'+ costBelongDeptId +'"/>' +
				'</td>'
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

		//商机编号
		var codeObj = $("#chanceCode");
		if(codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "输入完整的商机编号，系统自动匹配相关信息";
		var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机编号'>&nbsp;</span>");
		$button.click(function(){
			if(codeObj.val() == ""){
				alert('请输入一个商机编号');
				return false;
			}
		});

		//添加清空按钮
		var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
		$button2.click(function(){
			if(codeObj.val() != ""){
				//清除销售信息
				clearSale();
				openInput('chance');
			}
		});
		codeObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');

		//商机名称
		var nameObj = $("#chanceName");
		if(nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "输入完整的商机名称，系统自动匹配相关信息";
		var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机名称'>&nbsp;</span>");
		$button.click(function(){
			if(nameObj.val() == ""){
				alert('请输入一个商机名称');
				return false;
			}
		});

		//添加清空按钮
		var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
		$button2.click(function(){
			if(nameObj.val() != ""){
				//清除销售信息
				clearSale();
				openInput('chance');
			}
		});
		nameObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');

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
					$("#projectType").val('');
					clearSale();

					//开启其他入口
					openInput('trialPlan');
				}
			}
		}).attr('class','txt');

		//项目编号
		$("#projectCode").yxcombogrid_esmproject({
			isDown : true,
			hiddenId : 'projectId',
			nameCol : 'projectCode',
			searchName : 'projectCodeSearch',
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

						$("#projectName").val(data.projectName);
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
								$("#provinceHidden").val(trialProjectInfo.province);

								var customerTypeObj = $("#CustomerType");
								customerTypeObj.combobox('setValue',trialProjectInfo.customerTypeName);
								mulSelectSet(customerTypeObj);

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
								mulSelectSet(cityObj);
							}
						}
					}
				}
			},
			event : {
				'clear' : function() {
					$("#projectName").val('');
					$("#proManagerName").val('');
					$("#proManagerId").val('');
					$("#projectType").val('');
					clearSale();

					//开启其他入口
					openInput('trialPlan');
				}
			}
		}).attr('class','txt');

		//初始化客户
		initCustomer();

		//客户类型
		var customerTypeArr = '';
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
	        	if(customerTypeArr.indexOf(obj.text) == -1){
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
		var cityObj = $('#city');
		provinceObj.combobox({
			url:'index1.php?model=system_procity_province&action=listJsonSort',
			valueField:'provinceName',
	        textField:'provinceName',
			editable : false,
			onSelect : function(obj){
				//设置对象下的选中项
				$("#provinceHidden").val(obj.provinceName);
				//根据省份读取城市
				cityObj.combobox({
					url : "?model=system_procity_city&action=listJson&tProvinceName=" + obj.provinceName
				});
			}
		});

		//城市渲染
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
		var dataArr = expenseSaleDept;
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

		//调用一次禁用窗口
		closeInput();
		//调用一次设置销售负责人
		changeCustomerType();
	}

	//TODO 初始化售后费用
	function initContractEdit(objInfo){
		//初始值赋予
		var costBelongDeptName='',costBelongDeptId='',proManagerName='',proManagerId='',contractCode='',contractName='',id='';
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
			id = objInfo.id;
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">合同编号</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="contractCode" name="'+defaults.objName+'[contractCode]" value="'+contractCode+'"/>' +
					'<input type="hidden" id="contractId" name="'+defaults.objName+'[contractId]" value="'+contractId+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">合同名称</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="contractName" name="'+defaults.objName+'[contractName]" value="'+contractName+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">客户名称</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="customerName" name="'+defaults.objName+'[customerName]" readonly="readonly" value="'+customerName+'"/>' +
					'<input type="hidden" id="customerId" name="'+defaults.objName+'[customerId]" value="'+customerId+'"/>' +
					'<input type="hidden" name="'+defaults.objName+'[id]" value="'+id +'"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">客户省份</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="province" name="'+defaults.objName+'[province]" readonly="readonly" value="'+province+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">客户城市</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="city" name="'+defaults.objName+'[city]" readonly="readonly" value="'+city+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">客户类型</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="customerType" name="'+defaults.objName+'[customerType]" readonly="readonly" value="'+customerType+'"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">销售负责人</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="costBelonger" name="'+defaults.objName+'[costBelonger]" readonly="readonly" value="'+costBelonger+'"/>' +
					'<input type="hidden" id="costBelongerId" name="'+defaults.objName+'[costBelongerId]" value="'+costBelongerId+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" style="width:202px;" value="'+costBelongDeptName+'"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" value="'+costBelongDeptId+'"/>' +
				'</td>'
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

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

		//编号搜索渲染
		var codeObj = $("#contractCode");
		if(codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "输入完整的合同编号，系统自动匹配相关信息";
		var $button = $("<span class='search-trigger' id='contractCodeSearch' title='合同编号'>&nbsp;</span>");
		$button.click(function(){
			if($("#" + thisId).val() == ""){
				alert('请输入一个合同编号');
				return false;
			}
		});

		//添加清空按钮
		var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
		$button2.click(function(){
			$(".ciClass").val('');
		});
		codeObj.bind('blur',getContractInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true);

		//名称搜索渲染
		var nameObj = $("#contractName");
		if(nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "输入完整的合同名称，系统自动匹配相关信息";
		var $button = $("<span class='search-trigger' id='contractCodeSearch' title='合同名称'>&nbsp;</span>");
		$button.click(function(){
			if($("#" + thisId).val() == ""){
				alert('请输入一个合同名称');
				return false;
			}
		});

		//添加清空按钮
		var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
		$button2.click(function(){
			$(".ciClass").val('');
		});
		nameObj.bind('blur',getContractInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true);
	}
	//************************* 表单验证 ****************************/
	//表单验证方法
	function costCheckForm(){
		var detailType = $("input[name='" +defaults.objName+ "[detailType]']:checked").val();
		if(detailType){
			//类型 对应特殊验证
			switch(detailType){
				case '1' :
					var costBelongCom = $("#costBelongCom").val();
					if(costBelongCom == ""){
						alert("没有填写费用归属公司");
						return false;
					}
					var costBelongDeptName = $("#costBelongDeptName").val();
					if(costBelongDeptName == ""){
						alert("没有填写费用归属部门");
						return false;
					}
					break;
				case '2' :
					var projectCode = $("#projectCode").val();
					if(projectCode == ""){
						alert("请选择该笔费用所在工程项目");
						return false;
					}
					break;
				case '3' :
					var projectCode = $("#projectCode").val();
					if(projectCode == ""){
						alert("请选择该笔费用所在研发项目");
						return false;
					}
					break;
				case '4' :
					var province = $("#province").combobox('getValue');
					if(province == ""){
						alert("请选择客户所在省份");
						return false;
					}
					var city = $("#city").combobox('getValues');
					if(city == ""){
						alert("请选择客户所在城市");
						return false;
					}
					var customerType = $("#customerType").combobox('getValues');
					if(customerType == ""){
						alert("请选择客户类型");
						return false;
					}
					var costBelongerId = $("#costBelongerId").val();
					if(costBelongerId == ""){
						alert("请录入销售负责人，销售负责人可由商机、客户名称自动带出，或者通过客户省份、城市、类型由系统匹配");
						return false;
					}
					var costBelongDeptId = $("#costBelongDeptId").val();
					var costBelongDeptName = $("#costBelongDeptName").combobox('getValue');
					if(costBelongDeptId == "" || costBelongDeptName ==""){
						alert("请选择费用归属部门");
						return false;
					}
					break;
				case '5' :
					var contractCode = $("#contractCode").val();
					if(contractCode == ""){
						alert("请选择该笔费用归属合同");
						return false;
					}
					var costBelongDeptId = $("#costBelongDeptId").val();
					var costBelongDeptName = $("#costBelongDeptName").combobox('getValue');
					if(costBelongDeptId == "" || costBelongDeptName ==""){
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

	$.fn.costbelong = function(options){
		//合并属性
		var options = $.extend(defaults,options);
		//支持选择器以及链式操作
		return this.each(function(){
			//赋值一个表明
			defaults.myId = this.id;
			var thisObj = $(this);//自己的对象

			//如果不是新增,那么获取一个方法
			if(defaults.actionType != 'add'){
				//ajax获取销售负责人
				var responseText = $.ajax({
					url:defaults.url,
					data : defaults.data,
					type : "POST",
					async : false
				}).responseText;
				var objInfo = eval("(" + responseText + ")");
			}
			if(defaults.actionType == 'view'){
				//初始化费用内容
				initCostTypeView(objInfo);
			}else{
				if(defaults.actionType == 'add'){
					initCostType(thisObj);
				}else if(defaults.actionType == 'edit'){
					initCostTypeEdit(thisObj,objInfo);
				}

				//绑定表单验证方法
				if(defaults.isRequired == true)
					$("form").bind('submit',costCheckForm);
			}
		});
	};
})(jQuery);
//��ʼ��һЩ�ֶ�
var objName = 'message';
var initId = 'feeTbl_c';
var myUrl = '?model=flights_require_require&action=ajaxGet';

//���ù�����������
var expenseSaleDept;
var expenseContractDept;
var expenseTrialProjectFeeDept;

//��ʼ����������
function initCostType(id){
	//ajax��ȡ���õ���
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
				'<td class = "form_text_left"><span id="detailTypeTitle" class="red">��ѡ���������</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="radio" name="'+objName+'[detailType]" value="1" onclick="changeDetailType(this.value)" /> ���ŷ��� ' +
					'<input type="radio" name="'+objName+'[detailType]" value="2" onclick="changeDetailType(this.value)" /> ��ͬ��Ŀ���� ' +
					'<input type="radio" name="'+objName+'[detailType]" value="3" onclick="changeDetailType(this.value)" /> �з����� ' +
					'<input type="radio" name="'+objName+'[detailType]" value="4" onclick="changeDetailType(this.value)" /> ��ǰ���� ' +
					'<input type="radio" name="'+objName+'[detailType]" value="5" onclick="changeDetailType(this.value)" /> �ۺ���� ' +
				'</td>' +
			'</tr>' +
		'</table>';
	$("#"+initId).html(tableStr);

	//��ʼ��
	changeDetailType(objInfo.detailType,objInfo);
}

//ѡ���������
function changeDetailType(detailType,objInfo){
	if(detailType){
		//��ѡ��ֵ
		$("input[name='"+objName+"[detailType]']").each(function(i,n){
			if(this.value == detailType){
				$(this).attr("checked",this);
				return false;
			}
		});
		$("#detailTypeTitle").html('��������').removeClass('red').addClass('blue');
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

//��ʼ������
function initDept(objInfo){
	//��ʼֵ����
	var costBelongCom='',costBelongComId='',costBelongDeptName='',costBelongDeptId='';
	if(objInfo){
		costBelongCom = objInfo.costBelongCom;
		costBelongComId = objInfo.costBelongComId;
		costBelongDeptName = objInfo.costBelongDeptName;
		costBelongDeptId = objInfo.costBelongDeptId;
	}
	var tableStr = '<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">���ù�����˾</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+costBelongCom +'" readonly="readonly"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+costBelongComId +'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">���ù�������</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" value="'+costBelongDeptName +'" readonly="readonly"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
			'</td>' +
		'</tr>';
	$("#"+objName + "tbl").append(tableStr);
	//��˾��Ⱦ
	$("#costBelongCom").yxcombogrid_branch({
		hiddenId : 'costBelongComId',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false
		}
	});
	//���ù�������ѡ��
	$("#costBelongDeptName").yxselect_dept({
		hiddenId : 'costBelongDeptId'
	});
}

//��ʼ����ͬ��Ŀ
function initContractProject(objInfo){
	//��ʼֵ����
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
			'<td class = "form_text_left"><span class="blue">��Ŀ����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly" value="'+projectName +'"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" value="'+projectName +'"/>' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" value="'+projectName +'"/>' +
				'<input type="hidden" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" value="'+costBelongDeptName +'"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">��Ŀ����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" value="'+proManagerId +'"/>' +
			'</td>' +
		'</tr>';
	$("#"+objName + "tbl").append(tableStr);

	//��ͬ��Ŀ��Ⱦ
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

					//���÷��ù�������
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

				//���÷��ù�������
				$("#costBelongDeptId").val('');
				$("#costBelongDeptName").val('');
			}
		}
	});
}

//��ʼ���з���Ŀ
function initRdProject(objInfo){
	//��ʼֵ����
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
			'<td class = "form_text_left"><span class="blue">��Ŀ����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly" value="'+projectName +'"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" value="'+projectName +'"/>' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" value="'+projectName +'"/>' +
				'<input type="hidden" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" value="'+costBelongDeptName +'"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">��Ŀ����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" value="'+proManagerId +'"/>' +
			'</td>' +
		'</tr>';
	$("#"+objName + "tbl").append(tableStr);

	//�з���Ŀ��Ⱦ
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

					//���÷��ù�������
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

				//���÷��ù�������
				$("#costBelongDeptId").val('');
				$("#costBelongDeptName").val('');
			}
		}
	});
}

//��ʼ����ǰ
function initSale(objInfo){
	//��ʼֵ����
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
			'<td class = "form_text_left">������Ŀ����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly" value="'+projectName+'"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" value="'+projectCode+'"/>' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" value="'+projectId+'"/>' +
			'</td>' +
			'<td class = "form_text_left">��Ŀ����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly" value="'+proManagerName+'"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" value="'+proManagerId+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">�̻����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="chanceCode" name="'+objName+'[chanceCode]" onblur="getChanceInfo();" value="'+chanceCode+'"/>' +
				'<input type="hidden" id="chanceName" name="'+objName+'[chanceName]" value="'+chanceName+'"/>' +
				'<input type="hidden" id="chanceId" name="'+objName+'[chanceId]" value="'+chanceId+'"/>' +
			'</td>' +
			'<td class = "form_text_left">�ͻ�����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="customerName" name="'+objName+'[customerName]" value="'+customerName+'"/>' +
				'<input type="hidden" id="customerId" name="'+objName+'[customerId]" value="'+customerId+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">�ͻ�ʡ��</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="province" name="'+objName+'[province]" style="width:202px;" value="'+province+'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">�ͻ�����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="city" name="'+objName+'[city]" style="width:202px;" value="'+city+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">�ͻ�����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="customerType" name="'+objName+'[customerType]" style="width:202px;" value="'+customerType+'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">���۸�����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="costBelonger" name="'+objName+'[costBelonger]" style="width:202px;" value="'+costBelonger+'"/>' +
				'<input type="hidden" id="costBelongerId" name="'+objName+'[costBelongerId]" value="'+costBelongerId+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">���ù�������</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
				'<input type="text" class="txt" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" style="width:202px;" value="'+costBelongDeptName+'"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+costBelongDeptId+'"/>' +
			'</td>'
		'</tr>';
	$("#"+objName + "tbl").append(tableStr);
	//��Ⱦ�̻���Ű�ť
	buildInputSet('chanceCode','�̻����','chance');

	//������Ŀ��Ⱦ
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
					//�����������
					closeInput('trialPlan',data.id);

					$("#projectCode").val(data.projectCode);
					$("#proManagerName").val(data.managerName);
					$("#proManagerId").val(data.managerId);

					//��ѯʹ����Ŀ��Ϣ
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

							//���۸�����
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

							//���ؿͻ�����
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

				//�����������
				openInput('trialPlan');
			}
		}
	}).attr('readonly',false).attr('class','txt');

	//��ʼ���ͻ�
	initCustomer();

	//�ͻ�����
	var CustomerTypeArr = '';
	var str;
	//�ͻ�������Ⱦ
	var customerObj = $('#customerType');
	customerObj.combobox({
		url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
		multiple:true,
		valueField:'text',
        textField:'text',
  		editable : false,
        formatter: function(obj){
        	//�ж� ���û�г�ʼ�������У���ѡ��
        	if(CustomerTypeArr.indexOf(obj.text) == -1){
        		str = "<input type='checkbox' id='customerType_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
        	}else{
        		str = "<input type='checkbox' id='customerType_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
        	}
			return str;
        },
		onSelect : function(obj){
			//checkbox��ֵ
			$("#customerType_" + obj.text).attr('checked',true);
			//�������۸�����
			changeCustomerType();
		},
		onUnselect : function(obj){
			//checkbox��ֵ
			$("#customerType_" + obj.text).attr('checked',false);
			//�������۸�����
			changeCustomerType();
		}
	});

	//ʡ����Ⱦ
	var provinceObj = $('#province');
	provinceObj.combobox({
		url:'index1.php?model=system_procity_province&action=listJsonSort',
		valueField:'provinceName',
        textField:'provinceName',
		editable : false,
		onSelect : function(obj){
			//���ö����µ�ѡ����
			$("#provinceHidden").val(obj.provinceName);
			//����ʡ�ݶ�ȡ����
			reloadCity(obj.provinceName);
		}
	});

	//������Ⱦ
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
			//checkbox��ֵ
			$("#city_" + obj.cityName).attr('checked',true);
			//�������۸�����
			changeCustomerType();
		},
		onUnselect : function(obj){
			//checkbox��ֵ
			$("#city_" + obj.cityName).attr('checked',false);
			//�������۸�����
			changeCustomerType();
		}
	});

	//���ù�������
	if(expenseSaleDept == undefined){
		//ajax��ȡ���۸�����
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

//��ʼ���ۺ�
function initContract(objInfo){
	//��ʼֵ����
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
			'<td class = "form_text_left"><span class="blue">��ͬ���</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="contractCode" name="'+objName+'[contractCode]" onblur="getContractInfo()" value="'+contractCode+'"/>' +
				'<input type="hidden" id="contractName" name="'+objName+'[contractName]" value="'+contractName+'"/>' +
				'<input type="hidden" id="contractId" name="'+objName+'[contractId]" value="'+contractId+'"/>' +
			'</td>' +
			'<td class = "form_text_left">�ͻ�����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="customerName" name="'+objName+'[customerName]" readonly="readonly" value="'+customerName+'"/>' +
				'<input type="hidden" id="customerId" name="'+objName+'[customerId]" value="'+customerId+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">�ͻ�ʡ��</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="province" name="'+objName+'[province]" readonly="readonly" value="'+province+'"/>' +
			'</td>' +
			'<td class = "form_text_left">�ͻ�����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="city" name="'+objName+'[city]" readonly="readonly" value="'+city+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">�ͻ�����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="customerType" name="'+objName+'[customerType]" readonly="readonly" value="'+customerType+'"/>' +
			'</td>' +
			'<td class = "form_text_left">���۸�����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="costBelonger" name="'+objName+'[costBelonger]" readonly="readonly" value="'+costBelonger+'"/>' +
				'<input type="hidden" id="costBelongerId" name="'+objName+'[costBelongerId]" value="'+costBelongerId+'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">���ù�������</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
				'<input type="text" class="txt" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" style="width:202px;" value="'+costBelongDeptName+'"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+costBelongDeptId+'"/>' +
			'</td>'
		'</tr>';;
	$("#"+objName + "tbl").append(tableStr);

	//���ù�������
	if(expenseContractDept == undefined){
		//ajax��ȡ���۸�����
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

	buildInputSet('contractCode','��ͬ���','contract');
}

//�첽ƥ���ͬ��Ϣ
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
					alert('ϵͳ�д��ڡ�' + dataArr.thisLength + '��������Ϊ��' + contractName + '���ĺ�ͬ����ͨ����ͬ���ƥ���ͬ��Ϣ��');
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
				alert('û�в�ѯ����غ�ͬ��Ϣ');
				clearContract();
	   	    }
		}
	});
}

//�����ͬ��Ϣ
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

	//���ʡ�пͻ�����
//	clearPCC();
}

//��տͻ�ʡ�ݡ����С��ͻ�����ϵ��
function clearPCC(){
	//���ʡ����Ϣ
	$("#province").combobox('setValue','');
	$("#provinceHidden").val("");

	//��տͻ�������Ϣ
	var customerTypeObj = $("#CustomerType");
	mulSelectClear(customerTypeObj);

	var cityObj = $("#city");
	mulSelectClear(cityObj);
}

//��ѡ��� - ������ն�ѡ������������
function mulSelectClear(thisObj){
	thisObj.combobox("setValues","");
	$("#"+ thisObj.attr('id') + "Hidden").val('');
	//��ո�ѡ��
	if(thisObj.attr("id") == 'city'){
		$("input:checkbox[id^='" + thisObj.attr("id") +"_']").attr("checked",false);
	}else{
		$("input:checkbox[id^='customerType_']").attr("checked",false);
	}
}

//����������Ⱦ
function buildInputSet(thisId,thisName,thisType){
	//��Ⱦһ��ƥ�䰴ť
	var thisObj = $("#" + thisId);
	if(thisObj.attr('wchangeTag2') == 'true' || thisObj.attr('wchangeTag2') == true){
		return false;
	}
	var title = "����������" + thisName +"��ϵͳ�Զ�ƥ�������Ϣ";
	var $button = $("<span class='search-trigger' id='" + thisId + "Search' title='"+ title +"'>&nbsp;</span>");
	$button.click(function(){
		if($("#" + thisId).val() == ""){
			alert('������һ��' + thisName);
			return false;
		}
	});

	//�����հ�ť
	var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
	$button2.click(function(){
		//�����Ⱦ�������̻���������ϼ����������
		if(thisType == 'chance'){
			if($("#" + thisId).val() != ""){
				//���������Ϣ
				clearSale();
				openInput(thisType);
			}
		}else if(thisType == 'contract'){
			clearContract();
		}
	});
	thisObj.after($button2).width(thisObj.width() - $button2.width()).after($button).width(thisObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');
}

// �����������
function closeInput(thisType,projectId){
	//��Ŀid��ȡ
	if(projectId == undefined){
		var projectId = $("#projectId").val();//��Ŀid
	}
	//���û���������ͣ��������ж�
	if(thisType == undefined){
		var chanceId = $("#chanceId").val();//�̻�id
		var customerId = $("#customerId").val();//�ͻ�id
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

		//����̻�����Ⱦ
		clearInputSet('chanceCode');
		clearInputSet('chanceName');
	}else if(thisType == 'customer'){
		//��Ŀ
		$("#projectCode").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');
		$("#projectName").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');

		//�̻�
		$("#chanceCode").attr("class",'readOnlyTxtNormal').attr('readonly',true);
		$("#chanceName").attr("class",'readOnlyTxtNormal').attr('readonly',true);

		//����̻�����Ⱦ
		clearInputSet('chanceCode');
		clearInputSet('chanceName');
	}else if(thisType == 'chance'){
		//��Ŀ
		$("#projectCode").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');
		$("#projectName").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');
		$("#customerName").attr("class",'readOnlyTxtNormal').yxcombogrid_customer('remove').attr('readonly',true);
	}

	//�������������ͣ�����Ⱦ��Ӧ����
	if(thisType != undefined){
		//����ʡ�ݵ�������
//		$("#province").combobox('disable');
//		$("#city").combobox('disable');
//		$("#CustomerType").combobox('disable');
	}
}

//�����������
function openInput(thisType){
	if(thisType == 'trialPlan'){
		//����ʵ�����ͻ�ѡ��
		initCustomer();

		//�̻�����Ⱦ
		buildInputSet('chanceCode','�̻����','chance');
	}else if(thisType == 'customer'){
		//��Ŀ
		initTrialproject();

		$("#customerName").attr("class",'txt').attr('readonly',false);

		//�̻�����Ⱦ
		buildInputSet('chanceCode','�̻����','chance');
	}else if(thisType == 'chance'){
		//��Ŀ
		initTrialproject();

		//����ʵ�����ͻ�ѡ��
		initCustomer();
	}

	//��ʾʡ�ݵ�������
	$("#province").combobox('enable');
	$('#city').combobox('enable');
	$("#CustomerType").combobox('enable');
	$("#CostBelonger").combobox('enable');
}



//���������Ⱦ
function clearInputSet(thisId){
	//��Ⱦһ��ƥ�䰴ť
	var thisObj = $("#" + thisId);
	//ȥ����һ����ť
	var $button = thisObj.next();
	thisObj.width(thisObj.width() + $button.width()).attr("wchangeTag2", false);
	$button.remove();

	//ȥ���ڶ�����ť
	$button = thisObj.next();
	thisObj.width(thisObj.width() + $button.width()).attr("wchangeTag2", false);
	$button.remove();
}

//ajax��ȡ������Ŀ������Ϣ
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
//ѡ��ͻ�����
function changeCustomerType(thisType){
	var chanceId = $("#chanceId").val();
	var customerId = $("#customerId").val();
	if((chanceId == "" || chanceId == '0') &&(customerId == "" || customerId == '0')){

		var customerType = $('#customerType').combobox('getValues').toString();//�ͻ�����
		var province = $('#province').combobox('getValue');//ʡ��
		var city = $('#city').combobox('getValues').toString();//����
		if(province && city && customerType){
			//ajax��ȡ���۸�����
			var responseText = $.ajax({
				url : 'index1.php?model=system_saleperson_saleperson&action=getSalePerson',
				data : { "province" : province , "city" : city , 'customerTypeName' : customerType },
				type : "POST",
				async : false
			}).responseText;

			//�з���ֵ
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

//�����������
function reloadCity(data) {
	var str;

	//������Ⱦ
	var cityObj = $('#city');
	cityObj.combobox({
		url : "?model=system_procity_city&action=listJson&tProvinceName=" + data
	});
}

//���������Ϣ
function clearSale(){
	//���ʡ�пͻ�����
	clearPCC();

	$("#chanceName").val('');
	$("#chanceId").val('');
	$("#chanceCode").val('');
	$("#customerName").val('');
	$("#customerId").val('');

	//���÷��ù�������
	if(isCombobox('costBelonger') == 1){
		$("#costBelonger").combobox("setValue",'');
		$("#costBelongerId").val('');
	}else{
		$("#costBelonger").val('');
		$("#costBelongerId").val('');
	}
}

//�ж϶����combobox�Ƿ��Ѵ���
function isCombobox(objCode){
	if($("#" + objCode).attr("comboname")){
		return 1;
	}else{
		return 0;
	}
}

//��տͻ�ʡ�ݡ����С��ͻ�����ϵ��
function clearPCC(){
	//���ʡ����Ϣ
	$("#province").combobox('setValue','');

	//��տͻ�������Ϣ
	var customerTypeObj = $("#customerType");
	mulSelectClear(customerTypeObj);

	var cityObj = $("#city");
	mulSelectClear(cityObj);
}

//��ʼ���ͻ�
function initCustomer(){
	//���Ƴ�
	var customerNameObj = $("#customerName");
	customerNameObj.yxcombogrid_customer('remove');
	// �ͻ�
	customerNameObj.yxcombogrid_customer({
		hiddenId : 'customerId',
		height : 300,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					//�ر��������
					closeInput('customer');

					$("#province").combobox('setValue',data.Prov);
					$("#provinceHidden").val(data.Prov);

					var customerTypeName = getDataByCode(data.TypeOne);
					var customerTypeObj = $("#CustomerType");
					customerTypeObj.combobox('setValue',customerTypeName);

					//���ؿͻ�����
					reloadCity(data.Prov);
					var cityObj = $("#city");
					cityObj.combobox('setValue',data.City);

					//���۸�����
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

				//�����������
				openInput('customer');
			}
		}
	}).attr('readonly',false).attr('class','txt');
}

//��ȡ�̻���Ϣ
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
					alert('ϵͳ�д��ڡ�' + dataArr.thisLength + '��������Ϊ��' + chanceName + '�����̻�����ͨ���̻����ƥ���̻���Ϣ��');
					clearSale();
				}else{
					if(!thisType){
						//�ر��������
						closeInput('chance');
					}

					//�̻���Ϣ��ֵ
					chanceSetValue(dataArr,thisType);
				}
	   	    }else{
				alert('û�в�ѯ������̻���Ϣ');
				clearSale();
	   	    }
		}
	});
}

//�̻���ֵ��Ϣ
function chanceSetValue(dataArr,thisType){
	$("#chanceCode").val(dataArr.chanceCode);
	$("#chanceId").val(dataArr.id);
	$("#chanceName").val(dataArr.chanceName);
	$("#customerId").val(dataArr.customerId);
	$("#customerName").val(dataArr.customerName);

	$("#province").combobox('setValue',dataArr.Province);
	$("#provinceHidden").val(dataArr.Province);

	//���ؿͻ�����
	reloadCity(dataArr.Province);
	var cityObj = $("#city");
	cityObj.combobox('setValue',dataArr.City).combobox('disable');
	mulSelectSet(cityObj);

	//�ͻ�����
	var customerTypeObj = $("#CustomerType");
	customerTypeObj.combobox('setValue',dataArr.customerTypeName);
	mulSelectSet(customerTypeObj);

	//���۸�����
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

//��ʼ������Ʊ��
function initRequireDetail(requireId){
    //��ȡ��Ʊ����˻�����Ϣ������
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
        title : '��Ʊ��ϸ��Ϣ',
		isAddOneRow : true,
		colModel : [{
				name : 'auditDate',
				display : '��������',
				width : 'txtmiddle',
				width : 80,
				type : 'date',
				validation : {
					required : true
				}
			},{
				name : 'airName',
				display : '�˻�������',
				type : 'hidden'
			},
			{
				name : 'airId',
				readonly : true,
				display : '�˻���',
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
				display : '���չ�˾',
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
							param : {"findVal" : "����"}
						}
					});
				}
			},
			{
				name : 'airlineId',
				tclass : 'txt',
				display : '���չ�˾ID',
				type : 'hidden'
			},
			{
				name : 'flightNumber',
				tclass : 'txt',
				display : '����/�����',
				width : 120,
				validation : {
					required : true
				}
			},
			{
				name : 'flightTime',
				display : '�˻�ʱ��',
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
				display : '����ʱ��',
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
				display : '�����ص�',
				width : 100,
				validation : {
					required : true
				}
			},
			{
				name : 'arrivalPlace',
				display : '����ص�',
				width : 100,
				validation : {
					required : true
				}
			},
			{
				name : 'isLow',
				type : 'checkbox',
				display : '�������',
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
				display : 'Ʊ��۸�',
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
				display : '���������',
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
				display : '�����',
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
				display : 'ȼ�͸��ӷ�',
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
				display : 'ʵ�ʶ�Ʊ���',
				type : 'money',
				width : 80,
				validation : {
					required : true
				}
			},
			{
				name : 'lowremark',
				tclass : 'txt',
				display : 'δ������ͼ�ԭ��',
				validation : {
					required : true
				},
				width : 150
			},
			{
				name : 'remark',
				tclass : 'txt',
				display : '��ע',
				width : 120
			}
		]
	});
}
//��ʼ��һЩ�ֶ�
//var objName = ''; //ҵ�����
//var initId = ''; //����ģ��id
//var actionType = ''; //�������� add edit view create
//var myUrl = ''; //��������
//var isCompanyReadonly = ''; //��˾�Ƿ�ֻ��
var defaultCompany = '���Ͷ���'; //Ĭ�Ϲ�˾ֵ
var defaultCompanyId = 'dl'; //Ĭ�Ϲ�˾ֵ

//���ù�����������
var expenseSaleDept;
var expenseContractDept;
var expenseTrialProjectFeeDept;

$(document).ready(function() {
	if(checkCanInit() == false){
		return false;
	}

	if(actionType != 'add'){
		//ajax��ȡ���۸�����
		var responseText = $.ajax({
			url:myUrl,
			data : {"id" : $("#id").val()},
			type : "POST",
			async : false
		}).responseText;
		var objInfo = eval("(" + responseText + ")");
	}
	if(actionType == 'view'){
		//��ʼ����������
		initCostTypeView(objInfo);
	}else{
		if(actionType == 'add'){
			initCostType();
		}else if(actionType == 'edit'){
			initCostTypeEdit(objInfo);
		}

		//�󶨱���֤����
		$("form").bind('submit',costCheckForm);
	}
});

//��֤�Ƿ�ɳ�ʼ��
function checkCanInit(){
	//��ʼ��ʱ��֤�����Ƿ����
	try{
		objName;
	}catch(e){
		alert('�޷���ʼ�����ù�����Ϣ�����ȶ�����룡');
		return false;
	}

	//��ʼ��ʱ��֤�����Ƿ����
	try{
		initId;
	}catch(e){
		alert('�޷���ʼ�����ù�����Ϣ�����ȶ������ģ��id��');
		return false;
	}

	//��ʼ��ʱ��֤�����Ƿ����
	try{
		actionType;
	}catch(e){
		alert('�޷���ʼ�����ù�����Ϣ�����ȶ��嶯�����ͣ�');
		return false;
	}
	//���������������Ҫ�����Ƿ��ж������ݻ�ȡ·��
	if(actionType != 'add'){
		//��ʼ��ʱ��֤�����Ƿ����
		try{
			myUrl;
		}catch(e){
			alert('�޷���ʼ�����ù�����Ϣ�����ȶ���ҵ����Ϣ��ȡ·����');
			return false;
		}
	}

	//�жϹ�˾�Ƿ����趨ֻ��
	try{
		isCompanyReadonly;
	}catch(e){
		isCompanyReadonly = false;
	}
}

//��ʼ����������
function initCostType(){
    var tableStr = '<table class="form_in_table" id="'+objName+'tbl">' +
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
}

//ѡ���������
function changeDetailType(detailType){
	if(detailType){
		$("#detailTypeTitle").html('��������').removeClass('red').addClass('blue');
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

//��ʼ������
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

	//Ĭ�ϻ�ȡ
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
			'<td class = "form_text_left"><span class="blue">���ù�����˾</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="'+ thisClass + '" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+thisCompany+'" readonly="readonly"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+thisCompanyId+'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">���ù�������</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" value="'+ deptName +'" readonly="readonly"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+ deptId +'"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">�� �� ��</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" />' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" />' +
				'<input type="hidden" id="projectType" name="'+objName+'[projectType]" />' +
			'</td>' +
			'<td class = "form_text_left">�����龭��</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" />' +
			'</td>' +
		'</tr>';
	$("#"+objName + "tbl").append(tableStr);

	if(!isCompanyReadonly == true){
		//��˾��Ⱦ
		$("#costBelongCom").yxcombogrid_branch({
			hiddenId : 'costBelongComId',
			height : 250,
			isFocusoutCheck : false,
			gridOptions : {
				showcheckbox : false
			}
		});
	}
	//���ù�������ѡ��
	$("#costBelongDeptName").yxselect_dept({
		hiddenId : 'costBelongDeptId',
        event : {
            'selectReturn' : function(e,obj){
                //ajax��ȡ���۸�����
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
                //��ʼ��
                initDeptAppendInfo();
            }
        }
	});

	//��ͬ��Ŀ��Ⱦ
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

    //��Ⱦʡ�ݡ��ͻ�����
    initDeptAppendInfo();
}

//��Ⱦ�ͻ�����
function initDeptAppendInfo(objInfo){
    var deptIsNeedProvince = $("#deptIsNeedProvince").val();
    var deptIsNeedCustomerType = $("#deptIsNeedCustomerType").val();

    var province = '';
    var customerType = '';
    if(objInfo){
        province = objInfo.province;
        customerType = objInfo.customerType;
    }
    //�����Ҫ���ż��
    if(deptIsNeedProvince == "1" || deptIsNeedCustomerType == "1"){
        if($("#deptAppendInfo").length == 1){
            $("#deptAppendInfo").remove();
        }
        var tableStr = '<tr class="feeTypeContent" id="deptAppendInfo">';
        //�ֱ���Ⱦ
        if(deptIsNeedProvince == "1" && deptIsNeedCustomerType == "0"){
            tableStr += '<td class = "form_text_left"><span class="blue">ʡ��</span></td>' +
                '<td class = "form_text_right" colspan="3">' +
                '<input type="text" class="txt" id="province" name="'+objName+'[province]" value="'+province+'" readonly="readonly" style="width:202px;"/>' +
                '<input type="hidden" id="customerType" name="'+objName+'[customerType]" value=""/>' +
                '</td>';
        }
        //�ֱ���Ⱦ
        if(deptIsNeedProvince == "0" && deptIsNeedCustomerType == "1"){
            tableStr += '<td class = "form_text_left"><span class="blue">�ͻ�����</span></td>' +
                '<td class = "form_text_right" colspan="3">' +
                '<input type="text" class="txt" id="customerType" name="'+objName+'[customerType]" value="'+customerType+'" readonly="readonly" style="width:202px;"/>' +
                '<input type="hidden" id="province" name="'+objName+'[province]" value=""/>' +
                '</td>';
        }

        //�������������
        if(deptIsNeedProvince == "1" && deptIsNeedCustomerType == "1"){
            tableStr += '<td class = "form_text_left"><span class="blue">ʡ��</span></td>' +
                '<td class = "form_text_right">' +
                '<input type="text" class="txt" id="province" name="'+objName+'[province]" value="'+province+'" readonly="readonly" style="width:202px;"/>' +
                '</td>'+'<td class = "form_text_left"><span class="blue">�ͻ�����</span></td>' +
                '<td class = "form_text_right">' +
                '<input type="text" class="txt" id="customerType" name="'+objName+'[customerType]" value="'+customerType+'" readonly="readonly" style="width:202px;"/>' +
                '</td>';
        }

        //ҳ�����
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
            //�ͻ�������Ⱦ
            $('#customerType').combobox({
                url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
                valueField:'text',
                textField:'text',
                editable : false
            });
        }
    }else if(deptIsNeedProvince == "0"){
        //���
        clearDeptAppendInfo();
    }
}

//������Ҫ���Ĳ�����չ����
function clearDeptAppendInfo(){
    //�����Ҫ���ż��
    if($("#deptAppendInfo").length == 1){
        //���ر��ȴ��ո�ֵ
        $("#deptAppendInfo").hide();
        $('#���province').combobox('setValue','');
        $('#customerType').combobox('setValue','');
    }
}

//��ʼ����ͬ��Ŀ
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
			'<td class = "form_text_left"><span class="blue">��Ŀ����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" />' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" />' +
				'<input type="hidden" id="projectType" name="'+objName+'[projectType]" />' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" />' +
				'<input type="hidden" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">��Ŀ����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" />' +
			'</td>' +
		'</tr>'+
		'<tr class="feeTypeContent">'+
			'<td class = "form_text_left"><span class="blue">���ù�������</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
			'<input type="text" class="readOnlyTxtNormal" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" readonly="readonly"/>' +
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
					$("#projectType").val(data.projectType);

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
			'<td class = "form_text_left"><span class="blue">��Ŀ����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" />' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" />' +
				'<input type="hidden" id="projectType" name="'+objName+'[projectType]" />' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" />' +
				'<input type="hidden" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">��Ŀ����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" />' +
			'</td>' +
		'</tr>'+
		'<tr class="feeTypeContent">'+
			'<td class = "form_text_left"><span class="blue">���ù�������</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
			'<input type="text" class="readOnlyTxtNormal" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" readonly="readonly"/>' +
			'</td>' +
		'</tr>';
	$("#"+objName + "tbl").append(tableStr);

	//�з���Ŀ��Ⱦ
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
			'<td class = "form_text_left">������Ŀ����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" />' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" />' +
				'<input type="hidden" id="projectType" name="'+objName+'[projectType]" />' +
				'<input type="hidden" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
			'</td>' +
			'<td class = "form_text_left">��Ŀ����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" />' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">�̻����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="chanceCode" name="'+objName+'[chanceCode]" onblur="getChanceInfo();"/>' +
				'<input type="hidden" id="chanceName" name="'+objName+'[chanceName]" />' +
				'<input type="hidden" id="chanceId" name="'+objName+'[chanceId]" />' +
			'</td>' +
			'<td class = "form_text_left">�ͻ�����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="customerName" name="'+objName+'[customerName]"/>' +
				'<input type="hidden" id="customerId" name="'+objName+'[customerId]" />' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">�ͻ�ʡ��</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="province" name="'+objName+'[province]" style="width:202px;"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">�ͻ�����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="city" name="'+objName+'[city]" style="width:202px;"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">�ͻ�����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="customerType" name="'+objName+'[customerType]" style="width:202px;"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">���۸�����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="costBelonger" name="'+objName+'[costBelonger]" style="width:202px;"/>' +
				'<input type="hidden" id="costBelongerId" name="'+objName+'[costBelongerId]" />' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">���ù�������</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
				'<input type="text" class="txt" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" style="width:202px;"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" />' +
			'</td>' +
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
			param : {'contractType' : 'GCXMYD-04' , 'statusArr' : 'GCXMZT02'},
			event : {
				'row_dblclick' : function(e,row,data) {
					//�����������
					closeInput('trialPlan',data.id);

					$("#projectCode").val(data.projectCode);
					$("#proManagerName").val(data.managerName);
					$("#proManagerId").val(data.managerId);
					$("#projectType").val('esm');

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

							$("#customerType").combobox('setValue',trialProjectInfo.customerTypeName);

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

				//�����������
				openInput('trialPlan');
			}
		}
	}).attr('readonly',false).attr('class','txt');

	//��ʼ���ͻ�
	initCustomer();

	//�ͻ�������Ⱦ
	$('#customerType').combobox({
		url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
		valueField:'text',
        textField:'text',
  		editable : false,
		onSelect : function(){
			//�������۸�����
			changeCustomerType();
		},
		onUnselect : function(){
			//�������۸�����
			changeCustomerType();
		}
	});

	//ʡ����Ⱦ
	$('#province').combobox({
		url:'index1.php?model=system_procity_province&action=listJsonSort',
		valueField:'provinceName',
        textField:'provinceName',
		editable : false,
		onSelect : function(obj){
			//����ʡ�ݶ�ȡ����
			reloadCity(obj.provinceName);
		}
	});

	//������Ⱦ
	$('#city').combobox({
		textField:'cityName',
		valueField:'cityName',
		multiple:true,
		editable : false,
        formatter: function(obj){
			return "<input type='checkbox' id='city_"+ obj.cityName +"' value='"+ obj.cityName +"'/> " + obj.cityName;
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

//��ʼ���ۺ�
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
			'<td class = "form_text_left"><span class="blue">��ͬ���</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="contractCode" name="'+objName+'[contractCode]" onblur="getContractInfo()"/>' +
				'<input type="hidden" id="contractName" name="'+objName+'[contractName]" />' +
				'<input type="hidden" id="contractId" name="'+objName+'[contractId]" />' +
				'<input type="hidden" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
			'</td>' +
			'<td class = "form_text_left">�ͻ�����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="customerName" name="'+objName+'[customerName]" readonly="readonly"/>' +
				'<input type="hidden" id="customerId" name="'+objName+'[customerId]" />' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">�ͻ�ʡ��</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="province" name="'+objName+'[province]" readonly="readonly"/>' +
			'</td>' +
			'<td class = "form_text_left">�ͻ�����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="city" name="'+objName+'[city]" readonly="readonly"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">�ͻ�����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="customerType" name="'+objName+'[customerType]" readonly="readonly"/>' +
			'</td>' +
			'<td class = "form_text_left">���۸�����</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="costBelonger" name="'+objName+'[costBelonger]" readonly="readonly"/>' +
				'<input type="hidden" id="costBelongerId" name="'+objName+'[costBelongerId]" />' +
			'</td>' +
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left"><span class="blue">���ù�������</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
				'<input type="text" class="txt" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" style="width:202px;"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" />' +
			'</td>' +
		'</tr>';
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

	//��տͻ�������Ϣ
	$("#customerType").combobox('setValue','');

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
		//clearInputSet('chanceName');
	}else if(thisType == 'customer'){
		//��Ŀ
		$("#projectCode").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');
		$("#projectName").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');

		//�̻�
		$("#chanceCode").attr("class",'readOnlyTxtNormal').attr('readonly',true);
		$("#chanceName").attr("class",'readOnlyTxtNormal').attr('readonly',true);

		//����̻�����Ⱦ
		clearInputSet('chanceCode');
		//clearInputSet('chanceName');
	}else if(thisType == 'chance'){
		//��Ŀ
		$("#projectCode").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');
		$("#projectName").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_esmproject('remove');
		$("#customerName").attr("class",'readOnlyTxtNormal').yxcombogrid_customer('remove').attr('readonly',true);
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
	$("#customerType").combobox('enable');
	$("#costBelonger").combobox('enable');
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

		var customerType = $('#customerType').combobox('getValue');//�ͻ�����
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
			//���۸�����
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

//�����������
function reloadCity(data) {
	//������Ⱦ
	$('#city').combobox({
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
	$("#customerType").combobox('setValue','');

	mulSelectClear($("#city"));
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

					var customerTypeName = getDataByCode(data.TypeOne);
					$("#customerType").combobox('setValue',customerTypeName);

					//���ؿͻ�����
					reloadCity(data.Prov);
					$("#city").combobox('setValue',data.City);

					//���۸�����
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

				//�����������
				openInput('customer');
			}
		}
	}).attr('readonly',false).attr('class','txt');
}

//��ȡ�̻���Ϣ
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
function chanceSetValue(dataArr){
	$("#chanceCode").val(dataArr.chanceCode);
	$("#chanceId").val(dataArr.id);
	$("#chanceName").val(dataArr.chanceName);
	$("#customerId").val(dataArr.customerId);
	$("#customerName").val(dataArr.customerName);

	$("#province").combobox('setValue',dataArr.Province);

	//���ؿͻ�����
	reloadCity(dataArr.Province);
	$("#city").combobox('setValue',dataArr.City).combobox('disable');

	//�ͻ�����
	$("#customerType").combobox('setValue',dataArr.customerTypeName);

	//���۸�����
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

//��ʼ����������
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

//��ʼ������
function initDeptView(objInfo){
	var tableStr = '<table class="form_in_table" id="'+objName+'tbl">' +
        '<tr id="feeTypeTr">' +
            '<td class = "form_text_left"><span id="detailTypeTitle">��������</span></td>' +
            '<td class = "form_text_right" colspan="3">' +
                '���ŷ���' +
            '</td>' +
        '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left">���ù�����˾</td>' +
            '<td class = "form_text_right" width="30%">' +
                objInfo.costBelongCom +
            '</td>' +
            '<td class = "form_text_left">���ù�������</td>' +
            '<td class = "form_text_right">' +
                objInfo.costBelongDeptName +
            '</td>' +
        '</tr>' +
        '<tr class="feeTypeContent">' +
            '<td class = "form_text_left">�� �� ��</td>' +
            '<td class = "form_text_right" width="30%">' +
                objInfo.projectName +
            '</td>' +
            '<td class = "form_text_left">�����龭��</td>' +
            '<td class = "form_text_right">' +
                objInfo.proManagerName +
            '</td>' +
        '</tr>';
    //�������ʡ����Ϣ
    if(objInfo.province && objInfo.customerType == ""){
        //����ʡ��
        tableStr += "<tr><td class='form_text_left'>����ʡ��</td><td class='form_text_right' colspan='3'>"+objInfo.province+"</td></tr>";
    }
    if(objInfo.province == "" && objInfo.customerType){
        //����ͻ�����
        tableStr += "<tr><td class='form_text_left'>�ͻ�����</td><td class='form_text_right' colspan='3'>"+objInfo.customerType+"</td></tr>";
    }
    if(objInfo.province && objInfo.customerType){
        //����ʡ�ݺͿͻ�����
        tableStr += "<tr><td class='form_text_left'>����ʡ��</td><td class='form_text_right'>"+objInfo.province+"</td>" +
            "<td class='form_text_left'>�ͻ�����</td><td class='form_text_right'>"+objInfo.customerType+"</td>" +
            "</tr>";
    }
    tableStr += '</table>';
	$("#"+initId).html(tableStr);
}

//��ʼ����Ŀ�鿴
function initProjectView(objInfo){
    var projectView = objInfo.detailType == '2' ? '��ͬ��Ŀ����' : '�з�����';
	var tableStr = '<table class="form_in_table" id="'+objName+'tbl">' +
			'<tr id="feeTypeTr">' +
				'<td class = "form_text_left"><span id="detailTypeTitle">��������</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
                    projectView +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">��Ŀ����</span></td>' +
				'<td class = "form_text_right" width="30%">' +
					objInfo.projectName +
				'</td>' +
				'<td class = "form_text_left">��Ŀ����</td>' +
				'<td class = "form_text_right">' +
					objInfo.proManagerName +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">���ù�������</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					objInfo.costBelongDeptName +
				'</td>' +
			'</tr>' +
		'</table>';
	$("#"+initId).html(tableStr);
}

//��ʼ����ǰ
function initSaleView(objInfo){
	var tableStr = '<table class="form_in_table" id="'+objName+'tbl">' +
			'<tr id="feeTypeTr">' +
				'<td class = "form_text_left"><span id="detailTypeTitle">��������</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'��ǰ����' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">������Ŀ����</td>' +
				'<td class = "form_text_right" width="30%">' +
					objInfo.projectName +
				'</td>' +
				'<td class = "form_text_left">��Ŀ����</td>' +
				'<td class = "form_text_right">' +
					objInfo.proManagerName +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">�̻����</td>' +
				'<td class = "form_text_right">' +
					objInfo.chanceCode +
				'</td>' +
				'<td class = "form_text_left">�ͻ�����</td>' +
				'<td class = "form_text_right">' +
					objInfo.customerName +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">�ͻ�ʡ��</td>' +
				'<td class = "form_text_right">' +
					objInfo.province +
				'</td>' +
				'<td class = "form_text_left">�ͻ�����</td>' +
				'<td class = "form_text_right">' +
					objInfo.city +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">�ͻ�����</td>' +
				'<td class = "form_text_right">' +
					objInfo.customerType +
				'</td>' +
				'<td class = "form_text_left">���۸�����</td>' +
				'<td class = "form_text_right">' +
					objInfo.costBelonger +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">���ù�������</td>' +
				'<td class = "form_text_right" colspan="3">' +
					objInfo.costBelongDeptName +
				'</td>' +
			'</tr>'+
		'</table>';
	$("#"+initId).html(tableStr);
}

//��ʼ���ۺ�
function initContractView(objInfo){
	var tableStr = '<table class="form_in_table" id="'+objName+'tbl">' +
			'<tr id="feeTypeTr">' +
				'<td class = "form_text_left"><span id="detailTypeTitle">��������</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'�ۺ����' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">��ͬ���</td>' +
				'<td class = "form_text_right" width="30%">' +
					objInfo.contractCode +
				'</td>' +
				'<td class = "form_text_left">�ͻ�����</td>' +
				'<td class = "form_text_right">' +
					objInfo.customerName +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">�ͻ�ʡ��</td>' +
				'<td class = "form_text_right">' +
					objInfo.province +
				'</td>' +
				'<td class = "form_text_left">�ͻ�����</td>' +
				'<td class = "form_text_right">' +
					objInfo.city +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">�ͻ�����</td>' +
				'<td class = "form_text_right">' +
					objInfo.customerType +
				'</td>' +
				'<td class = "form_text_left">���۸�����</td>' +
				'<td class = "form_text_right">' +
					objInfo.costBelonger +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left">���ù�������</td>' +
				'<td class = "form_text_right" colspan="3">' +
					objInfo.costBelongDeptName +
				'</td>' +
			'</tr>' +
		'</table>';
    $("#"+initId).html(tableStr);
}

//��ʼ����������
function initCostTypeEdit(objInfo){
	var tableStr = '<table class="form_in_table" id="'+objName+'tbl">' +
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
	changeDetailTypeEdit(objInfo.detailType,objInfo);
}

//ѡ���������
function changeDetailTypeEdit(detailType,objInfo){
	if(detailType){
		//��ѡ��ֵ
		$("input[name='"+objName+"[detailType]']").each(function(i,n){
			if(this.value == detailType){
				$(this).attr("checked",this);
				return false;
			}
		});
		$("#detailTypeTitle").html('��������').removeClass('red').addClass('blue');
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

//��ʼ������
function initDeptEdit(objInfo){
	//��ʼֵ����
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
		'</tr>' +
		'<tr class="feeTypeContent">' +
			'<td class = "form_text_left">�� �� ��</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" value="'+projectName +'" readonly="readonly"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" value="'+projectCode +'"/>' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" value="'+projectId +'" />'+
				'<input type="hidden" id="projectType" name="'+objName+'[projectType]" value="'+projectType +'"/>' +
			'</td>' +
			'<td class = "form_text_left">�����龭��</td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" value="'+proManagerName +'" readonly="readonly"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" value="'+proManagerId +'"/>' +
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
		hiddenId : 'costBelongDeptId',
        event : {
            'selectReturn' : function(e,obj){
                //ajax��ȡ���۸�����
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
                //��ʼ��
                initDeptAppendInfo();
            }
        }
	});

	//��ͬ��Ŀ��Ⱦ
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

    //��ʼ��
    initDeptAppendInfo(objInfo);
}

//��ʼ����ͬ��Ŀ
function initContractProjectEdit(objInfo){
	//��ʼֵ����
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
			'<td class = "form_text_left"><span class="blue">��Ŀ����</span></td>' +
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
			'<td class = "form_text_left"><span class="blue">��Ŀ����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" value="'+proManagerId +'"/>' +
			'</td>' +
		'</tr>'+
		'<tr class="feeTypeContent">'+
			'<td class = "form_text_left"><span class="blue">���ù�������</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
			'<input type="text" class="readOnlyTxtNormal" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" readonly="readonly"  value="'+costBelongDeptName +'"/>' +
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
				$("#projectId").val('');
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
function initRdProjectEdit(objInfo){
	//��ʼֵ����
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
			'<td class = "form_text_left"><span class="blue">��Ŀ����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="txt" id="projectName" name="'+objName+'[projectName]" readonly="readonly" value="'+projectName +'"/>' +
				'<input type="hidden" id="projectCode" name="'+objName+'[projectCode]" value="'+projectCode +'"/>' +
				'<input type="hidden" id="projectId" name="'+objName+'[projectId]" value="'+projectId +'"/>' +
				'<input type="hidden" id="projectType" name="'+objName+'[projectType]" value="'+projectType +'"/>' +
				'<input type="hidden" id="costBelongDeptId" name="'+objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
				'<input type="hidden" id="costBelongCom" name="'+objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
				'<input type="hidden" id="costBelongComId" name="'+objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
			'</td>' +
			'<td class = "form_text_left"><span class="blue">��Ŀ����</span></td>' +
			'<td class = "form_text_right">' +
				'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
				'<input type="hidden" id="proManagerId" name="'+objName+'[proManagerId]" value="'+proManagerId +'"/>' +
			'</td>' +
		'</tr>'+
		'<tr class="feeTypeContent">'+
			'<td class = "form_text_left"><span class="blue">���ù�������</span></td>' +
			'<td class = "form_text_right" colspan="3">' +
			'<input type="text" class="readOnlyTxtNormal" id="costBelongDeptName" name="'+objName+'[costBelongDeptName]" readonly="readonly" value="'+costBelongDeptName+'"/>' +
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
					$("#projectId").val(data.projectId);
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
				$("#projectId").val('');
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
function initSaleEdit(objInfo){
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
			'</td>' +
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
			param : {'contractType' : 'GCXMYD-04' , 'statusArr' : 'GCXMZT02'},
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

							$("#customerType").combobox('setValue',trialProjectInfo.customerTypeName);

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

				//�����������
				openInput('trialPlan');
			}
		}
	}).attr('readonly',false).attr('class','txt');

	//��ʼ���ͻ�
	initCustomer();

	//�ͻ�������Ⱦ
	$('#customerType').combobox({
		url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
		valueField:'text',
        textField:'text',
  		editable : false,
		onSelect : function(){
			//�������۸�����
			changeCustomerType();
		},
		onUnselect : function(){
			//�������۸�����
			changeCustomerType();
		}
	});

	//ʡ����Ⱦ
	$('#province').combobox({
		url:'index1.php?model=system_procity_province&action=listJsonSort',
		valueField:'provinceName',
        textField:'provinceName',
		editable : false,
		onSelect : function(obj){
			//����ʡ�ݶ�ȡ����
			reloadCity(obj.provinceName);
		}
	});

	//������Ⱦ
	var cityArr = city.split(',');
	$('#city').combobox({
		url : "?model=system_procity_city&action=listJson&tProvinceName=" + province,
		textField:'cityName',
		valueField:'cityName',
		multiple:true,
		editable : false,
        formatter: function(obj){
        	//�ж� ���û�г�ʼ�������У���ѡ��
        	if(cityArr.indexOf(obj.cityName) == -1){
    			return "<input type='checkbox' id='city_"+ obj.cityName +"' value='"+ obj.cityName +"'/> " + obj.cityName;
        	}else{
    			return "<input type='checkbox' id='city_"+ obj.cityName +"' value='"+ obj.cityName +"' checked='checked'/> " + obj.cityName;
        	}
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
	}).combobox('setValues',cityArr);

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

	//����һ�ν��ô���
	closeInput();
	//����һ���������۸�����
	changeCustomerType('init');
}

//��ʼ���ۺ�
function initContractEdit(objInfo){
	//��ʼֵ����
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
			'</td>' +
		'</tr>';
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

	buildInputSet('contractCode','��ͬ���','contract');
}

//����֤����
function costCheckForm(){
	var detailType = $("input[name='" +objName+ "[detailType]']:checked").val();
	if(detailType){
		//���� ��Ӧ������֤
		switch(detailType){
			case '1' :
				if($("#costBelongCom").val() == ""){
					alert("û����д���ù�����˾");
					return false;
				}
				if($("#costBelongDeptName").val() == ""){
					alert("û����д���ù�������");
					return false;
				}
                if($("#deptIsNeedProvince").val() == "1"){
                    var province = $("#province").combobox('getValue');
                    if(province == ""){
                        alert("��ѡ������ʡ��");
                        return false;
                    }
                }
				break;
			case '2' :
				if($("#projectCode").val() == ""){
					alert("��ѡ��ñʷ������ڹ�����Ŀ");
					return false;
				}
				break;
			case '3' :
				if($("#projectCode").val() == ""){
					alert("��ѡ��ñʷ��������з���Ŀ");
					return false;
				}
				break;
			case '4' :
				if($("#province").combobox('getValue') == ""){
					alert("��ѡ��ͻ�����ʡ��");
					return false;
				}
				if($("#city").combobox('getValues') == ""){
					alert("��ѡ��ͻ����ڳ���");
					return false;
				}
				if($("#customerType").combobox('getValue') == ""){
					alert("��ѡ��ͻ�����");
					return false;
				}
				if($("#costBelongerId").val() == ""){
					alert("��¼�����۸����ˣ����۸����˿����̻����ͻ������Զ�����������ͨ���ͻ�ʡ�ݡ����С�������ϵͳƥ��");
					return false;
				}
				if($("#costBelongDeptId").val() == "" || $("#costBelongDeptName").combobox('getValue') ==""){
					alert("��ѡ����ù�������");
					return false;
				}
				break;
			case '5' :
				if($("#contractCode").val() == ""){
					alert("��ѡ��ñʷ��ù�����ͬ");
					return false;
				}
				if($("#costBelongDeptId").val() == "" || $("#costBelongDeptName").combobox('getValue') ==""){
					alert("��ѡ����ù�������");
					return false;
				}
				break;
			default : break;
		}
		return true;
	}else{
		alert('��ѡ���������');
		return false;
	}
}
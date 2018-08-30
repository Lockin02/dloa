//���ù������
(function($){
	//Ĭ������
	var defaults = {
		getId : 'id', //ȡ����ѯ����id
		objName : 'objName', //ҵ�����
		actionType : 'add', //�������� add edit view create,Ĭ��add
		url : '', //ȡ��url
		data : {},//��������
		isCompanyReadonly : false, //��˾�Ƿ�ֻ��
		company : '���Ͷ���', //Ĭ�Ϲ�˾ֵ
		companyId : 'dl', //Ĭ�Ϲ�˾ֵ
		isRequired : true//�Ƿ���ù�������
	};

	//���ù����������� - ���ڻ�������
	var expenseSaleDept;
	var expenseContractDept;
	var expenseTrialProjectFeeDept;

	//================== �ڲ����� ====================//
	//��ʼ����������
	function initCostType(thisObj){
		var tableStr = '<table class="form_in_table" id="'+ defaults.myId +'tbl">' +
				'<tr id="feeTypeTr">' +
					'<td class = "form_text_left_three"><span id="detailTypeTitle" class="red">��ѡ���������</span></td>' +
					'<td class = "form_text_right" colspan="5">' +
						'<input type="radio" name="'+defaults.objName+'[detailType]" value="1"/> ���ŷ��� ' +
						'<input type="radio" name="'+defaults.objName+'[detailType]" value="2"/> ��ͬ��Ŀ���� ' +
						'<input type="radio" name="'+defaults.objName+'[detailType]" value="3"/> �з����� ' +
						'<input type="radio" name="'+defaults.objName+'[detailType]" value="4"/> ��ǰ���� ' +
						'<input type="radio" name="'+defaults.objName+'[detailType]" value="5"/> �ۺ���� ' +
					'</td>' +
				'</tr>' +
			'</table>';
		$(thisObj).html(tableStr);
		$("input[name='"+defaults.objName+"[detailType]']").each(function(){
			$(this).bind('click',resetCombo);

			//���������Ͱ󶨴����¼�
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

	//���ø�������
	function resetCombo(){
		$("#detailTypeTitle").html('��������').removeClass('red').addClass('blue');
		$("#projectName").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
		$("#projectCode").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
		$("#costBelongCom").yxcombogrid_branch('remove');
		$(".feeTypeContent").remove();
	}

	/****************************** ��ͬ���õ��� ***********************************/
	//��ʼ������ TODO
	function initDept(){
		var thisClass,thisCompany,thisCompanyId;
		if(defaults.isCompanyReadonly == true){
			thisClass = "readOnlyTxtNormal";
		}else{
			thisClass = "txt";
		}
		thisCompany = defaults.company;
		thisCompanyId = defaults.companyId;

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
				'<td class = "form_text_left_three"><span class="blue">���ù�����˾</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="'+ thisClass + '" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+defaults.company+'" readonly="readonly"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+thisCompanyId+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" value="'+ deptName +'" readonly="readonly"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" value="'+ deptId +'"/>' +
				'</td>' +
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

		if(!defaults.isCompanyReadonly == true){
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
			hiddenId : 'costBelongDeptId'
		});
	}

	//��ʼ����ͬ��Ŀ TODO
	function initContractProject(){
		var thisCompany = defaults.company;
		var thisCompanyId = defaults.companyId;
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[projectCode]" readonly="readonly"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" />' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" id="projectType"/>' +
					'<input type="hidden" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" />' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" />' +
					'<input type="hidden" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" />' +
				'</td>' +
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

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

		//������Ŀ��Ⱦ
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
						//���÷��ù�������
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

					//���÷��ù�������
					$("#costBelongDeptId").val('');
					$("#costBelongDeptName").val('');
				}
			}
		});
	}

	//��ʼ���з���Ŀ TODO
	function initRdProject(){
		var thisCompany = defaults.company;
		var thisCompanyId = defaults.companyId;
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[projectCode]" readonly="readonly"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" />' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" id="projectType"/>' +
					'<input type="hidden" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" />' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" />' +
					'<input type="hidden" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" />' +
				'</td>' +
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

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

		//�з���Ŀ��Ⱦ
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

						//���÷��ù�������
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

					//���÷��ù�������
					$("#costBelongDeptId").val('');
					$("#costBelongDeptName").val('');
				}
			}
		});
	}

	//��ʼ����ǰ TODO ��ǰ
	function initSale(){
		var thisCompany = defaults.company;
		var thisCompanyId = defaults.companyId;
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">������Ŀ���</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[projectCode]" readonly="readonly"/>' +
				'</td>' +
				'<td class = "form_text_left_three">������Ŀ����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" />' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" />' +
					'<input type="hidden" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">��Ŀ����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" />' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">�̻����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="chanceCode" name="'+defaults.objName+'[chanceCode]"/>' +
					'<input type="hidden" id="chanceId" name="'+defaults.objName+'[chanceId]" />' +
				'</td>' +
				'<td class = "form_text_left_three">�̻�����</td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="txt" id="chanceName" name="'+defaults.objName+'[chanceName]"/>' +
				'</td>' +
				'<td class = "form_text_left_three">�ͻ�����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="customerName" name="'+defaults.objName+'[customerName]"/>' +
					'<input type="hidden" id="customerId" name="'+defaults.objName+'[customerId]" />' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">�ͻ�ʡ��</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="province" name="'+defaults.objName+'[province]" style="width:202px;"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">�ͻ�����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="city" name="'+defaults.objName+'[city]" style="width:202px;"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">�ͻ�����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="customerType" name="'+defaults.objName+'[customerType]" style="width:202px;"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">���۸�����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="costBelonger" name="'+defaults.objName+'[costBelonger]" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongerId" name="'+defaults.objName+'[costBelongerId]" />' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" />' +
				'</td>'
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

		//�̻����
		var codeObj = $("#chanceCode");
		if(codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "�����������̻���ţ�ϵͳ�Զ�ƥ�������Ϣ";
		var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻����'>&nbsp;</span>");
		$button.click(function(){
			if(codeObj.val() == ""){
				alert('������һ���̻����');
				return false;
			}
		});

		//�����հ�ť
		var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
		$button2.click(function(){
			if(codeObj.val() != ""){
				//���������Ϣ
				clearSale();
				openInput('chance');
			}
		});
		codeObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');

		//�̻�����
		var nameObj = $("#chanceName");
		if(nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "�����������̻����ƣ�ϵͳ�Զ�ƥ�������Ϣ";
		var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻�����'>&nbsp;</span>");
		$button.click(function(){
			if(nameObj.val() == ""){
				alert('������һ���̻�����');
				return false;
			}
		});

		//�����հ�ť
		var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
		$button2.click(function(){
			if(nameObj.val() != ""){
				//���������Ϣ
				clearSale();
				openInput('chance');
			}
		});
		nameObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');

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
					$("#projectType").val('');
					clearSale();

					//�����������
					openInput('trialPlan');
				}
			}
		}).attr('class','txt');

		//��Ŀ���
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
						//�����������
						closeInput('trialPlan',data.id);

						$("#projectName").val(data.projectName);
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
								$("#provinceHidden").val(trialProjectInfo.province);

								var customerTypeObj = $("#CustomerType");
								customerTypeObj.combobox('setValue',trialProjectInfo.customerTypeName);
								mulSelectSet(customerTypeObj);

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

					//�����������
					openInput('trialPlan');
				}
			}
		}).attr('class','txt');

		//��ʼ���ͻ�
		initCustomer();

		//�ͻ�����
		var customerTypeArr = '';
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
	        	if(customerTypeArr.indexOf(obj.text) == -1){
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
		var cityObj = $('#city');
		provinceObj.combobox({
			url:'index1.php?model=system_procity_province&action=listJsonSort',
			valueField:'provinceName',
	        textField:'provinceName',
			editable : false,
			onSelect : function(obj){
				//���ö����µ�ѡ����
				$("#provinceHidden").val(obj.provinceName);
				//����ʡ�ݶ�ȡ����
				cityObj.combobox({
					url : "?model=system_procity_city&action=listJson&tProvinceName=" + obj.provinceName
				});
			}
		});

		//������Ⱦ
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

	//��ʼ���ۺ� TODO �ۺ�
	function initContract(){
		var thisCompany = defaults.company;
		var thisCompanyId = defaults.companyId;
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">��ͬ���</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt ciClass" id="contractCode" name="'+defaults.objName+'[contractCode]"/>' +
					'<input type="hidden" class="ciClass" id="contractId" name="'+defaults.objName+'[contractId]" />' +
					'<input type="hidden" class="ciClass" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+ thisCompany +'"/>' +
					'<input type="hidden" class="ciClass" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+ thisCompanyId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��ͬ���</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt ciClass" id="contractName" name="'+defaults.objName+'[contractName]"/>' +
				'</td>' +
				'<td class = "form_text_left_three">�ͻ�����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal ciClass" id="customerName" name="'+defaults.objName+'[customerName]" readonly="readonly"/>' +
					'<input type="hidden" class="ciClass" id="customerId" name="'+defaults.objName+'[customerId]" />' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">�ͻ�ʡ��</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal ciClass" id="province" name="'+defaults.objName+'[province]" readonly="readonly"/>' +
				'</td>' +
				'<td class = "form_text_left_three">�ͻ�����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal ciClass" id="city" name="'+defaults.objName+'[city]" readonly="readonly"/>' +
				'</td>' +
				'<td class = "form_text_left_three">�ͻ�����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal ciClass" id="customerType" name="'+defaults.objName+'[customerType]" readonly="readonly"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">���۸�����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="'+defaults.objName+'[costBelonger]" readonly="readonly"/>' +
					'<input type="hidden" class="ciClass" id="costBelongerId" name="'+defaults.objName+'[costBelongerId]" />' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" />' +
				'</td>'
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

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

		//���������Ⱦ
		var codeObj = $("#contractCode");
		if(codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "���������ĺ�ͬ��ţ�ϵͳ�Զ�ƥ�������Ϣ";
		var $button = $("<span class='search-trigger' id='contractCodeSearch' title='��ͬ���'>&nbsp;</span>");
		$button.click(function(){
			if($("#" + thisId).val() == ""){
				alert('������һ����ͬ���');
				return false;
			}
		});

		//�����հ�ť
		var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
		$button2.click(function(){
			$(".ciClass").val('');
		});
		codeObj.bind('blur',getContractInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true);

		//����������Ⱦ
		var nameObj = $("#contractName");
		if(nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "���������ĺ�ͬ���ƣ�ϵͳ�Զ�ƥ�������Ϣ";
		var $button = $("<span class='search-trigger' id='contractCodeSearch' title='��ͬ����'>&nbsp;</span>");
		$button.click(function(){
			if($("#" + thisId).val() == ""){
				alert('������һ����ͬ����');
				return false;
			}
		});

		//�����հ�ť
		var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
		$button2.click(function(){
			$(".ciClass").val('');
		});
		nameObj.bind('blur',getContractInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true);
	}

	//�첽ƥ���ͬ��Ϣ
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
						alert('ϵͳ�д��ڡ�' + dataArr.thisLength + '��������Ϊ��' + contractName + '���ĺ�ͬ����ͨ����ͬ���ƥ���ͬ��Ϣ��');
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
					alert('û�в�ѯ����غ�ͬ��Ϣ');
					$(".ciClass").val('');
		   	    }
			}
		});
	}

	//��ʼ���ͻ�
	function initCustomer(){
		//���Ƴ�
		$("#customerName").yxcombogrid_customer('remove').yxcombogrid_customer({
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
						var customerTypeObj = $("#customerType");
						var valArr = [];
						valArr.push(customerTypeName);
						customerTypeObj.combobox('setValues',valArr);

						//���ؿͻ�����
						var cityObj = $("#city");
						cityObj.combobox({
							url : "?model=system_procity_city&action=listJson&tProvinceName=" + data.Prov
						}).combobox('setValue',data.City);

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
						if(typeof(thisType) == 'object'){
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
			$("#customerName").attr("class",'readOnlyTxtNormal').attr('readonly',true).yxcombogrid_customer('remove');
		}
	}

	//�����������
	function openInput(thisType){
		if(thisType == 'trialPlan'){
			//����ʵ�����ͻ�ѡ��
			initCustomer();

			//�̻����
			var codeObj = $("#chanceCode");
			if(codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true){
				return false;
			}
			var title = "�����������̻���ţ�ϵͳ�Զ�ƥ�������Ϣ";
			var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻����'>&nbsp;</span>");
			$button.click(function(){
				if(codeObj.val() == ""){
					alert('������һ���̻����');
					return false;
				}
			});

			//�����հ�ť
			var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
			$button2.click(function(){
				if(codeObj.val() != ""){
					//���������Ϣ
					clearSale();
					openInput('chance');
				}
			});
			codeObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');

			//�̻�����
			var nameObj = $("#chanceName");
			if(nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true){
				return false;
			}
			var title = "�����������̻����ƣ�ϵͳ�Զ�ƥ�������Ϣ";
			var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻�����'>&nbsp;</span>");
			$button.click(function(){
				if(nameObj.val() == ""){
					alert('������һ���̻�����');
					return false;
				}
			});

			//�����հ�ť
			var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
			$button2.click(function(){
				if(nameObj.val() != ""){
					//���������Ϣ
					clearSale();
					openInput('chance');
				}
			});
			nameObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');
		}else if(thisType == 'customer'){
			//��Ŀ
			initTrialproject();

			$("#customerName").attr("class",'txt').attr('readonly',false);

			//�̻����
			var codeObj = $("#chanceCode");
			if(codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true){
				return false;
			}
			var title = "�����������̻���ţ�ϵͳ�Զ�ƥ�������Ϣ";
			var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻����'>&nbsp;</span>");
			$button.click(function(){
				if(codeObj.val() == ""){
					alert('������һ���̻����');
					return false;
				}
			});

			//�����հ�ť
			var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
			$button2.click(function(){
				if(codeObj.val() != ""){
					//���������Ϣ
					clearSale();
					openInput('chance');
				}
			});
			codeObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');

			//�̻�����
			var nameObj = $("#chanceName");
			if(nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true){
				return false;
			}
			var title = "�����������̻����ƣ�ϵͳ�Զ�ƥ�������Ϣ";
			var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻�����'>&nbsp;</span>");
			$button.click(function(){
				if(nameObj.val() == ""){
					alert('������һ���̻�����');
					return false;
				}
			});

			//�����հ�ť
			var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
			$button2.click(function(){
				if(nameObj.val() != ""){
					//���������Ϣ
					clearSale();
					openInput('chance');
				}
			});
			nameObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');
		}else if((typeof(thisType) == "object" && thisType.data== 'chance') || thisType== 'chance'){
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

	//������Ŀ��Ⱦ -- ������Ŀ
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
						//�����������
						closeInput('trialPlan',data.id);

						$("#projectName").val(data.projectName);
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
								$("#provinceHidden").val(trialProjectInfo.province);

								var customerTypeObj = $("#customerType");
								customerTypeObj.combobox('setValue',trialProjectInfo.customerTypeName);
								mulSelectSet(customerTypeObj);

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

					//�����������
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
								$("#provinceHidden").val(trialProjectInfo.province);

								var customerTypeObj = $("#customerType");
								customerTypeObj.combobox('setValue',trialProjectInfo.customerTypeName);
								mulSelectSet(customerTypeObj);

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

					//�����������
					openInput('trialPlan');
				}
			}
		}).attr('class','txt');
	}

	//������������
	function mulSelectSet(thisObj){
		thisObj.next().find("input").each(function(i,n){
			if($(this).attr('class') == 'combo-text validatebox-text'){
				$("#"+ thisObj.attr('id') + "Hidden").val(this.value);
			}
		});
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
		cityObj.combobox('setValue',dataArr.City);

		//�ͻ�����
		var customerTypeObj = $("#customerType");
		var valArr = [];
		valArr.push(dataArr.customerTypeName);
		customerTypeObj.combobox('setValues',valArr);

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

	//�����������
	function reloadCity(data) {
		var str;

		//������Ⱦ
		var cityObj = $('#city');
		cityObj.combobox({
			url : "?model=system_procity_city&action=listJson&tProvinceName=" + data
		});
	}


	//*********************** TODO �鿴���� *********************/
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
		var tableStr = '<table class="form_in_table" id="'+defaults.myId+'tbl">' +
				'<tr id="feeTypeTr">' +
					'<td class = "form_text_left_three"><span id="detailTypeTitle">��������</span></td>' +
					'<td class = "form_text_right" colspan="5">' +
						'���ŷ��� ' +
					'</td>' +
				'</tr>' +
				'<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">���ù�����˾</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.costBelongCom +
					'</td>' +
					'<td class = "form_text_left_three">���ù�������</td>' +
					'<td class = "form_text_right" colspan="3">' +
						objInfo.costBelongDeptName +
					'</td>' +
				'</tr>'
			'</table>';
		$("#"+defaults.myId).html(tableStr);
	}

	//��ʼ����ͬ��Ŀ
	function initProjectView(objInfo){
		var tableStr = '<table class="form_in_table" id="'+defaults.myId+'tbl">' +
				'<tr id="feeTypeTr">' +
					'<td class = "form_text_left_three"><span id="detailTypeTitle">��������</span></td>' +
					'<td class = "form_text_right" colspan="5">' +
						'��ͬ��Ŀ���� ' +
					'</td>' +
				'</tr>' +
				'<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">��Ŀ���</span></td>' +
					'<td class = "form_text_right_three">' +
						objInfo.projectCode +
					'</td>' +
					'<td class = "form_text_left_three">��Ŀ����</span></td>' +
					'<td class = "form_text_right_three">' +
						objInfo.projectName +
					'</td>' +
					'<td class = "form_text_left_three">��Ŀ����</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.proManagerName +
					'</td>' +
				'</tr>'
			'</table>';
		$("#"+defaults.myId).html(tableStr);
	}

	//��ʼ����ǰ
	function initSaleView(objInfo){
		var tableStr = "";
		tableStr = '<table class="form_in_table" id="'+defaults.myId+'tbl">' +
				'<tr id="feeTypeTr">' +
					'<td class = "form_text_left_three"><span id="detailTypeTitle">��������</span></td>' +
					'<td class = "form_text_right_three" colspan="5">' +
						'��ǰ���� ' +
					'</td>' +
				'</tr>' +
				'<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">������Ŀ���</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.projectCode +
					'</td>' +
					'<td class = "form_text_left_three">������Ŀ����</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.projectName +
					'</td>' +
					'<td class = "form_text_left_three">��Ŀ����</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.proManagerName +
					'</td>' +
				'</tr>' +
				'<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">�̻����</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.chanceCode +
					'</td>' +
					'<td class = "form_text_left_three">�̻�����</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.chanceName +
					'</td>' +
					'<td class = "form_text_left_three">�ͻ�����</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.customerName +
					'</td>' +
				'</tr>' +
				'<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">�ͻ�ʡ��</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.province +
					'</td>' +
					'<td class = "form_text_left_three">�ͻ�����</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.city +
					'</td>' +
					'<td class = "form_text_left_three">�ͻ�����</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.customerType +
					'</td>' +
				'</tr>' +
				'<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">���۸�����</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.costBelonger +
					'</td>' +
					'<td class = "form_text_left_three">���ù�������</td>' +
					'<td class = "form_text_right" colspan="3">' +
						objInfo.costBelongDeptName +
					'</td>'
				'</tr>'
			'</table>';
		$("#"+defaults.myId).html(tableStr);
	}

	//��ʼ���ۺ�
	function initContractView(objInfo){
		var tableStr = "";
		tableStr = '<table class="form_in_table" id="'+defaults.myId+'tbl">' +
				'<tr id="feeTypeTr">' +
					'<td class = "form_text_left_three"><span id="detailTypeTitle">��������</span></td>' +
					'<td class = "form_text_right" colspan="5">' +
						'�ۺ���� ' +
					'</td>' +
				'</tr>' +
			'</table>';
		$("#"+defaults.myId).html(tableStr);

		tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">��ͬ���</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.contractCode +
				'</td>' +
				'<td class = "form_text_left_three">��ͬ����</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.contractCode +
				'</td>' +
				'<td class = "form_text_left_three">�ͻ�����</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.customerName +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">�ͻ�ʡ��</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.province +
				'</td>' +
				'<td class = "form_text_left_three">�ͻ�����</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.city +
				'</td>' +
				'<td class = "form_text_left_three">�ͻ�����</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.customerType +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">���۸�����</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.costBelonger +
				'</td>' +
				'<td class = "form_text_left_three">���ù�������</td>' +
				'<td class = "form_text_right" colspan="3">' +
					objInfo.costBelongDeptName +
				'</td>'
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);
	}

	//********************* TODO �༭���� ************************/
	//��ʼ����������
	function initCostTypeEdit(thisObj,objInfo){
		initCostType(thisObj);
		//��ѡ��ֵ
		$("input[name='"+defaults.objName+"[detailType]']").each(function(i,n){
			if(this.value == objInfo.detailType){
				$(this).attr("checked",this);
				return false;
			}
		});
		$("#detailTypeTitle").html('��������').removeClass('red').addClass('blue');
		switch(objInfo.detailType){
			case '1' : initDeptEdit(objInfo);break;
			case '2' : initContractProjectEdit(objInfo);break;
			case '3' : initRdProjectEdit(objInfo);break;
			case '4' : initSaleEdit(objInfo);break;
			case '5' : initContractEdit(objInfo);break;
			default : break;
		}
	}

	//TODO ��ʼ������
	function initDeptEdit(objInfo){
		//��ʼֵ����
		var costBelongCom='',costBelongComId='',costBelongDeptName='',costBelongDeptId='',id='';
		if(objInfo){
			costBelongCom = objInfo.costBelongCom;
			costBelongComId = objInfo.costBelongComId;
			costBelongDeptName = objInfo.costBelongDeptName;
			costBelongDeptId = objInfo.costBelongDeptId;
			id = objInfo.id;
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">���ù�����˾</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+costBelongCom +'" readonly="readonly"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+costBelongComId +'"/>' +
					'<input type="hidden" name="'+defaults.objName+'[id]" value="'+id +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" value="'+costBelongDeptName +'" readonly="readonly"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
				'</td>' +
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);
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

	// TODO ��ʼ����ͬ��Ŀ
	function initContractProjectEdit(objInfo){
		//��ʼֵ����
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
				'<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[projectCode]" readonly="readonly" value="'+projectCode +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly" value="'+projectName +'"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" value="'+projectId +'"/>' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" value="'+projectType +'"/>' +
					'<input type="hidden" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" value="'+costBelongDeptName +'"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
					'<input type="hidden" name="'+defaults.objName+'[id]" value="'+id +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" value="'+proManagerId +'"/>' +
				'</td>' +
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

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


		//������Ŀ��Ⱦ
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
						//���÷��ù�������
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

					//���÷��ù�������
					$("#costBelongDeptId").val('');
					$("#costBelongDeptName").val('');
				}
			}
		});
	}

	//TODO ��ʼ���з���Ŀ
	function initRdProjectEdit(objInfo){
		//��ʼֵ����
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
				'<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[projectCode]" readonly="readonly" value="'+projectCode +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly" value="'+projectName +'"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" value="'+projectId +'"/>' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" value="'+projectType +'"/>' +
					'<input type="hidden" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" value="'+costBelongDeptName +'"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" value="'+costBelongDeptId +'"/>' +
					'<input type="hidden" name="'+defaults.objName+'[id]" value="'+id +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" value="'+proManagerId +'"/>' +
				'</td>' +
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

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

		//�з���Ŀ��Ⱦ
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

						//���÷��ù�������
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

					//���÷��ù�������
					$("#costBelongDeptId").val('');
					$("#costBelongDeptName").val('');
				}
			}
		});
	}

	//TODO ��ʼ����ǰ����
	function initSaleEdit(objInfo){
		//��ʼֵ����
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
				'<td class = "form_text_left_three">������Ŀ���</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[projectCode]" readonly="readonly" value="'+projectCode+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">������Ŀ����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly" value="'+projectName+'"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" value="'+projectId+'"/>' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" value="'+projectType+'"/>' +
					'<input type="hidden" id="costBelongCom" name="'+defaults.objName+'[costBelongCom]" value="'+ costBelongCom +'"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[costBelongComId]" value="'+ costBelongComId +'"/>' +
					'<input type="hidden" name="'+defaults.objName+'[id]" value="'+id +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">��Ŀ����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" value="'+ proManagerName +'" readonly="readonly"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" value="'+ proManagerId +'"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">�̻����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="chanceCode" name="'+defaults.objName+'[chanceCode]" value="'+ chanceCode +'"/>' +
					'<input type="hidden" id="chanceId" name="'+defaults.objName+'[chanceId]" value="'+ chanceId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">�̻�����</td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="txt" id="chanceName" name="'+defaults.objName+'[chanceName]" value="'+ chanceName +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">�ͻ�����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="customerName" name="'+defaults.objName+'[customerName]" value="'+ customerName +'"/>' +
					'<input type="hidden" id="customerId" name="'+defaults.objName+'[customerId]" value="'+ customerId +'"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">�ͻ�ʡ��</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="province" name="'+defaults.objName+'[province]" value="'+ province +'" style="width:202px;"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">�ͻ�����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="city" name="'+defaults.objName+'[city]" value="'+ city +'" style="width:202px;"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">�ͻ�����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="customerType" name="'+defaults.objName+'[customerType]" value="'+ customerType +'" style="width:202px;"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">���۸�����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="costBelonger" name="'+defaults.objName+'[costBelonger]" value="'+ costBelonger +'" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongerId" name="'+defaults.objName+'[costBelongerId]" value="'+ costBelongerId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" value="'+ costBelongDeptName +'" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" value="'+ costBelongDeptId +'"/>' +
				'</td>'
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

		//�̻����
		var codeObj = $("#chanceCode");
		if(codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "�����������̻���ţ�ϵͳ�Զ�ƥ�������Ϣ";
		var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻����'>&nbsp;</span>");
		$button.click(function(){
			if(codeObj.val() == ""){
				alert('������һ���̻����');
				return false;
			}
		});

		//�����հ�ť
		var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
		$button2.click(function(){
			if(codeObj.val() != ""){
				//���������Ϣ
				clearSale();
				openInput('chance');
			}
		});
		codeObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');

		//�̻�����
		var nameObj = $("#chanceName");
		if(nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "�����������̻����ƣ�ϵͳ�Զ�ƥ�������Ϣ";
		var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻�����'>&nbsp;</span>");
		$button.click(function(){
			if(nameObj.val() == ""){
				alert('������һ���̻�����');
				return false;
			}
		});

		//�����հ�ť
		var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
		$button2.click(function(){
			if(nameObj.val() != ""){
				//���������Ϣ
				clearSale();
				openInput('chance');
			}
		});
		nameObj.bind('blur',{thisType: 'chance'},getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');

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
					$("#projectType").val('');
					clearSale();

					//�����������
					openInput('trialPlan');
				}
			}
		}).attr('class','txt');

		//��Ŀ���
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
						//�����������
						closeInput('trialPlan',data.id);

						$("#projectName").val(data.projectName);
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
								$("#provinceHidden").val(trialProjectInfo.province);

								var customerTypeObj = $("#CustomerType");
								customerTypeObj.combobox('setValue',trialProjectInfo.customerTypeName);
								mulSelectSet(customerTypeObj);

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

					//�����������
					openInput('trialPlan');
				}
			}
		}).attr('class','txt');

		//��ʼ���ͻ�
		initCustomer();

		//�ͻ�����
		var customerTypeArr = '';
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
	        	if(customerTypeArr.indexOf(obj.text) == -1){
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
		var cityObj = $('#city');
		provinceObj.combobox({
			url:'index1.php?model=system_procity_province&action=listJsonSort',
			valueField:'provinceName',
	        textField:'provinceName',
			editable : false,
			onSelect : function(obj){
				//���ö����µ�ѡ����
				$("#provinceHidden").val(obj.provinceName);
				//����ʡ�ݶ�ȡ����
				cityObj.combobox({
					url : "?model=system_procity_city&action=listJson&tProvinceName=" + obj.provinceName
				});
			}
		});

		//������Ⱦ
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

		//����һ�ν��ô���
		closeInput();
		//����һ���������۸�����
		changeCustomerType();
	}

	//TODO ��ʼ���ۺ����
	function initContractEdit(objInfo){
		//��ʼֵ����
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
				'<td class = "form_text_left_three"><span class="blue">��ͬ���</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="contractCode" name="'+defaults.objName+'[contractCode]" value="'+contractCode+'"/>' +
					'<input type="hidden" id="contractId" name="'+defaults.objName+'[contractId]" value="'+contractId+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��ͬ����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="contractName" name="'+defaults.objName+'[contractName]" value="'+contractName+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">�ͻ�����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="customerName" name="'+defaults.objName+'[customerName]" readonly="readonly" value="'+customerName+'"/>' +
					'<input type="hidden" id="customerId" name="'+defaults.objName+'[customerId]" value="'+customerId+'"/>' +
					'<input type="hidden" name="'+defaults.objName+'[id]" value="'+id +'"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">�ͻ�ʡ��</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="province" name="'+defaults.objName+'[province]" readonly="readonly" value="'+province+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">�ͻ�����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="city" name="'+defaults.objName+'[city]" readonly="readonly" value="'+city+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">�ͻ�����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="customerType" name="'+defaults.objName+'[customerType]" readonly="readonly" value="'+customerType+'"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">���۸�����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="costBelonger" name="'+defaults.objName+'[costBelonger]" readonly="readonly" value="'+costBelonger+'"/>' +
					'<input type="hidden" id="costBelongerId" name="'+defaults.objName+'[costBelongerId]" value="'+costBelongerId+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
				'<td class = "form_text_right" colspan="3">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[costBelongDeptName]" style="width:202px;" value="'+costBelongDeptName+'"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[costBelongDeptId]" value="'+costBelongDeptId+'"/>' +
				'</td>'
			'</tr>';
		$("#"+defaults.myId + "tbl").append(tableStr);

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

		//���������Ⱦ
		var codeObj = $("#contractCode");
		if(codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "���������ĺ�ͬ��ţ�ϵͳ�Զ�ƥ�������Ϣ";
		var $button = $("<span class='search-trigger' id='contractCodeSearch' title='��ͬ���'>&nbsp;</span>");
		$button.click(function(){
			if($("#" + thisId).val() == ""){
				alert('������һ����ͬ���');
				return false;
			}
		});

		//�����հ�ť
		var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
		$button2.click(function(){
			$(".ciClass").val('');
		});
		codeObj.bind('blur',getContractInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true);

		//����������Ⱦ
		var nameObj = $("#contractName");
		if(nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true){
			return false;
		}
		var title = "���������ĺ�ͬ���ƣ�ϵͳ�Զ�ƥ�������Ϣ";
		var $button = $("<span class='search-trigger' id='contractCodeSearch' title='��ͬ����'>&nbsp;</span>");
		$button.click(function(){
			if($("#" + thisId).val() == ""){
				alert('������һ����ͬ����');
				return false;
			}
		});

		//�����հ�ť
		var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
		$button2.click(function(){
			$(".ciClass").val('');
		});
		nameObj.bind('blur',getContractInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true);
	}
	//************************* ����֤ ****************************/
	//����֤����
	function costCheckForm(){
		var detailType = $("input[name='" +defaults.objName+ "[detailType]']:checked").val();
		if(detailType){
			//���� ��Ӧ������֤
			switch(detailType){
				case '1' :
					var costBelongCom = $("#costBelongCom").val();
					if(costBelongCom == ""){
						alert("û����д���ù�����˾");
						return false;
					}
					var costBelongDeptName = $("#costBelongDeptName").val();
					if(costBelongDeptName == ""){
						alert("û����д���ù�������");
						return false;
					}
					break;
				case '2' :
					var projectCode = $("#projectCode").val();
					if(projectCode == ""){
						alert("��ѡ��ñʷ������ڹ�����Ŀ");
						return false;
					}
					break;
				case '3' :
					var projectCode = $("#projectCode").val();
					if(projectCode == ""){
						alert("��ѡ��ñʷ��������з���Ŀ");
						return false;
					}
					break;
				case '4' :
					var province = $("#province").combobox('getValue');
					if(province == ""){
						alert("��ѡ��ͻ�����ʡ��");
						return false;
					}
					var city = $("#city").combobox('getValues');
					if(city == ""){
						alert("��ѡ��ͻ����ڳ���");
						return false;
					}
					var customerType = $("#customerType").combobox('getValues');
					if(customerType == ""){
						alert("��ѡ��ͻ�����");
						return false;
					}
					var costBelongerId = $("#costBelongerId").val();
					if(costBelongerId == ""){
						alert("��¼�����۸����ˣ����۸����˿����̻����ͻ������Զ�����������ͨ���ͻ�ʡ�ݡ����С�������ϵͳƥ��");
						return false;
					}
					var costBelongDeptId = $("#costBelongDeptId").val();
					var costBelongDeptName = $("#costBelongDeptName").combobox('getValue');
					if(costBelongDeptId == "" || costBelongDeptName ==""){
						alert("��ѡ����ù�������");
						return false;
					}
					break;
				case '5' :
					var contractCode = $("#contractCode").val();
					if(contractCode == ""){
						alert("��ѡ��ñʷ��ù�����ͬ");
						return false;
					}
					var costBelongDeptId = $("#costBelongDeptId").val();
					var costBelongDeptName = $("#costBelongDeptName").combobox('getValue');
					if(costBelongDeptId == "" || costBelongDeptName ==""){
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

	$.fn.costbelong = function(options){
		//�ϲ�����
		var options = $.extend(defaults,options);
		//֧��ѡ�����Լ���ʽ����
		return this.each(function(){
			//��ֵһ������
			defaults.myId = this.id;
			var thisObj = $(this);//�Լ��Ķ���

			//�����������,��ô��ȡһ������
			if(defaults.actionType != 'add'){
				//ajax��ȡ���۸�����
				var responseText = $.ajax({
					url:defaults.url,
					data : defaults.data,
					type : "POST",
					async : false
				}).responseText;
				var objInfo = eval("(" + responseText + ")");
			}
			if(defaults.actionType == 'view'){
				//��ʼ����������
				initCostTypeView(objInfo);
			}else{
				if(defaults.actionType == 'add'){
					initCostType(thisObj);
				}else if(defaults.actionType == 'edit'){
					initCostTypeEdit(thisObj,objInfo);
				}

				//�󶨱���֤����
				if(defaults.isRequired == true)
					$("form").bind('submit',costCheckForm);
			}
		});
	};
})(jQuery);
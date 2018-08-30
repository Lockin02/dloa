//���ù������
(function($){
	//Ĭ������
	var defaults = {
		getId : 'id', //ȡ����ѯ����id
		objName : 'objName', //ҵ�����
		actionType : 'add', //�������� add edit view create,Ĭ��add
		url : '', //ȡ��url
		data : {},//��������
		isCompanyReadonly : true, //��˾�Ƿ�ֻ��
		isCompanyDefault : false, //��˾�Ƿ�Ĭ��
		company : '���Ͷ���', //Ĭ�Ϲ�˾ֵ
		companyId : 'dl' //Ĭ�Ϲ�˾ֵ
	};

	//���ù����������� - ���ڻ�������
	var expenseSaleDept;
	var expenseContractDept;
	var expenseTrialProjectFeeDept;

	//================== �ڲ����� ====================//
	//��ʼ����������
	function initCostType(thisObj,objInfo){
		if(objInfo){
			var CostDateBegin = objInfo.CostDateBegin;
			var CostDateEnd = objInfo.CostDateEnd;
			var days = objInfo.days;
			var InputManName = objInfo.InputManName;
			var InputMan = objInfo.InputMan;
			var deptId = objInfo.CostDepartID;
			var deptName = objInfo.CostDepartName;
			var companyId = objInfo.CostManComId;
			var companyName = objInfo.CostManCom;
			var purpose = objInfo.Purpose;
		}else{
			var CostDateBegin = CostDateEnd = $("#thisDate").val();
			var days = 1;
			var InputManName = $("#InputManName").val();
			var InputMan = $("#InputMan").val();
			var deptId = $("#deptId").val();
			var deptName = $("#deptName").val();
			var companyId = $("#companyId").val();
			var companyName = $("#companyName").val();
			var templateId = $("#templateId").val();
			var templateName = $("#templateName").val();
			var purpose = '';
		}
		var fileUrl = $("#fileUrl").val();
		var tableStr = '<table class="form_in_table" id="'+ defaults.myId +'tbl">' +
				'<tr id="feeTypeTr">' +
					'<td class = "form_text_left_three"><span id="detailTypeTitle" class="red">��ѡ���������</span></td>' +
					'<td class = "form_text_right" colspan="5">' +
						'<input type="radio" name="'+defaults.objName+'[DetailType]" value="1"/> ���ŷ��� ' +
						'<input type="radio" name="'+defaults.objName+'[DetailType]" value="2"/> ��ͬ��Ŀ���� ' +
						'<input type="radio" name="'+defaults.objName+'[DetailType]" value="3"/> �з����� ' +
						'<input type="radio" name="'+defaults.objName+'[DetailType]" value="4"/> ��ǰ���� ' +
						'<input type="radio" name="'+defaults.objName+'[DetailType]" value="5"/> �ۺ���� ' +
						'&nbsp;&nbsp;' +
						'<img src="images/icon/view.gif"/>' +
						'<a href="'+fileUrl+'" title="����˵��" taget="_blank" id="fileId" onclick="getFile();">����˵��</a>' +
						'<span class="blue" id="tipsView"></span>' +
					'</td>' +
				'</tr>' +
				'<tr>' +
					'<td class="form_text_left_three"><span class="blue">�����ڼ�</span></td>' +
					'<td class="form_text_right" colspan="5">' +
						'<input type="text" class="txtmiddle Wdate" name="'+defaults.objName+'[CostDateBegin]" id="CostDateBegin" onfocus="WdatePicker()" value="'+CostDateBegin+'"/>' +
						'&nbsp;��&nbsp;' +
						'<input type="text" class="txtmiddle Wdate" name="'+defaults.objName+'[CostDateEnd]" id="CostDateEnd" onfocus="WdatePicker()" value="'+CostDateEnd+'"/>' +
						'&nbsp;��&nbsp;' +
						'<input type="text" class="rimless_textB" style="width:50px;text-align:center" name="'+defaults.objName+'[days]" id="days" value="'+days+'"/>' +
						'<input type="hidden" id="periodDays" value="'+days+'"/>' +
						'&nbsp;��' +
					'</td>' +
				'</tr>' +
				'<tr>' +
					'<td class="form_text_left_three"><span class="blue">�� ��</span></td>' +
					'<td class="form_text_right" colspan="5">' +
						'<input type="text"  class="rimless_textB" style="width:760px" name="'+defaults.objName+'[Purpose]" id="Purpose" value="'+purpose+'"/>' +
					'</td>' +
				'</tr>' +
				'<tr id="baseTr">' +
					'<td class="form_text_left_three"><span class="blue">������Ա</span></td>' +
					'<td class="form_text_right">' +
						'<input type="text" class="txt" name="'+defaults.objName+'[CostManName]" id="CostManName" value="'+InputManName+'" readonly="readonly"/>' +
						'<input type="hidden" name="'+defaults.objName+'[CostMan]" id="CostMan" value="'+InputMan+'"/>' +
					'</td>' +
					'<td class="form_text_left_three">�����˲���</td>' +
					'<td class="form_text_right">' +
						'<input type="text" class="readOnlyTxtNormal" name="'+defaults.objName+'[CostDepartName]" id="CostDepartName" value="'+deptName+'" readonly="readonly"/>' +
						'<input type="hidden" name="'+defaults.objName+'[CostDepartID]" id="CostDepartID" value="'+deptId+'"/>' +
					'</td>' +
					'<td class="form_text_left_three">�����˹�˾</td>' +
					'<td class="form_text_right">' +
						'<input type="text" name="'+defaults.objName+'[CostManCom]" id="CostManCom" class="readOnlyTxtNormal" value="'+companyName+'" readonly="readonly"/>' +
						'<input type="hidden" name="'+defaults.objName+'[CostManComId]" id="CostManComId" value="'+companyId+'"/>' +
					'</td>' +
				'</tr>'+
				'<tr>' +
					'<td class="form_text_left_three">ͬ �� ��</td>' +
					'<td class="form_text_right_three">' +
						'<input type="text" class="txt" readonly="readonly" name="'+defaults.objName+'[memberNames]" id="memberNames"/>' +
						'<input type="hidden" name="'+defaults.objName+'[memberIds]" id="memberIds"/>' +
					'</td>' +
					'<td class="form_text_left_three">ͬ������</td>' +
					'<td class="form_text_right" colspan="3" id="memberNumberTr">' +
						'<input type="text" class="txt" name="'+defaults.objName+'[memberNumber]" id="memberNumber"/>' +
					'</td>' +
				'</tr>';
		//������ȡ
		var fileInfo = '';
		var fileInfoObj = $("#fileInfo");
		if(fileInfoObj.length > 0){
			fileInfo = fileInfoObj.html();
		}
		if(objInfo){
			tableStr +=	'<tr>' +
					'<td class="form_text_left_three">�� ��</td>' +
					'<td class="form_text_right" colspan="5">' +
						'<div class="upload">' +
							'<div class="upload" id="fsUploadProgress"></div>' +
							'<div class="upload"><span id="swfupload"></span>' +
								'<input id="btnCancel" type="button" value="��ֹ�ϴ�" onclick="cancelQueue(uploadfile);" disabled="disabled" /><br />' +
							'</div>' +
							'<div id="uploadfileList" class="upload">'+fileInfo+'</div>' +
						'</div>' +
					'</td>' +
				'</tr>' +
			'</table>';
		}else{
			tableStr +=	'<tr>' +
					'<td class="form_text_left_three">����ģ��</td>' +
					'<td class="form_text_right">' +
						'<input type="text" class="txt" name="'+defaults.objName+'[ModelTypeName]" id="modelTypeName" value="'+templateName+'" readonly="readonly"/>' +
						'<input type="hidden" name="'+defaults.objName+'[modelType]" id="modelType" value="'+templateId+'" />' +
					'</td>' +
					'<td class="form_text_left_three">�� ��</td>' +
					'<td class="form_text_right" colspan="3">' +
						'<div class="upload">' +
							'<div class="upload" id="fsUploadProgress"></div>' +
							'<div class="upload"><span id="swfupload"></span>' +
								'<input id="btnCancel" type="button" value="��ֹ�ϴ�" onclick="cancelQueue(uploadfile);" disabled="disabled" /><br />' +
							'</div>' +
							'<div id="uploadfileList" class="upload">'+fileInfo+'</div>' +
						'</div>' +
					'</td>' +
				'</tr>' +
			'</table>';
		}
		$(thisObj).html(tableStr);
		$("input[name='"+defaults.objName+"[DetailType]']").each(function(){
			$(this).bind('click',resetCombo);
			//���������Ͱ󶨴����¼�
			switch(this.value){
				case '1' : $(this).bind('click',initDept);break;
				case '2' : $(this).bind('click',initContractProject);break;
				case '3' : $(this).bind('click',initRdProject);break;
				case '4' : $(this).bind('click',initSale);break;
				case '5' : $(this).bind('click',initContract);break;
				default : break;
			}
		});

		//ģ����Ⱦ
		$("#modelTypeName").yxcombogrid_expensemodel({
			hiddenId :  'modelType',
			isFocusoutCheck : false,
			height : 300,
			isShowButton : true,
			isClear : false,
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e, row, data){
						initTemplate(data.modelType);
					}
				}
			}
		});

		//�ڼ�ʱ���
		$("#CostDateBegin").bind('blur',actTimeCheck);
		$("#CostDateEnd").bind('blur',actTimeCheck);
		$("#days").bind('blur',periodDaysCheck);

		// ���б�����
		if($("#allApply").val() == "1"){
			$("#CostManName").yxselect_user({
				isGetDept : [true, "CostDepartID", "CostDepartName"],
				hiddenId : 'CostMan',
				formCode : 'expense',
				event : {
					"select" : function(obj, row) {
						$("#CostBelongDeptName").val(row.deptName);
						$("#CostBelongDeptId").val(row.deptId);

						// ��˾����
						$("#CostManComId").val(row.companyCode);
						$("#CostManCom").val(row.companyName);
						$("#CostBelongComId").val(row.companyCode);
						$("#CostBelongCom").val(row.companyName);
					}
				}
			});
		}

		// ��Ⱦͬ����
		$("#memberNames").yxselect_user({
			hiddenId : 'memberIds',
			formCode : 'expenseMember',
			mode : 'check',
			event : {
				"select" : function(obj, row) {
					if(row != undefined){
						if(row.val != ''){
							var memberArr = row.val.split(',');
							$("#memberNumber").val(memberArr.length);
						}else{
							$("#memberNumber").val('');
						}
					}
				},
				"clearReturn" : function(){
					$("#memberNumber").val('');
				}
			}
		});

		//����
		if(objInfo){
			uploadfile=createSWFUpload({
				"serviceId" : objInfo.ID,
				"serviceNo" : objInfo.BillNo,
				"serviceType":"cost_summary_list"//ҵ��ģ����룬һ��ȡ����
			});
		}else{
			uploadfile=createSWFUpload({
				"serviceType":"cost_summary_list"//ҵ��ģ����룬һ��ȡ����
			});
		}
	}

	//���ø�������
	function resetCombo(){
		$("#detailTypeTitle").html('��������').removeClass('red').addClass('blue');
		$("#projectName").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
		$("#projectCode").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
		$("#costBelongCom").yxcombogrid_branch('remove');
		$(".feeTypeContent").remove();
		//���������
		clearWorkTeam();
	}

	//���ù�����
	function initWorkTeam(objInfo){
		var projectName = projectId = projectCode = proManagerName = proManagerId = projectType = '';
		if(objInfo){
			projectName = objInfo.projectName;
			projectId = objInfo.projectId;
			projectCode = objInfo.ProjectNo;
			proManagerName = objInfo.proManagerName;
			proManagerId = objInfo.proManagerId;
			projectType = objInfo.projectType;
		}
		//����ʡ��
		var str="<td class='form_text_left_three workTeamShow'>" +
				"<span class='blue'>�� �� ��</span>" +
			"</td>" +
			"<td class='form_text_right_three workTeamShow'>" +
				"<input class='txt' name='"+defaults.objName+"[projectName]' id='projectName' value='"+ projectName +"' readonly='readonly'/>" +
				"<input type='hidden' name='"+defaults.objName+"[projectId]' id='projectId' value='"+ projectId +"'/>" +
				"<input type='hidden' name='"+defaults.objName+"[ProjectNo]' id='projectCode' value='"+ projectCode +"'/>" +
				"<input type='hidden' name='"+defaults.objName+"[proManagerName]' id='proManagerName' value='"+ proManagerName +"'/>" +
				"<input type='hidden' name='"+defaults.objName+"[proManagerId]' id='proManagerId' value='"+ proManagerId +"'/>" +
				"<input type='hidden' name='"+defaults.objName+"[projectType]' id='projectType' value='"+ projectType +"'/>" +
			"</td>";
		//��д���Ÿ�
		$("#memberNumberTr").attr("colspan",1).attr("class","form_text_right_three").after(str);
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
				param : {'productLine' : 'GCSCX-05' , 'statusArr' : 'GCXMZT02,GCXMZT01'},
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
	}

	//���ù�����
	function initWorkTeamView(objInfo){
		//����ʡ��
		var str="<td class='form_text_left_three workTeamShow'>" +
				"�� �� ��" +
			"</td>" +
			"<td class='form_text_right_three workTeamShow'>" +
				objInfo.projectName +
			"</td>";
		//��д���Ÿ�
		$("#memberNumberTr").attr("colspan",1).attr("class","form_text_right_three").after(str);
	}

	//ȡ��������
	function clearWorkTeam(){
		//��д���Ÿ�
		$("#memberNumberTr").attr("colspan",3).attr("class","form_text_right");
		$(".workTeamShow").remove();
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
		if(defaults.isCompanyDefault == true){
			thisCompany = defaults.company;
			thisCompanyId = defaults.companyId;
		}else{
			thisCompany = $("#CostManCom").val();
			thisCompanyId = $("#CostManComId").val();
		}

		//Ĭ�ϻ�ȡ
		var deptId = $("#CostDepartID").val();
		var deptName = $("#CostDepartName").val();

		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">���ù�����˾</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="'+ thisClass + '" id="costBelongCom" name="'+defaults.objName+'[CostBelongCom]" value="'+thisCompany+'" readonly="readonly"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[CostBelongComId]" value="'+thisCompanyId+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
				'<td class = "form_text_right" colspan="3" id="feeDept">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[CostBelongDeptName]" value="'+ deptName +'" readonly="readonly"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[CostBelongDeptId]" value="'+ deptId +'"/>' +
				'</td>' +
			'</tr>';
		$("#baseTr").after(tableStr);

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
			hiddenId : 'costBelongDeptId',
			event : {
				'selectReturn' : function(e,obj){
					//ajax��ȡ���۸�����
					var responseText = $.ajax({
						url:'index1.php?model=finance_expense_expense&action=deptIsNeddProvince',
						data : { "deptId" : obj.val },
						type : "POST",
						async : false
					}).responseText;
					//��ʼ��
					initCheckDept(responseText);
				}
			}
		});

		//��Ҫ���ż��Ĳ�����Ⱦʡ��
		initCheckDept();
		//��Ⱦ������
		initWorkTeam();
		//������ʾ����
		$("#tipsView").html('');
	}

	//����һ����Ҫ���Ĳ�����չ����
	function initCheckDept(deptIsNeedProvince,objInfo){
		//���û�����ж�ֵ�������л�ȡ
		if(deptIsNeedProvince == undefined){
			deptIsNeedProvince = $("#deptIsNeedProvince").val();
		}else{
			$("#deptIsNeedProvince").val(deptIsNeedProvince);
		}
		var province = '';
		if(objInfo){
			province = objInfo.province;
		}
		//�����Ҫ���ż��
		if(deptIsNeedProvince == "1"){
			//����ʡ��
			var str="<td class='form_text_left_three' id='feeDeptProvinceShow'><span class='blue'>����ʡ��</span></td><td class='form_text_right_three' id='feeDeptProvince'><input class='txt' name='"+defaults.objName+"[province]' id='province' value='"+province+"' style='width:202px;'/></td>";
			//��д���Ÿ�
			$("#feeDept").attr("colspan",1).attr("class","form_text_right_three").after(str);
			$('#province').combobox({
				url:'index1.php?model=system_procity_province&action=listJsonSort',
				valueField:'provinceName',
		        textField:'provinceName',
		        editable : false
			});
		}else if(deptIsNeedProvince == "0"){
			//���
			clearCheckDept();
		}
	}

	//������Ҫ���Ĳ�����չ���� - �鿴
	function initCheckDeptView(objInfo){
		if(objInfo.province){
			//����ʡ��
			var str="<td class='form_text_left_three' id='feeDeptProvinceShow'>����ʡ��</td><td class='form_text_right_three' id='feeDeptProvince'>"+objInfo.province+"</td>";
			//��д���Ÿ�
			$("#feeDept").attr("colspan",1).attr("class","form_text_right_three").after(str);
		}
	}

	//������Ҫ���Ĳ�����չ����
	function clearCheckDept(){
		//�����Ҫ���ż��
		var provinceObj = $('#province');
		if(provinceObj.length == 1){
			//��ȥ��̬���
			$("#feeDeptProvinceShow").remove();
			$("#feeDeptProvince").remove();
			//��д���Ÿ�
			$("#feeDept").attr("colspan",3).attr("class","form_text_right");

			//���province ֵ
			$('#province').combobox('setValue','');
		}
	}

	//��ʼ����ͬ��Ŀ TODO
	function initContractProject(){
		var thisCompany,thisCompanyId;
		if(defaults.isCompanyDefault == true){
			thisCompany = defaults.company;
			thisCompanyId = defaults.companyId;
		}else{
			thisCompany = $("#companyName").val();
			thisCompanyId = $("#companyId").val();
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[ProjectNo]" readonly="readonly"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" />' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" id="projectType"/>' +
					'<input type="hidden" id="costBelongDeptName" name="'+defaults.objName+'[CostBelongDeptName]" />' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[CostBelongDeptId]" />' +
					'<input type="hidden" id="costBelongCom" name="'+defaults.objName+'[CostBelongCom]" value="'+ thisCompany +'"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[CostBelongComId]" value="'+ thisCompanyId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" />' +
				'</td>' +
			'</tr>';
		$("#baseTr").after(tableStr);

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
		//������ʾ����
		$("#tipsView").html('');
	}

	//��ʼ���з���Ŀ TODO
	function initRdProject(){
		var thisCompany,thisCompanyId;
		if(defaults.isCompanyDefault == true){
			thisCompany = defaults.company;
			thisCompanyId = defaults.companyId;
		}else{
			thisCompany = $("#companyName").val();
			thisCompanyId = $("#companyId").val();
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[ProjectNo]" readonly="readonly"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" />' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" id="projectType"/>' +
					'<input type="hidden" id="costBelongDeptName" name="'+defaults.objName+'[CostBelongDeptName]" />' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[CostBelongDeptId]" />' +
					'<input type="hidden" id="costBelongCom" name="'+defaults.objName+'[CostBelongCom]" value="'+ thisCompany +'"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[CostBelongComId]" value="'+ thisCompanyId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" />' +
				'</td>' +
			'</tr>';
		$("#baseTr").after(tableStr);

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
		//������ʾ����
		$("#tipsView").html('');
	}

	//��ʼ����ǰ TODO ��ǰ
	function initSale(){
		var thisCompany,thisCompanyId;
		if(defaults.isCompanyDefault == true){
			thisCompany = defaults.company;
			thisCompanyId = defaults.companyId;
		}else{
			thisCompany = $("#companyName").val();
			thisCompanyId = $("#companyId").val();
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">������Ŀ���</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[ProjectNo]" readonly="readonly"/>' +
				'</td>' +
				'<td class = "form_text_left_three">������Ŀ����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" />' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" />' +
					'<input type="hidden" id="costBelongCom" name="'+defaults.objName+'[CostBelongCom]" value="'+ thisCompany +'"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[CostBelongComId]" value="'+ thisCompanyId +'"/>' +
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
					'<input type="text" class="txt" id="customerType" name="'+defaults.objName+'[CustomerType]" style="width:202px;"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class="form_text_left_three">�ͻ�����</td>' +
				'<td class="form_text_right_three">' +
					'<input type="text" class="txt" name="'+defaults.objName+'[customerDept]" id="customerDept"/>' +
				'</td>'+
				'<td class = "form_text_left_three"><span class="blue">���۸�����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="costBelonger" name="'+defaults.objName+'[CostBelonger]" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongerId" name="'+defaults.objName+'[CostBelongerId]" />' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[CostBelongDeptName]" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[CostBelongDeptId]" />' +
				'</td>'
			'</tr>';
		$("#baseTr").after(tableStr);

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
		//����˵��
		$("#tipsView").html('����ʾ���̻����/����¼����ɺ�ϵͳ���Զ�������Ӧ��Ϣ��');
	}

	//��ʼ���ۺ�
	function initContract(){
		var thisCompany,thisCompanyId;
		if(defaults.isCompanyDefault == true){
			thisCompany = defaults.company;
			thisCompanyId = defaults.companyId;
		}else{
			thisCompany = $("#companyName").val();
			thisCompanyId = $("#companyId").val();
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">��ͬ���</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt ciClass" id="contractCode" name="'+defaults.objName+'[contractCode]"/>' +
					'<input type="hidden" class="ciClass" id="contractId" name="'+defaults.objName+'[contractId]" />' +
					'<input type="hidden" class="ciClass" id="costBelongCom" name="'+defaults.objName+'[CostBelongCom]" value="'+ thisCompany +'"/>' +
					'<input type="hidden" class="ciClass" id="costBelongComId" name="'+defaults.objName+'[CostBelongComId]" value="'+ thisCompanyId +'"/>' +
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
					'<input type="text" class="readOnlyTxtNormal ciClass" id="customerType" name="'+defaults.objName+'[CustomerType]" readonly="readonly"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class="form_text_left_three">�ͻ�����</td>' +
				'<td class="form_text_right_three">' +
					'<input type="text" class="txt" name="'+defaults.objName+'[customerDept]" id="customerDept"/>' +
				'</td>'+
				'<td class = "form_text_left_three">���۸�����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="'+defaults.objName+'[CostBelonger]" readonly="readonly"/>' +
					'<input type="hidden" class="ciClass" id="costBelongerId" name="'+defaults.objName+'[CostBelongerId]" />' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[CostBelongDeptName]" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[CostBelongDeptId]" />' +
				'</td>'
			'</tr>';
		$("#baseTr").after(tableStr);

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
		//����˵��
		$("#tipsView").html('����ʾ����ͬ���/����¼����ɺ�ϵͳ���Զ�������Ӧ��Ϣ��');
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


	//*********************** �鿴���� *********************/
	//��ʼ����������
	function initCostTypeView(objInfo){
		if(objInfo.DetailType){
			//��ʼ����ͬ����
			initViewHead(objInfo);
			switch(objInfo.DetailType){
				case '1' : initDeptView(objInfo);break;
				case '2' : initProjectView(objInfo);break;
				case '3' : initProjectView(objInfo);break;
				case '4' : initSaleView(objInfo);break;
				case '5' : initContractView(objInfo);break;
				default : break;
			}
		}
	}

	//��ʼ���鿴ͷ
	function initViewHead(objInfo){
		var deT = '';
		switch(objInfo.DetailType){
			case '1' : deT = '���ŷ���';break;
			case '2' : deT = '��ͬ��Ŀ����';break;
			case '3' : deT = '�з�����';break;
			case '4' : deT = '��ǰ����';break;
			case '5' : deT = '�ۺ����';break;
			default : break;
		}
		//������ȡ
		var fileInfo = '';
		var fileInfoObj = $("#fileInfo");
		if(fileInfoObj.length > 0){
			fileInfo = fileInfoObj.html();
		}
		var isEditPurpose = '';
		if($("#isEdit").length > 0){
			isEditPurpose = '<img src="images/changeedit.gif" title="�޸�����" onclick="openSavePurpose()";/>';
		}
		var tableStr = '<table class="form_in_table" id="'+defaults.myId+'tbl">' +
				'<tr id="feeTypeTr">' +
					'<td class = "form_text_left_three"><span id="detailTypeTitle">��������</span></td>' +
					'<td class = "form_text_right" colspan="5">' +
						deT +
					'</td>' +
				'</tr>' +
				'<tr>' +
					'<td class="form_text_left_three">�����ڼ�</td>' +
					'<td class="form_text_right" colspan="5">' +
						'<span class="blue">' + objInfo.CostDateBegin + '</span> �� ' +
						'<span class="blue">' + objInfo.CostDateEnd + '</span> �� ' +
						'<span class="blue">' + objInfo.days + '</span>' +
						' �� <span id="mainTitle"></span>'+
					'</td>' +
				'</tr>' +
				'<tr>' +
					'<td class="form_text_left_three">�� ��</td>' +
					'<td class="form_text_right" colspan="5">' +
						isEditPurpose +
						'<span id="PurposeShow">' + objInfo.Purpose + '<span/>' +
					'</td>' +
				'</tr>' +
				'<tr id="baseTr">' +
					'<td class="form_text_left_three">������Ա</td>' +
					'<td class="form_text_right_three">'+
						objInfo.CostManName +
					'</td>' +
					'<td class="form_text_left_three">�����˲���</td>' +
					'<td class="form_text_right_three">'+
						objInfo.CostDepartName +
					'</td>' +
					'<td class="form_text_left_three">�����˹�˾</td>' +
					'<td class="form_text_right_three">'+
						objInfo.CostManCom +
					'</td>' +
				'</tr>' +
				'<tr>' +
					'<td class="form_text_left_three">ͬ �� ��</td>' +
					'<td class="form_text_right_three">'+
						objInfo.memberNames +
					'</td>' +
					'<td class="form_text_left_three">ͬ������</td>' +
					'<td class="form_text_right" colspan="3" id="memberNumberTr">' +
						objInfo.memberNumber +
					'</td>'+
				'</tr>' +
				'<tr>' +
					'<td class="form_text_left_three">�� ��</td>' +
					'<td class="form_text_right_three" colspan="5">'+
						fileInfo +
					'</td>' +
			    '</tr>' +
			'</table>';
		$("#"+defaults.myId).html(tableStr);
	}

	//��ʼ������
	function initDeptView(objInfo){
		var tableStr = '<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">���ù�����˾</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.CostBelongCom +
					'</td>' +
					'<td class = "form_text_left_three">���ù�������</td>' +
					'<td class = "form_text_right" colspan="3" id="feeDept">' +
						objInfo.CostBelongDeptName +
					'</td>' +
				'</tr>';
		$("#baseTr").after(tableStr);

		//ʡ��
		initCheckDeptView(objInfo);
		//������
		initWorkTeamView(objInfo);
	}

	//��ʼ����ͬ��Ŀ
	function initProjectView(objInfo){
		var tableStr = '<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">��Ŀ���</span></td>' +
					'<td class = "form_text_right_three">' +
						objInfo.ProjectNo +
					'</td>' +
					'<td class = "form_text_left_three">��Ŀ����</span></td>' +
					'<td class = "form_text_right_three">' +
						objInfo.projectName +
					'</td>' +
					'<td class = "form_text_left_three">��Ŀ����</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.proManagerName +
					'</td>' +
				'</tr>';
		$("#baseTr").after(tableStr);
	}

	//��ʼ����ǰ
	function initSaleView(objInfo){
		var tableStr = '<tr class="feeTypeContent">' +
					'<td class = "form_text_left_three">������Ŀ���</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.ProjectNo +
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
						objInfo.CustomerType +
					'</td>' +
				'</tr>' +
				'<tr class="feeTypeContent">' +
					'<td class="form_text_left_three">�ͻ�����</td>' +
					'<td class="form_text_right_three">' +
						objInfo.customerDept+
					'</td>'+
					'<td class = "form_text_left_three">���۸�����</td>' +
					'<td class = "form_text_right_three">' +
						objInfo.CostBelonger +
					'</td>' +
					'<td class = "form_text_left_three">���ù�������</td>' +
					'<td class = "form_text_right">' +
						objInfo.CostBelongDeptName +
					'</td>'
				'</tr>';
		$("#baseTr").after(tableStr);
	}

	//��ʼ���ۺ�
	function initContractView(objInfo){
		var tableStr = '<tr class="feeTypeContent">' +
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
					objInfo.CustomerType +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class="form_text_left_three">�ͻ�����</td>' +
				'<td class="form_text_right_three">' +
					objInfo.customerDept+
				'</td>'+
				'<td class = "form_text_left_three">���۸�����</td>' +
				'<td class = "form_text_right_three">' +
					objInfo.CostBelonger +
				'</td>' +
				'<td class = "form_text_left_three">���ù�������</td>' +
				'<td class = "form_text_right">' +
					objInfo.CostBelongDeptName +
				'</td>'
			'</tr>';
		$("#baseTr").after(tableStr);
	}

	//********************* �༭���� ************************/
	//��ʼ����������
	function initCostTypeEdit(thisObj,objInfo){
		initCostType(thisObj,objInfo);
		//��ѡ��ֵ
		$("input[name='"+defaults.objName+"[DetailType]']").each(function(i,n){
			if(this.value == objInfo.DetailType){
				$(this).attr("checked",this);
				return false;
			}
		});
		$("#detailTypeTitle").html('��������').removeClass('red').addClass('blue');
		switch(objInfo.DetailType){
			case '1' : initDeptEdit(objInfo);break;
			case '2' : initContractProjectEdit(objInfo);break;
			case '3' : initRdProjectEdit(objInfo);break;
			case '4' : initSaleEdit(objInfo);break;
			case '5' : initContractEdit(objInfo);break;
			default : break;
		}
	}

	//��ʼ������
	function initDeptEdit(objInfo){
		//��ʼֵ����
		var costBelongCom='',costBelongComId='',costBelongDeptName='',costBelongDeptId='',id='';
		if(objInfo){
			costBelongCom = objInfo.CostBelongCom;
			costBelongComId = objInfo.CostBelongComId;
			costBelongDeptName = objInfo.CostBelongDeptName;
			costBelongDeptId = objInfo.CostBelongDeptId;
		}
		var thisClass,thisCompany,thisCompanyId;
		if(defaults.isCompanyReadonly == true){
			thisClass = "readOnlyTxtNormal";
		}else{
			thisClass = "txt";
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">���ù�����˾</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="'+thisClass+'" id="costBelongCom" name="'+defaults.objName+'[CostBelongCom]" value="'+costBelongCom +'" readonly="readonly"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[CostBelongComId]" value="'+costBelongComId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
				'<td class = "form_text_right" colspan="3" id="feeDept">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[CostBelongDeptName]" value="'+costBelongDeptName +'" readonly="readonly"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[CostBelongDeptId]" value="'+costBelongDeptId +'"/>' +
				'</td>' +
			'</tr>';
		$("#baseTr").after(tableStr);
		if(defaults.isCompanyReadonly != true){
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

		//��Ҫ���ż��Ĳ�����Ⱦʡ��
		initCheckDept(undefined,objInfo);
		//��Ⱦ������
		initWorkTeam(objInfo);
		//������ʾ����
		$("#tipsView").html('');
	}

	//��ʼ����ͬ��Ŀ
	function initContractProjectEdit(objInfo){
		//��ʼֵ����
		var projectName='',projectCode='',projectId='',costBelongDeptName='',costBelongDeptId='',proManagerName='',proManagerId='',id='';
		if(objInfo){
			projectName = objInfo.projectName;
			projectCode = objInfo.ProjectNo;
			projectId = objInfo.projectId;
			projectType = objInfo.projectType;
			costBelongDeptName = objInfo.CostBelongDeptName;
			costBelongDeptId = objInfo.CostBelongDeptId;
			proManagerName = objInfo.proManagerName;
			proManagerId = objInfo.proManagerId;
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[ProjectNo]" readonly="readonly" value="'+projectCode +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly" value="'+projectName +'"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" value="'+projectId +'"/>' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" value="'+projectType +'"/>' +
					'<input type="hidden" id="costBelongDeptName" name="'+defaults.objName+'[CostBelongDeptName]" value="'+costBelongDeptName +'"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[CostBelongDeptId]" value="'+costBelongDeptId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" value="'+proManagerId +'"/>' +
				'</td>' +
			'</tr>';
		$("#baseTr").after(tableStr);

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
		//������ʾ����
		$("#tipsView").html('');
	}

	//��ʼ���з���Ŀ
	function initRdProjectEdit(objInfo){
		//��ʼֵ����
		var projectName='',projectCode='',projectId='',costBelongDeptName='',costBelongDeptId='',proManagerName='',proManagerId='',id='';
		if(objInfo){
			projectName = objInfo.projectName;
			projectCode = objInfo.ProjectNo;
			projectId = objInfo.projectId;
			projectType = objInfo.projectType;
			costBelongDeptName = objInfo.CostBelongDeptName;
			costBelongDeptId = objInfo.CostBelongDeptId;
			proManagerName = objInfo.proManagerName;
			proManagerId = objInfo.proManagerId;
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[ProjectNo]" readonly="readonly" value="'+projectCode +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly" value="'+projectName +'"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" value="'+projectId +'"/>' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" value="'+projectType +'"/>' +
					'<input type="hidden" id="costBelongDeptName" name="'+defaults.objName+'[CostBelongDeptName]" value="'+costBelongDeptName +'"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[CostBelongDeptId]" value="'+costBelongDeptId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="'+defaults.objName+'[proManagerName]" readonly="readonly" value="'+proManagerName +'"/>' +
					'<input type="hidden" id="proManagerId" name="'+defaults.objName+'[proManagerId]" value="'+proManagerId +'"/>' +
				'</td>' +
			'</tr>';
		$("#baseTr").after(tableStr);

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
		//������ʾ����
		$("#tipsView").html('');
	}

	//��ʼ����ǰ����
	function initSaleEdit(objInfo){
		//��ʼֵ����
		var projectName='',projectCode='',projectId='',projectType='',costBelongDeptName='',costBelongDeptId='';
		var proManagerName='',proManagerId='',chanceCode='',chanceName='',id='',customerDept;
		var chanceId='',customerName='',customerId='',province='',city='',customerType='',costBelonger='',costBelongerId='';
		if(objInfo){
			projectName = objInfo.projectName;
			projectCode = objInfo.ProjectNo;
			projectId = objInfo.projectId;
			projectType = objInfo.projectType;
			costBelongDeptName = objInfo.CostBelongDeptName;
			costBelongDeptId = objInfo.CostBelongDeptId;
			costBelongComId = objInfo.CostBelongComId;
			costBelongCom = objInfo.CostBelongCom;
			proManagerName = objInfo.proManagerName;
			proManagerId = objInfo.proManagerId;
			chanceCode = objInfo.chanceCode;
			chanceName = objInfo.chanceName;
			chanceId = objInfo.chanceId;
			customerName = objInfo.customerName;
			customerId = objInfo.customerId;
			province = objInfo.province;
			city = objInfo.city;
			customerType = objInfo.CustomerType;
			costBelonger = objInfo.CostBelonger;
			costBelongerId = objInfo.CostBelongerId;
			customerDept = objInfo.customerDept;
		}
		var tableStr = '<tr class="feeTypeContent">' +
				'<td class = "form_text_left_three">������Ŀ���</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectCode" name="'+defaults.objName+'[ProjectNo]" readonly="readonly" value="'+projectCode+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three">������Ŀ����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="projectName" name="'+defaults.objName+'[projectName]" readonly="readonly" value="'+projectName+'"/>' +
					'<input type="hidden" id="projectId" name="'+defaults.objName+'[projectId]" value="'+projectId+'"/>' +
					'<input type="hidden" id="projectType" name="'+defaults.objName+'[projectType]" value="'+projectType+'"/>' +
					'<input type="hidden" id="costBelongCom" name="'+defaults.objName+'[CostBelongCom]" value="'+ costBelongCom +'"/>' +
					'<input type="hidden" id="costBelongComId" name="'+defaults.objName+'[CostBelongComId]" value="'+ costBelongComId +'"/>' +
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
					'<input type="text" class="txt" id="customerType" name="'+defaults.objName+'[CustomerType]" value="'+ customerType +'" style="width:202px;"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class="form_text_left_three">�ͻ�����</td>' +
				'<td class="form_text_right_three">' +
					'<input type="text" class="txt" name="'+defaults.objName+'[customerDept]" id="customerDept" value="'+customerDept+'"/>' +
				'</td>'+
				'<td class = "form_text_left_three"><span class="blue">���۸�����</span></td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="txt" id="costBelonger" name="'+defaults.objName+'[CostBelonger]" value="'+ costBelonger +'" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongerId" name="'+defaults.objName+'[CostBelongerId]" value="'+ costBelongerId +'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[CostBelongDeptName]" value="'+ costBelongDeptName +'" style="width:202px;"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[CostBelongDeptId]" value="'+ costBelongDeptId +'"/>' +
				'</td>'
			'</tr>';
		$("#baseTr").after(tableStr);

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
		//����˵��
		$("#tipsView").html('����ʾ���̻����/����¼����ɺ�ϵͳ���Զ�������Ӧ��Ϣ��');
	}

	//��ʼ���ۺ����
	function initContractEdit(objInfo){
		//��ʼֵ����
		var costBelongDeptName='',costBelongDeptId='',proManagerName='',proManagerId='',contractCode='',contractName='',id='';
		var contractId='',customerName='',customerId='',province='',city='',customerType='',costBelonger='',costBelongerId='';
		if(objInfo){
			costBelongDeptName = objInfo.CostBelongDeptName;
			costBelongDeptId = objInfo.CostBelongDeptId;
			proManagerName = objInfo.proManagerName;
			proManagerId = objInfo.proManagerId;
			contractCode = objInfo.contractCode;
			contractName = objInfo.contractName;
			contractId = objInfo.contractId;
			customerName = objInfo.customerName;
			customerId = objInfo.customerId;
			province = objInfo.province;
			city = objInfo.city;
			customerType = objInfo.CustomerType;
			costBelonger = objInfo.CostBelonger;
			costBelongerId = objInfo.CostBelongerId;
			customerDept = objInfo.customerDept;
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
					'<input type="text" class="readOnlyTxtNormal" id="customerType" name="'+defaults.objName+'[CustomerType]" readonly="readonly" value="'+customerType+'"/>' +
				'</td>' +
			'</tr>' +
			'<tr class="feeTypeContent">' +
				'<td class="form_text_left_three">�ͻ�����</td>' +
				'<td class="form_text_right_three">' +
					'<input type="text" class="txt" name="'+defaults.objName+'[customerDept]" id="customerDept" value="'+customerDept+'"/>' +
				'</td>'+
				'<td class = "form_text_left_three">���۸�����</td>' +
				'<td class = "form_text_right_three">' +
					'<input type="text" class="readOnlyTxtNormal" id="costBelonger" name="'+defaults.objName+'[CostBelonger]" readonly="readonly" value="'+costBelonger+'"/>' +
					'<input type="hidden" id="costBelongerId" name="'+defaults.objName+'[CostBelongerId]" value="'+costBelongerId+'"/>' +
				'</td>' +
				'<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
				'<td class = "form_text_right">' +
					'<input type="text" class="txt" id="costBelongDeptName" name="'+defaults.objName+'[CostBelongDeptName]" style="width:202px;" value="'+costBelongDeptName+'"/>' +
					'<input type="hidden" id="costBelongDeptId" name="'+defaults.objName+'[CostBelongDeptId]" value="'+costBelongDeptId+'"/>' +
				'</td>'
			'</tr>';
		$("#baseTr").after(tableStr);

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
		//����˵��
		$("#tipsView").html('����ʾ����ͬ���/����¼����ɺ�ϵͳ���Զ�������Ӧ��Ϣ��');
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
			}
		});
	};
})(jQuery);
//����������Ϊѡ��
function setSelected(id,val){
	$("#"+id+" option[value='"+val+"']").attr("selected",true);
}

//ӦƸְλ
function positionsGrid(id){
	$("#positionsName").yxcombogrid_jobs({
		hiddenId : 'positionsId',
		width : 280,
		gridOptions : {
			param : {
				deptId : id
			}
		}
	});
}

function hrJobGrid(id){
	$("#hrJobName").yxcombogrid_jobs({
		hiddenId : 'hrJobId',
		width : 350,
		gridOptions : {
			param : {
				deptId : id
			}
		}
	});
}

$(document).ready(function() {
	var sexy = $("#sexyHidden").val();
	setSelected('sexy',sexy);
	$("#manager").yxselect_user({
		hiddenId : 'managerId',
		mode : 'check'
	});
	$("#useManager").yxselect_user({
		hiddenId : 'useManagerId',
		mode : 'check'
	});
	$("#tutor").yxselect_user({
		hiddenId : 'tutorId'
	});

	//������Ϣ
	$("#deptName").dblclick(function(){
		$("#positionsName").val("");
		$("#positionsId").val("");
		$("#hrJobName").val("");
		$("#hrJobId").val("");
		$("#positionsName").yxcombogrid_jobs("remove");
		$("#hrJobName").yxcombogrid_jobs("remove");
	});

	$("#deptName").yxselect_dept({
		hiddenId : 'deptId',
		event:{
			selectReturn : function(e ,row) {
				//ʵϰ��
				if (row.dept.id == '155') {
					isIntern();
				} else {
					noIntern();
				}

				$("#positionsName").val("");
				$("#positionsId").val("");
				$("#positionsName").yxcombogrid_jobs("remove");
				$("#hrJobName").val("");
				$("#hrJobId").val("");
				$("#hrJobName").yxcombogrid_jobs("remove");
				positionsGrid(row.dept.id);
				hrJobGrid(row.dept.id);

				positionLevelChange(); //���ŵĲ����¼�
			}
		}
	});

	$("#positionsName").attr("readonly",true);
	$("#positionsName").click(function(){
		if($("#deptId").val()==""){
			alert("��ѡ�����˲���");
			$(this).val("");
		}
	});

	$("#hrJobName").attr("readonly",true);
	$("#hrJobName").click(function(){
		if($("#deptId").val()==""){
			alert("��ѡ�����˲���");
			$(this).val("");
		}
	});

	// ��ѡ��Ա�ȼ�
	$("#personLevel").yxcombogrid_personlevel({
		hiddenId : 'personLevelId',
		width : 280,
		gridOptions : {
			showcheckbox : false,
			param : {
				status : 0
			}
		}
	});

	$("#compensation").hide();
	var hintArr = '����д����ҵ����Э�顷������׼';
	//ѡ��ǩ������ҵ����Э�顷
	$("#useSign").change(function(){
		if($(this).val() == '��'){
			if($.trim($("#compensation").val()) == ''){
				$("#compensation").css({"color":"#999"});
				$("#compensation").val(hintArr);
			}
			$("#compensation").show();
		}else {
			$("#compensation").hide();
		}
	});

	$("#compensation").focus(function(){
		if($.trim($(this).val()) == hintArr) {
			$(this).val("");
			$(this).css({"color":"#000"});
		}
	});

	$("#compensation").blur(function(){
		if($.trim($(this).val()) == '') {
			$(this).val(hintArr);
			$(this).css({"color":"#999"});
		}
	});

	//��˾
	$('select[name="interview[sysCompanyId]"] option').each(function() {
		if( $(this).val() == $("#sysCompanyIdSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	//����
	$('select[name="interview[useAreaId]"] option').each(function() {
		if( $(this).val() == $("#useAreaIdSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	//�칫���������豸����
	$('select[name="interview[useDemandEqu]"] option').each(function() {
		if( $(this).val() == $("#useDemandEquSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	$("#projectType").bind('change',function(){// ��Ŀ����
		$("#projectGroup").val("");
		$("#projectGroupId").val("");
		$("#projectCode").val("");
		$("#projectGroup").yxcombogrid_esmproject("remove");
		$("#projectGroup").yxcombogrid_rdprojectfordl("remove");
		if($(this).val() == "GCXM"){
			$("#projectGroup").yxcombogrid_esmproject({
				hiddenId : 'projectGroupId',
				nameCol : 'projectName',
				isShowButton : false,
				height : 250,
				event : {
					'clear': function() {
						$("#projectCode").val("");
					}
				},
				gridOptions : {
					isTitle : true,
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#projectCode").val(data.projectCode);
						}
					}
				}
			});
			$("#projectGroup").yxcombogrid_esmproject("show");
		} else if ($(this).val()=="YFXM") { // �з�������Ŀ
			$("#projectGroup").yxcombogrid_rdprojectfordl({
				hiddenId : 'projectGroupId',
				nameCol : 'projectName',
				isShowButton : false,
				isFocusoutCheck:false,
				height : 250,
				event : {
					'clear' : function() {
						$("#projectCode").val("");
					}
				},
				gridOptions : {
					isTitle : true,
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#projectCode").val(data.projectCode);
						}
					}
				}
			});
			$("#projectGroup").yxcombogrid_rdprojectfordl("show");
		} else {
		}
	});

	$("#useJobName").yxcombogrid_jobs({
		hiddenId : 'useJobId',
		width : 280
	});

	$("#socialPlace").mouseover(function(){
		$.validationEngine.buildPrompt(this,"�����������麣�����Ϻ�,������Դ��ͨ�������ڱ��������ݱ���������ڹ���",null);
	});

	$("#socialPlace").mouseout(function(){
		$.validationEngine.closePrompt(this,false);
	});

	$("#socialPlace").yxcombogrid_socialplace({
		hiddenId : 'socialPlaceId',
		width : 350
	});

	$("#applyCode").yxcombogrid_interview({
		hiddenId : 'applyId',
		width : 500,
		nameCol:'employmentCode',
		isFocusoutCheck:false,
		gridOptions : {
			event:{
				'row_dblclick' : function(e, row, data) {
					$("#applyCode").val(data.employmentCode);
				}
			},
			showcheckbox : false
		}
	});

	$("#sourceCode").yxcombogrid_interviewparent({
		hiddenId : 'sourceId',
		nameCol:'formCode',
		isFocusoutCheck:false,
		gridOptions : {
			event:{
				'row_dblclick' : function(e, row, data) {
					$("#sourceCode").val(data.formCode);
					$("#sourceId").val(data.id);
				}
			},
			showcheckbox : false
		}
	});

	$("#useHireType").change(function () {
		positionLevelChange();
	});

	validate({
		"userName" : {
			required : true
		},
		"deptName" : {
			required : true
		},
		"sexy" : {
			required : true
		},
		"positionsName" : {
			required : true
		},
//		"applyCode" : {
//			required : true
//		},
		"phone" : {
			required : true
		},
		"email" : {
			required : true
		},
		"positionLevel" : {
			required : true
		},
		"postType" : {
			required : true
		},
		"useInterviewResult" : {
			required : true
		},
		"useHireType" : {
			required : true
		},
		"sysCompanyId" : {
			required : true
		},
		"useAreaId" : {
			required : true
		},
		"useTrialWage" : {
			required : true
		},
		"useFormalWage" : {
			required : true
		},
		"useDemandEquSelect" : {
			required : true
		},
		"useSignSelect" : {
			required : true
		},
		"probation" : {
			required : true
		},
		"contractYear" : {
			required : true
		},
		"hrSourceType1" : {
			required : true
		},
		"hrJobName" : {
			required : true
		},
		"hrIsManageJobSelect" : {
			required : true
		},
		"entryDate" : {
			required : true
		},
		"useDemandEqu" : {
			required : true
		},
		"useSign" : {
			required : true
		},
		"personLevel" : {
			required : true
		},
		"assistManName":{
			required : true
		},
		"MailTitle":{
			required : true
		},
		"wageLevelCode":{
			required : true
		}
	});

	$("#itemTable").yxeditgrid({
		objName : 'interview[items]',
		isAddOneRow : true,
		colModel : [{
			display : '���Թ�',
			name : 'interviewer',
			validation : {
				required : true
			},
			process : function($input) {
				var rowNum = $input.data("rowNum");
				$input.yxselect_user({
					hiddenId: 'itemTable_cmp_interviewerId'+rowNum
				});
			}
		},{
			display : '������ID',
			name : 'interviewerId',
			type : 'txt',
			type:'hidden'
		},{
			display : '��������',
			name : 'interviewDate',
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '��������',
			name : 'interviewEva',
			type : 'textarea',
			cols : '40',
			rows : '3',
			validation : {
				required : true
			}
		},{
			display : '��������',
			name : 'useWriteEva',
			type : 'textarea',
			cols : '40',
			rows : '3',
			validation : {
				required : true
			}
		}]
	});
});

//ѡ����������ְλʱ�����������ֵ�����
function initLevelWY(){
	var dataArr = [];
	var data = $.ajax({
		url:'?model=hr_basicinfo_level&action=listJson&sort=personLevel&dir=ASC&status=0',
		type:'post',
		dataType : 'json',
		async : false
	}).responseText;
	data = eval("(" + data + ")");
	var positionLevel = $("#positionLevel");
	positionLevel.empty();
	for(i = 0 ;i < data.length ;i++) {
		var option = $("<option></option");
		option.val(data[i].id);
		option.text(data[i].personLevel);
		option.appendTo(positionLevel);
	}

	//���ż������¼�
	$("#positionLevel").unbind("change").change(positionLevelChange).trigger("change");
}

function initPostType(){
	if($("#postType").val()=='YPZW-WY') {
		initLevelWY();
	} else {
		var options='<option value="">...��ѡ��...</option> <option value="1">����</option><option value="2">�м�</option><option value="3">�߼�</option>';
		var positionLevel=$("#positionLevel");
		positionLevel.empty();
		positionLevel.html(options);

		$("#positionLevel").trigger("change").unbind("change"); //���ż������¼�
	}
}

$(function(){
	initPostType();
	$("#postType").change(function(){
		initPostType();
	});

	$("#useAreaId").change(function(){
		$("#useAreaName").val($("#useAreaId").find("option:selected").text());
	});

	//¼����ʽ
	$("#useHireType").change(function(){
		$("#useHireTypeName").val($(this).find("option:selected").text());
	});

	//������˾
	$("#sysCompanyId").change(function(){
		$("#sysCompanyName").val($(this).find("option:selected").text());
	});

	//����
	$('select[name="entryNotice[positionLevel]"] option').each(function() {
		if( $(this).val() == $("#positionLevelHidden").val() ){
			$(this).attr("selected","selected");
		}
	});
	$("#positionLevel").trigger('change'); //�����������¼�

	//��Ŀ����
	$('select[name="entryNotice[projectType]"] option').each(function() {
		if( $(this).val() == $("#projectTypeResultSelect").val() ){
			$(this).attr("selected","selected");
		}
	});

	//������˾
	$('select[name="entryNotice[sysCompanyId]"] option').each(function() {
		if( $(this).html() == $("#sysCompanyName").val() ){
			$(this).attr("selected","selected");
		}
	});

	//��������
	$('select[name="entryNotice[useAreaId]"] option').each(function() {
		if( $(this).html() == $("#useAreaName").val() ){
			$(this).attr("selected","selected");
		}
	});

	//�Ƿ񱾵ػ�
	$('select[name="entryNotice[isLocal]"] option').each(function() {
		if( $(this).val() == $("#isLocalHidden").val() ){
			$(this).attr("selected","selected");
		}
	});

	//�����Ƿ񰴹�˾��׼
	$('input[name="entryNotice[isCompanyStandard]"]').each(function() {
		if( $(this).val() == $("#isCompanyStandardHidden").val() ){
			$(this).attr("checked","checked");
		}
	});

	//ǩ������ҵ����Э�顷
	$('select[name="entryNotice[useSign]"] option').each(function() {
		if( $(this).val() == $("#useSignSelect").val() ){
			$(this).attr("selected","selected").trigger('change');
		}
	});

	// �칫���������豸����
	$('select[name="entryNotice[useDemandEqu]"] option').each(function() {
		if( $(this).val() == $("#useDemandEquSelect").val() ){
			$(this).attr("selected","selected");
		}
	});

	// ����������н�㼰н���Ƿ��Ӧ
	$('select[name="entryNotice[hrIsMatch]"] option').each(function() {
		if( $(this).val() == $("#hrIsMatchHidden").val() ){
			$(this).attr("selected","selected");
		}
	});

	//ʡ��
	$('select[name="entryNotice[eprovinceId]"] option').each(function() {
		if( $(this).html() == $("#eprovince").val() ){
			$(this).attr("selected","selected").trigger('change');
		}
	});

	//����
	$('select[name="entryNotice[ecityId]"] option').each(function() {
		if( $(this).val() == $("#ecity2").val() ){
			$(this).attr("selected","selected");
		}
	});
});

$(document).ready(function() {
	$( 'textarea.editor' ).ckeditor();
	//������Աѡ��
	$("#toccmail").yxselect_user({
		hiddenId : 'toccmailId',
		mode : 'check',
		formCode : 'intUseManager'
	});

	//������Աѡ��
	$("#tobccmail").yxselect_user({
		hiddenId : 'tobccmailId',
		mode : 'check',
		formCode : 'intUseManager'
	});

	//Э����Աѡ��
	$("#assistManName").yxselect_user({
		hiddenId : 'assistManId'
	});

});

function addmail(name) {
	if(name == 'ccmail'){
		var nameCol = "����";
	} else {
		var nameCol = "����";
	}
	var temp = document.getElementById(name);
	if (temp.style.display == ''){
		temp.style.display = "none";
		$("#to"+name).val("");

		$("#btn"+name).val("���" + nameCol);
	} else if (temp.style.display == "none"){
		temp.style.display = '';
		$("#btn"+name).val("ɾ��" + nameCol);
	}
}

//�ύУ������
function checkData(){
	if($("#compensation").val() == '����д����ҵ����Э�顷������׼' || $("#useSign").val() != '��') {
		$("#compensation").val("");
	}

	//��������Ϣ
	if (!$("#tripSubsidy_v").hasClass("validate[required]")) {
		$("#tripSubsidy").val("");
	}
	if (!$("#manageSubsidy_v").hasClass("validate[required]")) {
		$("#manageSubsidy").val("");
	}
	if (!$("#accommodSubsidy_v").hasClass("validate[required]")) {
		$("#accommodSubsidy").val("");
	}
	if (!$("#phoneSubsidy_v").hasClass("validate[required]")) {
		$("#phoneSubsidy").val("");
	}
	if (!$("#computerSubsidy_v").hasClass("validate[required]")) {
		$("#computerSubsidy").val("");
	}
	if (!$("#bonusLimit_v").hasClass("validate[required]")) {
		$("#bonusLimit").val("");
	}
	if (!$("#otherSubsidy_v").hasClass("validate[required]")) {
		$("#otherSubsidy").val("");
	}
	if (!$("#workBonus_v").hasClass("validate[required]")) {
		$("#workBonus").val("");
	}

	if (!$("#tripSubsidyFormal_v").hasClass("validate[required]")) {
		$("#tripSubsidyFormal").val("");
	}
	if (!$("#manageSubsidyFormal_v").hasClass("validate[required]")) {
		$("#manageSubsidyFormal").val("");
	}
	if (!$("#accommodSubsidyFormal_v").hasClass("validate[required]")) {
		$("#accommodSubsidyFormal").val("");
	}
	if (!$("#phoneSubsidyFormal_v").hasClass("validate[required]")) {
		$("#phoneSubsidyFormal").val("");
	}
	if (!$("#computerSubsidyFormal_v").hasClass("validate[required]")) {
		$("#computerSubsidyFormal").val("");
	}
	if (!$("#bonusLimitFormal_v").hasClass("validate[required]")) {
		$("#bonusLimitFormal").val("");
	}
	if (!$("#otherSubsidyFormal_v").hasClass("validate[required]")) {
		$("#otherSubsidyFormal").val("");
	}
	if (!$("#workBonusFormal_v").hasClass("validate[required]")) {
		$("#workBonusFormal").val("");
	}

	if (!$("#allTrialWage_v").hasClass("validate[required]")) {
		$("#allTrialWage").val("");
	}
	if (!$("#allFormalWage_v").hasClass("validate[required]")) {
		$("#allFormalWage").val("");
	}
}

function save(){
	document.getElementById('form1').action = "?model=hr_recruitment_entryNotice&action=add&isSave=1";
}

//��ʵϰ��
function isIntern() {
	$("#useHireType").val('HRLYXX-03').change(function() {
		if ($(this).val() != 'HRLYXX-03') {
			alert('���ܸ���¼����ʽ��');
			$(this).val('HRLYXX-03');
		}
	});

	$("#useTrialWage_v").removeClass("validate[required]").parent().hide().prev().hide(); //�����ڹ���
	$("#useFormalWage_v").removeClass("validate[required]").parent().hide().prev().hide(); //ת������
	$("#internshipSalaryType").addClass("validate[required]").parent().show().prev().show(); //ʵϰ��������
	$("#internshipSalary_v").addClass("validate[required]").parent().parent().next().hide(); //ʵϰ����
	$("#probation").removeClass("validate[required]").parent().parent().hide(); //�Ͷ���ͬ��Ϣ ������
	$("#contractYear").removeClass("validate[required]"); //�Ͷ���ͬ��Ϣ ��ͬ����
	$("#socialPlace").removeClass("validate[required]").val('��'); //�籣�����
	$("#socialPlaceId").val('0');
}

//����ʵϰ��
function noIntern() {
	$("#useHireType").unbind('change').change(function () {
		positionLevelChange();
	});
	$("#useTrialWage").addClass("validate[required]").parent().show().prev().show(); //�����ڹ���
	$("#useFormalWage").addClass("validate[required]").parent().show().prev().show(); //ת������
	$("#internshipSalaryType").removeClass("validate[required]").parent().hide().prev().hide(); //ʵϰ��������
	$("#internshipSalary_v").removeClass("validate[required]").parent().parent().next().show(); //ʵϰ����
	$("#hrInterviewResult").addClass("validate[required]").parent().show().prev().show(); //��������
	$("#probation").addClass("validate[required]").parent().parent().show(); //�Ͷ���ͬ��Ϣ ������
	$("#contractYear").addClass("validate[required]"); //�Ͷ���ͬ��Ϣ ��ͬ����
	$("#socialPlace").addClass("validate[required]"); //�籣�����
}

//�貹��Ϊ����
function addSubsidyRequired() {
	$("#phoneSubsidy_v").addClass("validate[required]");
	$("#computerSubsidy_v").addClass("validate[required]");
	$("#phoneSubsidyFormal_v").addClass("validate[required]");
	$("#computerSubsidyFormal_v").addClass("validate[required]");
	$("#allTrialWage_v").addClass("validate[required]");
	$("#allFormalWage_v").addClass("validate[required]");
}

//�Ƴ�����Ϊ����
function removeSubsidyRequired() {
	$("#tripSubsidy_v").removeClass("validate[required]");
	$("#workBonus_v").removeClass("validate[required]");
	$("#phoneSubsidy_v").removeClass("validate[required]");
	$("#computerSubsidy_v").removeClass("validate[required]");
	$("#manageSubsidy_v").removeClass("validate[required]");
	$("#accommodSubsidy_v").removeClass("validate[required]");
	$("#bonusLimit_v").removeClass("validate[required]");
	$("#otherSubsidy_v").removeClass("validate[required]");

	$("#tripSubsidyFormal_v").removeClass("validate[required]");
	$("#workBonusFormal_v").removeClass("validate[required]");
	$("#phoneSubsidyFormal_v").removeClass("validate[required]");
	$("#computerSubsidyFormal_v").removeClass("validate[required]");
	$("#manageSubsidyFormal_v").removeClass("validate[required]");
	$("#accommodSubsidyFormal_v").removeClass("validate[required]");
	$("#bonusLimitFormal_v").removeClass("validate[required]");
	$("#otherSubsidyFormal_v").removeClass("validate[required]");

	$("#allTrialWage_v").removeClass("validate[required]");
	$("#allFormalWage_v").removeClass("validate[required]");
}

//���ż������¼�
function positionLevelChange() {
	if ($("#useHireType").val() != 'HRLYXX-03' && $("#useHireType").val() != '') { //��ʵϰ��
		var personClass = $("#positionLevel option:selected").text(); //��Ա���
		$("#subsidyTr1").show();
		$("#subsidyTr2").show();
		$("#subsidyTr3").show();
		$("#levelSubsidy").parent().hide().prev().hide();
		$("#areaSubsidy").parent().hide().prev().hide();
		if (personClass.indexOf('A') == 0 || !isNaN(personClass.substring(0 ,1))) { //A��
			$("#tripSubsidy_v").addClass("validate[required]").parent().show().prev().show();
			$("#tripSubsidyFormal_v").addClass("validate[required]");
			$("#workBonus_v").removeClass("validate[required]").parent().hide().prev().hide();
			$("#workBonusFormal_v").removeClass("validate[required]");
			addSubsidyRequired();
			$("#manageSubsidy_v").addClass("validate[required]");
			$("#manageSubsidyFormal_v").addClass("validate[required]");
			$("#accommodSubsidy_v").addClass("validate[required]");
			$("#accommodSubsidyFormal_v").addClass("validate[required]");
			$("#bonusLimit_v").addClass("validate[required]");
			$("#bonusLimitFormal_v").addClass("validate[required]");
			$("#otherSubsidy_v").addClass("validate[required]").parent().show().prev().show();
			$("#otherSubsidyFormal_v").addClass("validate[required]");

			//�����ܶ�
			bindAllTrialWage();
			$("#tripSubsidy_v").trigger('blur');
			$("#tripSubsidyFormal_v").trigger('blur');
		} else if (personClass.indexOf('B') == 0) { //B��
			$("#tripSubsidy_v").removeClass("validate[required]").parent().hide().prev().hide();
			$("#tripSubsidyFormal_v").removeClass("validate[required]");
			$("#workBonus_v").addClass("validate[required]").parent().show().prev().show();
			$("#workBonusFormal_v").addClass("validate[required]");
			$("#subsidyTr2").hide();
			addSubsidyRequired();
			$("#manageSubsidy_v").removeClass("validate[required]");
			$("#manageSubsidyFormal_v").removeClass("validate[required]");
			$("#accommodSubsidy_v").removeClass("validate[required]");
			$("#accommodSubsidyFormal_v").removeClass("validate[required]");
			$("#bonusLimit_v").removeClass("validate[required]");
			$("#bonusLimitFormal_v").removeClass("validate[required]");
			$("#otherSubsidy_v").removeClass("validate[required]").parent().hide().prev().hide();
			$("#otherSubsidyFormal_v").removeClass("validate[required]");
			$("#levelSubsidy_v").parent().show().prev().show();
			$("#areaSubsidy_v").parent().show().prev().show();

			//�����ܶ�
			bindAllFormalWage();
			$("#workBonus_v").trigger('blur');
			$("#workBonusFormal_v").trigger('blur');
		} else {
			$("#subsidyTr1").hide();
			$("#subsidyTr2").hide();
			$("#subsidyTr3").hide();
			removeSubsidyRequired();
		}
	} else {
		$("#subsidyTr1").hide();
		$("#subsidyTr2").hide();
		$("#subsidyTr3").hide();
		removeSubsidyRequired();
	}
}

//����A��Ԥ���ܶ�
function countAllTrialWage() {
	//����
	var commonSubsidy = parseFloat($("#tripSubsidy").val() ? $("#tripSubsidy").val() : 0)
						+ parseFloat($("#phoneSubsidy").val() ? $("#phoneSubsidy").val() : 0)
						+ parseFloat($("#computerSubsidy").val() ? $("#computerSubsidy").val() : 0)
						+ parseFloat($("#manageSubsidy").val() ? $("#manageSubsidy").val() : 0)
						+ parseFloat($("#accommodSubsidy").val() ? $("#accommodSubsidy").val() : 0)
						+ parseFloat($("#bonusLimit").val() ? $("#bonusLimit").val() : 0)
						+ parseFloat($("#otherSubsidy").val() ? $("#otherSubsidy").val() : 0);
	$("#allTrialWage_v").val(parseFloat($("#useTrialWage").val() ? $("#useTrialWage").val() : 0) + commonSubsidy).trigger('blur');

	//ת��
	var commonSubsidyFormal = parseFloat($("#tripSubsidyFormal").val() ? $("#tripSubsidyFormal").val() : 0)
						+ parseFloat($("#phoneSubsidyFormal").val() ? $("#phoneSubsidyFormal").val() : 0)
						+ parseFloat($("#computerSubsidyFormal").val() ? $("#computerSubsidyFormal").val() : 0)
						+ parseFloat($("#manageSubsidyFormal").val() ? $("#manageSubsidyFormal").val() : 0)
						+ parseFloat($("#accommodSubsidyFormal").val() ? $("#accommodSubsidyFormal").val() : 0)
						+ parseFloat($("#bonusLimitFormal").val() ? $("#bonusLimitFormal").val() : 0)
						+ parseFloat($("#otherSubsidyFormal").val() ? $("#otherSubsidyFormal").val() : 0);
	$("#allFormalWage_v").val(parseFloat($("#useFormalWage").val() ? $("#useFormalWage").val() : 0) + commonSubsidyFormal).trigger('blur');
}

//�󶨼���A���ܶ��¼�
function bindAllTrialWage() {
	//����
	$("#tripSubsidy_v").blur(countAllTrialWage);
	$("#phoneSubsidy_v").blur(countAllTrialWage);
	$("#computerSubsidy_v").blur(countAllTrialWage);
	$("#manageSubsidy_v").blur(countAllTrialWage);
	$("#accommodSubsidy_v").blur(countAllTrialWage);
	$("#useTrialWage_v").blur(countAllTrialWage);
	$("#bonusLimit_v").blur(countAllTrialWage);
	$("#otherSubsidy_v").blur(countAllTrialWage);

	//ת��
	$("#tripSubsidyFormal_v").blur(countAllTrialWage);
	$("#phoneSubsidyFormal_v").blur(countAllTrialWage);
	$("#computerSubsidyFormal_v").blur(countAllTrialWage);
	$("#manageSubsidyFormal_v").blur(countAllTrialWage);
	$("#accommodSubsidyFormal_v").blur(countAllTrialWage);
	$("#useFormalWage_v").blur(countAllTrialWage);
	$("#bonusLimitFormal_v").blur(countAllTrialWage);
	$("#otherSubsidyFormal_v").blur(countAllTrialWage);
}

//����B��Ԥ���ܶ�
function countAllFormalWage() {
	//����
	var commonSubsidy = parseFloat($("#workBonus").val() ? $("#workBonus").val() : 0)
						+ parseFloat($("#phoneSubsidy").val() ? $("#phoneSubsidy").val() : 0)
						+ parseFloat($("#computerSubsidy").val() ? $("#computerSubsidy").val() : 0)
						+ parseFloat($("#levelSubsidy").val() ? $("#levelSubsidy").val() : 0)
						+ parseFloat($("#areaSubsidy").val() ? $("#areaSubsidy").val() : 0);
	$("#allTrialWage_v").val(parseFloat($("#useTrialWage").val() ? $("#useTrialWage").val() : 0) + commonSubsidy).trigger('blur');

	//ת��
	var commonSubsidyFormal = parseFloat($("#workBonusFormal").val() ? $("#workBonusFormal").val() : 0)
						+ parseFloat($("#phoneSubsidyFormal").val() ? $("#phoneSubsidyFormal").val() : 0)
						+ parseFloat($("#computerSubsidyFormal").val() ? $("#computerSubsidyFormal").val() : 0)
						+ parseFloat($("#levelSubsidyFormal").val() ? $("#levelSubsidyFormal").val() : 0)
						+ parseFloat($("#areaSubsidyFormal").val() ? $("#areaSubsidyFormal").val() : 0);
	$("#allFormalWage_v").val(parseFloat($("#useFormalWage").val() ? $("#useFormalWage").val() : 0) + commonSubsidyFormal).trigger('blur');
}

//�󶨼���B��Ԥ���ܶ��¼�
function bindAllFormalWage() {
	//����
	$("#workBonus_v").blur(countAllFormalWage);
	$("#phoneSubsidy_v").blur(countAllFormalWage);
	$("#computerSubsidy_v").blur(countAllFormalWage);
	$("#levelSubsidy_v").blur(countAllFormalWage);
	$("#areaSubsidy_v").blur(countAllFormalWage);
	$("#useTrialWage_v").blur(countAllFormalWage);

	//ת��
	$("#workBonusFormal_v").blur(countAllFormalWage);
	$("#phoneSubsidyFormal_v").blur(countAllFormalWage);
	$("#computerSubsidyFormal_v").blur(countAllFormalWage);
	$("#levelSubsidyFormal_v").blur(countAllFormalWage);
	$("#areaSubsidyFormal_v").blur(countAllFormalWage);
	$("#useFormalWage_v").blur(countAllFormalWage);
}
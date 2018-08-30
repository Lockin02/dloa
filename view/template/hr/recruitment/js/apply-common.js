//������������
function mulSelectSet(thisObj){
	thisObj.next().find("input").each(function(i,n){
		if($(this).attr('class') == 'combo-text validatebox-text'){
			$("#"+ thisObj.attr('id') + "Hidden").val(this.value);
		}
	});
}

//��ֵ��ѡֵ -- ��ʼ����ֵ
function mulSelectInit(thisObj){
	//��ʼ����Ӧ����
	var objVal = $("#"+ thisObj.attr('id') + "Hidden").val();
	if(objVal != "" ){
		thisObj.combobox("setValues",objVal.split(','));
	}
}

//��ʼ�����鲹�䷽ʽ��Ϣ
function initPCC(){
	//��ȡ���鲹�䷽ʽ
	var addModeNameArr = $('#addModeNameHidden').val().split(",");
	var str;
	//���鲹�䷽ʽ��Ⱦ
	var addModeNameObj = $('#addModeName');
	addModeNameObj.combobox({
		url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=HRBCFS',
		multiple:true,
		valueField:'text',
		textField:'text',
		editable : false,
		formatter: function(obj){
			//�ж� ���û�г�ʼ�������У���ѡ��
			if(addModeNameArr.indexOf(obj.text) == -1){
				str = "<input type='checkbox' id='addModeName_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
			}else{
				str = "<input type='checkbox' id='addModeName_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
			}
			return str;
		},
		onSelect : function(obj){
			//checkbox��ֵ
			$("#addModeName_" + obj.text).attr('checked',true);
			//���ö����µ�ѡ����
			mulSelectSet(addModeNameObj);
		},
		onUnselect : function(obj){
			//checkbox��ֵ
			$("#addModeName_" + obj.text).attr('checked',false);
			//����������
			mulSelectSet(addModeNameObj);
		}
	});

	//�ͻ����ͳ�ʼ����ֵ
	mulSelectInit(addModeNameObj);
}

//��ʼ�����鲹�䷽ʽ��Ϣ
function initLevel(data){
	//��ȡ���鲹�䷽ʽ
	var positionLevelArr = $('#positionLevelHidden').val().split(",");
	var str;
	//���鲹�䷽ʽ��Ⱦ
	var positionLevelObj = $('#positionLevel');
	var dataArr=[{"positionLevel":"����"},{"positionLevel":"�м�"},{"positionLevel":"�߼�"}];

	if(data){  //add chenrf
		dataArr = data;
	}

	positionLevelObj.combobox({
		data : dataArr,
		multiple:true,
		editable : false,
		valueField:'positionLevel',
		textField:'positionLevel',
		formatter: function(obj){
			//�ж� ���û�г�ʼ�������У���ѡ��
			if(positionLevelArr.indexOf(obj.positionLevel) == -1){
				str = "<input type='checkbox' id='positionLevel_"+ obj.positionLevel +"' value='"+ obj.positionLevel +"'/> " + obj.positionLevel;
			}else{
				str = "<input type='checkbox' id='positionLevel_"+ obj.positionLevel +"' value='"+ obj.positionLevel +"' checked='checked'/> " + obj.positionLevel;
			}
			return str;
		},
		onSelect : function(obj){
			//checkbox��ֵ
			$("#positionLevel_" + obj.positionLevel).attr('checked',true);
			//���ö����µ�ѡ����
			mulSelectSet(positionLevelObj);
		},
		onUnselect : function(obj){
			//checkbox��ֵ
			$("#positionLevel_" + obj.positionLevel).attr('checked',false);
			//����������
			mulSelectSet(positionLevelObj);
		}
	});

	//�ͻ����ͳ�ʼ����ֵ
	mulSelectInit(positionLevelObj);

	//ȥ��������豸�ı�������
	$("#networkSpan").css("color" ,"");
	$("#network").removeClass("validate[required]");
	$("#deviceSpan").css("color" ,"");
	$("#device").removeClass("validate[required]");
}

/*************add chenrf 20130508************************************/
//ѡ����������ְλʱ�����������ֵ�����
function initLevelWY(){
	var dataArr=[];
	var data=$.ajax({
		url:'?model=hr_basicinfo_level&action=listJson&sort=personLevel&dir=ASC&status=0',
		type:'post',
		dataType:'json',
		async:false
	}).responseText;
	data = eval("("+data+")");

	for(i = 0 ;i < data.length ;i++) {
		dataArr.push({"positionLevel":data[i].personLevel})
	}
	initLevel(dataArr);
	//��������豸����Ϊ����
	$("#networkSpan").css("color" ,"blue");
	$("#network").addClass("validate[required]");
	$("#deviceSpan").css("color" ,"blue");
	$("#device").addClass("validate[required]");
}

$(function(){
	var $postType = $("#postType");
	if('YPZW-WY' == $postType.val()) {
		initLevelWY();
	} else {
		initLevel();
	}
	//ְλ���͸ı䴥���¼�
	$postType.change(function(){
		$('#positionLevelHidden').val('');
		$('#positionLevel').val('');
		if($(this).val() == 'YPZW-WY') { //ѡ����������
			initLevelWY();
		} else {
			initLevel();
		}
	});

	//ָ����ʦ
	$("#tutor").yxselect_user({
		hiddenId : 'tutorId'
	});

	//ѧ�������¼�
	$("#education").change(function(){
		var edicationName=($(this).find('option:selected').text())
		$("input[name='apply[educationName]']").val(edicationName);
	});

	//��������
	$("#useAreaId").change(function(){
		$("#useAreaName").val($(this).find("option:selected").text());
	});
});

//add chenrf
//�ύУ������
function checkData(){
	if($("#addTypeCode").val() == "") {
		alert("��������Ա����");
		return false;
	} else if ($("#addTypeCode").val() == "ZYLXLZ") {
		if($("#leaveManName").val() == "") {
			alert("��������ְ/����������");
			return false;
		}
	} else if ($("#positionLevelHidden").val() == "") {
		alert("��ѡ�񼶱�");
		return false;
	} else if ($("#addModeNameHidden").val() == "") {
		alert("��ѡ���鲹�䷽ʽ");
		return false;
	} else if ($("#postType").val() == "") {
		alert("��ѡ��ְλ����");
		return false;
	} else if ($("#employmentTypeCode").val() == "") {
		alert("��ѡ���ù�����");
		return false;
	} else if ($("#applyReason").val() == "") {
		alert("����������ԭ��");
		return false;
	} else if ($("#postType").val() == 'YPZW-WY' && $("#province").val() == '') {
		alert("��ѡ����ʡ��");
		return false;
	} else if ($("#oldNeedNum").val() > 0) {
		if ($("#needNum").val() > $("#oldNeedNum").val() || $("#needNum").val() < 1) {
			alert("���������������ӻ���С��1");
			return false;
		}
	} else {
		return true;
	}
}

$(function(){
	validate({
		"deptName" : {
			required : true
		},
		"positionName" : {
			required : true
		},
		"postType" : {
			required : true
		},
		"needNum" : {
			required : true,
			custom : ['onlyNumber']
		},
		"workPlace" : {
			required : true
		},
		"hopeDate" : {
			required : true
		},
		"applyReason" : {
			required : true
		},
		"resumeToName" : {
			required : true
		},
		"addTypeCode" : {
			required: true
		},
		"employmentTypeCode" : {
			required : true
		},
		"wageRange" : {
			required : true
		},
		"regionId" : {
			required : true
		},
		"workDuty" : {
			required : true
		},
		"jobRequire" : {
			required : true
		},
		"workArrange" : {
			required : true
		},
		"assessmentIndex" : {
			required : true
		},
		"tutor" : {
			required : true
		},
		"computerConfiguration" : {
			required : true
		}
	});

	$("#plan").yxcombogrid_plan({
		gridOptions:{
			param:{
				'state':'2'
			},
			event:{
				row_dblclick:function(e,row,data){
					$("#objId").val(data.id);
					$("#deptName").val(data.deptName);
					$("#deptId").val(data.deptId);
					$("#postType").val(data.postType);//ְλ����
					$("#positionName").val(data.positionName);
					$("#positionId").val(data.positionId);
					$("#developPositionName").val(data.developPositionName);

					$("#positionLevelHidden").val(data.positionLevel);   //����
					if('YPZW-WY' == data.postType){
						initLevelWY();
					} else {
						initLevel();
					}

					$("#positionLevelHidden").val(data.positionLevel);
					$("input[type='radio'][name='apply[isEmergency]'][value='"+data.isEmergency+"']")[0].checked=true;//�Ƿ����
					$("#needNum").val(data.needNum);
					$("#hopeDate").val(data.hopeDate);
					$("#workPlace").val(data.workPlace);
					$("#wageRange").val(data.wageRange);

					$("#addModeNameHidden").val(data.addMode);  //���鲹�䷽ʽ
					initPCC();

					$("#addTypeCode").val(data.addTypeCode);//��Ա����
					if('ZYLXLZ'==data.addTypeCode){
						$("#leaveManName").attr('readonly',false);
						$("#leaveManName").removeClass('readOnlyText');
						$("#leaveManName").addClass('txt');
					} else {
						$("#leaveManName").attr('readonly',true);
						$("#leaveManName").removeClass('txt');
						$("#leaveManName").addClass('readOnlyText');
					}
					$("#leaveManName").val(data.leaveManName);
					$("#employmentTypeCode").val(data.employmentTypeCode);//�ù�����
					$("#useAreaName").val(data.useAreaName);
					$("#regionId").val(data.useAreaId);       //��������
					$("#projectType").val(data.projectType);//��Ŀ����
					$("#projectGroup").val(data.projectGroup);
					$("#projectGroupId").val(data.projectGroupId);
					$("#projectCode").val(data.projectCode);
					if(typeof(data.sex) != 'undefined' && $.trim(data.sex) != '') {
						$("input[type='radio'][name='apply[sex]'][value='"+data.sex+"']")[0].checked = true;//�Ա�
					}
					$("#age").val(data.age);
					$("#maritalStatus").val(data.maritalStatus);//����
					$("#education").val(data.education);//ѧ��
					$("#professionalRequire").val(data.professionalRequire);
					$("#workExperiernce").val(data.workExperiernce);
					$("#resumeToId").val(data.resumeToId);
					$("#resumeToName").val(data.resumeToName);
					$("#applyReason").val(data.applyReason);
					$("#jobRequire").val(data.jobRequire);
					$("#workDuty").val(data.workDuty);
					$("#workArrange").val(data.workArrange);
					$("#assessmentIndex").val(data.assessmentIndex);
					$("#uploadfileList").val(data.uploadfileList);
					$("#network").val(data.network);
					$("#device").val(data.device);
				}
			}
		}
	});
});

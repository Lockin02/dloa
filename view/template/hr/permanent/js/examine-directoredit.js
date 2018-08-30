$(document).ready(function() {
	$("#afterPositionName").yxcombogrid_jobs({
		hiddenId : 'afterPositionId',
		width : 280,
		gridOptions : {
			showcheckbox : false,
			param : {"deptId" : $("#deptId").val()}
		}
	});

	//����ǹ����ת���������ع��ʺ�ְλȷ��
	if($("#planstatus").val() == "1") {
		$("#salaryInfo").hide();
	}

	$("#summaryTable").yxeditgrid({
		objName : 'examine[summaryTable]',
		isAddOneRow : true,
		url : '?model=hr_permanent_linkcontent&action=listJson',
		type : 'view',
		param : {
			parentId : $("#parentId").val(),
			ownType : 1
		},
		colModel : [{
			display : '����Ҫ�����',
			name : 'workPoint'
		},{
			display : '����ɹ�',
			name : 'outPoint'
		},{
			display : '���ʱ��ڵ�',
			name : 'finishTime'
		},{
			name : 'ownType',
			type : 'hidden',
			value : 1
		},{
			type : 'hidden',
			display : 'id',
			name : 'id'
		}]
	});

	$("#planTable").yxeditgrid({
		objName : 'examine[planTable]',
		url : '?model=hr_permanent_linkcontent&action=listJson',
		type : 'view',
		param : {
			parentId : $("#parentId").val(),
			ownType : 2
		},
		isAddOneRow : true,
		colModel : [{
			display : '����Ҫ�����',
			name : 'workPoint'
		},{
			display : '����ɹ�������׼',
			name : 'outPoint'
		},{
			display : '���ʱ��ڵ�',
			name : 'finishTime'
		},{
			name : 'ownType',
			type : 'hidden',
			value : 2
		},{
			type : 'hidden',
			display : 'id',
			name : 'id'
		}]
	});

	$("#schemeTable").yxeditgrid({
		objName : 'examine[schemeTable]',
		url : '?model=hr_permanent_detail&action=listJson',
		param : {
			parentId : $("#parentId").val()
		},

		isAddOneRow : true,
		isAddAndDel : false,
		colModel : [{
			name : 'standardId',
			type : 'hidden'
		},{
			display : '������Ŀ',
			name : 'standard',
			type : 'hidden'
		},{
			display : '������Ŀ',
			name : 'standard',
			type : 'statictext'
		},{
			display : '����Ȩ��',
			name : 'standardProportion',
			type : 'hidden'
		},{
			display : '����Ҫ��',
			name : 'standardPoint',
			type : 'hidden'
		},{
			display : '��������',
			name : 'standardContent',
			type : 'hidden'
		},{
			display : '����Ҫ��',
			name : 'standardPoint',
			type : 'statictext',
			align:'left'
		},{
			display : '��������',
			name : 'standardContent',
			type : 'statictext',
			align:'left'
		},{
			display : '����',
			name : 'selfScore',
			type : 'hidden'
		},{
			display : '����',
			name : 'selfScore',
			type : 'statictext'
		},{
			display : '��ʦ����',
			name : 'otherScore',
			type : 'statictext'
		},{
			display : '�쵼����',
			name : 'leaderScore',
			type : 'statictext'
		},{
			display : '��ע',
			name : 'comment',
			type : 'statictext'
		},{
			type : 'hidden',
			display : 'id',
			name : 'id'
		}]
	});

	$(window.parent.document.getElementById("sub")).bind("click" ,function() {
		setinfo();

		if ($(window.parent.document.getElementById("appendHtml")).html() == "") {    //�ж��Ƿ�Ϊ���һ��������,��Ϊ����Ϊ���һ��
			alert("����дת�������Ϣ��Ϣ��");
			return false;
		} else if ($(window.parent.document.getElementById("directorComment")).val() == "") {
			alert("����д���");
			return false;
		} else if ($(window.parent.document.getElementById("permanentTypeCode")).val() == "undefined") {
			alert("����дת�������");
			return false;
		} else if ($(window.parent.document.getElementById("beforeSalary")).val() == "") {
			alert("����дת��ǰ���ʡ�");
			return false;
		} else if ($(window.parent.document.getElementById("afterSalary")).val() == "") {
			alert("����дת�����ʡ�");
			return false;
		// } else if ($(window.parent.document.getElementById("levelCode")).val() == "") {
		// 	alert("����дת����ְ����");
		// 	return false;
		// } else if ($(window.parent.document.getElementById("afterPositionName")).val() == "") {
		// 	alert("����дת����ְλ��");
		// 	return false;
		} else if ($(window.parent.document.getElementsByName("examine[permanentTypeCode]")).val() == "ZZLXTQZZ") {
			if ($(window.parent.document.getElementById("permanentDate")).val() == "") {
				alert("��ǰת����Ҫ��дת�����ڡ�");
				return false;
			}
		}
	});

	$("#directorComment").blur(function () {
		setinfo();
	});
	$("input:radio[name='examine[permanentTypeCode]']").blur(function () {
		setinfo();
	});
	$("#beforeSalary_v").blur(function () {
		setinfo();
	});
	$("#afterSalary_v").blur(function () {
		setinfo();
	});
	$("#levelCode").blur(function () {
		setinfo();
	});
	$("#afterPositionName").change(function () {
		setinfo();
	});
	$("#permanentDate").change(function () {
		setinfo();
	});
});

function setinfo(){
	var directorComment = $("#directorComment").val();    //�ܼ����
	var permanentTypeCode = $("input:radio[name='examine[permanentTypeCode]']:checked").val(); //ת������
	var beforeSalary = $("#beforeSalary").val();  //֮ǰ����
	var afterSalary = $("#afterSalary").val();  //֮����
	var afterPositionName = $("#afterPositionName").val();  //ְλ����
	var afterPositionId = $("#afterPositionId").val();  //ְλID
	var levelCode = $("#levelCode").val();  //ְλ����
	var parentId = $("#parentId").val();

	var appendHtml=
	' <input type="hidden" id="directorComment" name="examine[directorComment]" value="'+
	directorComment
	+'"/>'+
	' <input type="hidden" id="permanentTypeCode" name="examine[permanentTypeCode]" value="'+
	permanentTypeCode
	+'"/>'+
	' <input type="hidden" id="beforeSalary" name="examine[beforeSalary]" value="'+
	beforeSalary
	+'"/>'+
	' <input type="hidden" id="afterSalary" name="examine[afterSalary]" value="'+
	afterSalary
	+'"/>'+
	' <input type="hidden" id="afterPositionName" name="examine[afterPositionName]" value="'+
	afterPositionName
	+'"/>'+
	'<input type="hidden" id="afterPositionId" name="examine[afterPositionId]" value="'+
	afterPositionId
	+'"/>'+
	'<input type="hidden" id="levelCode" name="examine[levelCode]" value="'+
	levelCode
	+'"/>'+
	'<input type="hidden" id="parentid" name="examine[id]" value="'+
	parentId
	+'"/>';
	if(permanentTypeCode == 'ZZLXTQZZ') {
		var permanentDate = $("#permanentDate").val();
		appendHtml+=
		' <input type="hidden" id="permanentDate" name="examine[permanentDate]" value="'+
		permanentDate
		+'"/>';
	}
	if($(window.parent.document.getElementById("appendHtml")).html() != "") {   //����ѡ����Ȱ�ǰһ��׷�ӵ��������
		$(window.parent.document.getElementById("appendHtml")).html("");
	}
	$(window.parent.document.getElementById("appendHtml")).append(appendHtml);
}

function getRadio(){
	if($("#permanentDate").length > 0) {
		$("#permanentDate").remove();
	}
	var input = document.createElement("input");
	input.type = "text";
	input.onclick = function() { WdatePicker(); };
	input.id = "permanentDate";
	input.name = "examine[permanentDate]";
	input.readOnly = true;
	document.getElementById("ZZYJ").appendChild(input);
}

function closeRadio(){
	if($("#permanentDate").length > 0) {
		$("#permanentDate").remove();
		return;
	}
}
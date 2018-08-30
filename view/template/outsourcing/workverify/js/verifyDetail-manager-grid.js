//�����б�
var objGrid;
$(document).ready(function() {

	objGrid = $("#verifyListGrid");
	//��ʼ���б�
	initWorklog();

 });

 //��ʼ��������б�
function initWorklog(thisType,thisVal){
	//����ı�Ͳ�ѯ��ʽ���б�
	var auditObj = $("#auditType");
	if(thisType){
		if(thisVal == auditObj.val()){
			return false;
		}else{
			auditObj.val(thisVal);
			objGrid.empty();
		}
	}

	var paramObj = {
		managerAuditState : '0',
		suppState : '1',
		projectIdArr : $("#projectIds").val()
	};
	if(auditObj.val() == "0"){
		//�������
		auditNormalList(paramObj);
	}else{
		//����Ŀͳ�����
		auditForPersonList(paramObj);
	}
}

 //��������б�
function auditNormalList(paramObj){
	if(objGrid.children().length != 0){
		objGrid.yxeditgrid("setParam",paramObj).yxeditgrid("processData");
	}else{
	$("#verifyListGrid").yxeditgrid({
		objName : 'workVerify[rentList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {'managerAuditState':'0','suppState':'1','projectIdArr':$("#projectIds").val()},
		type : 'view',
		dir : 'ASC',
		colModel : [{
					display : 'id',
					name : 'id',
					type : 'hidden'
				}, {
					display : '����',
					name : 'officeName',
					type : 'txt',
					width :50
				},   {
					display : 'ʡ��',
					name : 'province',
					width :50
				}, {
					display : '����',
					name : 'outsourcingName',
					width :50
				}, {
					display : '��Ŀ����',
					name : 'projectName',
					type : 'txt',
					width : 120
				},  {
					display : '��Ŀ���',
					name : 'projectCode',
					type : 'txt',
					width : 100
				},{
					display : '�����ͬ��',
					name : 'outsourceContractCode',
					type : 'txt',
					width : 80
				}, {
					display : '�����˾',
					name : 'outsourceSupp',
					type : 'txt',
					width : 80
				},  {
					display : '������',
					name : 'principal',
					type : 'txt',
					width : 50
				},{
					display : '�ܽ���',
					name : 'scheduleTotal',
					type : 'txt',
					width : 50
				},{
					display : '���ڽ���',
					name : 'presentSchedule',
					width : 50
				},{
					display : '����',
					name : 'userName',
					type : 'txt',
					width :50,
					readonly:true,
					process : function(v,row){
							return "<a href='#' onclick='showModalWin(\"?model=engineering_worklog_esmworklog&action=getDetailList&projectId=" + row.projectId +"&createId="+row.userId+"&beginDate="+row.beginDate+"&endDate="+row.endDate+"\",\"1\",\"��Ա��־\")'>" + v + "</a>";
					}
				},{
					display : '���ڿ�ʼ',
					name : 'beginDate',
					width : 70
				},{
					display : '���ڽ���',
					name : 'endDate',
					width : 70
				},{
					display : '�Ƽ�����',
					name : 'feeDay',
					width : 50
				},{
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(1);">ȷ��</a>',
					name : 'managerAuditStateName',
					width : 40,
					process : function(v,row){
						if(row.managerAuditState == 1){
								return "<span class='blue'>��</span>";
						}else{
							return '-';
						}
					},
					event : {
						click : function(){
							//���к�
							var rowNum = $(this).data("rowNum");
							var managerAuditState=objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'managerAuditState').val();
							//��˲���
							if(managerAuditState==0){
								auditAction(rowNum,'1');

							}else if(managerAuditState==1){
								auditAction(rowNum,'0');
							}
						}
					}
				}, {
					display : '������',
					name : 'managerAuditRemark',
					align : 'left',
					process : function(v,row){
							return '<input type="text" class="txtmiddle" style="width:98%" id="managerAuditRemark'+ row.id +'"/>';
					}
				}, {
					display : '������������',
					name : 'serverAuditRemark',
					align : 'left'
				},{
					display : 'managerAuditState',
					name : 'managerAuditState',
					type : 'hidden'
				},{
					display : '�����ڿ�ʼ',
					name : 'beginDatePM',
					width : 70,
					process : function(v,row){
							return '<input type="text" class="txtmiddle" onfocus="WdatePicker()" readonly style="width:98%" id="beginDatePM'+ row.id +'"/>';
					}
				},{
					display : '�����ڽ���',
					name : 'endDatePM',
					width : 70,
					process : function(v,row){
							return '<input type="text" class="txtmiddle" onfocus="WdatePicker()" readonly style="width:98%" id="endDatePM'+ row.id +'"/>';
					}
				},{
					display : '����Ƽ�����',
					name : 'feeDayPM',
					width : 50,
					process : function(v,row){
							return '<input type="text" class="txtmiddle" style="width:98%" id="feeDayPM'+ row.id +'"/>';
					}
				},{
					display : '���º˶�',
					width : 50,
					process : function(v,row){
							return '<a href="javascript:void(0);" style="color:red;">���º˶�</a>';
					},
					event : {
						click : function(){
							//���к�
							var rowNum = $(this).data("rowNum");
							backAction(rowNum);
						}
					}
				}]
	});}
}

//����Ŀͳ��
function auditForPersonList(paramObj){
	if(objGrid.children().length == 0){
		//��ȡͳ�Ƶ�����
		var tbStr ='<table class="main_table">' +
				'<thead>' +
					'<tr class="main_tr_header">' +
						'<th width="5%">���</th>' +
						'<th width="5%"></th>' +
						'<th width="20%">ȷ������</th>' +
						'<th width="10%">����</th>' +
						'<th width="10%">ʡ��</th>' +
						'<th width="20%">��Ŀ����</th>' +
						'<th width="20%">��Ŀ���</th>' +
						'<th width="10%"><a href="javascript:void(0);" onclick="auditBatchSet(1);">ȷ��</a></th>' +
					'</tr>' +
				'</thead>' +
				'<tbody id="myTbody"></tbody>' +
			'</table>';

		//��ͷ����
		objGrid.append(tbStr);
	}

	//���ݼ���
	reloadPersonData(paramObj);
}

//�����б�����
function reloadPersonData(paramObj){
	//��ȡ��������Ⱦ
	var logArr;
	$.ajax({
		type : 'POST',
		url : "?model=outsourcing_workverify_verifyDetail&action=auditJsonForManager",
		data : paramObj,
	    async: false,
		success : function(data) {
			logArr = eval("(" + data + ")");
		}
	});

	if(logArr.length > 0){
		var tbodyStr = "";
		var j,trClass;
		for(var i = 0;i< logArr.length ; ++i){
			j = i + 1;
			trClass = i%2 == 0 ? 'tr_odd' : 'tr_even';
			tbodyStr += '<tr class="'+trClass+'" id="tr'+ i +'">' +
					'<td>'+ j +'</td>' +
					'<td><a href="javascript:void(0)" onclick="changeShow(this,' + i + ',\''+ logArr[i].parentId +'\',\''+ logArr[i].projectId +'\');">+</a></td>' +
					'<td>' + logArr[i].parentBeginDate + '~' + logArr[i].parentEndDate + '</td>' +
					'<td>' + logArr[i].officeName + '</td>' +
					'<td>' + logArr[i].province + '</td>' +
					'<td>' + logArr[i].projectName + '</td>' +
					'<td>' + logArr[i].projectCode +  '' +
						'<input type="hidden" id="parentId'+ i +'" value="'+ logArr[i].parentId +'"/>' +
						'<input type="hidden" id="projectId'+ i +'" value="'+ logArr[i].projectId +'"/>' +
						'<input type="hidden" id="managerAuditState'+ i +'" value="0"/>' +
						'<input type="hidden" id="assessResult'+ i +'" value="0"/>' +
					'</td>' +
					'<td id="managerAuditStateName'+ i +'" onclick="auditAction('+ i +',1);"> - </td>' +
				'</tr>';
		}
		//��������
		$("#myTbody").empty().append(tbodyStr);
	}else{
		//��������
		$("#myTbody").empty();
	}
}

//������ʾ����
function changeShow(thisObj,rowNum,parentId,projectId){
	thisObj = $(thisObj);
	if(thisObj.text() == '+'){
		thisObj.text('-');
		loadDetail(rowNum,parentId,projectId);
	}else{
		thisObj.text('+');
		$("#tb_" + rowNum).hide();
	}
}

//��ȡ��ϸ��
function loadDetail(rowNum,parentId,projectId){
	var tbObj = $("#tb_" + rowNum);
	if(tbObj.length == 1){
		tbObj.show();
		return false;
	}
	//�����ж�
	var paramObj = {
		projectId : projectId,
		parentId : parentId,
		managerAuditState : '0',
		suppState : '1'
	};

	//��ȡ��������Ⱦ
	var logArr;
	$.ajax({
		type : 'POST',
		url : "?model=outsourcing_workverify_verifyDetail&action=listJson",
		data : paramObj,
	    async: false,
		success : function(data) {
			logArr = eval("(" + data + ")");
		}
	});

	if(logArr.length > 0){
		//��ȡͳ�Ƶ�����
		var tbStr ='<tr id="tb_'+ rowNum +'"><td></td><td colspan="99"><table class="main_table">' +
				'<thead>' +
					'<tr class="main_tr_header">' +
						'<th width="3%">���</th>' +
						'<th width="7%">����</th>' +
						'<th width="15%">�����ͬ��</th>' +
						'<th width="15%">�����˾</th>' +
						'<th width="7%">������</th>' +
						'<th width="7%">�ܽ���</th>' +
						'<th width="7%">���ڽ���</th>' +
						'<th width="7%">����</th>' +
						'<th width="10%">���ڿ�ʼ</th>' +
						'<th width="10%">���ڽ���</th>' +
						'<th>�Ƽ�����</th>' +
					'</tr>' +
				'</thead>' +
				'<tbody>';
		var j,trClass;
		for(var i = 0;i< logArr.length ; ++i){
			j = i + 1;
			trClass = i%2 == 0 ? 'tr_odd' : 'tr_even';
			tbStr += '<tr class="'+trClass+'">' +
					'<td>'+ j +'</td>' +
					'<td>' + logArr[i].outsourcingName + '</td>' +
					'<td>' + logArr[i].outsourceContractCode + '</td>' +
					'<td>' + logArr[i].outsourceSupp + '</td>' +
					'<td>' + logArr[i].principal + '</td>' +
					'<td>' + logArr[i].scheduleTotal + '</td>' +
					'<td>' + logArr[i].presentSchedule + '</td>' +
					'<td> <a href="#" onclick="showModalWin(\'?model=engineering_worklog_esmworklog&action=getDetailList&projectId=' + logArr[i].projectId +'&createId='+logArr[i].userId+'&beginDate='+logArr[i].beginDate+'&endDate='+logArr[i].endDate+'\',\'1\',\'��Ա��־\')">'+ logArr[i].userName + '</a></td>' +
					'<td>' + logArr[i].beginDate + '</td>' +
					'<td>' + logArr[i].endDate + '</td>' +
					'<td style="text-align:left;">' + logArr[i].feeDay + '</td>' +
				'</tr>';
		}

		tbStr += '</tbody></table></td></tr>';

		//��ͷ����
		$("#tr" + rowNum).after(tbStr);
	}
}

 //����������˽��
function auditBatchSet(assessResult){
	//����ı�Ͳ�ѯ��ʽ���б�
	var auditObj = $("#auditType");
	if(auditObj.val() == "0"){
		var rows = objGrid.yxeditgrid("getCmpByCol","managerAuditState");
		if(rows.length > 0){
			rows.each(function(){
					//��˲���
					auditAction($(this).data('rowNum'),assessResult);
			});
		}
	}else{
		var rows = $("tr[id^='tr']");
		if(rows.length){
			rows.each(function(i,n){
				//��˲���
				auditAction(i,assessResult);
			});
		}
	}
}

//���
function auditAction(rowNum,assessResult){
	//��������ųⷽ��
	auditExclude(rowNum,assessResult);
}

//����ų���÷���
function auditExclude(rowNum,assessResult){
	//����ı�Ͳ�ѯ��ʽ���б�
	var auditObj = $("#auditType");
	if(auditObj.val() == "0"){
		if(1 == assessResult*1){
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'managerAuditStateName').html("<span class='blue'>��</span>");
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'managerAuditState').val(assessResult);
		}else{
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'managerAuditStateName').html('-');
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'managerAuditState').val(assessResult);
		}
	}else{
			if(1 == assessResult*1){
				$("#assessResult" + rowNum).val(1);
				$("#managerAuditState" + rowNum).val(assessResult);
				$("#" +"managerAuditStateName" + rowNum).html("<span class='blue'>��</span>");
			}else{
				$("#" + "managerAuditStateName"+ rowNum).html('-');
			}

	}
}
//�������
function auditBatch(){
	//����ı�Ͳ�ѯ��ʽ���б�
	var auditObj = $("#auditType");
	var isNull = true;

	//�ж���˷�ʽ
	if(auditObj.val() == "0"){
		var objArr = objGrid.yxeditgrid("getCmpByCol", "managerAuditState");

		if(objArr.length != 0){
			if(!confirm('ȷ��Ҫ�����ѡ�еļ�¼��')){
				return false;
			}
			objArr.each(function(i,n){
				if(this.value == "1"){
					isNull = false;

					//����������ȡ��Ӧֵ
					var rowNum = $(this).data('rowNum');
					var id =  objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'id').val();
					var managerAuditState =  objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'managerAuditState').val();
					//���
					auditLog(id,managerAuditState);
				}
			});
		}
	}else{
		var objArr = $("input[id^='managerAuditState']");
		if(objArr.length != 0){
			if(!confirm('ȷ��Ҫ�����ѡ�еļ�¼��')){
				return false;
			}
			objArr.each(function(i,n){
				if(this.value == "1"){
					isNull = false;

					//����������ȡ��Ӧֵ
					var managerAuditState = $("#managerAuditState" + i).val();
					var projectId = $("#projectId" + i).val();
					var parentId = $("#parentId" + i).val();
					//���
					auditLogForManager(projectId,parentId,managerAuditState);
				}
			});
		}
	}

	if(isNull == true){
		alert('û����Ҫȷ����˵ļ�¼');
	}else{
		//���¼����б�
		show_page();
	}
}

//��˹�����
function auditLog(id,managerAuditState){
	var managerAuditRemark = $("#managerAuditRemark" + id).val();
	//��ȡ��������Ⱦ
	$.ajax({
		type : 'POST',
		url : "?model=outsourcing_workverify_verifyDetail&action=managerAudit",
		data : {
			id : id,
			managerAuditState : managerAuditState,
			managerAuditRemark : managerAuditRemark
		},
	    async: false,
		success : function(data) {
			if(data == '1'){

			}else{
				alert('���ʧ��');
			}
		}
	});
}

/**
 * ˢ���б�
 */
function show_page(){
	//����ı�Ͳ�ѯ��ʽ���б�
	var auditObj = $("#auditType");
	if(auditObj.val() == "0"){
		objGrid.yxeditgrid('processData');
	}else{
		var paramObj = {
			managerAuditState : '0',
			suppState : '1',
			projectIdArr : $("#projectIds").val()
		};
		//���ݼ���
		reloadPersonData(paramObj);}
}

//����Ŀ���
function auditLogForManager(projectId,parentId,managerAuditState){
	//�����ж�
	var paramObj = {
		projectId : projectId,
		parentId : parentId,
		managerAuditState : managerAuditState
	};

	//��ȡ��������Ⱦ
	$.ajax({
		type : 'POST',
		url : "?model=outsourcing_workverify_verifyDetail&action=auditAllForManager",
		data : paramObj,
	    async: false,
		success : function(data) {
			if(data == '1'){

			}else{
				alert('���ʧ��');
			}
		}
	});
}

//��ز���
function backAction(rowNum){
	var id = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"id").val();
	var feeDay = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"feeDay").val();
	var beginDatePM = $("#beginDatePM" + id).val();
	var endDatePM = $("#endDatePM" + id).val();
	var feeDayPM = $("#feeDayPM" + id).val();
	var managerAuditRemark =$("#managerAuditRemark" + id).val();
	if(beginDatePM==''||endDatePM==''||feeDayPM==''){
		alert("����д�˶���Ϣ");
		return false;
	}
	if(feeDayPM>feeDay){
		alert("����Ƽ��������ܴ��ڼƼ�����");
		return false;
	}
	if(confirm('ȷ��Ҫ���º˶Ը���Ա��������')){
		//���
		backLog(id,beginDatePM,endDatePM,feeDayPM,managerAuditRemark);
	}
}

//���
function backLog(id,beginDatePM,endDatePM,feeDayPM,managerAuditRemark){
	//��ȡ��������Ⱦ
	$.ajax({
		type : 'POST',
		url : "?model=outsourcing_workverify_verifyDetail&action=managerAuditBack",
		data : {
			id : id,
			beginDatePM : beginDatePM,
			endDatePM : endDatePM,
			feeDayPM : feeDayPM,
			managerAuditRemark : managerAuditRemark
		},
	    async: false,
		success : function(data) {
			if(data == '1'){
				show_page();
			}else{
				alert('���ʧ��');
			}
		}
	});
}

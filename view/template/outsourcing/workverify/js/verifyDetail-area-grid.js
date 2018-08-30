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
		managerAuditState : '1',
		serverAuditState:'1',
		areaAuditState:'0',
		suppState : '1',
		officeIdArr : $("#officeIds").val()
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
		param : {'managerAuditState':'1','serverAuditState':'1','areaAuditState':'0','officeIdArr':$("#officeIds").val()},
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
					display : 'PM�˶Ա��ڿ�ʼ',
					name : 'beginDatePM',
					width : 70
				},{
					display : 'PM�˶Ա��ڽ���',
					name : 'endDatePM',
					width : 70
				},{
					display : 'PM�˶ԼƼ�����',
					name : 'feeDayPM',
					width : 50
				},{
					display : '��Ŀ��������',
					name : 'managerAuditState',
					width : 50,
					process : function(v,row) {
						if (v == "1") {
							return "<span style='color:blue'>��("+row.managerName+")</span>";
						} else {
							return "<span style='color:red'>-</span>";
						}
					}
				},{
					display : '����������',
					name : 'serverAuditState',
					width : 50,
					process : function(v,row) {
						if (v == "1") {
							return "<span style='color:blue'>��("+row.serverManagerName+")</span>";
						} else {
							return "<span style='color:red'>-</span>";
						}
					}
				}, {
					display : '��˱�ע',
					name : 'managerAuditRemark',
					type : 'txt',
					align : 'left',
					width : 120,
					process : function(v,row){
						if(row.managerAuditRemark!=""&&row.serverAuditRemark!=''){
							return row.managerName+":<br/>"+row.managerAuditRemark+"<br/>"+row.serverManagerName+":<br/>"+row.serverAuditRemark;
						}else if(row.managerAuditRemark!=""&&row.serverAuditRemark==''){
							return row.managerName+":<br/>"+row.managerAuditRemark;
						}else if(row.managerAuditRemark==""&&row.serverAuditRemark!=''){
							return row.serverManagerName+":<br/>"+row.serverAuditRemark;
						}else{
							return '';
						}
					}
				},{
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(1);">ȷ��</a>',
					name : 'areaAuditStateName',
					width : 40,
					process : function(v,row){
						if(row.areaAuditState == 1){
								return "<span class='blue'>��</span>";
						}else{
							return '-';
						}
					},
					event : {
						click : function(){
							//���к�
							var rowNum = $(this).data("rowNum");
							var areaAuditState=objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditState').val();
							//��˲���
							if(areaAuditState==0){
								auditAction(rowNum,'1');

							}else if(areaAuditState==1){
								auditAction(rowNum,'0');
							}
						}
					}
				}, {
					display : '������',
					name : 'areaAuditRemark',
					align : 'left',
					process : function(v,row){
							return '<input type="text" class="txtmiddle" style="width:98%" id="areaAuditRemark'+ row.id +'"/>';
					}
				},{
					display : '���',
					width : 50,
					process : function(v,row){
							return '<a href="javascript:void(0);" style="color:red;">���</a>';
					},
					event : {
						click : function(){
							//���к�
							var rowNum = $(this).data("rowNum");
							backAction(rowNum);
						}
					}
				},{
					display : 'areaAuditState',
					name : 'areaAuditState',
					type : 'hidden'
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
						'<input type="hidden" id="areaAuditState'+ i +'" value="0"/>' +
						'<input type="hidden" id="assessResult'+ i +'" value="0"/>' +
					'</td>' +
					'<td id="areaAuditStateName'+ i +'" onclick="auditAction('+ i +',1);"> - </td>' +
				'</tr>';
		}
		//��������
		$("#myTbody").empty().append(tbodyStr);
	}else{
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
		managerAuditState : '1',
		serverAuditState : '1',
		areaAuditState : '0',
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
						'<th width="5%">����</th>' +
						'<th width="8%">�����ͬ��</th>' +
						'<th width="8%">�����˾</th>' +
						'<th width="7%">������</th>' +
						'<th width="5%">�ܽ���</th>' +
						'<th width="5%">���ڽ���</th>' +
						'<th width="5%">����</th>' +
						'<th width="8%">���ڿ�ʼ</th>' +
						'<th width="8%">���ڽ���</th>' +
						'<th width="5%">�Ƽ�����</th>' +
						'<th width="5%">PM�˶Ա��ڿ�ʼ</th>' +
						'<th width="5%">PM�˶Ա��ڽ���</th>' +
						'<th width="5%">PM�˶ԼƼ�����</th>' +
						'<th width="5%">��Ŀ��������</th>' +
						'<th width="5%">����������</th>' +
						'<th>��˱�ע</th>' +
					'</tr>' +
				'</thead>' +
				'<tbody>';
		var j,trClass;
		for(var i = 0;i< logArr.length ; ++i){
			j = i + 1;
			trClass = i%2 == 0 ? 'tr_odd' : 'tr_even';
			if( logArr[i].managerAuditRemark!=""&& logArr[i].serverAuditRemark!=''){
				var  remark= logArr[i].managerName+":<br/>"+ logArr[i].managerAuditRemark+"<br/>"+ logArr[i].serverManagerName+":<br/>"+ logArr[i].serverAuditRemark;
			}else if( logArr[i].managerAuditRemark!=""&& logArr[i].serverAuditRemark==''){
				var  remark= logArr[i].managerName+":<br/>"+ logArr[i].managerAuditRemark;
			}else if( logArr[i].managerAuditRemark==""&& logArr[i].serverAuditRemark!=''){
				var  remark= logArr[i].serverManagerName+":<br/>"+ logArr[i].serverAuditRemark;
			}else{
				var  remark='';
			}
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
					'<td>' + logArr[i].feeDay + '</td>' +
					'<td>' + logArr[i].beginDatePM + '</td>' +
					'<td>' + logArr[i].endDatePM + '</td>' +
					'<td>' + logArr[i].feeDayPM + '</td>' +
					'<td>��('+ logArr[i].managerName +')</td>' +
					'<td>��('+ logArr[i].serverManagerName +')</td>' +
					'<td style="text-align:left;">' + remark + '</td>' +
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
		var rows = objGrid.yxeditgrid("getCmpByCol","areaAuditState");
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
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditStateName').html("<span class='blue'>��</span>");
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditState').val(assessResult);
		}else{
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditStateName').html('-');
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditState').val(assessResult);
		}
	}else{
			if(1 == assessResult*1){
				$("#assessResult" + rowNum).val(1);
				$("#areaAuditState" + rowNum).val(assessResult);
				$("#" +"areaAuditStateName" + rowNum).html("<span class='blue'>��</span>");
			}else{
				$("#" + "areaAuditStateName"+ rowNum).html('-');
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
		var objArr = objGrid.yxeditgrid("getCmpByCol", "areaAuditState");

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
					var areaAuditState =  objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditState').val();
					//���
					auditLog(id,areaAuditState);
				}
			});
		}
	}else{
		var objArr = $("input[id^='areaAuditState']");
		if(objArr.length != 0){
			if(!confirm('ȷ��Ҫ�����ѡ�еļ�¼��')){
				return false;
			}
			objArr.each(function(i,n){
				if(this.value == "1"){
					isNull = false;

					//����������ȡ��Ӧֵ
					var areaAuditState = $("#areaAuditState" + i).val();
					var projectId = $("#projectId" + i).val();
					var parentId = $("#parentId" + i).val();
					//���
					auditLogForManager(projectId,parentId,areaAuditState);
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
function auditLog(id,areaAuditState){
	var areaAuditRemark = $("#areaAuditRemark" + id).val();
	//��ȡ��������Ⱦ
	$.ajax({
		type : 'POST',
		url : "?model=outsourcing_workverify_verifyDetail&action=areaAudit",
		data : {
			id : id,
			areaAuditState : areaAuditState,
			areaAuditRemark : areaAuditRemark
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
			managerAuditState : '1',
			serverAuditState : '1',
			areaAuditState : '0',
			suppState : '1',
			officeIdArr : $("#officeIds").val()
		};
		//���ݼ���
		reloadPersonData(paramObj);}
}

//����Ŀ���
function auditLogForManager(projectId,parentId,areaAuditState){
	//�����ж�
	var paramObj = {
		projectId : projectId,
		parentId : parentId,
		areaAuditState : '1'
	};

	//��ȡ��������Ⱦ
	$.ajax({
		type : 'POST',
		url : "?model=outsourcing_workverify_verifyDetail&action=auditAllForArea",
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
	var areaAuditRemark = $("#areaAuditRemark" + id).val();
	if(areaAuditRemark==''){
		alert("����д������");
		return false;
	}
	if(confirm('ȷ�ϴ�أ�')){
		//���
		$.ajax({
			type : 'POST',
			url : "?model=outsourcing_workverify_verifyDetail&action=areaAuditBack",
			data : {
				id : id,
				serverAuditState : '0',
				areaAuditRemark : areaAuditRemark
			},
		    async: false,
			success : function(data) {
				if(data == '1'){
					//���¼����б�
					show_page();
				}else{
					alert('���ʧ��');
				}
			}
		});
	}
}

//�����б�
var objGrid;
$(document).ready(function() {
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
					display : 'areaAuditState',
					name : 'areaAuditState',
					type : 'hidden'
				}]
	});

	objGrid = $("#verifyListGrid");

 });

 //����������˽��
function auditBatchSet(assessResult){
		var rows = objGrid.yxeditgrid("getCmpByCol","areaAuditState");
		if(rows.length > 0){
			rows.each(function(){
					//��˲���
					auditAction($(this).data('rowNum'),assessResult);
			});
		}
}

//���
function auditAction(rowNum,assessResult){
	//��������ųⷽ��
	auditExclude(rowNum,assessResult);
}

//����ų���÷���
function auditExclude(rowNum,assessResult){
		if(1 == assessResult*1){
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditStateName').html("<span class='blue'>��</span>");
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditState').val(assessResult);
		}else{
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditStateName').html('-');
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditState').val(assessResult);
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
	}else{}
}

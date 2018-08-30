var provinceArr; //�����ʡ������

$(document).ready(function() {
	//ʡ��
	provinceArr = getProvince();
	$("#wholeListInfo").yxeditgrid({
		objName : 'workVerify[wholeList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {
			parentId :$("#id").val(),
			outsourcingArr :'HTWBFS-01,HTWBFS-03',
			purchAuditState:'0'
		},
		dir : 'ASC',
		isAddAndDel : false,
		colModel : [  {
					type : 'hidden',
					name : 'id',
					display : 'id'
				},{
					display : '����',
					name : 'officeName',
					type : 'statictext',
					width :50
				},  {
					display : 'ʡ��',
					name : 'province',
					type : 'txt',
					width :50,
					type:'statictext'
				}, {
					display : '����',
					name : 'outsourcingName',
					type : 'statictext',
					width :50
				},{
					display : '��Ŀ����',
					name : 'projectName',
					type : 'statictext',
					width : 120
				},  {
					display : '��Ŀ���',
					name : 'projectCode',
					type : 'statictext',
					width : 100
				},{
					display : '�����ͬ��',
					name : 'outsourceContractCode',
					type : 'statictext',
					width : 80
				},  {
					display : '�����˾',
					name : 'outsourceSupp',
					type : 'statictext',
					width : 80
				},  {
					display : '������',
					name : 'principal',
					type : 'statictext',
					width : 70
				},{
					display : '�ܽ���',
					name : 'scheduleTotal',
					type : 'statictext',
					width : 70
				},{
					display : '���ڽ���',
					name : 'presentSchedule',
					type : 'statictext',
					width : 70
				}, {
					display : '�ܽ��',
					name : 'feeTotal',
					width : 100
				}]
	});

	$("#rentListInfo").yxeditgrid({
				objName : 'workVerify[rentList]',
				url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
				param : {
					parentId :$("#id").val(),
					outsourcingArr :'HTWBFS-02',
					purchAuditState:'0'
				},
				dir : 'ASC',
				isAddAndDel : false,
				realDel : true,
				colModel : [ {
					type : 'hidden',
					name : 'id',
					display : 'id'
				},{
					display : '����',
					name : 'officeName',
					type : 'statictext',
					width :50
				},  {
					display : 'ʡ��',
					name : 'province',
					type : 'statictext',
					width :50
				}, {
					display : '��Ŀ����',
					name : 'projectName',
					type : 'statictext',
					width : 120
				}, {
					display : '��Ŀ���',
					name : 'projectCode',
					type : 'statictext',
					width : 100
				},{
					display : '�����ͬ��',
					name : 'outsourceContractCode',
					type : 'statictext',
					width : 80
				},  {
					display : '�����˾',
					name : 'outsourceSupp',
					type : 'statictext',
					width : 80
				}, {
					display : '������',
					name : 'principal',
					type : 'statictext',
					width : 70
				},{
					display : '����',
					name : 'userName',
					type : 'statictext',
					width :80,
					process : function(v,row){
							return "<a href='#' onclick='showModalWin(\"?model=engineering_worklog_esmworklog&action=getDetailList&projectId=" + row.projectId +"&createId="+row.userId+"&beginDate="+row.beginDate+"&endDate="+row.endDate+"\",\"1\",\"��Ա��־\")'>" + v + "</a>";
					},
					readonly:true
				}, {
					display : '���ڿ�ʼ',
					name : 'beginDate',
					type : 'statictext',
					width : 70
				},{
					display : '���ڽ���',
					name : 'endDate',
					type : 'statictext',
					width : 70
				},{
					display : '�Ƽ�����',
					name : '',
					type : 'statictext',
					width : 50,
					process : function(v,row){
							return row.feeDay;
					}
				},{
					display : '�Ƽ�����',
					name : 'feeDay',
					type : 'hidden',
					width : 50
				}, {
					display : '����',
					name : 'confirmFee',
					width : 50,
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //�ж��Ƿ�Ϊ����
								if (isNaN(this.value)) {
									this.value = "";
								}else{
								}
							}
							var rownum = $(this).data('rowNum');// �ڼ���
							var colnum = $(this).data('colNum');// �ڼ���
							var grid = $(this).data('grid');// ������
							var feeDay=grid.getCmpByRowAndCol(rownum, 'feeDay').val();
							grid.getCmpByRowAndCol(rownum, 'feeTotal').val(feeDay*this.value);
						}
					}
				}, {
					display : '�ܽ��',
					name : 'feeTotal',
					width : 80
				}]
					});

 });

		         //ֱ���ύ
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_workverify_workVerify&action=edit&addType=submit";
}
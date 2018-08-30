var scoreLevelArr;

$(function() {
	//������־
	$("#esmweeklogTable").yxeditgrid({
		url : '?model=engineering_worklog_esmworklog&action=listJson',
		type : 'view',
		param : {
			weekId : $("#weekId").val()
		},
		tableClass : 'form_in_table',
		colModel : [ {
				display : 'ִ������',
				name : 'executionDate',
				width : 80
			}, {
				display : '���ڵ�',
				name : 'provinceCity',
				width : 80
			}, {
				display : '����״̬',
				name : 'workStatus',
				width : 60,
				datacode : 'GXRYZT'
			}, {
				display : '��Ŀ���',
				name : 'projectCode',
				width : 130
			},{
				display : '��Ŀ����',
				name : 'projectName',
				width : 200
			},{
				display : '��������',
				name : 'activityName',
				width : 150
			},{
				display : '�����',
				name : 'workloadDay',
				width : 70,
				process : function(v,row){
					return v + " " + row.workloadUnitName ;
				}
			}, {
				display : '����',
				name : 'costMoney',
				width : 70,
				process : function(v,row){
					if(v * 1 == 0 || v == ''){
						return v;
					}else{
						return "<a href='javascript:void(0)' onclick='viewCost(\"" + row.id + "\",1)' title='����鿴����'>" + moneyFormat2(v) + "</a>";
					}
				}
			}, {
				display : '��������',
				name : 'description'
			}
		]
	});

	//����������Ⱦ
	var indexTblObj = $("#indexTbl");
	if(indexTblObj.html() == ""){
		indexTblObj.html('�ܱ���Ӧ��Ŀû�����ÿ���ָ�꣬��������Ŀ�н�������');
		$("#submitBtn").attr('disabled',true);
	}

	//��ʼ����ֵ����
	scoreLevelArr = $("input[id^='score']");

	//��һ����ʼ��
	changeOption(0);
});

//ָ���޸��¼�
function changeOption(thisNo){
	var thisOption = $("#option"+thisNo).find("option:selected");

	//ѡ���ֵ
	$("#optionId" + thisNo).val(thisOption.attr("optionId"));
	$("#optionName" + thisNo).val(thisOption.attr("optionName"));

	var allScore = 0;
	//�ܷ�ֵ����
	$("select[id^='option']").each(function(i,n){
		allScore = accAdd(allScore,this.value,0);
	});

	var thisLevel ;
	scoreLevelArr.each(function(i,n){
		if(allScore >= this.value*1){
			thisLevel = $(this).attr("levelName");
			return false;
		}
	});
	$("#rsScore").val(allScore);
	$("#rsLevel").val(thisLevel);
	$("#scoreShow").html(allScore);
}

//�����־
function backLog(){
	if(confirm('ȷ�ϴ���ܱ���')){
		$.ajax({
		    type: "POST",
		    url: "?model=engineering_worklog_esmweeklog&action=backLog",
		    data: { 'id' : $("#weekId").val() },
		    async: false,
		    success: function(data){
		   		if(data == '1'){
					alert('��سɹ�');
					window.opener.show_page(1);
					window.close();
		   	    }else{
					alert('���ʧ��');
					window.opener.show_page(1);
					window.close();
		   	    }
			}
		});
	}
}

//����鿴����ҳ��
function viewCost(worklogId){
	var url = "?model=engineering_worklog_esmworklog&action=toView&id=" + worklogId;
	var height = 800;
	var width = 1150;
	window.open(url, "�鿴��־��Ϣ",
	'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
			+ width + ',height=' + height);
}
var show_page = function(page) {
	$("#checkacceptGrid").yxgrid("reload");
};
$(function() {
	$("#checkacceptGrid").yxgrid({
		model : 'contract_checkaccept_checkaccept',
		title : '��ͬ���յ�',
		isAddAction : false,
		isEditAction :false,
		isViewAction :false,
		isDelAction :false,
		isOpButton : false,
		param:{checkStatus:$("#checkStatus").val()},
		//����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'confirmStatus',
			display : 'ȷ��',
			sortable : true,
			process : function(v){
				if(v == "��ȷ��"){
					return '<img title="��ȷ��" src="images/icon/ok3.png">';
				}
			},
			width : 20,
			align : 'center'
		}, {
			name : 'checkStatus',
			display : '����',
			sortable : true,
			process : function(v){
				if(v == "������"){
					return '<img title="������" src="images/icon/ok3.png">';
				}
			},
			width : 20,
			align : 'center'

		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 120,
			process : function(v,row){
				if(row.isChange == 1){
					return "<span style='color:red'>"+v+"</span>"
				}else{
					return v;
				}
			}
		}, {
			name : 'realEndDateView',
			display : '��ͬ���ʱ��',
			sortable : true,
            align : 'center',
            process : function(v){
                if(v == ""){
                    return "-"
                }else{
                    return v;
                }
            }
		}, {
			name : 'clause',
			display : '���տ���',
			sortable : true
		}, {
			name : 'checkDate',
			display : 'Ԥ����������',
			width : 125,
			sortable : true,
			process : function(v,row){
				if(row.confirmStatus == '��ȷ��' && row.checkStatus == '������'){
					return v+'<span class="blue" onclick = "changeHistory('+row.id+');"> <img title="�鿴�����ʷ" src="images/icon/view.gif"></span>';
				}else if(row.confirmStatus == '��ȷ��'){
					return '<input type="text" id="checkDate'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>'+'<span class="blue" onclick = "changeHistory('+row.id+');"> <img title="�鿴�����ʷ" src="images/icon/view.gif"></span>';
				}else{
					return '<input type="text" id="checkDate'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>'+'<span class="blue" onclick = "changeHistory('+row.id+');"> <img title="�鿴�����ʷ" src="images/icon/view.gif"></span>';
				}
			}
		}, {
			name : 'checkDateOld',
			display : 'Ԥ����������Old',
			process : function(v,row){
				if(row.confirmStatus == '��ȷ��'){
					return '<input type="text" id="checkDateOld'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>';
				}else if(row.checkStatus == '������'){
					return v;
				}else{
					return '<input type="text" id="checkDateOld'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>';
				}
			},
			hide : 'true'
		}, {
			name : 'isSend',
			display : '������������',
			sortable : true,
			width : 80
		}, {
			name : 'remind',
			display : '����δ��������',
			sortable : true,
			process: function(v){
				return "������"+v+"��";
			}
		}, {
			name : 'realCheckDate',
			display : 'ʵ����������',
			sortable : true,
			process : function(v,row){
				if(row.checkStatus == '������'){
					return v;
				}else{
					return '<input type="text" id="realCheckDate'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>';
				}
			}
		}, {
			name : 'checkFile',
			display : '�����ı��ϴ�',
			sortable : true,
			process : function(v,row){
				if(row.checkStatus == '������'){
					return v;
				}else{
					return v+'<input type="button" value="�ı��ϴ�" onclick = "uploadfile('+row.id+');"/>'
				}
			}
		}, {
			name : 'deal',
			display : '����',
			sortable : true,
			width : 40,
			process : function(v,row){
				if(row.confirmStatus == 'δȷ��'){
					return '<input type="button" value="ȷ��" onclick = "confirmDate('+row.id+');"/>';
				}else if(row.confirmStatus == '��ȷ��' && row.checkStatus == 'δ����'){
					var temp = 1;
					if(row.checkFile == "" || row.checkFile == "�����κθ���"){
						temp = 0;
					}
					return '<input type="button" value="����" onclick = "check('+row.id+','+temp+');"/>';
				}
			}
		}, {
			name : 'deal',
			display : '�������',
			sortable : true,
			width : 85,
			process : function(v,row){
				if(row.confirmStatus == '��ȷ��' && row.checkStatus == 'δ����'){
					var temp = 1;
					if(row.checkFile == "" || row.checkFile == "�����κθ���"){
						temp = 0;
					}
					return '<input type="button" value="���Ԥ������" onclick = "changeDate('+row.id+');"/> ';
//					'<input type="button" value="�����ʷ" onclick = "changeHistory('+row.id+');"/>';
				}else if(row.confirmStatus == '��ȷ��' && row.checkStatus == '������'){
					return '<input type="button" value="�鿴�����ʷ" onclick = "changeHistory('+row.id+');"/>';
				}
			}
		}, {
			name : 'reason',
			display : '����/�쳣����ԭ��',
			sortable : true,
			process : function(v,row){
				if(row.checkStatus == "������"){
					return v;
				}else{
					return '<input type="text" id="reason'+row.id+'" style="width:120px" value="'+v+'">';
				}
			},
			width : 120
		} ],
//		toViewConfig : {
//			action : 'toView'
//		},
		searchitems : [ {
			display : "��ͬ���",
			name : 'contractCodeSearch'
		} ,{
			display : "���տ���",
			name : 'clauseSearch'
		} ],
		comboEx : [{
			text: "����״̬",
			key: 'checkStatus',
			data : [ {
				text : 'δ����',
				value : 'δ����'
			},{
				text : '������',
				value : '������'
			}]
		},{
			text: "ȷ��״̬",
			key: 'confirmStatus',
			data : [ {
				text : 'δȷ��',
				value : 'δȷ��'
			},{
				text : '��ȷ��',
				value : '��ȷ��'
			}]
		}]
	});
});

//ȷ��Ԥ������ʱ��
function confirmDate(id){
	if(window.confirm('ȷ��Ҫȷ��Ԥ������ʱ����')){
		if($("#checkDate"+id).val() != ''){
			$.ajax({
				type : "POST",
				url : "?model=contract_checkaccept_checkaccept&action=confirm",
				data : {
					id : id,
					checkDate : $("#checkDate"+id).val()
				},
				success : function(msg) {
					if (msg) {
						alert('ȷ�ϳɹ���');
						show_page();
					}else {
					     alert("ȷ��ʧ�ܣ�")
					}
				}
			});
		}else{
			alert('Ԥ������ʱ�䲻��Ϊ��');
		}
	}
}


//���Ԥ������ʱ��
function changeDate(id){
	if(window.confirm('ȷ��Ҫ���Ԥ������ʱ����')){
		if($("#checkDate"+id).val() != ''){
			$.ajax({
				type : "POST",
				url : "?model=contract_checkaccept_checkaccept&action=change",
				data : {
					id : id,
					checkDate : $("#checkDate"+id).val(),
					checkDateOld : $("#checkDateOld"+id).val()
				},
				success : function(msg) {
					if (msg) {
						alert('����ɹ���');
						show_page();
					}else {
					     alert("���ʧ�ܣ�")
					}
				}
			});
		}else{
			alert('Ԥ������ʱ�䲻��Ϊ��');
		}
	}
}

//�鿴�����ʷ
function changeHistory(id){
	showThickboxWin('?model=contract_checkaccept_checkaccept&action=showChanceHistory&id='
			+ id
			+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600');
}

//����
function check(id,temp){
	if(window.confirm('ȷ��������')){
		if(temp == 1){
			$.ajax({
				type : "POST",
				url : "?model=contract_checkaccept_checkaccept&action=check",
				data : {
					id : id,
					reason : $("#reason"+id).val(),
					realCheckDate : $("#realCheckDate"+id).val(),
					isError : 0
				},
				success : function(msg) {
					if (msg == 1) {
						alert('���ճɹ�');
						show_page();
					}else if(msg == 2){
						alert('����д�쳣����ԭ��');
					}else if(msg == 3){
						alert('�����ѳ��ڣ�����д��������ԭ��');
					}else{
						alert('����ʧ��');
					}
				}
			});
		}else if(window.confirm('δ�ϴ��ı����Ƿ�Ϊ�쳣���գ�')){
			$.ajax({
				type : "POST",
				url : "?model=contract_checkaccept_checkaccept&action=check",
				data : {
					id : id,
					reason : $("#reason"+id).val(),
					realCheckDate : $("#realCheckDate"+id).val(),
					isError : 1
				},
				success : function(msg) {
					if (msg == 1) {
						alert('���ճɹ�');
						show_page();
					}else if(msg == 2){
						alert('����д�쳣����ԭ��');
					}else if(msg == 3){
						alert('�����ѳ��ڣ�����д��������ԭ��');
					}else{
						alert('����ʧ��');
					}
				}
			});
		}else{
			alert('�����ϴ��ı�');
		}
	}
}

//��ת���ϴ�����

function uploadfile(id){
	showThickboxWin("?model=contract_checkaccept_checkaccept&action=toUploadFile&id="
			+ id
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
}

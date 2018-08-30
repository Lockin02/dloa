$(document).ready(function() {
	//��ʼ���ʼ���ϸ
	initDetail();

	//�ʼ���Ⱦ
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'qualityReport'
	});

	//��ʼ���ʼ�������
	initMailPerson();
})

//��ʼ���ʼ�������
function initMailPerson(){
	//Ĭ���ʼ�������
	var TO_NAME = $("#TO_NAME").val();
	var TO_ID = $("#TO_ID").val();
	var TO_NAMEArr = TO_NAME.split(",");
	var TO_IDArr = TO_ID.split(",");

	var applyUserCode = $("#applyUserCode").val();
	if(applyUserCode != "" && jQuery.inArray(applyUserCode,TO_IDArr) == -1){
		TO_IDArr.push(applyUserCode);
	}

	var applyUserName = $("#applyUserName").val();
	if(applyUserName != "" && jQuery.inArray(applyUserName,TO_NAMEArr) == -1){
		TO_NAMEArr.push(applyUserName);
	}

	$("#TO_NAME").val(TO_NAMEArr.toString());
	$("#TO_ID").val(TO_IDArr.toString());
}

//��ʼ���ʼ���ϸ
function initDetail(){
	$("#itemTable").yxeditgrid({
		objName : 'qualityapply[items]',
		url : '?model=produce_quality_qualityapplyitem&action=editItemJson',
		title :'�ʼ�������ϸ',
		param : {
			mainId : $("#id").val()
		},
		type : 'view',
		isAddAndDel : false,
		colModel : [ {
			name : 'id',
			display : "<input type='checkbox' id='checkAll' onclick='checkAll();' value='all'/>",
			width : 30,
			process : function(v,row){
				if(row.status != "4"){
					return "";
				}else{
					return "<input type='checkbox' name='idCheckbox' value='" + row.id +"'/>";
				}
			},
			type : 'statictext'
		}, {
			name : 'productCode',
			display : '���ϱ��',
			width : 90
		}, {
			name : 'productName',
			display : '��������',
			width : 150
		}, {
			name : 'pattern',
			display : '�ͺ�',
			width : 100
		}, {
			name : 'unitName',
			display : '��λ',
			width : 50
		}, {
			name : 'checkTypeName',
			display : '�ʼ췽ʽ',
			width : 80
		}, {
			name : 'qualityNum',
			display : '��������',
			width : 80
		}, {
			name : 'assignNum',
			display : '���´�����',
			width : 80
		}, {
			name : 'standardNum',
			display : '�ϸ�����',
			width : 80
		},{
			name : 'status',
			display : '������',
			width : 80,
			process : function(v){
				switch(v){
					case "0" : return "�ʼ����";
					case "1" : return "���ִ���";
					case "2" : return "������";
					case "3" : return "�ʼ����";
					case "4" : return "δ����";
                    case "5" : return "�𻵷���";
					default : return "";
				}
			}
		},{
			name : 'dealUserName',
			display : '������',
			width : 80
		},{
			name : 'dealTime',
			display : '����ʱ��',
			width : 140
		}, {
			display : '���κ�',
			name : 'batchNum',
			width : 80
		}, {
			display : '���к�',
			name : 'serialName',
			process : function(v){
				if(v!="" && v != '���������е�����'){
					return "<a href='javascript:void(0);' onclick='showOpenWin(\"?model=stock_serialno_serialno&action=toViewFormat"+
						"&nos=" + v
						+"\",1,400,600)'>����鿴</a>";
				}else{
					return '��';
				}
			},
			width : 80
		}]
	});
}

//ȷ�����
function confirmPass(){
	//��ѡ����
	var objArr = $("input[name='idCheckbox']:checked");

	if(objArr.length == 0){
		alert('û��ѡ���κ�����');
		return false;
	}

	//ȷ��ѡ������
	if(confirm('ȷ�϶�ѡ�е����Ͻ����ʼ���в�����')){
		//id���黺��
		var idArr = [];
		objArr.each(function(i,n){
			idArr.push(this.value);
		});
		var ids = idArr.toString();

		//ȷ�����
		$.ajax({
		    type: "POST",
		    url: "?model=produce_quality_qualityapplyitem&action=confirmPass",
		    data: {"ids" : ids , 'issend' : $("input[name='issend'][checked]").val() , "TO_ID" : $("#TO_ID").val()},
		    async: false,
		    success: function(data){
		   		if(data == 1){
					alert('�����ɹ�');
		   	    }else{
					alert('����ʧ��');
		   	    }
			}
		});

		//�رձ�ҳ�Լ�ˢ���б�
		self.parent.tb_remove();
		parent.show_page();
	}
}

//�𻵷���
function damagePass(){
    var objGrid = $("#itemTable");
    var objArr = objGrid.yxeditgrid("getCmpByCol", "id");

    if(objArr.length == 0){
        alert('û��ѡ���κ�����');
        return false;
    }

    //ȷ��ѡ������
    if(confirm('ȷ�Ͻ���ǰ���������𻵷��д�����')){
        //id���黺��
        var idArr = [];
        objArr.each(function(i,n){
            idArr.push(this.value);
        });
        var ids = idArr.toString();

        //ȷ�����
        $.ajax({
            type: "POST",
            url: "?model=produce_quality_qualityapplyitem&action=damagePass",
            data: {
                "ids" : ids ,
                'issend' : $("input[name='issend']:checked").val() ,
                "TO_ID" : $("#TO_ID").val(),
                "passReason" : $("#passReason").val()
            },
            async: false,
            success: function(data){
                if(data == 1){
                    alert('�����ɹ�');
                }else{
                    alert('����ʧ��');
                }
            }
        });

        //�رձ�ҳ�Լ�ˢ���б�
        self.parent.tb_remove();
        parent.show_page();
    }
}

//ѡ��ȫ��
function checkAll(){
	//ȫѡ����
	var checkedVal = $("#checkAll").attr("checked");

	//��ѡ����
	var objArr = $("input[name='idCheckbox']");
	objArr.each(function(i,n){
		$(this).attr("checked",checkedVal);
	});
}
$(document).ready(function() {
	//��ʼ���ʼ���ϸ
	initDetail();

	//�ʼ���Ⱦ
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'qualityReport'
	});

    if ($("#isDamagePass").val() == "1") {
        $("#damageBtn").show();
    }
});

//��ʼ���ʼ�������
function initMailPerson(){
	//Ĭ���ʼ�������
	var TO_NAME = $("#defaultUserName").val();
	var TO_ID = $("#defaultUserCode").val();
	var TO_NAMEArr = TO_NAME == "" ? [] : TO_NAME.split(",");
	var TO_IDArr = TO_ID == "" ? [] : TO_ID.split(",");

	var objGrid = $("#itemTable");
    objGrid.yxeditgrid("getCmpByCol", "applyUserName").each(function(i,n) {
		//���˵�ɾ������
		if($("#qualityapply[items]_" + i +"_isDelTag").length == 0){
			if(jQuery.inArray(this.value,TO_NAMEArr) == -1){
				TO_NAMEArr.push(this.value);
			}
		}
	});

    objGrid.yxeditgrid("getCmpByCol", "applyUserCode").each(function(i,n) {
		//���˵�ɾ������
		if($("#qualityapply[items]_" + i +"_isDelTag").length == 0){
			if(jQuery.inArray(this.value,TO_IDArr) == -1){
				TO_IDArr.push(this.value);
			}
		}
	});

	$("#TO_NAME").val(TO_NAMEArr.toString());
	$("#TO_ID").val(TO_IDArr.toString());
}

//��ʼ���ʼ���ϸ
function initDetail(){
	$("#itemTable").yxeditgrid({
		objName : 'qualityapply[items]',
		url : '?model=produce_quality_qualityapplyitem&action=confirmPassJson',
		param : {
			'idArr' : $("#id").val(),
			'status' : '4'
		},
		isAdd : false,
		event : {
			'reloadData' : function(e){
				//��ʼ���ʼ�������
				initMailPerson();
			},
			"removeRow" : function(e,rowNum){
				//��ʼ���ʼ�������
				initMailPerson();
			}
		},
		colModel : [{
			name : 'id',
			display : "id",
			type : 'hidden'
		}, {
			name : 'relDocId',
			display : "relDocId",
			type : 'hidden'
		}, {
			name : 'relDocType',
			display : "relDocType",
			type : 'hidden'
		}, {
			name : 'applyUserCode',
			display : "applyUserCode",
			type : 'hidden'
		}, {
			name : 'productCode',
			display : '���ϱ��',
			width : 90,
			type : 'statictext'
		}, {
			name : 'productName',
			display : '��������',
			width : 180,
			type : 'statictext'
		}, {
			name : 'pattern',
			display : '�ͺ�',
			width : 130,
			type : 'statictext'
		}, {
			name : 'unitName',
			display : '��λ',
			width : 70,
			type : 'statictext'
		}, {
			name : 'checkTypeName',
			display : '�ʼ췽ʽ',
			width : 70,
			type : 'statictext'
		}, {
			name : 'qualityNum',
			display : '��������',
			width : 70,
			type : 'statictext'
		}, {
			name : 'relDocCode',
			display : 'Դ�����',
			type : 'statictext'
		}, {
			name : 'applyUserName',
			display : '������',
			type : 'statictext'
		}]
	});
}

//ȷ�����
function confirmPass(){
	var objGrid = $("#itemTable");
	var objArr = objGrid.yxeditgrid("getCmpByCol", "id");

	if(objArr.length == 0){
		alert('û��ѡ���κ�����');
		return false;
	}

	//ȷ��ѡ������
	if(confirm('ȷ�Ͻ���ǰ���������ʼ���д�����')){
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
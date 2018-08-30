$(function() {
	$("#taskTable").yxeditgrid({
		objName : 'task[taskItem]',
		url : '?model=asset_purchase_task_taskItem&action=listJson',
		param : {
			parentId : $("#parentId").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '���',
			name : 'pattem',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '��λ',
			name : 'unitName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '��Ӧ��',
			name : 'supplierName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '�ɹ�����',
			name : 'purchAmount',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '��������',
			name : 'taskAmount',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '����',
			name : 'price',
			tclass : 'readOnlyTxtItem',
			type : 'money'
		}, {
			display : '���',
			name : 'moneyAll',
			tclass : 'readOnlyTxtItem',
			type : 'money'
		}, {
			display : 'ϣ����������',
			name : 'dateHope',
			type : 'date',
			tclass : 'txtshort datehope'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'readOnlyTxtItem'
		}]
	})
	// ѡ����Ա���
	$("#purcherName").yxselect_user({
		hiddenId : 'purcherId'
	});

	//��������
	$("#arrivaDate").bind("focusout",function(){
		var dateHope=$(this).val();
		$.each($(':input[class^="txtshort datehope"]'),function(i,n){
			$(this).val(dateHope);
		})
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"sendName" : {
			required : true
		},
		"sendTime" : {
			custom : ['date']
		},
		"purcherName" : {
			required : true
		},
		"acceptDate" : {
			custom : ['date']
		},
		"endDate" : {
			custom : ['date']
		}
	});

});


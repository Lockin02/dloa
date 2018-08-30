var show_page = function(page) {
	$("#balanceGrid").yxgrid("reload");
};

$(function() {
	var formTypeVal = $("#formTypeVal").val();
	var periodStr = "";

	$("#balanceGrid").yxgrid({
    	model : 'finance_balance_balance',
    	param : { "formType" : $("#formType").val()},
    	title : '�ڳ����� - ' + $("#formTypeCN").val() ,
    	isEditAction :false,
    	isDelAction : false,
    	sortorder : 'asc',
    	//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'thisYear',
				display : '������',
				sortable : true
			}, {
				name : 'thisMonth',
				display : '����·�',
				sortable : true
			}, {
				name : 'objectName',
				display : '�ͻ�/��Ӧ��',
				sortable : true,
				width : 150
			}, {
				name : 'directionsName',
				display : '�������',
				sortable : true
			}, {
				name : 'needPay',
				display : '����Ӧ' + formTypeVal,
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'payed',
				display : '����ʵ' + formTypeVal,
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'balance',
				display : '��ĩ���',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'thisDate',
				display : '����',
				sortable : true
			}, {
				name : 'isUsing',
				display : '�Ƿ���ʹ��',
				sortable : true,
				process : function(v,row){
					if(v == 1){
						if(periodStr == ""){
							if(row.thisMonth < 10){
								periodStr = row.thisYear + '0' + row.thisMonth;
							}else{
								periodStr = row.thisYear + row.thisMonth;
							}
							return '<input type="hidden" id="isUsing" value="' + periodStr + '"/><span class="red">��</span>';
						}
						return '<span class="red">��</span>';
					}else{
						return '��';
					}
				}
			}
		],
		toAddConfig : {
			plusUrl : '&formType=' + $("#formType").val()
		},
		menusEx : [
			{
				text : '�༭',
				icon : 'edit',
				showMenuFn : function(row) {
					isUsing = $("#isUsing").val();
					if(row.thisMonth < 10){
						thisVal = row.thisYear + '0' + row.thisMonth;
					}else{
						thisVal = row.thisYear + row.thisMonth;
					}
					if (isUsing*1 <= thisVal*1) {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (row)
						showThickboxWin("?model=finance_balance_balance"
								+ "&action=init"
								+ "&id="
								+ row.id
								+ "&formType" + $("formType").val()
								+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400"
								+ "&width=800");
				}
			}
			,{
				name : 'delete',
				text : 'ɾ��',
				icon : 'delete',
				showMenuFn : function(row) {
					isUsing = $("#isUsing").val();
					if(row.thisMonth < 10){
						thisVal = row.thisYear + '0' + row.thisMonth;
					}else{
						thisVal = row.thisYear + row.thisMonth;
					}
					if (isUsing*1 < thisVal*1) {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (row) {
						if (window.confirm(("ȷ��Ҫɾ��?"))) {
							$.ajax({
								type : "POST",
								url : "?model=finance_balance_balance&action=ajaxdeletes",
								data : {
									id : row.id
								},
								success : function(msg) {
									if (msg == 1) {
										alert('ɾ���ɹ���');
										$("#balanceGrid").yxgrid("reload");
									}else{
										alert('ɾ��ʧ�ܣ�');
									}
								}
							});
						}
					} else {
						alert("��ѡ��һ������");
					}
				}
			}
		],
		buttonsEx : [
			{
				text : '����',
				icon : 'edit',
				action : function(row) {
					if (window.confirm(("ȷ��Ҫ����?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_balance_balance&action=checkout",
							data :{ "formType" : $("#formType").val() },
							success : function(msg) {
								if (msg == 1) {
									alert('����ɹ���');
									$("#balanceGrid").yxgrid("reload");
								}else if(msg = 2){
									alert('���Ƚ���������');
								}else{
									alert('����ʧ�ܣ�');
								}
							}
						});
					}
				}
			},
			{
				text : '������',
				icon : 'edit',
				action : function(row) {
					if (window.confirm(("ȷ��������������?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_balance_balance&action=balanceCount",
							data :{ "formType" : $("#formType").val() },
							success : function(msg) {
								if (msg == 1) {
									alert('������ɹ���');
									$("#balanceGrid").yxgrid("reload");
								}else{
									alert('������ʧ��!�����Ƿ���¼���ʼ���');
								}
							}
						});
					}
				}
			},
			{
				text : '������',
				icon : 'edit',
				action : function(row) {
					if (window.confirm(("��������������ڵ��ڳ���ȷ����������?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_balance_balance&action=unCheckout",
							data :{ "formType" : $("#formType").val() },
							success : function(msg) {
								if (msg == 1) {
									alert('������ɹ���');
									$("#balanceGrid").yxgrid("reload");
								}else if(msg = 2){
									alert('��ǰ�������ǳ�ʼ���ڻ���ϵͳδ���г�ʼ�������ܷ����㣡');
								}else{
									alert('������ʧ�ܣ�');
								}
							}
						});
					}
				}
			}
		],
		comboEx:
		[
			{
				text: "ʹ��״̬",
				key: 'isUsing',
				data : [
					{
						text : '��',
						value : 1
					},
					{
						text : '��',
						value : 0
					}
				]
			}
		]
	});
});
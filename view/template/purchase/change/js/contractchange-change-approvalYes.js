// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".approvalYesGrid").yxgrid("reload");
};
$(function() {
	$(".approvalYesGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		model : 'purchase_change_contractchange',
		action : 'pageJsonYes',
		title : '����˵Ĳɹ�����',
		isToolBar : false,
		showcheckbox : false,

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�������',
					name : 'hwapplyNumb',
					sortable : true,
					width : '200'
				}, {
					display : '����������',
					name : 'createName',
					sortable : true
				}, {
					display : 'Ԥ�����ʱ��',
					name : 'dateHope',
					sortable : true
				}, {
					display : '����״̬',
					name : 'ExaStatus',
					width : 70,
					sortable : true
				}, {
					display : '����ʱ��',
					name : 'ExaDT',
					width : 70,
					sortable : true
				}, {
					display : '��Ӧ������',
					name : 'suppName',
					sortable : true
				}, {
					display : '��Ʊ����',
					name : 'billingType',
					datacode : 'FPLX', // �����ֵ����
					width : 80,
					sortable : true
				}, {
					display : '���ʽ',
					name : 'paymetType',
					datacode : 'fkfs',
					width : 80,
					sortable : true
				}, {
					display : '����״̬',
					name : 'isChanged',
					process : function(v) {
						if (v == 0) {
							return "��ȷ��";
						}
						return "�ɽ���״̬";

					}
				}],
		// comboEx : [{
		// text : "����״̬",
		// key : 'isChanged',
		// data : [{
		// text : '��ȷ��',
		// value : 0
		// }, {
		// text : '�ɽ���״̬',
		// value : 1
		// }]
		// }],
		param : {
			"ExaStatus" : "���,���"
		},
		// ��չ��ť
		buttonsEx : [],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					// showThickboxWin("?model=purchase_contract_purchasecontract&action=init"
					// + "&id="
					// + row.id
					// +
					// "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
					// + 400 + "&width=" + 700);
					showOpenWin("?model=purchase_contract_purchasecontract&action=toTabView&id="+row.id+"&applyNumb="+row.applyNumb+"&skey="+row['skey_']);
				} else {
					alert("��ѡ��һ������");
				}
			}

		}
				// ,{
				// text : '�������',
				// icon : 'view',
				// action : function(row,rows,grid){
				// if(row){
				// showThickboxWin("controller/common/readview.php?itemtype=oa_purchase_apply_basic&pid="
				// +row.id
				// +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
				// }
				// }
				// }
		, {
			text : 'ȷ��',
			icon : 'add',
			 showMenuFn : function(row){
			 if(row.ExaStatus == '���' && row.isChanged == '1'){
			 return true;
			 }
			 return false;
			 },
			action : function(row, rows, grid) {
				if (row && row.ExaStatus == "���") {
					// location =
					// "?model=purchase_change_contractchange&action=coverChange&id="+row.id;

					if (window.confirm("ȷ��Ҫȷ����")) {
						$.ajax({
							type : "POST",
							//url : "?model=purchase_change_contractchange&action=coverChange",
							url : "?model=common_changeLog&action=confirmChange",
							data : {
								id : row.id,
								logObj:'purchasecontract'
							},
							success : function(msg) {alert(msg)
								if (msg == 1) {
									alert("ȷ�ϳɹ�");
									show_page();
								} else {
									alert("ȷ�ϲ��ɹ�");
									show_page();
								}
							}
						});
					}
				}
			}

		}],
		// ��������
		searchitems : [{
					display : '�������',
					name : 'hwapplyNumb'
				}, {
					display : '��Ӧ������',
					name : 'suppName'
				}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		// boName : '��Ӧ����ϵ��',
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳��
		sortorder : "ASC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});
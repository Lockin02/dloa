var show_page = function(page) {
	$(".rdprojectAllGrid").yxgrid("reload");
};
$(function() {
	$(".rdprojectAllGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		model : 'rdproject_yxrdproject_rdproject',
		title : '�з���ͬ��Ϣ',
		// action: ''
		isToolBar : false,
		showcheckbox : false,
		customCode : 'listAll',
		// param : {
		// //'states' : '2'
		// },
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'ExaDT',
					display : '����ʱ��',
					sortable : true,
					width : 80
				}, {
					name : 'orderCode',
					display : '������ͬ��',
					sortable : true,
					width : 180
				}, {
					name : 'orderTempCode',
					display : '��ʱ��ͬ��',
					sortable : true,
					width : 180
				}, {
					name : 'cusName',
					display : '�ͻ�����',
					sortable : true,
					width : 100
				}, {
					name : 'orderName',
					display : '��ͬ����',
					sortable : true,
					width : 150
				}, {
					name : 'orderTempMoney',
					display : 'Ԥ�ƺ�ͬ���',
					sortable : true,
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'orderMoney',
					display : 'ǩԼ��ͬ���',
					sortable : true,
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true,
					width : 60
				}, {
					name : 'sign',
					display : '�Ƿ�ǩԼ',
					sortable : true,
					width : 70
				}, {
					name : 'areaName',
					display : '��������',
					sortable : true,
					width : 60
				}, {
					name : 'orderPrincipal',
					display : '��ͬ������',
					sortable : true,
					width : 80
				}, {
					name : 'state',
					display : '��ͬ״̬',
					sortable : true,
					process : function(v) {
						if (v == '0') {
							return "δ�ύ";
						} else if (v == '1') {
							return "������";
						} else if (v == '2') {
							return "ִ����";
						} else if (v == '3') {
							return "�ѹر�";
						} else if (v == '4') {
							return "�����";
						} else if (v == '5') {
							return "�Ѻϲ�";
						} else if (v == '5') {
							return "�Ѳ��";
						}
					},
					width : 60
				}, {
					name : 'objCode',
					display : 'ҵ����',
					width : 150
				}],
		// ��չ��ť
		buttonsEx : [],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					// showThickboxWin("?model=engineering_serviceContract_serviceContract&action=init"
					// + "&id="
					// + row.id
					// +
					// "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
					// + 400 + "&width=" + 700);
					showOpenWin('?model=rdproject_yxrdproject_rdproject&action=init&perm=view&id='
							+ row.id + "&skey=" + row['skey_'])
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			text : '����',
			icon : 'add',
			action : function(row) {
				window
						.open('?model=contract_common_allcontract&action=importCont&id='
								+ row.id
								+ '&type=oa_sale_rdproject'
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		},{
			text : '�����ϴ�',
			icon : 'add',
			action: function(row){
				     showThickboxWin ('?model=projectmanagent_order_order&action=toUploadFile&id='
				                      + row.id
				                      +'&type=oa_sale_rdproject'
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		 },{
			text : '��ɺ�ͬ',
			icon : 'edit',
			showMenuFn : function (row){
				   if(row.state == 2){
				       return true;
				   }
				       return false;
				},
			action: function(row){
				if (window.confirm(("ȷ��Ҫ�Ѻ�ͬ״̬��Ϊ ����ɡ� ״̬��"))) {
	                 	$.ajax({
							type : "POST",
							url : "?model=projectmanagent_order_order&action=completeOrder&id=" + row.id + "&type=oa_sale_rdproject",
							success : function(msg) {
	                                $(".rdprojectAllGrid").yxgrid("reload");
							}
						});
	                 }
				}
		},{
			text : 'ִ�к�ͬ',
			icon : 'edit',
			showMenuFn : function (row){
			   if(row.state == 4){
			       return true;
			   }
			       return false;
			},
			action: function(row){
				if (window.confirm(("ȷ��Ҫ�Ѻ�ͬ״̬��Ϊ ��ִ���С� ״̬��"))) {
	                 	$.ajax({
							type : "POST",
							url : "?model=projectmanagent_order_order&action=exeOrder&id=" + row.id + "&type=oa_sale_rdproject",
							success : function(msg) {
								   if(msg == '0'){
                                       alert("��ͬ��������ɣ���ѡ��������");
                                       $(".rdprojectAllGrid").yxgrid("reload");
								   }else{
								       $(".rdprojectAllGrid").yxgrid("reload");
								   }

							}
						});
	                 }
				}
		}],

		comboEx : [{
			text : '��ͬ״̬',
			key : 'state',
			data : [ {
				text : 'δ�ύ',
				value : '0'
			},{
				text : '������',
				value : '1'
			},{
				text : 'ִ����',
				value : '2'
			},{
				text : '�����',
				value : '4'
			},{
				text : '�ѹر�',
				value : '3'
			}  ]
		}],
		// ��������
		searchitems : [{
			display : '��ͬ����',
			name : 'orderName'
		}, {
			display : '��ͬ���',
			name : 'orderCodeOrTempSearch'
		}, {
					name : 'objCode',
					display : 'ҵ����',
					width : 150
				}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		// boName : '��Ӧ����ϵ��',
		sortname : "createTime",
		// ��ʾ�鿴��ť
		isViewAction : false
			// isAddAction : true,
			// isEditAction : false
	});

});
var show_page = function(page) {
	$("#basicinfoGrid").yxgrid("reload");
};
$(function() {

			//��ͷ��ť����
	var buttonsArr = [
//        {
//			name : 'view',
//			text : "�߼���ѯ",
//			icon : 'view',
//			action : function() {
//				showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=900');
//			}
//        }
    ];
    //��ͷ��ť����
	var excelInArr = {
		name : 'exportIn',
		text : "���������Ӧ��",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_supplier_basicinfo&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	var excelUpdateArr = {
		name : 'exportIn',
		text : "���������Ӧ��",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_supplier_basicinfo&action=toExcelUpdate"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};
	$.ajax({
		type : 'POST',
		url : '?model=outsourcing_supplier_basicinfo&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data ==1) {
				buttonsArr.push(excelInArr);
				buttonsArr.push(excelUpdateArr);
			}
		}
	});
	$("#basicinfoGrid").yxgrid({
		model : 'outsourcing_supplier_basicinfo',
		title : '�����Ӧ�̿�',
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		param:{'isDel':0},
		bodyAlign:'center',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'suppCode',
			display : '��Ӧ�̱��',
			width:70,
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showModalWin(\"?model=outsourcing_supplier_basicinfo&action=toTabView&id=" + row.id +"\",1)'>" + v + "</a>";
			}
		}, {
			name : 'suppName',
			display : '��Ӧ������',
			width:150,
			sortable : true
		}, {
			name : 'suppGrade',
			display : '��֤�ȼ�',
			width:60,
			sortable : true,
			process:function(v){
					if(v=="1"){
						return "�� ";
					}else if(v=="2"){
						return "��";
					}else if(v=="3"){
						return "ͭ";
					}else if(v=="4"){
						return "������";
					}else if(v=="0"){
						return "δ��֤";
					}
			}
		},  {
			name : 'officeName',
			display : '����',
			width:50,
			sortable : true
		}, {
			name : 'province',
			display : 'ʡ��',
			width:50,
			sortable : true
		}, {
			name : 'suppTypeName',
			display : '��Ӧ������',
			width:60,
			sortable : true
		}, {
			name : 'registeredDate',
			display : '����ʱ��',
			width:70,
			sortable : true
		}, {
			name : 'registeredFunds',
			display : 'ע���ʽ�(��Ԫ)',
			width:80,
			sortable : true
		},{
			name : 'mainBusiness',
			display : '��Ӫҵ��',
			width:150,
			sortable : true
		}, {
			name : 'adeptNetType',
			display : '�ó���������',
			width:150,
			sortable : true
		}, {
			name : 'adeptDevice',
			display : '�ó������豸',
			width:150,
			sortable : true
		},  {
			name : 'certifyNumber',
			display : '��֤����',
			width:50,
			sortable : true
		} , {
			name : 'ExaStatus',
			display : '����״̬',
			width:50,
			sortable : true
		}, {
			name : 'createName',
			display : '¼����',
			width:90,
			sortable : true
		}, {
			name : 'createTime',
			display : '¼��ʱ��',
			width:120,
			sortable : true
		}],

		lockCol:['suppCode','suppName'],//����������


		buttonsEx : buttonsArr,
		//��������
		comboEx : [{
			text : '��֤�ȼ�',
			key : 'suppGrade',
			data : [{
					text : 'δ��֤',
					value : '0'
				},{
					text : '��',
					value : '1'
				},{
					text : '��',
					value : '2'
				},{
					text : 'ͭ',
					value : '3'
				},{
					text : '������',
					value : '4'
				}]
			},{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
					text : 'δ����',
					value : 'δ����'
				},{
					text : '��������',
					value : '��������'
				},{
					text : '���',
					value : '���'
				}]
			}
		],

		// ��չ�Ҽ��˵�

		menusEx : [{
				text : '�༭',
				icon : 'edit',
				action : function(row) {
					if(row.status == '1'){
							$.ajax({
								type : 'POST',
								url : '?model=outsourcing_supplier_basicinfo&action=getLimits',
								data : {
									'limitName' : '�޸�Ȩ��'
								},
								async : false,
								success : function(data) {
									if (data ==1) {
										showModalWin("?model=outsourcing_supplier_basicinfo&action=toTabEdit&id=" +row.id,'1');
									}else{
										alert('û�в���Ȩ��');
										$("#basicinfoGrid").yxgrid("reload");
									}
								}
							});
					}else{
						showModalWin("?model=outsourcing_supplier_basicinfo&action=toTabEdit&id=" +row.id,'1');}
				}

			},{
				text : '�ύ����',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.ExaStatus == 'δ����') {
						return true;
					}
					return false;
				},
				action : function(row) {
               	 showThickboxWin('controller/outsourcing/supplier/ewf_index.php?actTo=ewfSelect&billId='+ row.id+ '&flowMoney=0&billDept='+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");

				}

			},{
				text : '��֤�ȼ����',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == '1'&&(row.changeExaStatus == '���'||row.changeExaStatus == 'δ����'||row.changeExaStatus == '���')) {
						return true;
					}
					return false;
				},
				action : function(row) {
               	 	showThickboxWin("?model=outsourcing_supplier_basicinfo&action=toChangeSuppGrad&id=" +row.id+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=750");
               	 	$.ajax({
							type : "POST",
							url : "?model=outsourcing_supplier_basicinfo&action=ajaxChange",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									$("#basicinfoGrid").yxgrid("reload");
								}
							}
						});
				}

			},{
					name : 'aduit',
					text : '�������',
					icon : 'view',
					showMenuFn : function(row) {
						if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
							return true;
						}
						return false;
					},
					action : function(row, rows, grid) {
						if (row) {
							showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcesupp_supplib&pid="
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
						}
					}
				},{
				text : 'ɾ��',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.ExaStatus == 'δ����') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_supplier_basicinfo&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									$("#basicinfoGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			},  {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status==1&&row.isDel==0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
					$.ajax({
						type : 'POST',
						url : '?model=outsourcing_supplier_basicinfo&action=getLimits',
						data : {
							'limitName' : 'ɾ��Ȩ��'
						},
						async : false,
						success : function(data) {
							if (data ==1) {
								if (window.confirm(("ȷ��Ҫɾ��?"))) {
									$.ajax({
										type : "POST",
										url : "?model=outsourcing_supplier_basicinfo&action=deleteSupp",
										data : {
											id : row.id,
											isDel : 1
										},
										success : function(msg) {
											if (msg == 1) {
												alert('ɾ���ɹ���');
												$("#basicinfoGrid").yxgrid("reload");
											}
										}
									});
								}
							}else{
								alert('û�в���Ȩ��');
								$("#basicinfoGrid").yxgrid("reload");
							}
						}
					});
				}
		},{
			name : 'view',
			text : "������־",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
						+ row.id
						+ "&tableName=oa_outsourcesupp_supplib"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		} ],
		toAddConfig : {
			formWidth : 1000,
			formHeight : 500,
			toAddFn : function(p, g) {
				showModalWin("?model=outsourcing_supplier_basicinfo&action=toAdd",'1');
			}
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_supplier_basicinfo&action=toTabView&id=" + get[p.keyField],1);
				}
			}
		},
		searchitems : [{
						display : "��Ӧ�̱��",
						name : 'suppCode'
					},{
						display : "��Ӧ������",
						name : 'suppName'
					},{
						display : "����",
						name : 'officeName'
					},{
						display : "ʡ��",
						name : 'province'
					},{
						display : "��Ӧ������",
						name : 'suppTypeName'
					},{
						display : "����ʱ��",
						name : 'registeredDate'
					},{
						display : "���˴���",
						name : 'legalRepre'
					},{
						display : "��Ӫҵ��",
						name : 'mainBusiness'
					},{
						display : "�ó���������",
						name : 'adeptNetType'
					},{
						display : "�ó������豸",
						name : 'adeptDevice'
					}],

				sortname : 'suppGrade',
				sortorder : 'ASC'
	});
});
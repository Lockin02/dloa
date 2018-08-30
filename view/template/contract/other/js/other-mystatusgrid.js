var show_page = function(page) {
	$("#otherGrid").yxgrid("reload");
};

$(function() {
	$("#otherGrid").yxgrid({
		model : 'contract_other_other',
		action : 'myOtherListPageJson',
		title : '�ҵ�������ͬ',
		isViewAction : false,
		isAddAction :false,
		customCode : 'otherGrid',
		param : { "status" : $("#status").val() },
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'createDate',
				display : '¼������',
				width : 70
			}, {
				name : 'fundTypeName',
				display : '��������',
				sortable : true,
				width : 70,
				process : function(v,row){
					if(row.fundType == 'KXXZB'){
						return '<span style="color:blue">' + v  +'</span>';
					}else if( row.fundType == 'KXXZA'){
						return '<span style="color:green">' + v  +'</span>';
					}else{
						return v;
					}
				}
			}, {
				name : 'orderCode',
				display : '������ͬ��',
				sortable : true,
				width : 130,
	            process : function(v,row){
					if(row.status == 4){
						return "<a href='#' style='color:red' title='����еĺ�ͬ' onclick='window.open(\"?model=contract_other_other&action=viewTab&id=" + row.id+ "&fundType=" + row.fundType + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}else{
						if(row.ExaStatus == '���ύ' || row.ExaStatus == '��������'){
							return "<a href='#' onclick='showModalWin(\"?model=contract_other_other&action=viewAlong&id=" + row.id + '&skey=' + row.skey_ + "\",1,"+ row.id +")'>" + v + "</a>";
						}else{
							return "<a href='#' onclick='showModalWin(\"?model=contract_other_other&action=viewTab&id=" + row.id + "&fundType=" + row.fundType + '&skey=' + row.skey_ + "\",1,"+ row.id +")'>" + v + "</a>";
						}
					}
	            }
			}, {
				name : 'orderName',
				display : '��ͬ����',
				sortable : true,
				width : 130
			}, {
				name : 'signCompanyName',
				display : 'ǩԼ��˾',
				sortable : true,
				width : 150
			}, {
				name : 'proName',
				display : '��˾ʡ��',
				sortable : true,
				width : 70
			}, {
				name : 'address',
				display : '��ϵ��ַ',
				sortable : true,
				hide : true
			}, {
				name : 'phone',
				display : '��ϵ�绰',
				sortable : true,
				hide : true
			}, {
				name : 'linkman',
				display : '��ϵ��',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'signDate',
				display : 'ǩԼ����',
				sortable : true,
				width : 80
			}, {
				name : 'orderMoney',
				display : '��ͬ�ܽ��',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'payApplyMoney',
				display : '���븶��',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB' ){
						return '--';
					}else{
						if(v*1 == 0){
							return 0;
						}else{
							var thisTitle = '���г�ʼ���븶����Ϊ: ' + moneyFormat2(row.initPayMoney) + ',���ڸ���������Ϊ��' + moneyFormat2(row.countPayApplyMoney);
							return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
						}
					}
				},
				width : 80
			}, {
				name : 'payedMoney',
				display : '�Ѹ����',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB' ){
						return '--';
					}else{
						if(v*1 == 0){
							return 0;
						}else{
							var thisTitle = '���г�ʼ���븶����Ϊ: ' + moneyFormat2(row.initPayMoney) + ',���ڸ�����Ϊ��' + moneyFormat2(row.countPayMoney);
							return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
						}
					}
				},
				width : 80
			}, {
				name : 'returnMoney',
				display : '������',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB'){
						if(row.id == 'noId'){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 != 0){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80
			}, {
				name : 'invotherMoney',
				display : '���շ�Ʊ',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB'){
						return '--';
					}else{
						if(v*1 != 0){
							var thisTitle = '���г�ʼ������Ʊ���Ϊ: ' + moneyFormat2(row.initInvotherMoney) + ',������Ʊ���Ϊ��' + moneyFormat2(row.countInvotherMoney);
							return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80
			}, {
				name : 'applyInvoice',
				display : '���뿪Ʊ',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZA' ){
						return '--';
					}else{
						if(v*1 == 0){
							return 0;
						}else{
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}
					}
				},
				width : 80
			}, {
				name : 'invoiceMoney',
				display : '�ѿ���Ʊ',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZA'){
						return '--';
					}else{
						if(v*1 != 0){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80
			}, {
				name : 'incomeMoney',
				display : '�տ���',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZA'){
						return '--';
					}else{
						if(v*1 != 0){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80
			}, {
				name : 'principalName',
				display : '��ͬ������',
				sortable : true,
				hide : true
			}, {
				name : 'deptName',
				display : '��������',
				sortable : true,
				hide : true
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				width : 60
				,
				process : function(v,row){
					if(row.id == 'noId') return false;
					if(v == 0){
						return "δ�ύ";
					}else if(v == 1){
						return "������";
					}else if(v == 2){
						return "ִ����";
					}else if(v == 3){
						return "�ѹر�";
					}else if(v == 4){
						return "�����";
					}
				}
			},{
				name : 'ExaStatus',
				display : '����״̬',
				sortable : true,
				width : 60
			},{
	            name : 'signedStatus',
	            display : '��ͬǩ��',
	            sortable : true,
	            process : function(v,row){
					if(row.id == "noId"){
						return '';
					}
					if(v=="1"){
						return "��ǩ��";
					}else{
						return "δǩ��";
					}
				},
	            width : 70
	        }, {
				name : 'objCode',
				display : 'ҵ����',
				sortable : true,
				width : 120
			},{
	            name : 'isNeedStamp',
	            display : '���������',
	            sortable : true,
	            width : 60,
	            process : function(v,row){
					if(v=="1"){
						return "��";
					}else{
						return "��";
					}
				}
	        },{
	            name : 'isStamp',
	            display : '�Ƿ��Ѹ���',
	            sortable : true,
	            width : 60,
	            process : function(v,row){
					if(v == 1){
						return "��";
					}else{
						return "��";
					}
	            }
	        },{
	            name : 'stampType',
	            display : '��������',
	            sortable : true,
	            width : 80
	        },{
	            name : 'createName',
	            display : '������',
	            sortable : true
	        },{
	            name : 'updateTime',
	            display : '����ʱ��',
	            sortable : true,
	            width : 130
	        }],
		toEditConfig : {
			formWidth : 1000,
			formHeight : 500,
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				var c = p.toEditConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showModalWin("?model="
							+ p.model
							+ "&action="
							+ c.action
							+ c.plusUrl
							+ "&id="
							+ rowData[p.keyField]
							+ keyUrl);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴��ͬ',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=contract_other_other&action=viewTab&id="
							+ row.id
							+ "&fundType="
							+ row.fundType
							+ "&skey=" + row.skey_
							);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=contract_otherpayapply_otherpayapply&action=getFeeDeptId",
						data : { "contractId" : row.id ,'contractType' : 'oa_sale_other' },
						success : function(data) {
							if(data != '0'){
								showThickboxWin('controller/contract/other/ewf_forpayapply.php?actTo=ewfSelect&billId='
									+ row.id
									+ "&flowMoney=" + row.orderMoney
									+ "&billDept=" + data
                                    + "&billCompany=" + row.businessBelong
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							}else{
								if(row.fundType == 'KXXZB'){
									showThickboxWin('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId='
										+ row.id
										+ "&flowMoney=" + row.orderMoney
										+ "&billDept=" + row.feeDeptId
                                        + "&billCompany=" + row.businessBelong
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
								}else{
									showThickboxWin('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId='
										+ row.id
										+ "&flowMoney=" + row.orderMoney
                                        + "&billCompany=" + row.businessBelong
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
								}
							}
						}
					});
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			text : '���뿪Ʊ',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
				if (row.ExaStatus == "���" && row.fundType == 'KXXZA')
					return true;
				else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]="
							+ row.id
							+ "&invoiceapply[objCode]="
							+ row.orderCode
							+ "&invoiceapply[objType]=KPRK-09");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : '���븶��',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
				if (row.ExaStatus == "���" && row.fundType == 'KXXZB')
					return true;
				else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&objType=YFRK-02&objId=" + row.id);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : '¼�뷢Ʊ',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
				if (row.ExaStatus == "���" && row.fundType == 'KXXZB')
					return true;
				else
					return false;
			},
			action : function(row, rows, grid) {
				if(row.orderMoney*1 <= accAdd(row.invotherMoney,row.returnMoney,2)*1){
					alert('��ͬ��¼�뷢Ʊ������');
					return false;
				}
				showModalWin("?model=finance_invother_invother&action=toAddObj&objType=YFQTYD02&objId=" + row.id);
			}
		} ,{
			name : 'stamp',
			text : '�������',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
				if (row.ExaStatus == "���" && row.isStamp != "1")
					return true;
				else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(row.isNeedStamp == '1'){
						alert('�˺�ͬ���������,�����ظ�����');
						return false;
					}
					showThickboxWin("?model=contract_other_other&action=toStamp&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			name : 'file',
			text : '�ϴ�����',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=contract_other_other&action=toUploadFile&id="
					+ row.id
					+ "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
			}
		},{
			name : 'change',
			text : '�����ͬ',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.status == 2 && row.ExaStatus == '���'){
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showOpenWin("?model=contract_other_other&action=toChange&id="
					+ row.id
					+ "&skey=" + row.skey_ );
			}
		}, {
			text : '�رպ�ͬ',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == "���" && row.status == "2") {
					return true;
				}
				return false;
			},
			action: function(row){
				if (window.confirm(("ȷ���ر���"))) {
					$.ajax({
						type : "POST",
						url : "?model=contract_other_other&action=changeStatus",
						data : { "id" : row.id },
						success : function(msg) {
							if( msg == 1 ){
								alert('�رճɹ���');
				                show_page();
							}else{
								alert('�ر�ʧ�ܣ�');
							}
						}
					});
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "���ύ" || row.ExaStatus == "���")) {
					return true;
				}
				return false;
			},
			action : function(rowData, rows, rowIds, g) {
				g.options.toDelConfig.toDelFn(g.options, g);
			}
		}],
		searchitems : [{
			display : '������',
			name : 'principalName'
		}, {
			display : 'ǩԼ��˾',
			name : 'signCompanyName'
		}, {
			display : '��ͬ����',
			name : 'orderName'
		}, {
			display : '��ͬ���',
			name : 'orderCodeSearch'
		},{
			display : 'ҵ����',
			name : 'objCodeSearch'
		}],
		isDelAction : false,
		// Ĭ�������ֶ���
		sortname : "c.createTime",
		// Ĭ������˳�� ����DESC ����ASC
		sortorder : "DESC",
		// ����״̬���ݹ���
		comboEx : [{
			text : "��������",
			key : 'fundType',
			datacode : 'KXXZ'
		}]
	});
});
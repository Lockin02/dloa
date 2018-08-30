var show_page = function(page) {
	$("#outsourcingGrid").yxgrid("reload");
};

$(function() {
    $("#outsourcingGrid").yxgrid({
        model : 'contract_outsourcing_outsourcing',
        action : 'myOutsourcingListPageJson',
        title : '�ҵ������ͬ',
		isViewAction : false,
		isAddAction :false,
		customCode : 'outsourcingGrid',
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
			},{
	            name : 'orderCode',
	            display : '������ͬ���',
	            sortable : true,
	            width : 130,
	            process : function(v,row){
					if(row.status == 4){
						return "<a href='#' style='color:red' title='����еĺ�ͬ' onclick='window.open(\"?model=contract_outsourcing_outsourcing&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}else{
						if(row.ExaStatus == '���ύ' || row.ExaStatus == '��������'){
							return "<a href='#' onclick='window.open(\"?model=contract_outsourcing_outsourcing&action=viewAlong&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
						}else{
							return "<a href='#' onclick='window.open(\"?model=contract_outsourcing_outsourcing&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
						}
					}
	            }
	        },{
	            name : 'orderName',
	            display : '��ͬ����',
	            sortable : true,
	            width : 130
	        },{
	            name : 'outContractCode',
	            display : '�����ͬ��',
	            sortable : true,
	            width : 130
	        },{
	            name : 'signCompanyName',
	            display : 'ǩԼ��˾',
	            sortable : true,
	            width : 130
	        },{
	            name : 'signCompanyId',
	            display : 'ǩԼ��˾id',
	            sortable : true,
	            hide : true
	        },{
	            name : 'proName',
	            display : '��˾ʡ��',
	            sortable : true,
	            hide : true
	        },{
	            name : 'proCode',
	            display : 'ʡ�ݱ���',
	            sortable : true,
	            hide : true
	        },{
	            name : 'address',
	            display : '��ϵ��ַ',
	            sortable : true,
	            hide : true
	        },{
	            name : 'phone',
	            display : '��ϵ�绰',
	            sortable : true,
	            hide : true
	        },{
	            name : 'linkman',
	            display : '��ϵ��',
	            sortable : true,
	            hide : true
	        },{
	            name : 'signDate',
	            display : 'ǩԼ����',
	            sortable : true,
	            width : 80
	        },{
	            name : 'beginDate',
	            display : '��ʼ����',
	            sortable : true,
	            width : 80
	        },{
	            name : 'endDate',
	            display : '��������',
	            sortable : true,
	            width : 80
	        },{
	            name : 'orderMoney',
	            display : '��ͬ���',
	            sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
	            width : 80
	        },{
	            name : 'initPayMoney',
	            display : '��ʼ������',
	            sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
	            width : 80
	        },{
	            name : 'initInvoiceMoney',
	            display : '��ʼ��Ʊ���',
	            sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
	            width : 80
	        },{
	            name : 'allCount',
	            display : '���շ�Ʊ',
	            sortable : true,
				process : function(v,row){
					if(v*1 != 0){
						var thisTitle = '���г�ʼ������Ʊ���Ϊ: ' + moneyFormat2(row.initInvoiceMoney) + ',������Ʊ���Ϊ��' + moneyFormat2(row.orgAllCount);
						return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
					}else{
						return 0;
					}
				},
	            width : 80
	        },{
	            name : 'applyedMoney',
	            display : '�����븶��',
	            sortable : true,
				process : function(v,row){
					if(v*1 != 0){
						var thisTitle = '���г�ʼ���븶����Ϊ: ' + moneyFormat2(row.initPayMoney) + ',�������븶����Ϊ��' + moneyFormat2(row.orgApplyedMoney);
						return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
					}else{
						return 0;
					}
				},
	            width : 80
	        },{
	            name : 'payedMoney',
	            display : '����ϼ�',
	            sortable : true,
				process : function(v,row){
					if(v*1 != 0){
						var thisTitle = '���г�ʼ���븶����Ϊ: ' + moneyFormat2(row.initPayMoney) + ',���ڸ�����Ϊ��' + moneyFormat2(row.orgPayedMoney);
						return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
					}else{
						return 0;
					}
				},
	            width : 80
	        },{
	            name : 'payTypeName',
	            display : '���ʽ',
	            sortable : true,
	            width : 100
	        },{
	            name : 'outsourcingName',
	            display : '�����ʽ',
	            sortable : true,
	            width : 80
	        },{
	            name : 'outsourceTypeName',
	            display : '�������',
	            sortable : true,
	            width : 80
	        },{
	            name : 'projectCode',
	            display : '��Ŀ���',
	            sortable : true
	        },{
	            name : 'projectName',
	            display : '��Ŀ����',
	            sortable : true,
	            width : 130,
	            hide : true
	        },{
	            name : 'deptName',
	            display : '��������',
	            sortable : true,
	            width : 100
	        },{
	            name : 'principalName',
	            display : '��ͬ������',
	            sortable : true
	        }, {
				name : 'status',
				display : '״̬',
				sortable : true,
				width : 60
				,
				process : function(v,row){
					if(row.id == "noId"){
						return '';
					}
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
			}, {
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
	        },{
	            name : 'objCode',
	            display : 'ҵ����',
	            sortable : true,
	            width : 110
	        },{
	            name : 'isNeedStamp',
	            display : '���������',
	            sortable : true,
	            width : 60,
	            process : function(v,row){
					if(row.id == "noId"){
						return '';
					}
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
					if(row.id == "noId"){
						return '';
					}
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
			formWidth : 1100,
			formHeight : 550,
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
					if(row.ExaStatus == '���ύ' || row.ExaStatus == '��������'){
						showModalWin("?model=contract_outsourcing_outsourcing&action=viewAlong&id=" + row.id + '&skey=' + row.skey_ );
					}else{
						showModalWin("?model=contract_outsourcing_outsourcing&action=viewTab&id=" + row.id + '&skey=' + row.skey_ );
					}
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
					if(row.isNeedPayapply == 1){
						$.ajax({
							type : "POST",
							url : "?model=contract_otherpayapply_otherpayapply&action=getFeeDeptId",
							data : { "contractId" : row.id ,'contractType' : 'oa_sale_outsourcing' },
							success : function(data) {
								if(data != '0'){
									showThickboxWin('controller/contract/outsourcing/ewf_forpayapply.php?actTo=ewfSelect&billId='
										+ row.id
										+ "&flowMoney=" + row.orderMoney
										+ "&flowDept=" + data
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
								}else{
									showThickboxWin('controller/contract/outsourcing/ewf_index.php?actTo=ewfSelect&billId='
										+ row.id
										+ "&flowMoney=" + row.orderMoney
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
								}
							}
						});
					}else{
						showThickboxWin('controller/contract/outsourcing/ewf_index.php?actTo=ewfSelect&billId='
								+ row.id
								+ "&flowMoney=" + row.orderMoney
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
					}
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			name : 'payapply',
			text : '���븶��',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
				if (row.ExaStatus == "���"){
					return true;
				}
				else
					return false;
			},
			action : function(row, rows, grid) {
				if(row.orderMoney*1 <= row.applyedMoney*1){
					alert('�������Ѵ�����');
					return false;
				}
				showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&objType=YFRK-03&objId=" + row.id);
			}
		}, {
			name : 'invoice',
			text : '¼�뷢Ʊ',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
				if (row.ExaStatus == "���")
					return true;
				else
					return false;
			},
			action : function(row, rows, grid) {
				if(row.allCount*1 >= row.orderMoney *1 ){
					alert('��ͬ¼��������,���ܼ���¼��');
					return false;
				}
				showModalWin("?model=finance_invother_invother&action=toAddObj&objType=YFQTYD01&objId=" + row.id);
			}
//		}, {
//			name : 'aduit',
//			text : '�������',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if (row.ExaStatus != "���ύ") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin("controller/common/readview.php?itemtype=oa_sale_outsourcing&pid="
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
//				}
//			}
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
					showThickboxWin("?model=contract_outsourcing_outsourcing&action=toStamp&id="
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
				showThickboxWin("?model=contract_outsourcing_outsourcing&action=toUploadFile&id="
					+ row.id
					+ "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
			}
		},{
			text : '�޸ı�ע',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=contract_outsourcing_outsourcing&action=toUpdateInfo&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
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
				showOpenWin("?model=contract_outsourcing_outsourcing&action=toChange&id="
					+ row.id
					+ "&skey=" + row.skey_ );
			}
		},{
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
						url : "?model=contract_outsourcing_outsourcing&action=changeStatus",
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
			showMenuFn:function(row){
				if((row.ExaStatus == "���ύ" || row.ExaStatus == "���")){
					return true;
				}
				return false;
			},
			action : function(rowData, rows, rowIds, g) {
				g.options.toDelConfig.toDelFn(g.options,g);
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
		sortorder : "DESC"
    });
});
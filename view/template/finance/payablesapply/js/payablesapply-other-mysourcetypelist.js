var show_page = function(page) {
    $("#payablesapplyGrid").yxsubgrid("reload");
};

$(function() {
    $("#payablesapplyGrid").yxsubgrid({
        model: 'finance_payablesapply_payablesapply',
        action : "myApplyJson",
        title: '�ҵ�������ͬ��������',
        isEditAction : false,
        isDelAction :false,
        isAddAction :false,
		customCode : 'myPayablesapplyGrid',
		param : {"sourceType" : $("#sourceType").val()},
        //����Ϣ
        colModel: [{
            display: '��ӡ',
            name: 'id',
            width : 30,
            align : 'center',
            sortable: false,
			process : function(v,row){
				if(row.id == 'noId') return '';
				if(row.printCount > 0){
					return '<img src="images/icon/print.gif" title="�Ѵ�ӡ����ӡ����Ϊ:'+ row.printCount+'"/>';
				}else{
					return '<img src="images/icon/print1.gif" title="δ��ӡ���ĵ���"/>';
				}
			}
        },{
            display: 'id',
            name: 'id',
            sortable: true,
			process : function(v,row){
				if(row.id == 'noId'){
					return v;
				}
				if(row.payFor == 'FKLX-03'){
					if(row.sourceType != ''){
						return "<a href='javascript:void(0)' title='�˿�����' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' title='�˿�����' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}
				}else{
					if(row.sourceType != ''){
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}
				}
			},
			width : 50
        },
        {
            name: 'formNo',
            display: '���뵥���',
            sortable: true,
            width : 140,
			process : function(v,row){
				if(row.id == 'noId'){
					return v;
				}
				if(row.payFor == 'FKLX-03'){
					if(row.sourceType != ''){
						return "<a href='javascript:void(0)' title='�˿�����' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' title='�˿�����' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}
				}else{
					if(row.sourceType != ''){
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
					}
				}
			}
        },
        {
            name: 'formDate',
            display: '��������',
            sortable: true,
            width : 80
        },
        {
            name: 'sourceType',
            display: 'Դ������',
            sortable: true,
            datacode : 'YFRK',
            width : 80
        },
        {
            name: 'payFor',
            display: '��������',
            sortable: true,
            datacode : 'FKLX',
            width : 80
        },
        {
            name: 'supplierName',
            display: '��Ӧ������',
            sortable: true,
            width : 150
        },
        {
            name: 'payMoney',
            display: '������',
            sortable: true,
            process : function(v){
				return moneyFormat2(v);
            },
            width : 80
        },
        {
            name: 'payedMoney',
            display: '�Ѹ����',
            sortable: true,
            process : function(v){
				return moneyFormat2(v);
            },
            width : 80
        },
        {
            name: 'status',
            display: '����״̬',
            sortable: true,
            datacode: 'FKSQD',
            width : 70
        },
        {
            name: 'ExaStatus',
            display: '����״̬',
            sortable: true,
            width : 80
        },
        {
            name: 'ExaDT',
            display: '����ʱ��',
            sortable: true,
            width : 80
        },
        {
            name: 'deptName',
            display: '���벿��',
            sortable: true
        },
        {
            name: 'salesman',
            display: '������',
            sortable: true
        },
        {
            name: 'feeDeptName',
            display: '���ù�������',
            sortable: true,
            width : 80
        },
        {
            name: 'feeDeptId',
            display: '���ù�������id',
            sortable: true,
            hide : true,
            width : 80
        },
        {
            name: 'shareStatus',
            display: '���÷�̯״̬',
            sortable: true,
            width : 80,
            process : function(v){
            	if(v == '1'){
            		return '�ѷ�̯';
            	}else if(v == '0'){
            		return 'δ��̯';
            	}else if(v == '2'){
            		return '���ַ�̯';
            	}
            }
        },
        {
            name: 'shareMoney',
            display: '��̯���',
            sortable: true,
            width : 80,
            process : function(v){
				return moneyFormat2(v);
            }
        },
        {
            name: 'createName',
            display: '������',
            sortable: true
        },
        {
            name: 'createTime',
            display: '��������',
            sortable: true,
            width : 120,
            hide : true
        },
        {
            name: 'printCount',
            display: '��ӡ����',
            sortable: true,
            width : 80
        }],

        // ���ӱ������
		subGridOptions : {
			url : '?model=finance_payablesapply_detail&action=pageJsonNone',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [
				{
					paramId : 'payapplyId',// ���ݸ���̨�Ĳ�������
					colId : 'id'// ��ȡ���������ݵ�������
				}
			],

			// ��ʾ����
			colModel : [{
					name : 'objType',
					display : 'Դ������',
					datacode : 'YFRK'
				},{
					name : 'objCode',
					display : 'Դ�����',
					width : 150
				},{
					name : 'money',
					display : '������',
					process : function(v){
						return moneyFormat2(v);
					}
				},{
					name : 'purchaseMoney',
					display : 'Դ�����',
					process : function(v){
						return moneyFormat2(v);
					}
				},{
					name : 'payDesc',
					display : '��ע˵��',
					width : 300
				}
			]
		},
        menusEx : [
        	{
				text : '�༭',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.ExaStatus == '���ύ') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if(row.sourceType != ''){
						showModalWin("?model=finance_payablesapply_payablesapply&action=toEdit&id=" + row.id + '&skey=' + row['skey_'],1);
					}else{
						showModalWin("?model=finance_payablesapply_payablesapply&action=init&owner=my&id=" + row.id + '&skey=' + row['skey_'],1);
					}
				}
			},
        	{
				text : '�ύ����',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.ExaStatus == '���ύ') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if(row.sourceType == 'YFRK-02' || row.sourceType == 'YFRK-03'){
						//add chenrf 20130504    ������ͬ�˿�����
						if(row.payFor == 'FKLX-03'){
							showThickboxWin('controller/finance/payablesapply/ewf_indexpayback.php?actTo=ewfSelect&billId='
								+ row.id + '&flowMoney=' + Math.abs(row.payMoney)
								+ '&skey=' + row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");

						}
						else{
							showThickboxWin('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId='
									+ row.id + '&flowMoney=' + row.payMoney
									+ '&billDept=' + row.feeDeptId
									+ '&skey=' + row['skey_']
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
						}

					}else{
						if(row.payFor == 'FKLX-03'){
							showThickboxWin('controller/finance/payablesapply/ewf_indexback.php?actTo=ewfSelect&billId='
								+ row.id + '&flowMoney=' + Math.abs(row.payMoney)
								+ '&skey=' + row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");

						}else{
							showThickboxWin('controller/finance/payablesapply/ewf_index.php?actTo=ewfSelect&billId='
								+ row.id + '&flowMoney=' + row.payMoney
								+ '&skey=' + row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
						}
					}
				}
			},
        	{
				text : 'ɾ��',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.ExaStatus == '���ύ') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
			        if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_payablesapply_payablesapply&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									show_page();
								}else{
									alert('ɾ��ʧ�ܣ�');
								}
							}
						});
					}
				}
			},
			{
				text : '�������',
				icon : 'view',
				showMenuFn : function(row) {
					if (row.ExaStatus != '���ύ') {
						return true;
					}
					return false;
				},
				action : function(row) {
					showThickboxWin('controller/common/readview.php?itemtype=oa_finance_payablesapply&pid='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				}
			},
        	{
				text : '�ύ����֧��',
				icon : 'edit',
				showMenuFn : function(row) {
					if(row.payDate ==""){
						if(confirm('ȷ���ύ֧����')){
							$.ajax({
								type : "POST",
								url : "?model=finance_payablesapply_payablesapply&action=handUpPay",
								data : {
									id : row.id
								},
								success : function(msg) {
									if (msg == 1) {
										alert('�ύ�ɹ���');
										show_page();
									}else{
										alert('�ύʧ�ܣ�');
									}
								}
							});
						}
					}else{
						var thisDate = formatDate(new Date());
						var s = DateDiff(thisDate,row.payDate);
						if(s>0){
							alert('���������������ڻ��� '+ s +" �죬�ݲ����ύ����֧��");
						}else{
							if(confirm('ȷ���ύ֧����')){
								$.ajax({
									type : "POST",
									url : "?model=finance_payablesapply_payablesapply&action=handUpPay",
									data : {
										id : row.id
									},
									success : function(msg) {
										if (msg == 1) {
											alert('�ύ�ɹ���');
											show_page();
										}else{
											alert('�ύʧ�ܣ�');
										}
									}
								});
							}
						}
					}
				}
			},
        	{
				name : 'file',
				text : '�ϴ�����',
				icon : 'add',
				showMenuFn : function(row) {
					if(row.status == 3){
						return false;
					}
				},
				action : function(row, rows, grid) {
					showThickboxWin("?model=finance_payablesapply_payablesapply&action=toUploadFile&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				}
			},{
				text : '��ӡ',
				icon : 'print',
				action : function(row, rows, grid) {
					showModalWin("?model=finance_payablesapply_payablesapply&action=toPrint&id=" + row.id + '&skey=' + row['skey_'],1);
				}
			},
//			{ //TODO
//				text : '¼����÷�̯',
//				icon : 'edit',
//				showMenuFn : function(row) {
//					if(row.id == 'noId'){
//						return false;
//					}
//					if (row.ExaStatus == '���') {
//						return true;
//					}
//					return false;
//				},
//				action : function(row, rows, grid) {
//					showThickboxWin('?model=finance_payablescost_payablescost&action=toShare&payapplyId='
//						+ row.id
//						+ '&payapplyCode=' + row.formNo
//						+ '&payapplyMoney=' + row.payMoney
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//				}
//			},
        	{
				text : '�ر�',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.ExaStatus == '���' &&(row.status == 'FKSQD-01' || row.status == 'FKSQD-00')) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
			        showThickboxWin('?model=finance_payablesapply_payablesapply&action=toClose&id='
						+ row.id
						+ '&skey=' + row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
        ],

        toAddConfig :{
        	toAddFn : function(p) {
				showModalWin("?model=finance_payablesapply_payablesapply&action=toAdd&owner=my");
			}
        },
        toViewConfig :{
        	toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					if(rowData.sourceType != ''){
						showModalWin("?model=finance_payablesapply_payablesapply&action=toView&id=" + rowData.id + keyUrl,1);
					}else{
						showModalWin("?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + rowData.id + keyUrl,1);
					}
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
        },

        //��������
		comboEx:[{
		     text:'����״̬',
		     key:'ExaStatus',
		     type : 'workFlow',
			 value : '���'
		   },{
		     text:'����״̬',
		     key:'status',
		     datacode : 'FKSQD'
		   },{
		     text:'���÷�̯״̬',
		     key:'shareStatus',
		     data : [{
					text : ' δ��̯',
					value : '0'
				}, {
					text : '���ַ�̯',
					value : '2'
				}, {
					text : '�ѷ�̯',
					value : '1'
				}
			]
		}],

		searchitems : [{
			display : '��Ӧ������',
			name : 'supplierName'
		},{
			display : '���뵥���',
			name : 'formNoSearch'
		},{
			display : '�������',
			name : 'objCodeSearch'
		},{
			display : '������',
			name : 'payMoney'
		}],
		sortname : 'c.status asc,c.formDate'
    });
});
var show_page = function(page) {
    $("#payablesapplyGrid").yxsubgrid("reload");
};

$(function() {
    $("#payablesapplyGrid").yxsubgrid({
        model: 'finance_payablesapply_payablesapply',
        title: '�˿�����',
        action : 'pageJsonList',
        param : {"payForArr" : 'FKLX-03'},
        isAddAction : false,
        isEditAction : false,
        isDelAction : false,
        customCode : 'payablesapplyGrid',
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
            width : 40,
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
			}
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
            name: 'isAdvPay',
            display: '��ǰ����',
            sortable: true,
            width : 80,
            hide: true,
            process : function(v){
				if( v == '1'){
					return '��';
				}else{
					return '��';
				}
            }
        },
        {
            name: 'actPayDate',
            display: 'ʵ���˿�����',
            sortable: true,
            width : 80
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
            name: 'bank',
            display: '��������',
            sortable: true,
            width : 120
        },
        {
            name: 'account',
            display: '�����˺�',
            sortable: true,
            width : 120
        },
        {
            name: 'payMoney',
            display: '������',
            sortable: true,
            process : function(v){
            	if(v >= 0){
					return moneyFormat2(v);
            	}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
            	}
            },
            width : 80
        },
        {
            name: 'payedMoney',
            display: '�Ѹ����',
            sortable: true,
            process : function(v){
            	if(v >= 0){
					return moneyFormat2(v);
            	}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
            	}
            },
            width : 80
        },
        {
            name: 'payMoneyCur',
            display: '��λ�ҽ��',
            sortable: true,
            process: function (v) {
                if (v >= 0) {
                    return moneyFormat2(v);
                } else {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
            },
            width: 80
        }, {
            name: 'currency',
            display: '�������',
            sortable: true,
            width: 60
        }, {
            name: 'rate',
            display: '����',
            sortable: true,
            width: 60
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
            name: 'ExaUser',
            display: '������',
            sortable: true
        },
        {
            name: 'ExaContent',
            display: '������Ϣ',
            sortable: true,
            width : 130
        },
        {
            name: 'deptName',
            display: '���벿��',
            sortable: true,
            width : 80
        },
        {
            name: 'salesman',
            display: '������',
            sortable: true,
            width : 80
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
            name: 'createName',
            display: '������',
            hide : true,
            sortable: true
        },
        {
            name: 'createTime',
            display: '��������',
            sortable: true,
            width : 120,
            hide : true
        }],

		// ���ӱ������
		subGridOptions : {
			url : '?model=finance_payablesapply_detail&action=pageJson',// ��ȡ�ӱ�����url
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
				}
			]
		},
        menusEx : [
        	{
				text : '�༭',
				icon : 'edit',
				showMenuFn : function(row) {
					if(row.id == 'noId'){
						return false;
					}
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
					if(row.id == 'noId'){
						return false;
					}
					if (row.ExaStatus == '���ύ') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if(row.sourceType == 'YFRK-02' || row.sourceType == 'YFRK-03'){
						showThickboxWin('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId='
							+ row.id + '&flowMoney=' + row.payMoney
							+ '&flowDept=' + row.feeDeptId
							+ '&skey=' + row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
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
			},{
				text : 'ɾ��',
				icon : 'delete',
				showMenuFn : function(row) {
					if(row.id == 'noId'){
						return false;
					}
					if (row.ExaStatus == '���ύ') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (confirm('ȷ��Ҫɾ����')) {
						$.post(
							"?model=finance_payablesapply_payablesapply&action=ajaxdeletes",
							{ "id": row.id },
							function(data){
								if(data == 1){
									alert('ɾ���ɹ�');
									show_page();
								}else{
									alert('ɾ��ʧ��');
									show_page();
								}
							}
						)
					}
				}
			},
			{
				text : '¼���˿��¼',
				icon : 'add',
				showMenuFn : function(row) {
					if(row.id == 'noId'){
						return false;
					}
					if (row.ExaStatus == '���' && row.status != 'FKSQD-03') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if(row.payFor == 'FKLX-01'){
						showOpenWin('?model=finance_payables_payables&action=toAddForApply&objId='
							+ row.id
							+ '&formType=CWYF-01'
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
					}else if(row.payFor == 'FKLX-02'){
						showOpenWin('?model=finance_payables_payables&action=toAddForApply&objId='
							+ row.id
							+ '&formType=CWYF-02'
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
					}else{
						showOpenWin('?model=finance_payables_payables&action=toAddForApply&objId='
							+ row.id
							+ '&formType=CWYF-03'
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
					}
				}
//			},{
//				text : '�������',
//				icon : 'view',showMenuFn : function(row) {
//					if (row.ExaStatus == '���' || row.ExaStatus == '��������') {
//						return true;
//					}
//					return false;
//				},
//				action : function(row) {
//					showThickboxWin('controller/common/readview.php?itemtype=oa_finance_payablesapply&pid='
//						+ row.id
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
//				}
			},
        	{
				text : '��ӡ',
				icon : 'print',
				showMenuFn : function(row) {
					if(row.id == 'noId'){
						return false;
					}
				},
				action : function(row, rows, grid) {
					showModalWin("?model=finance_payablesapply_payablesapply&action=toPrint&id=" + row.id + '&skey=' + row['skey_'],1);
				}
			}
        ],

        toAddConfig :{
        	toAddFn : function(p) {
//				showModalWin("?model=finance_payablesapply_payablesapply&action=toAdd");
				showModalWin("?model=finance_payablesapply_payablesapply&action=toAddDept&sourceType=YFRK-04");
			}
        },
        toViewConfig :{
			showMenuFn : function(row) {
				if(row.id == 'noId'){
					return false;
				}
			},
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
		buttonsEx :[{
				text: "ȷ���˿�",
				icon: 'add',
				action: function(row,rows,idArr ) {
					if(row){
						if(confirm('ȷ���˿�ô��')){
							var markIdSupplier = "";
							var markIsRed = "";
							var isSame = true;
							for (var i = 0; i < rows.length; i++) {
								if(rows[i].ExaStatus !== '���'){
									alert('���� ['+ rows[i].id +'] ����δ��ɣ����ܽ���ȷ�ϸ������');
									return false;
								}

								if(rows[i].status != 'FKSQD-01'){
									alert('���� ['+ rows[i].id +'] ����δ����״̬�����ܽ���ȷ�ϸ������');
									return false;
								}
							}
							idStr = idArr.toString();
							$.ajax({
								type : "POST",
								url : "?model=finance_payables_payables&action=addInGroupOneKey",
								data : {
									"ids" : idStr
								},
								success : function(msg) {
									if (msg == 1) {
										alert('¼��ɹ���');
										show_page(1);
									}else{
										alert('¼��ʧ��!');
									}
								}
							});
						}
					}else{
						alert('����ѡ������һ����¼');
					}
				}
			},
	        {
				name : 'view',
				text : "�߼���ѯ",
				icon : 'view',
				action : function() {
					showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
				}
	        },{
				name : 'excOut',
				text : "����",
				icon : 'excel',
				action : function() {
					$thisGrid = $("#payablesapplyGrid").data('yxsubgrid');
					$advArr = $("#payablesapplyGrid").yxsubgrid('getAdvSearchArr');

//					$.showDump($advArr);
					url = "?model=finance_payablesapply_payablesapply&action=excelOut"
						+ '&ExaStatus=' + filterUndefined( $thisGrid.options.param.ExaStatus )
						+ '&status=' + filterUndefined( $thisGrid.options.param.status )

						+ '&supplierName=' + filterUndefined( $thisGrid.options.param.supplierName )

						+ '&formDateBegin=' + filterUndefined( $thisGrid.options.param.formDateBegin )
						+ '&formDateEnd=' + filterUndefined( $thisGrid.options.param.formDateEnd )

						+ '&salesman=' + filterUndefined( $thisGrid.options.param.salesman )
						+ '&salesmanId=' + filterUndefined( $thisGrid.options.param.salesmanId )

						+ '&deptName=' + filterUndefined( $thisGrid.options.param.deptName )
						+ '&deptId=' + filterUndefined( $thisGrid.options.param.deptId )

						+ '&feeDeptName=' + filterUndefined( $thisGrid.options.param.feeDeptName )
						+ '&feeDeptId=' + filterUndefined( $thisGrid.options.param.feeDeptId )

						+ '&sourceType=' + filterUndefined( $thisGrid.options.param.sourceType )
						+ '&payForArr=FKLX-03'
					;

//					alert(url)
//					openPostWindow(url,$advArr,'�˿��');
//					window.open(url);
					window.open(url,"", "width=200,height=200,top=200,left=200");
				}
		    }
		],

        //��������
		comboEx:[{
		     text:'����״̬',
		     key:'ExaStatus',
		     type : 'workFlow',
			 value : '���'
		   },{
		     text:'����״̬',
		     key:'status',
		     datacode : 'FKSQD',
			 value : 'FKSQD-01'
		   }],

		searchitems : [{
			display : '��Ӧ������',
			name : 'supplierName'
		},{
			display : '���뵥���',
			name : 'formNoSearch'
		},{
			display : 'Դ�����',
			name : 'objCodeSearch'
		},{
			display : 'id',
			name : 'id'
		},{
			display : '������',
			name : 'salesmanSearch'
		},{
			display : '���벿��',
			name : 'deptNameSearch'
		},{
			display : '���ù�������',
			name : 'feeDeptNameSearch'
		}],
		sortorder : 'DESC',
		sortname : 'c.actPayDate DESC,c.formDate '
    });
});
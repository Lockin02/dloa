var show_page = function(page) {
    $("#payablesapplyGrid").yxsubgrid("reload");
};

$(function() {
    $("#payablesapplyGrid").yxsubgrid({
        model: 'finance_payablesapply_payablesapply',
        action : 'pageJsonForRead',
        title: '����������Ϣ',
        isEditAction : false,
        isDelAction : false,
        isAddAction : false,
        customCode : 'payablesapplyGrid',
        //����Ϣ
        colModel: [{
            display: '��ӡ',
            name: 'printId',
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
            display: '�����',
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
            name: 'formDate',
            display: '��������',
            sortable: true,
            width : 80
        },
        {
            name: 'payDate',
            display: '������������',
            sortable: true,
            width : 80
        },
        {
            name: 'actPayDate',
            display: 'ʵ�ʸ�������',
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
            sortable: false,
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
        }, {
                name: 'pchMoney',//Դ�����
                display: 'Դ����ͬ���',
                sortable: true,
                process: function(v) {
                    if (v >= 0) {
                        return moneyFormat2(v);
                    } else {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                },
                width: 80
            }, {
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
        menusEx : [{
				text : '��ӡ',
				icon : 'print',
				showMenuFn : function(row) {
					if(row.id == 'noId'){
						return false;
					}
					if (row.status != 'FKSQD-04' ) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					showModalWin("?model=finance_payablesapply_payablesapply&action=toPrint&id=" + row.id + '&skey=' + row['skey_'],1);
				}
			},{ //TODO
				text : '¼����÷�̯',
				icon : 'edit',
				showMenuFn : function(row) {
					if(row.id == 'noId' || row.payFor == 'FKLX-03'){
						return false;
					}
					if (row.ExaStatus == '���' && (row.status != 'FKSQD-04' || row.status != 'FKSQD-05')) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					showThickboxWin('?model=finance_payablescost_payablescost&action=toShare&payapplyId='
						+ row.id
						+ '&payapplyCode=' + row.formNo
						+ '&payapplyMoney=' + row.payMoney
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
        ],
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
		buttonsEx :[
	        {
				name : 'view',
				text : "�߼���ѯ",
				icon : 'view',
				action : function() {
					showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
				}
	        }
		],
      	//�߼�����
//		advSearchOptions : {
//			modelName : 'payablesapplySearch',
//			// ѡ���ֶκ��������ֵ����
//			selectFn : function($valInput) {
//				$valInput.yxselect_user("remove");
//			},
//			searchConfig : [{
//		            name : '��������',
//		            value : 'c.formDate',
//					changeFn : function($t, $valInput) {
//						$valInput.click(function() {
//							WdatePicker({
//								dateFmt : 'yyyy-MM-dd'
//							});
//						});
//					}
//		        },{
//		            name : '������������',
//		            value : 'c.payDate',
//					changeFn : function($t, $valInput) {
//						$valInput.click(function() {
//							WdatePicker({
//								dateFmt : 'yyyy-MM-dd'
//							});
//						});
//					}
//		        },{
//		            name : 'ʵ�ʸ�������',
//		            value : 'c.actPayDate',
//					changeFn : function($t, $valInput) {
//						$valInput.click(function() {
//							WdatePicker({
//								dateFmt : 'yyyy-MM-dd'
//							});
//						});
//					}
//		        },{
//					name : '������',
//					value : 'c.salesman',
//					changeFn : function($t, $valInput, rowNum) {
//						if (!$("#salesmanId" + rowNum)[0]) {
//							$hiddenCmp = $("<input type='hidden' id='salesmanId"+ rowNum + "'/>");
//							$valInput.after($hiddenCmp);
//						}
//						$valInput.yxselect_user({
//							hiddenId : 'salesmanId' + rowNum,
//							height : 200,
//							width : 550,
//							formCode : 'payablesapply',
//							gridOptions : {
//								showcheckbox : false
//							}
//						});
//					}
//				},{
//		            name : '��Ӧ������',
//		            value : 'c.supplierName'
//		        },{
//		            name : 'ԭ������',
//		            value : 'sourceType',
//					type:'select',
//		            datacode : 'YFRK'
//		        }
//			]
//		},

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
var show_page = function (page) {
    $("#borrowequGrid").yxgrid("reload");
};
$(function () {
    $("#borrowequGrid").yxsubgrid({
        model: 'projectmanagent_borrow_borrow',
        action: 'borrowequJsons',
        param: {
        	'customerId': $("#customerId").val(),
        	'showAll': $("#showAll").val()
        },
        showcheckbox: true,
        isAddAction: false,
        isDelAction: false,
        isEditAction: false,
        isViewAction: false,
        // ����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'chanceId',
                display: '�̻�Id',
                sortable: true,
                hide: true
            },
            {
                name: 'Code',
                display: '���',
                sortable: true,
    			process : function(v, row) {
    				if (row.salesNameId == $("#salesNameId").val()){
    					return "<font color='red'>" + v + "</font>" + '</a>';
    				}else{
    					return "<font color='green'>" + v + "</font>" + '</a>';
    				}
    			}
            },
            {
                name: 'Type',
                display: '����',
                sortable: true,
                hide: true
            },
            {
                name: 'customerName',
                display: '�ͻ�����',
                sortable: true,
                width: 120
            },
            {
                name: 'salesName',
                display: '���۸�����',
                sortable: true,
                width: 80
            },
            {
                display: 'salesNameId',
                name: 'salesNameId',
                sortable: true,
                hide: true
            },
            {
                name: 'beginTime',
                display: '��ʼ����',
                sortable: true,
                width: 80
            },
            {
                name: 'closeTime',
                display: '��ֹ����',
                sortable: true,
                width: 80
            },
            {
                name: 'scienceName',
                display: '����������',
                sortable: true,
                width: 80
            },
            {
                name: 'status',
                display: '����״̬',
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "����";
                    } else if (v == '1') {
                        return "���ֹ黹";
                    } else if (v == '2') {
                        return "�ر�";
                    } else if (v == '3') {
                        return "�˻�";
                    } else if (v == '4') {
                        return "����������"
                    } else if (v == '5') {
                        return "ת��ִ�в�"
                    } else if (v == '6') {
                        return "ת��ȷ����"
                    } else {
                        return v;
                    }
                },
                width: 70
            },
            {
                name: 'ExaStatus',
                display: '����״̬',
                sortable: true,
                width: 70
            }
            ,
            {
                name: 'backStatus',
                display: '�黹״̬',
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "δ�黹";
                    } else if (v == '1') {
                        return "�ѹ黹";
                    } else if (v == '2') {
                        return "���ֹ黹";
                    }
                },
                width: 70
            }
            ,
            {
                name: 'DeliveryStatus',
                display: '����״̬',
                sortable: true,
                process: function (v) {
                    if (v == 'WFH') {
                        return "δ����";
                    } else if (v == 'YFH') {
                        return "�ѷ���";
                    } else if (v == 'BFFH') {
                        return "���ַ���";
                    } else if (v == 'TZFH') {
                        return "ֹͣ����";
                    }
                },
                width: 70
            },
            {
                name: 'ExaDT',
                display: '����ʱ��',
                sortable: true,
                hide: true,
                width: 70
            },
            {
                name: 'createName',
                display: '������',
                sortable: true,
                hide: true
            },
            {
                name: 'remark',
                display: '��ע',
                sortable: true
            },
            {
                name: 'objCode',
                display: 'ҵ����',
                width: 120
            }
        ],
        comboEx: [
            {
                text: '���ݹ�������',
                key: 'objType',
                value: $("#chanceId").val(),
                data: [
                    {
                        text: '�̻�',
                        value: $("#chanceId").val()
                    }
                ]
            }
        ],
        buttonsEx: [
            {
                name: 'Add',
                text: "ѡ��",
                icon: 'add',
                action: function (row, rows, rowIds, g) {
                    var dataArr = g.getAllSubSelectRowDatas();
                    if (dataArr) {
                    	var salesNameId = $("#salesNameId").val();
                    	var isWarming = false;
                    	$.each(dataArr,function(){
                    		if(salesNameId != $("#row" + this.borrowId).find("td[namex='salesNameId'] div").html()){
                    			isWarming = true;
                            	return false;
                    		}
                    	})
                    	if(isWarming){
                    		if(!confirm('���ڷǵ�ǰ�������½����õ������ϣ��Ƿ���Ҫѡ��')){
                    			return false;
                    		}
                    	}
                        parent.window.returnValue = dataArr;
                        // $.showDump(outJson);
                        parent.window.close();
                    } else {
                        alert('����ѡ���¼');
                    }
                }
            }
        ],
        // ���ӱ������
        subGridOptions: {
            subgridcheck: true,
            url: '?model=projectmanagent_borrow_borrowequ&action=listJsonEqu&checkIds=' + $("#checkIds").val(),// ��ȡ�ӱ�����url
            // ���ݵ���̨�Ĳ�����������
            param: [
                {
                    paramId: 'borrowId',// ���ݸ���̨�Ĳ�������
                    colId: 'id'// ��ȡ���������ݵ�������
                }
            ],
            colModel: [
                {
                    name: 'productName',
                    display: '��������',
                    sortable: true
                },
                {
                    name: 'productId',
                    display: '����ID',
                    sortable: true
                },
                {
                    name: 'productNo',
                    display: '���ϱ���',
                    sortable: true
                },
                {
                    name: 'productModel',
                    display: '���/�ͺ�',
                    sortable: true
                },
                {
                    name: 'warrantyPeriod',
                    display: '������',
                    sortable: true
                },
                {
                    name: 'number',
                    display: '����',
                    sortable: true
                },
                {
                    name: 'executedNum',
                    display: '��ִ������',
                    sortable: true
                },
                {
                    name: 'backNum',
                    display: '�黹����',
                    sortable: true
                }
//					, {
//						name : 'toContractNum',
//						display : 'ת��������',
//						sortable : true,
//						width : 50,
//						process : function(v){
//						   if(v == ''){
//						      return 0;
//						   }else{
//						      return v;
//						   }
//						}
//					}
                ,
                {
                    name: 'price',
                    display: '����',
                    sortable: true
                },
                {
                    name: 'money',
                    display: '���',
                    sortable: true
                },
                {
                    name: 'borrowId',
                    display: '������Դ��ID',
                    sortable: true,
                    hide: true
                }
            ]
        },
//		event : {
//			'row_dblclick' : function(e, row, rowData) {
//				if (rowData) {
//					// ���json
//					outJson = {
//						"id" : rowData.id,
//						"prodcutId" : rowData.productId,
//						"productCode" : rowData.prodcutNo,
//						"productName" : rowData.productName,
//						"productModel" : rowData.productModel,
//						"number" : rowData.number,
//						"price" : rowData.price,
//						"money" : rowData.money,
//						"warrantyPeriod" : rowData.warrantyPeriod,
//						"isBorrowToorder" : 1,
//						"toBorrowId" : rowData.borrowId,
//						"toBorrowequId" : rowData.id
//					};
//					parent.window.returnValue = outJson;
//
//					// $.showDump(outJson);
//					parent.window.close();
//				} else {
//					alert('����ѡ���¼');
//				}
//			}
//		},
//		toViewConfig : {
//			action : 'toView'
//		},
        searchitems: [
            {
                display: "���",
                name: 'Code'
            },
            {
                display: "�ͻ�����",
                name: 'customerName'
            },
            {
                display: "���۸�����",
                name: 'salesName'
            }, {
                display: '���к�',
                name: 'serialName2'
            }
        ]
    });

});

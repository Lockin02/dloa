var show_page = function(page) {
    $("#payablesapplychangeGrid").yxgrid("reload");
};

$(function() {
    $("#payablesapplychangeGrid").yxgrid({
        model: 'finance_payablesapply_payablesapplychange',
        title: '�������ڱ������',
        action : 'pageJson',
        isAddAction : false,
        isEditAction : false,
        isDelAction : false,
        param : {
        	salesmanId : $("#salesmanId").val()
        },
        //����Ϣ
        colModel: [{
        	name: 'purOrderId',
            display: '�������뵥Id',
            sortable: true,
            width : 80
//            process:function(v){
//            	return "<a href='javascript:void(0)' title='�������뵥id' " +
//            			"style='color:blue' onclick='showModalWin(\"?model=" +
//            			"finance_payablesapply_payablesapply&action=toView&id="
//            			+row.purOrderId"&skey="+row.skey_+"\")'>"+v+"</a>";
//            }
        },{
            name: 'supplierName',
            display: '��Ӧ������',
            sortable: true,
            width : 180
        },{
            name: 'payMoney',
            display: '������',
            sortable: true,
            width : 80,
            process : function(v){
            	return moneyFormat2(v);
            }
        },{
            name: 'perAuditDate',
            display: '�޸�ǰ������������',
            sortable: true,
            width : 120
        },{
            name: 'newAuditDate',
            display: '�޸ĺ�������������',
            sortable: true,
            width : 120
        },{
            name: 'ExaStatus',
            display: '����״̬',
            sortable: true,
            width : 80
        },{
            name: 'ExaDT',
            display: '����ʱ��',
            sortable: true,
            width : 80
        },{
            name: 'salesman',
            display: '������',
            sortable: true,
            width : 80
        },{
            name: 'deptName',
            display: '���벿��',
            sortable: true,
            width : 100
        },{
            name: 'businessBelongName',
            display: '������˾',
            sortable: true,
            width : 80
        },{
            name: 'createName',
            display: '������',
            sortable: true,
            width : 80
        },{
            name: 'createTime',
            display: '����ʱ��',
            sortable: true,
            width : 100
        }],

        toViewConfig : {
        	action : 'toView'
        },
        
		searchitems : [{
			display : '��Ӧ������',
			name : 'supplierName'
		},{
			display : '�������뵥id',
			name : 'purOrderId'
		}]
    });
});
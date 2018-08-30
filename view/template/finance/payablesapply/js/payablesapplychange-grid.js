var show_page = function(page) {
    $("#payablesapplychangeGrid").yxgrid("reload");
};

$(function() {
    $("#payablesapplychangeGrid").yxgrid({
        model: 'finance_payablesapply_payablesapplychange',
        title: '付款日期变更申请',
        action : 'pageJson',
        isAddAction : false,
        isEditAction : false,
        isDelAction : false,
        param : {
        	salesmanId : $("#salesmanId").val()
        },
        //列信息
        colModel: [{
        	name: 'purOrderId',
            display: '付款申请单Id',
            sortable: true,
            width : 80
//            process:function(v){
//            	return "<a href='javascript:void(0)' title='付款申请单id' " +
//            			"style='color:blue' onclick='showModalWin(\"?model=" +
//            			"finance_payablesapply_payablesapply&action=toView&id="
//            			+row.purOrderId"&skey="+row.skey_+"\")'>"+v+"</a>";
//            }
        },{
            name: 'supplierName',
            display: '供应商名称',
            sortable: true,
            width : 180
        },{
            name: 'payMoney',
            display: '申请金额',
            sortable: true,
            width : 80,
            process : function(v){
            	return moneyFormat2(v);
            }
        },{
            name: 'perAuditDate',
            display: '修改前审批付款日期',
            sortable: true,
            width : 120
        },{
            name: 'newAuditDate',
            display: '修改后审批付款日期',
            sortable: true,
            width : 120
        },{
            name: 'ExaStatus',
            display: '审批状态',
            sortable: true,
            width : 80
        },{
            name: 'ExaDT',
            display: '审批时间',
            sortable: true,
            width : 80
        },{
            name: 'salesman',
            display: '申请人',
            sortable: true,
            width : 80
        },{
            name: 'deptName',
            display: '申请部门',
            sortable: true,
            width : 100
        },{
            name: 'businessBelongName',
            display: '归属公司',
            sortable: true,
            width : 80
        },{
            name: 'createName',
            display: '创建人',
            sortable: true,
            width : 80
        },{
            name: 'createTime',
            display: '创建时间',
            sortable: true,
            width : 100
        }],

        toViewConfig : {
        	action : 'toView'
        },
        
		searchitems : [{
			display : '供应商名称',
			name : 'supplierName'
		},{
			display : '付款申请单id',
			name : 'purOrderId'
		}]
    });
});
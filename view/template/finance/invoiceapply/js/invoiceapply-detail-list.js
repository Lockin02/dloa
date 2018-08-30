/**��Ʊ�����б�**/
var show_page = function(){
   $("#invoiceapplyGrid").yxgrid("reload");
};

$(function(){
    $("#invoiceapplyGrid").yxgrid({
    	model:'finance_invoiceapply_invoiceapply',
		param : {'objId' : $('#objId').val(),'objType' : $('#objType').val()},
		action : 'objPageJson',
    	title:'���еĿ�Ʊ����',
    	isAddAction:false,
    	isViewAction:false,
    	isEditAction:false,
    	isDelAction:false,
    	showcheckbox: false,
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : '���뵥��',
				name : 'applyNo',
				sortable : true,
				width:150,
				process : function(v,row){
					return "<a href='#' onclick='showModalWin(\"?model=finance_invoiceapply_invoiceapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\",1,"+row.id+")'>" + v + "</a>";
				}
			},
			{
				display : '�ͻ���λ',
				name : 'customerName',
				width:200
			},
			{
				display : '������',
				sortable : true,
				name : 'createName',
				width:90
			},
            {
                display : '����ʱ��',
                sortable : true,
                name : 'applyDate',
                width:90
            },
			{
				display : '��������',
				name : 'invoiceTypeName',
				sortable : true,
				width:130
			},
			{
				display : '������',
				name : 'invoiceMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width:90
			},
			{
				display : '�ѿ����',
				name : 'payedAmount',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width:90
			},
			{
				display : '����״̬',
				sortable : true,
				name : 'ExaStatus',
				width:80
			},
            {
                display : '��������',
                sortable : true,
                name : 'ExaDT',
                width:80
            }
		],
		//��չ�Ҽ��˵�
		menusEx : [
		{
			text : '�鿴',
			icon : 'view',
			action : function(row){
                showModalWin('?model=finance_invoiceapply_invoiceapply&action=init&perm=view'
                    + '&id=' + row.id + '&skey=' + row['skey_']
                    + '&perm=view',1,row.id);
			}
		},{
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
                return row.ExaStatus != '���ύ' ? true : false;
			},
			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_finance_invoiceapply&pid='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		},{
			text : '��ӡ',
			icon : 'print',
			action : function(row) {
				showModalWin('?model=finance_invoiceapply_invoiceapply&action=printInvoiceApply'
				    + '&id=' + row.id + '&skey=' + row['skey_'],1,row.id);
			}
		}],
        comboEx:[{
            text: "����״̬",
            key: 'ExaStatus',
            type : 'workFlow'
        },{
            text: "��Ʊ״̬",
            key: 'moneyStatus',
            data : [{
                text : '���',
                value : 'done'
            }, {
                text : 'δ���',
                value : 'undo'
            }]
        }],
		searchitems:[
	        {
	            display:'�ͻ���λ',
	            name:'customerNameSearch'
	        },
	        {
	            display:'ҵ����',
	            name:'objCodeSearch'
	        },
	        {
	            display:'������',
	            name:'createName'
	        },
	        {
	            display:'��Ʊ���뵥��',
	            name:'applyNo'
	        }
        ]
    });
});
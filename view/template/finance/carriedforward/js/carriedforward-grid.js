var show_page = function(page) {
	$("#carriedforwardGrid").yxgrid("reload");
};

$(function() {
    $("#carriedforwardGrid").yxgrid({
        model : 'finance_carriedforward_carriedforward',
        title : '��ͬ��ת��',
        isAddAction : false,
        isEditAction : false,
        showcheckbox : false,
        isDelAction : false,
        //����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        },{
            name : 'customerName',
            display : '�ͻ�',
            sortable : true,
            width : 130
        },{
            name : 'saleId',
            display : '��ͬid',
            sortable : true,
            hide : true
        },{
            name : 'saleCode',
            display : '��ͬ���',
            sortable : true,
            width : 130
        },{
            name : 'rObjCode',
            display : 'ҵ����',
            sortable : true,
            width : 110
        },{
            name : 'invoiceNo',
            display : '��Ʊ��',
            sortable : true
        },{
            name : 'productModel',
            display : '��Ʒ����',
            sortable : true,
            datacode : 'CWCPLX',
            width : 80
        },{
            name : 'invoiceId',
            display : '��Ʊid',
            sortable : true,
            hide : true
        },{
            name : 'outStockId',
            display : '���ⵥ��id',
            sortable : true,
            hide : true
        },{
            name : 'outStockCode',
            display : '���ⵥ�ݱ��',
            sortable : true
        },{
            name : 'productName',
            display : '��������',
            sortable : true,
            width : 120
        },{
            name : 'outStockType',
            display : '���ⵥ������',
            sortable : true,
            datacode : 'CKDLX',
            width : 80,
            hide : true
        },{
            name : 'subCost',
            display : '�ɱ�',
            sortable : true,
            process : function (v){
				return moneyFormat2(v);
            }
        },{
            name : 'carryMoney',
            display : '��ת���',
            sortable : true,
            process : function (v){
				return moneyFormat2(v);
            }
        },{
            name : 'outStockDetailId',
            display : '�������ϼ�¼id',
            sortable : true,
            hide : true
        }         ,{
            name : 'carryRate',
            display : '��ת����(%)',
            sortable : true,
            width : 80
        },{
            name : 'createName',
            display : '����������',
            sortable : true,
            hide : true
        },{
            name : 'periodNo',
            display : '����������',
            sortable : true,
            width : 80
        },{
            name : 'status',
            display : '״̬',
            sortable : true,
            hide : true,
            width : 80
        },{
            name : 'carryType',
            display : '��ת����',
            sortable : true,
            width : 80,
            process : function (v){
				if(v == 0){
					return '��Ʊ��ת';
				}else{
					return '�����ת';
				}
            }
        },{
            name : 'createName',
            display : '������',
            sortable : true
        }],
        menusEx:[
		   {
		     text:'ɾ��',
		     icon:'delete',
		     action:function(row,rows,grid){
		        if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=finance_carriedforward_carriedforward&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#carriedforwardGrid").yxgrid("reload");
							}
						}
					});
				}
		     }
		   }

        ],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ���',
			name : 'saleCodeSearch'
		},{
			display : '���ⵥ��',
			name : 'outStockCodeSearch'
		},{
			display : '�ͻ�����',
			name : 'customerName'
		}],
        comboEx : [ {
			text : '��ת����',
			key : 'carryType',
			data : [ {
				text : '��Ʊ��ת',
				value : 0
			}, {
				text : ' �����ת',
				value : 1
			}]
		}]
    });
});
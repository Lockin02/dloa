/**�����б�**/

var show_page=function(page){
   $("#receviableGrid").yxgrid("reload");
};

$(function(){
    $("#receviableGrid").yxgrid({
    	model:'finance_receviable_receviable',
    	title:'���е�Ӧ���˿�',
    	param : {"contStatus" : '1,3,9','ExaStatus' : '���'},
    	isToolBar:false,
    	isAddAction :false,
    	isViewAction :false,
    	isEditAction:false,
    	isDelAction:false,
    	showcheckbox:false,

		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : '������',
				name : 'temporaryNo',
				sortable : true,
				width:130
			}, {
				display : '��ͬ��',
				name : 'contNumber',
				sortable : true,
				width:130
			},{
				display : '��ͬ����',
				name : 'contName',
				width:160
			},
			{
				display : '��ͬ��λ',
				name : 'customerName',
				width:130
			},
			{
				display : '��ͬ���',
				name : 'money',
				width : 90,
				process :function(v){
					return moneyFormat2(v);
				}
			},
			{
				display : '��Ʊ���',
				name : 'invoiceMoney',
				width : 90,
				process :function(v){
					return moneyFormat2(v);
				}
			},
			{
				display : '���ս��',
				name : 'incomeMoney',
				width : 90,
				process :function(v){
					return moneyFormat2(v);
				}
			},
			{
				display : 'ʣ����',
				name : 'remainMoney',
				width : 90,
				process :function(v){
					return moneyFormat2(v);
				}
			},
			{
				display : '��Ʊ�ٷֱ�',
				name : 'percentageInv',
				width : 70,
				process :function(v){
					if(v != ""){
						return v + ' <font color="blue">%</font>';
					}
				}
			},
			{
				display : '����ٷֱ�',
				name : 'percentage',
				width : 70,
				process :function(v){
					if(v != ""){
						return v + ' <font color="blue">%</font>';
					}
				}
			},
				{
					display : '��ͬ״̬',
					name : 'contStatus',
					width : 60,
					process :function(v){
						switch (v) {
							case '1': return '��ִ��';break;
							case '3': return '�����';break;
							case '9': return '�ѹر�';break;
							default : return 'δ����';break;
						}
				}
			},
			{
				display : '�ͻ�����',
				name : 'customerType',
				width : 90,
				datacode : 'KHLX'
			},
			{
				display : 'ʡ��',
				width : 60,
				name : 'province'
			}
		],
		searchitems:[{
            display:'��ͬ��',
            name:'contNumber'
        }],
		sortname:'id',

		//��չ�Ҽ��˵�
		menusEx : [
		{
			text : '�鿴��ͬ',
			icon : 'view',
			action :function(row,rows,grid) {
				if(row){
                   showOpenWin("?model=contract_sales_sales&action=infoTab"
					+ "&id="
					+ row.id
					+"&contNumber="
					+row.contNumber)
				}else{
					alert("��ѡ��һ������");
				}
			}

		}]
    });
});
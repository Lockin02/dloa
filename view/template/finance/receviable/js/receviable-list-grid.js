/**到款列表**/

var show_page=function(page){
   $("#receviableGrid").yxgrid("reload");
};

$(function(){
    $("#receviableGrid").yxgrid({
    	model:'finance_receviable_receviable',
    	title:'所有的应收账款',
    	param : {"contStatus" : '1,3,9','ExaStatus' : '完成'},
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
				display : '订单号',
				name : 'temporaryNo',
				sortable : true,
				width:130
			}, {
				display : '合同号',
				name : 'contNumber',
				sortable : true,
				width:130
			},{
				display : '合同名称',
				name : 'contName',
				width:160
			},
			{
				display : '合同单位',
				name : 'customerName',
				width:130
			},
			{
				display : '合同金额',
				name : 'money',
				width : 90,
				process :function(v){
					return moneyFormat2(v);
				}
			},
			{
				display : '开票金额',
				name : 'invoiceMoney',
				width : 90,
				process :function(v){
					return moneyFormat2(v);
				}
			},
			{
				display : '已收金额',
				name : 'incomeMoney',
				width : 90,
				process :function(v){
					return moneyFormat2(v);
				}
			},
			{
				display : '剩余金额',
				name : 'remainMoney',
				width : 90,
				process :function(v){
					return moneyFormat2(v);
				}
			},
			{
				display : '开票百分比',
				name : 'percentageInv',
				width : 70,
				process :function(v){
					if(v != ""){
						return v + ' <font color="blue">%</font>';
					}
				}
			},
			{
				display : '到款百分比',
				name : 'percentage',
				width : 70,
				process :function(v){
					if(v != ""){
						return v + ' <font color="blue">%</font>';
					}
				}
			},
				{
					display : '合同状态',
					name : 'contStatus',
					width : 60,
					process :function(v){
						switch (v) {
							case '1': return '正执行';break;
							case '3': return '变更中';break;
							case '9': return '已关闭';break;
							default : return '未启动';break;
						}
				}
			},
			{
				display : '客户类型',
				name : 'customerType',
				width : 90,
				datacode : 'KHLX'
			},
			{
				display : '省份',
				width : 60,
				name : 'province'
			}
		],
		searchitems:[{
            display:'合同号',
            name:'contNumber'
        }],
		sortname:'id',

		//扩展右键菜单
		menusEx : [
		{
			text : '查看合同',
			icon : 'view',
			action :function(row,rows,grid) {
				if(row){
                   showOpenWin("?model=contract_sales_sales&action=infoTab"
					+ "&id="
					+ row.id
					+"&contNumber="
					+row.contNumber)
				}else{
					alert("请选中一条数据");
				}
			}

		}]
    });
});
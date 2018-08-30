/**到款列表**/

var show_page=function(page){
   $("#invoiceGrid").yxgrid("reload");
};

$(function(){
        $("#invoiceGrid").yxgrid({
        	model:'finance_invoice_invoice',
        	//action:'pageJson',
        	param : {"applyId" : $('#applyId').val()},
        	title:'开票记录 - ' + $('#applyNo').val() ,
        	isToolBar:true,
        	isAddAction:false,
        	isEditAction : false,
        	isDelAction : false,

			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				display : '发票号',
				name : 'invoiceNo',
				sortable : true

			},{
				display : '开票申请id',
				name : 'applyId',
				hide : true

			}, {
				display : '开票申请单号',
				name : 'applyNo',
				sortable : true,
				width : 150
			}, {
				display : '开票类型',
				name : 'invoiceTypeName',
				sortable : true
			}, {
				display : '总金额',
				name : 'invoiceMoney',
				sortable : true,
				width : 100,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : '软件金额',
				name : 'softMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : '硬件金额',
				name : 'hardMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : '服务金额',
				name : 'serviceMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}

			}, {
				display : '维修金额',
				name : 'repairMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : '设备租赁金额',
				name : 'equRentalMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : '场地租赁金额',
				name : 'spaceRentalMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			},  {
				display : '其他金额',
				name : 'otherMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			},  {
				display : '代收电费总金额',
				name : 'dsEnergyCharge',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			},  {
				display : '代收水费总金额',
				name : 'dsWaterRateMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			},  {
				display : '房屋出租总金额',
				name : 'houseRentalFee',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			},  {
				display : '安装服务总金额',
				name : 'installationCost',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : '开票人',
				name : 'createName',
				sortable : true
			}, {
				display : '开票日期',
				name : 'invoiceTime',
				sortable : true
			},
			{
				display : '已邮寄',
				name : 'isMail',
				width:50,
				process : function(v){
					if(v == 1){
						return '是';
					}else{
						return '否';
					}
				}
			}],
			menusEx : [
				{
					name : 'view',
					text : "邮寄信息",
					icon : 'view',
					action : function(row, rows, grid) {
						showThickboxWin("?model=mail_mailinfo&action=viewByDoc&docId="
							+ row.id
							+ '&docType=YJSQDLX-FPYJ'
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
					}
				}
			],
			searchitems:[
			        {
			            display:'发票号',
			            name:'invoiceNo'
			        }
			        ],
			toViewConfig : {
				formWidth : 800,
				formHeight : 500
			},
			buttonsEx : [
				{
					name : 'back',
					text : '关闭',
					icon : 'edit',
					action : function() {
						self.close();
					}
				}
			],
			sortorder:'DESC'
        });
});
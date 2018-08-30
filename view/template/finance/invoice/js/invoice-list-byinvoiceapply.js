/**�����б�**/

var show_page=function(page){
   $("#invoiceGrid").yxgrid("reload");
};

$(function(){
        $("#invoiceGrid").yxgrid({
        	model:'finance_invoice_invoice',
        	//action:'pageJson',
        	param : {"applyId" : $('#applyId').val()},
        	title:'��Ʊ��¼ - ' + $('#applyNo').val() ,
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
				display : '��Ʊ��',
				name : 'invoiceNo',
				sortable : true

			},{
				display : '��Ʊ����id',
				name : 'applyId',
				hide : true

			}, {
				display : '��Ʊ���뵥��',
				name : 'applyNo',
				sortable : true,
				width : 150
			}, {
				display : '��Ʊ����',
				name : 'invoiceTypeName',
				sortable : true
			}, {
				display : '�ܽ��',
				name : 'invoiceMoney',
				sortable : true,
				width : 100,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : '������',
				name : 'softMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : 'Ӳ�����',
				name : 'hardMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : '������',
				name : 'serviceMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}

			}, {
				display : 'ά�޽��',
				name : 'repairMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : '�豸���޽��',
				name : 'equRentalMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : '�������޽��',
				name : 'spaceRentalMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			},  {
				display : '�������',
				name : 'otherMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			},  {
				display : '���յ���ܽ��',
				name : 'dsEnergyCharge',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			},  {
				display : '����ˮ���ܽ��',
				name : 'dsWaterRateMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			},  {
				display : '���ݳ����ܽ��',
				name : 'houseRentalFee',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			},  {
				display : '��װ�����ܽ��',
				name : 'installationCost',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : '��Ʊ��',
				name : 'createName',
				sortable : true
			}, {
				display : '��Ʊ����',
				name : 'invoiceTime',
				sortable : true
			},
			{
				display : '���ʼ�',
				name : 'isMail',
				width:50,
				process : function(v){
					if(v == 1){
						return '��';
					}else{
						return '��';
					}
				}
			}],
			menusEx : [
				{
					name : 'view',
					text : "�ʼ���Ϣ",
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
			            display:'��Ʊ��',
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
					text : '�ر�',
					icon : 'edit',
					action : function() {
						self.close();
					}
				}
			],
			sortorder:'DESC'
        });
});
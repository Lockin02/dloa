/**�������Ĳɹ�ѯ�۵� �б�
 *2010-5-14 can
 */

var show_page = function(page) {
	$("#inquirysheetyYesGrid").yxsubgrid("reload");
};
$(function() {
	$("#inquirysheetyYesGrid").yxsubgrid({
		isTitle:true,
		title:'�������Ĳɹ�ѯ�۵�',
		isToolBar : true,
		isAddAction:false,
		isViewAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		menuWidth : 130,
		model : 'purchase_inquiry_inquirysheet',
		action : 'pageJsonAuditYes',

			// ����Ϣ
			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : 'ѯ�۵����',
				name : 'inquiryCode',
				sortable : true,
				width : 160
			}, {
				display : '�ɹ�Ա',
				name : 'purcherName',
				sortable : true
			}, {
				display : 'ѯ������',
				name : 'inquiryBgDate',
				sortable : true
			}, {
				display : 'ָ����Ӧ��',
				name : 'suppName',
				sortable : true,
				width:160
			},
			{
				display : '��Ӧ��ID',
				name : 'suppId',
				sortable : true,
				hide : true
			},
			{
				display : 'ָ��������',
				name : 'amaldarName',
				sortable : true
			},  {
				display : '����״̬',
				name : 'ExaStatus',
//				sortable : true,
				width:60
			}],
			searchitems : [{
				display : 'ѯ�۵����',
				name : 'inquiryCode'
			},{
				display : '�ɹ�Ա',
				name : 'purcherName'
			},{
				display : '��������',
				name : 'productName'
			},{
				display : '���ϱ��',
				name : 'productNumb'
			}],
			// ���ӱ������
			subGridOptions : {
				url : '?model=purchase_inquiry_equmentInquiry&action=pageJson',
				param : [{
							paramId : 'parentId',
							colId : 'id'
						}],
				colModel : [{
							name : 'productNumb',
							display : '���ϱ��'
						}, {
							name : 'productName',
							width : 200,
							display : '��������'
						},{
							name : 'pattem',
							display : "����ͺ�"
						},{
							name : 'units',
							display : "��λ"
						},{
							name : 'amount',
							display : "ѯ������"
						},{
							name : 'purchTypeCn',
							display : "�ɹ�����"
						}]
			},

		//��չ�Ҽ�
		menusEx:[
			{  text:'�鿴',    //��ָ���Ĳ鿴
			   icon:'view',
//			   showMenuFn:function(row){
//					if(row.state==2||row.state==3){
//						return true;
//					}
//					return false;
//				},
			   action:function(row,rows,grid){
			   		if(row){
						parent.location = "?model=purchase_inquiry_inquirysheet&action=toView&id="+ row.id+"&skey="+row['skey_'];
			   		}
			   }
			},
//				{
//				text:'ָ����Ӧ��',
//				icon:'edit',
//				showMenuFn:function(row){
//					if(row.suppId==""){
//						return true;
//					}
//					return false;
//				},
//				action:function(row,rows,grid){
//					if(row){
//						 	location = "?model=purchase_inquiry_inquirysheet&action=toAssignSupp&id="+ row.id+"&type=todiff";
//						}
//				}
//			},
				{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_inquiry&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}]
	});
});
/**�ɹ�ѯ�۵� �б�
 *2010-5-14 can
 */

var show_page = function(page) {
	$("#inquiryOrderGrid").yxsubgrid("reload");
};
$(function() {
	var idArr=$("#idArr").val();
	$("#inquiryOrderGrid").yxsubgrid({
		isTitle:true,
		title:'�ɹ�ѯ�۵�',
		isToolBar : true,
		isAddAction:false,
		isViewAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		menuWidth : 130,
		model : 'purchase_inquiry_inquirysheet',
		param:{"idArr":idArr},
		action : 'pageJson',

			// ����Ϣ
			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : 'ѯ�۵����',
				name : 'inquiryCode',
				sortable : true
			}, {
				display : '����״̬',
				name : 'ExaStatus',
//				sortable : true,
				width:60
			}, {
				display : 'ѯ�۵�״̬',
				name : 'stateName',
				sortable : true,
				width:60
			}, {
				display : '�ɹ�Ա',
				name : 'purcherName',
				sortable : true
			}, {
				display : 'ѯ������',
				name : 'inquiryBgDate',
				sortable : true
			}, {
				display : '���۽�ֹ����',
				name : 'inquiryEndDate',
				sortable : true
			}, {
				display : '��Ч����',
				name : 'effectiveDate',
				sortable : true,
				hide : true
			}, {
				display : 'ʧЧ����',
				name : 'expiryDate',
				sortable : true,
				hide : true
			}, {
				display : 'ָ����Ӧ��',
				name : 'suppName',
				sortable : true
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
			searchitems : [{
				display : 'ѯ�۵����',
				name : 'inquiryCode'
			}],

		//��չ�Ҽ�
		menusEx:[
			{  text:'�鿴',    //��ָ���Ĳ鿴
			   icon:'view',
			   showMenuFn:function(row){
					if(row.ExaStatus=="���"){
						return true;
					}
					return false;
				},
			   action:function(row,rows,grid){
			   		if(row){
						showThickboxWin("?model=purchase_inquiry_inquirysheet&action=toView&id="+ row.id+"&skey="+row['skey_']
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800");
			   		}
			   }
			},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_inquiry&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}
		]
	});
});
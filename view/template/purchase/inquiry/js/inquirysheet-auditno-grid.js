/**�������Ĳɹ�ѯ�۵� �б�
 *2010-5-14 can
 */

var show_page = function(page) {
	$("#inquirysheetyNoGrid").yxsubgrid("reload");
};
$(function() {
	$("#inquirysheetyNoGrid").yxsubgrid({
		isTitle:true,
		title:'�������Ĳɹ�ѯ�۵�',
		isToolBar : true,
		isAddAction:false,
		isViewAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:true,
		menuWidth : 130,
		model : 'purchase_inquiry_inquirysheet',
		action : 'myAuditPj',

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
				display : '����״̬',
				name : 'ExaStatus',
//				sortable : true,
				width:60
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
				sortable : true
			}, {
				display : 'ʧЧ����',
				name : 'expiryDate',
				sortable : true
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

		//��չ��ť
		buttonsEx : [{
			name : 'sumbit',
			text : '����',
			icon : 'edit',
			action : function(row, rows, grid) {
				if(row){
					parent.location = "controller/purchase/inquiry/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_purch_inquiry";
				}else{
					alert("��ѡ��һ����¼");
				}

			}
		}],

		//��չ�Ҽ�
		menusEx:[
			{  text:'�鿴',
			   icon:'view',
			   showMenuFn:function(row){
			   		if(row.state==0|row.state==1){
			   			return true;
			   		}
			   		return false;
			   },
			   action:function(row,rows,grid){
			   		if(row){
						 parent.location = "?model=purchase_inquiry_inquirysheet&action=toRead&id="+ row.id+"&skey="+row['skey_'];
			   		}
			   }
			},{
			name : 'sumbit',
			text : '����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
					parent.location = "controller/purchase/inquiry/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_purch_inquiry";
			}
		}]
	});
});
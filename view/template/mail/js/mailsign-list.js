// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".mailsignGrid").yxgrid("reload");
};
$(function() {
	$(".mailsignGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'mail_mailsign',
		//action : 'mylogpageJson',
		showcheckbox:false,
		title : "�ʼ���Ϣ",
//			 */
			isToolBar : false,
			/**
			 * ��Ĭ�Ͽ��
			 */
//			formWidth : 900,
			/**
			 * ��Ĭ�Ͽ��
			 */
//			formHeight : 550,

			/**
			 * �Ƿ���ʾ��Ӱ�ť/�˵�
			 *
			 * @type Boolean
			 */
			isAddAction: false,

			/**
			 * �Ƿ���ʾ�鿴��ť/�˵�
			 *
			 * @type Boolean
			 */
			isViewAction : false,

			/**
			 * �Ƿ���ʾ�޸İ�ť/�˵�
			 *
			 * @type Boolean
			 */
			isEditAction : false,



		menusEx : [{
				name : 'edit',
				text : "�޸�",
				icon : 'edit',
				action : function(row,rows,grid) {
							showThickboxWin("?model=mail_mailsign&action=init&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}, {
				name : 'readMailMessage',
				text : "�ʼ���Ϣ",
				icon : 'view',
				action : function(row,rows,grid) {
							showThickboxWin("?model=mail_mailinfo&action=init&perm=view&id=" + row.mailInfoId + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
				}
			}],


		// ����Ϣ
		colModel : [
				{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ʼĵ���',
					name : 'mailNo',
					sortable : true
				}, {
					display : 'ǩ������',
					name : 'signDate',
					sortable : true,
					width : '130',
					align : 'center'
				}, {
					display : '�ͻ�ǩ����',
					name : 'signMan',
					sortable : true
				}, {
					display : '��ע',
					name : 'remark',
					sortable : true,
					width : '200'
				}],
						// ��������
				searchitems : [{
					display : '�ͻ�ǩ����',
					name : 'signMan'
				}],
				// Ĭ������˳��
				sortorder : "DESC"

			});
});
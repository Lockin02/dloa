var show_page = function(page) {
	$("#lockRecord").yxgrid("reload");
};
$(function() {
			var skey_ = $('#skey').val();
			// var proIdValue = parent.document.getElementById("proId").value;
			var param = {
				"objId" : $('#id').val(),
				"stockId" : $('#stockId').val(),
				//"objCode" : $('#objCode').val(),//ͬ����ͬ����ʱ����ʽ�ţ����Բ��ܸ��ݱ��ȥ����
				"objType" : $('#objType').val()
			};
			if ($("#equId").val() != '') {
				param.objEquId = $('#equId').val();
			}
			$("#lockRecord").yxgrid({

				// �������url�����ô����url������ʹ��model��action�Զ���װ

				param : param,

				model : 'stock_lock_lock',
				action : 'lockPageJson',

				// action : 'lockPageJson&objCode=' +$('#objCode').val()
				// +'&equId' + $('#equId').val()
				// +'&stockId' + $('#stockId').val() ,

				/**
				 * �Ƿ���ʾ�鿴��ť/�˵�
				 */
				isViewAction : false,
				/**
				 * �Ƿ���ʾ�޸İ�ť/�˵�
				 */
				isEditAction : false,
				/**
				 * �Ƿ���ʾɾ����ť/�˵�
				 */
				isDelAction : false,
				/**
				 * �Ƿ���ʾ�Ҽ��˵�
				 */
				isRightMenu : false,
				// �Ƿ���ʾ��Ӱ�ť
				isAddAction : false,
				// �Ƿ���ʾ������
				isToolBar : true,
				// �Ƿ���ʾcheckbox
				showcheckbox : false,

				// ��չ��ť
				buttonsEx : [{
					name : 'return',
					text : '����',
					icon : 'delete',
					action : function(row, rows, grid) {
						location = "?model=stock_lock_lock&action=toLokStock&id="
								+ $('#id').val()
								+ "&objCode="
								+ $('#objCode').val()
								+ "&objType="
								+ $('#objType').val() + "&skey=" + skey_;

					}
				}],

				// ��
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : '��Ʒ���',
							name : 'productNo',
							sortable : true,
							width : 100

						}, {
							display : '��Ʒ����',
							name : 'productName',
							sortable : true,
							width : 150
						}, {
							display : '�����ֿ�',
							name : 'stockName',
							sortable : true,
							width : 100
						}, {
							display : '��������',
							name : 'lockType',
							process : function(v) {
								if (v == 'stockLock') {
									return "�ֿ�����";
								} else if (v == 'purchaseLock') {
									return "�ɹ�����";
								} else if (v == 'productionLock') {
									return "��������";
								} else if (v == 'instockLock') {
									return "�������";
								}else if (v == 'outstockLock') {
									return "��������";
								}else if (v == 'allocationLock') {
									return "��������";
								}else if (v == 'otherLock') {
									return "��������";
								}
							},
							sortable : true,
							width : 80
						}, {
							display : '��������',
							name : 'lockNum',
							sortable : true,
							width : 100
						}, {
							display : '������',
							name : 'updateName',
							sortable : true,
							width : 150
						}, {
							display : '����ʱ��',
							name : 'createTime',
							sortable : true,
							width : 150
						}],

				/**
				 * ��������
				 */
				searchitems : [{
							display : '��Ʒ���',
							name : 'productNo'
						}, {
							display : '��Ʒ����',
							name : 'productName'
						}],
				sortname : 'id',
				sortorder : 'DESC',
				title : '������¼'
			});
		});
var show_page = function(page) {
	$("#lockamount").yxgrid("reload");
};
$(function() {
	// var proIdValue = parent.document.getElementById("proId").value;

	$("#lockamount").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ


        param : {"objCode" : $('#objCode').val()},

		model : 'stock_lock_lock',

//		action : 'lockPageJson&objCode=' +$('#objCode').val()
//		          +'&equId' + $('#equId').val()
//		          +'&stockId' + $('#stockId').val() ,


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
             //�Ƿ���ʾ��Ӱ�ť
            isAddAction : false,
            //�Ƿ���ʾ������
            isToolBar : true,
            //�Ƿ���ʾcheckbox
	        showcheckbox : false,


	        //��չ��ť
		buttonsEx : [{
			name : 'return',
			text : '����',
			icon : 'delete',
			action : function(row, rows, grid) {
				location = "?model=contract_sales_sales&action=toLockStockByContract&id="+  $('#id').val();

			}
		}],

		// ��
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				display : '��Ʒ���',
				name : 'productNo',
				sortable : true,
				width : 150

			}, {
				display : '��Ʒ����',
				name : 'productName',
				sortable : true,
                width : 150
			}, {
				display : '�����ֿ�',
				name : 'stockName',
				sortable : true,
				width : 150
			}, {
				display : '��������',
				name : 'lockNum',
				sortable : true,
				width : 150
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
			display : '��Ʒ����',
			name : 'productName'
		}],
		sortorder : "DESC",
		title : '�鿴��ͬ��������'
	});
});
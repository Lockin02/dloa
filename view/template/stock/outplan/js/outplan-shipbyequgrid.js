var show_page = function(page) {
	$("#equGrid").yxsubgrid("reload");
};
$(function() {
	$("#equGrid").yxsubgrid({
		model : 'stock_outplan_outplan',
		action : 'equJson',
		title : '��ͬ�豸',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		// ����Ϣ
		colModel : [{
			name : 'id',
			display : 'id',
			hide : true
		}, {
			name : 'productId',
			display : '�豸Id',
			sortable : true,
			hide : true
		}, {
			name : 'productName',
			display : '�豸����',
			width : 150,
			sortable : true
		}, {
			name : 'productNo',
			display : '�豸���',
			sortable : true
		}, {
			name : 'number',
			display : '��ͬ����',
			sortable : true
		}, {
			name : 'executedNum',
			display : '��ִ������',
			sortable : true
		}, {
			name : 'onWayNum',
			display : '��;����',
			sortable : true
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=stock_outplan_outplan&action=contJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'productId',// ���ݸ���̨�Ĳ�������
				colId : 'productId'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel : [{
				name : 'tablename',
				display : '��ͬ����',
				sortable : true,
				process : function(v) {
					if (v == 'oa_sale_order') {
						return "���ۺ�ͬ";
					}else if (v == 'oa_sale_lease') {
						return "���޺�ͬ";
					}else if (v == 'oa_sale_service'){
					    return "�����ͬ";
					}else if (v == 'oa_sale_rdproject'){
					    return "�з���ͬ";
					}
				}
			}, {
				name : 'orderCode',
				width : 180,
				sortable : true,
				display : '��ʽ��ͬ��'
			}, {
				name : 'orderTempCode',
				width : 180,
				sortable : true,
				display : '��ʱ��ͬ��'
			}, {
				name : 'number',
				sortable : true,
				display : '��ͬ����'
			}, {
				name : 'onWayNum',
				sortable : true,
				display : '��;����'
			}, {
				name : 'executedNum',
				sortable : true,
				display : '��ִ������'
					// },{
					// name : 'projArraDate',
					// display : '�ƻ���������',
					// process : function(v){
					// if( v == null ){
					// return '��';
					// }else{
					// return v;
					// }
					// }
					// },{
					// name : 'exeNum',
					// display : '�������',
					// process : function(v){
					// if(v==''){
					// return 0;
					// }else
					// return v;
					// }
					}]
		},

		// menusEx : [{
		// }],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '�豸����',
			name : 'productName'
		}]
	});
});
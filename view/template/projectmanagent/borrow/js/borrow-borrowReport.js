var show_page = function(page) {
	$("#borrowReportGrid").yxsubgrid("reload");
};
$(function() {
	$("#borrowReportGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'borrowReportJson',
//		param : {
//			'limits' : '�ͻ�'
//////			'statusArr' : '0,1'
//		},
		title : '�����ñ���',
		//��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,


		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'userId',
			sortable : true,
			hide : true
		}, {
			name : 'dept',
			display : '����',
			width : 150,
			sortable : true
		}, {
			name : 'user',
			display : 'Ա��',
			width : 150,
			sortable : true
		}, {
			name : 'userId',
			display : 'Ա��Id',
			sortable : true,
			hide : true
		}, {
			name : 'allMoney',
			display : '�ܽ��ý��',
			width : 150,
			sortable : true,
			process : function(v) {
					return moneyFormat2(v);
				     }
		}, {
			name : 'moneyLimit',
			display : '���ý����',
			width : 150,
			sortable : false,
			process : function(v) {
					return moneyFormat2(v);
				     }
		}, {
			name : 'isOverrun',
			display : '�Ƿ���',
			width : 150,
			sortable : false
		}, {
			name : 'overrunMoeny',
			display : '���޽��',
			width : 150,
			sortable : false,
			process : function(v) {
					return moneyFormat2(v);
				     }
		}],
//		//��չ�Ҽ��˵�
//		menusEx : [
//			{
//				text : '123',
//				icon : 'view',
//				showMenuFn : function(row){
//					alert(row);
//				}
//			}
//		],
		/**
		 * ��������
		 */
		searchitems : [{
			display : 'Ա��',
			name : 'user'
		},{
			display : '����',
			name : 'dept'
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_borrow_borrow&action=borrowreportTable',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'createId',// ���ݸ���̨�Ĳ�������
				colId : 'userId'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel : [{
						name : 'productNo',
						width : 80,
						display : '��Ʒ����'
					},{
						name : 'productName',
						width : 150,
						display : '��������'
					},{
						name : 'productModel',
						width : 200,
						display : '����ͺ�'
					},{
						name : 'number',
						width : 40,
						display : '����'
					}, {
					    name : 'price',
					    display : '����',
						width : 70,
							process : function(v) {
									return moneyFormat2(v);
								     }
					}, {
					    name : 'money',
					    display : '�ܽ��',
						width : 70,
						process : function(v) {
							return moneyFormat2(v);
					    }
					},{
					    name : 'beginTime',
					    display : '��ʼʱ��',
						width : 80
					},{
					    name : 'endTime',
					    display : 'Ԥ�ƹ黹ʱ��',
						width : 80
					},{
					    name : 'isOvertime',
					    display : '�Ƿ�ʱ',
						width : 50
					},{
					    name : 'overtimeNum',
					    display : '��ʱ����',
						width : 60
					}, {
						name : 'renewNum',
						display : '��������',
						width : 80,
						sortable : false
					}, {
						name : 'renewDate',
						display : '�����ֹ����',
						width : 80,
						sortable : false
					}]

		},
		sortname : 'user',
        sortorder : 'ASC'

	});

});
var show_page = function(page) {
	$("#myequlistGrid").yxsubgrid("reload");
};
$(function() {
	$("#myequlistGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrowequ',
		action : 'borrowEquPageJson',
		param : {
			'borrowLimits' : '�ͻ�'
		},
		title : '�����豸�嵥',
		//��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		customCode : 'myborrowequlist',
		//����Ϣ
		colModel : [
	    {
			name : 'productId',
			display : '����id',
			sortable : true,
			hide : true
		}, {
			name : 'productNo',
			display : '���ϱ��',
			sortable : true,
			width : 150
		},{
			name : 'productName',
			display : '��������',
			sortable : true,
			width : 200
		}, {
			name : 'productModel',
			display : '�����ͺ�',
			sortable : true,
			width : 200
		}, {
		    name : 'number',
		    display : '��������',
			width : 80
		}, {
		    name : 'executedNum',
		    display : '��ִ������',
			width : 80
		}, {
		    name : 'applyBackNum',
		    display : '������黹����'
		}, {
		    name : 'backNum',
		    display : '�ѹ黹����',
			width : 80
		}],
		comboEx : [{
			text : '����״̬',
			key : 'borrowExaStatus',
			data : [{
				text : 'δ����',
				value : 'δ����'
			}, {
				text : '��������',
				value : '��������'
			}, {
				text : '���',
				value : '���'
			}]
		},{
			text : '״̬',
			key : 'borrowStatus',
			data : [{
				text : '����',
				value : '0'
			}, {
				text : '�ر�',
				value : '2'
			}, {
				text : '�˻�',
				value : '3'
			}, {
				text : '����������',
				value : '4'
			}, {
				text : 'ת��ִ�в�',
				value : '5'
			}, {
				text : 'ת��ȷ����',
				value : '6'
			}]
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_borrow_borrow&action=listPageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'ids',// ���ݸ���̨�Ĳ�������
				colId : 'borrowIdArr'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [
			{
				name : 'Code',
				display : '���õ����',
				width : 150,
				process : function(v, row) {
					return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_borrow_borrow&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
				}
			},
			{
			    name : 'Type',
			    display : '����',
				width : 80
			},
			{
			    name : 'customerName',
			    display : '�ͻ�����',
				width : 200
			},
			{
			    name : 'beginTime',
			    display : '��ʼ����',
				width : 80
			},
			{
			    name : 'closeTime',
			    display : '��ֹ����',
				width : 80
			}]
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���ϱ��',
			name : 'productNo'
		},{
			display : '��������',
			name : 'productName'
		},{
			display : '���к�',
			name : 'serialName2'
		}]
	});

});
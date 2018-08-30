var show_page = function(page) {
	$("#borrowGrid").yxsubgrid("reload");
};
$(function() {
	 if($("#ids").val() != ''){
	    var ids = $("#ids").val();
	 }else{
	    var ids = '0';
	 }

	$("#borrowGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'pageJsonWithChance',
		param : {
			"ids" : ids
		},
		title : '������',
		// ��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isToolBar : false, // �Ƿ���ʾ������
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'chanceId',
					display : '�̻�Id',
					sortable : true,
					hide : true
				}, {
					name : 'Code',
					display : '���',
					sortable : true
				}, {
					name : 'Type',
					display : '����',
					sortable : true,
					hide : true
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true
				}, {
					name : 'salesName',
					display : '���۸�����',
					sortable : true
				}, {
					name : 'beginTime',
					display : '��ʼ����',
					width : 80,
					sortable : true
				}, {
					name : 'closeTime',
					display : '��ֹ����',
					width : 80,
					sortable : true
				}, {
					name : 'scienceName',
					display : '����������',
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true,
					width : 80
				},{
					name : 'DeliveryStatus',
					display : '����״̬',
					sortable : true,
					width : 80,
					process : function(v){
		  				if( v == 'WFH'){
		  					return "δ����";
		  				}else if(v == 'YFH'){
		  					return "�ѷ���";
		  				}else if(v == 'BFFH'){
			                return "���ַ���";
			            }else if(v == 'TZFH'){
			                return "ֹͣ����";
			            }
		  			}
				}, {
					name : 'ExaDT',
					display : '����ʱ��',
					sortable : true,
					hide :true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true
				}, {
					name : 'objCode',
					display : 'ҵ����',
					width : 120
				}, {
					name : 'chanceCode',
					display : '�̻����',
					width : 120,
					process : function(v, row) {
						return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
							+ row.chanceId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1,'+ row.chanceId +')">'
							+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
					}
				},{
					name : 'chanceStatus',
					display : '�̻�״̬',
					sortable : true,
					width : 80,
					process : function(v){
						if (v == 0) {
							return "������";
						} else if (v == 3) {
							return "�ر�";
						} else if (v == 4) {
							return "�����ɺ�ͬ";
						} else if (v == 5) {
							return "������"
						} else if (v == 6) {
							return "��ͣ"
						}
		  			}
				}],
				comboEx : [{
					text : '����״̬',
					key : 'ExaStatus',
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
					text : '����״̬',
					key : 'DeliveryStatus',
					data : [{
						text : 'δ����',
						value : '0'
					}, {
						text : '�ѷ���',
						value : '1'
					}, {
						text : '���ַ���',
						value : '2'
					}]
				}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_borrow_borrowequ&action=listPageJson&isTemp=0',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'borrowId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel : [{
						name : 'productNo',
						width : 200,
						display : '��Ʒ���',
						process : function(v,row){
							return v+"&nbsp;&nbsp;K3:"+row['productNoKS'];
						}
					},{
						name : 'productName',
						width : 200,
						display : '��Ʒ����',
						process : function(v,row){
							return v+"&nbsp;&nbsp;K3:"+row['productNameKS'];
						}
					}, {
					    name : 'number',
					    display : '��������',
						width : 80
					}, {
					    name : 'executedNum',
					    display : '��ִ������',
						width : 80
					}, {
					    name : 'backNum',
					    display : '�ѹ黹����',
						width : 80
					}]
		},
		/**
		 * ��������
		 */
		searchitems : [{
					display : '���',
					name : 'Code'
				}, {
					display : '�ͻ�����',
					name : 'customerName'
				}, {
					display : 'ҵ����',
					name : 'objCode'
				},{
				    display : '���۸�����',
				    name : 'salesName'
				},{
				    display : '������',
				    name : 'createNmae'
				},{
					display : '��������',
					name : 'createTime'
				},{
				    display : 'K3��������',
				    name : 'productNameKS'
				},{
				    display : 'ϵͳ��������',
				    name : 'productName'
				},{
				    display : 'K3���ϱ���',
				    name : 'productNoKS'
				},{
				    display : 'ϵͳ���ϱ���',
				    name : 'productNo'
				}],
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=toViewTab&id="
							+ row.id + "&skey=" + row['skey_']);

				}
			}

		}]

	});

});
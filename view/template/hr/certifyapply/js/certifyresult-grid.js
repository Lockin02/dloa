var show_page = function(page) {
	$("#certifyresultGrid").yxgrid("reload");
};
$(function() {
	$("#certifyresultGrid").yxgrid({
		model : 'hr_certifyapply_certifyresult',
		title : '��ְ�ʸ���˱�',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//����Ϣ
		colModel : [{
					display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'periodName',
				display : '��������',
				sortable : true,
				hide : true
			}, {
				name : 'careerDirection',
				display : 'ְҵ��չͨ��',
				sortable : true,
				hide : true
			}, {
				name : 'careerDirectionName',
				display : 'ͨ������',
				sortable : true
			}, {
				name : 'formDate',
				display : '�����',
				sortable : true
			}, {
				name : 'formUserId',
				display : '�����',
				sortable : true,
				hide : true
			}, {
				name : 'formUserName',
				display : '�����',
				sortable : true
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				sortable : true
			}, {
				name : 'ExaDT',
				display : '��������',
				sortable : true
			}, {
				name : 'status',
				display : '����״̬',
				sortable : true,
				process : function(v){
					switch(v){
						case '0' : return '����';break;
						case '1' : return '������';break;
						case '2' : return '���';break;
						default : return v;
					}
				}
			}, {
				name : 'createId',
				display : '������Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '������',
				sortable : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				width : 130
			}, {
				name : 'updateId',
				display : '�޸���Id',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '�޸���',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				hide : true
			}],
		toViewConfig : {
			action : 'toView',
			showMenuFn : function(row) {
				return true;
			},
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					//�ж�
					showModalWin("?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
        //��������
		comboEx:[{
		     text:'����״̬',
		     key:'ExaStatus',
		     type : 'workFlow'
	   }],
		searchitems : [{
			display : "ְҵ��չͨ��",
			name : 'careerDirectionNameSearch'
		}]
	});
});
var show_page = function(page) {
	$("#cassessGrid").yxgrid("reload");
};
$(function() {
	$("#cassessGrid").yxgrid({
		model : 'hr_certifyapply_cassess',
		title : '�ҵ���ְ�ʸ�ȼ���֤���۱�',
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
				name : 'modelName',
				display : 'ƥ��ģ������',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : 'Ա�����',
				sortable : true,
				hide : true
			}, {
				name : 'userAccount',
				display : 'Ա���˺�',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : 'Ա������',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'deptName',
				display : '��������',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'jobName',
				display : 'ְλ����',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'nowDirectionName',
				display : '��ǰͨ��',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'nowLevelName',
				display : '��ǰ����',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'nowGradeName',
				display : '��ǰ����',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'careerDirectionName',
				display : '����ͨ��',
				sortable : true,
				width : 80
			}, {
				name : 'baseLevelName',
				display : '���뼶��',
				sortable : true,
				width : 70
			}, {
				name : 'baseGradeName',
				display : '���뼶��',
				sortable : true,
				width : 70
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				width : 80,
				process : function(v){
					switch(v){
						case '0' : return '����';break;
						case '1' : return '��֤׼����';break;
						case '2' : return '������';break;
						case '3' : return '��ɴ�����';break;
						case '4' : return '���������';break;
						case '5' : return 'ȷ�������';break;
						case '6' : return '��������';break;
						default : return v;
					}
				}
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				sortable : true,
				width : 80
			}, {
				name : 'ExaDT',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'managerName',
				display : '������ί',
				sortable : true,
				width : 80
			}, {
				name : 'memberName',
				display : '������ί',
				sortable : true,
				width : 150
			}, {
				name : 'scoreAll',
				display : '����',
				sortable : true,
				width : 70
			}, {
				name : 'createName',
				display : '������',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				width : 120
			}, {
				name : 'updateName',
				display : '�޸�������',
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
		menusEx : [{
			name : 'edit',
			text : "�ύ��֤����",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				//�ж�
				showModalWin("?model=hr_certifyapply_cassess&action=toEditDetail&id=" + row.id + "&skey=" + row.skey);
			}
		},{
			name : 'audit',
			text : "�ύ����",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '1') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin('controller/hr/certifyapply/ewf_index.php?actTo=ewfSelect&billId='
					+ row.id + '&billDept=' + row.deptId
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
			}
		}],
		searchitems : [{
			display : "Ա������",
			name : 'userNameSearch'
		}]
	});
});
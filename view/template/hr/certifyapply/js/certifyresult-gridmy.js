var show_page = function(page) {
	$("#certifyresultGrid").yxgrid("reload");
};
$(function() {
	$("#certifyresultGrid").yxgrid({
		model : 'hr_certifyapply_certifyresult',
		action : 'myPageJson',
		title : '�ҵ���ְ�ʸ���˱�',
		isAddAction : false,
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
		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				var c = p.toEditConfig;
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
			name : 'audit',
			text : "�ύ����",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin('controller/hr/certifyapply/ewf_certifyresult.php?actTo=ewfSelect&billId='
					+ row.id + '&billDept=' + row.deptId
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
			}
		},{
			name : 'delete',
			text : "ɾ��",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_certifyapply_certifyresult&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page(1);
							}else{
								alert("ɾ��ʧ��! ");
							}
						}
					});
				}
			}
		}],
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
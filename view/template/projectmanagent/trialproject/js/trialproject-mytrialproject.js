var show_page = function(page) {
	$("#myprojectGrid").yxgrid("reload");
};
$(function() {
	$("#myprojectGrid").yxgrid({
		model : 'projectmanagent_trialproject_trialproject',
		action : 'mypagejson',
		title : '������Ŀ',
		param : {'applyNameId' : $("#userId").val()},
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'mytrialprojectGrid',
//		buttonsEx : [{
//			name : 'Add',
//			// hide : true,
//			text : "����",
//			icon : 'add',
//			action : function(row) {
//				showModalWin('?model=projectmanagent_trialproject_trialproject&action=toAdd');
//			}
//		}],
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'projectCode',
			display : '��Ŀ���',
			sortable : true
		}, {
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true
		},{
		    name : 'serCon',
		    display : '�ύ״̬',
		    sortable : true,
		    process : function(v,row){
		       if(row.id == "noId"){
					return '';
				}
				switch(v){
					case '0' : return 'δ�ύ';break;
					case '1' : return '���ύ';break;
					case '2' : return '���';break;
					case '3' : return '��������';break;
					case '4' : return '����������';break;
					case '5' : return '������������';break;
					default : return v;
				}
		    }
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
		}, {
			name : 'status',
			display : '��Ŀ״̬',
			sortable : true,
			process : function(v,row){
				if(row.id == "noId"){
					return '';
				}
				switch(v){
					case '0' :
					  if (row.serCon == '1'){
					     return '�ɱ�ȷ����';break;
					  }else{
					     return 'δ�ύ';break;
					  }
					case '1' : return '������';break;
					case '2' : return '��ִ��';break;
					case '3' : return 'ִ����';break;
					case '4' : return '�����';break;
					case '5' : return '�ѹر�';break;
					default : return v;
				}
			}
		}, {
			name : 'beginDate',
			display : '���ÿ�ʼʱ��',
			sortable : true
		}, {
			name : 'closeDate',
			display : '���ý���ʱ��',
			sortable : true
		}, {
			name : 'budgetMoney',
			display : 'Ԥ�ƽ��',
			sortable : true,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'applyName',
			display : '������',
			sortable : true
		}, {
			name : 'applyNameId',
			display : '������ID',
			sortable : true,
			hide : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'customerType',
			display : '�ͻ�����Type',
			sortable : true,
			hide : true
		}, {
			name : 'customerTypeName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'customerWay',
			display : '�ͻ���ϵ��ʽ',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'province',
			display : 'ʡ��',
			sortable : true,
			hide : true
		}, {
			name : 'city',
			display : '����',
			sortable : true,
			hide : true
		}, {
			name : 'areaName',
			display : '��������',
			sortable : true
		}, {
			name : 'areaPrincipal',
			display : '��������',
			sortable : true
		}, {
			name : 'areaPrincipalId',
			display : '��������Id',
			sortable : true,
			hide : true
		}, {
			name : 'areaCode',
			display : '�����ţ�ID��',
			sortable : true,
			hide : true
		}, {
			name : 'projectDescribe',
			display : '����Ҫ������',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'updateTime',
			display : '�޸�ʱ��',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '�޸�������',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '�޸���Id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '����������',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '������ID',
			sortable : true,
			hide : true
		}, {
			name : 'isFail',
			display : '�Ƿ���Ч',
			sortable : true,
			process : function(v,row){
				switch(v){
					case '0' : return '��Ч'; break;
					case '1' : return '��ת��ͬ'; break;
					case '2' : return '�ֹ��ر�'; break;
					default : return v;
				}
			}
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 180,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
						+ row.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>"
						+ v
						+ "</font>"
						+ '</a>';
			}
		}, {
			name : 'contractId',
			display : '��ͬid',
			sortable : true,
			hide : true
		}],
        // ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=projectmanagent_trialproject_trialproject&action=viewTab&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row) {
				if ((row.serCon == '0' || row.serCon == '2') && row.isFail == '0' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=projectmanagent_trialproject_trialproject&action=init&id='
						+ row.id
						+ '&perm=edit'
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '�ύ',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.serCon == '0' || row.serCon == '2') && row.isFail == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
			  if(row.expand == '1'){
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_trialproject_trialproject&action=ajaxUpdateSer",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								showThickboxWin('controller/projectmanagent/trialproject/ewf_index1.php?actTo=ewfSelect&billId='
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
								$("#myprojectGrid").yxgrid("reload");
							}
						}
					});
				}
			  }else{
			    if (window.confirm(("ȷ��Ҫ�ύ?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_trialproject_trialproject&action=subConproject",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�ύ�ɹ���');
								$("#myprojectGrid").yxgrid("reload");
							}
						}
					});
				}
			  }
			}

		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (( row.serCon == '0' || row.serCon == '2') && row.isFail == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_trialproject_trialproject&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#myprojectGrid").yxgrid("reload");
							}
						}
					});
				}
			}

		}, {
			text : '��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && (row.serCon == '1' || row.serCon == '4') && row.isFail == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=projectmanagent_trialproject_extension&action=toAdd&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '�ر�',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && (row.serCon == '1' || row.serCon == '4') && row.isFail == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫ�ر�?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_trialproject_trialproject&action=ajaxCloseTr",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�رճɹ���');
								$("#myprojectGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}],
		comboEx : [
			{
				text : '����״̬',
				key : 'ExaStatus',
				data : [{
					text : 'δ����',
					value : 'δ����'
				}, {
					text : '��������',
					value : '��������'
				},{
					text : '���',
					value : '���'
				}, {
					text : '���',
					value : '���'
				}]
			}
		],
		/**
		 * ��������
		 */
		searchitems : [{
					display : '��Ŀ���',
					name : 'projectCode'
				}, {
					display : '��Ŀ����',
					name : 'projectName'
				}, {
					display : '�ͻ�����',
					name : 'customerName'
				}]
	});
});
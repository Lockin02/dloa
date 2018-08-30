var show_page = function(page) {
	$("#trialprojectGrid").yxgrid("reload");
};
$(function() {
	$("#trialprojectGrid").yxgrid({
		model : 'projectmanagent_trialproject_trialproject',
		param : {'chanceId' : $("#chanceId").val()},
		title : '������Ŀ',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'trialprojectGrid',
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
			name : 'affirmMoney',
			display : 'ȷ��Ԥ����',
			sortable : true,
			process : function(v){
				return moneyFormat2(v);
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
			name : 'applyName',
			display : '������',
			sortable : true
		}, {
			name : 'projectProcess',
			display : '��Ŀ����',
			sortable : true,
			process : function(v){
				return moneyFormat2(v) + ' %';
			}
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
		}],
        // ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=projectmanagent_trialproject_trialproject&action=init&perm=view&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row) {

				showThickboxWin('controller/projectmanagent/trialproject/readview.php?itemtype=oa_trialproject_trialproject&pid='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
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
							}, {
								text : '���',
								value : '���'
							}]
				},{
					text : 'ȷ��״̬',
					key : 'ExaStatusArr',
					data : [{
								text : 'δȷ��',
								value : 'δ����'
							}, {
								text : '��ȷ��',
								value : '��������,���,���'
							}]
				}],
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
				}, {
					display : '������',
					name : 'applyName'
				}]
	});
});
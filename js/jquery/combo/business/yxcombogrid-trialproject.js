/**
 * ���ϻ�����Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_trialproject', {
		options : {
			hiddenId : 'id',
			nameCol : 'projectCode',
			gridOptions : {
				showcheckbox : false,
				model : 'projectmanagent_trialproject_trialproject',
				action : 'pageJsonCombogrid',
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
					case '0' : return 'δ�ύ';break;
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
		}],
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
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
/** ���޺�ͬ�б�* */
var show_page = function(page) {
	$("#rdprojectGrid").yxgrid("reload");
};

$(function() {
	$("#rdprojectGrid").yxgrid({
		model : 'rdproject_yxrdproject_rdproject',
		param : {'ExaStatus' : '��������'},
		action : 'pageJsonNo',
		title : 'δ�����з���ͬ',
		isToolBar : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : 'task',
			name : 'task',
			sortable : true,
			hide : true
		},{
			display : '��ͬid',
			name : 'contractId',
			sortable : true,
			hide : true
		},{
			name : 'orderCode',
			display : '������ͬ��',
  			sortable : true
		},{
    		name : 'orderTempCode',
  			display : '��ʱ��ͬ��',
  			sortable : true
        },{
			name : 'orderName',
  			display : '��ͬ����',
  			sortable : true
        },{
    		name : 'orderPrincipal',
  		    display : '���۸�����',
  			sortable : true
        },{
    		name : 'district',
  			display : '��������',
  			sortable : true
        },{
    		name : 'saleman',
  			display : '����Ա',
  			sortable : true
        },{
			display : '����ʱ��',
			name : 'createTime',
			width : 150
		}, {
			display : '������',
			name : 'createName'
		}, {
			display : '����״̬',
			name : 'ExaStatus'
		}],

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=rdproject_yxrdproject_rdproject&action=init&perm=view&id='
							+ row.contractId + "&skey="+row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");

				}

			}
		},{
			text : '����',
			icon : 'edit',
			action: function(row){
                location = 'controller/rdproject/yxrdproject/ewf_index.php?taskId='+
                	row.task +
                	'&spid=' +
                	row.id +
                	'&billId=' +
                	row.contractId +  '&actTo=ewfExam' + "&skey="+row['skey_'];
			}
		}],

		searchitems : [{
			display : '���뵥��',
			name : 'applyNo'
		}],
		sortname : 'id',
		sortorder : 'DESC'
	});
});
/** ���޺�ͬ�б�* */
var show_page = function(page) {
	$("#rdprojectGrid").yxgrid("reload");
};

$(function() {
	$("#rdprojectGrid").yxgrid({
		model : 'rdproject_yxrdproject_rdproject',
		param : {'ExaStatuss' : '���,���'},
		action : 'pageJsonYes',
		title : '�������з���ͬ',
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
			text : '�������',
			icon : 'view',
			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_sale_rdproject&pid='
						+ row.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
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
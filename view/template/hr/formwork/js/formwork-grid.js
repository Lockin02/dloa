var show_page = function(page) {
	$("#formworkGrid").yxgrid("reload");
};
$(function() {
		//��ͷ��ť����
	buttonsArr = [{
			name : 'add',
			text : "����",
			icon : 'add',
			action : function(row) {
				showModalWin("?model=hr_formwork_formwork&action=toAdd"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
			}
		}];
	$("#formworkGrid").yxgrid({
		model : 'hr_formwork_formwork',
		title : '����ģ������',
	    isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
					showModalWin('?model=hr_formwork_formwork&action=toView&id='
							+ row.id + "&skey=" + row['skey_']);
			}
		},{
			text : '�༭ģ��',
			icon : 'edit',
			action : function(row) {
					showModalWin('?model=hr_formwork_formwork&action=toEdit&id='
							+ row.id + "&skey=" + row['skey_']);
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_formwork_formwork&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#formworkGrid").yxgrid("reload");
							}
						}
					});
				}
			}

		}],
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formworkName',
			display : 'ģ������',
			sortable : true,
			process : function(v, row) {
					return '<a href="javascript:void(0)" title="����鿴����" onclick="javascript:showModalWin(\'?model=hr_formwork_formwork&action=toView&id='
								+ row.id
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
				}
		}, {
			name : 'isUse',
			display : '�Ƿ�����',
			sortable : true,
			process : function(v,row){
			   if(v == '0'){
			      return "����";
			   }else if(v == '1'){
			      return "ͣ��";
			   }
			}
		}],
        buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "�����ֶ�",
			name : 'XXX'
		}]
	});
});
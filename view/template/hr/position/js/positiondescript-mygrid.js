var show_page = function(page) {
	$("#positiondescriptGrid").yxgrid("reload");
};
$(function() {
	$("#positiondescriptGrid").yxgrid({
		model : 'hr_position_positiondescript',
		action : 'myPageJson',
		title : '�ҵ�ְλ˵����',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'positionName',
			display : 'ְλ����',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_position_positiondescript&action=toView&id="+row.id+"\")'>"+v+"</a>";
			}
		},/* {
			display : '����״̬',
			name : 'ExaStatus',
			sortable : true
		},*/ {
			name : 'deptName',
			display : '���ڲ���',
			sortable : true
		}, {
			name : 'rewardGrade',
			display : 'н�ʵȼ�',
			sortable : true
		}, {
			name : 'superiorPosition',
			display : '�ϼ�ְλ',
			sortable : true
		}, {
			name : 'suborPosition',
			display : '����ְλ',
			sortable : true
		}, {
			name : 'parallelPosition',
			display : 'ƽ��ְλ',
			sortable : true
		}, {
			display : '������',
			name : 'createName',
			sortable : true
		}],
		toAddConfig : {
			toAddFn : function() {
				showOpenWin("?model=hr_position_positiondescript&action=toAdd" );
			}
		},
		
		toEditConfig : {
			toEditFn : function(p,g) {
				if (g) {
					showOpenWin("?model=hr_position_positiondescript&action=toEdit&id=" + g.getCheckedRowIds());
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p,g) {
				if (g) {
					showOpenWin("?model=hr_position_positiondescript&action=toView&id=" + g.getCheckedRowIds());
				}
			}
		},/*
		menusEx : [{
			text : '�ύ����',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.ExaStatus == 'δ���'){
					return true;
				}else
					return false;
			},
			action : function(row, rows, grid) {
					parent.location = "controller/hr/position/ewf_index.php?actTo=ewfSelect&billId="+row.id+"&examCode=oa_hr_position_description&formName=ְλ˵����";
			}

		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_position_NULL&action=pageItemJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'XXX',
				display : '�ӱ��ֶ�'
			}]
		},
		comboEx : [{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
						text : '��������',
						value : '��������'
					}, {
						text : '���',
						value : '���'
					}, {
						text : 'δ���',
						value : 'δ����'
					}, {
						text : '���',
						value : '���'
					}]
		}],*/
		searchitems : [{
			display : "ְλ����",
			name : 'positionName'
		}, {
			display : "���ڲ���",
			name : 'deptName'
		}, {
			display : "н�ʵȼ�",
			name : 'rewardGrade'
		}]
	});
}); 
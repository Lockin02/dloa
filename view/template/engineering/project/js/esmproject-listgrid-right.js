$(function() {
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJson',
		title : '��Ŀ�ֲ�  ' + $('#provinceName').val() ,
//		param : {'provinceId' : $('#provinceId').val()},
		param : {'provinceName' : $('#provinceName').val(),'provinceId' : $('#provinceId').val()},
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "��ʼ��",
			icon : 'edit',
			/**
			 * row ���һ��ѡ�е��� rows ѡ�е��У���ѡ�� rowIds
			 * ѡ�е���id���� grid ��ǰ���ʵ��
			 */
			action : function(row) {
				location='?model=engineering_project_esmproject&action=rightList'
			}
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=readTab"
							+ "&id=" + row.id);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}]
	});

});
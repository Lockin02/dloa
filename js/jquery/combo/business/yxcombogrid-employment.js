/**
 * �����з���Ŀ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_employment', {
		isDown : false,
		setValue : function(rowData) {
			if (rowData) {
				var t = this, p = t.options, el = t.el;
				p.rowData = rowData;
				if (p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData.idArr;
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData.text;
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				} else if (!p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData[p.valueCol];
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData[p.nameCol];
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				}
			}
		},
		options : {
			hiddenId : 'id',
			nameCol : 'employmentCode',
			openPageOptions : {
				url : '?model=hr_recruitment_employment&action=selectEmployment',
				width : '750'
			},
			closeCheck : false,// �ر�״̬,����ѡ��
			gridOptions : {
				showcheckbox : false,
				model : 'hr_recruitment_employment',
				//����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'employmentCode',
					display : '���ݱ��',
					sortable : true,
					width : 150,
					process : function(v, row) {
							return '<a href="javascript:void(0)" title="����鿴" onclick="javascript:showModalWin(\'?model=hr_recruitment_employment&action=toView&id='
										+ row.id
										+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
										+ "<font color = '#4169E1'>"
										+ v
										+ "</font>"
										+ '</a>';
						}
				}, {
					name : 'name',
					display : '����',
					sortable : true
				}, {
					name : 'sex',
					display : '�Ա�',
					sortable : true
				}, {
					name : 'nation',
					display : '����',
					sortable : true
				}, {
					name : 'age',
					display : '����',
					sortable : true
				}, {
					name : 'highEducationName',
					display : 'ѧ��',
					sortable : true
				}, {
					name : 'highSchool',
					display : '��ҵѧУ',
					sortable : true
				}, {
					name : 'professionalName',
					display : 'רҵ',
					sortable : true
				}, {
					name : 'telephone',
					display : '�̶��绰',
					sortable : true
				}, {
					name : 'mobile',
					display : '�ƶ��绰',
					sortable : true
				}, {
					name : 'personEmail',
					display : '���˵�������',
					sortable : true
				}, {
					name : 'QQ',
					display : 'QQ',
					sortable : true
				}],
				// ��������
				searchitems : [{
						display : '���ݱ��',
						name : 'employmentCode'
					},{
						display : '����',
						name : 'name'
					}
				],
				// Ĭ�������ֶ���
				sortname : "name",
				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);
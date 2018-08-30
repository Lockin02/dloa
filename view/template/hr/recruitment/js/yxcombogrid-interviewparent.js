/**
 * ������Ա���������
 */
 (function($) {
 	$.woo.yxcombogrid.subclass('woo.yxcombogrid_interviewparent', {
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
 			nameCol : 'formCode',
 			searchName : 'formCode',
 			openPageOptions : {
 				url : '?model=hr_recruitment_apply&action=selectApply',
 				width : '1000'
 			},
			closeCheck : false,// �ر�״̬,����ѡ��
			closeAndStockCheck:false,//�ر���У����
			gridOptions : {
				showcheckbox : true,
				model : 'hr_recruitment_apply',
				//����Ϣ
				colModel : [{
					name : 'formCode',
					display : '���ݱ��',
					width:130,
					sortable : true,
					process : function(v, row) {
						return '<a href="javascript:void(0)" title="����鿴" onclick="javascript:showModalWin(\'?model=hr_recruitment_apply&action=toView&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
					}
				},{
					name : 'deptName',
					display : '������',
					width:100,
					sortable : true
				},{
					name : 'positionName',
					display : '����ְλ'
				},{
					name : 'addType',
					display : '��Ա����'
				},{
					name : 'workPlace',
					display : '�����ص�'
				},{
					name : 'resumeToName',
					display : '�ӿ���'
				},{
					name : 'positionNote',
					display : 'ְλ��ע',
					width : 150,
					process : function(v,row){
						var tmp = '';
						if (row.developPositionName) {
							tmp += row.developPositionName + '��';
						}
						if (row.network) {
							tmp += row.network + '��';
						}
						if (row.device) {
							tmp += row.device;
						}
						return tmp;
					}
				}],

				// ��������
				searchitems : [{
					display : '���ݱ��',
					name : 'formCode'
				}],

				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
/**
 * ����������Ŀ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_interview', {
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
					searchName : 'employmentCode',
					openPageOptions : {
										url : '?model=hr_recruitment_employment&action=selectEmp',
										width : '500'
									},
					closeCheck : false,// �ر�״̬,����ѡ��
					closeAndStockCheck:false,//�ر���У����
					gridOptions : {
						showcheckbox : true,
						model : 'hr_recruitment_employment',
						//����Ϣ
						colModel : [{
		            					name : 'name',
		              					display : '����',
		              					width:60,
		              					sortable : true
		                          },{
		            					name : 'employmentCode',
		              					display : '���ݱ��',
		              					width:120,
		              					sortable : true,
										process : function(v, row) {
											return '<a href="javascript:void(0)" title="����鿴" onclick="javascript:showModalWin(\'?model=hr_recruitment_employment&action=toView&id='
														+ row.id
														+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
														+ "<font color = '#4169E1'>"
														+ v
														+ "</font>"
														+ '</a>';
										}
		                          },{
		            					name : 'sex',
		              					display : '�Ա�',
		              					width:50,
		              					sortable : true
		                          },{
		                				name : 'mobile',
		              					display : '�绰'
		                          }],
						// ��������
						searchitems : [{
									display : '����',
									name : 'name'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);
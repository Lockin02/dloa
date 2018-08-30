/**
 * ������Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_resume', {
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
			nameCol : 'applicantName',
			closeCheck : false,// �ر�״̬,����ѡ��
			gridOptions : {

				showcheckbox : false,
				model : 'hr_recruitment_resume',
				action : 'pageJson',
				bodyAlign:'center',
				param : {
	 				"resumeTypeArr" : "0,3,4,5,6"
				},
				pageSize : 10,
				// ����Ϣ
			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'resumeCode',
				display : '�������',
				sortable : true,
				process : function(v, row) {
						return '<a href="javascript:void(0)" title="����鿴����" onclick="javascript:showModalWin(\'?model=hr_recruitment_resume&action=toView&id='
									+ row.id
									+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
									+ "<font color = '#4169E1'>"
									+ v
									+ "</font>"
									+ '</a>';
					}
			}, {
				name : 'applicantName',
				display : 'ӦƸ������',
				width:70,
				sortable : true
			}, {
				name : 'isInform',
				display : '����֪ͨ',
				sortable : true,
				process : function(v,row){
				    if(v=="0"){
				       return "δ֪ͨ����";
				    }else if(v=="1"){
				       return "��֪ͨ����";
				    }
				}
			}, {
				name : 'post',
				display : 'ӦƸְλ',
				sortable : true,
				datacode : 'YPZW'
			}, {
				name : 'phone',
				display : '��ϵ�绰',
				sortable : true
			}, {
				name : 'email',
				display : '��������',
				sortable : true,
				width : 200
			}, {
				name : 'resumeType',
				display : '��������',
				sortable : true,
				process : function(v,row){
				    if(v=="0"){
				       return "��˾����";
				    }else if(v=="1"){
				       return "��ְ����";
				    }else if(v=="2"){
				       return "������";
				    }else if(v=="3"){
				       return "��������";
				    }else if(v=="4"){
				       return "��̭����";
				    }else if(v=="5"){
				       return "��ְ����";
				    }else if(v=="6"){
				       return "��ְ����";
				    }
				}
			}],
						// ��������
						searchitems : [{
									display : 'ӦƸ������',
									name : 'applicantName'
								}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
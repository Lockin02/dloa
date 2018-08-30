/**
 * ���ϻ�����Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_chance', {
		options : {
			hiddenId : 'id',
			nameCol : 'chanceName',
			gridOptions : {
				isTitle : true,
				showcheckbox : false,
				model : 'projectmanagent_chance_chance',
				action : 'pageJson',
				//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'chanceCode',
			display : '�̻����',
			sortable : true
		}, {
			name : 'chanceName',
			display : '�̻�����',
			sortable : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'trackman',
			display : '������',
			sortable : true
		}, {
			name : 'customerProvince',
			display : '�ͻ�����ʡ��',
			sortable : true
		}, {
			name : 'customerType',
			display : '�ͻ�����',
			datacode : 'KHLX',
			sortable : true
		}, {
			name : 'customerTypeName',
			display : '�ͻ���������',
			sortable : true,
			hide : true
		}, {
			name : 'status',
			display : '�̻�״̬',
			process : function(v) {
				if (v == 0) {
					return "������";
				}else if(v == 3){
					return "�ر�";
				}else if(v == 4){
					return "�����ɺ�ͬ";
				}else if(v == 5){
				    return "������"
				}else if(v == 6){
				    return "��ͣ"
				}
//				return "�ɽ���״̬";

			},
			sortable : true
		},{
			name : 'chanceType',
			display : '�̻�����',
            process : function(v) {
				if (v == "SJLX-XSXS") {
					return "������Ŀ";
				}else if(v == "SJLX-FWXM"){
					return "������Ŀ";
				}else if(v == "SJLX-ZL"){
					return "������Ŀ";
				}else if(v == "SJLX-YF"){
				    return "�з���Ŀ"
				}

			},
			sortable : true
		},{
		   name : 'chanceLevel',
		   display : '�̻��ȼ�',
		   datacode : 'SJDJ',
		   sortable : true
		}],
		comboEx : [ {
			text : '�̻�����',
			key : 'chanceType',
			data : [ {
				text : '������Ŀ',
				value : 'SJLX-XSXS'
			},{
				text : '������Ŀ',
				value : 'SJLX-FWXM'
			}, {
				text : '������Ŀ',
				value : 'SJLX-ZL'
			},{
				text : '�з���Ŀ',
				value : 'SJLX-YF'
			}  ]
		},
		   {
			text : '�̻�״̬',
			key : 'status',
			data : [ {
				text : '������',
				value : '0,5'
			},{
				text : '��ͣ',
				value : '6'
			}, {
				text : '�ر�',
				value : '3'
			},{
				text : '�����ɺ�ͬ',
				value : '4'
			}  ]
		}
		],
				// ��������
				searchitems : [{
							display : '�̻����',
							name : 'chanceCode'
						},{
							display : '�̻�����',
							name : 'chanceName'
						}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
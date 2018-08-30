var show_page = function (page) {
	$("#certifytitleGrid").yxgrid("reload");
};
$(function () {
	$("#certifytitleGrid").yxgrid({
		model : 'hr_baseinfo_certifytitle',
		title : '��ְ�ʸ��ν��',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'titleName',
				display : 'ͷ������',
				sortable : true
			}, {
				name : 'careerDirectionName',
				display : 'ְҵ��չͨ��',
				sortable : true
			}, {
				name : 'baseLevelName',
				display : '���뼶��',
				sortable : true
			}, {
				name : 'baseGradeName',
				display : '���뼶��',
				sortable : true
			}, {
				name : 'remark',
				display : '��ע',
				sortable : true
			}, {
				name : 'statusCN',
				display : '״̬',
				sortable : true
			}
		],
		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_baseinfo_NULL&action=pageItemJson',
			param : [{
					paramId : 'mainId',
					colId : 'id'
				}
			],
			colModel : [{
					name : 'XXX',
					display : '�ӱ��ֶ�'
				}
			]
		},
		
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		comboEx : [{
			text : '״̬',
			key : 'status',
			data : [ {
						text : '�ر�',
						value : '0'
					}, {
						text : '����',
						value : '1'
					}]
		}],
		searchitems : [{
				display : "ͷ������",
				name : 'titleName'
			}, {
				display : "���뼶��",
				name : 'baseGradeName'
			}, {
				display : "���뼶��",
				name : 'baseLevelName'
			}, {
				display : "ְҵ��չͨ��",
				name : 'careerDirectionName'
			}
		]
	});
});
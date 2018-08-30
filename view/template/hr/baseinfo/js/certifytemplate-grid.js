var show_page = function(page) {
	$("#certifytemplateGrid").yxgrid("reload");
};
$(function() {
	$("#certifytemplateGrid").yxgrid({
		model : 'hr_baseinfo_certifytemplate',
		title : '��ְ�ʸ�ȼ���֤���۱�ģ��',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'modelName',
				display : 'ģ������',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_baseinfo_certifytemplate&action=toView&id=" + row.id + "\")'>" + v + "</a>";
				}
			}, {
				name : 'careerDirection',
				display : 'ְҵ��չͨ��',
				sortable : true,
				hide : true
			}, {
				name : 'careerDirectionName',
				display : 'ְҵ��չͨ��',
				sortable : true,
				width : 80
			}, {
				name : 'baseLevel',
				display : '���뼶��',
				sortable : true,
				hide : true
			}, {
				name : 'baseLevelName',
				display : '���뼶��',
				sortable : true,
				width : 80
			}, {
				name : 'baseGrade',
				display : '���뼶��',
				sortable : true,
				hide : true
			}, {
				name : 'baseGradeName',
				display : '���뼶��',
				sortable : true,
				width : 80
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				width : 60,
				process : function(v){
					if(v == "1"){
						return '����';
					}else{
						return '�ر�';
					}
				}
			}, {
				name : 'remark',
				display : '��ע',
				sortable : true,
				width : 120
			}, {
				name : 'createName',
				display : '������',
				sortable : true,
				width : 80
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				width : 120
			}, {
				name : 'updateName',
				display : '�޸���',
				sortable : true,
				width : 80
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				width : 120
			}
		],
		toEditConfig : {
			toEditFn : function(p,g){
				action : showOpenWin("?model=hr_baseinfo_certifytemplate&action=toEdit&id=" + g.getSelectedRow().data('data')['id']);
			}
		},
		toViewConfig : {
			toViewFn : function(p,g){
				action : showOpenWin("?model=hr_baseinfo_certifytemplate&action=toView&id=" + g.getSelectedRow().data('data')['id']);
			}
		},
		toAddConfig : {
			toAddFn : function(p,g){
				showOpenWin("?model=hr_baseinfo_certifytemplate&action=toAdd");
			}
		},
		comboEx:
		[{
				text: " ��չͨ��",
				key: 'careerDirection',
				datacode : 'HRZYFZ'
			},
			{
				text: "״̬",
				key: 'status',
				data: [{
					text : '����',
					value : '1'
				},{
					text : '�ر�',
					value : '0'
				}]
			}
		],
		searchitems : [{
			display : "ģ������",
			name : 'modelNameSearch'
		}]
	});
});
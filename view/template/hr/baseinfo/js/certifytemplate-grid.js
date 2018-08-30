var show_page = function(page) {
	$("#certifytemplateGrid").yxgrid("reload");
};
$(function() {
	$("#certifytemplateGrid").yxgrid({
		model : 'hr_baseinfo_certifytemplate',
		title : '任职资格等级认证评价表模板',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'modelName',
				display : '模板名称',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_baseinfo_certifytemplate&action=toView&id=" + row.id + "\")'>" + v + "</a>";
				}
			}, {
				name : 'careerDirection',
				display : '职业发展通道',
				sortable : true,
				hide : true
			}, {
				name : 'careerDirectionName',
				display : '职业发展通道',
				sortable : true,
				width : 80
			}, {
				name : 'baseLevel',
				display : '申请级别',
				sortable : true,
				hide : true
			}, {
				name : 'baseLevelName',
				display : '申请级别',
				sortable : true,
				width : 80
			}, {
				name : 'baseGrade',
				display : '申请级等',
				sortable : true,
				hide : true
			}, {
				name : 'baseGradeName',
				display : '申请级等',
				sortable : true,
				width : 80
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				width : 60,
				process : function(v){
					if(v == "1"){
						return '启用';
					}else{
						return '关闭';
					}
				}
			}, {
				name : 'remark',
				display : '备注',
				sortable : true,
				width : 120
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true,
				width : 80
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				width : 120
			}, {
				name : 'updateName',
				display : '修改人',
				sortable : true,
				width : 80
			}, {
				name : 'updateTime',
				display : '修改时间',
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
				text: " 发展通道",
				key: 'careerDirection',
				datacode : 'HRZYFZ'
			},
			{
				text: "状态",
				key: 'status',
				data: [{
					text : '启用',
					value : '1'
				},{
					text : '关闭',
					value : '0'
				}]
			}
		],
		searchitems : [{
			display : "模板名称",
			name : 'modelNameSearch'
		}]
	});
});
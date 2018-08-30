var show_page = function(page) {
	$("#categoryGrid").yxgrid("reload");
};
$(function() {
	var id=$("#id").val();
	var name=$("#name").val();
	var isUse = $("#isUse").val();
	var buttonsArr = [];
	var btnIsUse = true;
	
	if(isUse == 1)
		var btnIsUse = false;
	$("#categoryGrid").yxgrid({
				model : 'yxlicense_license_category',
				title : 'license分类信息',
				param :{
					licenseId : id,
					'dir' : 'ASC'
				},
				isAddAction : btnIsUse,
				isDelAction : btnIsUse,
				isEditAction : btnIsUse,
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'categoryName',
							display : 'lincense分类名称',
							sortable : true,
							width : 200
						}, {
							name : 'appendDesc',
							display : '扩展描述',
							sortable : true,
							width : 200
						}, {
							name : 'orderNum',
							display : '排序',
							sortable : true
						}, {
							name : 'showType',
							display : '显示类型',
							sortable : true,
							process : function(v, row) {
								switch(v){
									case "1" : return "<span>列表显示</span>";break;
									case "2" : return "<span>分组显示</span>";break;
									case "3" : return "<span>表单显示</span>";break;
									case "4" : return "<span>直接输入</span>";break;
									case "5" : return "<span>填写表格</span>";break;
								}
							}
						}, {
							name : 'lineFeed',
							display : '最大列数',
							sortable : true
						}, {
							name : 'isHideTitle',
							display : '是否隐藏表名',
							sortable : true,
							process : function(v, row) {
								switch(v){
									case "1" : return "<span>是</span>";break;
									case "0" : return "<span>否</span>";break;
								}
							}
						}],
				
				toViewConfig : {
					action : 'toView',
					formHeight : 350,
					formWidth : 800
				},
				toAddConfig : {
					action : 'toAdd&id='+id,
					formHeight : 350,
					formWidth : 800
				},
				toEditConfig : {
					action : 'toEdit',
					formHeight : 350,
					formWidth : 800
				},
				searchitems : [{
					display : "lincense分类名称",
					name : 'categoryName'
				}]
			});
});
var show_page = function(page) {
	$("#esmasstemplateGrid").yxgrid("reload");
};
$(function() {
	$("#esmasstemplateGrid").yxgrid({
		model : 'engineering_assess_esmasstemplate',
		title : '考核模板表',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'name',
			display : '模板名称',
			sortable : true,
			width : 150
		}, {
			name : 'indexIds',
			display : '考核指标',
			sortable : true,
			hide : true
		}, {
			name : 'indexNames',
			display : '考核指标',
			sortable : true,
			width : 400,
			process : function(v,row){
				var indexNamesArr = v.split(',');
				var needIndexNames = row.needIndexNames;
				var needIndexNamesArr = needIndexNames.split(',');
				var rtVal = "";
				//
				for(var i = 0; i < indexNamesArr.length ;i++){
					if(jQuery.inArray(indexNamesArr[i], needIndexNamesArr) != -1){
						if(i == 0){
							rtVal = "<span class='blue' title='必选指标'>" + indexNamesArr[i] + "</span>";
						}else{
							rtVal += ",<span class='blue' title='必选指标'>" + indexNamesArr[i] + "</span>";
						}
					}else{
						if(i == 0){
							rtVal = indexNamesArr[i];
						}else{
							rtVal += "," + indexNamesArr[i];
						}
					}
				}
				return rtVal;
			}
		}, {
			name : 'needIndexIds',
			display : '必选指标',
			sortable : true,
			hide : true
		}, {
			name : 'needIndexNames',
			display : '必选指标',
			sortable : true,
			width : 200,
			hide : true
		}, {
			name : 'baseScore',
			display : '考核总分',
			sortable : true,
			width : 80
		}, {
			name : 'score',
			display : '模板总分值',
			sortable : true,
			width : 80
		}, {
			name : 'needScore',
			display : '必选合计',
			sortable : true,
			width : 80
		}, {
			name : 'remark',
			display : '备注信息',
			sortable : true,
			width : 200
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "模板名称",
			name : 'nameSearch'
		},{
			display : "备注信息",
			name : 'remarkSearch'
		}]
	});
});
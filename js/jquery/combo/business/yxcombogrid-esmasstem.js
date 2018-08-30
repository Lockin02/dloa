/**
 * 预算项目下拉combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_esmasstem', {
		options : {
			hiddenId : 'id',
			nameCol : 'name',
			gridOptions : {
				model : 'engineering_assess_esmasstemplate',
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
					width : 100
				}, {
					name : 'indexIds',
					display : '考核指标',
					sortable : true,
					hide : true
				}, {
					name : 'indexNames',
					display : '考核指标',
					sortable : true,
					width : 200,
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
					width : 60
				}, {
					name : 'score',
					display : '模板总分值',
					sortable : true,
					width : 60
				}, {
					name : 'needScore',
					display : '必选合计',
					sortable : true,
					width : 60
				}, {
					name : 'remark',
					display : '备注信息',
					sortable : true,
					width : 150
				}],
				searchitems : [{
					display : "模板名称",
					name : 'nameSearch'
				},{
					display : "备注信息",
					name : 'remarkSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "id",
				title : '工程考核模板'
			}
		}
	});
})(jQuery);

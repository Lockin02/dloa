

$(function(){
	// 动态添加预算项目下拉
	$("#personLevel").yxcombogrid_eperson({
		hiddenId : 'personLevelId',
		width : 600,
		height : 250,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			isTitle : true,
			param  : {'status' : 0 ,'isLeaf' : 1},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#coefficient").val(data.coefficient);
					$("#price").val(data.price);

					calPerson();
				}
			}
		}
	});
});
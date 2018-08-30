$(function() {

	moduleArr = getData('HTBK');
    addDataToSelect(moduleArr, 'module');

	$("#areaPrincipal").yxselect_user({
		hiddenId : 'areaPrincipalId',
		formCode : 'regionPrincipal'
	});
	$("#areaSalesman").yxselect_user({
		mode : 'check',
		hiddenId : 'areaSalesmanId',
		formCode : 'regionSalesman'
	});
	$("#tomailName").yxselect_user({
		mode : 'check',
		hiddenId : 'tomailId',
		formCode : 'tomailName'
	});

	$("#province").yxcombotree({
		hiddenId : 'province',// 隐藏控件id
		nameCol : 'name',
		valueCol : 'name',
		width : 290,
		height : 200,
		treeOptions : {
			checkable : true,// 多选
			event : {
				"node_click" : function(event, treeId, treeNode) {

				}
			},
			url : "index1.php?model=system_procity_province&action=getChildren"// 获取数据url
		}
	});

	$("#businessBelongName").yxcombogrid_branch({
		hiddenId : 'businessBelong',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
				}
			}
		}
	});

    $("#provinceManager").yxselect_user({
		mode : 'check',
		hiddenId : 'provinceManagerId',
		formCode : 'provinceManager'
	});
    $("#departmentLeader").yxselect_user({
		mode : 'check',
		hiddenId : 'departmentLeaderId',
		formCode : 'departmentLeader'
	});
    $("#departmentDirector").yxselect_user({
		mode : 'check',
		hiddenId : 'departmentDirectorId',
		formCode : 'departmentDirector'
	});

	// 填入板块名
	var moduleName = $('#module option:selected').text();
	$("#moduleName").val(moduleName);
	$('#module').change(function(){
		$("#moduleName").val($('#module option:selected').text());
	});
});
function sub() {
	var areaName = $("#areaName").val();
    var provinceManager = $("#provinceManager").val();
    var departmentLeader = $("#departmentLeader").val();
    var departmentDirector = $("#departmentDirector").val();
	var areaPrincipal = $("#areaPrincipal").val();
	if (areaName == '') {
		alert("区域名称不能为空！");
		return false;
	}
	return true;
}
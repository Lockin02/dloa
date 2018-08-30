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
		hiddenId : 'province',// ���ؿؼ�id
		nameCol : 'name',
		valueCol : 'name',
		width : 290,
		height : 200,
		treeOptions : {
			checkable : true,// ��ѡ
			event : {
				"node_click" : function(event, treeId, treeNode) {

				}
			},
			url : "index1.php?model=system_procity_province&action=getChildren"// ��ȡ����url
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

	// ��������
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
		alert("�������Ʋ���Ϊ�գ�");
		return false;
	}
	return true;
}
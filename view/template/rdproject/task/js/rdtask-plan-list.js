$(document).ready(function() {
	var planId = $("#planId").val();
	var planName = $("#planName").val();
	var projectId = $("#projectId").val();
	var projectCode = $("#projectCode").val();
	var projectName = $("#projectName").val();
	var tkAltStrAlt = "?model=rdproject_task_rdtask&action=toPlanAddTask&planId="
			+ planId
			+ "&planName="
			+ planName
			+ "&projectId="
			+ projectId
			+ "&projectCode="
			+ projectCode
			+ "&projectName="
			+ projectName
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=950";
	var tnAltStrAlt = "?model=rdproject_task_tknode&action=toAdd&planId="
			+ planId
			+ "&planName="
			+ planName
			+ "&projectId="
			+ projectId
			+ "&projectName="
			+ projectName
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700";
	var importExcel = "?model=rdproject_task_rdtask&action=toImportExcel"
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700"
	$("#addTask").attr("alt", tkAltStrAlt);
	$("#addNode").attr("alt", tnAltStrAlt);
	$("#importTask").attr("alt", importExcel);
});

function goPage(thisValue) {
	if (thisValue == "treeTable") {
		$('#tkGantt').hide();
		$('#gridTree').show();
		// show_page(1);
	} else if (thisValue == "gantt") {
		$('#gridTree').hide();
		$('#tkGantt').show();
		var planId = $("#planId").val();
		$('#tkGanttView')
				.attr("src",
						"?model=rdproject_task_rdtask&action=showGantt&planId=" + planId );
	} else {
		return false;
	}
}
/*
 * 导出EXCEL
 */
function exportExcel() {
	var selectField = "";
	var tdName = "";

	var ckSize = $('.multiSelect').next('.multiSelectOptions')
			.find('INPUT:checkbox:checked').size();
	if (ckSize == 0) {
		alert("请选择需要导出的字段!")
	} else {
		$('.multiSelect').next('.multiSelectOptions')
				.find('INPUT:checkbox:checked').each(function() {
					if ($(this).parent().text() != "全选") {
						selectField += $(this).val() + ",";
						tdName += $(this).parent().text() + ",";
					}
				});
		window.open(
				"?model=rdproject_task_rdtask&action=exportExcel&selectField="
						+ selectField + "&tdName=" + tdName, "",
				"width=200,height=200,top=200,left=200");
	}

	// window.open("?model=rdproject_task_rdtask&action=exportExcel","","width=200,height=200,top=200,left=200");
}

// 映射字段控制
$(document).ready(function() {
	$("#control_1").multiSelect();
})
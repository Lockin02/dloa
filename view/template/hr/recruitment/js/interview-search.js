$(document).ready(function() {

	$("#deptName").yxselect_dept({
			hiddenId : 'deptId',
			event : {
				selectReturn : function(e,row){
					$("#positionName").val("");
					$("#positionId").val("");
					$("#positionName").yxcombogrid_position("remove");
					//ְλѡ��
					$("#positionName").yxcombogrid_position({
						hiddenId : 'positionId',
						width:350,
						gridOptions : {
							param:{deptId:row.dept.id}
						}
					});
					$("#positionName").yxcombogrid_position("show");
				}
			}
	});
});

 function toSupport(){

	var formCodeSearch = $.trim($("#formCode").val());
	var formDateBefSearch = $.trim($("#formDateBef").val());
	var formDateEndSearch = $.trim($("#formDateEnd").val());
	var userNameSearch = $.trim($("#userName").val());

	var sexySearch = $.trim($("#sexy").val());
	var positionsNameSearch = $.trim($("#positionsName").val());
	var deptStateSearch = $.trim($("#deptState").val());

	var hrStateSearch = $.trim($("#hrState").val());
	var stateSearch = $.trim($("#state").val());
	var ExaStatusSearch = $.trim($("#ExaStatus").val());

	var deptNameSearch = $.trim($("#deptName").val());
	var useInterviewResultSearch = $.trim($("#useInterviewResult").val());
	var hrSourceType1Search = $.trim($("#hrSourceType1").val());

	var hrSourceType2NameSearch = $.trim($("#hrSourceType2Name").val());

	//���б�����ȡ
	var listGrid= parent.$("#interviewGrid").data('yxgrid');

	//����ֵ�Լ������б����
	setVal(listGrid,'formCode',formCodeSearch);
	setVal(listGrid,'DateBegin',formDateBefSearch);
	setVal(listGrid,'DateEnd',formDateEndSearch);
	setVal(listGrid,'userNameSearch',userNameSearch);

	setVal(listGrid,'sexy',sexySearch);
	setVal(listGrid,'positionsNameSearch',positionsNameSearch);
	setVal(listGrid,'deptState',deptStateSearch);

	setVal(listGrid,'hrState',hrStateSearch);
	setVal(listGrid,'state',stateSearch);
	setVal(listGrid,'ExaStatus',ExaStatusSearch);

	setVal(listGrid,'deptNamSearche',deptNameSearch);
	setVal(listGrid,'useInterviewResult',useInterviewResultSearch);
	setVal(listGrid,'hrSourceType1',hrSourceType1Search);

	setVal(listGrid,'hrSourceType2NameSearch',hrSourceType2NameSearch);

	//ˢ���б�
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal) {
	return obj.options.extParam[thisKey] = thisVal;
}

//���
function toClear(){
	$(".toClear").val('');
}
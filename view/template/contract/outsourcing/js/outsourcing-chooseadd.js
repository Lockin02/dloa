$(function() {
	$("#chooseGrid").yxeditgrid({
		url : '?model=outsourcing_approval_persronRental&action=listJson',
		param : {
			dir : 'ASC',
			mainId :$("#projectId").val()
		},
		objName : "persronRental",
		isAddAndDel : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			type : "hidden"
		},{
			display : 'mainId',
			name : 'mainId',
			sortable : true,
			type : "hidden"
		},{
			display : '选择',
			name : 'choose',
			sortable : true,
			width : 20,
			type : 'checkbox',
			process : function($input, rowData){
				if(rowData.isAddContract == '1'){
					var rowNum = $input.data("rowNum");
					$('#chooseGrid_cmp_choose'+rowNum).hide();
				}
			}
		},{
			display : '级别',
			name : 'personLevelName',
			sortable : true,
			width : 80,
			type : 'statictext'
		},{
			display : '姓名',
			name : 'pesonName',
			sortable : true,
			width : 100,
			type : 'statictext'
		},{
			name : 'suppId',
			display : '归属外包供应商ID',
			width : 200,
			type : 'hidden'
		},{
			name : 'suppName',
			display : '归属外包供应商',
			width : 200,
			type : 'statictext'
		},{
			name : 'suppName',
			display : '归属外包供应商',
			width : 200,
			type : 'hidden'
		},{
			name : 'rentalPrice',
			display : '外包价格',
			width : 100,
			type : 'statictext'
		},{
			name : 'rentalPrice',
			display : '外包价格',
			width : 100,
			type : 'hidden'
		},{
			name : 'isAddContract',
			display : '生成合同',
			width : 50,
			type : 'statictext',
			process : function(v){
				if(v == 1){
					return '是';
				}else{
					return '否';
				}
			}
		}]
	});
});

function checkForm (){
	var length = $("[id^='chooseGrid_cmp_choose']").length;
		var suppId = '';
		for(var i = 0;i<length;i++){
			if($("#chooseGrid_cmp_choose"+i).attr("checked") == true){
				if(suppId == ''){
					suppId = $("#chooseGrid_cmp_suppId"+i).val();
				}else if(suppId != $("#chooseGrid_cmp_suppId"+i).val()){
					alert('选择的立项中存在不相同的供应商');
					return false;
				}			
			}
		}
		if(suppId == ''){
			alert('请选择至少一条记录');
			return false;
		}
//	return true;
}
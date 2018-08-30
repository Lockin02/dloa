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
			display : 'ѡ��',
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
			display : '����',
			name : 'personLevelName',
			sortable : true,
			width : 80,
			type : 'statictext'
		},{
			display : '����',
			name : 'pesonName',
			sortable : true,
			width : 100,
			type : 'statictext'
		},{
			name : 'suppId',
			display : '���������Ӧ��ID',
			width : 200,
			type : 'hidden'
		},{
			name : 'suppName',
			display : '���������Ӧ��',
			width : 200,
			type : 'statictext'
		},{
			name : 'suppName',
			display : '���������Ӧ��',
			width : 200,
			type : 'hidden'
		},{
			name : 'rentalPrice',
			display : '����۸�',
			width : 100,
			type : 'statictext'
		},{
			name : 'rentalPrice',
			display : '����۸�',
			width : 100,
			type : 'hidden'
		},{
			name : 'isAddContract',
			display : '���ɺ�ͬ',
			width : 50,
			type : 'statictext',
			process : function(v){
				if(v == 1){
					return '��';
				}else{
					return '��';
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
					alert('ѡ��������д��ڲ���ͬ�Ĺ�Ӧ��');
					return false;
				}			
			}
		}
		if(suppId == ''){
			alert('��ѡ������һ����¼');
			return false;
		}
//	return true;
}
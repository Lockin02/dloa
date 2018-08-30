$(document).ready(function() {
	//初始化序列号
	var serialTb = $("#serialTb");
	serialTb.yxeditgrid({
		objName : 'compensate[detail]',
		url : '?model=finance_compensate_compensate&action=getSerialNos',
		tableClass : 'form_in_table',
		isAdd : false,
		title : "选择序列号,数量：" + $("#number").val(),
		param : {
			'relDocId' : $("#relDocId").val(),
			'relDocType' : $("#relDocType").val(),
			'returnequId' : $("#returnequId").val(),
			'id' : $("#id").val()
		},
		colModel : [{
			display : 'serialId',
			name : 'serialId',
			type : 'hidden'
		}, {
			display : '序列号',
			name : 'serialName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}],
		event : {
			'reloadData' : function(){
				serialTb.find('thead tr').eq(0).children().eq(0).append(
					' <input type="button" class="txt_btn_a" onclick="checkSerialNos();" value="确认选择"/>'
				);
			}
		}
	});
});

//序列号选择方法
function checkSerialNos(){
	var number = $("#number").val();//数量
	var serialTb = $("#serialTb");//缓存列表对象
	var serialIdArr = serialTb.yxeditgrid('getCmpByCol','serialId');//序列号ID数组
	if(serialIdArr.length > number*1){
		alert('序列号数量不能对应物料数量');
		return false;
	}else{
		var serialNos = [];//需要返回的名称数组
		var serialIds = [];//需要返回的id数组
		var serialNameArr = serialTb.yxeditgrid('getCmpByCol','serialName');//序列号名称数组

		serialIdArr.each(function(){
			serialIds.push(this.value);
		});

		serialNameArr.each(function(){
			serialNos.push(this.value);
		});

		//赋值
		var rowNum = $("#rowNum").val();
		self.parent.$("#detail").yxeditgrid('setRowColValue',rowNum,'serialIds',serialIds.toString());
		self.parent.$("#detail").yxeditgrid('setRowColValue',rowNum,'serialNos',serialNos.toString());
		closeFun();
	}
}
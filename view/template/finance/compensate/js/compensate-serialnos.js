$(document).ready(function() {
	//��ʼ�����к�
	var serialTb = $("#serialTb");
	serialTb.yxeditgrid({
		objName : 'compensate[detail]',
		url : '?model=finance_compensate_compensate&action=getSerialNos',
		tableClass : 'form_in_table',
		isAdd : false,
		title : "ѡ�����к�,������" + $("#number").val(),
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
			display : '���к�',
			name : 'serialName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}],
		event : {
			'reloadData' : function(){
				serialTb.find('thead tr').eq(0).children().eq(0).append(
					' <input type="button" class="txt_btn_a" onclick="checkSerialNos();" value="ȷ��ѡ��"/>'
				);
			}
		}
	});
});

//���к�ѡ�񷽷�
function checkSerialNos(){
	var number = $("#number").val();//����
	var serialTb = $("#serialTb");//�����б����
	var serialIdArr = serialTb.yxeditgrid('getCmpByCol','serialId');//���к�ID����
	if(serialIdArr.length > number*1){
		alert('���к��������ܶ�Ӧ��������');
		return false;
	}else{
		var serialNos = [];//��Ҫ���ص���������
		var serialIds = [];//��Ҫ���ص�id����
		var serialNameArr = serialTb.yxeditgrid('getCmpByCol','serialName');//���к���������

		serialIdArr.each(function(){
			serialIds.push(this.value);
		});

		serialNameArr.each(function(){
			serialNos.push(this.value);
		});

		//��ֵ
		var rowNum = $("#rowNum").val();
		self.parent.$("#detail").yxeditgrid('setRowColValue',rowNum,'serialIds',serialIds.toString());
		self.parent.$("#detail").yxeditgrid('setRowColValue',rowNum,'serialNos',serialNos.toString());
		closeFun();
	}
}
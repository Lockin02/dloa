$(function() {
	var html='<tr>'+
			'<td >1</td>'+
			'<td >'+
				'<select id="logic0" class="selectshort logic" name="contract[0][logic]">'+
					'<option value="and">����</option>'+
					'<option value="or">����</option>'+
				'</select>'+
			'</td>'+
			'<td >'+
				'<select id="field0" class="selectmiddel field"  name="contract[0][field]">'+
					'<option value="productName">��������</option>'+
					'<option value="productNumb">���ϴ���</option>'+
					'<option value="searchYear">��ѯ���</option>'+
					'<option value="beginMonth">��ʼ�·�</option>'+
					'<option value="endMonth">�����·�</option>'+
				'</select>'+
			'</td>'+
			'<td>'+
				'<select id="relation0" class="selectshort relation"  name="contract[0][relation]">'+
					'<option value="in">����</option>'+
					'<option value="equal">����</option>'+
					'<option value="notequal">������</option>'+
					'<option value="greater">����</option>'+
					'<option value="less">С��</option>'+
					'<option value="notin">������</option>'+
				'</select>'+
			'</td>'+
			'<td>'+
				'<div  id="type0"><input type="text" id="values0" class="txt value"  name="contract[0][values]" value="" onblur="trimSpace(this);"/></div>'+
			'</td>'+
			'<td>'+
				'<img title="ɾ����" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif"/>'+
			'</td>'+
		'</tr>';
	var invnumber=$("#invnumber").val();
	if(invnumber==0){
		$("#invbody").append(html);
	}


	//��ѯ�ֶ�ѡ����¼�
	$("#field0").bind("change",function(){
		if($(this).val()=="beginMonth"||$(this).val()=="endMonth"){//�жϲ�ѯ�ֶ��Ƿ�Ϊ����ʼ�·ݡ����������׷��ѡ���
			var tdHtml='<select id="values0" class="select field"  name="contract[0][values]">'+
							'<option value="01">1�·�</option>' +
							'<option value="02">2�·�</option>' +
							'<option value="003">3�·�</option>' +
							'<option value="4">4�·�</option>' +
							'<option value="05">5�·�</option>' +
							'<option value="06">6�·�</option>' +
							'<option value="007">7�·�</option>' +
							'<option value="8">8�·�</option>' +
							'<option value="09">9�·�</option>' +
							'<option value="10">10�·�</option>' +
							'<option value="11">11�·�</option>' +
							'<option value="12">12�·�</option>' +
						'</select>';
			$("#type0").html("");
			$("#type0").html(tdHtml);
			$("#relation0").val("equal");
		}else {
			var tdHtml='<input type="text" id="values0" class="txt value"  name="contract[0][values]" value="" onblur="trimSpace(this);"/>';
			$("#type0").html(tdHtml);
			$("#relation0").val("in");
		}
	});

});
/**********************ɾ����̬��*************************/
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 2; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i-1;
		}
	}
}
/**********************��Ŀ�б�*************************/
function dynamic_add(packinglist, countNumP) {
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i+1;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML =  '<select id="logic'+mycount+'" class="selectshort logic"  name="contract['+mycount+'][logic]">'+
						'<option value="and">����</option>'+
						'<option value="or">����</option></select>';
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML ='<select id="field'+mycount+'" class="selectmiddel field" name="contract['+mycount+'][field]"> '+
						'<option value="productName">��������</option>'+
						'<option value="productNumb">���ϴ���</option>'+
						'<option value="searchYear">��ѯ���</option>'+
						'<option value="beginMonth">��ʼ�·�</option>'+
						'<option value="endMonth">�����·�</option>'+
						'</select>';
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML ='<select id="relation'+mycount+'" class="selectshort relation" name="contract['+mycount+'][relation]"> '+
						'<option value="in">����</option>'+
						'<option value="equal">����</option>'+
						'<option value="notequal">������</option>'+
						'<option value="greater">����</option>'+
						'<option value="less">С��</option>'+
						'<option value="notin">������</option>'+
						'</select>';
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<div  id="type'+mycount+'"><input type="text" class="txt values" id="value'+mycount+'" name="contract['+mycount+'][values]"   value="" onblur="trimSpace(this);"></div>';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<img title="ɾ����" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;
				//��ѯ�ֶ�ѡ����¼�
	$("#field"+mycount).bind("change",function(mycount){
			return function(){
				if($(this).val()=="beginMonth"||$(this).val()=="endMonth"){//�жϲ�ѯ�ֶ��Ƿ�Ϊ���ɹ����͡����������׷��ѡ���
					var tdHtml='<select id="values'+mycount+'" class="select field"  name="contract['+mycount+'][values]">'+
									'<option value="01">1�·�</option>' +
									'<option value="02">2�·�</option>' +
									'<option value="03">3�·�</option>' +
									'<option value="04">4�·�</option>' +
									'<option value="05">5�·�</option>' +
									'<option value="06">6�·�</option>' +
									'<option value="07">7�·�</option>' +
									'<option value="08">8�·�</option>' +
									'<option value="09">9�·�</option>' +
									'<option value="10">10�·�</option>' +
									'<option value="11">11�·�</option>' +
									'<option value="12">12�·�</option>' +
								'</select>';
					$("#type"+mycount).html("");
					$("#type"+mycount).html(tdHtml);
					$("#relation"+mycount).val("equal");
				}else {
					var tdHtml='<input type="text" id="value'+mycount+'" class="txt value"  name="contract['+mycount+'][values]" value="" onblur="trimSpace(this);"/>';
					$("#type"+mycount).html(tdHtml);
					$("#relation"+mycount).val("in");
				}
			};
	}(mycount));

}
//���ݲ�ѯ�������в�ѯ
function toSupport() {
	$.each($(':input[class^="txt values"]'),function(){
			if($(this).val()==""){
				alert("�������ѯֵ");
				$(this).focus();
				return false;
			}
		});
//	parent.opener.$("#form2").html("");
//	parent.opener.$("#form2").append($("#form1").html());
//		alert($("#form1").html())
	parent.opener.document.getElementById("form2").innerHTML=document.getElementById("form1").innerHTML;
	parent.opener.document.getElementById("form2").submit();
//	this.opener.location = "?model=purchase_contract_purchasecontract&action=toListInfo"
	this.close();
}
//ȥ��ǰ��ո�
function trimSpace(obj){
	var newVal=$.trim($(obj).val());
	$(obj).val(newVal);
}


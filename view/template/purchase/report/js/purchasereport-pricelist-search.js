$(function() {
	var html='<tr>'+
			'<td >1</td>'+
			'<td >'+
				'<select id="logic0" class="selectshort logic" name="contract[0][logic]">'+
					'<option value="and">并且</option>'+
					'<option value="or">或者</option>'+
				'</select>'+
			'</td>'+
			'<td >'+
				'<select id="field0" class="selectmiddel field"  name="contract[0][field]">'+
					'<option value="productName">物料名称</option>'+
					'<option value="productNumb">物料代码</option>'+
					'<option value="searchYear">查询年份</option>'+
					'<option value="beginMonth">起始月份</option>'+
					'<option value="endMonth">结束月份</option>'+
				'</select>'+
			'</td>'+
			'<td>'+
				'<select id="relation0" class="selectshort relation"  name="contract[0][relation]">'+
					'<option value="in">包含</option>'+
					'<option value="equal">等于</option>'+
					'<option value="notequal">不等于</option>'+
					'<option value="greater">大于</option>'+
					'<option value="less">小于</option>'+
					'<option value="notin">不包含</option>'+
				'</select>'+
			'</td>'+
			'<td>'+
				'<div  id="type0"><input type="text" id="values0" class="txt value"  name="contract[0][values]" value="" onblur="trimSpace(this);"/></div>'+
			'</td>'+
			'<td>'+
				'<img title="删除行" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif"/>'+
			'</td>'+
		'</tr>';
	var invnumber=$("#invnumber").val();
	if(invnumber==0){
		$("#invbody").append(html);
	}


	//查询字段选择绑定事件
	$("#field0").bind("change",function(){
		if($(this).val()=="beginMonth"||$(this).val()=="endMonth"){//判断查询字段是否为“起始月份”，如果是则追加选择框
			var tdHtml='<select id="values0" class="select field"  name="contract[0][values]">'+
							'<option value="01">1月份</option>' +
							'<option value="02">2月份</option>' +
							'<option value="003">3月份</option>' +
							'<option value="4">4月份</option>' +
							'<option value="05">5月份</option>' +
							'<option value="06">6月份</option>' +
							'<option value="007">7月份</option>' +
							'<option value="8">8月份</option>' +
							'<option value="09">9月份</option>' +
							'<option value="10">10月份</option>' +
							'<option value="11">11月份</option>' +
							'<option value="12">12月份</option>' +
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
/**********************删除动态表单*************************/
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 2; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i-1;
		}
	}
}
/**********************条目列表*************************/
function dynamic_add(packinglist, countNumP) {
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i+1;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML =  '<select id="logic'+mycount+'" class="selectshort logic"  name="contract['+mycount+'][logic]">'+
						'<option value="and">并且</option>'+
						'<option value="or">或者</option></select>';
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML ='<select id="field'+mycount+'" class="selectmiddel field" name="contract['+mycount+'][field]"> '+
						'<option value="productName">物料名称</option>'+
						'<option value="productNumb">物料代码</option>'+
						'<option value="searchYear">查询年份</option>'+
						'<option value="beginMonth">起始月份</option>'+
						'<option value="endMonth">结束月份</option>'+
						'</select>';
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML ='<select id="relation'+mycount+'" class="selectshort relation" name="contract['+mycount+'][relation]"> '+
						'<option value="in">包含</option>'+
						'<option value="equal">等于</option>'+
						'<option value="notequal">不等于</option>'+
						'<option value="greater">大于</option>'+
						'<option value="less">小于</option>'+
						'<option value="notin">不包含</option>'+
						'</select>';
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<div  id="type'+mycount+'"><input type="text" class="txt values" id="value'+mycount+'" name="contract['+mycount+'][values]"   value="" onblur="trimSpace(this);"></div>';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<img title="删除行" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;
				//查询字段选择绑定事件
	$("#field"+mycount).bind("change",function(mycount){
			return function(){
				if($(this).val()=="beginMonth"||$(this).val()=="endMonth"){//判断查询字段是否为“采购类型”，如果是则追加选择框
					var tdHtml='<select id="values'+mycount+'" class="select field"  name="contract['+mycount+'][values]">'+
									'<option value="01">1月份</option>' +
									'<option value="02">2月份</option>' +
									'<option value="03">3月份</option>' +
									'<option value="04">4月份</option>' +
									'<option value="05">5月份</option>' +
									'<option value="06">6月份</option>' +
									'<option value="07">7月份</option>' +
									'<option value="08">8月份</option>' +
									'<option value="09">9月份</option>' +
									'<option value="10">10月份</option>' +
									'<option value="11">11月份</option>' +
									'<option value="12">12月份</option>' +
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
//根据查询条件进行查询
function toSupport() {
	$.each($(':input[class^="txt values"]'),function(){
			if($(this).val()==""){
				alert("请输入查询值");
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
//去除前后空格
function trimSpace(obj){
	var newVal=$.trim($(obj).val());
	$(obj).val(newVal);
}


//��ѡָ��
var needIndexIdsArr = [];

$(function(){

	//ʵ����ģ��
	$("#templateName").yxcombogrid_esmasstem({
		hiddenId :  'templateId',
		isFocusoutCheck : false,
		height : 300,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					$("#baseScore").val(data.baseScore);
					$("#score").val(data.score);
					$("#needScore").val(data.needScore);
					$("#useScore").val(data.needScore);

					$("#useIndexNames").val(data.needIndexNames);
					$("#useIndexIds").val(data.needIndexIds);
					$("#needIndexNames").val(data.needIndexNames);
					$("#needIndexIds").val(data.needIndexIds);
					$("#indexNames").val(data.indexNames);
					$("#indexIds").val(data.indexIds);

					initDetail(data.indexIds,data.needIndexIds);
				}
			}
		},
		event : {
			'beforeclear' : function(e,g) {
				g.isClear = ajaxDelete();
			}
		}
	});

	if($("#indexInfo").html()){
		$("#indexTr").show();
	}

	//��ʼ����ѡ����
	initNeed();
});

//ģ��ѡ��󴥷��¼�
function initDetail(indexIds,needIndexIds){
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_assess_esmassindex&action=ajaxGetIndex",
	    data: {"indexIds" : indexIds,'needIndexIds' : needIndexIds},
	    async: false,
	    success: function(data){
	   		if(data != ""){
				$("#indexInfo").html(data);
				$("#indexTr").show();

				//��ʼ����ѡ����
				initNeed();
	   	    }else{
				alert('û��������ĵ���');
	   	    }
		}
	});
}

//���ģ����Ϣ
function ajaxDelete(){
	var id = $("#id").val();
	var templateId = $("#templateId").val();
	if(templateId){
		if(confirm('ȷ��Ҫ���ָ����Ϣ��')){
			if(id){
				$.ajax({
				    type: "POST",
				    url: "?model=engineering_assess_esmasspro&action=ajaxdeletes",
				    data: {"id" : id },
				    async: false,
				    success: function(data){
				   		if(data == "1"){
							alert('�����ɹ�');
				   	    }else{
							alert('û��������ĵ���');
				   	    }
					}
				});
			}
			clearInfo();
		}else{
			return false;
		}
	}else{
		alert('û������ָ����Ϣ�����ܽ����������');
	}
	return true;
}

//��������Ϣ
function clearInfo(){
	$("#indexInfo").empty();
	$("#indexTr").hide();

	$("#baseScore").val('');
	$("#score").val('');
	$("#needScore").val('');
	$("#useScore").val('');

	$("#useIndexNames").val('');
	$("#useIndexIds").val('');
	$("#needIndexNames").val('');
	$("#needIndexIds").val('');
	$("#indexNames").val('');
	$("#indexIds").val('');
	$("#templateName").val('');
	$("#templateId").val('');

	$("#id").val('');

	//��ʼ����ѡ����
	initNeed();
}

//��ʼ����ѡָ������
function initNeed(){
	var needIndexIds = $("#needIndexIds").val();
	if(needIndexIds != ""){
		needIndexIdsArr = needIndexIds.split(",");
	}
}

//ѡ���ѡ��
function checkVal($key){
	var useIndexNamesArr = [];//��ѡָ������
	var useIndexIdsArr = [];//��ѡָ��id
	var useScoreObj = $("#useScore");
	var useScore = 0;
	$("input[id^='chk']").each(function(i,n){
		var isDelTagIndex = $("#isDelTagIndex_" + i);
		if($(this).attr('checked') == true && isDelTagIndex.length == 0){
			useIndexNamesArr.push($(this).attr('indexName'));
			useIndexIdsArr.push($(this).attr('indexId'));
			useScore = accAdd(useScore,$(this).attr('score'),0);
		}
	});

	if(useIndexIdsArr.length > 0){
		$("#useIndexNames").val(useIndexNamesArr.toString());
		$("#useIndexIds").val(useIndexIdsArr.toString());
	}else{
		$("#useIndexNames").val("");
		$("#useIndexIds").val("");
	}

	var chk = $("#chk" + $key);
	if(chk.attr("checked") == true){//����Ѿ�ѡ���򴥷�ѡ�з���
		//���ñ���
		$("#isUse" + $key).val(1);
	}else{
		//���ñ���
		$("#isUse" + $key).val(0);
	}

	useScoreObj.val(useScore);
}

//��ʾ�༭��Ϣ
function showEditInfo(thisVal){
	$("#innerTr_" + thisVal).attr("class","innerTd");
	$("#span_" + thisVal).hide();
	$("#table_" + thisVal).show();
}

//ѡ����������
function optionSet($key,$k){

}

//ѡ���ֵ����
function scoreSet($key,$k){
	var scoreArr = $("input[id^='score_"+ $key +"']");

	var maxScore;
	var minScore;
	scoreArr.each(function(i,n){
		if($("#isDelTag_" + $key +'_'+i).length == "0"){
			if(maxScore){
				if(maxScore < this.value*1){
					maxScore = this.value*1;
				}
			}else{
				maxScore = this.value*1;
			}

			if(minScore){
				if(minScore > this.value*1){
					minScore = this.value*1;
				}
			}else{
				minScore = this.value*1;
			}
		}
	});
	var chk = $("#chk" + $key);
	chk.attr("score",maxScore);
	if(chk.attr("checked") == true){//����Ѿ�ѡ���򴥷�ѡ�з���
		checkVal();
	}
	$("#upperLimit" + $key).val(maxScore);
	$("#lowerLimit" + $key).val(minScore);
}

//ѡ��ؽ�ָ����Ϣ
function initIndex($key){
	var optionNameArr = $("input[id^='optionName_"+ $key +"']");
}

//ָ����Ϣ����
function indexSet($key){
	var indexName = $("#indexName" + $key).val();
	var chk = $("#chk" + $key);
	chk.attr("indexName",indexName);
	if(chk.attr("checked") == true){//����Ѿ�ѡ���򴥷�ѡ�з���
		checkVal();
	}
}

//�½�ָ��
function addIndex(){
	var rowLength = $("#indexInfo tr[id^='tr']").length;

	var idStr = rowLength + "_" + 0;
	var trClass = rowLength%2 == 0 ? 'tr_odd' : 'tr_even';
	var str = '<tr id="tr'+rowLength+'" class="'+trClass+'">' +
			'<td valign="top"><img src="images/removeline.png" onclick="removeIndex('+rowLength+',this)" title="ɾ����"/></td>' +
			'<td valign="top">' +
			'<input type="text" class="txtmiddle" name="esmasspro[esmassproindex]['+rowLength+'][indexName]" id="indexName'+rowLength+'" onblur="indexSet('+rowLength+');" value=""/>' +
			'<input type="hidden" name="esmasspro[esmassproindex]['+rowLength+'][indexId]" id="indexId'+rowLength+'" value="-1"/>' +
			'</td>' +
			'<td valign="top">' +
			'<input type="text" class="readOnlyTxtMiddle" name="esmasspro[esmassproindex]['+rowLength+'][upperLimit]" id="upperLimit'+rowLength+'" value="0" readonly="readonly"/>' +
			'</td>' +
			'<td valign="top">' +
			'<input type="text" class="readOnlyTxtMiddle" name="esmasspro[esmassproindex]['+rowLength+'][lowerLimit]" id="lowerLimit'+rowLength+'" value="0" readonly="readonly"/>' +
			'</td>' +
			'<td valign="top">��</td>' +
			'<td valign="top">' +
			'<input type="checkbox" id="chk'+rowLength+'" onclick="checkVal('+rowLength+')" score="0" indexName="" indexId="-1"/>' +
			'<input type="hidden" name="esmasspro[esmassproindex]['+rowLength+'][isUse]" id="isUse'+rowLength+'" value="0"/>' +
			'</td>' +
			'<td valign="top" id="innerTr_'+rowLength+'" colspan="3" style="text-align:left" class="innerTd">' +
			'<span id="span_'+rowLength+'" ondblclick="showEditInfo('+rowLength+');" style="display:none"></span>' +
			'<table class="form_in_table" id="table_'+rowLength+'"><tr id="option_'+ idStr +'">' +
			'<td width="35%">' +
			'<input type="text" name="esmasspro[esmassproindex]['+rowLength+'][options]['+rowLength+'][optionName]" id="optionName_'+ idStr +'" value="" class="txtmiddle" onblur="optionSet('+rowLength+','+rowLength+');"/>' +
			'</td>' +
			'<td width="35%">' +
			'<input type="text" name="esmasspro[esmassproindex]['+rowLength+'][options]['+rowLength+'][score]" id="score_'+ idStr +'" value="" class="txtshort" onblur="scoreSet('+rowLength+','+rowLength+');"/>' +
			'</td>' +
			'<td width="30%">' +
			'<img src="images/add_item.png" onclick="addOption('+rowLength+','+rowLength+',this)" title="�����"/>' +
			'</td>' +
			'</tr>' +
			'</table>' +
			'</td>' +
			'</tr>';
	$("#indexInfo").append(str);
}

//�½�ָ��
function removeIndex($key,obj){
	var indexId = $("#indexId" + $key).val();
	if(jQuery.inArray(indexId,needIndexIdsArr) != '-1'){
		alert('��ѡָ�겻�ܽ���ɾ������');
		return false;
	}
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="esmasspro[esmassproindex]['+
				$key +'][isDelTag]" id="isDelTagIndex_'+ $key +'" value="1"/>');

		//���¼���
		checkVal();
	}
}

//����ѡ��
function addOption($key,$k){
	var rowLength = $("input[id^='score_"+ $key +"']").length;

	var idStr = $key + "_" + rowLength;
	var str = '<tr id="option_'+ idStr +'">' +
			'<td width="35%">' +
			'<input type="text" name="esmasspro[esmassproindex]['+$key+'][options]['+rowLength+'][optionName]" id="optionName_'+ idStr +'" value="" class="txtmiddle" onblur="optionSet('+$key+','+rowLength+');"/>' +
			'</td>' +
			'<td width="35%">' +
			'<input type="text" name="esmasspro[esmassproindex]['+$key+'][options]['+rowLength+'][score]" id="score_'+ idStr +'" value="" class="txtshort" onblur="scoreSet('+$key+','+rowLength+');"/>' +
			'</td>' +
			'<td width="30%">' +
			'<img src="images/removeline.png" onclick="removeOption('+$key+','+rowLength+',this)" title="ɾ����"/>' +
			'</td>' +
			'</tr>';
	$("#table_" + $key).append(str);
}

//ɾ��ѡ��
function removeOption($key,$k,obj){
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="esmasspro[esmassproindex]['+
				$key +'][options][' +
				rowNo + '][isDelTag]" id="isDelTag_'+ $key +'_'+rowNo +'" value="1"/>');

		//��ֵ����
		scoreSet($key,$k);
	}
}

//����֤
function checkform(){
	var templateName = $("#templateName").val();
	if(templateName == ""){
		alert('��ѡ��һ��ģ��');
		return false;
	}

	var baseScore = $("#baseScore").val()*1;
	var useScore = $("#useScore").val()*1;
	if(baseScore != useScore){
		alert('ѡ�кϼƱ����뿼���ܷ���ȣ���ǰѡ��ָ���ܷ�Ϊ' + useScore + "��ģ��Ҫ���ܷ�Ϊ" + baseScore);
		return false;
	}

	//ȷ�ϱ���
	if(!confirm('ȷ�ϱ�����')){
		return false;
	}
	return true;
}
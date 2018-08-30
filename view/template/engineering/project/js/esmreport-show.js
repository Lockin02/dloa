var show_page = function() {
	$("#esmprojectGrid").yxeditgrid("reload");
};

var datas;
var typeArr ={'showProjectCount': '','showContractMoneySum':'','showSalesMoney':'',
          	'showCost': '','showProfitMoney': '','showProfitRate': '','showBudget':'',
          	'showEstimate':''};
var detailThead = {'showContractMoneySum': {'��ͬ��' : ['Ŀ��','ʵ��']},'showSalesMoney':{'Ӫ��' : ['Ŀ��','ʵ��']},
               	'showCost': {'�ɱ�' : ['Ŀ��','ʵ��']},'showProfitMoney':{'ë��' : ['Ŀ��','ʵ��']},
               	'showProfitRate': {'ë����' : ['Ŀ��','ʵ��']},'showBudget': {'Ԥ��' : ['Ŀ��','ʵ��']},
            	'showEstimate': {'����' : ['Ŀ��','ʵ��']}};
var detailTheadArr = [];
$(function() {
	var data = $("#memoryused").val();
	datas =  eval("(" + data + ")");
	
	//�����û�ϰ������ĳЩ��
	for(var key in datas){

		if(datas[key] == "1"){
			$("#"+key).attr("checked",true);
			if(key != 'showProjectCount'){
				detailTheadArr.push(detailThead[key]);
			}
			typeArr[key] = "";
		}else{
			typeArr[key] = "hidden";
		}
	}
	$(".checkItems").each(function(){
		$(this).click(function(){
			checkChange($(this).attr('id'));
		});
	});

	getReport();
	
	$("#search").click(function(){
		//��ʾLoading
		$("#loading").show();
//		if(JSON.stringify(datas) != $("#memoryused").val()){
			checkAjax();
			resetTableHead();
//		}
		search();
	});
});

/**
 * ��д�ӱ��ͷ
 */
function tableHead(){
	var trHTML =  '';
	var detailArr = [];
	var mergeArr = [];
	var lengthArr = [];
	
	
	//���ϱ�ͷ�������
	var detailTheadJson = detailTheadArr;
//	var detailTheadJson = {
//		'��ͬ��' : ['Ŀ��','ʵ��'],
//		'Ӫ��' : ['Ŀ��','ʵ��'],
//		'�ɱ�' : ['Ŀ��','ʵ��'],
//		'ë��' : ['Ŀ��','ʵ��'],
//		'ë����' : ['Ŀ��','ʵ��'],
//		'Ԥ��' : ['Ŀ��','ʵ��'],
//		'����' : ['Ŀ��','ʵ��']
//	};
	//ѭ�����������ϱ�ͷ����
	var length = 0;
	for(var i =0; i<detailTheadJson.length;i++){
		for(k in detailTheadJson[i]){
			mergeArr.push(k);
			length = 0;
			for(var prop in detailTheadJson[i][k]){
			    if(detailTheadJson[i][k].hasOwnProperty(prop))
			        length++;
			}
			lengthArr.push(length);
			//��ϸ��ͷ
			for(m in detailTheadJson[i][k]){
				if(m*1 == m){
					detailArr.push(detailTheadJson[i][k][m]);
				}
			}
		}
	}

	var trObj = $("#esmprojectGrid tr:eq(0)");
	var tdArr = trObj.children();
	var markMergeTitle = '';
	var markMergeLength = 0;
	var markMergeNo = 0;
	tdArr.each(function(i,n){
		if($(this).text() == '���' || $(this).is(":hidden") == true ){
			$(this).attr("rowSpan",2);
		}else{//����Ŵ���
			if($.inArray($(this).text(),detailArr) != -1){
				if(markMergeLength!=0){//�ϲ�����
					markMergeNo++;
					$(this).remove();
					markMergeLength--;
				}else{
					markMergeTitle = mergeArr[markMergeNo];
					markMergeLength = lengthArr[markMergeNo];
					$(this).attr('colSpan',markMergeLength).text(markMergeTitle);
					if(markMergeLength != 1){
						markMergeLength--;
					}
				}
			}else{
				$(this).attr("rowSpan",2);
			}
		}
	});

	trHTML+='<tr class="main_tr_header">';
	for(m=0;m<detailArr.length;m++){
		if(detailArr[m] == 'ȫѡ'){
			trHTML+='<th><div class="divChangeLine" style="min-width:60px;"><input type="checkbox" onclick="checkAll();" id="all"></div></th>';
		}else{
			trHTML+='<th><div class="divChangeLine" style="min-width:60px;">'+detailArr[m]+'</div></th>';
		}
	}
	trHTML+='</tr>';
	trObj.after(trHTML);
}

function getReport(){
	//���ݲ�ѯ���������б�
	$("#esmprojectGrid").yxeditgrid({
		url : '?model=engineering_project_esmreport&action=showProjectList',
		action : 'post', 
		isAddAndDel : false,
		type : 'view',
		event : {
			'reloadData' : function(e){
				//����Loading
				$("#loading").hide();
			}
		},
		//����Ϣ
		colModel : [{
			display : '��ϸ',
			name : 'detail',
			process : function(v,row,a,b,c,rowNum){
				if(row.officeName != '����'){
					return '<img src="images/add_item.png" id="add'+row.productLine+'" onclick="showdetail(\''+row.productLine+'\',\'officeName\',\''+row.productLine+'\',\''
					+rowNum+'\');"><img src="images/removeline.png" id="remove'+row.productLine+'" style="display:none" onclick="removedetail(\''+row.productLine+'\')">';
				}
			}
		},{
			display : 'ִ������',
			name : 'productLineName',
			process : function(v,row){
				if(v){
					return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&productLine=" + row.productLine
					+"&beginDateSearch="+$("#beginDate").val()
					+"&endDateSearch="+$("#endDate").val()
					+"&status="+$("#status").val()+"&nature="+$("#nature").val()
					+"&contractTypeMix="+$("#contractType").val()
					+"&autoload=true"
					+"\",1)'>" + v + "</a>";
				}
			}
		},{
			display : '����',
			name : 'officeName',
			process : function(v,row){
				if(v){
					if (v =='����'){
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&autoload=true\",1)'>" + v + "</a>";
					}
					return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&officeName=" + v 
					+"&beginDateSearch="+$("#beginDate").val()
					+"&endDateSearch="+$("#endDate").val()
					+"&status="+$("#status").val()+"&nature="+$("#nature").val()
					+"&contractTypeMix="+$("#contractType").val()
					+"&productLine="+row.productLine
					+"&autoload=true"
					+"\",1)'>" + v + "</a>";
				}
			}
		},{
			display : 'ʡ��',
			name : 'province',
			process : function(v,row){
				if(v){
					return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&province=" + v +"&officeName="+row.officeName 
					+"&beginDateSearch="+$("#beginDate").val()
					+"&endDateSearch="+$("#endDate").val()
					+"&status="+$("#status").val()+"&nature="+$("#nature").val()
					+"&contractTypeMix="+$("#contractType").val()
					+"&productLine="+row.productLine
					+"&autoload=true"
					+"\",1)'>" + v + "</a>";
				}
			}
		},{
			display : '��Ŀ����',
			name : 'projectCount',
			type : typeArr['showProjectCount']
		},{
			display : 'Ŀ��',
			name : 'mubiao1',
			process : function(){
				return '-';
			},
			type : typeArr['showContractMoneySum']
		},{
			display : 'ʵ��',
			name : 'contractMoneySum',
			process : function(v){
				if(v < 0){
					return "<span style='color:red'>"+moneyFormat2(v)+"</span>";
				}
				else{
					return moneyFormat2(v);
				}
			},
			align : 'right',
			type : typeArr['showContractMoneySum']
		},{
			display : 'Ŀ��',
			name : 'mubiao2',
			process : function(){
				return '-';
			},
			type : typeArr['showSalesMoney']
		},{
			display : 'ʵ��',
			name : 'salesMoney',
			process : function(v){
				if(v < 0){
					return "<span style='color:red'>"+moneyFormat2(v)+"</span>";
				}
				else{
					return moneyFormat2(v);
				}
			},
			align : 'right',
			type : typeArr['showSalesMoney']
		},{
			display : 'Ŀ��',
			name : 'mubiao3',
			process : function(){
				return '-';
			},
			type : typeArr['showCost']
		},{
			display : 'ʵ��',
			name : 'cost',
			process : function(v){
				if(v < 0){
					return "<span style='color:red'>"+moneyFormat2(v)+"</span>";
				}
				else{
					return moneyFormat2(v);
				}
			},
			align : 'right',
			type : typeArr['showCost']
		},{
			display : 'Ŀ��',
			name : 'mubiao4',
			process : function(){
				return '-';
			},
			type : typeArr['showProfitMoney']
		},{
			display : 'ʵ��',
			name : 'profitMoney',
			process : function(v){
				if(v < 0){
					return "<span style='color:red'>"+moneyFormat2(v)+"</span>";
				}
				else{
					return moneyFormat2(v);
				}
			},
			align : 'right',
			type : typeArr['showProfitMoney']
		},{
			display : 'Ŀ��',
			name : 'mubiao5',
			process : function(){
				return '-';
			},
			type : typeArr['showProfitRate']
		},{
			display : 'ʵ��',
			name : 'profitRate',
			process : function(v){
				if(v < 0){
					return "<span style='color:red'>"+v+'%'+"</span>";
				}
				else{
					return v+'%';
				}
			},
			align : 'right'	,
			type : typeArr['showProfitRate']
		},{
			display : 'Ŀ��',
			name : 'mubiao6',
			process : function(){
				return '-';
			},
			type : typeArr['showBudget']
		},{
			display : 'ʵ��',
			name : 'budget',
			process : function(v){
				if(v < 0){
					return "<span style='color:red'>"+moneyFormat2(v)+"</span>";
				}
				else{
					return moneyFormat2(v);
				}
			},
			align : 'right',
			type : typeArr['showBudget']
		},{
			display : 'Ŀ��',
			name : 'mubiao7',
			process : function(){
				return '-';
			},
			type : typeArr['showEstimate']
		},{
			display : 'ʵ��',
			name : 'estimate',
			process : function(v){
				if(v < 0){
					return "<span style='color:red'>"+moneyFormat2(v)+"</span>";
				}
				else{
					return moneyFormat2(v);
				}
			},
			align : 'right',
			type : typeArr['showEstimate']
		}]
	});
	//���ϱ�ͷ��ʼ��
	tableHead();
}


//��������������
function search (){
	var gridObj = $("#esmprojectGrid");
	var paramObj = {
			statistical : $("#statistical").val(),
			contractType : $("#contractType").val(),
			status : $("#status").val(),
			nature : $("#nature").val(),
			beginDateSearch : $("#beginDate").val(),
			endDateSearch : $("#endDate").val(),
			productLine : $("#productLine").val(),
			
	};
	gridObj.yxeditgrid("remove");
	getReport();
	gridObj.yxeditgrid("setParam",paramObj).yxeditgrid('processData');
}

//�޸ĸ�ѡ��ʱ�����޸Ķ�Ӧ��ֵ
function checkChange(id){
	if($("#"+id).attr("checked")){
		datas[id] = 1;
	}else{
		datas[id] = 0;
	}
}

//�޸��û�ϰ��
function checkAjax(){
	$.ajax({
		type : "POST",
		url : "?model=system_usersetting_usersetting&action=update",
		data : {
			user : $("#user").val(),  
			memoryused : datas
		}
	});
}

//�����û�ϰ�����ñ�ͷ
function resetTableHead(){
	detailTheadArr = [];
	for(var key in datas){
		if(datas[key] == 1){
			if(key != 'showProjectCount'){
				detailTheadArr.push(detailThead[key]);
			}
			typeArr[key] = "";
		}else{
			typeArr[key] = "hidden";
		}
	}
}

// ��ʾ�¼�����(����)
function showdetail(productLine,type,name,rowNum){
	var paramObj = {
			statistical : $("#statistical").val(),
			contractType : $("#contractType").val(),
			status : $("#status").val(),
			nature : $("#nature").val(),
			beginDateSearch : $("#beginDate").val(),
			endDateSearch : $("#endDate").val(),
			productLine : productLine,
			type : type
	};
	if($("tr[name = '"+productLine+"']").html() == null){
	$.ajax({
		type : 'post',
		url : "?model=engineering_project_esmreport&action=showProjectList",
		data : paramObj,
		success : function(msg) {
			var str = "";
			if (msg) {
				msg = eval("(" + msg + ")");
				$.each(msg,function(key,val){
					if(val['officeName'] != '����'){
						str += '<tr class="tr_even" name="'+val['productLine']+'" id="'+val['productLine']+val['officeId']+'"><td type="rowNum"></td><td style="text-align: center;"><div class="divChangeLine">'
						+'<img src="images/add_item.png" id="add'+val['productLine']+val['officeId']+'" onclick="showprovince(\''+val['productLine']+'\',\''+val['officeName']+'\',\'province\',\''+val['productLine']+val['officeId']+'\',\''+val['productLine']+val['officeId']+'\');">'
						+'<img src="images/removeline.png" id="remove'+val['productLine']+val['officeId']+'"  onclick="removeprovince(\''+val['productLine']+val['officeId']+'\')" style="display:none">'
						+'</div></td>'
						+'<td style="text-align: center;"><div class="divChangeLine"></div></td>'
						+'<td style="text-align: center;">'
						+'<div class="divChangeLine" >'
						+"<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&productLine=" + val['productLine']+"&officeName="+val['officeName']
						+"&beginDateSearch="+$("#beginDate").val()
						+"&endDateSearch="+$("#endDate").val()
						+"&status="+$("#status").val()+"&nature="+$("#nature").val()
						+"&contractTypeMix="+$("#contractType").val()
						+"&autoload=true"
						+"\",1)'>" + val['officeName'] + "</a>"
						+'<td style="text-align: center;"><div class="divChangeLine"></div></td>';
						if(typeArr['showProjectCount'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine" >'+val['projectCount']+'</div></td>'
						}
						if(typeArr['showContractMoneySum'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine">-</div></td>';
							if(val['contractMoneySum']<0){
								str += '<td style="text-align: right;"><div class="divChangeLine"><span style="color:red">'+moneyFormat2(val['contractMoneySum'])+'</span></div></td>';
							}else{
								str += '<td style="text-align: right;"><div class="divChangeLine" >'+moneyFormat2(val['contractMoneySum'])+'</div></td>';
							}
						}
						if(typeArr['showSalesMoney'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine">-</div></td>';
							if(val['salesMoney'] < 0){
								str += '<td style="text-align: right;"><div class="divChangeLine"><span style="color:red">'+moneyFormat2(val['salesMoney'])+'</span></div></td>';
							}else{
								str += '<td style="text-align: right;"><div class="divChangeLine">'+moneyFormat2(val['salesMoney'])+'</div></td>';
							}
						}
						if(typeArr['showCost'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine">-</div></td>';
							if(val['cost']<0){
								str += '<td style="text-align: right;"><div class="divChangeLine"><span style="color:red">'+moneyFormat2(val['cost'])+'</span></div></td>';
							}else{
								str += '<td style="text-align: right;"><div class="divChangeLine">'+moneyFormat2(val['cost'])+'</div></td>';
							}
						}
						if(typeArr['showProfitMoney'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine">-</div></td>';
							if(val['profitMoney']<0){
								str += '<td style="text-align: right;"><div class="divChangeLine"><span style="color:red">'+moneyFormat2(val['profitMoney'])+'</span></div></td>'
							}else{
								str += '<td style="text-align: right;"><div class="divChangeLine">'+moneyFormat2(val['profitMoney'])+'</div></td>';
							}
						}
						if(typeArr['showProfitRate'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine">-</div></td>';
							if(val['profitRate']<0){
								str += '<td style="text-align: right;"><div class="divChangeLine"><span style="color:red">'+val['profitRate']+'</span></div></td>'
							}else{
								str += '<td style="text-align: right;"><div class="divChangeLine">'+val['profitRate']+'</div></td>';
							}
							
						}
						if(typeArr['showBudget'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine" >-</div></td>';
							if(val['budget']<0){
								str += '<td style="text-align: right;"><div class="divChangeLine"><span style="color:red">'+moneyFormat2(val['budget'])+'</span></div></td>';
							}else{
								str += '<td style="text-align: right;"><div class="divChangeLine">'+moneyFormat2(val['budget'])+'</div></td>';
							}
						}
						if(typeArr['showEstimate'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine">-</div></td>'
							+'<td style="text-align: right;"><div class="divChangeLine"></div></td></tr>';
						}
					}
				});
				$("tr[rownum = '"+rowNum+"']").after(str);
			}
		}
	});
	}else{
		$("tr[name = '"+productLine+"']").show();
	}
	$("#add"+name).hide();
	$("#remove"+name).show();
}

//��ʾ�¼�ʡ��
function showprovince(productLine,officeName,type,name,trId){
	var paramObj = {
			statistical : $("#statistical").val(),
			contractType : $("#contractType").val(),
			status : $("#status").val(),
			nature : $("#nature").val(),
			beginDateSearch : $("#beginDate").val(),
			endDateSearch : $("#endDate").val(),
			productLine : productLine,
			officeName : officeName,
			type : type
	};
	if($("tr[name = '"+productLine+officeName+"']").html() == null){
	$.ajax({
		type : 'post',
		url : "?model=engineering_project_esmreport&action=showProjectList",
		data : paramObj,
		success : function(msg) {
			var str = "";
			if (msg) {
				msg = eval("(" + msg + ")");
				$.each(msg,function(key,val){
					if(val['officeName'] != '����'){
						str += '<tr class="tr_even" name="'+val['productLine']+val['officeId']+'" id="'+val['productLine']+val['officeId']+val['provinceId']+'"><td type="rowNum"></td><td style="text-align: center;"><div class="divChangeLine">'
						+'<td style="text-align: center;"><div class="divChangeLine"></div></td>'
						+'<td style="text-align: center;"><div class="divChangeLine"></div></td>'
						+'<td style="text-align: center;"><div class="divChangeLine"></div>'
						+"<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&province=" + val['province'] +"&officeName="+val['officeName'] 
							+"&beginDateSearch="+$("#beginDate").val()
							+"&endDateSearch="+$("#endDate").val()
							+"&status="+$("#status").val()+"&nature="+$("#nature").val()
							+"&contractTypeMix="+$("#contractType").val()
							+"&productLine="+val['productLine']
							+"&autoload=true"
							+"\",1)'>" + val['province'] + "</a>"
						+'</td>'
						if(typeArr['showProjectCount'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine" >'+val['projectCount']+'</div></td>'
						}
						if(typeArr['showContractMoneySum'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine">-</div></td>';
							if(val['contractMoneySum']<0){
								str += '<td style="text-align: right;"><div class="divChangeLine"><span style="color:red">'+moneyFormat2(val['contractMoneySum'])+'</span></div></td>';
							}else{
								str += '<td style="text-align: right;"><div class="divChangeLine" >'+moneyFormat2(val['contractMoneySum'])+'</div></td>';
							}
						}
						if(typeArr['showSalesMoney'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine">-</div></td>';
							if(val['salesMoney'] < 0){
								str += '<td style="text-align: right;"><div class="divChangeLine"><span style="color:red">'+moneyFormat2(val['salesMoney'])+'</span></div></td>';
							}else{
								str += '<td style="text-align: right;"><div class="divChangeLine">'+moneyFormat2(val['salesMoney'])+'</div></td>';
							}
						}
						if(typeArr['showCost'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine">-</div></td>';
							if(val['cost']<0){
								str += '<td style="text-align: right;"><div class="divChangeLine"><span style="color:red">'+moneyFormat2(val['cost'])+'</span></div></td>';
							}else{
								str += '<td style="text-align: right;"><div class="divChangeLine">'+moneyFormat2(val['cost'])+'</div></td>';
							}
						}
						if(typeArr['showProfitMoney'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine">-</div></td>';
							if(val['profitMoney']<0){
								str += '<td style="text-align: right;"><div class="divChangeLine"><span style="color:red">'+moneyFormat2(val['profitMoney'])+'</span></div></td>'
							}else{
								str += '<td style="text-align: right;"><div class="divChangeLine">'+moneyFormat2(val['profitMoney'])+'</div></td>';
							}
						}
						if(typeArr['showProfitRate'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine">-</div></td>';
							if(val['profitRate']<0){
								str += '<td style="text-align: right;"><div class="divChangeLine"><span style="color:red">'+val['profitRate']+'</span></div></td>'
							}else{
								str += '<td style="text-align: right;"><div class="divChangeLine">'+val['profitRate']+'</div></td>';
							}
							
						}
						if(typeArr['showBudget'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine" >-</div></td>';
							if(val['budget']<0){
								str += '<td style="text-align: right;"><div class="divChangeLine"><span style="color:red">'+moneyFormat2(val['budget'])+'</span></div></td>';
							}else{
								str += '<td style="text-align: right;"><div class="divChangeLine">'+moneyFormat2(val['budget'])+'</div></td>';
							}
						}
						if(typeArr['showEstimate'] != "hidden"){
							str += '<td style="text-align: center;"><div class="divChangeLine">-</div></td>'
							+'<td style="text-align: right;"><div class="divChangeLine"></div></td></tr>';
						}
					}
				});
				$("tr[id = '"+trId+"']").after(str);
			}
		}
	});
	}else{
		$("tr[name = '"+productLine+officeName+"']").show();
	}
	$("#add"+name).hide();
	$("#remove"+name).show();
}

//����ʡ�ݼ�����Ϣ
function removeprovince(name){
	$("tr[name = '"+name+"']").hide();
	$("#remove"+name).hide();
	$("#add"+name).show();
}

//����ִ�в��ż������µ���Ϣ
function removedetail (name){
	$("tr[name ^= '"+name+"']").hide();
	$("#remove"+name).hide();
	$("#add"+name).show();
}

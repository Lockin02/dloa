var show_page = function() {
	$("#esmprojectGrid").yxeditgrid("reload");
};

var datas;
var typeArr ={'showProjectCount': '','showContractMoneySum':'','showSalesMoney':'',
          	'showCost': '','showProfitMoney': '','showProfitRate': '','showBudget':'',
          	'showEstimate':''};
var detailThead = {'showContractMoneySum': {'合同额' : ['目标','实际']},'showSalesMoney':{'营收' : ['目标','实际']},
               	'showCost': {'成本' : ['目标','实际']},'showProfitMoney':{'毛利' : ['目标','实际']},
               	'showProfitRate': {'毛利率' : ['目标','实际']},'showBudget': {'预算' : ['目标','实际']},
            	'showEstimate': {'概算' : ['目标','实际']}};
var detailTheadArr = [];
$(function() {
	var data = $("#memoryused").val();
	datas =  eval("(" + data + ")");
	
	//根据用户习惯隐藏某些列
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
		//显示Loading
		$("#loading").show();
//		if(JSON.stringify(datas) != $("#memoryused").val()){
			checkAjax();
			resetTableHead();
//		}
		search();
	});
});

/**
 * 重写从表表头
 */
function tableHead(){
	var trHTML =  '';
	var detailArr = [];
	var mergeArr = [];
	var lengthArr = [];
	
	
	//复合表头配置情况
	var detailTheadJson = detailTheadArr;
//	var detailTheadJson = {
//		'合同额' : ['目标','实际'],
//		'营收' : ['目标','实际'],
//		'成本' : ['目标','实际'],
//		'毛利' : ['目标','实际'],
//		'毛利率' : ['目标','实际'],
//		'预算' : ['目标','实际'],
//		'概算' : ['目标','实际']
//	};
	//循环解析出符合表头数组
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
			//明细表头
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
		if($(this).text() == '序号' || $(this).is(":hidden") == true ){
			$(this).attr("rowSpan",2);
		}else{//非序号处理
			if($.inArray($(this).text(),detailArr) != -1){
				if(markMergeLength!=0){//合并计数
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
		if(detailArr[m] == '全选'){
			trHTML+='<th><div class="divChangeLine" style="min-width:60px;"><input type="checkbox" onclick="checkAll();" id="all"></div></th>';
		}else{
			trHTML+='<th><div class="divChangeLine" style="min-width:60px;">'+detailArr[m]+'</div></th>';
		}
	}
	trHTML+='</tr>';
	trObj.after(trHTML);
}

function getReport(){
	//根据查询条件生成列表
	$("#esmprojectGrid").yxeditgrid({
		url : '?model=engineering_project_esmreport&action=showProjectList',
		action : 'post', 
		isAddAndDel : false,
		type : 'view',
		event : {
			'reloadData' : function(e){
				//隐藏Loading
				$("#loading").hide();
			}
		},
		//列信息
		colModel : [{
			display : '详细',
			name : 'detail',
			process : function(v,row,a,b,c,rowNum){
				if(row.officeName != '汇总'){
					return '<img src="images/add_item.png" id="add'+row.productLine+'" onclick="showdetail(\''+row.productLine+'\',\'officeName\',\''+row.productLine+'\',\''
					+rowNum+'\');"><img src="images/removeline.png" id="remove'+row.productLine+'" style="display:none" onclick="removedetail(\''+row.productLine+'\')">';
				}
			}
		},{
			display : '执行区域',
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
			display : '区域',
			name : 'officeName',
			process : function(v,row){
				if(v){
					if (v =='汇总'){
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
			display : '省份',
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
			display : '项目数量',
			name : 'projectCount',
			type : typeArr['showProjectCount']
		},{
			display : '目标',
			name : 'mubiao1',
			process : function(){
				return '-';
			},
			type : typeArr['showContractMoneySum']
		},{
			display : '实际',
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
			display : '目标',
			name : 'mubiao2',
			process : function(){
				return '-';
			},
			type : typeArr['showSalesMoney']
		},{
			display : '实际',
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
			display : '目标',
			name : 'mubiao3',
			process : function(){
				return '-';
			},
			type : typeArr['showCost']
		},{
			display : '实际',
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
			display : '目标',
			name : 'mubiao4',
			process : function(){
				return '-';
			},
			type : typeArr['showProfitMoney']
		},{
			display : '实际',
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
			display : '目标',
			name : 'mubiao5',
			process : function(){
				return '-';
			},
			type : typeArr['showProfitRate']
		},{
			display : '实际',
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
			display : '目标',
			name : 'mubiao6',
			process : function(){
				return '-';
			},
			type : typeArr['showBudget']
		},{
			display : '实际',
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
			display : '目标',
			name : 'mubiao7',
			process : function(){
				return '-';
			},
			type : typeArr['showEstimate']
		},{
			display : '实际',
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
	//复合表头初始化
	tableHead();
}


//根据条件查数据
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

//修改复选框时触发修改对应的值
function checkChange(id){
	if($("#"+id).attr("checked")){
		datas[id] = 1;
	}else{
		datas[id] = 0;
	}
}

//修改用户习惯
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

//根据用户习惯重置表头
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

// 显示下级数据(区域)
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
					if(val['officeName'] != '汇总'){
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

//显示下级省份
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
					if(val['officeName'] != '汇总'){
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

//隐藏省份级别信息
function removeprovince(name){
	$("tr[name = '"+name+"']").hide();
	$("#remove"+name).hide();
	$("#add"+name).show();
}

//隐藏执行部门级别以下的信息
function removedetail (name){
	$("tr[name ^= '"+name+"']").hide();
	$("#remove"+name).hide();
	$("#add"+name).show();
}

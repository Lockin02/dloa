$(document).ready(function() {
	$("#menberName").yxselect_user({
		hiddenId : 'menberId',
		mode:"check"
			});
	uploadfile = createSWFUpload({
		"serviceType": "oa_supp_suppasses"
	});


	/**
	 * 验证信息
	 */
	validate({
//		"schemeCode" : {
//			required : true
//		},
		"suppName" : {
			required : true
		}
	});
	$("#newsupp").hide();
	$("#yearsupp").hide();
	$("#assessType").bind('change',function(){//根据不同的考核类型，隐藏或者显示“供应商来源”文本框
			$("#suppId").val("");
			$("#suppName").val("");
			$("#suppLinkName").val("");
			$("#suppTel").val("");
			$("#suppAddress").val("");
			$("#mainProduct").val("");
			$("#suppLinkName").val("");
			$("#suppassesTable").html("");
			$("#assessName").val("");
			$("#assessId").val("");
			$("#assessCode").val("");
			$("#totalNum").val("");
		if($(this).val()=="xgyspg"){
			$("#newsupp").show();
			$("#yearsupp").hide();
		}
		if($(this).val()=="gysjd"){
			$("#newsupp").hide();
			$("#yearsupp").show();
		}
		if($(this).val()=="gysnd"){
			$("#newsupp").hide();
			$("#yearsupp").show();
		}
		if($(this).val()==""){//如果没有选择评估类型，则清空评估方案
			$("#newsupp").hide();
			$("#yearsupp").hide();
			$("#assessName").yxcombogrid_scheme("remove");
			$("#suppName").yxcombogrid_supplier("remove");
			$("#suppassesTable").html("");
		}
		if($(this).val()!=""){
			if($(this).val()=="xgyspg"){
				var suppGrad="E";
			}else{
				var suppGrad="A,B,C";
			}
			var supassType=$(this).val();
			$("#suppName").yxcombogrid_supplier("remove");
			$("#suppName").yxcombogrid_supplier({
						hiddenId : 'suppId',
						gridOptions : {
							showcheckbox : false,
							param:{suppGrade:suppGrad},
							event : {
								'row_dblclick' : function(e, row, data) {
									 $.ajax({//判断是否已进行考核
									    type: "POST",
									    url: "?model=supplierManage_assessment_supasses&action=isAsses",
									    data: { suppId: data.id,
									    		supassType:supassType
									    	},
									    async: false,
									    success: function(msg){
									   	   if(msg==1){
												$("#suppLinkName").val("");
												$("#suppTel").val("");
												$("#suppAddress").val("");
												$("#mainProduct").val("");
												$("#suppLinkName").val("");
					//							$("#suppLinkName").val("");
												//如果供应商存有联系人信息，则默认带出第一个信息
												$.post(
														"?model=supplierManage_formal_sfcontact&action=getLinkmanInfo",
														{
															suppId : data.id
														}, function(linkman) {
															if(linkman){
					        									var o = eval("(" + linkman + ")");
																$("#suppLinkName").val(o.name);
															}
														});
												$("#suppTel").val(data.plane);
												$("#suppAddress").val(data.address+"  "+data.zipCode);
												$("#mainProduct").val(data.products);
									   	   }else{
									   	   		alert("该供应商已进行评估考核");
												$("#suppId").val("");
												$("#suppName").val("");
												$("#suppLinkName").val("");
												$("#suppTel").val("");
												$("#suppAddress").val("");
												$("#mainProduct").val("");
												$("#suppLinkName").val("");
									   	   }
										}
									});
								}
							}
						}
			});
			$("#suppName").yxcombogrid_supplier("showCombo");
			$("#assessName").yxcombogrid_scheme("remove");
			$("#assessName").yxcombogrid_scheme({
						hiddenId : 'assessId',
						width:500,
						gridOptions : {
							showcheckbox : false,
							param:{"schemeType":supassType},
							event : {
								'row_dblclick' : function(e, row, data) {
									$("#assessCode").val(data.schemeCode);
									$("#suppassesTable").html("");
									$("#suppassesTable").yxeditgrid({
											objName : 'supasses[assesmentitem]',
											url : '?model=supplierManage_assessment_assesmentitem&action=addItemJson',
											param:{parentId:data.id},
											isAddAndDel : false,
											colModel : [{
												display : '评估项目',
												name : 'assesProName',
												type : 'statictext'
											},{
												display : '评估项目',
												name : 'assesProName',
												type : 'hidden'
											}, {
												display : '评估指标',
												name : 'assesStandard',
												type : 'statictext'
											},  {
												display : '评估指标',
												name : 'assesStandard',
												type : 'hidden'
											}, {
												display : '指标权重',
												name : 'assesProportion',
												type : 'statictext'
											},  {
												display : '指标权重',
												name : 'assesProportion',
												type : 'hidden'
											}, {
												display : '评估说明',
												name : 'assesExplain',
												type : 'statictext',
												width:550
											}, {
												display : '评估说明',
												name : 'assesExplain',
												type : 'hidden'
											}, {
												display : '得分[*]',
												name : 'assesScore',
												tclass : 'txtshort',
												validation : {
													custom : ['percentageNum']
												},
												event : {
													blur : function() {
														var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
														if (!re.test(this.value)) { //判断是否为数字
															if (isNaN(this.value)) {
																this.value = "";
	//															return false;
															}
														}
														var rownum = $(this).data('rowNum');// 第几行
														var colnum = $(this).data('colNum');// 第几列
														var grid = $(this).data('grid');// 表格组件
														var assesProportion = grid.getCmpByRowAndCol(rownum, 'assesProportion').val();
														var assesScore = $(this).val();
														assesProportion = parseFloat(assesProportion);
														assesScore = parseFloat(assesScore);
														if (assesScore > assesProportion) {
															alert("得分不能超过指标权重："+assesProportion);
															$(this).val("");
														}
														check_all();
													}
												}
											}, {
												display : '评分说明',
												name : 'assesRemark'
											}]
										});
								}
							}
						}
			});
			$("#assessName").yxcombogrid_scheme("showCombo");
		}
	});
  })

  // 计算总分数
	function check_all() {
		var totalNum = 0;
		var cmps = $("#suppassesTable").yxeditgrid("getCmpByCol", "assesScore");
		cmps.each(function() {
			totalNum = accAdd(totalNum, $(this).val(),2);
		});
		$("#totalNum").val(totalNum);
		checkGrade(totalNum);//划分供应商等级
	//	return false;
	}

	//划分供应商等级
	function checkGrade(totalNum){
		var assessType=$("#assessType").val();
		if(assessType=="gysjd"||assessType=="gysnd"){
			if(totalNum>90){
				$("#suppGrade").val("A");
			}else if(totalNum==75||totalNum>75){
				$("#suppGrade").val("B");
			}else if(totalNum>60||totalNum==60){
				$("#suppGrade").val("C");
			}else if(totalNum<60){
				$("#suppGrade").val("D");
			}
		}else if(assessType=="xgyspg"){
			if(totalNum>70||totalNum==70){
				$("#suppGrade").val("C");
			}
		}
	}

  //验证是否选择了评估类型
  function checkSelectSupp(){
	var assessType=$("#assessType").val();
	if(assessType==""){
		alert("请先选择评估类型");
	}
  }

  //验证是否选择了评估类型
  function checkAssesType(){
	var assessType=$("#assessType").val();
	if(assessType==""){
		alert("请先选择评估类型");
	}
  }

  //直接提交审批
function toSubmit(){
	document.getElementById('form1').action = "index1.php?model=supplierManage_assessment_supasses&action=add&actType=audit";
}
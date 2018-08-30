$(document).ready(function() {
	    var parentId=$("#id").val();
		$("#suppassesTable").yxeditgrid({
				objName : 'supasses[assesmentitem]',
				url : '?model=supplierManage_assessment_assesmentitem&action=assesListJson',
				param:{'parentId':parentId,'affstate':'0'},
				isAddAndDel : false,
				colModel : [{
					display : 'id',
					name : 'id',
					type : 'hidden'
				},{
                    display : '评估部门',
                    name : 'assesDept',
                    type : 'txt',
                    width : 100,
                    readonly:true,
                    validation : {
                        required : true
                    },
                    process : function($input) {
                        var rowNum = $input.data("rowNum");
                        $input.yxselect_dept({
                            hiddenId: 'suppassesTable_cmp_assesDeptId'+rowNum
                        });
                    }
                }, {
                    display : '评估部门Id',
                    name : 'assesDeptId',
                    type : 'txt',
                    type:'hidden'
                },{
					display : '评估项目',
					name : 'assesProName',
					type : 'statictext'
				}, {
					display : '评估指标',
					name : 'assesStandard',
					type : 'statictext'
				}, {
					display : '指标权重',
					name : 'assesProportion',
					type : 'statictext'
				},  {
					display : '评估说明',
					name : 'assesExplain',
					type : 'statictext',
					width:450
				}, {
                    display : '负责人',
                    name : 'assesMan',
                    type : 'txt',
                    width : 100,
                    readonly:true,
                    validation : {
                        required : true
                    },
                    process : function($input) {
                        var rowNum = $input.data("rowNum");
                        $input.yxselect_user({
                            mode : 'check',
                            hiddenId: 'suppassesTable_cmp_assesManId'+rowNum
                        });
                    }
                }, {
                    display : '负责人Id',
                    name : 'assesManId',
                    type : 'txt',
                    type:'hidden'
                }
                    , {
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
				}
                ]
			});

  });


  //直接提交审批
function toSubmit(){
	document.getElementById('form1').action = "index1.php?model=supplierManage_assessment_supasses&action=asses&actType=audit";
}

//查看采购订单明细
function toViewPurch(){
    var assessType=$("#assessType").val();
    var suppId=$("#suppId").val();
    if(assessType=='gysjd'||assessType=="gysnd"){
        var assesYear=$("#assesYear").val();
        var assesQuarter=$("#assesQuarter").val();
        showModalWin('?model=purchase_contract_purchasecontract&action=toViewEquList&suppId='+suppId+'&assessType='+assessType+'&assesYear='+assesYear+'&assesQuarter='+assesQuarter,'1');
    }
}
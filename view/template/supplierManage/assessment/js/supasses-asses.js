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
                    display : '��������',
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
                    display : '��������Id',
                    name : 'assesDeptId',
                    type : 'txt',
                    type:'hidden'
                },{
					display : '������Ŀ',
					name : 'assesProName',
					type : 'statictext'
				}, {
					display : '����ָ��',
					name : 'assesStandard',
					type : 'statictext'
				}, {
					display : 'ָ��Ȩ��',
					name : 'assesProportion',
					type : 'statictext'
				},  {
					display : '����˵��',
					name : 'assesExplain',
					type : 'statictext',
					width:450
				}, {
                    display : '������',
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
                    display : '������Id',
                    name : 'assesManId',
                    type : 'txt',
                    type:'hidden'
                }
                    , {
					display : '�÷�[*]',
					name : 'assesScore',
					tclass : 'txtshort',
					validation : {
						custom : ['percentageNum']
					},
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //�ж��Ƿ�Ϊ����
								if (isNaN(this.value)) {
									this.value = "";
//															return false;
								}
							}
							var rownum = $(this).data('rowNum');// �ڼ���
							var colnum = $(this).data('colNum');// �ڼ���
							var grid = $(this).data('grid');// ������
							var assesProportion = grid.getCmpByRowAndCol(rownum, 'assesProportion').val();
							var assesScore = $(this).val();
							assesProportion = parseFloat(assesProportion);
							assesScore = parseFloat(assesScore);
							if (assesScore > assesProportion) {
								alert("�÷ֲ��ܳ���ָ��Ȩ�أ�"+assesProportion);
								$(this).val("");
							}
							check_all();
						}
					}
				}, {
					display : '����˵��',
					name : 'assesRemark'
				}
                ]
			});

  });


  //ֱ���ύ����
function toSubmit(){
	document.getElementById('form1').action = "index1.php?model=supplierManage_assessment_supasses&action=asses&actType=audit";
}

//�鿴�ɹ�������ϸ
function toViewPurch(){
    var assessType=$("#assessType").val();
    var suppId=$("#suppId").val();
    if(assessType=='gysjd'||assessType=="gysnd"){
        var assesYear=$("#assesYear").val();
        var assesQuarter=$("#assesQuarter").val();
        showModalWin('?model=purchase_contract_purchasecontract&action=toViewEquList&suppId='+suppId+'&assessType='+assessType+'&assesYear='+assesYear+'&assesQuarter='+assesQuarter,'1');
    }
}
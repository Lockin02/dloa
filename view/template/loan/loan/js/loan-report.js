var show_page = function(page) {
	$("#loanGrid").yxgrid("reload");
};
$(function() {
	$("#loanGrid").yxgrid({
		model : 'loan_loan_loan',
        action: 'reportPageJson',
		title : '���ͳ�Ʊ�',
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
        isSearch:false,
        leftLayout: true,
        leftLayoutInitClosed: false,
		buttonsEx : [{
			name : 'excel',
			// hide : true,
			text : "����",
			icon : 'excel',
			action : function(row) {
                var i = 1;
                var colId = "";
                var colName = "";
                $("#loanGrid_hTable").children("thead").children("tr")
                    .children("th").each(function () {
                        if ($(this).css("display") != "none"
                            && $(this).attr("colId") != undefined && $(this).attr("axis") != 'col1') {
                            colName += $(this).children("div").html() + ",";
                            colId += $(this).attr("colId") + ",";
                            i++;
                        }
                    })
                var serchVal = ($("#searchType").val() == "deptName")? $("#deptId").val() : $("#searchVal").val();
                window
                    .open("?model=loan_loan_loan&action=exportExcel&colId="
                    + colId
                    + "&colName="
                    + colName
                    + "&searchType="
                    + $("#searchType").val()
                    + "&searchVal="
                    + serchVal
                    + "&company="
                    + $("#company").val()
                    + "&payBegin="
                    + $("#payBegin").val()
                    + "&payEnd="
                    + $("#payEnd").val())
			}
		}],

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'type',
			display : '��ѯ������Ϣ',
			sortable : true,
            process: function (v, row) {
			    var valStr = (v == '')? '-' : v;

                return (row.type == '�ϼ�')? v : "<a href='index1.php?model=loan_loan_loan&action=loanReportDetail&type="+v+"&typeCode="+row.typeCode+"&extParam="+row.extParam+"&deptCode="+row.deptCode+"' target='_blank'>"+valStr+"</a>";
            }
		}, {
			name : 'userNo',
			display : '����',
            process: function (v, row) {
                if(row.typeCode == "debtorName"){
                    return (v == '')? '-' : v;
                }else{
                    return "-";
                }
            }
		}, {
			name : 'amount',
			display : '�ѽ����',
			sortable : true,
			process: function (v, row) {
                if (v == '') {
                    return "<div class='text-right'>0.00</div>";
                } else {
                    return "<div class='text-right'>"+moneyFormat2(v)+"</div>";
                }
            }
		}, {
            name : 'unamount',
            display : '������',
            sortable : true,
            process: function (v, row) {
                if (v == '') {
                    return "<div class='text-right'>0.00</div>";
                } else {
                    return "<div class='text-right'>"+moneyFormat2(v)+"</div>";
                }
            }
        }, {
            name : 'beamount',
            display : '���ڽ�����',
            sortable : true,
            process: function (v, row) {
                if (v == '') {
                    return "<div class='text-right'>0.00</div>";
                } else {
                    return "<div class='text-right'>"+moneyFormat2(v)+"</div>";
                }
            }
        }, {
            name : 'deptamount',
            display : '���Ž�����',
            sortable : true,
            process: function (v, row) {
                if (v == '') {
                    return "<div class='text-right'>0.00</div>";
                } else {
                    return "<div class='text-right'>"+moneyFormat2(v)+"</div>";
                }
            }
        }, {
            name : 'proamount',
            display : '��Ŀ������',
            sortable : true,
            process: function (v, row) {
                if (v == '') {
                    return "<div class='text-right'>0.00</div>";
                } else {
                    return "<div class='text-right'>"+moneyFormat2(v)+"</div>";
                }
            }
        }],

		menusEx: []
	});


    //������
    $("#view").append('<table class="form_main_table"><tbody><tr><td colspan="2"><div><span class="systemView">��ѯ��ͼ</span></div></td></tr>'+
        '<tr><td class="form_text_left" style="min-width: 50px;">��ѯ����</td>' +
        '<td class="form_view_right">' +
        "<select class='selectauto' id='searchType' style='width: 100%;' onchange='changeType()'>" +
        "<option value='Debtor'>�����</option>" +
        "<option value='deptName'>����</option>" +
        "<option value='divisionName'>��ҵ��</option>" +
        "</select></td></tr>" +
        '<tr><td class="form_text_left" style="min-width: 50px;">��ѯ����</td>' +
        '<td><input id="searchVal" class="txt" style="width:60%;" /><input type="hidden" id="deptId"></td></tr>'+
        '<tr id="companySrch"><td class="form_text_left" style="min-width: 50px;">��˾</td>' +
        '<td><input id="company" class="txt" style="width:80%;" /><input id="companyId" type="hidden" style="width:100%;" /></td></tr>'+
        '<tr><td colspan="2" style="text-align:left;background-color:#eff7ff;"><div><span class="">���ɸ���ʱ��</span></div></td></tr>'+
        '<tr><td class="form_text_left" style="min-width: 50px;">��ʼʱ��</td>' +
        '<td><input id="payBegin" style="width:100%;" onfocus="WdatePicker()" /></td></tr>'+
        '<tr><td class="form_text_left" style="min-width: 50px;">����ʱ��</td>' +
        '<td><input id="payEnd" style="width:100%;" onfocus="WdatePicker()"/></td></tr>'+
        '<tr><td colspan="2" ><input type="button" class="txt_btn_a" onclick="searchBtn()" value="��ѯ">&nbsp;&nbsp;&nbsp;<input type="button" class="txt_btn_a" onclick="searchBtn(1)" value="����"></td></tr>'+
        "</tbody></table>");

    $("#searchType").change(function(){
        if($(this).val() != "Debtor"){
            $("#company").val("");
            $("#companyId").val("");
            $("#companySrch").hide();
        }else{
            $("#companySrch").show();
        }
    });

    $("#company").yxcombogrid_branch({
        hiddenId: 'companyId',
        height: 250,
        isFocusoutCheck: false,
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {

                }
            }
        }
    });

    // $("#searchVal").yxselect_user({});
    $("#searchVal").attr("style","width:100%");
});

function changeType(){
    var type = $("#searchType").val();
    $("#searchVal").val("");
    // $("#searchVal").yxselect_user("remove");
    // $("#searchVal").yxselect_dept("remove");

    $("#deptId").val('');
    if(type == 'Debtor'){
        // $("#searchVal").yxselect_user({});
        $("#searchVal").attr("style","width:100%");
    }else if(type=='deptName'){
        // $("#searchVal").yxselect_dept({hiddenId : 'deptId'});
        $("#searchVal").attr("style","width:100%");
    }else{//��ҵ��Ԥ��
        $("#searchVal").attr("style","width:100%");
    }
}

function searchBtn($type){
    var listGrid = $("#loanGrid").data('yxgrid');
    if($type == 1){
        location.reload();
    }else{
        listGrid.options.extParam['searchType'] = $("#searchType").val();
        //listGrid.options.extParam['searchVal']  = ($("#searchType").val() == "deptName")? $("#deptId").val() : $("#searchVal").val();
        listGrid.options.extParam['searchVal']  = $("#searchVal").val();
        listGrid.options.extParam['company']    = $("#company").val();
        listGrid.options.extParam['companyId']    = $("#companyId").val();
        listGrid.options.extParam['payBegin']   = $("#payBegin").val();
        listGrid.options.extParam['payEnd']     = $("#payEnd").val();
        listGrid.options.newp = 1;
    }
    listGrid.reload();
}


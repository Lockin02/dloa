//���ù������
(function ($) {
    var initNum = 0;//��ʼ������,���ڷ�ֹ�༭ҳ���һ�γ�ʼ���������������һ����������
    //Ĭ������
    var defaults = {
        getId: 'id', //ȡ����ѯ����id
        objName: 'objName', //ҵ�����
        actionType: 'add', //�������� add edit view create,Ĭ��add
        url: '', //ȡ��url
        data: {},//��������
        isCompanyReadonly: true, //��˾�Ƿ�ֻ��
        isCompanyDefault: false, //��˾�Ƿ�Ĭ��
        company: '���Ͷ���', //Ĭ�Ϲ�˾ֵ
        companyId: 'dl' //Ĭ�Ϲ�˾ֵ
    };

    //���ù����������� - ���ڻ�������
    var expenseSaleDept;
    var expenseContractDept;

    //================== �ڲ����� ====================//
    //��ʼ����������
    function initCostType(thisObj, objInfo) {
        if (objInfo) {
            var CostDateBegin = objInfo.CostDateBegin;
            var CostDateEnd = objInfo.CostDateEnd;
            var days = objInfo.days;
            var InputManName = objInfo.CostManName;
            var InputMan = objInfo.CostMan;
            var deptId = objInfo.CostDepartID;
            var deptName = objInfo.CostDepartName;
            var companyId = objInfo.CostManComId;
            var companyName = objInfo.CostManCom;
            var purpose = objInfo.Purpose;
            var salesArea = objInfo.salesArea;
            var salesAreaId = objInfo.salesAreaId;
            var module = objInfo.module;
            var feeMan = objInfo.feeMan;
            var feeManId = objInfo.feeManId;
            var memberNames = objInfo.memberNames;
            var memberIds = objInfo.memberIds;
            var memberNumber = objInfo.memberNumber;
        } else {
            var CostDateBegin = CostDateEnd = '';
            var days = '';
            var InputManName = $("#InputManName").val();
            var InputMan = $("#InputMan").val();
            var deptId = $("#deptId").val();
            var deptName = $("#deptName").val();
            var companyId = $("#companyId").val();
            var companyName = $("#companyName").val();
            var templateId = $("#templateId").val();
            var templateName = $("#templateName").val();
            var purpose = '';
            var salesArea = '';
            var salesAreaId = '';
            var module = '';
            var feeMan = $("#InputManName").val();
            var feeManId = $("#InputMan").val();
            var memberNames = '';
            var memberIds = '';
            var memberNumber = '';
        }
        var fileUrl = $("#fileUrl").val();
        var salesAreaStr =
            '<input type="hidden" class="txt" id="salesArea" name="' + defaults.objName + '[salesArea]" value="' + salesArea + '"/>' +
            '<input type="hidden" id="salesAreaId" name="' + defaults.objName + '[salesAreaId]" value="' + salesAreaId + '"/>';

        var tableStr = '<table class="form_in_table" id="' + defaults.myId + 'tbl">' +
            '<tr id="feeTypeTr">' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle" class="red">��ѡ���������</span></td>' +
            '<td class = "form_text_right" colspan="5">' +
            '<input type="radio" name="' + defaults.objName + '[DetailType]" id="dt1" value="1"/><label for="dt1">���ŷ���</label> ' +
            '<input type="radio" name="' + defaults.objName + '[DetailType]" id="dt2" value="2"/><label for="dt2">��ͬ��Ŀ����</label> ' +
            '<input type="radio" name="' + defaults.objName + '[DetailType]" id="dt3" value="3"/><label for="dt3">�з�����</label> ' +
            '<input type="radio" name="' + defaults.objName + '[DetailType]" id="dt4" value="4"/><label for="dt4">��ǰ����</label> ' +
            '<input type="radio" name="' + defaults.objName + '[DetailType]" id="dt5" value="5"/><label for="dt5">�ۺ����</label> ' +
            '&nbsp;&nbsp;' +
            '<img src="images/icon/view.gif"/>' +
            '<a href="#" title="����˵��" taget="_blank" id="fileId" onclick="window.open(\''+fileUrl+'\',\'_blank \');">����˵��</a>' +
            '<span class="blue" id="tipsView"></span>' +
            '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three"><span class="blue">�����ڼ�</span></td>' +
            '<td class="form_text_right" colspan="5">' +
            '<input type="text" class="txtmiddle Wdate" name="' + defaults.objName + '[CostDateBegin]" id="CostDateBegin" onfocus="WdatePicker()" value="' + CostDateBegin + '"/>' +
            '&nbsp;��&nbsp;' +
            '<input type="text" class="txtmiddle Wdate" name="' + defaults.objName + '[CostDateEnd]" id="CostDateEnd" onfocus="WdatePicker()" value="' + CostDateEnd + '"/>' +
            '&nbsp;��&nbsp;' +
            '<input type="text" class="rimless_textB" style="width:50px;text-align:center" name="' + defaults.objName + '[days]" id="days" value="' + days + '"/>' +
            '<input type="hidden" id="periodDays" value="' + days + '"/>' +
            '&nbsp;��' +
            '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three"><span class="blue">�� ��</span></td>' +
            '<td class="form_text_right" colspan="5">' +
            '<input type="text"  class="rimless_textB" style="width:760px" name="' + defaults.objName + '[Purpose]" id="Purpose" value="' + purpose + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr id="baseTr">' +
            '<td class="form_text_left_three"><span class="blue">������Ա</span></td>' +
            '<td class="form_text_right">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[CostManName]" id="CostManName" value="' + InputManName + '" readonly="readonly"/>' +
            '<input type="hidden" name="' + defaults.objName + '[CostMan]" id="CostMan" value="' + InputMan + '"/>' +
            '</td>' +
            '<td class="form_text_left_three">�����˲���</td>' +
            '<td class="form_text_right">' +
            '<input type="text" class="readOnlyTxtNormal" name="' + defaults.objName + '[CostDepartName]" id="CostDepartName" value="' + deptName + '" readonly="readonly"/>' +
            '<input type="hidden" name="' + defaults.objName + '[CostDepartID]" id="CostDepartID" value="' + deptId + '"/>' +
            '</td>' +
            '<td class="form_text_left_three">�����˹�˾</td>' +
            '<td class="form_text_right">' +
            '<input type="text" name="' + defaults.objName + '[CostManCom]" id="CostManCom" class="readOnlyTxtNormal" value="' + companyName + '" readonly="readonly"/>' +
            '<input type="hidden" name="' + defaults.objName + '[CostManComId]" id="CostManComId" value="' + companyId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three">ͬ �� ��</td>' +
            '<td class="form_text_right_three">' +
            '<input type="text" class="txt" readonly="readonly" name="' + defaults.objName + '[memberNames]" id="memberNames" value="' + memberNames + '"/>' +
            '<input type="hidden" name="' + defaults.objName + '[memberIds]" id="memberIds" value="' + memberIds + '"/>' +
            '</td>' +
            '<td class="form_text_left_three">ͬ������</td>' +
            '<td class="form_text_right" colspan="3" id="memberNumberTr">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[memberNumber]" id="memberNumber" value="' + memberNumber + '"/>' +
            salesAreaStr +
            '</td>' +
            '<td class="form_text_left_three" id="feeManTr"><span class="blue">���óе���</span></td>' +
            '<td class="form_text_right_three">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[feeMan]" id="feeMan" value="' + feeMan + '" readonly="readonly"/>' +
            '<input type="hidden" name="' + defaults.objName + '[feeManId]" id="feeManId" value="' + feeManId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr style="display:none;">' +
            '<td class="form_text_left_three"><span class="blue">�������</span></td>' +
            '<td class="form_text_right" colspan="5">' +
            '<select class="txt" name="' + defaults.objName + '[module]" id="module"/>' +
            '</td>' +
            '</tr>';
        //������ȡ
        var fileInfo = '';
        var fileInfoObj = $("#fileInfo");
        if (fileInfoObj.length > 0) {
            fileInfo = fileInfoObj.html();
        }
        if (objInfo) {
            tableStr += '<tr>' +
                '<td class="form_text_left_three">�� ��</td>' +
                '<td class="form_text_right" colspan="5">' +
                '<div class="upload">' +
                '<div class="upload" id="fsUploadProgress"></div>' +
                '<div class="upload"><span id="swfupload"></span>' +
                '<input id="btnCancel" type="button" value="��ֹ�ϴ�" onclick="cancelQueue(uploadfile);" disabled="disabled" /><br />' +
                '</div>' +
                '<div id="uploadfileList" class="upload">' + fileInfo + '</div>' +
                '</div>' +
                '</td>' +
                '</tr>' +
                '</table>';
        } else {
            tableStr += '<tr>' +
                '<td class="form_text_left_three">����ģ��</td>' +
                '<td class="form_text_right">' +
                '<input type="text" class="txt" name="' + defaults.objName + '[ModelTypeName]" id="modelTypeName" value="' + templateName + '" readonly="readonly" style="width: 150px;" />' +
                '<input type="hidden" name="' + defaults.objName + '[modelType]" id="modelType" value="' + templateId + '" />' +
                '<input type="button" class="txt_btn_a" style="margin-left: 15px;" value="ά��ģ��" onclick="toModifyModel()">' +
                '</td>' +
                '<td class="form_text_left_three">�� ��</td>' +
                '<td class="form_text_right" colspan="3">' +
                '<div class="upload">' +
                '<div class="upload" id="fsUploadProgress"></div>' +
                '<div class="upload"><span id="swfupload"></span>' +
                '<input id="btnCancel" type="button" value="��ֹ�ϴ�" onclick="cancelQueue(uploadfile);" disabled="disabled" /><br />' +
                '</div>' +
                '<div id="uploadfileList" class="upload">' + fileInfo + '</div>' +
                '</div>' +
                '</td>' +
                '</tr>' +
                '</table>';
        }
        $(thisObj).html(tableStr);
        $("input[name='" + defaults.objName + "[DetailType]']").each(function () {
            $(this).bind('click', resetCombo);
            //���������Ͱ󶨴����¼�
            switch (this.value) {
                case '1' :
                    $(this).bind('click', initDept);
                    break;
                case '2' :
                    $(this).bind('click', initContractProject);
                    break;
                case '3' :
                    $(this).bind('click', initRdProject);
                    break;
                case '4' :
                    $(this).bind('click', initSale);
                    break;
                case '5' :
                    $(this).bind('click', initContract);
                    break;
                default :
            }
        });

        //ģ����Ⱦ
        $("#modelTypeName").yxcombogrid_expensemodel({
            hiddenId: 'modelType',
            height: 300,
            isShowButton: true,
            isClear: false,
            gridOptions: {
                showcheckbox: false,
                isFocusoutCheck: false,
                event: {
                    row_dblclick: function (e, row, data) {
                        initTemplate(data.modelType);
                        initCostshareTemplate(data.modelType);
                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                    }
                }
            }
        });

        //�ڼ�ʱ���
        $("#CostDateBegin").bind('blur', actTimeCheck);
        $("#CostDateEnd").bind('blur', actTimeCheck);
        $("#days").bind('blur', periodDaysCheck);

        // ���б�����
        if ($("#allApply").val() == "1") {
            $("#CostManName").yxselect_user({
                isGetDept: [true, "CostDepartID", "CostDepartName"],
                hiddenId: 'CostMan',
                formCode: 'expense',
                event: {
                    select: function (obj, row) {
                        if (row != undefined) {
                            $("#CostBelongDeptName").val(row.deptName);
                            $("#CostBelongDeptId").val(row.deptId);
                            // ��˾����
                            $("#CostManComId").val(row.companyCode);
                            $("#CostManCom").val(row.companyName);
                            $("#CostBelongComId").val(row.companyCode);
                            $("#CostBelongCom").val(row.companyName);

                            //��������Ϊ���ŷ���ʱ���������ű����ù������ۺϹ������Ӳ��ţ�Ҫ���������������ŵİ����Ϣ
                            setModule();
                        }
                    }
                }
            });
        }

        // ��Ⱦͬ����
        $("#memberNames").yxselect_user({
            hiddenId: 'memberIds',
            formCode: 'expenseMember',
            mode: 'check',
            event: {
                select: function (obj, row) {
                    if (row != undefined) {
                        if (row.val != '') {
                            var memberArr = row.val.split(',');
                            $("#memberNumber").val(memberArr.length);
                        } else {
                            $("#memberNumber").val('');
                        }
                    }
                },
                clearReturn: function () {
                    $("#memberNumber").val('');
                }
            }
        });

        // ��Ⱦ���
        getAndAddDataToSelect('HTBK', 'module');
        // �������
        $("#module").prepend("<option value=''>��ѡ��</option>");
        // ���ڱ༭ҳ���ʼ�����
        if ($("#moduleVal").val() !== undefined && $("#moduleVal").val() !== "") {
            $("#module").attr('value', $("#moduleVal").val());
        }

        // ����
        if (objInfo) {
            uploadfile = createSWFUpload({
                serviceId: objInfo.ID,
                serviceNo: objInfo.BillNo,
                serviceType: "cost_summary_list"//ҵ��ģ����룬һ��ȡ����
            });
        } else {
            uploadfile = createSWFUpload({
                serviceType: "cost_summary_list"//ҵ��ģ����룬һ��ȡ����
            });
        }

        //���ط��óе���
        $("#feeManTr").hide().next("td").hide();
    }

    //���ø�������
    function resetCombo() {
        $("#detailTypeTitle").html('��������').removeClass('red').addClass('blue');
        $("#projectName").yxcombogrid_esmproject('remove');
        $("#projectCode").yxcombogrid_esmproject('remove');
        $("#costBelongCom").yxcombogrid_branch('remove');

        // alert($("#proManagerName").parent().prev().text());
        if($("#proManagerName").parent().prev().text() == '��Ŀ����'){
            $("#proManagerName").parent().parent().remove();
        }
        
        $(".feeTypeContent").remove();
        //�����������
        $("#salesArea").val('');
        $("#salesAreaId").val('');
        if (defaults.objName == "expense") {
            $('#salesAreaRead').val('');
        }

        //���������
        clearWorkTeam();
        //���÷��ù������Ź�˾��ʶ
        $("#comCode").val($("#comCodeDefault").val());
    }

    //���ù�����
    function initWorkTeam(objInfo) {
        var projectName = '', projectId = '', projectCode = '', proManagerName = '', proManagerId = '', projectType = '';
        if (objInfo) {
            projectName = objInfo.projectName;
            projectId = objInfo.projectId;
            projectCode = objInfo.ProjectNo;
            proManagerName = objInfo.proManagerName;
            proManagerId = objInfo.proManagerId;
            projectType = objInfo.projectType;
        }
        //����ʡ��
        var str = "<td class='form_text_left_three workTeamShow'>" +
            "�� �� ��" +
            "</td>" +
            "<td class='form_text_right_three workTeamShow'>" +
            "<input class='txt' name='" + defaults.objName + "[projectName]' id='projectName' value='" + projectName + "' readonly='readonly'/>" +
            "<input type='hidden' name='" + defaults.objName + "[projectId]' id='projectId' value='" + projectId + "'/>" +
            "<input type='hidden' name='" + defaults.objName + "[ProjectNo]' id='projectCode' value='" + projectCode + "'/>" +
            "<input type='hidden' name='" + defaults.objName + "[proManagerName]' id='proManagerName' value='" + proManagerName + "'/>" +
            "<input type='hidden' name='" + defaults.objName + "[proManagerId]' id='proManagerId' value='" + proManagerId + "'/>" +
            "<input type='hidden' name='" + defaults.objName + "[projectType]' id='projectType' value='" + projectType + "'/>" +
            "</td>";
        //��д���Ÿ�
        $("#memberNumberTr").attr("colspan", 1).attr("class", "form_text_right_three").after(str);
        //������Ŀ��Ⱦ
        $("#projectName").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectName',
            searchName: 'projectName',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {attribute: 'GCXMSS-04', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');
                }
            }
        });
    }

    //���ù�����
    function initWorkTeamView(objInfo) {
        //����ʡ��
        var str = "<td class='form_text_left_three workTeamShow'>" +
            "�� �� ��" +
            "</td>" +
            "<td class='form_text_right_three workTeamShow'>" +
            objInfo.projectName +
            "</td>";
        //��д���Ÿ�
        $("#memberNumberTr").attr("colspan", 1).attr("class", "form_text_right_three").after(str);
    }

    //ȡ��������
    function clearWorkTeam() {
        //��չ���Ÿ�
        $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        $(".workTeamShow").remove();
    }

    /****************************** ��ͬ���õ��� ***********************************/
    //��ʼ������ TODO
    function initDept() {
        if (defaults.objName == "expense") {
            $('.salesAreaWrap').hide();
        }
        var allCompany = $("#allCompany").val();
        var thisClass = !defaults.isCompanyReadonly == true || allCompany == "1" ? "txt" : "readOnlyTxtNormal";
        var thisCompany = defaults.isCompanyDefault == true ? defaults.company : $("#CostManCom").val();
        var thisCompanyId = defaults.isCompanyDefault == true ? defaults.companyId : $("#CostManComId").val();

        //Ĭ�ϻ�ȡ
        var deptId = $("#deptTempId").val();
        var deptName = $("#deptTempName").val();

        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">���ù�����˾</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="' + thisClass + '" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + thisCompany + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
            '<td class = "form_text_right" colspan="3" id="feeDept">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" value="' + deptName + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" value="' + deptId + '"/>' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        if (!defaults.isCompanyReadonly == true || allCompany == "1") {
            //��˾��Ⱦ
            $("#costBelongCom").yxcombogrid_branch({
                hiddenId: 'costBelongComId',
                height: 250,
                gridOptions: {
                    isFocusoutCheck: false,
                    showcheckbox: false
                }
            });
        }
        //���ù�������ѡ��
        $("#costBelongDeptName").yxselect_dept({
            hiddenId: 'costBelongDeptId',
            disableDeptLevel: '1', // ����ѡ��һ������
            unDeptFilter: $('#unDeptFilter').val(),
            unSltDeptFilter: $('#unSltDeptFilter').val(),
            event: {
                selectReturn: function (e, obj) {
                    //ajax��ȡ���۸�����
                    var responseText = $.ajax({
                        url: 'index1.php?model=finance_expense_expense&action=deptIsNeedProvince',
                        data: {deptId: obj.val},
                        type: "POST",
                        async: false
                    }).responseText;
                    //��ʼ��
                    initCheckDept(responseText);
                    // �Զ���ʼ�����
                    setModule();
                }
            }
        });

        //��Ҫ���ż��Ĳ�����Ⱦʡ��
        initCheckDept();
        //��Ⱦ������
        initWorkTeam();
        //������ʾ����
        $("#tipsView").html('');
        //�����������
        $("#module").find("option:eq(0)").attr("selected", true);
        //�������Ĭ�ϴ������ù��������������
        setModule();
        //���ط��óе���
        $("#feeManTr").hide().next("td").hide();
    }

    //����һ����Ҫ���Ĳ�����չ����
    function initCheckDept(deptIsNeedProvince, objInfo) {
        //���û�����ж�ֵ�������л�ȡ
        if (deptIsNeedProvince == undefined) {
            deptIsNeedProvince = $("#deptIsNeedProvince").val();
        } else {
            $("#deptIsNeedProvince").val(deptIsNeedProvince);
        }
        var province = '';
        if (objInfo) {
            province = objInfo.province;
        }
        //�����Ҫ���ż��
        if (deptIsNeedProvince == "1") {

            if ($("#feeDeptProvinceShow").length == 0) {

                //����ʡ��
                var str = "<td class='form_text_left_three' id='feeDeptProvinceShow'><span class='blue'>����ʡ��</span></td><td class='form_text_right_three' id='feeDeptProvince'><input class='txt' name='" + defaults.objName + "[province]' id='province' value='" + province + "' style='width:202px;'/></td>";
                //��д���Ÿ�
                $("#feeDept").attr("colspan", 1).attr("class", "form_text_right_three").after(str);
                $('#province').combobox({
                    url: 'index1.php?model=system_procity_province&action=listJsonSort',
                    valueField: 'provinceName',
                    textField: 'provinceName',
                    editable: false
                });
            }
        } else if (deptIsNeedProvince == "0") {
            //�����Ҫ���ż��
            var provinceObj = $('#province');
            if (provinceObj.length == 1) {
                //���province ֵ
                $('div[value="province"]').remove();
                //��ȥ��̬���
                $("#feeDeptProvinceShow").remove();
                $("#feeDeptProvince").remove();
                //��д���Ÿ�
                $("#feeDept").attr("colspan", 3).attr("class", "form_text_right");
            }
        }
    }

    //������Ҫ���Ĳ�����չ���� - �鿴
    function initCheckDeptView(objInfo) {
        if (objInfo.province) {
            //����ʡ��
            var str = "<td class='form_text_left_three' id='feeDeptProvinceShow'>����ʡ��</td><td class='form_text_right_three' id='feeDeptProvince'>" + objInfo.province + "</td>";
            //��д���Ÿ�
            $("#feeDept").attr("colspan", 1).attr("class", "form_text_right_three").after(str);
        }
    }

    //��ʼ����ͬ��Ŀ
    function initContractProject() {
        if (defaults.objName == "expense") {
            $('.salesAreaWrap').hide();// ������������
        }
        var thisCompany, thisCompanyId;
        if (defaults.isCompanyDefault == true) {
            thisCompany = defaults.company;
            thisCompanyId = defaults.companyId;
        } else {
            var companyName = $("#companyName");
            thisCompany = companyName.length == 0 ? $("#CostManCom").val() : companyName.val();
            thisCompanyId = companyName.length == 0 ? $("#CostManComId").val() : $("#companyId").val();
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[ProjectNo]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" />' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" id="projectType"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" />' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" />' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" />' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">��Ŀ��֧</td>' +
            '<td class="form_text_right_three" id="projectOverspend">-' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀʡ��</span></td>' +
            '<td class = "form_text_right" colspan="3">' +
            '<input type="text" class="readOnlyTxtNormal" id="proProvince" name="' + defaults.objName + '[proProvince]" readonly="readonly"/>' +
            '<input type="hidden" id="proProvinceId" name="' + defaults.objName + '[proProvinceId]" />' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        $("#projectName").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectName',
            searchName: 'projectName',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {attribute: 'GCXMSS-02', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        $("#projectId").val(data.id);
                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // ��Ŀʡ��
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        });

        //������Ŀ��Ⱦ
        $("#projectCode").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectCode',
            searchName: 'projectCode',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {attribute: 'GCXMSS-02', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        $("#projectId").val(data.id);
                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // ��Ŀʡ��
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        });
        //������ʾ����
        $("#tipsView").html('');
        //�����������
        $("#module").find("option:eq(0)").attr("selected", true);
        //�������Ĭ�ϴ������ù��������������
        setModule();
        //��չ���Ÿ�
        $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        //���ط��óе���
        $("#feeManTr").hide().next("td").hide();
    }

    //��ʼ���з���Ŀ
    function initRdProject() {
        if (defaults.objName == "expense") {
            $('.salesAreaWrap').hide();// ������������
        }
        var thisCompany, thisCompanyId;
        if (defaults.isCompanyDefault == true) {
            thisCompany = defaults.company;
            thisCompanyId = defaults.companyId;
        } else {
            var companyName = $("#companyName");
            thisCompany = companyName.length == 0 ? $("#CostManCom").val() : companyName.val();
            thisCompanyId = companyName.length == 0 ? $("#CostManComId").val() : $("#companyId").val();
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[ProjectNo]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" />' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" id="projectType"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" />' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" />' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" />' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">��Ŀ��֧</td>' +
            '<td class="form_text_right_three" id="projectOverspend">-' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        //�з���Ŀ��Ⱦ
        $("#projectName").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectName',
            searchName: 'projectName',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {attribute: 'GCXMSS-05', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        $("#projectId").val(data.id);
                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        });

        //�з���Ŀ��Ⱦ
        $("#projectCode").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectCode',
            searchName: 'projectCodeSearch',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {attribute: 'GCXMSS-05', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        $("#projectId").val(data.id);
                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        });
        //������ʾ����
        $("#tipsView").html('');
        //�����������
        $("#module").find("option:eq(0)").attr("selected", true);
        //�������Ĭ�ϴ������ù��������������
        setModule();
        //��չ���Ÿ�
        $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        //���ط��óе���
        $("#feeManTr").hide().next("td").hide();
    }

    //��ʼ����ǰ
    function initSale() {
        var thisCompany, thisCompanyId;
        if (defaults.isCompanyDefault == true) {
            thisCompany = defaults.company;
            thisCompanyId = defaults.companyId;
        } else {
            var companyName = $("#companyName");
            thisCompany = companyName.length == 0 ? $("#CostManCom").val() : companyName.val();
            thisCompanyId = companyName.length == 0 ? $("#CostManComId").val() : $("#companyId").val();
        }
        var salesAreaStrNew = '';
        if (defaults.objName == "expense") {// ���������ù���������� ����PMS2383
            salesAreaStrNew =
                '<td class="form_text_left_three salesAreaWrap blue" style="display:none">���ù�������</td>' +
                '<td class="form_text_right_three salesAreaWrap" style="display:none">' +
                '<select id="salesAreaOpt" class="txt" style="display:none"></select>' +
                '<input type="text" class="readOnlyTxtNormal" id="salesAreaRead" value="" readonly="readonly"/>' +
                '</td>';
        }

        var costBelongComChangeLimit = ($("#allCompanySq").val() == 1);
        var costBelongComStr = costBelongComChangeLimit?
            '<input type="text" class="txt"  id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + thisCompany + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + thisCompanyId + '"/>' :
            '<input type="text" class="readOnlyTxtNormal"  id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + thisCompany + '" readonly/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + thisCompanyId + '"/>';

        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">������Ŀ���</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[ProjectNo]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three">������Ŀ����</td>' +
            '<td class = "form_text_right_three" colspan="3">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" />' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" />' +
            '</td>' +
            '</tr><tr>' +
            '<td class = "form_text_left_three">��Ŀ����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" />' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀʡ��</span></td>' +
            '<td class = "form_text_right" colspan="3">' +
            '<input type="text" class="readOnlyTxtNormal" id="proProvince" name="' + defaults.objName + '[proProvince]" readonly="readonly"/>' +
            '<input type="hidden" id="proProvinceId" name="' + defaults.objName + '[proProvinceId]" />' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">�̻����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="chanceCode" name="' + defaults.objName + '[chanceCode]"/>' +
            '<input type="hidden" id="chanceId" name="' + defaults.objName + '[chanceId]" />' +
            '</td>' +
            '<td class = "form_text_left_three">�̻�����</td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="chanceName" name="' + defaults.objName + '[chanceName]"/>' +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="customerName" name="' + defaults.objName + '[customerName]"/>' +
            '<input type="hidden" id="customerId" name="' + defaults.objName + '[customerId]" />' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">�ͻ�ʡ��</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="province" name="' + defaults.objName + '[province]" style="width:202px;"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">�ͻ�����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="city" name="' + defaults.objName + '[city]" style="width:202px;"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">�ͻ�����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="customerType" name="' + defaults.objName + '[CustomerType]" style="width:202px;"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            salesAreaStrNew +
            '<td class = "form_text_left_three"><span class="blue">���۸�����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="' + defaults.objName + '[CostBelonger]" readonly="readonly"/>' +
            '<input type="hidden" class="ciClass" id="costBelongerId" name="' + defaults.objName + '[CostBelongerId]" />' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" style="width:202px;"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" />' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">�ͻ�����</td>' +
            '<td class="form_text_right_three">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[customerDept]" id="customerDept"/>' +
            '</td>' +
            '<td class="form_text_left_three">��Ŀ��֧</td>' +
            '<td class="form_text_right_three" id="projectOverspend">-' +
            '</td>' +
            '<td class="form_text_left_three"><span class="blue">���ù�����˾</span></td>' +
            '<td class="form_text_right_three" id="costBelongComWrap">' + costBelongComStr +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        if (defaults.objName == "expense") {
            //��������
            $('#salesAreaOpt').html('');
            $('#salesAreaOpt').hide();
            $('#salesAreaRead').show();
            $('.salesAreaWrap').show();// ��ʾ��������
        }

        if(costBelongComChangeLimit && $("#costBelongCom").val() != undefined){
            //��˾
            $("#costBelongCom").yxcombogrid_branch({
                hiddenId: 'costBelongComId',
                height: 250,
                isFocusoutCheck: false,
                gridOptions: {
                    showcheckbox: false,
                    event: {
                    }
                }
            }).attr('class', 'txt');
        }

        //�̻����
        var codeObj = $("#chanceCode");
        if (codeObj.attr('wchangeTag2')) {
            return false;
        }
        var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻����'>&nbsp;</span>");
        $button.click(function () {
            if (codeObj.val() == "") {
                alert('������һ���̻����');
                return false;
            }
        });

        //�����հ�ť
        var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
        $button2.click(function () {
            if (codeObj.val() != "") {
                //���������Ϣ
                clearSale();
                openInput('chance');
                //���óе��˴���
                dealFeeMan('');
            }
        });
        codeObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

        //�̻�����
        var nameObj = $("#chanceName");
        if (nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻�����'>&nbsp;</span>");
        $button.click(function () {
            if (nameObj.val() == "") {
                alert('������һ���̻�����');
                return false;
            }
        });

        //�����հ�ť
        $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
        $button2.click(function () {
            if (nameObj.val() != "") {
                //���������Ϣ
                clearSale();
                openInput('chance');
                //���óе��˴���
                dealFeeMan('');
            }
        });
        nameObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

        //������Ŀ��Ⱦ
        $("#projectName").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectName',
            searchName: 'projectName',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {contractType: 'GCXMYD-04', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        //�����������
                        closeInput('trialPlan', data.id);

                        $("#projectId").val(data.id);
                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // ��Ŀʡ��
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

                        //�������÷��ù�������
                        $('#costBelongDeptName').combobox({
                            data: [{text: data.deptName, value: data.deptId}],
                            valueField: 'text',
                            textField: 'text',
                            editable: false,
                            onSelect: function (obj) {
                                $("#costBelongDeptId").val(obj.value);
                            }
                        }).combobox('setValue', data.deptName);
                        $("#costBelongDeptId").val(data.deptId);

                        //��ѯʹ����Ŀ��Ϣ
                        var trialProjectInfo = getTrialProject(data.contractId);
                        if (trialProjectInfo) {
                            if (trialProjectInfo.chanceCode != '') {
                                $("#chanceCode").val(trialProjectInfo.chanceCode);
                                getChanceInfo('trialPlan');
                            } else {
                                $("#chanceId").val('');
                                $("#chanceCode").val('');
                                $("#chanceName").val('');
                                $("#customerName").val(trialProjectInfo.customerName);
                                $("#customerId").val(trialProjectInfo.customerId);
                                $("#province").combobox('setValue', trialProjectInfo.province);
                                $("#customerType").combobox('setValue', trialProjectInfo.customerTypeName);

                                //���۸�����
                                var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
                                if (defaults.objName == "expense" && costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
                                    $("#costBelonger").val(trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                } else {
                                    $('#costBelonger').combobox({
                                        valueField: 'text',
                                        textField: 'text',
                                        editable: false,
                                        data: [{text: trialProjectInfo.applyName, value: trialProjectInfo.applyNameId}],
                                        onSelect: function (obj) {
                                            $("#costBelongerId").val(obj.value);
                                        }
                                    }).combobox('setValue', trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                }

                                //���ؿͻ�����
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        //���óе��˴���
                        dealFeeMan(data.deptId);
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');
                    clearSale();
                    //�����������
                    openInput('trialPlan');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    //���óе��˴���
                    dealFeeMan('');
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        }).attr('class', 'txt');

        //��Ŀ���
        $("#projectCode").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectCode',
            searchName: 'projectCodeSearch',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {contractType: 'GCXMYD-04', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        //�����������
                        closeInput('trialPlan', data.id);

                        $("#projectId").val(data.id);
                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // ��Ŀʡ��
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

                        //�������÷��ù�������
                        $('#costBelongDeptName').combobox({
                            data: [{text: data.deptName, value: data.deptId}],
                            valueField: 'text',
                            textField: 'text',
                            editable: false,
                            onSelect: function (obj) {
                                $("#costBelongDeptId").val(obj.value);
                            }
                        }).combobox('setValue', data.deptName);
                        $("#costBelongDeptId").val(data.deptId);

                        //��ѯʹ����Ŀ��Ϣ
                        var trialProjectInfo = getTrialProject(data.contractId);
                        if (trialProjectInfo) {
                            if (trialProjectInfo.chanceCode != '') {
                                $("#chanceCode").val(trialProjectInfo.chanceCode);
                                getChanceInfo('trialPlan');
                            } else {
                                $("#chanceId").val('');
                                $("#chanceCode").val('');
                                $("#chanceName").val('');
                                $("#customerName").val(trialProjectInfo.customerName);
                                $("#customerId").val(trialProjectInfo.customerId);
                                $("#province").combobox('setValue', trialProjectInfo.province);
                                $("#customerType").combobox('setValue', trialProjectInfo.customerTypeName);

                                //���۸�����
                                var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
                                if (defaults.objName == "expense" && costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
                                    $("#costBelonger").val(trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                } else {
                                    $('#costBelonger').combobox({
                                        valueField: 'text',
                                        textField: 'text',
                                        editable: false,
                                        data: [{text: trialProjectInfo.applyName, value: trialProjectInfo.applyNameId}],
                                        onSelect: function (obj) {
                                            $("#costBelongerId").val(obj.value);
                                        }
                                    }).combobox('setValue', trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                }

                                //���ؿͻ�����
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        //���óе��˴���
                        dealFeeMan(data.deptId);
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');
                    clearSale();
                    //�����������
                    openInput('trialPlan');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    //���óе��˴���
                    dealFeeMan('');
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        }).attr('class', 'txt');

        //��ʼ���ͻ�
        initCustomer();

        //�ͻ�������Ⱦ
        $('#customerType').combobox({
            url: 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function () {
                //�������۸�����
                changeCustomerType();
            },
            onUnselect: function () {
                //�������۸�����
                changeCustomerType();
            }
        });

        //ʡ����Ⱦ
        var cityObj = $('#city');
        $('#province').combobox({
            url: 'index1.php?model=system_procity_province&action=listJsonSort',
            valueField: 'provinceName',
            textField: 'provinceName',
            editable: false,
            onSelect: function (obj) {
                //����ʡ�ݶ�ȡ����
                cityObj.combobox({
                    url: "?model=system_procity_city&action=listJson&tProvinceName=" + obj.provinceName
                });

                //����������
                setSalesArea();
            }
        });

        //������Ⱦ
        cityObj.combobox({
            textField: 'cityName',
            valueField: 'cityName',
            editable: false,
            onSelect: function () {
                //�������۸�����
                changeCustomerType();
            },
            onUnselect: function () {
                //�������۸�����
                changeCustomerType();
            }
        });

        //���ù�������
        if (expenseSaleDept == undefined) {
            //ajax��ȡ���۸�����
            var responseText = $.ajax({
                url: 'index1.php?model=finance_expense_expense&action=getSaleDept&detailType=4',
                type: "POST",
                async: false
            }).responseText;
            expenseSaleDept = eval("(" + responseText + ")");
        }
        $('#costBelongDeptName').combobox({
            data: expenseSaleDept,
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                $("#costBelongDeptId").val(obj.value);
                //�������Ĭ�ϴ������ù��������������
                setModule();
                //���óе��˴���
                dealFeeMan(obj.value);
            }
        });
        //����˵��
        $("#tipsView").html('����ʾ���̻����/����¼����ɺ�ϵͳ���Զ�������Ӧ��Ϣ��');
        //�������Ĭ�ϴ������ù��������������
        setModule();
        //��չ���Ÿ�
        $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        //��ʾ���óе���
        $("#feeManTr").hide().next("td").hide();
    }

    //��ʼ���ۺ�
    function initContract() {
        if (defaults.objName == "expense") {
            $('.salesAreaWrap').hide();// ������������
        }
        var thisCompany, thisCompanyId;
        if (defaults.isCompanyDefault == true) {
            thisCompany = defaults.company;
            thisCompanyId = defaults.companyId;
        } else {
            var companyName = $("#companyName");
            thisCompany = companyName.length == 0 ? $("#CostManCom").val() : companyName.val();
            thisCompanyId = companyName.length == 0 ? $("#CostManComId").val() : $("#companyId").val();
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">��ͬ���</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt ciClass" id="contractCode" name="' + defaults.objName + '[contractCode]"/>' +
            '<input type="hidden" class="ciClass" id="contractId" name="' + defaults.objName + '[contractId]" />' +
            '<input type="hidden" class="ciClass" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" class="ciClass" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��ͬ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt ciClass" id="contractName" name="' + defaults.objName + '[contractName]"/>' +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="customerName" name="' + defaults.objName + '[customerName]" readonly="readonly"/>' +
            '<input type="hidden" class="ciClass" id="customerId" name="' + defaults.objName + '[customerId]" />' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">�ͻ�ʡ��</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="province" name="' + defaults.objName + '[province]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="city" name="' + defaults.objName + '[city]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="customerType" name="' + defaults.objName + '[CustomerType]" readonly="readonly"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">�ͻ�����</td>' +
            '<td class="form_text_right_three">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[customerDept]" id="customerDept"/>' +
            '</td>' +
            '<td class = "form_text_left_three">���۸�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="' + defaults.objName + '[CostBelonger]" readonly="readonly"/>' +
            '<input type="hidden" class="ciClass" id="costBelongerId" name="' + defaults.objName + '[CostBelongerId]" />' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" style="width:202px;"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" />' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        //���ù�������
        if (expenseContractDept == undefined) {
            //ajax��ȡ���۸�����
            var responseText = $.ajax({
                url: 'index1.php?model=finance_expense_expense&action=getSaleDept&detailType=5',
                type: "POST",
                async: false
            }).responseText;
            expenseContractDept = eval("(" + responseText + ")");
        }
        $('#costBelongDeptName').combobox({
            data: expenseContractDept,
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                $("#costBelongDeptId").val(obj.value);
                //�������Ĭ�ϴ������ù��������������
                setModule();
                //���óе��˴���
                dealFeeMan(obj.value);
            }
        });

        //���������Ⱦ
        var codeObj = $("#contractCode");
        if (codeObj.attr('wchangeTag2')) {
            return false;
        }
        var $button = $("<span class='search-trigger' id='contractCodeSearch' title='��ͬ���'>&nbsp;</span>");
        $button.click(function () {
            if (codeObj == "") {
                alert('������һ����ͬ���');
                return false;
            }
        });

        //�����հ�ť
        var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
        $button2.click(function () {
            $(".ciClass").val('');
        });
        codeObj.bind('blur', getContractInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true);

        //����������Ⱦ
        var nameObj = $("#contractName");
        if (nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        $button = $("<span class='search-trigger' id='contractCodeSearch' title='��ͬ����'>&nbsp;</span>");
        $button.click(function () {
            if (nameObj.val() == "") {
                alert('������һ����ͬ����');
                return false;
            }
        });

        //�����հ�ť
        $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
        $button2.click(function () {
            $(".ciClass").val('');
        });
        nameObj.bind('blur', getContractInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true);
        //����˵��
        $("#tipsView").html('����ʾ����ͬ���/����¼����ɺ�ϵͳ���Զ�������Ӧ��Ϣ��');
        //�����������
        $("#module").find("option:eq(0)").attr("selected", true);
        //�������Ĭ�ϴ������ù��������������
        setModule();
        //��չ���Ÿ�
        $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        //��ʾ���óе���
        $("#feeManTr").hide().next("td").hide();
    }

    //�첽ƥ���ͬ��Ϣ
    function getContractInfo() {
        var contractCode = $("#contractCode").val();
        var contractName = $("#contractName").val();
        if (contractCode == "" && contractName == "") {
            return false;
        }
        $.ajax({
            type: "POST",
            url: "?model=contract_contract_contract&action=ajaxGetContract",
            data: {contractCode: contractCode, contractName: contractName},
            async: false,
            success: function (data) {
                if (data) {
                    var dataArr = eval("(" + data + ")");
                    if (dataArr.thisLength * 1 > 1) {
                        alert('ϵͳ�д��ڡ�' + dataArr.thisLength + '��������Ϊ��' + contractName + '���ĺ�ͬ����ͨ����ͬ���ƥ���ͬ��Ϣ��');
                        $(".ciClass").val('');
                    } else {
                        $("#contractCode").val(dataArr.contractCode);
                        $("#contractId").val(dataArr.id);
                        $("#contractName").val(dataArr.contractName);
                        $("#customerId").val(dataArr.customerId);
                        $("#customerName").val(dataArr.customerName);
                        $("#customerType").val(dataArr.customerTypeName);
                        $("#province").val(dataArr.contractProvince);
                        $("#city").val(dataArr.contractCity);
                        $("#costBelonger").val(dataArr.prinvipalName);
                        $("#costBelongerId").val(dataArr.prinvipalId);
                        $("#salesArea").val(dataArr.areaName);
                        if (defaults.objName == "expense") {
                            $('#salesAreaRead').val(dataArr.areaName);
                        }
                        $("#salesAreaId").val(dataArr.areaCode);
                        $("#module").val(dataArr.module);
                        $("#moduleName").val(dataArr.moduleName);
                    }
                } else {
                    alert('û�в�ѯ����غ�ͬ��Ϣ');
                    $(".ciClass").val('');
                }
            }
        });
    }

    //��ʼ���ͻ�
    function initCustomer() {
        //���Ƴ�
        $("#customerName").yxcombogrid_customer('remove').yxcombogrid_customer({
            hiddenId: 'customerId',
            height: 300,
            gridOptions: {
                showcheckbox: false,
                event: {
                    row_dblclick: function (e, row, data) {
                        //�ر��������
                        closeInput('customer');

                        $("#province").combobox('setValue', data.Prov);

                        var customerTypeName = getDataByCode(data.TypeOne);
                        $("#customerType").combobox('setValue', customerTypeName);

                        //���ؿͻ�����
                        $("#city").combobox({
                            url: "?model=system_procity_city&action=listJson&tProvinceName=" + data.Prov
                        }).combobox('setValue', data.City);

                        //���۸�����
                        var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
                        if (defaults.objName == "expense" && costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
                            $("#costBelonger").val(data.AreaLeader);
                            $("#costBelongerId").val(data.AreaLeaderId);
                        } else {
                            $('#costBelonger').combobox({
                                valueField: 'text',
                                textField: 'text',
                                editable: false,
                                data: [{text: data.AreaLeader, value: data.AreaLeaderId}],
                                onSelect: function (obj) {
                                    $("#costBelongerId").val(obj.value);
                                }
                            }).combobox('setValue', data.AreaLeader);
                            $("#costBelongerId").val(data.AreaLeaderId);
                        }

                        // ��������
                        setSalesArea();
                    }
                }
            },
            event: {
                clear: function () {
                    clearSale();

                    //�����������
                    openInput('customer');
                }
            }
        }).attr('readonly', false).attr('class', 'txt');
    }

    //��ȡ�̻���Ϣ
    function getChanceInfo(thisType) {
        if ($("#projectCode").val() != "" && typeof(thisType) == 'object') {
            return false;
        }
        var chanceCode = $("#chanceCode").val();
        var chanceName = $("#chanceName").val();
        if (chanceCode == "" && chanceName == "") {
            return false;
        }
        $.ajax({
            type: "POST",
            url: "?model=projectmanagent_chance_chance&action=ajaxChanceByCode",
            data: {chanceCode: chanceCode, chanceName: chanceName},
            async: false,
            success: function (data) {
                if (data) {
                    var dataArr = eval("(" + data + ")");
                    if (dataArr.thisLength * 1 > 1) {
                        alert('ϵͳ�д��ڡ�' + dataArr.thisLength + '��������Ϊ��' + chanceName + '�����̻�����ͨ���̻����ƥ���̻���Ϣ��');
                        clearSale();
                    } else {
                        if (typeof(thisType) == 'object') {
                            //�ر��������
                            closeInput('chance');
                        }
                        //�̻���Ϣ��ֵ
                        chanceSetValue(dataArr, thisType);
                    }
                } else {
                    alert('û�в�ѯ������̻���Ϣ');
                    clearSale();
                }
            }
        });
    }

    //���������Ϣ
    function clearSale() {
        //���ʡ�пͻ�����
        clearPCC();

        $("#chanceName").val('');
        $("#chanceId").val('');
        $("#chanceCode").val('');
        $("#customerName").val('');
        $("#customerId").val('');

        if (defaults.objName == "expense") {
            $("#salesAreaId").val('');
            $("#salesArea").val('');
            $("#salesAreaRead").val('');

            // ��������ѡ���,���ָ������
            $('#salesAreaOpt').html('');
            $('#salesAreaOpt').hide();
            $('#salesAreaRead').show();
        }

        //���÷��ù�������
        if (isCombobox('costBelonger') == 1) {
            $("#costBelonger").combobox("setValue", '');
            $("#costBelongerId").val('');
        } else {
            $("#costBelonger").val('');
            $("#costBelongerId").val('');
        }
    }

    //�ж϶����combobox�Ƿ��Ѵ���
    function isCombobox(objCode) {
        if ($("#" + objCode).attr("comboname")) {
            return 1;
        } else {
            return 0;
        }
    }

    //��տͻ�ʡ�ݡ����С��ͻ�����ϵ��
    function clearPCC() {
        //���ʡ����Ϣ
        $("#province").combobox('setValue', '');
        //��տͻ�������Ϣ
        $("#customerType").combobox("setValue", '');
        //��ճ���
        $("#city").combobox("setValue", '');
        $("input[id^='city_']").attr('checked', false);
    }

    // �����������
    function closeInput(thisType, projectId) {
        //��Ŀid��ȡ
        if (projectId == undefined) {
            projectId = $("#projectId").val();//��Ŀid
        }
        //���û���������ͣ��������ж�
        if (thisType == undefined) {
            var chanceId = $("#chanceId").val();//�̻�id
            var customerId = $("#customerId").val();//�ͻ�id
            if (projectId != "" && projectId != 0) {
                thisType = 'trialPlan';
            } else if (chanceId != "" && chanceId != 0) {
                thisType = 'chance';
            } else if (customerId != "" && customerId != 0) {
                thisType = 'customer';
            }
        }
        if (thisType == 'trialPlan') {
            $("#chanceCode").attr("class", 'readOnlyTxtNormal').attr('readonly', true);
            $("#chanceName").attr("class", 'readOnlyTxtNormal').attr('readonly', true);
            $("#customerName").attr("class", 'readOnlyTxtNormal').yxcombogrid_customer('remove').attr('readonly', true);

            //����̻�����Ⱦ
            clearInputSet('chanceCode');
            clearInputSet('chanceName');
        } else if (thisType == 'customer') {
            //��Ŀ
            $("#projectCode").attr("class", 'readOnlyTxtNormal').attr('readonly', true).yxcombogrid_esmproject('remove');
            $("#projectName").attr("class", 'readOnlyTxtNormal').attr('readonly', true).yxcombogrid_esmproject('remove');

            //�̻�
            $("#chanceCode").attr("class", 'readOnlyTxtNormal').attr('readonly', true);
            $("#chanceName").attr("class", 'readOnlyTxtNormal').attr('readonly', true);

            //����̻�����Ⱦ
            clearInputSet('chanceCode');
            clearInputSet('chanceName');
        } else if (thisType == 'chance') {
            //��Ŀ
            $("#projectCode").attr("class", 'readOnlyTxtNormal').attr('readonly', true).yxcombogrid_esmproject('remove');
            $("#projectName").attr("class", 'readOnlyTxtNormal').attr('readonly', true).yxcombogrid_esmproject('remove');
            $("#customerName").attr("class", 'readOnlyTxtNormal').attr('readonly', true).yxcombogrid_customer('remove');
        }
    }

    //�����������
    function openInput(thisType) {
        if (thisType == 'trialPlan') {
            //����ʵ�����ͻ�ѡ��
            initCustomer();

            //���ù�������
            $('#costBelongDeptName').combobox({
                data: expenseSaleDept,
                valueField: 'text',
                textField: 'text',
                editable: false,
                onSelect: function (obj) {
                    $("#costBelongDeptId").val(obj.value);
                    //���óе��˴���
                    dealFeeMan(obj.value);
                }
            }).combobox('setValue', '');
            $("#costBelongDeptId").val('');

            //�̻����
            var codeObj = $("#chanceCode");
            if (codeObj.attr('wchangeTag2') == true) {
                return false;
            }
            var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻����'>&nbsp;</span>");
            $button.click(function () {
                if (codeObj.val() == "") {
                    alert('������һ���̻����');
                    return false;
                }
            });

            //�����հ�ť
            var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
            $button2.click(function () {
                if (codeObj.val() != "") {
                    //���������Ϣ
                    clearSale();
                    openInput('chance');
                    //���óе��˴���
                    dealFeeMan('');
                }
            });
            codeObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

            //�̻�����
            var nameObj = $("#chanceName");
            if (nameObj.attr('wchangeTag2') == true) {
                return false;
            }
            $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻�����'>&nbsp;</span>");
            $button.click(function () {
                if (nameObj.val() == "") {
                    alert('������һ���̻�����');
                    return false;
                }
            });

            //�����հ�ť
            $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
            $button2.click(function () {
                if (nameObj.val() != "") {
                    //���������Ϣ
                    clearSale();
                    openInput('chance');
                    //���óе��˴���
                    dealFeeMan('');
                }
            });
            nameObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');
        } else if (thisType == 'customer') {
            //��Ŀ
            initTrialproject();

            $("#customerName").attr("class", 'txt').attr('readonly', false);

            //�̻����
            var codeObj = $("#chanceCode");
            if (codeObj.attr('wchangeTag2') == true) {
                return false;
            }
            var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻����'>&nbsp;</span>");
            $button.click(function () {
                if (codeObj.val() == "") {
                    alert('������һ���̻����');
                    return false;
                }
            });

            //�����հ�ť
            var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
            $button2.click(function () {
                if (codeObj.val() != "") {
                    //���������Ϣ
                    clearSale();
                    openInput('chance');
                }
            });
            codeObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

            //�̻�����
            var nameObj = $("#chanceName");
            if (nameObj.attr('wchangeTag2') == true) {
                return false;
            }
            $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻�����'>&nbsp;</span>");
            $button.click(function () {
                if (nameObj.val() == "") {
                    alert('������һ���̻�����');
                    return false;
                }
            });

            //�����հ�ť
            $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
            $button2.click(function () {
                if (nameObj.val() != "") {
                    //���������Ϣ
                    clearSale();
                    openInput('chance');
                }
            });
            nameObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');
        } else if ((typeof(thisType) == "object" && thisType.data == 'chance') || thisType == 'chance') {
            //���ù�������
            $('#costBelongDeptName').combobox({
                data: expenseSaleDept,
                valueField: 'text',
                textField: 'text',
                editable: false,
                onSelect: function (obj) {
                    $("#costBelongDeptId").val(obj.value);
                    //���óе��˴���
                    dealFeeMan(obj.value);
                }
            }).combobox('setValue', '');
            $("#costBelongDeptId").val('');

            //��Ŀ
            initTrialproject();

            //����ʵ�����ͻ�ѡ��
            initCustomer();
        }

        //��ʾʡ�ݵ�������
        $("#province").combobox('enable');
        $('#city').combobox('enable');
        $("#customerType").combobox('enable');
        if (defaults.objName == "expense" && costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
            $("#costBelonger").combobox('enable');
        }
    }

    //���������Ⱦ
    function clearInputSet(thisId) {
        //��Ⱦһ��ƥ�䰴ť
        var thisObj = $("#" + thisId);
        //ȥ����һ����ť
        var $button = thisObj.next();
        thisObj.width(thisObj.width() + $button.width()).attr("wchangeTag2", false);
        $button.remove();

        //ȥ���ڶ�����ť
        $button = thisObj.next();
        thisObj.width(thisObj.width() + $button.width()).attr("wchangeTag2", false);
        $button.remove();
    }

    //������Ŀ��Ⱦ -- ������Ŀ
    function initTrialproject() {
        $("#projectCode").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectCode',
            searchName: 'projectCodeSearch',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {contractType: 'GCXMYD-04', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        //�����������
                        closeInput('trialPlan', data.id);

                        $("#projectId").val(data.id);
                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        //�������÷��ù�������
                        $('#costBelongDeptName').combobox({
                            data: [{text: data.deptName, value: data.deptId}],
                            valueField: 'text',
                            textField: 'text',
                            editable: false,
                            onSelect: function (obj) {
                                $("#costBelongDeptId").val(obj.value);
                                //�������Ĭ�ϴ������ù��������������
                                setModule();
                            }
                        }).combobox('setValue', data.deptName);
                        $("#costBelongDeptId").val(data.deptId);

                        //��ѯʹ����Ŀ��Ϣ
                        var trialProjectInfo = getTrialProject(data.contractId);
                        if (trialProjectInfo) {
                            if (trialProjectInfo.chanceCode != '') {
                                $("#chanceCode").val(trialProjectInfo.chanceCode);
                                getChanceInfo('trialPlan');
                            } else {
                                $("#chanceId").val('');
                                $("#chanceCode").val('');
                                $("#chanceName").val('');
                                $("#customerName").val(trialProjectInfo.customerName);
                                $("#customerId").val(trialProjectInfo.customerId);
                                $("#province").combobox('setValue', trialProjectInfo.province);
                                $("#customerType").combobox('setValue', trialProjectInfo.customerTypeName);

                                //���۸�����
                                var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
                                if (defaults.objName == "expense" && costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
                                    $("#costBelonger").val(trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                } else {
                                    $('#costBelonger').combobox({
                                        valueField: 'text',
                                        textField: 'text',
                                        editable: false,
                                        data: [{text: trialProjectInfo.applyName, value: trialProjectInfo.applyNameId}],
                                        onSelect: function (obj) {
                                            $("#costBelongerId").val(obj.value);
                                        }
                                    }).combobox('setValue', trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                }
                                //���ؿͻ�����
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');
                    clearSale();

                    //�����������
                    openInput('trialPlan');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        }).attr('class', 'txt');

        $("#projectName").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectName',
            searchName: 'projectName',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {contractType: 'GCXMYD-04', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        //�����������
                        closeInput('trialPlan', data.id);

                        $("#projectId").val(data.id);
                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        //�������÷��ù�������
                        $('#costBelongDeptName').combobox({
                            data: [{text: data.deptName, value: data.deptId}],
                            valueField: 'text',
                            textField: 'text',
                            editable: false,
                            onSelect: function (obj) {
                                $("#costBelongDeptId").val(obj.value);
                                //�������Ĭ�ϴ������ù��������������
                                setModule();
                            }
                        }).combobox('setValue', data.deptName);
                        $("#costBelongDeptId").val(data.deptId);

                        //��ѯʹ����Ŀ��Ϣ
                        var trialProjectInfo = getTrialProject(data.contractId);
                        if (trialProjectInfo) {
                            if (trialProjectInfo.chanceCode != '') {
                                $("#chanceCode").val(trialProjectInfo.chanceCode);
                                getChanceInfo('trialPlan');
                            } else {
                                $("#chanceId").val('');
                                $("#chanceCode").val('');
                                $("#chanceName").val('');
                                $("#customerName").val(trialProjectInfo.customerName);
                                $("#customerId").val(trialProjectInfo.customerId);
                                $("#province").combobox('setValue', trialProjectInfo.province);
                                $("#customerType").combobox('setValue', trialProjectInfo.customerTypeName);

                                //���۸�����
                                var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
                                if (defaults.objName == "expense" && costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
                                    $("#costBelonger").val(trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                } else {
                                    $('#costBelonger').combobox({
                                        valueField: 'text',
                                        textField: 'text',
                                        editable: false,
                                        data: [{text: trialProjectInfo.applyName, value: trialProjectInfo.applyNameId}],
                                        onSelect: function (obj) {
                                            $("#costBelongerId").val(obj.value);
                                        }
                                    }).combobox('setValue', trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                }

                                //���ؿͻ�����
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');
                    clearSale();

                    //�����������
                    openInput('trialPlan');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        }).attr('class', 'txt');
    }

    //ѡ��ͻ�����
    function changeCustomerType(thisType) {
        var chanceId = $("#chanceId").val();
        var customerId = $("#customerId").val();
        var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
        if ((chanceId == "" || chanceId == '0') && (customerId == "" || customerId == '0') && costType != 4) {
            var customerType = $('#customerType').combobox('getValue');//�ͻ�����
            var province = $('#province').combobox('getValue');//ʡ��
            var city = $('#city').combobox('getValues').toString();//����

            if (province && city && customerType) {
                //ajax��ȡ���۸�����
                var responseText = $.ajax({
                    url: 'index1.php?model=system_saleperson_saleperson&action=getSalePerson',
                    data: {province: province, city: city, customerTypeName: customerType},
                    type: "POST",
                    async: false
                }).responseText;

                //�з���ֵ
                if (responseText != "") {
                    var dataArr = eval("(" + responseText + ")");
                    var costBelongerObj = $('#costBelonger');
                    costBelongerObj.combobox({
                        valueField: 'areaName',
                        textField: 'areaName',
                        data: dataArr,
                        editable: false,
                        onSelect: function (obj) {
                            $("#costBelongerId").val(obj.areaNameId);
                            //ajax��ȡ�������
                            var responseText = $.ajax({
                                url: 'index1.php?model=system_region_region&action=ajaxConModule',
                                data: {
                                    province: province,
                                    city: city,
                                    customerTypeName: customerType,
                                    personId: obj.areaNameId
                                },
                                type: "POST",
                                async: false
                            }).responseText;
                            if (responseText != "") {
                                var dataArr = eval("(" + responseText + ")");
                                $("#module").find("option:[text='" + dataArr[0].moduleName + "']").attr("selected", true);
                            }
                        }
                    });
                    if (thisType != 'init') {
                        costBelongerObj.combobox('setValue', '');
                        $("#costBelongerId").val('');
                    }
                }
            }
        } else if (thisType == 'init' && costType != 4) {
            var costBelongObj = $("#costBelonger");
            //���۸�����
            costBelongObj.combobox({
                valueField: 'text',
                textField: 'text',
                editable: false,
                data: [{text: costBelongObj.val(), value: $("#costBelongerId").val()}],
                onSelect: function (obj) {
                    $("#costBelongerId").val(obj.value);
                }
            });
        }
        // ����������
        setSalesArea();
    }

    //ajax��ȡ������Ŀ������Ϣ
    function getTrialProject(id) {
        var data = $.ajax({
            type: "POST",
            url: "?model=projectmanagent_trialproject_trialproject&action=ajaxGetInfo",
            data: {id: id},
            async: false
        }).responseText;
        return data != "" ? eval('(' + data + ")") : false;
    }

    //�̻���ֵ��Ϣ
    function chanceSetValue(dataArr, thisType) {
        $("#chanceCode").val(dataArr.chanceCode);
        $("#chanceId").val(dataArr.id);
        $("#chanceName").val(dataArr.chanceName);
        $("#customerId").val(dataArr.customerId);
        $("#customerName").val(dataArr.customerName);

        $("#province").combobox('setValue', dataArr.Province);

        //���ؿͻ�����
        reloadCity(dataArr.Province, dataArr.City);
        $("#city").combobox('setValue', dataArr.City);

        //�ͻ�����
        $("#customerType").combobox('setValue', dataArr.customerTypeName);

        //���۸�����
        var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
        if (defaults.objName == "expense" && costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
            $("#costBelonger").val(dataArr.prinvipalName);
            $("#costBelongerId").val(dataArr.prinvipalId);
        } else {
            $('#costBelonger').combobox({
                valueField: 'text',
                textField: 'text',
                editable: false,
                data: [{text: dataArr.prinvipalName, value: dataArr.prinvipalId}],
                onSelect: function (obj) {
                    $("#costBelongerId").val(obj.value);
                }
            }).combobox('setValue', dataArr.prinvipalName);
            $("#costBelongerId").val(dataArr.prinvipalId);
        }

        //����Ǵ�������Ŀ�����ģ�����Ҫ������Ⱦ���ù�������
        if (typeof(thisType) == 'object') {
            //�������÷��ù�������
            $('#costBelongDeptName').combobox({
//				data: [{text: dataArr.prinvipalDept, value: dataArr.prinvipalDeptId}],
                data: expenseSaleDept,
                valueField: 'text',
                textField: 'text',
                editable: false,
                onSelect: function (obj) {
                    $("#costBelongDeptId").val(obj.value);
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    //���óе��˴���
                    dealFeeMan(obj.value);
                }
            }).combobox('setValue', dataArr.prinvipalDept);
            $("#costBelongDeptId").val(dataArr.prinvipalDeptId);
            //�������Ĭ�ϴ������ù��������������
            setModule();
            //���óе��˴���
            dealFeeMan(dataArr.prinvipalDeptId);
            //������ַ��óе��ˣ���Ĭ��Ϊ���۸�����
            if ($("#feeManTr").is(':visible')) {
                $("#feeMan").val(dataArr.prinvipalName);
                $("#feeManId").val(dataArr.prinvipalId);
            }
        }

        // ����������
        setSalesArea(dataArr);
    }

    //�����������
    function reloadCity(provinceName, city) {
        $('#city').combobox({
            url: "?model=system_procity_city&action=listJson&tProvinceName=" + provinceName
        });
    }

    //*********************** �鿴���� *********************/
    //��ʼ����������
    function initCostTypeView(objInfo) {
        if (objInfo.DetailType) {
            //��ʼ����ͬ����
            initViewHead(objInfo);
            switch (objInfo.DetailType) {
                case '1' :
                    initDeptView(objInfo);
                    break;
                case '2' :
                    initProjectView(objInfo);
                    break;
                case '3' :
                    initProjectView(objInfo);
                    break;
                case '4' :
                    initSaleView(objInfo);
                    break;
                case '5' :
                    initContractView(objInfo);
                    break;
                default :
            }
        }
    }

    //��ʼ���鿴ͷ
    function initViewHead(objInfo) {
        var deT = '';
        switch (objInfo.DetailType) {
            case '1' :
                deT = '���ŷ���';
                break;
            case '2' :
                deT = '��ͬ��Ŀ����';
                break;
            case '3' :
                deT = '�з�����';
                break;
            case '4' :
                deT = '��ǰ����';
                break;
            case '5' :
                deT = '�ۺ����';
                break;
            default :
        }
        //������ȡ
        var fileInfo = '';
        var fileInfoObj = $("#fileInfo");
        if (fileInfoObj.length > 0) {
            fileInfo = fileInfoObj.html();
        }
        var isEditPurpose = '';
        if ($("#isEdit").length > 0) {
            isEditPurpose = '<img src="images/changeedit.gif" title="�޸�����" onclick="openSavePurpose()";/>';
        }
        var sourceType = ($("#sourceType").val() != undefined)? $("#sourceType").val() : '';
        var isDiffBillInfoMsg = ($("#isDiffBillInfoMsg").val() != undefined)? "<span style='margin-left: 3px;color:red;'>"+$("#isDiffBillInfoMsg").val()+"</span>" : '';
        var tableStr = '<table class="form_in_table" id="' + defaults.myId + 'tbl">' +
            '<tr id="feeTypeTr">' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle">��������</span></td>' +
            '<td class = "form_text_right">' + deT + '</td>' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle">��Դ</span></td>' +
            '<td class = "form_text_right" colspan="3">' + sourceType + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three">�����ڼ�</td>' +
            '<td class="form_text_right" colspan="5">' +
            '<input type="hidden" id="CostDateBegin" value="' + objInfo.CostDateBegin + '"> <input type="hidden" id="CostDateEnd" value="' + objInfo.CostDateEnd + '">' +
            '<span class="blue">' + objInfo.CostDateBegin + '</span> �� ' +
            '<span class="blue">' + objInfo.CostDateEnd + '</span> �� ' +
            '<span class="blue">' + objInfo.days + '</span>' +
            ' �� <span id="mainTitle"></span>' +
            '<div id="printLink" style="float: right;width:200px;text-align: left;display:none">' +
            '<input type="button" class="txt_btn_a" value=" ��ӡС�� " onclick="window.open(\'?model=cost_bill_billcheck&action=print_bill&billno='+objInfo.BillNo+'\');">&nbsp;&nbsp;' +
            '<input type="button" class="txt_btn_a" value=" ��ӡ�� " onclick="window.open(\'general/costmanage/print/expense/print_bill.php?QR_BillNo='+objInfo.BillNo+'\');">' +
            '</div>' +
            '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three">�� ��</td>' +
            '<td class="form_text_right" colspan="5">' +
            isEditPurpose +
            '<span id="PurposeShow">' + objInfo.Purpose + '<span/>' +
            '</td>' +
            '</tr>' +
            '<tr id="baseTr">' +
            '<td class="form_text_left_three">������Ա</td>' +
            '<td class="form_text_right_three">' +
            objInfo.CostManName + isDiffBillInfoMsg +
            '</td>' +
            '<td class="form_text_left_three">�����˲���</td>' +
            '<td class="form_text_right_three">' +
            objInfo.CostDepartName +
            '</td>' +
            '<td class="form_text_left_three">�����˹�˾</td>' +
            '<td class="form_text_right_three">' +
            objInfo.CostManCom +
            '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three">ͬ �� ��</td>' +
            '<td class="form_text_right_three">' +
            objInfo.memberNames +
            '</td>' +
            '<td class="form_text_left_three">ͬ������</td>' +
            '<td class="form_text_right" id="memberNumberTr">' +
            objInfo.memberNumber +
            '</td>' +
            '<td class="form_text_left_three" id="feeManTr">���óе���</td>' +
            '<td class="form_text_right">' +
            objInfo.feeMan +
            '</td>' +
            '</tr>' +
            '<tr style="display:none;" id="salesAreaTr">' +
            '<td class="form_text_left_three">���ù�������</td>' +
            '<td class="form_text_right">' +
            objInfo.salesArea +
            '</td>' +
            '</tr>' +
            '<tr style="display:none;">' +
            '<td class="form_text_left_three">�������</td>' +
            '<td class="form_text_right" colspan="5">' +
            objInfo.moduleName +
            '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three">�� ��</td>' +
            '<td class="form_text_right_three" colspan="5">' +
            fileInfo +
            '</td>' +
            '</tr>' +
            '</table>';
        $("#" + defaults.myId).html(tableStr);

        if(chkPrintLinkDisplayLimit()){
            $("#printLink").show();
        }
    }

    var chkPrintLinkDisplayLimit = function(){
         //ajax����Ȩ��
            var responseText = $.ajax({
                url: 'index1.php?model=finance_expense_exsummary&action=chkPrintLimit',
                type: "POST",
                async: false
            }).responseText;
            if(responseText === '1'){
                return true;
            }else{
                return false;
            }
    }

    //��ʼ������
    function initDeptView(objInfo) {
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">���ù�����˾</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.CostBelongCom +
            '</td>' +
            '<td class = "form_text_left_three">���ù�������</td>' +
            '<td class = "form_text_right" colspan="3" id="feeDept">' +
            objInfo.CostBelongDeptName +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        //ʡ��
        initCheckDeptView(objInfo);
        //������
        initWorkTeamView(objInfo);
        //���ط��óе���
        $("#feeManTr").hide().next("td").hide();
    }

    //��ʼ����ͬ��Ŀ
    function initProjectView(objInfo) {
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">��Ŀ���</span></td>' +
            '<td class = "form_text_right_three">' +
            objInfo.ProjectNo +
            '</td>' +
            '<td class = "form_text_left_three">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            objInfo.projectName +
            '</td>' +
            '<td class = "form_text_left_three">��Ŀ����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.proManagerName +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);
        //��չ���Ÿ�
        // $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        $("#memberNumberTr").after('<td class = "form_text_left_three">��Ŀʡ��</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.proProvince +
            '</td>');

        //���ط��óе���
        $("#feeManTr").hide().next("td").hide();
    }

    //��ʼ����ǰ
    function initSaleView(objInfo) {
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">������Ŀ���</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.ProjectNo +
            '</td>' +
            '<td class = "form_text_left_three">������Ŀ����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.projectName +
            '</td>' +
            '<td class = "form_text_left_three">��Ŀ����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.proManagerName +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">�̻����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.chanceCode +
            '</td>' +
            '<td class = "form_text_left_three">�̻�����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.chanceName +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.customerName +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">�ͻ�ʡ��</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.province +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.city +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.CustomerType +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">�ͻ�����</td>' +
            '<td class="form_text_right_three">' +
            objInfo.customerDept +
            '</td>' +
            '<td class = "form_text_left_three">���۸�����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.CostBelonger +
            '</td>' +
            '<td class = "form_text_left_three">���ù�������</td>' +
            '<td class = "form_text_right">' +
            objInfo.CostBelongDeptName +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);
        //��ʾ���ù�������
        $("#salesAreaTr").show();

        // ��ǰ��ӹ�����˾��Ϣ
        var belongComStr = '<td class = "form_text_left_three">���ù�����˾</td>' +
            '<td class = "form_text_right">' + objInfo.CostBelongCom + '</td>' +
            '<td class = "form_text_left_three">��Ŀʡ��</td>' +
            '<td class = "form_text_right">' + objInfo.proProvince + '</td>';
        $("#salesAreaTr").append(belongComStr);
    }

    //��ʼ���ۺ�
    function initContractView(objInfo) {
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">��ͬ���</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.contractCode +
            '</td>' +
            '<td class = "form_text_left_three">��ͬ����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.contractName +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.customerName +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">�ͻ�ʡ��</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.province +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.city +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.CustomerType +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">�ͻ�����</td>' +
            '<td class="form_text_right_three">' +
            objInfo.customerDept +
            '</td>' +
            '<td class = "form_text_left_three">���۸�����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.CostBelonger +
            '</td>' +
            '<td class = "form_text_left_three">���ù�������</td>' +
            '<td class = "form_text_right">' +
            objInfo.CostBelongDeptName +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);
        //��ʾ���ù�������
        $("#salesAreaTr").show();
    }

    //********************* �༭���� ************************/
    //��ʼ����������
    function initCostTypeEdit(thisObj, objInfo) {
        initCostType(thisObj, objInfo);
        //��ѡ��ֵ
        $("input[name='" + defaults.objName + "[DetailType]']").each(function () {
            if (this.value == objInfo.DetailType) {
                if (this.value == 4 && defaults.objName == "expense" && defaults.actionType == 'edit') {
                    $('.salesAreaWrap').show();// ��ʾ��������
                }
                $(this).attr("checked", this);
                return false;
            }
        });
        $("#detailTypeTitle").html('��������').removeClass('red').addClass('blue');
        switch (objInfo.DetailType) {
            case '1' :
                initDeptEdit(objInfo);
                break;
            case '2' :
                initContractProjectEdit(objInfo);
                break;
            case '3' :
                initRdProjectEdit(objInfo);
                break;
            case '4' :
                initSaleEdit(objInfo);
                break;
            case '5' :
                initContractEdit(objInfo);
                break;
            default :
        }
        // ��Ŀ��֧���
        checkProjectOverspend();
    }

    //��ʼ������
    function initDeptEdit(objInfo) {
        //��ʼֵ����
        var costBelongCom = '', costBelongComId = '', costBelongDeptName = '', costBelongDeptId = '', id = '';
        if (objInfo) {
            costBelongCom = objInfo.CostBelongCom;
            costBelongComId = objInfo.CostBelongComId;
            costBelongDeptName = objInfo.CostBelongDeptName;
            costBelongDeptId = objInfo.CostBelongDeptId;
        }
        var allCompany = $("#allCompany").val();
        var thisClass = !defaults.isCompanyReadonly == true || allCompany == "1" ? "txt" : "readOnlyTxtNormal";
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">���ù�����˾</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="' + thisClass + '" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + costBelongCom + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + costBelongComId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
            '<td class = "form_text_right" colspan="3" id="feeDept">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" value="' + costBelongDeptName + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);
        if (defaults.isCompanyReadonly != true || allCompany == "1") {
            //��˾��Ⱦ
            $("#costBelongCom").yxcombogrid_branch({
                hiddenId: 'costBelongComId',
                height: 250,
                gridOptions: {
                    isFocusoutCheck: true,
                    showcheckbox: false
                }
            });
        }
        //���ù�������ѡ��
        $("#costBelongDeptName").yxselect_dept({
            hiddenId: 'costBelongDeptId',
            disableDeptLevel: '1', // ����ѡ��һ������
            unDeptFilter: $('#unDeptFilter').val(),
            unSltDeptFilter: $('#unSltDeptFilter').val(),
            event: {
                selectReturn: function (e, obj) {
                    //ajax��ȡ���۸�����
                    var responseText = $.ajax({
                        url: 'index1.php?model=finance_expense_expense&action=deptIsNeedProvince',
                        data: {deptId: obj.val},
                        type: "POST",
                        async: false
                    }).responseText;
                    //��ʼ��
                    initCheckDept(responseText);
                    //�������Ĭ�ϴ������ù��������������
                    if (obj.dept.comCode == 'zh') {//�������ű����ù������ۺϹ������Ӳ��ţ�Ҫ���������������ŵİ����Ϣ
                        if ($("#CostDepartID").val() != '') {
                            setModule($("#CostDepartID").val());
                        }
                        //���÷��ù������Ź�˾��ʶ
                        $("#comCode").val('zh');
                    } else {
                        setModule();
                    }
                }
            }
        });

        //��Ҫ���ż��Ĳ�����Ⱦʡ��
        initCheckDept(undefined, objInfo);
        //��Ⱦ������
        initWorkTeam(objInfo);
        //������ʾ����
        $("#tipsView").html('');
    }

    //��ʼ����ͬ��Ŀ
    function initContractProjectEdit(objInfo) {
        //��ʼֵ����
        var projectName = '', projectCode = '', projectId = '', costBelongDeptName = '', costBelongDeptId = '', proManagerName = '', proManagerId = '', id = '';
        var projectType = '', CostBelongCom = '', CostBelongComId = '', proProvince = '', proProvinceId = '';
        if (objInfo) {
            projectName = objInfo.projectName;
            projectCode = objInfo.ProjectNo;
            projectId = objInfo.projectId;
            projectType = objInfo.projectType;
            costBelongDeptName = objInfo.CostBelongDeptName;
            costBelongDeptId = objInfo.CostBelongDeptId;
            proManagerName = objInfo.proManagerName;
            proManagerId = objInfo.proManagerId;
            proProvince = objInfo.proProvince;
            proProvinceId = objInfo.proProvinceId;
            CostBelongCom = objInfo.CostBelongCom;
            CostBelongComId = objInfo.CostBelongComId;
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[ProjectNo]" readonly="readonly" value="' + projectCode + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly" value="' + projectName + '"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" value="' + projectId + '"/>' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" value="' + projectType + '"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" value="' + costBelongDeptName + '"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + CostBelongCom + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + CostBelongComId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly" value="' + proManagerName + '"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" value="' + proManagerId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">��Ŀ��֧</td>' +
            '<td class="form_text_right_three" id="projectOverspend">-' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀʡ��</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proProvince" name="' + defaults.objName + '[proProvince]" readonly="readonly" value="' + proProvince + '"/>' +
            '<input type="hidden" id="proProvinceId" name="' + defaults.objName + '[proProvinceId]" value="' + proProvinceId + '"/>' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        // ��Ŀѡ��
        $("#projectName").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectName',
            searchName: 'projectName',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {attribute: 'GCXMSS-02', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        $("#projectId").val(data.id);
                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // ��Ŀʡ��
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);

                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        });

        // ��Ŀѡ��
        $("#projectCode").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectCode',
            searchName: 'projectCodeSearch',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {attribute: 'GCXMSS-02', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        $("#projectId").val(data.id);
                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // ��Ŀʡ��
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);

                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        });
        //������ʾ����
        $("#tipsView").html('');
    }

    //��ʼ���з���Ŀ
    function initRdProjectEdit(objInfo) {
        //��ʼֵ����
        var projectName = '', projectCode = '', projectId = '', costBelongDeptName = '', costBelongDeptId = '', proManagerName = '', proManagerId = '', id = '', projectType = '';
        var CostBelongCom = '', CostBelongComId = '';
        if (objInfo) {
            projectName = objInfo.projectName;
            projectCode = objInfo.ProjectNo;
            projectId = objInfo.projectId;
            projectType = objInfo.projectType;
            costBelongDeptName = objInfo.CostBelongDeptName;
            costBelongDeptId = objInfo.CostBelongDeptId;
            proManagerName = objInfo.proManagerName;
            proManagerId = objInfo.proManagerId;
            CostBelongCom = objInfo.CostBelongCom;
            CostBelongComId = objInfo.CostBelongComId;
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[ProjectNo]" readonly="readonly" value="' + projectCode + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly" value="' + projectName + '"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" value="' + projectId + '"/>' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" value="' + projectType + '"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" value="' + costBelongDeptName + '"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + CostBelongCom + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + CostBelongComId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly" value="' + proManagerName + '"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" value="' + proManagerId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">��Ŀ��֧</td>' +
            '<td class="form_text_right_three" id="projectOverspend">-' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        //�з���Ŀ��Ⱦ
        $("#projectName").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectName',
            searchName: 'projectName',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {attribute: 'GCXMSS-05', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        $("#projectId").val(data.id);
                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        });

        //�з���Ŀ��Ⱦ
        $("#projectCode").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectCode',
            searchName: 'projectCodeSearch',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {attribute: 'GCXMSS-05', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        $("#projectId").val(data.id);
                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        });
        //������ʾ����
        $("#tipsView").html('');
    }

    //��ʼ����ǰ����
    function initSaleEdit(objInfo) {
        //��ʼֵ����
        var projectName = '', projectCode = '', projectId = '', projectType = '', costBelongDeptName = '', costBelongDeptId = '';
        var proManagerName = '', proManagerId = '', proProvince = '', proProvinceId = '', chanceCode = '', chanceName = '', id = '', customerDept = '', costBelongComId = '', costBelongCom = '';
        var chanceId = '', customerName = '', customerId = '', province = '', city = '', customerType = '', costBelonger = '', costBelongerId = '';
        if (objInfo) {
            projectName = objInfo.projectName;
            projectCode = objInfo.ProjectNo;
            projectId = objInfo.projectId;
            projectType = objInfo.projectType;
            costBelongDeptName = objInfo.CostBelongDeptName;
            costBelongDeptId = objInfo.CostBelongDeptId;
            costBelongComId = objInfo.CostBelongComId;
            costBelongCom = objInfo.CostBelongCom;
            proManagerName = objInfo.proManagerName;
            proManagerId = objInfo.proManagerId;
            proProvince = objInfo.proProvince;
            proProvinceId = objInfo.proProvinceId;
            chanceCode = objInfo.chanceCode;
            chanceName = objInfo.chanceName;
            chanceId = objInfo.chanceId;
            customerName = objInfo.customerName;
            customerId = objInfo.customerId;
            province = objInfo.province;
            city = objInfo.city;
            customerType = objInfo.CustomerType;
            costBelonger = objInfo.CostBelonger;
            costBelongerId = objInfo.CostBelongerId;
            customerDept = objInfo.customerDept;
        }
        var salesAreaStrNew = '';
        if (defaults.objName == "expense") {// ���������ù���������� ����PMS2383
            salesAreaStrNew =
                '<td class="form_text_left_three salesAreaWrap blue">���ù�������</td>' +
                '<td class="form_text_right_three salesAreaWrap">' +
                '<select id="salesAreaOpt" class="txt" style="display:none"></select>' +
                '<input type="text" class="readOnlyTxtNormal" id="salesAreaRead" value="' + objInfo.salesArea + '" readonly="readonly"/>' +
                '</td>';
        }

        var costBelongComChangeLimit = ($("#allCompanySq").val() == 1);
        var costBelongComStr = costBelongComChangeLimit?
        '<input type="text" class="txt"  id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + costBelongCom + '" readonly="readonly"/>' +
        '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + costBelongComId + '"/>' :
        '<input type="text" class="readOnlyTxtNormal"  id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + costBelongCom + '" readonly/>' +
        '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + costBelongComId + '"/>';

        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">������Ŀ���</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[ProjectNo]" readonly="readonly" value="' + projectCode + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">������Ŀ����</td>' +
            '<td class = "form_text_right_three" colspan="3">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly" value="' + projectName + '"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" value="' + projectId + '"/>' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" value="' + projectType + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">��Ŀ����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" value="' + proManagerName + '" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" value="' + proManagerId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">��Ŀʡ��</td>' +
            '<td class = "form_text_right_three" colspan="3">' +
            '<input type="text" class="readOnlyTxtNormal" id="proProvince" name="' + defaults.objName + '[proProvince]" value="' + proProvince + '" readonly="readonly"/>' +
            '<input type="hidden" id="proProvinceId" name="' + defaults.objName + '[proProvinceId]" value="' + proProvinceId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">�̻����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="chanceCode" name="' + defaults.objName + '[chanceCode]" value="' + chanceCode + '"/>' +
            '<input type="hidden" id="chanceId" name="' + defaults.objName + '[chanceId]" value="' + chanceId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">�̻�����</td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="chanceName" name="' + defaults.objName + '[chanceName]" value="' + chanceName + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="customerName" name="' + defaults.objName + '[customerName]" value="' + customerName + '"/>' +
            '<input type="hidden" id="customerId" name="' + defaults.objName + '[customerId]" value="' + customerId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">�ͻ�ʡ��</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="province" name="' + defaults.objName + '[province]" value="' + province + '" style="width:202px;"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">�ͻ�����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="city" name="' + defaults.objName + '[city]" value="' + city + '" style="width:202px;"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">�ͻ�����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="customerType" name="' + defaults.objName + '[CustomerType]" value="' + customerType + '" style="width:202px;"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            salesAreaStrNew +
            '<td class = "form_text_left_three"><span class="blue">���۸�����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="' + defaults.objName + '[CostBelonger]" value="' + costBelonger + '" readonly="readonly"/>' +
            '<input type="hidden" class="ciClass" id="costBelongerId" name="' + defaults.objName + '[CostBelongerId]" value="' + costBelongerId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" value="' + costBelongDeptName + '" style="width:202px;"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">�ͻ�����</td>' +
            '<td class="form_text_right_three">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[customerDept]" id="customerDept" value="' + customerDept + '"/>' +
            '</td>' +
            '<td class="form_text_left_three">��Ŀ��֧</td>' +
            '<td class="form_text_right_three" id="projectOverspend">-' +
            '</td>' +
            '<td class="form_text_left_three"><span class="blue">���ù�����˾</span></td>' +
            '<td class="form_text_right_three" id="costBelongComWrap">' + costBelongComStr +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        //�̻����
        var codeObj = $("#chanceCode");
        if (codeObj.attr('wchangeTag2') == true) {
            return false;
        }
        var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻����'>&nbsp;</span>");
        $button.click(function () {
            if (codeObj.val() == "") {
                alert('������һ���̻����');
                return false;
            }
        });

        //�����հ�ť
        var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
        $button2.click(function () {
            if (codeObj.val() != "") {
                //���������Ϣ
                clearSale();
                openInput('chance');
                //���óе��˴���
                dealFeeMan('');
            }
        });
        codeObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

        if(costBelongComChangeLimit && $("#costBelongCom").val() != undefined){
            //��˾
            $("#costBelongCom").yxcombogrid_branch({
                hiddenId: 'costBelongComId',
                height: 250,
                isFocusoutCheck: false,
                gridOptions: {
                    showcheckbox: false,
                    event: {
                    }
                }
            }).attr('class', 'txt');
        }

        //�̻�����
        var nameObj = $("#chanceName");
        if (nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻�����'>&nbsp;</span>");
        $button.click(function () {
            if (nameObj.val() == "") {
                alert('������һ���̻�����');
                return false;
            }
        });

        //�����հ�ť
        $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
        $button2.click(function () {
            if (nameObj.val() != "") {
                //���������Ϣ
                clearSale();
                openInput('chance');
                //���óе��˴���
                dealFeeMan('');
            }
        });
        nameObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

        var projectCodeObj = $("#projectCode");

        //������Ŀ��Ⱦ
        $("#projectName").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectName',
            searchName: 'projectName',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: false,
                autoload: false,
                param: {contractType: 'GCXMYD-04', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        //�����������
                        closeInput('trialPlan', data.id);

                        $("#projectId").val(data.id);
                        projectCodeObj.val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // ��Ŀʡ��
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

                        //�������÷��ù�������
                        $('#costBelongDeptName').combobox({
                            data: [{text: data.deptName, value: data.deptId}],
                            valueField: 'text',
                            textField: 'text',
                            editable: false,
                            onSelect: function (obj) {
                                $("#costBelongDeptId").val(obj.value);
                            }
                        }).combobox('setValue', data.deptName);
                        $("#costBelongDeptId").val(data.deptId);

                        //��ѯʹ����Ŀ��Ϣ
                        var trialProjectInfo = getTrialProject(data.contractId);
                        if (trialProjectInfo) {
                            if (trialProjectInfo.chanceCode != '') {
                                $("#chanceCode").val(trialProjectInfo.chanceCode);
                                getChanceInfo('trialPlan');
                            } else {
                                $("#chanceId").val('');
                                $("#chanceCode").val('');
                                $("#chanceName").val('');
                                $("#customerName").val(trialProjectInfo.customerName);
                                $("#customerId").val(trialProjectInfo.customerId);
                                $("#province").combobox('setValue', trialProjectInfo.province);
                                $("#customerType").combobox('setValue', trialProjectInfo.customerTypeName);

                                //���۸�����
                                if (defaults.objName == "expense" && costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
                                    $("#costBelonger").val(trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                } else {
                                    $('#costBelonger').combobox({
                                        valueField: 'text',
                                        textField: 'text',
                                        editable: false,
                                        data: [{text: trialProjectInfo.applyName, value: trialProjectInfo.applyNameId}],
                                        onSelect: function (obj) {
                                            $("#costBelongerId").val(obj.value);
                                        }
                                    }).combobox('setValue', trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                }

                                //���ؿͻ�����
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        //���óе��˴���
                        dealFeeMan(data.deptId);
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    projectCodeObj.val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');
                    clearSale();

                    //�����������
                    openInput('trialPlan');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    //���óе��˴���
                    dealFeeMan('');
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        }).attr('class', 'txt');

        //��Ŀ���
        projectCodeObj.yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectCode',
            searchName: 'projectCodeSearch',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {contractType: 'GCXMYD-04', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        //�����������
                        closeInput('trialPlan', data.id);

                        $("#projectId").val(data.id);
                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // ��Ŀʡ��
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

                        //�������÷��ù�������
                        $('#costBelongDeptName').combobox({
                            data: [{text: data.deptName, value: data.deptId}],
                            valueField: 'text',
                            textField: 'text',
                            editable: false,
                            onSelect: function (obj) {
                                $("#costBelongDeptId").val(obj.value);
                            }
                        }).combobox('setValue', data.deptName);
                        $("#costBelongDeptId").val(data.deptId);

                        //��ѯʹ����Ŀ��Ϣ
                        var trialProjectInfo = getTrialProject(data.contractId);
                        if (trialProjectInfo) {
                            if (trialProjectInfo.chanceCode != '') {
                                $("#chanceCode").val(trialProjectInfo.chanceCode);
                                getChanceInfo('trialPlan');
                            } else {
                                $("#chanceId").val('');
                                $("#chanceCode").val('');
                                $("#chanceName").val('');
                                $("#customerName").val(trialProjectInfo.customerName);
                                $("#customerId").val(trialProjectInfo.customerId);
                                $("#province").combobox('setValue', trialProjectInfo.province);
                                $("#customerType").combobox('setValue', trialProjectInfo.customerTypeName);

                                //���۸�����
                                var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
                                if (defaults.objName == "expense" && costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
                                    $("#costBelonger").val(trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                } else {
                                    $('#costBelonger').combobox({
                                        valueField: 'text',
                                        textField: 'text',
                                        editable: false,
                                        data: [{text: trialProjectInfo.applyName, value: trialProjectInfo.applyNameId}],
                                        onSelect: function (obj) {
                                            $("#costBelongerId").val(obj.value);
                                        }
                                    }).combobox('setValue', trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                }

                                //���ؿͻ�����
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                        //�������Ĭ�ϴ������ù��������������
                        setModule();
                        //���óе��˴���
                        dealFeeMan(data.deptId);
                        // ��Ŀ��֧���
                        checkProjectOverspend();
                    }
                }
            },
            event: {
                clear: function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');
                    clearSale();

                    //�����������
                    openInput('trialPlan');
                    //�������Ĭ�ϴ������ù��������������
                    setModule();
                    //���óе��˴���
                    dealFeeMan('');
                    // ��Ŀ��֧���
                    checkProjectOverspend();
                }
            }
        }).attr('class', 'txt');

        //��ʼ���ͻ�
        initCustomer();

        //�ͻ�������Ⱦ
        $('#customerType').combobox({
            url: 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function () {
                //�������۸�����
                changeCustomerType();
            },
            onUnselect: function () {
                //�������۸�����
                changeCustomerType();
            }
        });

        //ʡ����Ⱦ
        var cityObj = $('#city');
        $('#province').combobox({
            url: 'index1.php?model=system_procity_province&action=listJsonSort',
            valueField: 'provinceName',
            textField: 'provinceName',
            editable: false,
            onSelect: function (obj) {
                //����ʡ�ݶ�ȡ����
                cityObj.combobox({
                    url: "?model=system_procity_city&action=listJson&tProvinceName=" + obj.provinceName
                }).combobox("setValue", '');

                //����������
                setSalesArea();
            }
        });

        //������Ⱦ
        cityObj.combobox({
            url: "?model=system_procity_city&action=listJson&tProvinceName=" + province,
            textField: 'cityName',
            valueField: 'cityName',
            editable: false,
            onSelect: function () {
                //�������۸�����
                changeCustomerType();
            },
            onUnselect: function () {
                //�������۸�����
                changeCustomerType();
            }
        }).combobox('setValue', city);

        //���ù�������
        if (expenseSaleDept == undefined) {
            //ajax��ȡ���۸�����
            var responseText = $.ajax({
                url: 'index1.php?model=finance_expense_expense&action=getSaleDept&detailType=4',
                type: "POST",
                async: false
            }).responseText;
            expenseSaleDept = eval("(" + responseText + ")");
        }
        var costBelongDeptNameObj = $('#costBelongDeptName');
        //����ǹ���pk��Ŀ����ô��ʼ��ʱֱ��ȡ���Ĳ�����Ⱦ������ǹ����̻�������ù������ſ�ѡ��
        var dataArr = projectCodeObj.val() == "" ? expenseSaleDept : [{
            text: costBelongDeptNameObj.val(),
            value: $('#costBelongDeptId').val()
        }];
        costBelongDeptNameObj.combobox({
            data: dataArr,
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                $("#costBelongDeptId").val(obj.value);
                //�������Ĭ�ϴ������ù��������������
                setModule();
                //���óе��˴���
                dealFeeMan(obj.value);
            }
        }).combobox('setValue', costBelongDeptNameObj.val());

        //����һ�ν��ô���
        closeInput();
        //����һ���������۸�����
        changeCustomerType('init');
        //����˵��
        $("#tipsView").html('����ʾ���̻����/����¼����ɺ�ϵͳ���Զ�������Ӧ��Ϣ��');
        //���óе��˴���
        dealFeeMan(costBelongDeptId, 'init');
    }

    //��ʼ���ۺ����
    function initContractEdit(objInfo) {
        //��ʼֵ����
        var costBelongDeptName = '', costBelongDeptId = '', customerDept = '', contractCode = '', contractName = '', id = '';
        var contractId = '', customerName = '', customerId = '', province = '', city = '', customerType = '', costBelonger = '', costBelongerId = '';
        var CostBelongCom = '', CostBelongComId = '';
        if (objInfo) {
            costBelongDeptName = objInfo.CostBelongDeptName;
            costBelongDeptId = objInfo.CostBelongDeptId;
            contractCode = objInfo.contractCode;
            contractName = objInfo.contractName;
            contractId = objInfo.contractId;
            customerName = objInfo.customerName;
            customerId = objInfo.customerId;
            province = objInfo.province;
            city = objInfo.city;
            customerType = objInfo.CustomerType;
            costBelonger = objInfo.CostBelonger;
            costBelongerId = objInfo.CostBelongerId;
            customerDept = objInfo.customerDept;
            CostBelongCom = objInfo.CostBelongCom;
            CostBelongComId = objInfo.CostBelongComId;
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">��ͬ���</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt ciClass" id="contractCode" name="' + defaults.objName + '[contractCode]" value="' + contractCode + '"/>' +
            '<input type="hidden" class="ciClass" id="contractId" name="' + defaults.objName + '[contractId]" value="' + contractId + '"/>' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + CostBelongCom + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + CostBelongComId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��ͬ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt ciClass" id="contractName" name="' + defaults.objName + '[contractName]" value="' + contractName + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="customerName" name="' + defaults.objName + '[customerName]" readonly="readonly" value="' + customerName + '"/>' +
            '<input type="hidden" class="ciClass" id="customerId" name="' + defaults.objName + '[customerId]" value="' + customerId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">�ͻ�ʡ��</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="province" name="' + defaults.objName + '[province]" readonly="readonly" value="' + province + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="city" name="' + defaults.objName + '[city]" readonly="readonly" value="' + city + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="customerType" name="' + defaults.objName + '[CustomerType]" readonly="readonly" value="' + customerType + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">�ͻ�����</td>' +
            '<td class="form_text_right_three">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[customerDept]" id="customerDept" value="' + customerDept + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">���۸�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="' + defaults.objName + '[CostBelonger]" readonly="readonly" value="' + costBelonger + '"/>' +
            '<input type="hidden" class="ciClass" id="costBelongerId" name="' + defaults.objName + '[CostBelongerId]" value="' + costBelongerId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" style="width:202px;" value="' + costBelongDeptName + '"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        //���ù�������
        if (expenseContractDept == undefined) {
            //ajax��ȡ���۸�����
            var responseText = $.ajax({
                url: 'index1.php?model=finance_expense_expense&action=getSaleDept&detailType=5',
                type: "POST",
                async: false
            }).responseText;
            expenseContractDept = eval("(" + responseText + ")");
        }
        $('#costBelongDeptName').combobox({
            data: expenseContractDept,
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                $("#costBelongDeptId").val(obj.value);
                //�������Ĭ�ϴ������ù��������������
                setModule();
                //���óе��˴���
                dealFeeMan(obj.value);
            }
        });

        //���������Ⱦ
        var codeObj = $("#contractCode");
        if (codeObj.attr('wchangeTag2') == true) {
            return false;
        }
        var $button = $("<span class='search-trigger' id='contractCodeSearch' title='��ͬ���'>&nbsp;</span>");
        $button.click(function () {
            if (codeObj.val() == "") {
                alert('������һ����ͬ���');
                return false;
            }
        });

        //�����հ�ť
        var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
        $button2.click(function () {
            $(".ciClass").val('');
        });
        codeObj.bind('blur', getContractInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true);

        //����������Ⱦ
        var nameObj = $("#contractName");
        if (nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        $button = $("<span class='search-trigger' id='contractNameSearch' title='��ͬ����'>&nbsp;</span>");
        $button.click(function () {
            if (nameObj.val() == "") {
                alert('������һ����ͬ����');
                return false;
            }
        });

        //�����հ�ť
        $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
        $button2.click(function () {
            $(".ciClass").val('');
        });
        nameObj.bind('blur', getContractInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true);
        //����˵��
        $("#tipsView").html('����ʾ����ͬ���/����¼����ɺ�ϵͳ���Զ�������Ӧ��Ϣ��');
        //���óе��˴���
        dealFeeMan(costBelongDeptId, 'init');
    }

    // ���ݹ���������������۸��������ö�Ӧֵ createdBy haojin 2017-01-24 PMS2418
    function setSalesPerson(personNames, personIds, isEdit) {
        var settedArr = [];
        if (personNames == '' || personIds == '' || personNames == undefined || personIds == undefined) {
            $("#costBelongerOpt").hide();
            $('#costBelonger').show().val('');
            $('#costBelongerId').val('');
        } else if (personNames != undefined) {
            var personNameArr = personNames.split(",");
            var personIdArr = personIds.split(",");
            if (personNameArr.length > 1) {
                var optStr = "";
                $.each(personNameArr, function (i, item) {
                    if ($.inArray(item, settedArr) < 0) {
                        settedArr.push(item);
                        if (isEdit == 1) {
                            if ($('#costBelongerId').val() == personIdArr[i]) {
                                optStr += "<option value='" + personIdArr[i] + "' selected>" + item + "</option>";
                            } else {
                                optStr += "<option value='" + personIdArr[i] + "'>" + item + "</option>";
                            }
                        } else {
                            optStr += "<option value='" + personIdArr[i] + "'>" + item + "</option>";
                            $('#costBelonger').val(personNameArr[0]);
                            $('#costBelongerId').val(personIdArr[0]);
                        }
                    }
                });
                var sltStr = "<select id='costBelongerOpt' class='txt'>" + optStr + "</select>";
                if ($("#costBelongerOpt").attr('class') == undefined) {
                    $('#costBelonger').before(sltStr);
                } else {
                    $("#costBelongerOpt").show().html(optStr);
                }

                $('#costBelonger').hide();
                $("#costBelongerOpt").change(function () {
                    var costBelongerId = $('#costBelongerOpt option:selected').val();
                    var costBelonger = $('#costBelongerOpt option:selected').text();
                    $('#costBelonger').val(costBelonger);
                    $('#costBelongerId').val(costBelongerId);
                });
            } else {
                $("#costBelongerOpt").hide();
                $('#costBelonger').show().val(personNameArr[0]);
                $('#costBelongerId').val(personIdArr[0]);
            }
        }
    }

    // �Զ�������������
    function setSalesArea(chanceArr) {
        var areaCode = '';
        var areaName = '';
        var province = $("#province").combobox('getValue');
        var customerType = $("#customerType").combobox('getValue');
        var businessBelong = $("#CostManCom").val();

        if ((province != "" && customerType != "" && businessBelong != "") || chanceArr) {
            if (chanceArr && defaults.objName == "expense") {
                areaCode = chanceArr.areaCode;
                areaName = chanceArr.areaName;
                customerType = chanceArr.customerTypeName;
                province = chanceArr.Province;
                businessBelong = chanceArr.businessBelongName;
            }
            var areaStr = $.ajax({
                type: "POST",
                url: "?model=system_region_region&action=ajaxConRegionByName",
                data: {
                    customerType: customerType,
                    province: province,
                    businessBelong: businessBelong,
                    needAll: (defaults.objName == "expense") ? 1 : 0
                },
                async: false
            }).responseText;
            if (areaStr != "false") {
                var areaInfo = eval('(' + areaStr + ")");
                if (defaults.objName == "expense" && areaInfo.length > 1 && $('.salesAreaWrap').css('display') != 'none') {//�����ص�������Ϣ���ڶ����ʱ��,��Ϊѡ�����û���ѡ
                    // ��ʾѡ��
                    var optionsStr = '<option value="">..��ѡ��..</option>';
                    if (defaults.actionType == 'edit' && initNum == 0) {
                        initNum += 1;
                        $.each(areaInfo, function () {
                            if ($(this)[0]['id'] == $("#salesAreaId").val()) {
                                optionsStr += '<option value="' + $(this)[0]['id'] + '" data-personNames="' + $(this)[0]['personNames'] + '" data-personIds="' + $(this)[0]['personIds'] + '" selected>' + $(this)[0]['areaName'] + '</option>';
                                setSalesPerson($(this)[0]['personNames'], $(this)[0]['personIds'], 1);
                                // $("#costBelonger").val($(this)[0]['areaPrincipal']);
                                // $("#costBelongerId").val($(this)[0]['areaPrincipalId']);
                            } else {
                                optionsStr += '<option value="' + $(this)[0]['id'] + '" data-personNames="' + $(this)[0]['personNames'] + '" data-personIds="' + $(this)[0]['personIds'] + '">' + $(this)[0]['areaName'] + '</option>';
                            }
                        });
                    } else {
                        $("#costBelonger").val("");
                        $("#costBelongerId").val("");
                        // �����ԭ����������Ϣ
                        $("#salesAreaId").val('');
                        $("#salesArea").val('');
                        $('#salesAreaRead').val('');
                        $.each(areaInfo, function () {
                            if (areaName != '' && chanceArr && areaCode == $(this)[0]['id']) {// ������̻���Ϣ����ƥ�����������Ĭ���̻�������ѡ��
                                $("#salesAreaId").val(areaCode);
                                $("#salesArea").val(areaName);
                                $('#salesAreaRead').val(areaName);
                                optionsStr += '<option value="' + $(this)[0]['id'] + '" data-personNames="' + $(this)[0]['personNames'] + '" data-personIds="' + $(this)[0]['personIds'] + '" selected>' + $(this)[0]['areaName'] + '</option>';
                                setSalesPerson($(this)[0]['personNames'], $(this)[0]['personIds']);
                                // $("#costBelonger").val($(this)[0]['areaPrincipal']);
                                // $("#costBelongerId").val($(this)[0]['areaPrincipalId']);
                            } else {
                                optionsStr += '<option value="' + $(this)[0]['id'] + '" data-personNames="' + $(this)[0]['personNames'] + '" data-personIds="' + $(this)[0]['personIds'] + '">' + $(this)[0]['areaName'] + '</option>';
                            }
                        });
                    }

                    $('#salesAreaOpt').html(optionsStr);
                    $('#salesAreaOpt').change(function () {
                        var salesAreaId = $('#salesAreaOpt option:selected').val();
                        var salesArea = $('#salesAreaOpt option:selected').text();
                        var personNames = (salesAreaId == '') ? '' : $('#salesAreaOpt option:selected').attr("data-personNames");
                        var personIds = (salesAreaId == '') ? '' : $('#salesAreaOpt option:selected').attr("data-personIds");
                        $("#salesAreaId").val(salesAreaId);
                        $("#salesArea").val(salesArea);
                        $("#salesAreaRead").val(salesArea);
                        setSalesPerson(personNames, personIds);
                        // $("#costBelonger").val(areaPrincipal);
                        // $("#costBelongerId").val(areaPrincipalId);
                    });
                    $('#salesAreaOpt').show();
                    $('#salesAreaRead').hide();
                } else {
                    if (defaults.objName == "expense") {
                        // ��������ѡ���
                        $('#salesAreaOpt').html('').hide();
                        // ��ʾ�����,�������Ӧֵ
                        $('#salesAreaRead').show();
                    }
                    $("#salesAreaId").val(areaInfo[0].id);
                    $("#salesArea").val(areaInfo[0].areaName);
                    $("#salesAreaRead").val(areaInfo[0].areaName);
                    setSalesPerson(areaInfo[0].personNames, areaInfo[0].personIds);
                    checkBudget(areaInfo[0].id);
                }
            } else {
                if (defaults.objName == "expense" && $('.salesAreaOpt').css('display') != 'none') {
                    // ��������ѡ���
                    $('#salesAreaOpt').html('').hide();
                    // ��ʾ�����,�������Ӧֵ
                    $('#salesAreaRead').show();
                }
                $("#salesAreaId").val('');
                $("#salesArea").val('');
                if (defaults.objName == "expense") {
                    $("#costBelonger").val("");
                    $("#costBelongerId").val("");
                    $('#salesAreaRead').val('');
                }
            }
        } else {
            if (defaults.objName == "expense" && $('.salesAreaOpt').css('display') != 'none') {
                // ��������ѡ���
                $('#salesAreaOpt').html('').hide();
                // ��ʾ�����,�������Ӧֵ
                $('#salesAreaRead').show();
            }
            $("#salesAreaId").val('');
            $("#salesArea").val('');
            if (defaults.objName == "expense") {
                $("#costBelonger").val("");
                $("#costBelongerId").val("");
                $('#salesAreaRead').val('');
            }
        }
    }

    $.fn.costbelong = function (options) {
        //�ϲ�����
        $.extend(defaults, options);

        //֧��ѡ�����Լ���ʽ����
        return this.each(function () {
            //��ֵһ������
            defaults.myId = this.id;
            var thisObj = $(this);//�Լ��Ķ���

            //�����������,��ô��ȡһ������
            if (defaults.actionType != 'add') {
                //ajax��ȡ���۸�����
                var responseText = $.ajax({
                    url: defaults.url,
                    data: defaults.data,
                    type: "POST",
                    async: false
                }).responseText;
                var objInfo = eval("(" + responseText + ")");
            }
            if (defaults.actionType == 'view') {
                //��ʼ����������
                initCostTypeView(objInfo);
            } else {
                if (defaults.actionType == 'add') {
                    initCostType(thisObj);
                } else if (defaults.actionType == 'edit') {
                    initCostTypeEdit(thisObj, objInfo);
                }
            }
        });
    };

    // ����ǹ����⳵�ķ�������,����⳵����ҳ��Ĳ鿴����
    setTimeout(function(e) {
        if($("#tempExpenseId").val() != undefined && Number($("#tempExpenseId").val()) > 0){
            var tempExpenseId = $("#tempExpenseId").val();
            var typeName = $("#MainTypeName0").text();
            var url = "?model=outsourcing_vehicle_allregister&action=toView&type=viewByExpenseTmpId&tmpId="+tempExpenseId;
            $("#MainTypeName0").html("<a href='"+url+"' target='_blank'>"+typeName+"</a>");
        }
    },200);

})(jQuery);

//�������� �жϱ�����Ԥ�����Լ���Ⱦ����Ƿ񳬱�
function checkBudget(areaId) {
    if (areaId != '') {
        $.ajax({
            type: "POST",
            url: "?model=finance_budget_budgetDetail&action=getByAreaId",
            data: {"areaId": areaId},
            success: function (data) {
                if (data != null) {
                    var obj = JSON.parse(data);
                    var season = currentSeason(); //��ǰ����
                    var seasonFlag = true;
                    var yearFlag = true;

                    //���������
                    var yearTip = $("#budgetYearTip");
                    if (yearTip) {
                        yearTip.remove();
                    }
                    var seasonTip = $("#budgetSeasonTip");
                    if (seasonTip) {
                        seasonTip.remove();
                    }
                    //�ж���Ⱦ����Ƿ񳬳����Ԥ��
                    yearFlag = (obj.totalBudget - obj.final) < 0 ? false : true;
                    //������Ŀ���
                    var projectCode = $("#projectCode").val(); //������Ŀ����Ҫ����Ԥ��
                    if (!yearFlag && projectCode == "") {
                        //�������Ԥ���������������̣�isSpecial�ֶ�Ϊ0
                        var budgetTip = "<div id='budgetYearTip' style='color:red;'><input type='hidden' name='expense[isSpecial]' value='1' />(" + obj.area + "������Ⱦ����ѳ����Ԥ�㣬����������������)</div>";
                        $("#tipsView").append(budgetTip);
                    }

                    //���ݼ����ж��Ƿ񳬳�Ԥ�㣬����������
                    if (season == 1) {
                        var firstBudget = obj.firstBudget;
                        var firstFinal = obj.firstFinal;
                        seasonFlag = (firstBudget - firstFinal) < 0 ? false : true;
                    } else if (season == 2) {
                        var secondBudget = obj.secondBudget;
                        var secondFinal = obj.secondFinal;
                        seasonFlag = (secondBudget - secondFinal) < 0 ? false : true;
                    } else if (season == 3) {
                        var thirdBudget = obj.thirdBudget;
                        var thirdFinal = obj.thirdFinal;
                        seasonFlag = (thirdBudget - thirdFinal) < 0 ? false : true;
                    } else if (season == 4) {
                        var fourthBudget = obj.fourthBudget;
                        var fourthFinal = obj.fourthFinal;
                        seasonFlag = (fourthBudget - fourthFinal) < 0 ? false : true;
                    }
                    if (!seasonFlag && projectCode == "") {
                        var budgetTip = "<div id='budgetSeasonTip' style='color:red;'>(" + obj.area + "���򱾼��Ⱦ����ѳ�����Ԥ��)</div>";
                        $("#tipsView").append(budgetTip);
                    }
                }
            }
        });
    }
}

//��ȡ��ǰ����
function currentSeason() {
    var date = new Date();
    var month = date.getMonth() + 1;
    if (month <= 3 && month >= 1) {
        return 1;
    } else if (month <= 6 && month >= 4) {
        return 2;
    } else if (month <= 9 && month >= 7) {
        return 3;
    } else if (month <= 12 && month >= 10) {
        return 4;
    }
}

function getFeeMansSelectOpts(){
    var opts = "";
    var feemanDefaultId = ($("#feemanDefaultId").val() != undefined)? $("#feemanDefaultId").val() : '';
    if($("#feemansForXtsSales").val() != undefined){
        var feemansForXtsSales = $("#feemansForXtsSales").val();
        var feemansForXtsSalesArr = feemansForXtsSales.split(",");
        if(feemansForXtsSalesArr .length > 0){
            $.each(feemansForXtsSalesArr,function(i,item){
                if(item != ''){
                    var itemArr = item.split(":");
                    if(feemanDefaultId != ''){
                        opts += (itemArr[0] == feemanDefaultId)? "<option value = '"+itemArr[0]+"' data-name = '"+itemArr[1]+"' selected>"+itemArr[1]+"</option>" : "<option value = '"+itemArr[0]+"' data-name = '"+itemArr[1]+"'>"+itemArr[1]+"</option>";
                    }else{
                        opts += (opts == "")? "<option value = '"+itemArr[0]+"' data-name = '"+itemArr[1]+"' selected>"+itemArr[1]+"</option>" : "<option value = '"+itemArr[0]+"' data-name = '"+itemArr[1]+"'>"+itemArr[1]+"</option>";
                    }
                }
            })
        }
    }
    return opts;
}

// ���÷��óе�����Ϣ
function setFeeManInfo(){
    var seletedVal = $("#feeMansSelect").find("option:selected");
    $("#feeManId").val(seletedVal.val());
    $("#feeMan").val(seletedVal.attr('data-name'));
}

//���óе��˴���
function dealFeeMan(deptId, type) {
    //��ȡ���۲�
    var saleDeptIdArr = $("#saleDeptId").val().split(",");
    $("#feeMansSelect").remove();
    $("#feeMan").show();
    if (type != 'init') {//��ʼ����ʱ�򲻸���ԭ����ֵ
        $("#feeManId").val('');
        $("#feeMan").val('');
    }

    //���ù�������Ϊ���۲�������ʾ���óе���
    if(deptId == 329){// ϵͳ������
        //��д���Ÿ�
        $("#memberNumberTr").attr("colspan", 1).attr("class", "form_text_right_three");
        //��ʾ���óе���
        $("#feeManTr").show().next("td").show();
        $("#feeMan").yxselect_user("remove");
        $("#feeMan").val('');
        $("#feeManId").val('');
        $("#feeMan").hide();
        var optsStr = getFeeMansSelectOpts();
        $("#feeMan").before("<select id='feeMansSelect' onchange='setFeeManInfo()' style='width:90px'>"+optsStr+"</select>");
        setFeeManInfo();
        $("#feemanDefaultId").val('');
    }else if (saleDeptIdArr.indexOf(deptId) != -1) {
        //��д���Ÿ�
        $("#memberNumberTr").attr("colspan", 1).attr("class", "form_text_right_three");
        //��ʾ���óе���
        $("#feeManTr").show().next("td").show();
        // ��Ⱦ���óе���
        $("#feeMan").yxselect_user("remove").yxselect_user({
            hiddenId: 'feeManId',
            deptIds: deptId,
            isDeptAddedUser: true,//������Ҫ���������Ա
            isDeptSetUserRange: true,//����������Աѡ��Χ
            event: {
                clearReturn: function () {
                }
            }
        });
        //���ñ��������ڲ�������ù���������ͬ�����Զ���ȡ���ñ�����ԱΪ���óе���
        if (type != 'init') {//��ʼ����ʱ�򲻸���ԭ����ֵ
            if ($("#CostDepartID").val() == deptId) {
                $("#feeMan").val($("#CostManName").val());
                $("#feeManId").val($("#CostMan").val());
            } else {
                $("#feeMan").val("");
                $("#feeManId").val("");
            }
        }
    } else {
        //��չ���Ÿ�
        $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        //���ط��óе���
        $("#feeManTr").hide().next("td").hide();
        //���óе���Ĭ��Ϊ������������
        $("#feeMan").val($("#CostManName").val());
        $("#feeManId").val($("#CostMan").val());
    }
}

// ������Ŀ�Ƿ�֧
function checkProjectOverspend() {
    var projectIdObj = $("#projectId");
    var projectOverspendObj = $("#projectOverspend");
    if (projectIdObj.length == 1 && projectOverspendObj.length == 1) {
        var projectId = projectIdObj.val();
        if (projectId != "0" && projectId != "") {
            $.ajax({
                type: "POST",
                url: "?model=engineering_project_esmproject&action=ajaxGetProject",
                data: {id: projectId},
                dataType: 'json',
                async: false,
                success: function (data) {
                    if (data != null) {
                        if (data.feeAll * 1 > data.budgetAll * 1) {
                            projectOverspendObj.html("<span class='red'>��</span>");
                        } else {
                            projectOverspendObj.html("��");
                        }
                    } else {
                        projectOverspendObj.html("-");
                    }
                }
            });
        } else {
            projectOverspendObj.html("-");
        }
    }
}

function toModifyModel(){
    showThickboxWin("?model=finance_expense_expense&action=toModifyModel"
        + "&placeValuesBefore&TB_iframe=true&modal=false&height=650&width=1100")
}
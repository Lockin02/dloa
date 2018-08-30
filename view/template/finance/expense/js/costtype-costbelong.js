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
        isCompanyReadonly: false, //��˾�Ƿ�ֻ��
        company: '���Ͷ���', //Ĭ�Ϲ�˾ֵ
        companyId: 'dl', //Ĭ�Ϲ�˾ֵ
        isRequired: true,//�Ƿ���ù�������
        costType: ''// ��������
    };

    //���ù����������� - ���ڻ�������
    var expenseSaleDept;
    var expenseContractDept;

    //================== �ڲ����� ====================//
    //��ʼ����������
    function initCostType(thisObj) {
        var tableStr = '<table class="form_in_table" id="' + defaults.myId + 'tbl">' +
            '<tr id="feeTypeTr">' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle" class="red">��ѡ���������</span></td>' +
            '<td class = "form_text_right" colspan="5">' +
            '<input type="radio" name="' + defaults.objName + '[detailType]" value="1"/> ���ŷ��� ' +
            '<input type="radio" name="' + defaults.objName + '[detailType]" value="2"/> ��ͬ��Ŀ���� ' +
            '<input type="radio" name="' + defaults.objName + '[detailType]" value="3"/> �з����� ' +
            '<input type="radio" name="' + defaults.objName + '[detailType]" value="4"/> ��ǰ���� ' +
            '<input type="radio" name="' + defaults.objName + '[detailType]" value="5"/> �ۺ���� ' +
            '</td>' +
            '</tr>' +
            '</table>';
        $(thisObj).html(tableStr);
        $("input[name='" + defaults.objName + "[detailType]']").each(function () {
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
                    break;
            }
        });
    }

    //���ø�������
    function resetCombo() {
        defaults.costType = this.value;
        $("#detailTypeTitle").html('��������').removeClass('red').addClass('blue');
        $("#projectName").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
        $("#projectCode").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
        $("#costBelongCom").yxcombogrid_branch('remove');
        $(".feeTypeContent").remove();
    }

    /****************************** ��ͬ���õ��� ***********************************/
    //��ʼ������ TODO
    function initDept() {
        var thisClass, thisCompany, thisCompanyId;
        if (defaults.isCompanyReadonly == true) {
            thisClass = "readOnlyTxtNormal";
        } else {
            thisClass = "txt";
        }
        thisCompany = defaults.company;
        thisCompanyId = defaults.companyId;

        //Ĭ�ϻ�ȡ
        var deptIdObj = $("#deptId");
        var deptNameObj = $("#deptName");
        var deptId, deptName;
        if (deptIdObj.length == 1) {
            deptId = deptIdObj.val();
        } else {
            deptId = '';
        }
        if (deptNameObj.length == 1) {
            deptName = deptNameObj.val();
        } else {
            deptName = '';
        }

        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">���ù�����˾</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="' + thisClass + '" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + thisCompany + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
            '<td class = "form_text_right" colspan="3">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" value="' + deptName + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" value="' + deptId + '"/>' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        if (!defaults.isCompanyReadonly == true) {
            //��˾��Ⱦ
            $("#costBelongCom").yxcombogrid_branch({
                hiddenId: 'costBelongComId',
                height: 250,
                isFocusoutCheck: false,
                gridOptions: {
                    showcheckbox: false
                }
            });
        }
        //���ù�������ѡ��
        $("#costBelongDeptName").yxselect_dept({
            hiddenId: 'costBelongDeptId',
            unDeptFilter: $('#unDeptFilter').val(),
            unSltDeptFilter: $('#unSltDeptFilter').val()
        });
    }

    //��ʼ����ͬ��Ŀ TODO
    function initContractProject() {
        var thisCompany = defaults.company;
        var thisCompanyId = defaults.companyId;
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[projectCode]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" />' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" id="projectType"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" />' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" />' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" />' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //��ͬ��Ŀ��Ⱦ
        $("#projectName").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectName',
            searchName: 'projectName',
            height: 250,
            isFocusoutCheck: false,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {attribute: 'GCXMSS-02', statusArr: 'GCXMZT02'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        $("#projectId").val(data.id);
                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
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
            isFocusoutCheck: false,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {attribute: 'GCXMSS-02', statusArr: 'GCXMZT02'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        $("#projectId").val(data.id);
                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');
                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });
    }

    //��ʼ���з���Ŀ TODO
    function initRdProject() {
        var thisCompany = defaults.company;
        var thisCompanyId = defaults.companyId;
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[projectCode]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" />' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" id="projectType"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" />' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" />' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" />' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //�з���Ŀ��Ⱦ
        $("#projectName").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectName',
            searchName: 'projectName',
            isShowButton: false,
            height: 250,
            isFocusoutCheck: false,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                param: {attribute: 'GCXMSS-05', statusArr: 'GCXMZT02'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });

        //�з���Ŀ��Ⱦ
        $("#projectCode").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectCode',
            searchName: 'projectName',
            isShowButton: false,
            height: 250,
            isFocusoutCheck: false,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload : false,
                param: {attribute: 'GCXMSS-05', statusArr: 'GCXMZT02'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });
    }

    //��ʼ����ǰ TODO ��ǰ
    function initSale() {
        var thisCompany = defaults.company;
        var thisCompanyId = defaults.companyId;
        var salesAreaStr = '';
        if (defaults.objName == 'specialapply[costbelong]') {// �ر�����������ǰ�������͵���ӹ��������ֶ� ����PMS2383
            salesAreaStr =
                '<td class = "form_text_left_three">���ù�������</td>' +
                '<td class = "form_text_right">' +
                '<select id="areaOpt" class="txt" style="display:none"></select>' +
                '<input type="text" class="readOnlyTxtNormal" id="areaRead" value="" readonly="readonly"/>' +
                '<input type="hidden" class="txt" id="area" name="' + defaults.objName + '[salesArea]" style="width:202px;"/>' +
                '<input type="hidden" id="areaId" name="' + defaults.objName + '[salesAreaId]" />' +
                '</td>';
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">������Ŀ���</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[projectCode]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three">������Ŀ����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" />' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" />' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">��Ŀ����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" />' +
            '</td>' +
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
            '<input type="text" class="txt" id="customerType" name="' + defaults.objName + '[customerType]" style="width:202px;"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">���۸�����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="' + defaults.objName + '[costBelonger]" readonly="readonly"/>' +
            '<input type="hidden" class="ciClass" id="costBelongerId" name="' + defaults.objName + '[costBelongerId]" />' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" style="width:202px;"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" />' +
            '</td>' +
            salesAreaStr +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //�̻����
        var codeObj = $("#chanceCode");
        if (codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "�����������̻���ţ�ϵͳ�Զ�ƥ�������Ϣ";
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
        if (nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "�����������̻����ƣ�ϵͳ�Զ�ƥ�������Ϣ";
        var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻�����'>&nbsp;</span>");
        $button.click(function () {
            if (nameObj.val() == "") {
                alert('������һ���̻�����');
                return false;
            }
        });

        //�����հ�ť
        var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
        $button2.click(function () {
            if (nameObj.val() != "") {
                //���������Ϣ
                clearSale();
                openInput('chance');
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
            isFocusoutCheck: true,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {'contractType': 'GCXMYD-04', 'statusArr': 'GCXMZT02,GCXMZT04'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        //�����������
                        closeInput('trialPlan', data.id);

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
                                if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
                                    $("#costBelonger").val(trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                } else {
                                    $('#costBelonger').combobox({
                                        valueField: 'text',
                                        textField: 'text',
                                        editable: false,
                                        data: [{
                                            "text": trialProjectInfo.applyName,
                                            "value": trialProjectInfo.applyNameId
                                        }],
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
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');
                    clearSale();

                    //�����������
                    openInput('trialPlan');
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
            isFocusoutCheck: true,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {'contractType': 'GCXMYD-04', 'statusArr': 'GCXMZT02,GCXMZT04'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        //�����������
                        closeInput('trialPlan', data.id);

                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // ��ȡ��Ŀ����Ĳ���
//						var userInfo = getUserInfo(data.managerId);

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
                                if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
                                    $("#costBelonger").val(trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                } else {
                                    $('#costBelonger').combobox({
                                        valueField: 'text',
                                        textField: 'text',
                                        editable: false,
                                        data: [{
                                            "text": trialProjectInfo.applyName,
                                            "value": trialProjectInfo.applyNameId
                                        }],
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
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');
                    clearSale();

                    //�����������
                    openInput('trialPlan');
                }
            }
        }).attr('class', 'txt');

        //��ʼ���ͻ�
        initCustomer();

        //�ͻ�����
        $('#customerType').combobox({
            url: 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                //�������۸�����
                changeCustomerType();
            },
            onUnselect: function (obj) {
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
                if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// �ر�����������ǰ�������͵���ӹ��������ֶ� ����PMS2383
                    setSalesArea();
                }
            }
        });

        //������Ⱦ
        cityObj.combobox({
            textField: 'cityName',
            valueField: 'cityName',
            multiple: true,
            editable: false,
            formatter: function (obj) {
                return "<input type='checkbox' id='city_" + obj.cityName + "' value='" + obj.cityName + "'/> " + obj.cityName;
            },
            onSelect: function (obj) {
                //checkbox��ֵ
                $("#city_" + obj.cityName).attr('checked', true);
                //�������۸�����
                changeCustomerType('cityChange');
            },
            onUnselect: function (obj) {
                //checkbox��ֵ
                $("#city_" + obj.cityName).attr('checked', false);
                //�������۸�����
                changeCustomerType('cityChange');
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
        var dataArr = expenseSaleDept;
        $('#costBelongDeptName').combobox({
            data: dataArr,
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                $("#costBelongDeptId").val(obj.value);
            }
        });
    }

    //��ʼ���ۺ� TODO �ۺ�
    function initContract() {
        var thisCompany = defaults.company;
        var thisCompanyId = defaults.companyId;
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">��ͬ���</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt ciClass" id="contractCode" name="' + defaults.objName + '[contractCode]"/>' +
            '<input type="hidden" class="ciClass" id="contractId" name="' + defaults.objName + '[contractId]" />' +
            '<input type="hidden" class="ciClass" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" class="ciClass" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + thisCompanyId + '"/>' +
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
            '<input type="text" class="readOnlyTxtNormal ciClass" id="customerType" name="' + defaults.objName + '[customerType]" readonly="readonly"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">���۸�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="' + defaults.objName + '[costBelonger]" readonly="readonly"/>' +
            '<input type="hidden" class="ciClass" id="costBelongerId" name="' + defaults.objName + '[costBelongerId]" />' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
            '<td class = "form_text_right" colspan="3">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" style="width:202px;"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" />' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

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
        var dataArr = expenseContractDept;
        $('#costBelongDeptName').combobox({
            data: dataArr,
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                $("#costBelongDeptId").val(obj.value);
            }
        });

        //���������Ⱦ
        var codeObj = $("#contractCode");
        if (codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "���������ĺ�ͬ��ţ�ϵͳ�Զ�ƥ�������Ϣ";
        var $button = $("<span class='search-trigger' id='contractCodeSearch' title='��ͬ���'>&nbsp;</span>");
        $button.click(function () {
            if ($("#contractCode").val() == "") {
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
        if (nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "���������ĺ�ͬ���ƣ�ϵͳ�Զ�ƥ�������Ϣ";
        var $button = $("<span class='search-trigger' id='contractCodeSearch' title='��ͬ����'>&nbsp;</span>");
        $button.click(function () {
            if ($("#contractName").val() == "") {
                alert('������һ����ͬ����');
                return false;
            }
        });

        //�����հ�ť
        var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
        $button2.click(function () {
            $(".ciClass").val('');
        });
        nameObj.bind('blur', getContractInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true);
    }

    // ���ݹ���������������۸��������ö�Ӧֵ createdBy haojin 2017-01-24 PMS2418
    function setSalesPerson(personNames, personIds, isEdit) {
        var settedArr = [];
        if (personNames == '' || personIds == '' || personNames == undefined || personIds == undefined) {
            $("#costBelongerOpt").hide();
            $('#costBelonger').show();
            $('#costBelonger').val('');
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
                    $("#costBelongerOpt").show();
                    $("#costBelongerOpt").html(optStr);
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
                $('#costBelonger').show();
                $('#costBelonger').val(personNameArr[0]);
                $('#costBelongerId').val(personIdArr[0]);
            }
        }
    }

    // �첽��������������Ϣ createdBy haojin 2017-01-12 PMS2383 (�ֶ��ر�����������ǰ���ÿ����˴˺�����defaults.objName == 'specialapply[costbelong]'��)
    function setSalesArea(chanceArr) {
        var areaCode = '';
        var areaName = '';
        var areaArr = [];
        var province = $("#province").combobox('getValue');
        var customerType = $("#customerType").combobox('getValue');
        var applyUserId = $("#applyUserId").val();
        if (chanceArr) {// ������̻���Ϣ,ֱ�Ӵ�����Ӧ����������
            areaCode = chanceArr.areaCode;
            areaName = chanceArr.areaName;
            var areaStr = $.ajax({
                type: "POST",
                url: "?model=system_region_region&action=ajaxConRegionByName",
                data: {
                    customerType: chanceArr.customerTypeName,
                    province: chanceArr.Province,
                    businessBelong: chanceArr.businessBelongName,
                    needAll: 1
                },
                async: false
            }).responseText;
            if (areaStr != 'false') {
                var areaArr = eval('(' + areaStr + ")");
            }
        } else if (province != "" && customerType != "" && applyUserId != "") {
            var areaStr = $.ajax({
                type: "POST",
                url: "?model=system_region_region&action=ajaxConRegionByName",
                data: {
                    customerType: customerType,
                    province: province,
                    userId: applyUserId,
                    getCompanyByUid: 1,
                    needAll: 1
                },
                async: false
            }).responseText;
            if (areaStr != 'false') {
                var areaArr = eval('(' + areaStr + ")");
            }
        }

        // ���������ȡ�����������������Ӧ�Ĵ���
        if (areaArr.length > 1 && defaults.actionType == 'edit' && initNum == 0 && !chanceArr) {
            initNum += 1;
            var optionsStr = '<option value="">..��ѡ��..</option>';
            $.each(areaArr, function () {
                if ($(this)[0]['id'] == $("#areaId").val()) {
                    optionsStr += '<option value="' + $(this)[0]['id'] + '" data-salesPerson="' + $(this)[0]['personNames'] + '" data-salesPersonId="' + $(this)[0]['personIds'] + '" selected>' + $(this)[0]['areaName'] + '</option>';
                    setSalesPerson($(this)[0]['personNames'], $(this)[0]['personIds'], 1);
                } else {
                    optionsStr += '<option value="' + $(this)[0]['id'] + '" data-salesPerson="' + $(this)[0]['personNames'] + '" data-salesPersonId="' + $(this)[0]['personIds'] + '">' + $(this)[0]['areaName'] + '</option>';
                }
            });

            $('#areaRead').hide();
            $('#areaOpt').html(optionsStr);
            $('#areaOpt').show();
            $('#areaOpt').change(function () {
                var areaId = $('#areaOpt option:selected').val();
                var area = $('#areaOpt option:selected').text();
                var sales_Person = (areaId == '') ? '' : $('#areaOpt option:selected').attr('data-salesPerson');
                var sales_PersonId = (areaId == '') ? '' : $('#areaOpt option:selected').attr('data-salesPersonId');
                if (areaId != '') {
                    $('#areaRead').val(area);
                    $('#area').val(area);
                    $('#areaId').val(areaId);
                } else {
                    $('#areaRead').val('');
                    $('#area').val('');
                    $('#areaId').val('');
                    $('#costBelongerId').val('');
                }

                // �Զ�������۸�������Ϣ
                setSalesPerson(sales_Person, sales_PersonId);
                // $('#costBelonger').val(sales_Person);
                // $('#costBelongerId').val(sales_PersonId);
            });
        }
        else if ((areaCode != '' && areaName != '') || areaArr.length > 0) {
            // ��ʼ������������Ϣ
            $('#areaRead').val('');
            $('#areaRead').show();
            $('#areaOpt').html('');
            $('#areaOpt').hide();
            $('#area').val('');
            $('#areaId').val('');
            if (areaArr.length > 1) {// �ж����������
                // ��ʾѡ��
                var optionsStr = '<option value="">..��ѡ��..</option>';
                $.each(areaArr, function () {
                    if (chanceArr && $(this)[0]['id'] == areaCode) {// ������̻���Ϣ,ֱ�Ӵ�����Ӧ����������
                        $('#area').val(areaName);
                        $('#areaId').val(areaCode);
                        optionsStr += '<option value="' + $(this)[0]['id'] + '" data-salesPerson="' + $(this)[0]['personNames'] + '" data-salesPersonId="' + $(this)[0]['personIds'] + '" selected>' + $(this)[0]['areaName'] + '</option>';
                        setSalesPerson($(this)[0]['personNames'], $(this)[0]['personIds']);
                        // $('#costBelonger').val($(this)[0]['personNames']);
                        // $('#costBelongerId').val($(this)[0]['personIds']);
                    } else {
                        optionsStr += '<option value="' + $(this)[0]['id'] + '" data-salesPerson="' + $(this)[0]['personNames'] + '" data-salesPersonId="' + $(this)[0]['personIds'] + '">' + $(this)[0]['areaName'] + '</option>';
                    }
                });

                $('#areaRead').hide();
                $('#areaOpt').html(optionsStr);
                $('#areaOpt').show();
                $('#areaOpt').change(function () {
                    var areaId = $('#areaOpt option:selected').val();
                    var area = $('#areaOpt option:selected').text();
                    var sales_Person = (areaId == '') ? '' : $('#areaOpt option:selected').attr('data-salesPerson');
                    var sales_PersonId = (areaId == '') ? '' : $('#areaOpt option:selected').attr('data-salesPersonId');
                    if (areaId != '') {
                        $('#areaRead').val(area);
                        $('#area').val(area);
                        $('#areaId').val(areaId);
                    } else {
                        $('#areaRead').val('');
                        $('#area').val('');
                        $('#areaId').val('');
                        $('#costBelongerId').val('');
                    }

                    // �Զ�������۸�������Ϣ
                    setSalesPerson(sales_Person, sales_PersonId);
                    // $('#costBelonger').val(sales_Person);
                    // $('#costBelongerId').val(sales_PersonId);
                });
            } else if (areaArr.length > 0 && areaArr.length <= 1) {// �е�����������
                if (!chanceArr) {// ���û���̻���Ϣ,������Ӧ����������,����ֱ�����̻�������
                    areaCode = areaArr[0].id;
                    areaName = areaArr[0].areaName;
                }

                $('#areaRead').val(areaName);
                $('#area').val(areaName);
                $('#areaId').val(areaCode);
                $('#areaRead').show();
                $('#areaOpt').html('');
                $('#areaOpt').hide();

                // �Զ�������۸�������Ϣ
                setSalesPerson(areaArr[0]['personNames'], areaArr[0]['personIds']);
                // $('#costBelonger').val(areaArr[0]['personNames']);
                // $('#costBelongerId').val(areaArr[0]['personIds']);

            } else {// ֻ��һ����������
                $('#areaRead').val(areaName);
                $('#area').val(areaName);
                $('#areaId').val(areaCode);
                $('#areaRead').show();
                $('#areaOpt').html('');
                $('#areaOpt').hide();
            }
        }
        else if (areaArr.length == 0) {// �鲻����Ӧ��������Ϣʱ
            $('#areaRead').val(areaName);
            $('#area').val(areaName);
            $('#areaId').val(areaCode);
            $('#areaRead').show();
            $('#areaOpt').html('');
            $('#areaOpt').hide();
            $("#costBelonger").val('');
            $('#costBelongerId').val('');
        }
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
            data: {"contractCode": contractCode, "contractName": contractName},
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
                    'row_dblclick': function (e, row, data) {
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
                        if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
                            $("#costBelonger").val('');
                            $("#costBelongerId").val('');
                        } else {
                            $('#costBelonger').combobox({
                                valueField: 'text',
                                textField: 'text',
                                editable: false,
                                data: [{"text": data.AreaLeader, "value": data.AreaLeaderId}],
                                onSelect: function (obj) {
                                    $("#costBelongerId").val(obj.value);
                                }
                            }).combobox('setValue', data.AreaLeader);
                            $("#costBelongerId").val(data.AreaLeaderId);
                        }

                        // ����������������
                        setSalesArea();
                    }
                }
            },
            event: {
                'clear': function () {
                    clearSale();

                    //�����������
                    openInput('customer');
                }
            }
        }).attr('readonly', false).attr('class', 'txt');
    }

    //��ȡ�̻���Ϣ
    function getChanceInfo(thisType) {
        if ($("#projectCode").val() != "" && !thisType) {
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
            data: {"chanceCode": chanceCode, "chanceName": chanceName},
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
                        if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// �ر�����������ǰ�������͵���ӹ��������ֶ� ����PMS2383
                            setSalesArea(dataArr);
                        }
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

        //���÷��ù�������
        $("#costBelongDeptName").combobox("setValue", '');
        $("#costBelongDeptId").val('');
        if (isCombobox('costBelonger') == 1) {
            $("#costBelonger").combobox("setValue", '');
            $("#costBelongerId").val('');
        } else {
            $("#costBelonger").val('');
            $("#costBelongerId").val('');
        }

        // ������������
        if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// �ر�����������ǰ�������͵���ӹ��������ֶ� ����PMS2383
            setSalesArea();
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
        $("#customerType").combobox('setValue', '');

        var cityObj = $("#city");
        mulSelectClear(cityObj);
    }

    //��ѡ��� - ������ն�ѡ������������
    function mulSelectClear(thisObj) {
        thisObj.combobox("setValues", "");
        $("#" + thisObj.attr('id') + "Hidden").val('');
        //��ո�ѡ��
        if (thisObj.attr("id") == 'city') {
            $("input:checkbox[id^='" + thisObj.attr("id") + "_']").attr("checked", false);
        } else {
            $("input:checkbox[id^='customerType_']").attr("checked", false);
        }
    }

    // �����������
    function closeInput(thisType, projectId) {
        //��Ŀid��ȡ
        if (projectId == undefined) {
            var projectId = $("#projectId").val();//��Ŀid
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

            //�̻����
            var codeObj = $("#chanceCode");
            if (codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true) {
                return false;
            }
            var title = "�����������̻���ţ�ϵͳ�Զ�ƥ�������Ϣ";
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
            if (nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true) {
                return false;
            }
            var title = "�����������̻����ƣ�ϵͳ�Զ�ƥ�������Ϣ";
            var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻�����'>&nbsp;</span>");
            $button.click(function () {
                if (nameObj.val() == "") {
                    alert('������һ���̻�����');
                    return false;
                }
            });

            //�����հ�ť
            var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
            $button2.click(function () {
                if (nameObj.val() != "") {
                    //���������Ϣ
                    clearSale();
                    openInput('chance');
                }
            });
            nameObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');
        } else if (thisType == 'customer') {
            //��Ŀ
            initTrialproject();

            $("#customerName").attr("class", 'txt').attr('readonly', false);

            //�̻����
            var codeObj = $("#chanceCode");
            if (codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true) {
                return false;
            }
            var title = "�����������̻���ţ�ϵͳ�Զ�ƥ�������Ϣ";
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
            if (nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true) {
                return false;
            }
            var title = "�����������̻����ƣ�ϵͳ�Զ�ƥ�������Ϣ";
            var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻�����'>&nbsp;</span>");
            $button.click(function () {
                if (nameObj.val() == "") {
                    alert('������һ���̻�����');
                    return false;
                }
            });

            //�����հ�ť
            var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
            $button2.click(function () {
                if (nameObj.val() != "") {
                    //���������Ϣ
                    clearSale();
                    openInput('chance');
                }
            });
            nameObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');
        } else if ((typeof(thisType) == "object" && thisType.data == 'chance') || thisType == 'chance') {
            //��Ŀ
            initTrialproject();

            //����ʵ�����ͻ�ѡ��
            initCustomer();
        }

        //��ʾʡ�ݵ�������
        $("#province").combobox('enable');
        $('#city').combobox('enable');
        $("#customerType").combobox('enable');
        $("#costBelonger").combobox('enable');
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
            isFocusoutCheck: true,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                param: {'contractType': 'GCXMYD-04', 'statusArr': 'GCXMZT02'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        //�����������
                        closeInput('trialPlan', data.id);

                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // ��ȡ��Ŀ����Ĳ���
//						var userInfo = getUserInfo(data.managerId);

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
                                if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
                                    $("#costBelonger").val(trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                } else {
                                    $('#costBelonger').combobox({
                                        valueField: 'text',
                                        textField: 'text',
                                        editable: false,
                                        data: [{
                                            "text": trialProjectInfo.applyName,
                                            "value": trialProjectInfo.applyNameId
                                        }],
                                        onSelect: function (obj) {
                                            $("#costBelongerId").val(obj.value);
                                        }
                                    }).combobox('setValue', trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                }

                                //���ؿͻ�����
                                reloadCity(trialProjectInfo.province);
                                var cityObj = $("#city");
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');
                    clearSale();

                    //�����������
                    openInput('trialPlan');
                }
            }
        }).attr('class', 'txt');

        $("#projectName").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectName',
            searchName: 'projectName',
            height: 250,
            isFocusoutCheck: true,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                param: {'contractType': 'GCXMYD-04', 'statusArr': 'GCXMZT02'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        //�����������
                        closeInput('trialPlan', data.id);

                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // ��ȡ��Ŀ����Ĳ���
//						var userInfo = getUserInfo(data.managerId);

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
                                if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
                                    $("#costBelonger").val(trialProjectInfo.applyName);
                                    $("#costBelongerId").val(trialProjectInfo.applyNameId);
                                } else {
                                    $('#costBelonger').combobox({
                                        valueField: 'text',
                                        textField: 'text',
                                        editable: false,
                                        data: [{
                                            "text": trialProjectInfo.applyName,
                                            "value": trialProjectInfo.applyNameId
                                        }],
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
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');
                    clearSale();

                    //�����������
                    openInput('trialPlan');
                }
            }
        }).attr('class', 'txt');
    }

    //ѡ��ͻ�����
    function changeCustomerType(thisType) {
        var chanceId = $("#chanceId").val();
        var customerId = $("#customerId").val();
        var salesAreaId = $("#areaId").val();
        if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// �ر�����������ǰ�������͵���ӹ��������ֶ� ����PMS2383
            if (thisType != 'cityChange') {// ���ĳ���,��Ӱ����������
                setSalesArea();
            }
        }
        if ((chanceId == "" || chanceId == '0') && (customerId == "" || customerId == '0') && (salesAreaId == '' || salesAreaId == undefined) && defaults.objName != 'specialapply[costbelong]' && defaults.costType != 4) {
            var customerType = $('#customerType').combobox('getValue');//�ͻ�����
            var province = $('#province').combobox('getValue');//ʡ��
            var city = $('#city').combobox('getValues').toString();//����
            if (province && city && customerType) {
                //ajax��ȡ���۸�����
                var responseText = $.ajax({
                    url: 'index1.php?model=system_saleperson_saleperson&action=getSalePerson',
                    data: {"province": province, "city": city, 'customerTypeName': customerType},
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
                        }
                    });

                    if (thisType != 'init') {
                        costBelongerObj.combobox('setValue', '');
                        $("#costBelongerId").val('');
                    }
                }
            } else if (thisType == 'init') {
                //���۸�����
                $('#costBelonger').combobox({
                    valueField: 'text',
                    textField: 'text',
                    editable: false,
                    data: [{"text": $("#costBelonger").val(), "value": $("#costBelongerId").val()}],
                    onSelect: function (obj) {
                        $("#costBelongerId").val(obj.value);
                    }
                });
            }
        }
    }

    //ajax��ȡ������Ŀ������Ϣ
    function getTrialProject(id) {
        var obj;
        $.ajax({
            type: "POST",
            url: "?model=projectmanagent_trialproject_trialproject&action=ajaxGetInfo",
            data: {"id": id},
            async: false,
            success: function (data) {
                if (data) {
                    obj = eval("(" + data + ")");
                }
            }
        });
        return obj;
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
        reloadCity(dataArr.Province);
        $("#city").combobox('setValue', dataArr.City);

        //�ͻ�����
        $("#customerType").combobox('setValue', dataArr.customerTypeName);

        //���۸�����
        if (defaults.objName != 'specialapply[costbelong]' && defaults.costType != 4) {// ��ǰ�������͵ĸ��ݹ��������ȥ���۸����� ����PMS2418
            $('#costBelonger').combobox({
                valueField: 'text',
                textField: 'text',
                editable: false,
                data: [{"text": dataArr.prinvipalName, "value": dataArr.prinvipalId}],
                onSelect: function (obj) {
                    $("#costBelongerId").val(obj.value);
                }
            }).combobox('setValue', dataArr.prinvipalName);
            $("#costBelongerId").val(dataArr.prinvipalId);
        }
    }

    //�����������
    function reloadCity(data) {
        $('#city').combobox({
            url: "?model=system_procity_city&action=listJson&tProvinceName=" + data
        });
    }

    //*********************** TODO �鿴���� *********************/
    //��ʼ����������
    function initCostTypeView(objInfo) {
        if (objInfo.detailType) {
            switch (objInfo.detailType) {
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
                    break;
            }
        }
    }

    //��ʼ������
    function initDeptView(objInfo) {
        var tableStr = '<table class="form_in_table" id="' + defaults.myId + 'tbl">' +
            '<tr id="feeTypeTr">' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle">��������</span></td>' +
            '<td class = "form_text_right" colspan="5">' +
            '���ŷ���' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">���ù�����˾</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.costBelongCom +
            '</td>' +
            '<td class = "form_text_left_three">���ù�������</td>' +
            '<td class = "form_text_right" colspan="3">' +
            objInfo.costBelongDeptName +
            '</td>' +
            '</tr>' +
            '</table>';
        $("#" + defaults.myId).html(tableStr);
    }

    //��ʼ����ͬ��Ŀ
    function initProjectView(objInfo) {
        var projectView = objInfo.detailType == '2' ? '��ͬ��Ŀ����' : '�з�����';
        var tableStr = '<table class="form_in_table" id="' + defaults.myId + 'tbl">' +
            '<tr id="feeTypeTr">' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle">��������</span></td>' +
            '<td class = "form_text_right" colspan="5">' +
            projectView +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">��Ŀ���</span></td>' +
            '<td class = "form_text_right_three">' +
            objInfo.projectCode +
            '</td>' +
            '<td class = "form_text_left_three">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            objInfo.projectName +
            '</td>' +
            '<td class = "form_text_left_three">��Ŀ����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.proManagerName +
            '</td>' +
            '</tr>' +
            '</table>';
        $("#" + defaults.myId).html(tableStr);
    }

    //��ʼ����ǰ
    function initSaleView(objInfo) {
        var tableStr = '<table class="form_in_table" id="' + defaults.myId + 'tbl">' +
            '<tr id="feeTypeTr">' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle">��������</span></td>' +
            '<td class = "form_text_right_three" colspan="5">' +
            '��ǰ����' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">������Ŀ���</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.projectCode +
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
            objInfo.customerType +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">���۸�����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.costBelonger +
            '</td>' +
            '<td class = "form_text_left_three">���ù�������</td>' +
            '<td class = "form_text_right">' +
            objInfo.costBelongDeptName +
            '</td>' +
            '<td class = "form_text_left_three">���ù�������</td>' +
            '<td class = "form_text_right">' +
            objInfo.salesArea +
            '</td>' +
            '</tr>' +
            '</table>';
        $("#" + defaults.myId).html(tableStr);
    }

    //��ʼ���ۺ�
    function initContractView(objInfo) {
        var tableStr = '<table class="form_in_table" id="' + defaults.myId + 'tbl">' +
            '<tr id="feeTypeTr">' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle">��������</span></td>' +
            '<td class = "form_text_right" colspan="5">' +
            '�ۺ����' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
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
            objInfo.customerType +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">���۸�����</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.costBelonger +
            '</td>' +
            '<td class = "form_text_left_three">���ù�������</td>' +
            '<td class = "form_text_right" colspan="3">' +
            objInfo.costBelongDeptName +
            '</td>' +
            '</tr>' +
            '</table>';
        $("#" + defaults.myId).html(tableStr);
    }

    //********************* TODO �༭���� ************************/
    //��ʼ����������
    function initCostTypeEdit(thisObj, objInfo) {
        initCostType(thisObj);
        //��ѡ��ֵ
        $("input[name='" + defaults.objName + "[detailType]']").each(function (i, n) {
            if (this.value == objInfo.detailType) {
                $(this).attr("checked", this);
                return false;
            }
        });
        $("#detailTypeTitle").html('��������').removeClass('red').addClass('blue');
        switch (objInfo.detailType) {
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
                break;
        }
    }

    //TODO ��ʼ������
    function initDeptEdit(objInfo) {
        //��ʼֵ����
        var costBelongCom = '', costBelongComId = '', costBelongDeptName = '', costBelongDeptId = '', id = '';
        if (objInfo) {
            costBelongCom = objInfo.costBelongCom;
            costBelongComId = objInfo.costBelongComId;
            costBelongDeptName = objInfo.costBelongDeptName;
            costBelongDeptId = objInfo.costBelongDeptId;
            id = objInfo.id;
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">���ù�����˾</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + costBelongCom + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + costBelongComId + '"/>' +
            '<input type="hidden" name="' + defaults.objName + '[id]" value="' + id + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
            '<td class = "form_text_right" colspan="3">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" value="' + costBelongDeptName + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);
        //��˾��Ⱦ
        $("#costBelongCom").yxcombogrid_branch({
            hiddenId: 'costBelongComId',
            height: 250,
            isFocusoutCheck: false,
            gridOptions: {
                showcheckbox: false
            }
        });
        //���ù�������ѡ��
        $("#costBelongDeptName").yxselect_dept({
            hiddenId: 'costBelongDeptId',
            unDeptFilter: $('#unDeptFilter').val(),
            unSltDeptFilter: $('#unSltDeptFilter').val()
        });
    }

    // TODO ��ʼ����ͬ��Ŀ
    function initContractProjectEdit(objInfo) {
        //��ʼֵ����
        var projectName = '', projectCode = '', projectId = '', costBelongDeptName = '', costBelongDeptId = '', proManagerName = '', proManagerId = '', id = '';
        if (objInfo) {
            projectName = objInfo.projectName;
            projectCode = objInfo.projectCode;
            projectId = objInfo.projectId;
            projectType = objInfo.projectType;
            costBelongDeptName = objInfo.costBelongDeptName;
            costBelongDeptId = objInfo.costBelongDeptId;
            proManagerName = objInfo.proManagerName;
            proManagerId = objInfo.proManagerId;
            id = objInfo.id;
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[projectCode]" readonly="readonly" value="' + projectCode + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly" value="' + projectName + '"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" value="' + projectId + '"/>' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" value="' + projectType + '"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" value="' + costBelongDeptName + '"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '<input type="hidden" name="' + defaults.objName + '[id]" value="' + id + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly" value="' + proManagerName + '"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" value="' + proManagerId + '"/>' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //��ͬ��Ŀ��Ⱦ
        $("#projectName").yxcombogrid_projectall({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectName',
            searchName: 'projectNameSearch',
            height: 250,
            isFocusoutCheck: false,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                param: {'contractType': 'GCXMYD-01'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        $("#projectId").val(data.projectId);
                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val(data.projectType);

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectId").val('');
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });


        //������Ŀ��Ⱦ
        $("#projectCode").yxcombogrid_projectall({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectCode',
            height: 250,
            isFocusoutCheck: false,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                param: {'contractType': 'GCXMYD-01'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        $("#projectId").val(data.projectId);
                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val(data.projectType);
                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });
    }

    //TODO ��ʼ���з���Ŀ
    function initRdProjectEdit(objInfo) {
        //��ʼֵ����
        var projectName = '', projectCode = '', projectId = '', costBelongDeptName = '', costBelongDeptId = '', proManagerName = '', proManagerId = '', id = '';
        if (objInfo) {
            projectName = objInfo.projectName;
            projectCode = objInfo.projectCode;
            projectId = objInfo.projectId;
            projectType = objInfo.projectType;
            costBelongDeptName = objInfo.costBelongDeptName;
            costBelongDeptId = objInfo.costBelongDeptId;
            proManagerName = objInfo.proManagerName;
            proManagerId = objInfo.proManagerId;
            id = objInfo.id;
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ���</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[projectCode]" readonly="readonly" value="' + projectCode + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly" value="' + projectName + '"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" value="' + projectId + '"/>' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" value="' + projectType + '"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" value="' + costBelongDeptName + '"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '<input type="hidden" name="' + defaults.objName + '[id]" value="' + id + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��Ŀ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly" value="' + proManagerName + '"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" value="' + proManagerId + '"/>' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //�з���Ŀ��Ⱦ
        $("#projectName").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectName',
            searchName: 'projectName',
            isShowButton: false,
            height: 250,
            isFocusoutCheck: false,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                param: {attribute: 'GCXMSS-05', statusArr: 'GCXMZT02'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });

        //�з���Ŀ��Ⱦ
        $("#projectCode").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'projectCode',
            searchName: 'projectName',
            isShowButton: false,
            height: 250,
            isFocusoutCheck: false,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                param: {attribute: 'GCXMSS-05', statusArr: 'GCXMZT02'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('rd');

                        //���÷��ù�������
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');

                    //���÷��ù�������
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });
    }

    //TODO ��ʼ����ǰ����
    function initSaleEdit(objInfo) {
        defaults.costType = 4;
        //��ʼֵ����
        var projectName = '', projectCode = '', projectId = '', projectType = '', costBelongDeptName = '', costBelongDeptId = '';
        var proManagerName = '', proManagerId = '', chanceCode = '', chanceName = '', id = '';
        var chanceId = '', customerName = '', customerId = '', province = '', city = '', area = '', areaId = '', customerType = '', costBelonger = '', costBelongerId = '';
        if (objInfo) {
            projectName = objInfo.projectName;
            projectCode = objInfo.projectCode;
            projectId = objInfo.projectId;
            projectType = objInfo.projectType;
            costBelongDeptName = objInfo.costBelongDeptName;
            costBelongDeptId = objInfo.costBelongDeptId;
            costBelongComId = objInfo.costBelongComId;
            costBelongCom = objInfo.costBelongCom;
            proManagerName = objInfo.proManagerName;
            proManagerId = objInfo.proManagerId;
            chanceCode = objInfo.chanceCode;
            chanceName = objInfo.chanceName;
            chanceId = objInfo.chanceId;
            customerName = objInfo.customerName;
            customerId = objInfo.customerId;
            province = objInfo.province;
            city = objInfo.city;
            customerType = objInfo.customerType;
            costBelonger = objInfo.costBelonger;
            costBelongerId = objInfo.costBelongerId;
            id = objInfo.id;
            area = objInfo.salesArea;
            areaId = objInfo.salesAreaId;
        }
        var salesAreaStr = '';
        if (defaults.objName == 'specialapply[costbelong]') {// �ر�����������ǰ�������͵���ӹ��������ֶ� ����PMS2383
            salesAreaStr =
                '<td class = "form_text_left_three">���ù�������</td>' +
                '<td class = "form_text_right">' +
                '<select id="areaOpt" class="txt" style="display:none"></select>' +
                '<input type="text" class="readOnlyTxtNormal" id="areaRead" value="' + area + '" readonly="readonly"/>' +
                '<input type="hidden" class="txt" id="area" name="' + defaults.objName + '[salesArea]" style="width:202px;" value="' + area + '"/>' +
                '<input type="hidden" id="areaId" name="' + defaults.objName + '[salesAreaId]" value="' + areaId + '"/>' +
                '</td>';
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">������Ŀ���</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[projectCode]" readonly="readonly" value="' + projectCode + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">������Ŀ����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly" value="' + projectName + '"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" value="' + projectId + '"/>' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" value="' + projectType + '"/>' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + costBelongCom + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + costBelongComId + '"/>' +
            '<input type="hidden" name="' + defaults.objName + '[id]" value="' + id + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">��Ŀ����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" value="' + proManagerName + '" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" value="' + proManagerId + '"/>' +
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
            '<input type="text" class="txt" id="customerType" name="' + defaults.objName + '[customerType]" value="' + customerType + '" style="width:202px;"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">���۸�����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="costBelonger" name="' + defaults.objName + '[costBelonger]" readonly="readonly" value="' + costBelonger + '"/>' +
            '<input type="hidden" id="costBelongerId" name="' + defaults.objName + '[costBelongerId]" value="' + costBelongerId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" value="' + costBelongDeptName + '" style="width:202px;"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '</td>' +
            salesAreaStr +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //�̻����
        var codeObj = $("#chanceCode");
        if (codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "�����������̻���ţ�ϵͳ�Զ�ƥ�������Ϣ";
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
        if (nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "�����������̻����ƣ�ϵͳ�Զ�ƥ�������Ϣ";
        var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='�̻�����'>&nbsp;</span>");
        $button.click(function () {
            if (nameObj.val() == "") {
                alert('������һ���̻�����');
                return false;
            }
        });

        //�����հ�ť
        var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
        $button2.click(function () {
            if (nameObj.val() != "") {
                //���������Ϣ
                clearSale();
                openInput('chance');
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
            isFocusoutCheck: true,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                param: {'contractType': 'GCXMYD-04', 'statusArr': 'GCXMZT02'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        //�����������
                        closeInput('trialPlan', data.id);

                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // ��ȡ��Ŀ����Ĳ���
//						var userInfo = getUserInfo(data.managerId);

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
                                $('#costBelonger').val(trialProjectInfo.applyName);
                                $("#costBelongerId").val(trialProjectInfo.applyNameId);

                                //���ؿͻ�����
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectCode").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');
                    clearSale();

                    //�����������
                    openInput('trialPlan');
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
            isFocusoutCheck: true,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                param: {'contractType': 'GCXMYD-04', 'statusArr': 'GCXMZT02'},
                event: {
                    'row_dblclick': function (e, row, data) {
                        //�����������
                        closeInput('trialPlan', data.id);

                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // ��ȡ��Ŀ����Ĳ���
//						var userInfo = getUserInfo(data.managerId);

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
                                $('#costBelonger').val(trialProjectInfo.applyName);
                                $("#costBelongerId").val(trialProjectInfo.applyNameId);

                                //���ؿͻ�����
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                    }
                }
            },
            event: {
                'clear': function () {
                    $("#projectName").val('');
                    $("#proManagerName").val('');
                    $("#proManagerId").val('');
                    $("#projectType").val('');
                    clearSale();

                    //�����������
                    openInput('trialPlan');
                }
            }
        }).attr('class', 'txt');

        //��ʼ���ͻ�
        initCustomer();

        //�ͻ�����
        $('#customerType').combobox({
            url: 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                //�������۸�����
                changeCustomerType();
            },
            onUnselect: function (obj) {
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
                $("#city").combobox('setValue', "");// ���ԭ���ĳ���
                if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// �ر�����������ǰ�������͵���ӹ��������ֶ� ����PMS2383
                    setSalesArea();
                }
            }
        });

        //������Ⱦ
        var cityArr = city.split(',');
        cityObj.combobox({
            url: "?model=system_procity_city&action=listJson&tProvinceName=" + province,
            textField: 'cityName',
            valueField: 'cityName',
            multiple: true,
            editable: false,
            formatter: function (obj) {
                //�ж� ���û�г�ʼ�������У���ѡ��
                if (cityArr.indexOf(obj.cityName) == -1) {
                    return "<input type='checkbox' id='city_" + obj.cityName + "' value='" + obj.cityName + "'/> " + obj.cityName;
                } else {
                    return "<input type='checkbox' id='city_" + obj.cityName + "' value='" + obj.cityName + "' checked='checked'/> " + obj.cityName;
                }
            },
            onSelect: function (obj) {
                //checkbox��ֵ
                $("#city_" + obj.cityName).attr('checked', true);
                //�������۸�����
                changeCustomerType('cityChange');
            },
            onUnselect: function (obj) {
                //checkbox��ֵ
                $("#city_" + obj.cityName).attr('checked', false);
                //�������۸�����
                changeCustomerType('cityChange');
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
        var dataArr = expenseSaleDept;
        $('#costBelongDeptName').combobox({
            data: dataArr,
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                $("#costBelongDeptId").val(obj.value);
            }
        });

        //����һ�ν��ô���
        closeInput();
        //����һ���������۸�����
        changeCustomerType('init');
    }

    //TODO ��ʼ���ۺ����
    function initContractEdit(objInfo) {
        //��ʼֵ����
        var costBelongDeptName = '', costBelongDeptId = '', proManagerName = '', proManagerId = '', contractCode = '', contractName = '', id = '';
        var contractId = '', customerName = '', customerId = '', province = '', city = '', customerType = '', costBelonger = '', costBelongerId = '';
        if (objInfo) {
            costBelongDeptName = objInfo.costBelongDeptName;
            costBelongDeptId = objInfo.costBelongDeptId;
            proManagerName = objInfo.proManagerName;
            proManagerId = objInfo.proManagerId;
            contractCode = objInfo.contractCode;
            contractName = objInfo.contractName;
            contractId = objInfo.contractId;
            customerName = objInfo.customerName;
            customerId = objInfo.customerId;
            province = objInfo.province;
            city = objInfo.city;
            customerType = objInfo.customerType;
            costBelonger = objInfo.costBelonger;
            costBelongerId = objInfo.costBelongerId;
            id = objInfo.id;
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">��ͬ���</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="contractCode" name="' + defaults.objName + '[contractCode]" value="' + contractCode + '"/>' +
            '<input type="hidden" id="contractId" name="' + defaults.objName + '[contractId]" value="' + contractId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">��ͬ����</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="contractName" name="' + defaults.objName + '[contractName]" value="' + contractName + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="customerName" name="' + defaults.objName + '[customerName]" readonly="readonly" value="' + customerName + '"/>' +
            '<input type="hidden" id="customerId" name="' + defaults.objName + '[customerId]" value="' + customerId + '"/>' +
            '<input type="hidden" name="' + defaults.objName + '[id]" value="' + id + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">�ͻ�ʡ��</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="province" name="' + defaults.objName + '[province]" readonly="readonly" value="' + province + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="city" name="' + defaults.objName + '[city]" readonly="readonly" value="' + city + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">�ͻ�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="customerType" name="' + defaults.objName + '[customerType]" readonly="readonly" value="' + customerType + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">���۸�����</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="costBelonger" name="' + defaults.objName + '[costBelonger]" readonly="readonly" value="' + costBelonger + '"/>' +
            '<input type="hidden" id="costBelongerId" name="' + defaults.objName + '[costBelongerId]" value="' + costBelongerId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">���ù�������</span></td>' +
            '<td class = "form_text_right" colspan="3">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" style="width:202px;" value="' + costBelongDeptName + '"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

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
        var dataArr = expenseContractDept;
        $('#costBelongDeptName').combobox({
            data: dataArr,
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                $("#costBelongDeptId").val(obj.value);
            }
        });

        //���������Ⱦ
        var codeObj = $("#contractCode");
        if (codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "���������ĺ�ͬ��ţ�ϵͳ�Զ�ƥ�������Ϣ";
        var $button = $("<span class='search-trigger' id='contractCodeSearch' title='��ͬ���'>&nbsp;</span>");
        $button.click(function () {
            if ($("#contractCode").val() == "") {
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
        if (nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "���������ĺ�ͬ���ƣ�ϵͳ�Զ�ƥ�������Ϣ";
        var $button = $("<span class='search-trigger' id='contractNameSearch' title='��ͬ����'>&nbsp;</span>");
        $button.click(function () {
            if ($("#contractName").val() == "") {
                alert('������һ����ͬ����');
                return false;
            }
        });

        //�����հ�ť
        var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
        $button2.click(function () {
            $(".ciClass").val('');
        });
        nameObj.bind('blur', getContractInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true);
    }

    //************************* ����֤ ****************************/
    //����֤����
    function costCheckForm() {
        var detailType = $("input[name='" + defaults.objName + "[detailType]']:checked").val();
        if (detailType) {
            //���� ��Ӧ������֤
            switch (detailType) {
                case '1' :
                    if ($("#costBelongCom").val() == "") {
                        alert("û����д���ù�����˾");
                        return false;
                    }
                    if ($("#costBelongDeptName").val() == "") {
                        alert("û����д���ù�������");
                        return false;
                    }
                    break;
                case '2' :
                    if ($("#projectCode").val() == "") {
                        alert("��ѡ��ñʷ������ڹ�����Ŀ");
                        return false;
                    }
                    break;
                case '3' :
                    if ($("#projectCode").val() == "") {
                        alert("��ѡ��ñʷ��������з���Ŀ");
                        return false;
                    }
                    break;
                case '4' :
                    if ($("#province").combobox('getValue') == "") {
                        alert("��ѡ��ͻ�����ʡ��");
                        return false;
                    }
                    if ($("#city").combobox('getValues') == "") {
                        alert("��ѡ��ͻ����ڳ���");
                        return false;
                    }
                    if ($("#customerType").combobox('getValue') == "") {
                        alert("��ѡ��ͻ�����");
                        return false;
                    }
                    if ($("#areaId").val() == "") {
                        alert("��ǰ���õĹ���������Ϊ�ա�");
                        return false;
                    }
                    if ($("#costBelongerId").val() == "") {
                        alert("��¼�����۸����ˣ����۸����˿����̻����ͻ������Զ�����������ͨ���ͻ�ʡ�ݡ����С�������ϵͳƥ��");
                        return false;
                    }
                    if ($("#costBelongDeptId").val() == "" || $("#costBelongDeptName").combobox('getValue') == "") {
                        alert("��ѡ����ù�������");
                        return false;
                    }
                    break;
                case '5' :
                    if ($("#contractCode").val() == "") {
                        alert("��ѡ��ñʷ��ù�����ͬ");
                        return false;
                    }
                    if ($("#costBelongDeptId").val() == "" || $("#costBelongDeptName").combobox('getValue') == "") {
                        alert("��ѡ����ù�������");
                        return false;
                    }
                    break;
                default :
                    break;
            }
            return true;
        } else {
            alert('��ѡ���������');
            return false;
        }
    }

    $.fn.costbelong = function (options) {
        //�ϲ�����
        var options = $.extend(defaults, options);
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

                //�󶨱���֤����
                if (defaults.isRequired == true)
                    $("form").bind('submit', costCheckForm);
            }
        });
    };

    //�����û�id��ȡ�û���Ϣ
    function getUserInfo(userId) {
        var userStr = $.ajax({
            type: "POST",
            url: "?model=deptuser_user_user&action=ajaxGetUserInfo",
            data: {userId: userId},
            async: false
        }).responseText;
        return userStr != "" ? eval('(' + userStr + ")") : false;
    }
})(jQuery);
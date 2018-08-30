//费用归属组件
(function ($) {
    var initNum = 0;//初始化次数,用于防止编辑页面第一次初始化后操作不保存上一步的数据用
    //默认属性
    var defaults = {
        getId: 'id', //取数查询对象id
        objName: 'objName', //业务编码
        actionType: 'add', //动作类型 add edit view create,默认add
        url: '', //取数url
        data: {},//传入数据
        isCompanyReadonly: false, //公司是否只读
        company: '世纪鼎利', //默认公司值
        companyId: 'dl', //默认公司值
        isRequired: true,//是否费用归属必填
        costType: ''// 费用类型
    };

    //费用归属部门数组 - 用于缓存数据
    var expenseSaleDept;
    var expenseContractDept;

    //================== 内部方法 ====================//
    //初始化费用类型
    function initCostType(thisObj) {
        var tableStr = '<table class="form_in_table" id="' + defaults.myId + 'tbl">' +
            '<tr id="feeTypeTr">' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle" class="red">请选择费用类型</span></td>' +
            '<td class = "form_text_right" colspan="5">' +
            '<input type="radio" name="' + defaults.objName + '[detailType]" value="1"/> 部门费用 ' +
            '<input type="radio" name="' + defaults.objName + '[detailType]" value="2"/> 合同项目费用 ' +
            '<input type="radio" name="' + defaults.objName + '[detailType]" value="3"/> 研发费用 ' +
            '<input type="radio" name="' + defaults.objName + '[detailType]" value="4"/> 售前费用 ' +
            '<input type="radio" name="' + defaults.objName + '[detailType]" value="5"/> 售后费用 ' +
            '</td>' +
            '</tr>' +
            '</table>';
        $(thisObj).html(tableStr);
        $("input[name='" + defaults.objName + "[detailType]']").each(function () {
            $(this).bind('click', resetCombo);

            //按费用类型绑定触发事件
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

    //重置各类下拉
    function resetCombo() {
        defaults.costType = this.value;
        $("#detailTypeTitle").html('费用类型').removeClass('red').addClass('blue');
        $("#projectName").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
        $("#projectCode").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
        $("#costBelongCom").yxcombogrid_branch('remove');
        $(".feeTypeContent").remove();
    }

    /****************************** 不同费用调用 ***********************************/
    //初始化部门 TODO
    function initDept() {
        var thisClass, thisCompany, thisCompanyId;
        if (defaults.isCompanyReadonly == true) {
            thisClass = "readOnlyTxtNormal";
        } else {
            thisClass = "txt";
        }
        thisCompany = defaults.company;
        thisCompanyId = defaults.companyId;

        //默认获取
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
            '<td class = "form_text_left_three"><span class="blue">费用归属公司</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="' + thisClass + '" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + thisCompany + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
            '<td class = "form_text_right" colspan="3">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" value="' + deptName + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" value="' + deptId + '"/>' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        if (!defaults.isCompanyReadonly == true) {
            //公司渲染
            $("#costBelongCom").yxcombogrid_branch({
                hiddenId: 'costBelongComId',
                height: 250,
                isFocusoutCheck: false,
                gridOptions: {
                    showcheckbox: false
                }
            });
        }
        //费用归属部门选择
        $("#costBelongDeptName").yxselect_dept({
            hiddenId: 'costBelongDeptId',
            unDeptFilter: $('#unDeptFilter').val(),
            unSltDeptFilter: $('#unSltDeptFilter').val()
        });
    }

    //初始化合同项目 TODO
    function initContractProject() {
        var thisCompany = defaults.company;
        var thisCompanyId = defaults.companyId;
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">项目编号</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[projectCode]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目名称</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" />' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" id="projectType"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" />' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" />' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目经理</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" />' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //合同项目渲染
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

                        //重置费用归属部门
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });

        //工程项目渲染
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
                        //重置费用归属部门
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });
    }

    //初始化研发项目 TODO
    function initRdProject() {
        var thisCompany = defaults.company;
        var thisCompanyId = defaults.companyId;
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">项目编号</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[projectCode]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目名称</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" />' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" id="projectType"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" />' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" />' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目经理</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" />' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //研发项目渲染
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

                        //重置费用归属部门
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });

        //研发项目渲染
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

                        //重置费用归属部门
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });
    }

    //初始化售前 TODO 售前
    function initSale() {
        var thisCompany = defaults.company;
        var thisCompanyId = defaults.companyId;
        var salesAreaStr = '';
        if (defaults.objName == 'specialapply[costbelong]') {// 特别事项申请售前费用类型的添加归属区域字段 关联PMS2383
            salesAreaStr =
                '<td class = "form_text_left_three">费用归属区域</td>' +
                '<td class = "form_text_right">' +
                '<select id="areaOpt" class="txt" style="display:none"></select>' +
                '<input type="text" class="readOnlyTxtNormal" id="areaRead" value="" readonly="readonly"/>' +
                '<input type="hidden" class="txt" id="area" name="' + defaults.objName + '[salesArea]" style="width:202px;"/>' +
                '<input type="hidden" id="areaId" name="' + defaults.objName + '[salesAreaId]" />' +
                '</td>';
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">试用项目编号</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[projectCode]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three">试用项目名称</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" />' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" />' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">项目经理</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" />' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">商机编号</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="chanceCode" name="' + defaults.objName + '[chanceCode]"/>' +
            '<input type="hidden" id="chanceId" name="' + defaults.objName + '[chanceId]" />' +
            '</td>' +
            '<td class = "form_text_left_three">商机名称</td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="chanceName" name="' + defaults.objName + '[chanceName]"/>' +
            '</td>' +
            '<td class = "form_text_left_three">客户名称</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="customerName" name="' + defaults.objName + '[customerName]"/>' +
            '<input type="hidden" id="customerId" name="' + defaults.objName + '[customerId]" />' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">客户省份</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="province" name="' + defaults.objName + '[province]" style="width:202px;"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">客户城市</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="city" name="' + defaults.objName + '[city]" style="width:202px;"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">客户类型</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="customerType" name="' + defaults.objName + '[customerType]" style="width:202px;"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">销售负责人</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="' + defaults.objName + '[costBelonger]" readonly="readonly"/>' +
            '<input type="hidden" class="ciClass" id="costBelongerId" name="' + defaults.objName + '[costBelongerId]" />' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" style="width:202px;"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" />' +
            '</td>' +
            salesAreaStr +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //商机编号
        var codeObj = $("#chanceCode");
        if (codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "输入完整的商机编号，系统自动匹配相关信息";
        var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机编号'>&nbsp;</span>");
        $button.click(function () {
            if (codeObj.val() == "") {
                alert('请输入一个商机编号');
                return false;
            }
        });

        //添加清空按钮
        var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
        $button2.click(function () {
            if (codeObj.val() != "") {
                //清除销售信息
                clearSale();
                openInput('chance');
            }
        });
        codeObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

        //商机名称
        var nameObj = $("#chanceName");
        if (nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "输入完整的商机名称，系统自动匹配相关信息";
        var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机名称'>&nbsp;</span>");
        $button.click(function () {
            if (nameObj.val() == "") {
                alert('请输入一个商机名称');
                return false;
            }
        });

        //添加清空按钮
        var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
        $button2.click(function () {
            if (nameObj.val() != "") {
                //清除销售信息
                clearSale();
                openInput('chance');
            }
        });
        nameObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

        //试用项目渲染
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
                        //禁用其他入口
                        closeInput('trialPlan', data.id);

                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        //重新设置费用归属部门
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

                        //查询使用项目信息
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

                                //销售负责人
                                if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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

                                //重载客户城市
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

                    //开启其他入口
                    openInput('trialPlan');
                }
            }
        }).attr('class', 'txt');

        //项目编号
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
                        //禁用其他入口
                        closeInput('trialPlan', data.id);

                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // 获取项目经理的部门
//						var userInfo = getUserInfo(data.managerId);

                        //重新设置费用归属部门
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

                        //查询使用项目信息
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

                                //销售负责人
                                if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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

                                //重载客户城市
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

                    //开启其他入口
                    openInput('trialPlan');
                }
            }
        }).attr('class', 'txt');

        //初始化客户
        initCustomer();

        //客户类型
        $('#customerType').combobox({
            url: 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                //设置销售负责人
                changeCustomerType();
            },
            onUnselect: function (obj) {
                //设置销售负责人
                changeCustomerType();
            }
        });

        //省份渲染
        var cityObj = $('#city');
        $('#province').combobox({
            url: 'index1.php?model=system_procity_province&action=listJsonSort',
            valueField: 'provinceName',
            textField: 'provinceName',
            editable: false,
            onSelect: function (obj) {
                //根据省份读取城市
                cityObj.combobox({
                    url: "?model=system_procity_city&action=listJson&tProvinceName=" + obj.provinceName
                });
                if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// 特别事项申请售前费用类型的添加归属区域字段 关联PMS2383
                    setSalesArea();
                }
            }
        });

        //城市渲染
        cityObj.combobox({
            textField: 'cityName',
            valueField: 'cityName',
            multiple: true,
            editable: false,
            formatter: function (obj) {
                return "<input type='checkbox' id='city_" + obj.cityName + "' value='" + obj.cityName + "'/> " + obj.cityName;
            },
            onSelect: function (obj) {
                //checkbox设值
                $("#city_" + obj.cityName).attr('checked', true);
                //设置销售负责人
                changeCustomerType('cityChange');
            },
            onUnselect: function (obj) {
                //checkbox设值
                $("#city_" + obj.cityName).attr('checked', false);
                //设置销售负责人
                changeCustomerType('cityChange');
            }
        });

        //费用归属部门
        if (expenseSaleDept == undefined) {
            //ajax获取销售负责人
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

    //初始化售后 TODO 售后
    function initContract() {
        var thisCompany = defaults.company;
        var thisCompanyId = defaults.companyId;
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">合同编号</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt ciClass" id="contractCode" name="' + defaults.objName + '[contractCode]"/>' +
            '<input type="hidden" class="ciClass" id="contractId" name="' + defaults.objName + '[contractId]" />' +
            '<input type="hidden" class="ciClass" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" class="ciClass" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">合同名称</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt ciClass" id="contractName" name="' + defaults.objName + '[contractName]"/>' +
            '</td>' +
            '<td class = "form_text_left_three">客户名称</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="customerName" name="' + defaults.objName + '[customerName]" readonly="readonly"/>' +
            '<input type="hidden" class="ciClass" id="customerId" name="' + defaults.objName + '[customerId]" />' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">客户省份</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="province" name="' + defaults.objName + '[province]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three">客户城市</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="city" name="' + defaults.objName + '[city]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three">客户类型</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="customerType" name="' + defaults.objName + '[customerType]" readonly="readonly"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">销售负责人</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="' + defaults.objName + '[costBelonger]" readonly="readonly"/>' +
            '<input type="hidden" class="ciClass" id="costBelongerId" name="' + defaults.objName + '[costBelongerId]" />' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
            '<td class = "form_text_right" colspan="3">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" style="width:202px;"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" />' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //费用归属部门
        if (expenseContractDept == undefined) {
            //ajax获取销售负责人
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

        //编号搜索渲染
        var codeObj = $("#contractCode");
        if (codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "输入完整的合同编号，系统自动匹配相关信息";
        var $button = $("<span class='search-trigger' id='contractCodeSearch' title='合同编号'>&nbsp;</span>");
        $button.click(function () {
            if ($("#contractCode").val() == "") {
                alert('请输入一个合同编号');
                return false;
            }
        });

        //添加清空按钮
        var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
        $button2.click(function () {
            $(".ciClass").val('');
        });
        codeObj.bind('blur', getContractInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true);

        //名称搜索渲染
        var nameObj = $("#contractName");
        if (nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "输入完整的合同名称，系统自动匹配相关信息";
        var $button = $("<span class='search-trigger' id='contractCodeSearch' title='合同名称'>&nbsp;</span>");
        $button.click(function () {
            if ($("#contractName").val() == "") {
                alert('请输入一个合同名称');
                return false;
            }
        });

        //添加清空按钮
        var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
        $button2.click(function () {
            $(".ciClass").val('');
        });
        nameObj.bind('blur', getContractInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true);
    }

    // 根据归属区域带出的销售负责人设置对应值 createdBy haojin 2017-01-24 PMS2418
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

    // 异步设置销售区域信息 createdBy haojin 2017-01-12 PMS2383 (字段特别事项申请售前费用开放了此函数【defaults.objName == 'specialapply[costbelong]'】)
    function setSalesArea(chanceArr) {
        var areaCode = '';
        var areaName = '';
        var areaArr = [];
        var province = $("#province").combobox('getValue');
        var customerType = $("#customerType").combobox('getValue');
        var applyUserId = $("#applyUserId").val();
        if (chanceArr) {// 如果有商机信息,直接带出相应的销售区域
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

        // 根据上面获取到的销售区域进行相应的处理
        if (areaArr.length > 1 && defaults.actionType == 'edit' && initNum == 0 && !chanceArr) {
            initNum += 1;
            var optionsStr = '<option value="">..请选择..</option>';
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

                // 自动填充销售负责人信息
                setSalesPerson(sales_Person, sales_PersonId);
                // $('#costBelonger').val(sales_Person);
                // $('#costBelongerId').val(sales_PersonId);
            });
        }
        else if ((areaCode != '' && areaName != '') || areaArr.length > 0) {
            // 初始化销售区域信息
            $('#areaRead').val('');
            $('#areaRead').show();
            $('#areaOpt').html('');
            $('#areaOpt').hide();
            $('#area').val('');
            $('#areaId').val('');
            if (areaArr.length > 1) {// 有多个归属区域
                // 显示选项
                var optionsStr = '<option value="">..请选择..</option>';
                $.each(areaArr, function () {
                    if (chanceArr && $(this)[0]['id'] == areaCode) {// 如果有商机信息,直接带出相应的销售区域
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

                    // 自动填充销售负责人信息
                    setSalesPerson(sales_Person, sales_PersonId);
                    // $('#costBelonger').val(sales_Person);
                    // $('#costBelongerId').val(sales_PersonId);
                });
            } else if (areaArr.length > 0 && areaArr.length <= 1) {// 有单个归属区域
                if (!chanceArr) {// 如果没有商机信息,带出相应的销售区域,否则直接用商机的区域
                    areaCode = areaArr[0].id;
                    areaName = areaArr[0].areaName;
                }

                $('#areaRead').val(areaName);
                $('#area').val(areaName);
                $('#areaId').val(areaCode);
                $('#areaRead').show();
                $('#areaOpt').html('');
                $('#areaOpt').hide();

                // 自动填充销售负责人信息
                setSalesPerson(areaArr[0]['personNames'], areaArr[0]['personIds']);
                // $('#costBelonger').val(areaArr[0]['personNames']);
                // $('#costBelongerId').val(areaArr[0]['personIds']);

            } else {// 只有一个归属区域
                $('#areaRead').val(areaName);
                $('#area').val(areaName);
                $('#areaId').val(areaCode);
                $('#areaRead').show();
                $('#areaOpt').html('');
                $('#areaOpt').hide();
            }
        }
        else if (areaArr.length == 0) {// 查不到对应的区域信息时
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

    //异步匹配合同信息
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
                        alert('系统中存在【' + dataArr.thisLength + '】条名称为【' + contractName + '】的合同，请通过合同编号匹配合同信息！');
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
                    alert('没有查询到相关合同信息');
                    $(".ciClass").val('');
                }
            }
        });
    }

    //初始化客户
    function initCustomer() {
        //先移除
        $("#customerName").yxcombogrid_customer('remove').yxcombogrid_customer({
            hiddenId: 'customerId',
            height: 300,
            gridOptions: {
                showcheckbox: false,
                event: {
                    'row_dblclick': function (e, row, data) {
                        //关闭其他入口
                        closeInput('customer');

                        $("#province").combobox('setValue', data.Prov);

                        var customerTypeName = getDataByCode(data.TypeOne);
                        $("#customerType").combobox('setValue', customerTypeName);

                        //重载客户城市
                        $("#city").combobox({
                            url: "?model=system_procity_city&action=listJson&tProvinceName=" + data.Prov
                        }).combobox('setValue', data.City);

                        //销售负责人
                        if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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

                        // 联动带出销售区域
                        setSalesArea();
                    }
                }
            },
            event: {
                'clear': function () {
                    clearSale();

                    //开启其他入口
                    openInput('customer');
                }
            }
        }).attr('readonly', false).attr('class', 'txt');
    }

    //获取商机信息
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
                        alert('系统中存在【' + dataArr.thisLength + '】条名称为【' + chanceName + '】的商机，请通过商机编号匹配商机信息！');
                        clearSale();
                    } else {
                        if (typeof(thisType) == 'object') {
                            //关闭其他入口
                            closeInput('chance');
                        }

                        //商机信息赋值
                        if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// 特别事项申请售前费用类型的添加归属区域字段 关联PMS2383
                            setSalesArea(dataArr);
                        }
                        chanceSetValue(dataArr, thisType);
                    }
                } else {
                    alert('没有查询到相关商机信息');
                    clearSale();
                }
            }
        });
    }

    //清空销售信息
    function clearSale() {
        //清空省市客户属性
        clearPCC();

        $("#chanceName").val('');
        $("#chanceId").val('');
        $("#chanceCode").val('');
        $("#customerName").val('');
        $("#customerId").val('');

        //重置费用归属部门
        $("#costBelongDeptName").combobox("setValue", '');
        $("#costBelongDeptId").val('');
        if (isCombobox('costBelonger') == 1) {
            $("#costBelonger").combobox("setValue", '');
            $("#costBelongerId").val('');
        } else {
            $("#costBelonger").val('');
            $("#costBelongerId").val('');
        }

        // 重设销售区域
        if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// 特别事项申请售前费用类型的添加归属区域字段 关联PMS2383
            setSalesArea();
        }
    }

    //判断对象的combobox是否已存在
    function isCombobox(objCode) {
        if ($("#" + objCode).attr("comboname")) {
            return 1;
        } else {
            return 0;
        }
    }

    //清空客户省份、城市、客户类型系列
    function clearPCC() {
        //清空省份信息
        $("#province").combobox('setValue', '');

        //清空客户类型信息
        $("#customerType").combobox('setValue', '');

        var cityObj = $("#city");
        mulSelectClear(cityObj);
    }

    //多选清空 - 用于清空多选下拉的隐藏项
    function mulSelectClear(thisObj) {
        thisObj.combobox("setValues", "");
        $("#" + thisObj.attr('id') + "Hidden").val('');
        //清空复选框
        if (thisObj.attr("id") == 'city') {
            $("input:checkbox[id^='" + thisObj.attr("id") + "_']").attr("checked", false);
        } else {
            $("input:checkbox[id^='customerType_']").attr("checked", false);
        }
    }

    // 禁用其他入口
    function closeInput(thisType, projectId) {
        //项目id获取
        if (projectId == undefined) {
            var projectId = $("#projectId").val();//项目id
        }
        //如果没有填入类型，则自行判断
        if (thisType == undefined) {
            var chanceId = $("#chanceId").val();//商机id
            var customerId = $("#customerId").val();//客户id
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

            //清除商机的渲染
            clearInputSet('chanceCode');
            clearInputSet('chanceName');
        } else if (thisType == 'customer') {
            //项目
            $("#projectCode").attr("class", 'readOnlyTxtNormal').attr('readonly', true).yxcombogrid_esmproject('remove');
            $("#projectName").attr("class", 'readOnlyTxtNormal').attr('readonly', true).yxcombogrid_esmproject('remove');

            //商机
            $("#chanceCode").attr("class", 'readOnlyTxtNormal').attr('readonly', true);
            $("#chanceName").attr("class", 'readOnlyTxtNormal').attr('readonly', true);

            //清除商机的渲染
            clearInputSet('chanceCode');
            clearInputSet('chanceName');
        } else if (thisType == 'chance') {
            //项目
            $("#projectCode").attr("class", 'readOnlyTxtNormal').attr('readonly', true).yxcombogrid_esmproject('remove');
            $("#projectName").attr("class", 'readOnlyTxtNormal').attr('readonly', true).yxcombogrid_esmproject('remove');
            $("#customerName").attr("class", 'readOnlyTxtNormal').attr('readonly', true).yxcombogrid_customer('remove');
        }
    }

    //启用其他入口
    function openInput(thisType) {
        if (thisType == 'trialPlan') {
            //重新实例化客户选择
            initCustomer();

            //商机编号
            var codeObj = $("#chanceCode");
            if (codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true) {
                return false;
            }
            var title = "输入完整的商机编号，系统自动匹配相关信息";
            var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机编号'>&nbsp;</span>");
            $button.click(function () {
                if (codeObj.val() == "") {
                    alert('请输入一个商机编号');
                    return false;
                }
            });

            //添加清空按钮
            var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
            $button2.click(function () {
                if (codeObj.val() != "") {
                    //清除销售信息
                    clearSale();
                    openInput('chance');
                }
            });
            codeObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

            //商机名称
            var nameObj = $("#chanceName");
            if (nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true) {
                return false;
            }
            var title = "输入完整的商机名称，系统自动匹配相关信息";
            var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机名称'>&nbsp;</span>");
            $button.click(function () {
                if (nameObj.val() == "") {
                    alert('请输入一个商机名称');
                    return false;
                }
            });

            //添加清空按钮
            var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
            $button2.click(function () {
                if (nameObj.val() != "") {
                    //清除销售信息
                    clearSale();
                    openInput('chance');
                }
            });
            nameObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');
        } else if (thisType == 'customer') {
            //项目
            initTrialproject();

            $("#customerName").attr("class", 'txt').attr('readonly', false);

            //商机编号
            var codeObj = $("#chanceCode");
            if (codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true) {
                return false;
            }
            var title = "输入完整的商机编号，系统自动匹配相关信息";
            var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机编号'>&nbsp;</span>");
            $button.click(function () {
                if (codeObj.val() == "") {
                    alert('请输入一个商机编号');
                    return false;
                }
            });

            //添加清空按钮
            var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
            $button2.click(function () {
                if (codeObj.val() != "") {
                    //清除销售信息
                    clearSale();
                    openInput('chance');
                }
            });
            codeObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

            //商机名称
            var nameObj = $("#chanceName");
            if (nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true) {
                return false;
            }
            var title = "输入完整的商机名称，系统自动匹配相关信息";
            var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机名称'>&nbsp;</span>");
            $button.click(function () {
                if (nameObj.val() == "") {
                    alert('请输入一个商机名称');
                    return false;
                }
            });

            //添加清空按钮
            var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
            $button2.click(function () {
                if (nameObj.val() != "") {
                    //清除销售信息
                    clearSale();
                    openInput('chance');
                }
            });
            nameObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');
        } else if ((typeof(thisType) == "object" && thisType.data == 'chance') || thisType == 'chance') {
            //项目
            initTrialproject();

            //重新实例化客户选择
            initCustomer();
        }

        //显示省份的下拉项
        $("#province").combobox('enable');
        $('#city').combobox('enable');
        $("#customerType").combobox('enable');
        $("#costBelonger").combobox('enable');
    }

    //清除填入渲染
    function clearInputSet(thisId) {
        //渲染一个匹配按钮
        var thisObj = $("#" + thisId);
        //去除第一个按钮
        var $button = thisObj.next();
        thisObj.width(thisObj.width() + $button.width()).attr("wchangeTag2", false);
        $button.remove();

        //去除第二个按钮
        $button = thisObj.next();
        thisObj.width(thisObj.width() + $button.width()).attr("wchangeTag2", false);
        $button.remove();
    }

    //试用项目渲染 -- 试用项目
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
                        //禁用其他入口
                        closeInput('trialPlan', data.id);

                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // 获取项目经理的部门
//						var userInfo = getUserInfo(data.managerId);

                        //重新设置费用归属部门
                        $('#costBelongDeptName').combobox({
                            data: [{text: data.deptName, value: data.deptId}],
                            valueField: 'text',
                            textField: 'text',
                            editable: false,
                            onSelect: function (obj) {
                                $("#costBelongDeptId").val(obj.value);
                                //所属板块默认带出费用归属部门所属板块
                                setModule();
                            }
                        }).combobox('setValue', data.deptName);
                        $("#costBelongDeptId").val(data.deptId);

                        //查询使用项目信息
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

                                //销售负责人
                                if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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

                                //重载客户城市
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

                    //开启其他入口
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
                        //禁用其他入口
                        closeInput('trialPlan', data.id);

                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // 获取项目经理的部门
//						var userInfo = getUserInfo(data.managerId);

                        //重新设置费用归属部门
                        $('#costBelongDeptName').combobox({
                            data: [{text: data.deptName, value: data.deptId}],
                            valueField: 'text',
                            textField: 'text',
                            editable: false,
                            onSelect: function (obj) {
                                $("#costBelongDeptId").val(obj.value);
                                //所属板块默认带出费用归属部门所属板块
                                setModule();
                            }
                        }).combobox('setValue', data.deptName);
                        $("#costBelongDeptId").val(data.deptId);

                        //查询使用项目信息
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

                                //销售负责人
                                if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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

                                //重载客户城市
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

                    //开启其他入口
                    openInput('trialPlan');
                }
            }
        }).attr('class', 'txt');
    }

    //选择客户类型
    function changeCustomerType(thisType) {
        var chanceId = $("#chanceId").val();
        var customerId = $("#customerId").val();
        var salesAreaId = $("#areaId").val();
        if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// 特别事项申请售前费用类型的添加归属区域字段 关联PMS2383
            if (thisType != 'cityChange') {// 更改城市,不影响销售区域
                setSalesArea();
            }
        }
        if ((chanceId == "" || chanceId == '0') && (customerId == "" || customerId == '0') && (salesAreaId == '' || salesAreaId == undefined) && defaults.objName != 'specialapply[costbelong]' && defaults.costType != 4) {
            var customerType = $('#customerType').combobox('getValue');//客户类型
            var province = $('#province').combobox('getValue');//省份
            var city = $('#city').combobox('getValues').toString();//城市
            if (province && city && customerType) {
                //ajax获取销售负责人
                var responseText = $.ajax({
                    url: 'index1.php?model=system_saleperson_saleperson&action=getSalePerson',
                    data: {"province": province, "city": city, 'customerTypeName': customerType},
                    type: "POST",
                    async: false
                }).responseText;

                //有返回值
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
                //销售负责人
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

    //ajax获取试用项目申请信息
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

    //商机设值信息
    function chanceSetValue(dataArr, thisType) {
        $("#chanceCode").val(dataArr.chanceCode);
        $("#chanceId").val(dataArr.id);
        $("#chanceName").val(dataArr.chanceName);
        $("#customerId").val(dataArr.customerId);
        $("#customerName").val(dataArr.customerName);

        $("#province").combobox('setValue', dataArr.Province);

        //重载客户城市
        reloadCity(dataArr.Province);
        $("#city").combobox('setValue', dataArr.City);

        //客户类型
        $("#customerType").combobox('setValue', dataArr.customerTypeName);

        //销售负责人
        if (defaults.objName != 'specialapply[costbelong]' && defaults.costType != 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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

    //重新载入城市
    function reloadCity(data) {
        $('#city').combobox({
            url: "?model=system_procity_city&action=listJson&tProvinceName=" + data
        });
    }

    //*********************** TODO 查看部分 *********************/
    //初始化费用内容
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

    //初始化部门
    function initDeptView(objInfo) {
        var tableStr = '<table class="form_in_table" id="' + defaults.myId + 'tbl">' +
            '<tr id="feeTypeTr">' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle">费用类型</span></td>' +
            '<td class = "form_text_right" colspan="5">' +
            '部门费用' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">费用归属公司</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.costBelongCom +
            '</td>' +
            '<td class = "form_text_left_three">费用归属部门</td>' +
            '<td class = "form_text_right" colspan="3">' +
            objInfo.costBelongDeptName +
            '</td>' +
            '</tr>' +
            '</table>';
        $("#" + defaults.myId).html(tableStr);
    }

    //初始化合同项目
    function initProjectView(objInfo) {
        var projectView = objInfo.detailType == '2' ? '合同项目费用' : '研发费用';
        var tableStr = '<table class="form_in_table" id="' + defaults.myId + 'tbl">' +
            '<tr id="feeTypeTr">' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle">费用类型</span></td>' +
            '<td class = "form_text_right" colspan="5">' +
            projectView +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">项目编号</span></td>' +
            '<td class = "form_text_right_three">' +
            objInfo.projectCode +
            '</td>' +
            '<td class = "form_text_left_three">项目名称</span></td>' +
            '<td class = "form_text_right_three">' +
            objInfo.projectName +
            '</td>' +
            '<td class = "form_text_left_three">项目经理</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.proManagerName +
            '</td>' +
            '</tr>' +
            '</table>';
        $("#" + defaults.myId).html(tableStr);
    }

    //初始化售前
    function initSaleView(objInfo) {
        var tableStr = '<table class="form_in_table" id="' + defaults.myId + 'tbl">' +
            '<tr id="feeTypeTr">' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle">费用类型</span></td>' +
            '<td class = "form_text_right_three" colspan="5">' +
            '售前费用' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">试用项目编号</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.projectCode +
            '</td>' +
            '<td class = "form_text_left_three">试用项目名称</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.projectName +
            '</td>' +
            '<td class = "form_text_left_three">项目经理</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.proManagerName +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">商机编号</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.chanceCode +
            '</td>' +
            '<td class = "form_text_left_three">商机名称</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.chanceName +
            '</td>' +
            '<td class = "form_text_left_three">客户名称</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.customerName +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">客户省份</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.province +
            '</td>' +
            '<td class = "form_text_left_three">客户城市</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.city +
            '</td>' +
            '<td class = "form_text_left_three">客户类型</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.customerType +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">销售负责人</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.costBelonger +
            '</td>' +
            '<td class = "form_text_left_three">费用归属部门</td>' +
            '<td class = "form_text_right">' +
            objInfo.costBelongDeptName +
            '</td>' +
            '<td class = "form_text_left_three">费用归属区域</td>' +
            '<td class = "form_text_right">' +
            objInfo.salesArea +
            '</td>' +
            '</tr>' +
            '</table>';
        $("#" + defaults.myId).html(tableStr);
    }

    //初始化售后
    function initContractView(objInfo) {
        var tableStr = '<table class="form_in_table" id="' + defaults.myId + 'tbl">' +
            '<tr id="feeTypeTr">' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle">费用类型</span></td>' +
            '<td class = "form_text_right" colspan="5">' +
            '售后费用' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">合同编号</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.contractCode +
            '</td>' +
            '<td class = "form_text_left_three">合同名称</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.contractName +
            '</td>' +
            '<td class = "form_text_left_three">客户名称</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.customerName +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">客户省份</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.province +
            '</td>' +
            '<td class = "form_text_left_three">客户城市</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.city +
            '</td>' +
            '<td class = "form_text_left_three">客户类型</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.customerType +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">销售负责人</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.costBelonger +
            '</td>' +
            '<td class = "form_text_left_three">费用归属部门</td>' +
            '<td class = "form_text_right" colspan="3">' +
            objInfo.costBelongDeptName +
            '</td>' +
            '</tr>' +
            '</table>';
        $("#" + defaults.myId).html(tableStr);
    }

    //********************* TODO 编辑部分 ************************/
    //初始化费用类型
    function initCostTypeEdit(thisObj, objInfo) {
        initCostType(thisObj);
        //附选中值
        $("input[name='" + defaults.objName + "[detailType]']").each(function (i, n) {
            if (this.value == objInfo.detailType) {
                $(this).attr("checked", this);
                return false;
            }
        });
        $("#detailTypeTitle").html('费用类型').removeClass('red').addClass('blue');
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

    //TODO 初始化部门
    function initDeptEdit(objInfo) {
        //初始值赋予
        var costBelongCom = '', costBelongComId = '', costBelongDeptName = '', costBelongDeptId = '', id = '';
        if (objInfo) {
            costBelongCom = objInfo.costBelongCom;
            costBelongComId = objInfo.costBelongComId;
            costBelongDeptName = objInfo.costBelongDeptName;
            costBelongDeptId = objInfo.costBelongDeptId;
            id = objInfo.id;
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">费用归属公司</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + costBelongCom + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + costBelongComId + '"/>' +
            '<input type="hidden" name="' + defaults.objName + '[id]" value="' + id + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
            '<td class = "form_text_right" colspan="3">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" value="' + costBelongDeptName + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);
        //公司渲染
        $("#costBelongCom").yxcombogrid_branch({
            hiddenId: 'costBelongComId',
            height: 250,
            isFocusoutCheck: false,
            gridOptions: {
                showcheckbox: false
            }
        });
        //费用归属部门选择
        $("#costBelongDeptName").yxselect_dept({
            hiddenId: 'costBelongDeptId',
            unDeptFilter: $('#unDeptFilter').val(),
            unSltDeptFilter: $('#unSltDeptFilter').val()
        });
    }

    // TODO 初始化合同项目
    function initContractProjectEdit(objInfo) {
        //初始值赋予
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
            '<td class = "form_text_left_three"><span class="blue">项目编号</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[projectCode]" readonly="readonly" value="' + projectCode + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目名称</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly" value="' + projectName + '"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" value="' + projectId + '"/>' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" value="' + projectType + '"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" value="' + costBelongDeptName + '"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '<input type="hidden" name="' + defaults.objName + '[id]" value="' + id + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目经理</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly" value="' + proManagerName + '"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" value="' + proManagerId + '"/>' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //合同项目渲染
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

                        //重置费用归属部门
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });


        //工程项目渲染
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
                        //重置费用归属部门
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });
    }

    //TODO 初始化研发项目
    function initRdProjectEdit(objInfo) {
        //初始值赋予
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
            '<td class = "form_text_left_three"><span class="blue">项目编号</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[projectCode]" readonly="readonly" value="' + projectCode + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目名称</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly" value="' + projectName + '"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" value="' + projectId + '"/>' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" value="' + projectType + '"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" value="' + costBelongDeptName + '"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '<input type="hidden" name="' + defaults.objName + '[id]" value="' + id + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目经理</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly" value="' + proManagerName + '"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" value="' + proManagerId + '"/>' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //研发项目渲染
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

                        //重置费用归属部门
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });

        //研发项目渲染
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

                        //重置费用归属部门
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                }
            }
        });
    }

    //TODO 初始化售前费用
    function initSaleEdit(objInfo) {
        defaults.costType = 4;
        //初始值赋予
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
        if (defaults.objName == 'specialapply[costbelong]') {// 特别事项申请售前费用类型的添加归属区域字段 关联PMS2383
            salesAreaStr =
                '<td class = "form_text_left_three">费用归属区域</td>' +
                '<td class = "form_text_right">' +
                '<select id="areaOpt" class="txt" style="display:none"></select>' +
                '<input type="text" class="readOnlyTxtNormal" id="areaRead" value="' + area + '" readonly="readonly"/>' +
                '<input type="hidden" class="txt" id="area" name="' + defaults.objName + '[salesArea]" style="width:202px;" value="' + area + '"/>' +
                '<input type="hidden" id="areaId" name="' + defaults.objName + '[salesAreaId]" value="' + areaId + '"/>' +
                '</td>';
        }
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">试用项目编号</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[projectCode]" readonly="readonly" value="' + projectCode + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">试用项目名称</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly" value="' + projectName + '"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" value="' + projectId + '"/>' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" value="' + projectType + '"/>' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[costBelongCom]" value="' + costBelongCom + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[costBelongComId]" value="' + costBelongComId + '"/>' +
            '<input type="hidden" name="' + defaults.objName + '[id]" value="' + id + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">项目经理</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" value="' + proManagerName + '" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" value="' + proManagerId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">商机编号</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="chanceCode" name="' + defaults.objName + '[chanceCode]" value="' + chanceCode + '"/>' +
            '<input type="hidden" id="chanceId" name="' + defaults.objName + '[chanceId]" value="' + chanceId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">商机名称</td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="chanceName" name="' + defaults.objName + '[chanceName]" value="' + chanceName + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">客户名称</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="customerName" name="' + defaults.objName + '[customerName]" value="' + customerName + '"/>' +
            '<input type="hidden" id="customerId" name="' + defaults.objName + '[customerId]" value="' + customerId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">客户省份</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="province" name="' + defaults.objName + '[province]" value="' + province + '" style="width:202px;"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">客户城市</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="city" name="' + defaults.objName + '[city]" value="' + city + '" style="width:202px;"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">客户类型</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="customerType" name="' + defaults.objName + '[customerType]" value="' + customerType + '" style="width:202px;"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">销售负责人</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="costBelonger" name="' + defaults.objName + '[costBelonger]" readonly="readonly" value="' + costBelonger + '"/>' +
            '<input type="hidden" id="costBelongerId" name="' + defaults.objName + '[costBelongerId]" value="' + costBelongerId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" value="' + costBelongDeptName + '" style="width:202px;"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '</td>' +
            salesAreaStr +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //商机编号
        var codeObj = $("#chanceCode");
        if (codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "输入完整的商机编号，系统自动匹配相关信息";
        var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机编号'>&nbsp;</span>");
        $button.click(function () {
            if (codeObj.val() == "") {
                alert('请输入一个商机编号');
                return false;
            }
        });

        //添加清空按钮
        var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
        $button2.click(function () {
            if (codeObj.val() != "") {
                //清除销售信息
                clearSale();
                openInput('chance');
            }
        });
        codeObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

        //商机名称
        var nameObj = $("#chanceName");
        if (nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "输入完整的商机名称，系统自动匹配相关信息";
        var $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机名称'>&nbsp;</span>");
        $button.click(function () {
            if (nameObj.val() == "") {
                alert('请输入一个商机名称');
                return false;
            }
        });

        //添加清空按钮
        var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
        $button2.click(function () {
            if (nameObj.val() != "") {
                //清除销售信息
                clearSale();
                openInput('chance');
            }
        });
        nameObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

        //试用项目渲染
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
                        //禁用其他入口
                        closeInput('trialPlan', data.id);

                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // 获取项目经理的部门
//						var userInfo = getUserInfo(data.managerId);

                        //重新设置费用归属部门
                        $('#costBelongDeptName').combobox({
                            data: [{text: data.deptName, value: data.deptId}],
                            valueField: 'text',
                            textField: 'text',
                            editable: false,
                            onSelect: function (obj) {
                                $("#costBelongDeptId").val(obj.value);
                                //所属板块默认带出费用归属部门所属板块
                                setModule();
                            }
                        }).combobox('setValue', data.deptName);
                        $("#costBelongDeptId").val(data.deptId);

                        //查询使用项目信息
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

                                //销售负责人
                                $('#costBelonger').val(trialProjectInfo.applyName);
                                $("#costBelongerId").val(trialProjectInfo.applyNameId);

                                //重载客户城市
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

                    //开启其他入口
                    openInput('trialPlan');
                }
            }
        }).attr('class', 'txt');

        //项目编号
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
                        //禁用其他入口
                        closeInput('trialPlan', data.id);

                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // 获取项目经理的部门
//						var userInfo = getUserInfo(data.managerId);

                        //重新设置费用归属部门
                        $('#costBelongDeptName').combobox({
                            data: [{text: data.deptName, value: data.deptId}],
                            valueField: 'text',
                            textField: 'text',
                            editable: false,
                            onSelect: function (obj) {
                                $("#costBelongDeptId").val(obj.value);
                                //所属板块默认带出费用归属部门所属板块
                                setModule();
                            }
                        }).combobox('setValue', data.deptName);
                        $("#costBelongDeptId").val(data.deptId);

                        //查询使用项目信息
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

                                //销售负责人
                                $('#costBelonger').val(trialProjectInfo.applyName);
                                $("#costBelongerId").val(trialProjectInfo.applyNameId);

                                //重载客户城市
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

                    //开启其他入口
                    openInput('trialPlan');
                }
            }
        }).attr('class', 'txt');

        //初始化客户
        initCustomer();

        //客户类型
        $('#customerType').combobox({
            url: 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                //设置销售负责人
                changeCustomerType();
            },
            onUnselect: function (obj) {
                //设置销售负责人
                changeCustomerType();
            }
        });

        //省份渲染
        var cityObj = $('#city');
        $('#province').combobox({
            url: 'index1.php?model=system_procity_province&action=listJsonSort',
            valueField: 'provinceName',
            textField: 'provinceName',
            editable: false,
            onSelect: function (obj) {
                //根据省份读取城市
                cityObj.combobox({
                    url: "?model=system_procity_city&action=listJson&tProvinceName=" + obj.provinceName
                });
                $("#city").combobox('setValue', "");// 清除原来的城市
                if (defaults.objName == 'specialapply[costbelong]' && defaults.costType == 4) {// 特别事项申请售前费用类型的添加归属区域字段 关联PMS2383
                    setSalesArea();
                }
            }
        });

        //城市渲染
        var cityArr = city.split(',');
        cityObj.combobox({
            url: "?model=system_procity_city&action=listJson&tProvinceName=" + province,
            textField: 'cityName',
            valueField: 'cityName',
            multiple: true,
            editable: false,
            formatter: function (obj) {
                //判断 如果没有初始化数组中，则选中
                if (cityArr.indexOf(obj.cityName) == -1) {
                    return "<input type='checkbox' id='city_" + obj.cityName + "' value='" + obj.cityName + "'/> " + obj.cityName;
                } else {
                    return "<input type='checkbox' id='city_" + obj.cityName + "' value='" + obj.cityName + "' checked='checked'/> " + obj.cityName;
                }
            },
            onSelect: function (obj) {
                //checkbox设值
                $("#city_" + obj.cityName).attr('checked', true);
                //设置销售负责人
                changeCustomerType('cityChange');
            },
            onUnselect: function (obj) {
                //checkbox设值
                $("#city_" + obj.cityName).attr('checked', false);
                //设置销售负责人
                changeCustomerType('cityChange');
            }
        });

        //费用归属部门
        if (expenseSaleDept == undefined) {
            //ajax获取销售负责人
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

        //调用一次禁用窗口
        closeInput();
        //调用一次设置销售负责人
        changeCustomerType('init');
    }

    //TODO 初始化售后费用
    function initContractEdit(objInfo) {
        //初始值赋予
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
            '<td class = "form_text_left_three"><span class="blue">合同编号</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="contractCode" name="' + defaults.objName + '[contractCode]" value="' + contractCode + '"/>' +
            '<input type="hidden" id="contractId" name="' + defaults.objName + '[contractId]" value="' + contractId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">合同名称</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="contractName" name="' + defaults.objName + '[contractName]" value="' + contractName + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">客户名称</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="customerName" name="' + defaults.objName + '[customerName]" readonly="readonly" value="' + customerName + '"/>' +
            '<input type="hidden" id="customerId" name="' + defaults.objName + '[customerId]" value="' + customerId + '"/>' +
            '<input type="hidden" name="' + defaults.objName + '[id]" value="' + id + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">客户省份</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="province" name="' + defaults.objName + '[province]" readonly="readonly" value="' + province + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">客户城市</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="city" name="' + defaults.objName + '[city]" readonly="readonly" value="' + city + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">客户类型</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="customerType" name="' + defaults.objName + '[customerType]" readonly="readonly" value="' + customerType + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">销售负责人</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="costBelonger" name="' + defaults.objName + '[costBelonger]" readonly="readonly" value="' + costBelonger + '"/>' +
            '<input type="hidden" id="costBelongerId" name="' + defaults.objName + '[costBelongerId]" value="' + costBelongerId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
            '<td class = "form_text_right" colspan="3">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[costBelongDeptName]" style="width:202px;" value="' + costBelongDeptName + '"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[costBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '</td>' +
            '</tr>';
        $("#" + defaults.myId + "tbl").append(tableStr);

        //费用归属部门
        if (expenseContractDept == undefined) {
            //ajax获取销售负责人
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

        //编号搜索渲染
        var codeObj = $("#contractCode");
        if (codeObj.attr('wchangeTag2') == 'true' || codeObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "输入完整的合同编号，系统自动匹配相关信息";
        var $button = $("<span class='search-trigger' id='contractCodeSearch' title='合同编号'>&nbsp;</span>");
        $button.click(function () {
            if ($("#contractCode").val() == "") {
                alert('请输入一个合同编号');
                return false;
            }
        });

        //添加清空按钮
        var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
        $button2.click(function () {
            $(".ciClass").val('');
        });
        codeObj.bind('blur', getContractInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true);

        //名称搜索渲染
        var nameObj = $("#contractName");
        if (nameObj.attr('wchangeTag2') == 'true' || nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        var title = "输入完整的合同名称，系统自动匹配相关信息";
        var $button = $("<span class='search-trigger' id='contractNameSearch' title='合同名称'>&nbsp;</span>");
        $button.click(function () {
            if ($("#contractName").val() == "") {
                alert('请输入一个合同名称');
                return false;
            }
        });

        //添加清空按钮
        var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
        $button2.click(function () {
            $(".ciClass").val('');
        });
        nameObj.bind('blur', getContractInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true);
    }

    //************************* 表单验证 ****************************/
    //表单验证方法
    function costCheckForm() {
        var detailType = $("input[name='" + defaults.objName + "[detailType]']:checked").val();
        if (detailType) {
            //类型 对应特殊验证
            switch (detailType) {
                case '1' :
                    if ($("#costBelongCom").val() == "") {
                        alert("没有填写费用归属公司");
                        return false;
                    }
                    if ($("#costBelongDeptName").val() == "") {
                        alert("没有填写费用归属部门");
                        return false;
                    }
                    break;
                case '2' :
                    if ($("#projectCode").val() == "") {
                        alert("请选择该笔费用所在工程项目");
                        return false;
                    }
                    break;
                case '3' :
                    if ($("#projectCode").val() == "") {
                        alert("请选择该笔费用所在研发项目");
                        return false;
                    }
                    break;
                case '4' :
                    if ($("#province").combobox('getValue') == "") {
                        alert("请选择客户所在省份");
                        return false;
                    }
                    if ($("#city").combobox('getValues') == "") {
                        alert("请选择客户所在城市");
                        return false;
                    }
                    if ($("#customerType").combobox('getValue') == "") {
                        alert("请选择客户类型");
                        return false;
                    }
                    if ($("#areaId").val() == "") {
                        alert("售前费用的归属区域不能为空。");
                        return false;
                    }
                    if ($("#costBelongerId").val() == "") {
                        alert("请录入销售负责人，销售负责人可由商机、客户名称自动带出，或者通过客户省份、城市、类型由系统匹配");
                        return false;
                    }
                    if ($("#costBelongDeptId").val() == "" || $("#costBelongDeptName").combobox('getValue') == "") {
                        alert("请选择费用归属部门");
                        return false;
                    }
                    break;
                case '5' :
                    if ($("#contractCode").val() == "") {
                        alert("请选择该笔费用归属合同");
                        return false;
                    }
                    if ($("#costBelongDeptId").val() == "" || $("#costBelongDeptName").combobox('getValue') == "") {
                        alert("请选择费用归属部门");
                        return false;
                    }
                    break;
                default :
                    break;
            }
            return true;
        } else {
            alert('请选择费用类型');
            return false;
        }
    }

    $.fn.costbelong = function (options) {
        //合并属性
        var options = $.extend(defaults, options);
        //支持选择器以及链式操作
        return this.each(function () {
            //赋值一个表明
            defaults.myId = this.id;
            var thisObj = $(this);//自己的对象

            //如果不是新增,那么获取一个方法
            if (defaults.actionType != 'add') {
                //ajax获取销售负责人
                var responseText = $.ajax({
                    url: defaults.url,
                    data: defaults.data,
                    type: "POST",
                    async: false
                }).responseText;
                var objInfo = eval("(" + responseText + ")");
            }
            if (defaults.actionType == 'view') {
                //初始化费用内容
                initCostTypeView(objInfo);
            } else {
                if (defaults.actionType == 'add') {
                    initCostType(thisObj);
                } else if (defaults.actionType == 'edit') {
                    initCostTypeEdit(thisObj, objInfo);
                }

                //绑定表单验证方法
                if (defaults.isRequired == true)
                    $("form").bind('submit', costCheckForm);
            }
        });
    };

    //根据用户id获取用户信息
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
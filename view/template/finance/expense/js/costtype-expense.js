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
        isCompanyReadonly: true, //公司是否只读
        isCompanyDefault: false, //公司是否默认
        company: '世纪鼎利', //默认公司值
        companyId: 'dl' //默认公司值
    };

    //费用归属部门数组 - 用于缓存数据
    var expenseSaleDept;
    var expenseContractDept;

    //================== 内部方法 ====================//
    //初始化费用类型
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
            '<td class = "form_text_left_three"><span id="detailTypeTitle" class="red">请选择费用类型</span></td>' +
            '<td class = "form_text_right" colspan="5">' +
            '<input type="radio" name="' + defaults.objName + '[DetailType]" id="dt1" value="1"/><label for="dt1">部门费用</label> ' +
            '<input type="radio" name="' + defaults.objName + '[DetailType]" id="dt2" value="2"/><label for="dt2">合同项目费用</label> ' +
            '<input type="radio" name="' + defaults.objName + '[DetailType]" id="dt3" value="3"/><label for="dt3">研发费用</label> ' +
            '<input type="radio" name="' + defaults.objName + '[DetailType]" id="dt4" value="4"/><label for="dt4">售前费用</label> ' +
            '<input type="radio" name="' + defaults.objName + '[DetailType]" id="dt5" value="5"/><label for="dt5">售后费用</label> ' +
            '&nbsp;&nbsp;' +
            '<img src="images/icon/view.gif"/>' +
            '<a href="#" title="类型说明" taget="_blank" id="fileId" onclick="window.open(\''+fileUrl+'\',\'_blank \');">报销说明</a>' +
            '<span class="blue" id="tipsView"></span>' +
            '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three"><span class="blue">费用期间</span></td>' +
            '<td class="form_text_right" colspan="5">' +
            '<input type="text" class="txtmiddle Wdate" name="' + defaults.objName + '[CostDateBegin]" id="CostDateBegin" onfocus="WdatePicker()" value="' + CostDateBegin + '"/>' +
            '&nbsp;至&nbsp;' +
            '<input type="text" class="txtmiddle Wdate" name="' + defaults.objName + '[CostDateEnd]" id="CostDateEnd" onfocus="WdatePicker()" value="' + CostDateEnd + '"/>' +
            '&nbsp;共&nbsp;' +
            '<input type="text" class="rimless_textB" style="width:50px;text-align:center" name="' + defaults.objName + '[days]" id="days" value="' + days + '"/>' +
            '<input type="hidden" id="periodDays" value="' + days + '"/>' +
            '&nbsp;天' +
            '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three"><span class="blue">事 由</span></td>' +
            '<td class="form_text_right" colspan="5">' +
            '<input type="text"  class="rimless_textB" style="width:760px" name="' + defaults.objName + '[Purpose]" id="Purpose" value="' + purpose + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr id="baseTr">' +
            '<td class="form_text_left_three"><span class="blue">报销人员</span></td>' +
            '<td class="form_text_right">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[CostManName]" id="CostManName" value="' + InputManName + '" readonly="readonly"/>' +
            '<input type="hidden" name="' + defaults.objName + '[CostMan]" id="CostMan" value="' + InputMan + '"/>' +
            '</td>' +
            '<td class="form_text_left_three">报销人部门</td>' +
            '<td class="form_text_right">' +
            '<input type="text" class="readOnlyTxtNormal" name="' + defaults.objName + '[CostDepartName]" id="CostDepartName" value="' + deptName + '" readonly="readonly"/>' +
            '<input type="hidden" name="' + defaults.objName + '[CostDepartID]" id="CostDepartID" value="' + deptId + '"/>' +
            '</td>' +
            '<td class="form_text_left_three">报销人公司</td>' +
            '<td class="form_text_right">' +
            '<input type="text" name="' + defaults.objName + '[CostManCom]" id="CostManCom" class="readOnlyTxtNormal" value="' + companyName + '" readonly="readonly"/>' +
            '<input type="hidden" name="' + defaults.objName + '[CostManComId]" id="CostManComId" value="' + companyId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three">同 行 人</td>' +
            '<td class="form_text_right_three">' +
            '<input type="text" class="txt" readonly="readonly" name="' + defaults.objName + '[memberNames]" id="memberNames" value="' + memberNames + '"/>' +
            '<input type="hidden" name="' + defaults.objName + '[memberIds]" id="memberIds" value="' + memberIds + '"/>' +
            '</td>' +
            '<td class="form_text_left_three">同行人数</td>' +
            '<td class="form_text_right" colspan="3" id="memberNumberTr">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[memberNumber]" id="memberNumber" value="' + memberNumber + '"/>' +
            salesAreaStr +
            '</td>' +
            '<td class="form_text_left_three" id="feeManTr"><span class="blue">费用承担人</span></td>' +
            '<td class="form_text_right_three">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[feeMan]" id="feeMan" value="' + feeMan + '" readonly="readonly"/>' +
            '<input type="hidden" name="' + defaults.objName + '[feeManId]" id="feeManId" value="' + feeManId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr style="display:none;">' +
            '<td class="form_text_left_three"><span class="blue">所属板块</span></td>' +
            '<td class="form_text_right" colspan="5">' +
            '<select class="txt" name="' + defaults.objName + '[module]" id="module"/>' +
            '</td>' +
            '</tr>';
        //附件获取
        var fileInfo = '';
        var fileInfoObj = $("#fileInfo");
        if (fileInfoObj.length > 0) {
            fileInfo = fileInfoObj.html();
        }
        if (objInfo) {
            tableStr += '<tr>' +
                '<td class="form_text_left_three">附 件</td>' +
                '<td class="form_text_right" colspan="5">' +
                '<div class="upload">' +
                '<div class="upload" id="fsUploadProgress"></div>' +
                '<div class="upload"><span id="swfupload"></span>' +
                '<input id="btnCancel" type="button" value="中止上传" onclick="cancelQueue(uploadfile);" disabled="disabled" /><br />' +
                '</div>' +
                '<div id="uploadfileList" class="upload">' + fileInfo + '</div>' +
                '</div>' +
                '</td>' +
                '</tr>' +
                '</table>';
        } else {
            tableStr += '<tr>' +
                '<td class="form_text_left_three">报销模板</td>' +
                '<td class="form_text_right">' +
                '<input type="text" class="txt" name="' + defaults.objName + '[ModelTypeName]" id="modelTypeName" value="' + templateName + '" readonly="readonly" style="width: 150px;" />' +
                '<input type="hidden" name="' + defaults.objName + '[modelType]" id="modelType" value="' + templateId + '" />' +
                '<input type="button" class="txt_btn_a" style="margin-left: 15px;" value="维护模板" onclick="toModifyModel()">' +
                '</td>' +
                '<td class="form_text_left_three">附 件</td>' +
                '<td class="form_text_right" colspan="3">' +
                '<div class="upload">' +
                '<div class="upload" id="fsUploadProgress"></div>' +
                '<div class="upload"><span id="swfupload"></span>' +
                '<input id="btnCancel" type="button" value="中止上传" onclick="cancelQueue(uploadfile);" disabled="disabled" /><br />' +
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
            }
        });

        //模板渲染
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
                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                    }
                }
            }
        });

        //期间时间绑定
        $("#CostDateBegin").bind('blur', actTimeCheck);
        $("#CostDateEnd").bind('blur', actTimeCheck);
        $("#days").bind('blur', periodDaysCheck);

        // 所有报销人
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
                            // 公司设置
                            $("#CostManComId").val(row.companyCode);
                            $("#CostManCom").val(row.companyName);
                            $("#CostBelongComId").val(row.companyCode);
                            $("#CostBelongCom").val(row.companyName);

                            //费用类型为部门费用时，其他部门报费用归属在综合管理部及子部门，要带报销人所属部门的板块信息
                            setModule();
                        }
                    }
                }
            });
        }

        // 渲染同行人
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

        // 渲染板块
        getAndAddDataToSelect('HTBK', 'module');
        // 插入空项
        $("#module").prepend("<option value=''>请选择</option>");
        // 用于编辑页面初始化板块
        if ($("#moduleVal").val() !== undefined && $("#moduleVal").val() !== "") {
            $("#module").attr('value', $("#moduleVal").val());
        }

        // 附件
        if (objInfo) {
            uploadfile = createSWFUpload({
                serviceId: objInfo.ID,
                serviceNo: objInfo.BillNo,
                serviceType: "cost_summary_list"//业务模块编码，一般取表名
            });
        } else {
            uploadfile = createSWFUpload({
                serviceType: "cost_summary_list"//业务模块编码，一般取表名
            });
        }

        //隐藏费用承担人
        $("#feeManTr").hide().next("td").hide();
    }

    //重置各类下拉
    function resetCombo() {
        $("#detailTypeTitle").html('费用类型').removeClass('red').addClass('blue');
        $("#projectName").yxcombogrid_esmproject('remove');
        $("#projectCode").yxcombogrid_esmproject('remove');
        $("#costBelongCom").yxcombogrid_branch('remove');

        // alert($("#proManagerName").parent().prev().text());
        if($("#proManagerName").parent().prev().text() == '项目经理'){
            $("#proManagerName").parent().parent().remove();
        }
        
        $(".feeTypeContent").remove();
        //清除区域数据
        $("#salesArea").val('');
        $("#salesAreaId").val('');
        if (defaults.objName == "expense") {
            $('#salesAreaRead').val('');
        }

        //清除工作组
        clearWorkTeam();
        //重置费用归属部门公司标识
        $("#comCode").val($("#comCodeDefault").val());
    }

    //设置工作组
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
        //插入省份
        var str = "<td class='form_text_left_three workTeamShow'>" +
            "工 作 组" +
            "</td>" +
            "<td class='form_text_right_three workTeamShow'>" +
            "<input class='txt' name='" + defaults.objName + "[projectName]' id='projectName' value='" + projectName + "' readonly='readonly'/>" +
            "<input type='hidden' name='" + defaults.objName + "[projectId]' id='projectId' value='" + projectId + "'/>" +
            "<input type='hidden' name='" + defaults.objName + "[ProjectNo]' id='projectCode' value='" + projectCode + "'/>" +
            "<input type='hidden' name='" + defaults.objName + "[proManagerName]' id='proManagerName' value='" + proManagerName + "'/>" +
            "<input type='hidden' name='" + defaults.objName + "[proManagerId]' id='proManagerId' value='" + proManagerId + "'/>" +
            "<input type='hidden' name='" + defaults.objName + "[projectType]' id='projectType' value='" + projectType + "'/>" +
            "</td>";
        //缩写部门格
        $("#memberNumberTr").attr("colspan", 1).attr("class", "form_text_right_three").after(str);
        //试用项目渲染
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

    //设置工作组
    function initWorkTeamView(objInfo) {
        //插入省份
        var str = "<td class='form_text_left_three workTeamShow'>" +
            "工 作 组" +
            "</td>" +
            "<td class='form_text_right_three workTeamShow'>" +
            objInfo.projectName +
            "</td>";
        //缩写部门格
        $("#memberNumberTr").attr("colspan", 1).attr("class", "form_text_right_three").after(str);
    }

    //取消工作组
    function clearWorkTeam() {
        //扩展部门格
        $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        $(".workTeamShow").remove();
    }

    /****************************** 不同费用调用 ***********************************/
    //初始化部门 TODO
    function initDept() {
        if (defaults.objName == "expense") {
            $('.salesAreaWrap').hide();
        }
        var allCompany = $("#allCompany").val();
        var thisClass = !defaults.isCompanyReadonly == true || allCompany == "1" ? "txt" : "readOnlyTxtNormal";
        var thisCompany = defaults.isCompanyDefault == true ? defaults.company : $("#CostManCom").val();
        var thisCompanyId = defaults.isCompanyDefault == true ? defaults.companyId : $("#CostManComId").val();

        //默认获取
        var deptId = $("#deptTempId").val();
        var deptName = $("#deptTempName").val();

        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three"><span class="blue">费用归属公司</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="' + thisClass + '" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + thisCompany + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
            '<td class = "form_text_right" colspan="3" id="feeDept">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" value="' + deptName + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" value="' + deptId + '"/>' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        if (!defaults.isCompanyReadonly == true || allCompany == "1") {
            //公司渲染
            $("#costBelongCom").yxcombogrid_branch({
                hiddenId: 'costBelongComId',
                height: 250,
                gridOptions: {
                    isFocusoutCheck: false,
                    showcheckbox: false
                }
            });
        }
        //费用归属部门选择
        $("#costBelongDeptName").yxselect_dept({
            hiddenId: 'costBelongDeptId',
            disableDeptLevel: '1', // 禁用选择一级部门
            unDeptFilter: $('#unDeptFilter').val(),
            unSltDeptFilter: $('#unSltDeptFilter').val(),
            event: {
                selectReturn: function (e, obj) {
                    //ajax获取销售负责人
                    var responseText = $.ajax({
                        url: 'index1.php?model=finance_expense_expense&action=deptIsNeedProvince',
                        data: {deptId: obj.val},
                        type: "POST",
                        async: false
                    }).responseText;
                    //初始化
                    initCheckDept(responseText);
                    // 自动初始化板块
                    setModule();
                }
            }
        });

        //需要部门检查的部分渲染省份
        initCheckDept();
        //渲染工作组
        initWorkTeam();
        //报销提示内容
        $("#tipsView").html('');
        //重置所属板块
        $("#module").find("option:eq(0)").attr("selected", true);
        //所属板块默认带出费用归属部门所属板块
        setModule();
        //隐藏费用承担人
        $("#feeManTr").hide().next("td").hide();
    }

    //设置一下需要检查的部门扩展内容
    function initCheckDept(deptIsNeedProvince, objInfo) {
        //如果没传入判断值，则自行获取
        if (deptIsNeedProvince == undefined) {
            deptIsNeedProvince = $("#deptIsNeedProvince").val();
        } else {
            $("#deptIsNeedProvince").val(deptIsNeedProvince);
        }
        var province = '';
        if (objInfo) {
            province = objInfo.province;
        }
        //如果需要部门检查
        if (deptIsNeedProvince == "1") {

            if ($("#feeDeptProvinceShow").length == 0) {

                //插入省份
                var str = "<td class='form_text_left_three' id='feeDeptProvinceShow'><span class='blue'>所属省份</span></td><td class='form_text_right_three' id='feeDeptProvince'><input class='txt' name='" + defaults.objName + "[province]' id='province' value='" + province + "' style='width:202px;'/></td>";
                //缩写部门格
                $("#feeDept").attr("colspan", 1).attr("class", "form_text_right_three").after(str);
                $('#province').combobox({
                    url: 'index1.php?model=system_procity_province&action=listJsonSort',
                    valueField: 'provinceName',
                    textField: 'provinceName',
                    editable: false
                });
            }
        } else if (deptIsNeedProvince == "0") {
            //如果需要部门检查
            var provinceObj = $('#province');
            if (provinceObj.length == 1) {
                //清空province 值
                $('div[value="province"]').remove();
                //出去动态表格
                $("#feeDeptProvinceShow").remove();
                $("#feeDeptProvince").remove();
                //缩写部门格
                $("#feeDept").attr("colspan", 3).attr("class", "form_text_right");
            }
        }
    }

    //设置需要检查的部门扩展内容 - 查看
    function initCheckDeptView(objInfo) {
        if (objInfo.province) {
            //插入省份
            var str = "<td class='form_text_left_three' id='feeDeptProvinceShow'>所属省份</td><td class='form_text_right_three' id='feeDeptProvince'>" + objInfo.province + "</td>";
            //缩写部门格
            $("#feeDept").attr("colspan", 1).attr("class", "form_text_right_three").after(str);
        }
    }

    //初始化合同项目
    function initContractProject() {
        if (defaults.objName == "expense") {
            $('.salesAreaWrap').hide();// 隐藏销售区域
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
            '<td class = "form_text_left_three"><span class="blue">项目编号</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[ProjectNo]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目名称</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" />' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" id="projectType"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" />' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" />' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目经理</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" />' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">项目超支</td>' +
            '<td class="form_text_right_three" id="projectOverspend">-' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目省份</span></td>' +
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

                        // 项目省份
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

                        //重置费用归属部门
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        // 项目超支检测
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    // 项目超支检测
                    checkProjectOverspend();
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

                        // 项目省份
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

                        //重置费用归属部门
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        // 项目超支检测
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    // 项目超支检测
                    checkProjectOverspend();
                }
            }
        });
        //报销提示内容
        $("#tipsView").html('');
        //重置所属板块
        $("#module").find("option:eq(0)").attr("selected", true);
        //所属板块默认带出费用归属部门所属板块
        setModule();
        //扩展部门格
        $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        //隐藏费用承担人
        $("#feeManTr").hide().next("td").hide();
    }

    //初始化研发项目
    function initRdProject() {
        if (defaults.objName == "expense") {
            $('.salesAreaWrap').hide();// 隐藏销售区域
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
            '<td class = "form_text_left_three"><span class="blue">项目编号</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[ProjectNo]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目名称</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" />' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" id="projectType"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" />' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" />' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + thisCompanyId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目经理</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" />' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">项目超支</td>' +
            '<td class="form_text_right_three" id="projectOverspend">-' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        //研发项目渲染
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

                        //重置费用归属部门
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        // 项目超支检测
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    // 项目超支检测
                    checkProjectOverspend();
                }
            }
        });

        //研发项目渲染
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

                        //重置费用归属部门
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        // 项目超支检测
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    // 项目超支检测
                    checkProjectOverspend();
                }
            }
        });
        //报销提示内容
        $("#tipsView").html('');
        //重置所属板块
        $("#module").find("option:eq(0)").attr("selected", true);
        //所属板块默认带出费用归属部门所属板块
        setModule();
        //扩展部门格
        $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        //隐藏费用承担人
        $("#feeManTr").hide().next("td").hide();
    }

    //初始化售前
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
        if (defaults.objName == "expense") {// 报销单费用归属区域调整 关联PMS2383
            salesAreaStrNew =
                '<td class="form_text_left_three salesAreaWrap blue" style="display:none">费用归属区域</td>' +
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
            '<td class = "form_text_left_three">试用项目编号</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[ProjectNo]" readonly="readonly"/>' +
            '</td>' +
            '<td class = "form_text_left_three">试用项目名称</td>' +
            '<td class = "form_text_right_three" colspan="3">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" />' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" />' +
            '</td>' +
            '</tr><tr>' +
            '<td class = "form_text_left_three">项目经理</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" />' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目省份</span></td>' +
            '<td class = "form_text_right" colspan="3">' +
            '<input type="text" class="readOnlyTxtNormal" id="proProvince" name="' + defaults.objName + '[proProvince]" readonly="readonly"/>' +
            '<input type="hidden" id="proProvinceId" name="' + defaults.objName + '[proProvinceId]" />' +
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
            '<input type="text" class="txt" id="customerType" name="' + defaults.objName + '[CustomerType]" style="width:202px;"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            salesAreaStrNew +
            '<td class = "form_text_left_three"><span class="blue">销售负责人</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="' + defaults.objName + '[CostBelonger]" readonly="readonly"/>' +
            '<input type="hidden" class="ciClass" id="costBelongerId" name="' + defaults.objName + '[CostBelongerId]" />' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" style="width:202px;"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" />' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">客户部门</td>' +
            '<td class="form_text_right_three">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[customerDept]" id="customerDept"/>' +
            '</td>' +
            '<td class="form_text_left_three">项目超支</td>' +
            '<td class="form_text_right_three" id="projectOverspend">-' +
            '</td>' +
            '<td class="form_text_left_three"><span class="blue">费用归属公司</span></td>' +
            '<td class="form_text_right_three" id="costBelongComWrap">' + costBelongComStr +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        if (defaults.objName == "expense") {
            //销售区域
            $('#salesAreaOpt').html('');
            $('#salesAreaOpt').hide();
            $('#salesAreaRead').show();
            $('.salesAreaWrap').show();// 显示销售区域
        }

        if(costBelongComChangeLimit && $("#costBelongCom").val() != undefined){
            //公司
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

        //商机编号
        var codeObj = $("#chanceCode");
        if (codeObj.attr('wchangeTag2')) {
            return false;
        }
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
                //费用承担人处理
                dealFeeMan('');
            }
        });
        codeObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

        //商机名称
        var nameObj = $("#chanceName");
        if (nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机名称'>&nbsp;</span>");
        $button.click(function () {
            if (nameObj.val() == "") {
                alert('请输入一个商机名称');
                return false;
            }
        });

        //添加清空按钮
        $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
        $button2.click(function () {
            if (nameObj.val() != "") {
                //清除销售信息
                clearSale();
                openInput('chance');
                //费用承担人处理
                dealFeeMan('');
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
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {contractType: 'GCXMYD-04', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        //禁用其他入口
                        closeInput('trialPlan', data.id);

                        $("#projectId").val(data.id);
                        $("#projectCode").val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // 项目省份
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

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
                                var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
                                if (defaults.objName == "expense" && costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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

                                //重载客户城市
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        //费用承担人处理
                        dealFeeMan(data.deptId);
                        // 项目超支检测
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
                    //开启其他入口
                    openInput('trialPlan');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    //费用承担人处理
                    dealFeeMan('');
                    // 项目超支检测
                    checkProjectOverspend();
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
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {contractType: 'GCXMYD-04', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        //禁用其他入口
                        closeInput('trialPlan', data.id);

                        $("#projectId").val(data.id);
                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // 项目省份
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

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
                                var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
                                if (defaults.objName == "expense" && costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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

                                //重载客户城市
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        //费用承担人处理
                        dealFeeMan(data.deptId);
                        // 项目超支检测
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
                    //开启其他入口
                    openInput('trialPlan');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    //费用承担人处理
                    dealFeeMan('');
                    // 项目超支检测
                    checkProjectOverspend();
                }
            }
        }).attr('class', 'txt');

        //初始化客户
        initCustomer();

        //客户类型渲染
        $('#customerType').combobox({
            url: 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function () {
                //设置销售负责人
                changeCustomerType();
            },
            onUnselect: function () {
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

                //销售区域处理
                setSalesArea();
            }
        });

        //城市渲染
        cityObj.combobox({
            textField: 'cityName',
            valueField: 'cityName',
            editable: false,
            onSelect: function () {
                //设置销售负责人
                changeCustomerType();
            },
            onUnselect: function () {
                //设置销售负责人
                changeCustomerType();
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
        $('#costBelongDeptName').combobox({
            data: expenseSaleDept,
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                $("#costBelongDeptId").val(obj.value);
                //所属板块默认带出费用归属部门所属板块
                setModule();
                //费用承担人处理
                dealFeeMan(obj.value);
            }
        });
        //报销说明
        $("#tipsView").html('【提示：商机编号/名称录入完成后，系统会自动带出对应信息】');
        //所属板块默认带出费用归属部门所属板块
        setModule();
        //扩展部门格
        $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        //显示费用承担人
        $("#feeManTr").hide().next("td").hide();
    }

    //初始化售后
    function initContract() {
        if (defaults.objName == "expense") {
            $('.salesAreaWrap').hide();// 隐藏销售区域
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
            '<td class = "form_text_left_three"><span class="blue">合同编号</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt ciClass" id="contractCode" name="' + defaults.objName + '[contractCode]"/>' +
            '<input type="hidden" class="ciClass" id="contractId" name="' + defaults.objName + '[contractId]" />' +
            '<input type="hidden" class="ciClass" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + thisCompany + '"/>' +
            '<input type="hidden" class="ciClass" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + thisCompanyId + '"/>' +
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
            '<input type="text" class="readOnlyTxtNormal ciClass" id="customerType" name="' + defaults.objName + '[CustomerType]" readonly="readonly"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">客户部门</td>' +
            '<td class="form_text_right_three">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[customerDept]" id="customerDept"/>' +
            '</td>' +
            '<td class = "form_text_left_three">销售负责人</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="' + defaults.objName + '[CostBelonger]" readonly="readonly"/>' +
            '<input type="hidden" class="ciClass" id="costBelongerId" name="' + defaults.objName + '[CostBelongerId]" />' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" style="width:202px;"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" />' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

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
        $('#costBelongDeptName').combobox({
            data: expenseContractDept,
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                $("#costBelongDeptId").val(obj.value);
                //所属板块默认带出费用归属部门所属板块
                setModule();
                //费用承担人处理
                dealFeeMan(obj.value);
            }
        });

        //编号搜索渲染
        var codeObj = $("#contractCode");
        if (codeObj.attr('wchangeTag2')) {
            return false;
        }
        var $button = $("<span class='search-trigger' id='contractCodeSearch' title='合同编号'>&nbsp;</span>");
        $button.click(function () {
            if (codeObj == "") {
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
        if (nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        $button = $("<span class='search-trigger' id='contractCodeSearch' title='合同名称'>&nbsp;</span>");
        $button.click(function () {
            if (nameObj.val() == "") {
                alert('请输入一个合同名称');
                return false;
            }
        });

        //添加清空按钮
        $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
        $button2.click(function () {
            $(".ciClass").val('');
        });
        nameObj.bind('blur', getContractInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true);
        //报销说明
        $("#tipsView").html('【提示：合同编号/名称录入完成后，系统会自动带出对应信息】');
        //重置所属板块
        $("#module").find("option:eq(0)").attr("selected", true);
        //所属板块默认带出费用归属部门所属板块
        setModule();
        //扩展部门格
        $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        //显示费用承担人
        $("#feeManTr").hide().next("td").hide();
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
            data: {contractCode: contractCode, contractName: contractName},
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
                        $("#salesArea").val(dataArr.areaName);
                        if (defaults.objName == "expense") {
                            $('#salesAreaRead').val(dataArr.areaName);
                        }
                        $("#salesAreaId").val(dataArr.areaCode);
                        $("#module").val(dataArr.module);
                        $("#moduleName").val(dataArr.moduleName);
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
                    row_dblclick: function (e, row, data) {
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
                        var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
                        if (defaults.objName == "expense" && costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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

                        // 销售区域
                        setSalesArea();
                    }
                }
            },
            event: {
                clear: function () {
                    clearSale();

                    //开启其他入口
                    openInput('customer');
                }
            }
        }).attr('readonly', false).attr('class', 'txt');
    }

    //获取商机信息
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
                        alert('系统中存在【' + dataArr.thisLength + '】条名称为【' + chanceName + '】的商机，请通过商机编号匹配商机信息！');
                        clearSale();
                    } else {
                        if (typeof(thisType) == 'object') {
                            //关闭其他入口
                            closeInput('chance');
                        }
                        //商机信息赋值
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

        if (defaults.objName == "expense") {
            $("#salesAreaId").val('');
            $("#salesArea").val('');
            $("#salesAreaRead").val('');

            // 隐藏下拉选项框,并恢复输入框
            $('#salesAreaOpt').html('');
            $('#salesAreaOpt').hide();
            $('#salesAreaRead').show();
        }

        //重置费用归属部门
        if (isCombobox('costBelonger') == 1) {
            $("#costBelonger").combobox("setValue", '');
            $("#costBelongerId").val('');
        } else {
            $("#costBelonger").val('');
            $("#costBelongerId").val('');
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
        $("#customerType").combobox("setValue", '');
        //清空城市
        $("#city").combobox("setValue", '');
        $("input[id^='city_']").attr('checked', false);
    }

    // 禁用其他入口
    function closeInput(thisType, projectId) {
        //项目id获取
        if (projectId == undefined) {
            projectId = $("#projectId").val();//项目id
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

            //重置归属部门
            $('#costBelongDeptName').combobox({
                data: expenseSaleDept,
                valueField: 'text',
                textField: 'text',
                editable: false,
                onSelect: function (obj) {
                    $("#costBelongDeptId").val(obj.value);
                    //费用承担人处理
                    dealFeeMan(obj.value);
                }
            }).combobox('setValue', '');
            $("#costBelongDeptId").val('');

            //商机编号
            var codeObj = $("#chanceCode");
            if (codeObj.attr('wchangeTag2') == true) {
                return false;
            }
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
                    //费用承担人处理
                    dealFeeMan('');
                }
            });
            codeObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

            //商机名称
            var nameObj = $("#chanceName");
            if (nameObj.attr('wchangeTag2') == true) {
                return false;
            }
            $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机名称'>&nbsp;</span>");
            $button.click(function () {
                if (nameObj.val() == "") {
                    alert('请输入一个商机名称');
                    return false;
                }
            });

            //添加清空按钮
            $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
            $button2.click(function () {
                if (nameObj.val() != "") {
                    //清除销售信息
                    clearSale();
                    openInput('chance');
                    //费用承担人处理
                    dealFeeMan('');
                }
            });
            nameObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');
        } else if (thisType == 'customer') {
            //项目
            initTrialproject();

            $("#customerName").attr("class", 'txt').attr('readonly', false);

            //商机编号
            var codeObj = $("#chanceCode");
            if (codeObj.attr('wchangeTag2') == true) {
                return false;
            }
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
            if (nameObj.attr('wchangeTag2') == true) {
                return false;
            }
            $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机名称'>&nbsp;</span>");
            $button.click(function () {
                if (nameObj.val() == "") {
                    alert('请输入一个商机名称');
                    return false;
                }
            });

            //添加清空按钮
            $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
            $button2.click(function () {
                if (nameObj.val() != "") {
                    //清除销售信息
                    clearSale();
                    openInput('chance');
                }
            });
            nameObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');
        } else if ((typeof(thisType) == "object" && thisType.data == 'chance') || thisType == 'chance') {
            //重置归属部门
            $('#costBelongDeptName').combobox({
                data: expenseSaleDept,
                valueField: 'text',
                textField: 'text',
                editable: false,
                onSelect: function (obj) {
                    $("#costBelongDeptId").val(obj.value);
                    //费用承担人处理
                    dealFeeMan(obj.value);
                }
            }).combobox('setValue', '');
            $("#costBelongDeptId").val('');

            //项目
            initTrialproject();

            //重新实例化客户选择
            initCustomer();
        }

        //显示省份的下拉项
        $("#province").combobox('enable');
        $('#city').combobox('enable');
        $("#customerType").combobox('enable');
        if (defaults.objName == "expense" && costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
            $("#costBelonger").combobox('enable');
        }
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
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                isFocusoutCheck: true,
                autoload: false,
                param: {contractType: 'GCXMYD-04', statusArr: 'GCXMZT02'},
                event: {
                    row_dblclick: function (e, row, data) {
                        //禁用其他入口
                        closeInput('trialPlan', data.id);

                        $("#projectId").val(data.id);
                        $("#projectName").val(data.projectName);
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
                                var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
                                if (defaults.objName == "expense" && costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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
                                //重载客户城市
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        // 项目超支检测
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

                    //开启其他入口
                    openInput('trialPlan');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    // 项目超支检测
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
                        //禁用其他入口
                        closeInput('trialPlan', data.id);

                        $("#projectId").val(data.id);
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
                                var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
                                if (defaults.objName == "expense" && costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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

                                //重载客户城市
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        // 项目超支检测
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

                    //开启其他入口
                    openInput('trialPlan');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    // 项目超支检测
                    checkProjectOverspend();
                }
            }
        }).attr('class', 'txt');
    }

    //选择客户类型
    function changeCustomerType(thisType) {
        var chanceId = $("#chanceId").val();
        var customerId = $("#customerId").val();
        var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
        if ((chanceId == "" || chanceId == '0') && (customerId == "" || customerId == '0') && costType != 4) {
            var customerType = $('#customerType').combobox('getValue');//客户类型
            var province = $('#province').combobox('getValue');//省份
            var city = $('#city').combobox('getValues').toString();//城市

            if (province && city && customerType) {
                //ajax获取销售负责人
                var responseText = $.ajax({
                    url: 'index1.php?model=system_saleperson_saleperson&action=getSalePerson',
                    data: {province: province, city: city, customerTypeName: customerType},
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
                            //ajax获取所属板块
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
            //销售负责人
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
        // 加入区域处理
        setSalesArea();
    }

    //ajax获取试用项目申请信息
    function getTrialProject(id) {
        var data = $.ajax({
            type: "POST",
            url: "?model=projectmanagent_trialproject_trialproject&action=ajaxGetInfo",
            data: {id: id},
            async: false
        }).responseText;
        return data != "" ? eval('(' + data + ")") : false;
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
        reloadCity(dataArr.Province, dataArr.City);
        $("#city").combobox('setValue', dataArr.City);

        //客户类型
        $("#customerType").combobox('setValue', dataArr.customerTypeName);

        //销售负责人
        var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
        if (defaults.objName == "expense" && costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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

        //如果是从试用项目进来的，不需要重新渲染费用归属部门
        if (typeof(thisType) == 'object') {
            //重新设置费用归属部门
            $('#costBelongDeptName').combobox({
//				data: [{text: dataArr.prinvipalDept, value: dataArr.prinvipalDeptId}],
                data: expenseSaleDept,
                valueField: 'text',
                textField: 'text',
                editable: false,
                onSelect: function (obj) {
                    $("#costBelongDeptId").val(obj.value);
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    //费用承担人处理
                    dealFeeMan(obj.value);
                }
            }).combobox('setValue', dataArr.prinvipalDept);
            $("#costBelongDeptId").val(dataArr.prinvipalDeptId);
            //所属板块默认带出费用归属部门所属板块
            setModule();
            //费用承担人处理
            dealFeeMan(dataArr.prinvipalDeptId);
            //如果出现费用承担人，则默认为销售负责人
            if ($("#feeManTr").is(':visible')) {
                $("#feeMan").val(dataArr.prinvipalName);
                $("#feeManId").val(dataArr.prinvipalId);
            }
        }

        // 销售区域处理
        setSalesArea(dataArr);
    }

    //重新载入城市
    function reloadCity(provinceName, city) {
        $('#city').combobox({
            url: "?model=system_procity_city&action=listJson&tProvinceName=" + provinceName
        });
    }

    //*********************** 查看部分 *********************/
    //初始化费用内容
    function initCostTypeView(objInfo) {
        if (objInfo.DetailType) {
            //初始化相同部分
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

    //初始化查看头
    function initViewHead(objInfo) {
        var deT = '';
        switch (objInfo.DetailType) {
            case '1' :
                deT = '部门费用';
                break;
            case '2' :
                deT = '合同项目费用';
                break;
            case '3' :
                deT = '研发费用';
                break;
            case '4' :
                deT = '售前费用';
                break;
            case '5' :
                deT = '售后费用';
                break;
            default :
        }
        //附件获取
        var fileInfo = '';
        var fileInfoObj = $("#fileInfo");
        if (fileInfoObj.length > 0) {
            fileInfo = fileInfoObj.html();
        }
        var isEditPurpose = '';
        if ($("#isEdit").length > 0) {
            isEditPurpose = '<img src="images/changeedit.gif" title="修改事由" onclick="openSavePurpose()";/>';
        }
        var sourceType = ($("#sourceType").val() != undefined)? $("#sourceType").val() : '';
        var isDiffBillInfoMsg = ($("#isDiffBillInfoMsg").val() != undefined)? "<span style='margin-left: 3px;color:red;'>"+$("#isDiffBillInfoMsg").val()+"</span>" : '';
        var tableStr = '<table class="form_in_table" id="' + defaults.myId + 'tbl">' +
            '<tr id="feeTypeTr">' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle">费用类型</span></td>' +
            '<td class = "form_text_right">' + deT + '</td>' +
            '<td class = "form_text_left_three"><span id="detailTypeTitle">来源</span></td>' +
            '<td class = "form_text_right" colspan="3">' + sourceType + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three">费用期间</td>' +
            '<td class="form_text_right" colspan="5">' +
            '<input type="hidden" id="CostDateBegin" value="' + objInfo.CostDateBegin + '"> <input type="hidden" id="CostDateEnd" value="' + objInfo.CostDateEnd + '">' +
            '<span class="blue">' + objInfo.CostDateBegin + '</span> 至 ' +
            '<span class="blue">' + objInfo.CostDateEnd + '</span> 共 ' +
            '<span class="blue">' + objInfo.days + '</span>' +
            ' 天 <span id="mainTitle"></span>' +
            '<div id="printLink" style="float: right;width:200px;text-align: left;display:none">' +
            '<input type="button" class="txt_btn_a" value=" 打印小单 " onclick="window.open(\'?model=cost_bill_billcheck&action=print_bill&billno='+objInfo.BillNo+'\');">&nbsp;&nbsp;' +
            '<input type="button" class="txt_btn_a" value=" 打印大单 " onclick="window.open(\'general/costmanage/print/expense/print_bill.php?QR_BillNo='+objInfo.BillNo+'\');">' +
            '</div>' +
            '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three">事 由</td>' +
            '<td class="form_text_right" colspan="5">' +
            isEditPurpose +
            '<span id="PurposeShow">' + objInfo.Purpose + '<span/>' +
            '</td>' +
            '</tr>' +
            '<tr id="baseTr">' +
            '<td class="form_text_left_three">报销人员</td>' +
            '<td class="form_text_right_three">' +
            objInfo.CostManName + isDiffBillInfoMsg +
            '</td>' +
            '<td class="form_text_left_three">报销人部门</td>' +
            '<td class="form_text_right_three">' +
            objInfo.CostDepartName +
            '</td>' +
            '<td class="form_text_left_three">报销人公司</td>' +
            '<td class="form_text_right_three">' +
            objInfo.CostManCom +
            '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three">同 行 人</td>' +
            '<td class="form_text_right_three">' +
            objInfo.memberNames +
            '</td>' +
            '<td class="form_text_left_three">同行人数</td>' +
            '<td class="form_text_right" id="memberNumberTr">' +
            objInfo.memberNumber +
            '</td>' +
            '<td class="form_text_left_three" id="feeManTr">费用承担人</td>' +
            '<td class="form_text_right">' +
            objInfo.feeMan +
            '</td>' +
            '</tr>' +
            '<tr style="display:none;" id="salesAreaTr">' +
            '<td class="form_text_left_three">费用归属区域</td>' +
            '<td class="form_text_right">' +
            objInfo.salesArea +
            '</td>' +
            '</tr>' +
            '<tr style="display:none;">' +
            '<td class="form_text_left_three">所属板块</td>' +
            '<td class="form_text_right" colspan="5">' +
            objInfo.moduleName +
            '</td>' +
            '</tr>' +
            '<tr>' +
            '<td class="form_text_left_three">附 件</td>' +
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
         //ajax检查打单权限
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

    //初始化部门
    function initDeptView(objInfo) {
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">费用归属公司</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.CostBelongCom +
            '</td>' +
            '<td class = "form_text_left_three">费用归属部门</td>' +
            '<td class = "form_text_right" colspan="3" id="feeDept">' +
            objInfo.CostBelongDeptName +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        //省份
        initCheckDeptView(objInfo);
        //工作组
        initWorkTeamView(objInfo);
        //隐藏费用承担人
        $("#feeManTr").hide().next("td").hide();
    }

    //初始化合同项目
    function initProjectView(objInfo) {
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">项目编号</span></td>' +
            '<td class = "form_text_right_three">' +
            objInfo.ProjectNo +
            '</td>' +
            '<td class = "form_text_left_three">项目名称</span></td>' +
            '<td class = "form_text_right_three">' +
            objInfo.projectName +
            '</td>' +
            '<td class = "form_text_left_three">项目经理</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.proManagerName +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);
        //扩展部门格
        // $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        $("#memberNumberTr").after('<td class = "form_text_left_three">项目省份</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.proProvince +
            '</td>');

        //隐藏费用承担人
        $("#feeManTr").hide().next("td").hide();
    }

    //初始化售前
    function initSaleView(objInfo) {
        var tableStr = '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">试用项目编号</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.ProjectNo +
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
            objInfo.CustomerType +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">客户部门</td>' +
            '<td class="form_text_right_three">' +
            objInfo.customerDept +
            '</td>' +
            '<td class = "form_text_left_three">销售负责人</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.CostBelonger +
            '</td>' +
            '<td class = "form_text_left_three">费用归属部门</td>' +
            '<td class = "form_text_right">' +
            objInfo.CostBelongDeptName +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);
        //显示费用归属区域
        $("#salesAreaTr").show();

        // 售前添加归属公司信息
        var belongComStr = '<td class = "form_text_left_three">费用归属公司</td>' +
            '<td class = "form_text_right">' + objInfo.CostBelongCom + '</td>' +
            '<td class = "form_text_left_three">项目省份</td>' +
            '<td class = "form_text_right">' + objInfo.proProvince + '</td>';
        $("#salesAreaTr").append(belongComStr);
    }

    //初始化售后
    function initContractView(objInfo) {
        var tableStr = '<tr class="feeTypeContent">' +
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
            objInfo.CustomerType +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">客户部门</td>' +
            '<td class="form_text_right_three">' +
            objInfo.customerDept +
            '</td>' +
            '<td class = "form_text_left_three">销售负责人</td>' +
            '<td class = "form_text_right_three">' +
            objInfo.CostBelonger +
            '</td>' +
            '<td class = "form_text_left_three">费用归属部门</td>' +
            '<td class = "form_text_right">' +
            objInfo.CostBelongDeptName +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);
        //显示费用归属区域
        $("#salesAreaTr").show();
    }

    //********************* 编辑部分 ************************/
    //初始化费用类型
    function initCostTypeEdit(thisObj, objInfo) {
        initCostType(thisObj, objInfo);
        //附选中值
        $("input[name='" + defaults.objName + "[DetailType]']").each(function () {
            if (this.value == objInfo.DetailType) {
                if (this.value == 4 && defaults.objName == "expense" && defaults.actionType == 'edit') {
                    $('.salesAreaWrap').show();// 显示销售区域
                }
                $(this).attr("checked", this);
                return false;
            }
        });
        $("#detailTypeTitle").html('费用类型').removeClass('red').addClass('blue');
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
        // 项目超支检测
        checkProjectOverspend();
    }

    //初始化部门
    function initDeptEdit(objInfo) {
        //初始值赋予
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
            '<td class = "form_text_left_three"><span class="blue">费用归属公司</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="' + thisClass + '" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + costBelongCom + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + costBelongComId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
            '<td class = "form_text_right" colspan="3" id="feeDept">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" value="' + costBelongDeptName + '" readonly="readonly"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);
        if (defaults.isCompanyReadonly != true || allCompany == "1") {
            //公司渲染
            $("#costBelongCom").yxcombogrid_branch({
                hiddenId: 'costBelongComId',
                height: 250,
                gridOptions: {
                    isFocusoutCheck: true,
                    showcheckbox: false
                }
            });
        }
        //费用归属部门选择
        $("#costBelongDeptName").yxselect_dept({
            hiddenId: 'costBelongDeptId',
            disableDeptLevel: '1', // 禁用选择一级部门
            unDeptFilter: $('#unDeptFilter').val(),
            unSltDeptFilter: $('#unSltDeptFilter').val(),
            event: {
                selectReturn: function (e, obj) {
                    //ajax获取销售负责人
                    var responseText = $.ajax({
                        url: 'index1.php?model=finance_expense_expense&action=deptIsNeedProvince',
                        data: {deptId: obj.val},
                        type: "POST",
                        async: false
                    }).responseText;
                    //初始化
                    initCheckDept(responseText);
                    //所属板块默认带出费用归属部门所属板块
                    if (obj.dept.comCode == 'zh') {//其他部门报费用归属在综合管理部及子部门，要带报销人所属部门的板块信息
                        if ($("#CostDepartID").val() != '') {
                            setModule($("#CostDepartID").val());
                        }
                        //设置费用归属部门公司标识
                        $("#comCode").val('zh');
                    } else {
                        setModule();
                    }
                }
            }
        });

        //需要部门检查的部分渲染省份
        initCheckDept(undefined, objInfo);
        //渲染工作组
        initWorkTeam(objInfo);
        //报销提示内容
        $("#tipsView").html('');
    }

    //初始化合同项目
    function initContractProjectEdit(objInfo) {
        //初始值赋予
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
            '<td class = "form_text_left_three"><span class="blue">项目编号</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[ProjectNo]" readonly="readonly" value="' + projectCode + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目名称</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly" value="' + projectName + '"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" value="' + projectId + '"/>' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" value="' + projectType + '"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" value="' + costBelongDeptName + '"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + CostBelongCom + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + CostBelongComId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目经理</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly" value="' + proManagerName + '"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" value="' + proManagerId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">项目超支</td>' +
            '<td class="form_text_right_three" id="projectOverspend">-' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目省份</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proProvince" name="' + defaults.objName + '[proProvince]" readonly="readonly" value="' + proProvince + '"/>' +
            '<input type="hidden" id="proProvinceId" name="' + defaults.objName + '[proProvinceId]" value="' + proProvinceId + '"/>' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        // 项目选择
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

                        // 项目省份
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

                        //重置费用归属部门
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);

                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        // 项目超支检测
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    // 项目超支检测
                    checkProjectOverspend();
                }
            }
        });

        // 项目选择
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

                        // 项目省份
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

                        //重置费用归属部门
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);

                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        // 项目超支检测
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    // 项目超支检测
                    checkProjectOverspend();
                }
            }
        });
        //报销提示内容
        $("#tipsView").html('');
    }

    //初始化研发项目
    function initRdProjectEdit(objInfo) {
        //初始值赋予
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
            '<td class = "form_text_left_three"><span class="blue">项目编号</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[ProjectNo]" readonly="readonly" value="' + projectCode + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目名称</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly" value="' + projectName + '"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" value="' + projectId + '"/>' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" value="' + projectType + '"/>' +
            '<input type="hidden" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" value="' + costBelongDeptName + '"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + CostBelongCom + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + CostBelongComId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">项目经理</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" readonly="readonly" value="' + proManagerName + '"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" value="' + proManagerId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">项目超支</td>' +
            '<td class="form_text_right_three" id="projectOverspend">-' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        //研发项目渲染
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

                        //重置费用归属部门
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        // 项目超支检测
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    // 项目超支检测
                    checkProjectOverspend();
                }
            }
        });

        //研发项目渲染
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

                        //重置费用归属部门
                        $("#costBelongDeptId").val(data.deptId);
                        $("#costBelongDeptName").val(data.deptName);
                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        // 项目超支检测
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

                    //重置费用归属部门
                    $("#costBelongDeptId").val('');
                    $("#costBelongDeptName").val('');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    // 项目超支检测
                    checkProjectOverspend();
                }
            }
        });
        //报销提示内容
        $("#tipsView").html('');
    }

    //初始化售前费用
    function initSaleEdit(objInfo) {
        //初始值赋予
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
        if (defaults.objName == "expense") {// 报销单费用归属区域调整 关联PMS2383
            salesAreaStrNew =
                '<td class="form_text_left_three salesAreaWrap blue">费用归属区域</td>' +
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
            '<td class = "form_text_left_three">试用项目编号</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt" id="projectCode" name="' + defaults.objName + '[ProjectNo]" readonly="readonly" value="' + projectCode + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">试用项目名称</td>' +
            '<td class = "form_text_right_three" colspan="3">' +
            '<input type="text" class="txt" id="projectName" name="' + defaults.objName + '[projectName]" readonly="readonly" value="' + projectName + '"/>' +
            '<input type="hidden" id="projectId" name="' + defaults.objName + '[projectId]" value="' + projectId + '"/>' +
            '<input type="hidden" id="projectType" name="' + defaults.objName + '[projectType]" value="' + projectType + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">项目经理</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal" id="proManagerName" name="' + defaults.objName + '[proManagerName]" value="' + proManagerName + '" readonly="readonly"/>' +
            '<input type="hidden" id="proManagerId" name="' + defaults.objName + '[proManagerId]" value="' + proManagerId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">项目省份</td>' +
            '<td class = "form_text_right_three" colspan="3">' +
            '<input type="text" class="readOnlyTxtNormal" id="proProvince" name="' + defaults.objName + '[proProvince]" value="' + proProvince + '" readonly="readonly"/>' +
            '<input type="hidden" id="proProvinceId" name="' + defaults.objName + '[proProvinceId]" value="' + proProvinceId + '"/>' +
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
            '<input type="text" class="txt" id="customerType" name="' + defaults.objName + '[CustomerType]" value="' + customerType + '" style="width:202px;"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            salesAreaStrNew +
            '<td class = "form_text_left_three"><span class="blue">销售负责人</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="' + defaults.objName + '[CostBelonger]" value="' + costBelonger + '" readonly="readonly"/>' +
            '<input type="hidden" class="ciClass" id="costBelongerId" name="' + defaults.objName + '[CostBelongerId]" value="' + costBelongerId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" value="' + costBelongDeptName + '" style="width:202px;"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">客户部门</td>' +
            '<td class="form_text_right_three">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[customerDept]" id="customerDept" value="' + customerDept + '"/>' +
            '</td>' +
            '<td class="form_text_left_three">项目超支</td>' +
            '<td class="form_text_right_three" id="projectOverspend">-' +
            '</td>' +
            '<td class="form_text_left_three"><span class="blue">费用归属公司</span></td>' +
            '<td class="form_text_right_three" id="costBelongComWrap">' + costBelongComStr +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

        //商机编号
        var codeObj = $("#chanceCode");
        if (codeObj.attr('wchangeTag2') == true) {
            return false;
        }
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
                //费用承担人处理
                dealFeeMan('');
            }
        });
        codeObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(codeObj.width() - $button2.width()).after($button).width(codeObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

        if(costBelongComChangeLimit && $("#costBelongCom").val() != undefined){
            //公司
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

        //商机名称
        var nameObj = $("#chanceName");
        if (nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        $button = $("<span class='search-trigger' id='chanceCodeSearch' title='商机名称'>&nbsp;</span>");
        $button.click(function () {
            if (nameObj.val() == "") {
                alert('请输入一个商机名称');
                return false;
            }
        });

        //添加清空按钮
        $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
        $button2.click(function () {
            if (nameObj.val() != "") {
                //清除销售信息
                clearSale();
                openInput('chance');
                //费用承担人处理
                dealFeeMan('');
            }
        });
        nameObj.bind('blur', {thisType: 'chance'}, getChanceInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly', false).attr("class", 'txt');

        var projectCodeObj = $("#projectCode");

        //试用项目渲染
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
                        //禁用其他入口
                        closeInput('trialPlan', data.id);

                        $("#projectId").val(data.id);
                        projectCodeObj.val(data.projectCode);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // 项目省份
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

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
                                if (defaults.objName == "expense" && costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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

                                //重载客户城市
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        //费用承担人处理
                        dealFeeMan(data.deptId);
                        // 项目超支检测
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

                    //开启其他入口
                    openInput('trialPlan');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    //费用承担人处理
                    dealFeeMan('');
                    // 项目超支检测
                    checkProjectOverspend();
                }
            }
        }).attr('class', 'txt');

        //项目编号
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
                        //禁用其他入口
                        closeInput('trialPlan', data.id);

                        $("#projectId").val(data.id);
                        $("#projectName").val(data.projectName);
                        $("#proManagerName").val(data.managerName);
                        $("#proManagerId").val(data.managerId);
                        $("#projectType").val('esm');

                        // 项目省份
                        $("#proProvince").val(data.province);
                        $("#proProvinceId").val(data.provinceId);

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
                                var costType = $('input:radio[name="expense[DetailType]"]:checked').val();
                                if (defaults.objName == "expense" && costType == 4) {// 售前费用类型的根据归属区域带去销售负责人 关联PMS2418
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

                                //重载客户城市
                                reloadCity(trialProjectInfo.province);
                                $("#city").combobox('setValue', trialProjectInfo.city);
                            }
                        }
                        //所属板块默认带出费用归属部门所属板块
                        setModule();
                        //费用承担人处理
                        dealFeeMan(data.deptId);
                        // 项目超支检测
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

                    //开启其他入口
                    openInput('trialPlan');
                    //所属板块默认带出费用归属部门所属板块
                    setModule();
                    //费用承担人处理
                    dealFeeMan('');
                    // 项目超支检测
                    checkProjectOverspend();
                }
            }
        }).attr('class', 'txt');

        //初始化客户
        initCustomer();

        //客户类型渲染
        $('#customerType').combobox({
            url: 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function () {
                //设置销售负责人
                changeCustomerType();
            },
            onUnselect: function () {
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
                }).combobox("setValue", '');

                //销售区域处理
                setSalesArea();
            }
        });

        //城市渲染
        cityObj.combobox({
            url: "?model=system_procity_city&action=listJson&tProvinceName=" + province,
            textField: 'cityName',
            valueField: 'cityName',
            editable: false,
            onSelect: function () {
                //设置销售负责人
                changeCustomerType();
            },
            onUnselect: function () {
                //设置销售负责人
                changeCustomerType();
            }
        }).combobox('setValue', city);

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
        var costBelongDeptNameObj = $('#costBelongDeptName');
        //如果是关联pk项目，那么初始化时直接取表单的部门渲染；如果是关联商机，则费用归属部门可选择
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
                //所属板块默认带出费用归属部门所属板块
                setModule();
                //费用承担人处理
                dealFeeMan(obj.value);
            }
        }).combobox('setValue', costBelongDeptNameObj.val());

        //调用一次禁用窗口
        closeInput();
        //调用一次设置销售负责人
        changeCustomerType('init');
        //报销说明
        $("#tipsView").html('【提示：商机编号/名称录入完成后，系统会自动带出对应信息】');
        //费用承担人处理
        dealFeeMan(costBelongDeptId, 'init');
    }

    //初始化售后费用
    function initContractEdit(objInfo) {
        //初始值赋予
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
            '<td class = "form_text_left_three"><span class="blue">合同编号</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt ciClass" id="contractCode" name="' + defaults.objName + '[contractCode]" value="' + contractCode + '"/>' +
            '<input type="hidden" class="ciClass" id="contractId" name="' + defaults.objName + '[contractId]" value="' + contractId + '"/>' +
            '<input type="hidden" id="costBelongCom" name="' + defaults.objName + '[CostBelongCom]" value="' + CostBelongCom + '"/>' +
            '<input type="hidden" id="costBelongComId" name="' + defaults.objName + '[CostBelongComId]" value="' + CostBelongComId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">合同名称</span></td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="txt ciClass" id="contractName" name="' + defaults.objName + '[contractName]" value="' + contractName + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">客户名称</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="customerName" name="' + defaults.objName + '[customerName]" readonly="readonly" value="' + customerName + '"/>' +
            '<input type="hidden" class="ciClass" id="customerId" name="' + defaults.objName + '[customerId]" value="' + customerId + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class = "form_text_left_three">客户省份</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="province" name="' + defaults.objName + '[province]" readonly="readonly" value="' + province + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">客户城市</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="city" name="' + defaults.objName + '[city]" readonly="readonly" value="' + city + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">客户类型</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="customerType" name="' + defaults.objName + '[CustomerType]" readonly="readonly" value="' + customerType + '"/>' +
            '</td>' +
            '</tr>' +
            '<tr class="feeTypeContent">' +
            '<td class="form_text_left_three">客户部门</td>' +
            '<td class="form_text_right_three">' +
            '<input type="text" class="txt" name="' + defaults.objName + '[customerDept]" id="customerDept" value="' + customerDept + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three">销售负责人</td>' +
            '<td class = "form_text_right_three">' +
            '<input type="text" class="readOnlyTxtNormal ciClass" id="costBelonger" name="' + defaults.objName + '[CostBelonger]" readonly="readonly" value="' + costBelonger + '"/>' +
            '<input type="hidden" class="ciClass" id="costBelongerId" name="' + defaults.objName + '[CostBelongerId]" value="' + costBelongerId + '"/>' +
            '</td>' +
            '<td class = "form_text_left_three"><span class="blue">费用归属部门</span></td>' +
            '<td class = "form_text_right">' +
            '<input type="text" class="txt" id="costBelongDeptName" name="' + defaults.objName + '[CostBelongDeptName]" style="width:202px;" value="' + costBelongDeptName + '"/>' +
            '<input type="hidden" id="costBelongDeptId" name="' + defaults.objName + '[CostBelongDeptId]" value="' + costBelongDeptId + '"/>' +
            '</td>' +
            '</tr>';
        $("#baseTr").after(tableStr);

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
        $('#costBelongDeptName').combobox({
            data: expenseContractDept,
            valueField: 'text',
            textField: 'text',
            editable: false,
            onSelect: function (obj) {
                $("#costBelongDeptId").val(obj.value);
                //所属板块默认带出费用归属部门所属板块
                setModule();
                //费用承担人处理
                dealFeeMan(obj.value);
            }
        });

        //编号搜索渲染
        var codeObj = $("#contractCode");
        if (codeObj.attr('wchangeTag2') == true) {
            return false;
        }
        var $button = $("<span class='search-trigger' id='contractCodeSearch' title='合同编号'>&nbsp;</span>");
        $button.click(function () {
            if (codeObj.val() == "") {
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
        if (nameObj.attr('wchangeTag2') == true) {
            return false;
        }
        $button = $("<span class='search-trigger' id='contractNameSearch' title='合同名称'>&nbsp;</span>");
        $button.click(function () {
            if (nameObj.val() == "") {
                alert('请输入一个合同名称');
                return false;
            }
        });

        //添加清空按钮
        $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
        $button2.click(function () {
            $(".ciClass").val('');
        });
        nameObj.bind('blur', getContractInfo).after($button2).width(nameObj.width() - $button2.width()).after($button).width(nameObj.width() - $button.width()).attr("wchangeTag2", true);
        //报销说明
        $("#tipsView").html('【提示：合同编号/名称录入完成后，系统会自动带出对应信息】');
        //费用承担人处理
        dealFeeMan(costBelongDeptId, 'init');
    }

    // 根据归属区域带出的销售负责人设置对应值 createdBy haojin 2017-01-24 PMS2418
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

    // 自动生成销售区域
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
                if (defaults.objName == "expense" && areaInfo.length > 1 && $('.salesAreaWrap').css('display') != 'none') {//当返回的区域信息存在多个的时候,改为选项让用户自选
                    // 显示选项
                    var optionsStr = '<option value="">..请选择..</option>';
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
                        // 先清空原来的区域信息
                        $("#salesAreaId").val('');
                        $("#salesArea").val('');
                        $('#salesAreaRead').val('');
                        $.each(areaInfo, function () {
                            if (areaName != '' && chanceArr && areaCode == $(this)[0]['id']) {// 如果有商机信息又是匹配出多个区域的默认商机的区域选中
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
                        // 隐藏下拉选项框
                        $('#salesAreaOpt').html('').hide();
                        // 显示输入框,并填入对应值
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
                    // 隐藏下拉选项框
                    $('#salesAreaOpt').html('').hide();
                    // 显示输入框,并填入对应值
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
                // 隐藏下拉选项框
                $('#salesAreaOpt').html('').hide();
                // 显示输入框,并填入对应值
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
        //合并属性
        $.extend(defaults, options);

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
            }
        });
    };

    // 如果是关联租车的费用类型,添加租车汇总页面的查看链接
    setTimeout(function(e) {
        if($("#tempExpenseId").val() != undefined && Number($("#tempExpenseId").val()) > 0){
            var tempExpenseId = $("#tempExpenseId").val();
            var typeName = $("#MainTypeName0").text();
            var url = "?model=outsourcing_vehicle_allregister&action=toView&type=viewByExpenseTmpId&tmpId="+tempExpenseId;
            $("#MainTypeName0").html("<a href='"+url+"' target='_blank'>"+typeName+"</a>");
        }
    },200);

})(jQuery);

//根据区域 判断本季度预决算以及年度决算是否超标
function checkBudget(areaId) {
    if (areaId != '') {
        $.ajax({
            type: "POST",
            url: "?model=finance_budget_budgetDetail&action=getByAreaId",
            data: {"areaId": areaId},
            success: function (data) {
                if (data != null) {
                    var obj = JSON.parse(data);
                    var season = currentSeason(); //当前季度
                    var seasonFlag = true;
                    var yearFlag = true;

                    //先清空提醒
                    var yearTip = $("#budgetYearTip");
                    if (yearTip) {
                        yearTip.remove();
                    }
                    var seasonTip = $("#budgetSeasonTip");
                    if (seasonTip) {
                        seasonTip.remove();
                    }
                    //判断年度决算是否超出年度预算
                    yearFlag = (obj.totalBudget - obj.final) < 0 ? false : true;
                    //试用项目编号
                    var projectCode = $("#projectCode").val(); //试用项目不需要费用预警
                    if (!yearFlag && projectCode == "") {
                        //超出年度预算走特殊审批流程，isSpecial字段为0
                        var budgetTip = "<div id='budgetYearTip' style='color:red;'><input type='hidden' name='expense[isSpecial]' value='1' />(" + obj.area + "区域年度决算已超年度预算，需走特殊审批流程)</div>";
                        $("#tipsView").append(budgetTip);
                    }

                    //根据季度判断是否超出预算，超出则提醒
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
                        var budgetTip = "<div id='budgetSeasonTip' style='color:red;'>(" + obj.area + "区域本季度决算已超季度预算)</div>";
                        $("#tipsView").append(budgetTip);
                    }
                }
            }
        });
    }
}

//获取当前季度
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

// 设置费用承担人信息
function setFeeManInfo(){
    var seletedVal = $("#feeMansSelect").find("option:selected");
    $("#feeManId").val(seletedVal.val());
    $("#feeMan").val(seletedVal.attr('data-name'));
}

//费用承担人处理
function dealFeeMan(deptId, type) {
    //获取销售部
    var saleDeptIdArr = $("#saleDeptId").val().split(",");
    $("#feeMansSelect").remove();
    $("#feeMan").show();
    if (type != 'init') {//初始化的时候不覆盖原来的值
        $("#feeManId").val('');
        $("#feeMan").val('');
    }

    //费用归属部门为销售部，才显示费用承担人
    if(deptId == 329){// 系统商销售
        //缩写部门格
        $("#memberNumberTr").attr("colspan", 1).attr("class", "form_text_right_three");
        //显示费用承担人
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
        //缩写部门格
        $("#memberNumberTr").attr("colspan", 1).attr("class", "form_text_right_three");
        //显示费用承担人
        $("#feeManTr").show().next("td").show();
        // 渲染费用承担人
        $("#feeMan").yxselect_user("remove").yxselect_user({
            hiddenId: 'feeManId',
            deptIds: deptId,
            isDeptAddedUser: true,//部门需要额外添加人员
            isDeptSetUserRange: true,//部门设置人员选择范围
            event: {
                clearReturn: function () {
                }
            }
        });
        //费用报销人所在部门与费用归属部门相同，则自动获取费用报销人员为费用承担人
        if (type != 'init') {//初始化的时候不覆盖原来的值
            if ($("#CostDepartID").val() == deptId) {
                $("#feeMan").val($("#CostManName").val());
                $("#feeManId").val($("#CostMan").val());
            } else {
                $("#feeMan").val("");
                $("#feeManId").val("");
            }
        }
    } else {
        //扩展部门格
        $("#memberNumberTr").attr("colspan", 3).attr("class", "form_text_right");
        //隐藏费用承担人
        $("#feeManTr").hide().next("td").hide();
        //费用承担人默认为报销单申请人
        $("#feeMan").val($("#CostManName").val());
        $("#feeManId").val($("#CostMan").val());
    }
}

// 检验项目是否超支
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
                            projectOverspendObj.html("<span class='red'>是</span>");
                        } else {
                            projectOverspendObj.html("否");
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
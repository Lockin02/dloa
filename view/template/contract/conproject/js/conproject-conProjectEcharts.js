$(function(){
    $("div.hidden").hide();
    $("#revenue").show();
   //导航控制
    $("#all-link").bind("click",function(){
//        $("div.hidden").show();
    });
//    $("#summary-link").bind("click",function(){
//        $("div.hidden").hide();
//        $("#summary").show();
//    });
    $("#revenue-link").bind("click",function(){
        $("div.hidden").hide();
        $("#revenue").show();
    });
//    $("#geography-link").bind("click",function(){
//        $("div.hidden").hide();
//        $("#geography").show();
//    });
//    $("#KPI-link").bind("click",function(){
//        $("div.hidden").hide();
//        $("#KPI").show();
//    });



// 路径配置
    require.config({
        paths: {
            echarts: 'echarts/build/dist'
        }
    });

// 使用
    require(
        [
            'echarts',
            'echarts/theme/macarons',
            'echarts/chart/line',
            'echarts/chart/bar',
            'echarts/chart/scatter',
            'echarts/chart/k',
            'echarts/chart/pie',
            'echarts/chart/radar',
            'echarts/chart/chord',
            'echarts/chart/force',
            'echarts/chart/map',
            'echarts/chart/gauge',
            'echarts/chart/funnel',
            'echarts/chart/eventRiver'

        ],
        function (ec,theme) {
            // 基于准备好的dom，初始化echarts图表
            var xmsltj = ec.init(document.getElementById('xmsltj'));//项目数量统计
            var xmsltjPie = ec.init(document.getElementById('xmsltjPie'));
            var yszk = ec.init(document.getElementById('yszk'),theme);//营收状况
            var mlzk = ec.init(document.getElementById('mlzk'),theme);//毛利状况
            var mlldb = ec.init(document.getElementById('mlldb'),theme);//毛利率对比
            var proNumGeo = ec.init(document.getElementById('proNumGeo'));//项目数量分布

            //项目数量统计
            $.ajax({
                type: 'POST',
                url: '?model=contract_conproject_conproject&action=getConNumChart',
//              async: false,
                success: function (data) {
                    data = eval("(" + data + ")")
                    var yAxisArr = data.yAxis;
                    var seriesArr = data.series;
                    var option = {
                        title : {
                            text: '项目数量统计',
                            subtext: '执行部门'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
                            data:['执行部门']
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: true},
                                dataView : {show: true, readOnly: false},
                                magicType: {show: true, type: ['line', 'bar']},
                                restore : {show: true},
                                saveAsImage : {show: true}
                            }
                        },
                        calculable : true,
                        xAxis : [
                            {
                                type : 'value',
                                boundaryGap : [0, 0.01]
                            }
                        ],
                        yAxis : [
                            {
                                type : 'category',
                                data : yAxisArr
                            }
                        ],
                        series : [
                            {
                                name:'执行部门',
                                type:'bar',
                                data:seriesArr
                            }
                        ]
                    };
                    // 为echarts对象加载数据
                    xmsltj.setOption(option);
                }
            });
            //项目类型=图图表
            $.ajax({
                type: 'POST',
                url: '?model=contract_conproject_conproject&action=getConNumChartPie',
//              async: false,
                success: function (data) {
                    data = eval("(" + data + ")")
                    var yAxisArr = data.yAxis;
                    var seriesArr = data.series;
                    var optionPie = {
                        tooltip : {
                            trigger: 'item',
                            formatter: "{a} <br/>{b} : {c} ({d}%)"
                        },
                        legend: {
                            orient : 'vertical',
                            x : 'left',
                            data:yAxisArr
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: true},
                                dataView : {show: true, readOnly: false},
                                magicType : {
                                    show: true,
                                    type: ['pie', 'funnel'],
                                    option: {
                                        funnel: {
                                            x: '25%',
                                            width: '50%',
                                            funnelAlign: 'center',
                                            max: 1548
                                        }
                                    }
                                },
                                restore : {show: true},
                                saveAsImage : {show: true}
                            }
                        },
                        calculable : true,
                        series : [
                            {
                                name:'项目数量',
                                type:'pie',
                                radius : ['50%', '70%'],
                                itemStyle : {
                                    normal : {
                                        label : {
                                            show : true
                                        },
                                        labelLine : {
                                            show : true
                                        }
                                    },
                                    emphasis : {
//                                        color:'red',
                                        label : {
                                            show : true,
                                            position : 'center',
                                            textStyle : {
                                                fontSize : '30',
                                                fontWeight : 'bold'
                                            }
                                        }
                                    }
                                },
                                data:seriesArr
                            }
                        ]
                    };
                    xmsltjPie.setOption(optionPie);

                }
            });
            //营收状况
            $.ajax({
                type: 'POST',
                url: '?model=contract_conproject_conproject&action=getRevenueChart',
//              async: false,
                success: function (data) {
                    data = eval("(" + data + ")");
                    var xAxisArr = data.xAxis;
                    var seriesTrueArr = data.seriesTrue;
                    var seriesTarArr = data.seriesTar;
                    var optionRevenue = {
                        title : {
                            text: '营收状况',
                            subtext: '单位（万元）'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
                            data:[
                                '目标','实际'
                            ]
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: false},
                                dataView : {show: true, readOnly: false},
                                magicType : {show: true, type: ['line', 'bar']},
                                restore : {show: true},
                                saveAsImage : {show: true}
                            }
                        },
                        calculable : true,
                        grid: {y: 70, y2:60,x:100, x2:20},
                        yAxis : [
                            {
                                type : 'category',
                                data : xAxisArr
                            },
                            {
                                type : 'category',
                                axisLine: {show:false},
                                axisTick: {show:false},
                                axisLabel: {show:false},
                                splitArea: {show:false},
                                splitLine: {show:false},
                                data : xAxisArr
                            }
                        ],
                        xAxis : [
                            {
                                type : 'value',
                                axisLabel:{formatter:'{value} '}
                            }
                        ],
                        series : [
                            {
                                name:'实际',
                                type:'bar',
//                                itemStyle: {normal: { label:{show:true,formatter:function(a,b,c){return c>0 ? (c +'\n'):'';}}}},
                                itemStyle: {normal: {color:'rgba(243,164,59,2)', label:{show:true}}},
                                data:seriesTrueArr
                            },
                            {
                                name:'目标',
                                type:'bar',
                                yAxisIndex:1,
//                                itemStyle: {normal: { label:{show:true,formatter:function(a,b,c){return c>0 ? (c +'\n'):'';}}}},
                                itemStyle: {normal: {color:'rgba(250,216,96,0.5)', label:{show:true,formatter:function(a,b,c){return c>0 ? (c +'\n'):'';}}}},
                                data:seriesTarArr
                            }
                        ]
                    };
                    // 为echarts对象加载数据
                    yszk.setOption(optionRevenue);
                }
            });
            //毛利状况
            $.ajax({
                type: 'POST',
                url: '?model=contract_conproject_conproject&action=getGrossChart',
//              async: false,
                success: function (data) {
                    data = eval("(" + data + ")");
                    var xAxisArr = data.xAxis;
                    var seriesTrueArr = data.seriesTrue;
                    var seriesTarArr = data.seriesTar;
                    var optionGross = {
                        title : {
                            text: '毛利状况',
                            subtext: '单位（万元）'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
                            data:[
                                '目标','实际'
                            ]
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: false},
                                dataView : {show: true, readOnly: false},
                                magicType : {show: true, type: ['line', 'bar']},
                                restore : {show: true},
                                saveAsImage : {show: true}
                            }
                        },
                        calculable : true,
                        grid: {y: 70, y2:60,x:100, x2:20},
                        yAxis : [
                            {
                                type : 'category',
                                data : xAxisArr
                            },
                            {
                                type : 'category',
                                axisLine: {show:false},
                                axisTick: {show:false},
                                axisLabel: {show:false},
                                splitArea: {show:false},
                                splitLine: {show:false},
                                data : xAxisArr
                            }
                        ],
                        xAxis : [
                            {
                                type : 'value',
                                axisLabel:{formatter:'{value}'}
                            }
                        ],
                        series : [
                            {
                                name:'实际',
                                type:'bar',
//                                itemStyle: {normal: { label:{show:true,formatter:function(a,b,c){return c>0 ? (c +'\n'):'';}}}},
                                itemStyle: {normal: {color:'rgba(0,204,0,1)', label:{show:true}}},
                                data:seriesTrueArr
                            },
                            {
                                name:'目标',
                                type:'bar',
                                yAxisIndex:1,
//                                itemStyle: {normal: { label:{show:true,formatter:function(a,b,c){return c>0 ? (c +'\n'):'';}}}},
                                itemStyle: {normal: {color:'rgba(153,255,153,0.5)', label:{show:true,formatter:function(a,b,c){return c>0 ? (c +'\n'):'';}}}},
                                data:seriesTarArr
                            }
                        ]
                    };
                    // 为echarts对象加载数据
                    mlzk.setOption(optionGross);
                }
            });
            //毛利率对比
            $.ajax({
                type: 'POST',
                url: '?model=contract_conproject_conproject&action=getRateGrossChart',
//              async: false,
                success: function (data) {
                    data = eval("(" + data + ")");
                    var xAxisArr = data.xAxis;
                    var seriesGSArr = data.seriesGS;
                    var seriesSJArr = data.seriesSJ;
                    //毛利率对比
                    var mlldbop = {
                        title : {
                            text: '毛利率对比',
                            subtext: '-'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
                            data:['概算','实际']
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                dataView : {show: true, readOnly: false},
                                magicType : {show: true, type: ['line', 'bar']},
                                restore : {show: true},
                                saveAsImage : {show: true}
                            }
                        },
                        calculable : true,
                        xAxis : [
                            {
                                type : 'category',
                                data : xAxisArr
                            }
                        ],
                        grid: {y: 80, y2:50,x:100, x2:290},
                        yAxis : [
                            {
                                type : 'value',
                                axisLabel:{formatter:'{value}% '}
                            }
                        ],
                        series : [
                            {
                                name:'概算',
                                type:'bar',
                                data:seriesGSArr,
                                itemStyle: {normal: {color:'#029BFF', label:{show:true,formatter:function(a,b,c){return c>0 ? (c +'%'):'';}}}},
                                markLine : {
                                    data : [
                                        {type : 'average', name: '平均值'}
                                    ]
                                }
                            },
                            {
                                name:'实际',
                                type:'bar',
                                data:seriesSJArr,
                                itemStyle: {normal: {color:'#9EDB02', label:{show:true,formatter:function(a,b,c){return c>0 ? (c +'%'):'';}}}},
                                markLine : {
                                    data : [
                                        {type : 'average', name : '平均值'}
                                    ]
                                }
                            }
                        ]
                    };
                    mlldb.setOption(mlldbop);
                }
            });

            //项目数量分布
            $.ajax({
                type: 'POST',
                url: '?model=contract_conproject_conproject&action=getProNumMapChart',
//              async: false,
                success: function (data) {
                    var dataArr = eval("(" + data + ")");
                    var legend = dataArr.legend;
                    var seriesArr = dataArr.series;
                    var seriesData = [];
                    var maxNum = parseInt(dataArr.maxNum,10)*3;
                    for (key in seriesArr){
                        var tempData = [];
                        for (k in seriesArr[key]){
                            var kv = {
                                name:seriesArr[key][k]['name'],
                                value:parseInt(seriesArr[key][k]['value'],10)
                            }
                            tempData.push(kv);
                        }
                        var tt = {
                            name: key,
                            type: 'map',
                            showLegendSymbol: true,
                            mapType: 'china',
                            roam: false,
                            itemStyle:{
                                normal:{label:{show:true,position:'outer'}},
                                emphasis:{label:{show:true,position:'outer'}}
                            },
                            data:tempData
                        }
                        seriesData.push(tt);
                    }
                    var proNumGeoOption = {
                        title : {
                            text: '项目数量分布',
                            subtext: '-',
                            x:'center'
                        },
                        tooltip : {
                            trigger: 'item'
                        },
                        legend: {
                            orient: 'vertical',
                            x:'left',
                            data:legend
                        },
                        dataRange: {
                            min: 0,
                            max: maxNum,
                            x: 'left',
                            y: 'bottom',
                            text:['高','低'],           // 文本，默认为数值文本
                            calculable : true
                        },
                        toolbox: {
                            show: true,
                            orient : 'vertical',
                            x: 'right',
                            y: 'center',
                            feature : {
                                mark : {show: true},
                                dataView : {show: true, readOnly: false},
                                restore : {show: true},
                                saveAsImage : {show: true}
                            }
                        },
                        roamController: {
                            show: true,
                            x: 'right',
                            mapTypeControl: {
                                'china': true
                            }
                        },
                        series : seriesData
                    };

                    // 为echarts对象加载数据
                    proNumGeo.setOption(proNumGeoOption);
                }
            });
        }
    );
})
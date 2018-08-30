$(function(){

    var chart = new FusionCharts(
        'FusionCharts/swf/Charts1/Column2D.swf',
        "chartSwf", "600", "400", "0", "1");
//    chart.setDataXML(data);
    chart.setDataXML("<xml><chart caption='xxxx' xAxisName='Mon' yAxisName='Money' showNames='1' decimalPrecision='0' formatNumberScale='0'><set name='1' value='100'><set name='2' value='200'></chart></xml>")

    chart.render("chart");
})
<script src="http://nvd3.org/lib/d3.v2.js"></script>
<script src="http://nvd3.org/lib/fisheye.js"></script>
<script src="http://nvd3.org/nv.d3.js"></script>

<link href="http://nvd3.org/css/common.css" rel="stylesheet">
<link href="http://nvd3.org/src/nv.d3.css" rel="stylesheet">
<link href="http://nvd3.org/css/syntax.css" rel="stylesheet">
<style>
    #chart_bar svg {
        height: 200px;
    }
    #chart_bar svg .nvd3 .nv-axis line{
        stroke: #CCC;
    }
    #chart_bar svg text {
        font: normal 12px Helvetica;
        fill: #333333;
    }
</style>
<script>
    nv.addGraph(function() {
        var chart = nv.models.discreteBarChart()
            .x(function(d) { return d.label })
            .y(function(d) { return d.value })
            .staggerLabels(false)
            .tooltips(false)
            .showValues(true)
            .forceY([0,100])
            .valueFormat(d3.format('f'))
            .color(['#00a99d']);
        chart.margin({top:10, right:10, bottom:20, left:35})
        chart.yAxis
            .tickFormat(d3.format(',f'));
        d3.select('#chart_bar svg')
            .datum(data_bar())
            .transition().duration(500)
            .call(chart);

        nv.utils.windowResize(chart.update);

        return chart;
    });
    function data_bar() {
        return [
            {
                key: "Group activity",
                values: [
                    {
                        "label" : "G1" ,
                        "value" : 29
                    } ,
                    {
                        "label" : "G2" ,
                        "value" : 10
                    } ,
                    {
                        "label" : "G3" ,
                        "value" : 40
                    } ,
                    {
                        "label" : "G4" ,
                        "value" : 25
                    } ,
                    {
                        "label" : "G5" ,
                        "value" : 80
                    } ,
                    {
                        "label" : "G6" ,
                        "value" : 50
                    }

                ]
            }
        ];
    }
</script>
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: equipo
 * Date: 6/02/14
 * Time: 12:37
 * To change this template use File | Settings | File Templates.
 */
$content = '
<!-- foreach-->
<a href="javascript:;" class="fa fa-chevron-right"></a>
<a href="javascript:;" class="fa fa-chevron-left"></a>
<div id="chart_bar" class="separator">
    <h3 style="color: #00a99d;">Empirical formula</h3>
    <svg></svg>
</div>
<!--<div class="separator" style="padding-top: 10px;">
    <canvas id="bar" height="200" style="width: 100% !important;"></canvas>
</div>-->
<!-- endforeach-->';

$all_link = elgg_view('output/url', array(
    'href' => "linkHref",
    'text' => elgg_echo('link:view:all'),
    'is_trusted' => true,
));
echo elgg_view('landing/module', array(
    'name'      => "group_activity",
    'title'     => "Group Activity",
    'content'   => $content,
    'all_link'  => $all_link,
));
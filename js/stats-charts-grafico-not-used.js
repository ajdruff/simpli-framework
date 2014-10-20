/* 
 * Test Charting - Flot Charts
 * stats-charts.js
 */


//var bargraph2 = new Grafico.BarGraph($('stats-click-bargraph2'),
//[31, 12, 12, 10, 6, 4, 26],
//{
//  labels :              ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
//  height:300,
//  width: 400,
//  color :               '#4b80b6',
//  meanline :            true,
//  label_rotation :      -30,
//  vertical_label_unit : "%",
//  bargraph_lastcolor :  "#666666",
//  hover_color :         "#006677",
//  datalabels :          {one: ["January", "February", "March", "April", "May", "June", "July"]}
//});


//var data=[31, 12, 12, 10, 6, 4, 26];
//var options={
//  labels :              ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
//  height:300,
//  width: 400,
//  color :               '#4b80b6',
//  meanline :            true,
//  label_rotation :      -30,
//  vertical_label_unit : "%",
//  bargraph_lastcolor :  "#666666",
//  hover_color :         "#006677",
//  datalabels :          {one: ["January", "February", "March", "April", "May", "June", "July"]}
//};

var data=simpli.frames.vars.plugin.charts.chart1.data;
var options=simpli.frames.vars.plugin.charts.chart1.options;

var bargraph3 = new Grafico.BarGraph($('mychart'),data,options
);


//var linegraph = new Grafico.LineGraph($('linegraph1'), { workload: [8, 10, 6, 12, 7, 6, 9] });

//var linegraph2 = new Grafico.LineGraph($('linegraph'), { workload: [0, 0, 8, 10, 6, 12, 7] },
//{
//  grid :                  true,
//  plot_padding :          0,
//  curve_amount :          0,
//  start_at_zero :         false,
//  stroke_width :          3,
//  label_rotation :        -30,
//  font_size :             11,
//  vertical_label_unit :   "hr",
//  colors :                {workload: '#4b80b6'},
//  background_color :      "#fff",
//  label_color :           "#444",
//  grid_color :            "#ccf",
//  markers :               "value",
//  meanline :              { 'stroke-width': '2px', stroke: '#000' },
//  draw_axis :             false,
//  labels :                ['mon', '', 'wed', '', 'fri', '', 'sun'],
//  hide_empty_label_grid : true,
//  datalabels :            {workload:function(idx, value) {
//	return ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'][idx] + " : " + value;
//  }},
//});
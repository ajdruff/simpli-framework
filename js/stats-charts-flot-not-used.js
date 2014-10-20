/* 
 * Test Charting
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







//var data=nomstock.com.vars.plugin.charts.chart1.data;
// var options=nomstock.com.vars.plugin.charts.chart1.options;

/*
 * Flot Plot
 */
var options = {
    series: {
        // lines: { show: true },
        points: {show: false},
        bars: {
                 lineWidth: 20,
                         show: true,
                 barWidth: 60 * 60 * 1000,
                 align: 'center',
                 fill: true
        },
        lines: {
            show: false,
            lineWidth: 2,
            fill: true

        }},
    xaxis: {
        //    min: Date.UTC(1970, 0, 0, 0, 0), max:   Date.UTC(2020, 1, 0, 0, 0),
             mode: 'time',
             timeformat: "%b %d",
             minTickSize: [1, "month"],
             tickSize: [1, "day"],
             autoscaleMargin: .10
   //     reserveSpace: true,
     //   autoscaleMargin: .25
     //   min: (new Date("2014/01/01")).getTime(),
      //   max: (new Date("2014/02/15")).getTime(),
//tickSize: [1, "day"] 
    },
//    selection: {
//        mode: "x"
//    },
//    grid: {
//        markings: weekendAreas
//    }




};




var data = [
    {label: "Clicks", data: nomstock.com.vars.plugin.charts.flot.chart1.clicks_history},
    {label: "Impressions", data: nomstock.com.vars.plugin.charts.flot.chart1.impressions_history}
];

// data = [
//    {label: "Clicks", data:[1391904000000,5]},
//    {label: "Impressions", data: nomstock.com.vars.plugin.charts.flot.chart1.impressions_history}
//];
var plot = jQuery.plot('#stats-history', data, options);


// helper for returning the weekends in a period
function weekendAreas(axes) {
    var markings = [],
            d = new Date(axes.xaxis.min);
// go to the first Saturday
    d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
    d.setUTCSeconds(0);
    d.setUTCMinutes(0);
    d.setUTCHours(0);
    var i = d.getTime();
// when we don't set yaxis, the rectangle automatically
// extends to infinity upwards and downwards
    do {
        markings.push({xaxis: {from: i, to: i + 2 * 24 * 60 * 60 * 1000}});
        i += 7 * 24 * 60 * 60 * 1000;
    } while (i < axes.xaxis.max);
    return markings;
}
//var bargraph3 = new Grafico.BarGraph($('mychart'),data,options);


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
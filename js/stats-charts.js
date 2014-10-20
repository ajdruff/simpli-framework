/* 
 * Nomstock Stats
 * Analytics for Domain Names for Nomstock.com
 * stats-charts.js
 */

//var data=nomstock.com.vars.plugin.charts.chart1.data;
// var options=nomstock.com.vars.plugin.charts.chart1.options;

//declare vars
var chartData;
var ticks;
var max_impressions;



	

    Array.max = function( array ){
        return Math.max.apply( Math, array );
    };
     
    Array.min = function( array ){
        return Math.min.apply( Math, array );
    };





/*
 * Historical  Chart
 */
//init
chartData=[];
ticks=[];
max_impressions=0;


chartData=[
      nomstock.com.vars.plugin.charts.jqplot.historicalTraffic.impressions,
    nomstock.com.vars.plugin.charts.jqplot.historicalTraffic.clicks
]
  

ticks = nomstock.com.vars.plugin.charts.jqplot.historicalTraffic.ticks;
max_impressions= Array.max(nomstock.com.vars.plugin.charts.jqplot.historicalTraffic.impressions);
/*
 * check if no impressions were made and if so, then not enough data...
 */

if (max_impressions<=0  ) {
 jQuery('#stats-history').html('<div class="jumbotron"><H2>Not Enough Data.<h2></div>');   
}else {
chartHistorical('stats-history',chartData,ticks);

}


/*
 * Today's Chart
 */

//init
chartData=[];
ticks=[];
max_impressions=0;





chartData=[
     nomstock.com.vars.plugin.charts.jqplot.todaysTraffic.impressions,
    nomstock.com.vars.plugin.charts.jqplot.todaysTraffic.clicks
   

];




max_impressions= Array.max(nomstock.com.vars.plugin.charts.jqplot.todaysTraffic.impressions);



ticks = nomstock.com.vars.plugin.charts.jqplot.todaysTraffic.ticks;

nomstock.com.log('ticks = ' + ticks);
nomstock.com.log('chartData = ' + chartData);
nomstock.com.log('clicks = ' + nomstock.com.vars.plugin.charts.jqplot.todaysTraffic.clicks);
nomstock.com.log('impressions = ' + nomstock.com.vars.plugin.charts.jqplot.todaysTraffic.impressions);
nomstock.com.log('max_impressions = ' + max_impressions);

/*
 * Check for Enough Data Before we can display the chart
 * We do this by finding the maximum nummber of impressions, and if its 
 * equal to zero, we conclude that no one has even seen the name yet!
 */
if (max_impressions<=0) {
 jQuery('#stats-today').html('<div class="jumbotron"><H2>Not Enough Data.<h2></div>');   
}else {
chartToday('stats-today',chartData,ticks);
}

function chartHistorical(chartId,chartData,ticks){
    /*
     * jqPlot doesnt seem to calculate range very well, ]
     * so we'll set max to always be equal to 100+ the maximum number of impressions ( after rounding to the nearest 100). Impressions are assumed to always be the first 0th array in ChartData
     */
      var yAxisMax=GetMaxY(chartData);
    
    
    var chart = jQuery.jqplot(chartId, chartData, {
    animate: !jQuery.jqplot.use_excanvas,
    seriesDefaults: {
        renderer: jQuery.jqplot.BarRenderer,
        pointLabels: {
            show: true
            ,
            formatString: "%#.0f" //label points will show as 0 instead of 0.0 and 1 instead of 1.0
        },
        rendererOptions: {
            barPadding: 8, // number of pixels between
            // adjacent bars in the same
            // group (same category or bin).
            barMargin: 25, // number of pixels between
            // adjacent groups of bars.
            barDirection: 'vertical', // vertical or
            // horizontal.
            barWidth: 20, // width of the bars. null to
            // calculate automatically.

        }
    },
    series: [
         {
            label: 'Impressions'
        },
        {
            label: 'Clicks'
        }  ],
    seriesColors: ["#efa229",  "#4BB2C5"],//"#245779",
   
    axesDefaults: {
        base: 10, // the logarithmic base.
        tickDistribution: 'evens', // 'even' or 'power'.
        // 'even' will produce
        // with even visiual
        // (pixel)
        // spacing on the axis. 'power' will produce ticks
        // spaced by
        // increasing powers of the log base.
    },
    axesDefaults : {
        tickRenderer: jQuery.jqplot.CanvasAxisTickRenderer,
        tickOptions: {
            fontSize: '14pt' // font size for labels
        }
    },
    axes: {
        xaxis: {
            renderer: jQuery.jqplot.CategoryAxisRenderer,
            ticks: ticks
   
        },
        yaxis: {
            // Don't pad out the bottom of the data range.
            // By default,
            // axes scaled as if data extended 10% above and
            // below the
            // actual range to prevent data points right on
            // grid boundaries.
            // Don't want to do that here.
            padMin: 0,
            min: 0,
            max: yAxisMax,
     tickOptions: {formatString: "%#.0f" }, // so we don't show .5 at tick marks
        }
    },
    tickOptions: {
        fontSize: '14pt'
    },
    legend: {
        show: true,
        location: 'n', // compass direction, nw, n, ne,
        // e, se, s, sw, w.
        xoffset: 12, // pixel offset of the legend box
        // from the x (or x2) axis.
        yoffset: 12, // pixel offset of the legend box
        // from the y (or y2) axis.
        placement: 'inside'
    },
    cursor: {
        show: false,
        showTooltip: true,
        tooltipLocation: 'ne',
    },
    grid: {
        background: 'white'
    }
});
}


function chartToday(chartId,chartData,ticks){
        /*
     * jqPlot doesnt seem to calculate range very well, ]
     * so we'll set max to always be equal to 100+ the maximum number of impressions ( after rounding to the nearest 100). Impressions are assumed to always be the first 0th array in ChartData
     */
    
    var yAxisMax=GetMaxY(chartData);
nomstock.com.log('yAxisMax ' +yAxisMax );
    var chart = jQuery.jqplot(chartId, chartData, {
    animate: !jQuery.jqplot.use_excanvas,
    seriesDefaults: {
        renderer: jQuery.jqplot.BarRenderer,
        pointLabels: {
            show: true
            ,
            formatString: "%#.0f"//label points will show as 0 instead of 0.0 and 1 instead of 1.0
        },
        rendererOptions: {
            barPadding: 8, // number of pixels between
            // adjacent bars in the same
            // group (same category or bin).
            barMargin: 25, // number of pixels between
            // adjacent groups of bars.
            barDirection: 'vertical', // vertical or
            // horizontal.
            barWidth: 5, // width of the bars. null to
            // calculate automatically.

        }
    },
    series: [ {
            label: 'Impressions'
        }, {
            label: 'Clicks'
        }, ],
    seriesColors: ["#efa229",  "#4BB2C5"],//"#245779",
   
    axesDefaults: {
        base: 10, // the logarithmic base.
        tickDistribution: 'even', // 'even' or 'power'.
        // 'even' will produce
        // with even visiual
        // (pixel)
        // spacing on the axis. 'power' will produce ticks
        // spaced by
        // increasing powers of the log base.
    },
    axesDefaults : {
        tickRenderer: jQuery.jqplot.CanvasAxisTickRenderer,
        tickOptions: {
            fontSize: '14pt', // font size for labels
  
        }
    },
    axes: {
        xaxis: {
            renderer: jQuery.jqplot.CategoryAxisRenderer,
            ticks: ticks,
            tickOptions: {
                               angle: -30,
          fontSize: '10pt'
            },
            label: 'Hour (PST)',
            labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer
           
        },
        yaxis: {
            // Don't pad out the bottom of the data range.
            // By default,
            // axes scaled as if data extended 10% above and
            // below the
            // actual range to prevent data points right on
            // grid boundaries.
            // Don't want to do that here.
            padMin: 0,
            label: 'Uniques',
            labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer,
            min: 0,
            max: yAxisMax,
            tickOptions: {formatString: "%#.0f" },// so we don't show .5 at tick marks
        }
    },
    tickOptions: {
        fontSize: '14pt',
    
    },
    legend: {
        show: true,
        location: 'n', // compass direction, nw, n, ne,
        // e, se, s, sw, w.
        xoffset: 12, // pixel offset of the legend box
        // from the x (or x2) axis.
        yoffset: 12, // pixel offset of the legend box
        // from the y (or y2) axis.
        placement: 'inside'
    },
    cursor: {
        show: false,
        showTooltip: true,
        tooltipLocation: 'ne',
    },
    grid: {
        background: 'white'
    }
});

}

/**
 * Get Max Y Value
 *
 * Determines Max Value on Y Axis. Jplot doesnt seem to calculate it properly
 * @param array chartData The array of impressions and clicks
 * @return string The parsed output of the form body tag
 */

function GetMaxY(chartData) {
      var maxImpressions=Math.ceil((Math.max.apply(Math,chartData[0]))/10)*10; 
    if (maxImpressions>100) {
        var yAxisMax=Math.ceil((maxImpressions)/100)*100; 
    }else {
        yAxisMax=maxImpressions+20;
        
    }
   
    
    /*
     * At higher ranges, we need to 
     * add 500 so we have enough room for the label to show before
     * hitting the top of the chart, where it would be cut off
     * otherwise.
     */
    if (yAxisMax>500) {
        yAxisMax=yAxisMax+500;
    } 
    return (Math.ceil(yAxisMax/10)*10);
}

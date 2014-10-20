<?php

/**
 * Core Module
 *
 * Plugin's core functionality
 * See http://simpliwp.com/plugin-builder/ for examples
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 *
 *
 */
class Simpli_Frames_Modules_NomstockStats extends Simpli_Frames_Base_v1c2_Plugin_Module {

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t(); //trace provides a information about the method and arguments, and provides a backtrace in an expandable box. A visual trace is also provided if graphiviz is enabled.





    }

    /**
     * Add jqPlot Charts
     * 
     * 
     * Adds Charts Using the jqPlot javascript library. It does this by localizing the chart data
     * so it becomes available in javascript.
     * 
     * Note that this is a very unoptimized function. It repeats the same 
     * operations for historical as it does for today's data.
     * At some point, refactor to optimize.
     * 
     *
     * @param none
     * @return void
     */
    public function addjqPlotCharts( $domain_name = "mydomain.com" ) {

        /*
         * This function will do the following in this order:
         * - retrieve the impression data from the database
         * - transform it into an array where the dates are keys and values are the counts
         * - use the impression dates to create an array for click data
         * - retrieve the click data from the database and create an array where the impression dates are keys and values are unique_click counts
         * -localize the data for consumption by jqPlot.
         * this data will become available in javascript as : 
         * simpli.frames.vars.plugin.charts.jqplot.chart1.clicks  //click counts series
         * simpli.frames.vars.plugin.charts.jqplot.chart1.impressions    //impression counts series
         * simpli.frames.vars.plugin.charts.jqplot.chart1.ticks   //ticks ( labels)
         * it is then used by stats-charts.js



         */


        #initialize
        $impressionsQueryResults = array();
        $clicksQueryResults = array();
        $impressions = array();
        $clicks = array();
        $dates = array();
        $new_clicks = array();
        $new_impressions = array();


        /*
         * check for demo request, which
         * is anytime someone requests example.com
         * If a Demo Request, we'll use hardcoded data.
         */

        if ( $domain_name === 'example.com' ) {
            $historyChart[ 'impressions' ] = array( '5487', '3400', '4300', '4800' );
            $historyChart[ 'clicks' ] = array( '324', '286', '330', '280' );

            $historyChart[ 'ticks' ] = array( 'Feb 1', 'Feb 2', 'Feb 10', 'Feb 20' );


            $todaysChart[ 'impressions' ] = array_values(array(
                '00:00-01:00' => 0
                , '01:00-02:00' => 0
                , '02:00-03:00' => 0
                , '03:00-04:00' => 0
                , '04:00-05:00' => 0
                , '05:00-06:00' => 505
                , '06:00-07:00' => 411
                , '07:00-08:00' => 470
                , '08:00-09:00' => 620
                , '09:00-10:00' => 325
                , '10:00-11:00' => 530
                , '11:00-12:00' => 663
                , '12:00-13:00' => 842
                , '13:00-14:00' => 955
                , '14:00-15:00' => 0
                , '15:00-16:00' => 0
                , '16:00-17:00' => 0
                , '17:00-18:00' => 0
                , '18:00-19:00' => 0
                , '19:00-20:00' => 0
                , '20:00-21:00' => 0
                , '21:00-22:00' => 0
                , '22:00-23:00' => 0
                , '23:00-24:00' => 0
            ));
            $todaysChart[ 'clicks' ] = array_values(array
                (
                '00:00-01:00' => 0
                , '01:00-02:00' => 0
                , '02:00-03:00' => 0
                , '03:00-04:00' => 0
                , '04:00-05:00' => 0
                , '05:00-06:00' => 34
                , '06:00-07:00' => 28
                , '07:00-08:00' => 32
                , '08:00-09:00' => 42
                , '09:00-10:00' => 22
                , '10:00-11:00' => 36
                , '11:00-12:00' => 45
                , '12:00-13:00' => 57
                , '13:00-14:00' => 65
                , '14:00-15:00' => 0
                , '15:00-16:00' => 0
                , '16:00-17:00' => 0
                , '17:00-18:00' => 0
                , '18:00-19:00' => 0
                , '19:00-20:00' => 0
                , '20:00-21:00' => 0
                , '21:00-22:00' => 0
                , '22:00-23:00' => 0
                , '23:00-24:00' => 0
            ));

            $series_framework = $this->getjqPlotHourlySeriesFramework();

            $todaysChart[ 'ticks' ] = array_keys( $series_framework );


            /*
             * Now send everything to javascript
             */


            $vars = $this->plugin()->getLocalVars();
            $vars[ 'plugin' ][ 'charts' ][ 'jqplot' ][ 'historicalTraffic' ] = $historyChart;
            $vars[ 'plugin' ][ 'charts' ][ 'jqplot' ][ 'todaysTraffic' ] = $todaysChart;
            $this->debug()->logVar( '$vars = ', $vars );
            $this->plugin()->setLocalVars( $vars );

            return;

}

        /*
         * Get Impressions
         * 
         */



        $impressionsQueryResults = $this->plugin()->getModule( 'Tools' )->mySQLCallProc(
                "nstock_stats_get_report('by-day','impressions','" . $domain_name . "',Null)"// $stored_procedure 
        );

  



        $impressions = $this->plugin()->getModule( 'Tools' )->remapAssocArray(
                $impressionsQueryResults, //the array to remap
                'date', //$key1 The key whose value will become the key of the converted array. In the example, this would be 'date'
                'unique_count' //$key2 The key whose value will become the value of the remapped array. In the example, this would be 'unique_clicks'
        );
        /*
         * make sure we have an array before we do any array operations
         */

        $impressions = (is_array( $impressions )) ? $impressions : array();



        $dates = array_keys( $impressions );

        $this->debug()->logVar( '$dates to use as ticks = ', $dates );


        /*
         * Get the click data
         */

        $clicks = $this->getjqPlotHistoryClickData( $impressions, $domain_name );



        $this->debug()->logVar( 'final click data $clicks = ', $clicks );




        /*
         * Send the data to the chart, using the keys for the ticks, and the values as the series
         */


        $historyChart[ 'impressions' ] = array_values( $impressions );
        $historyChart[ 'clicks' ] = array_values( $clicks );

        
        
        /*
         * Convert the ticks to the desired time format
         * F j   // March 10
         * M j  .// Mar 10
         */

        $historyChart[ 'ticks' ] = $this->convertDates( $dates, "M j" );
        $this->debug()->logVar( '$historyChart[ ticks ] ', $historyChart[ 'ticks' ] );



        $this->debug()->logVar( 'Final $historyChart = ', $historyChart );


        /*
         * Repeat, Using Todays Data
         * 
         * 
         * Now Do the same as above, but this time with daily charting.
         * 
         * Future: Consider optimization/refactoring this code, but at this point,
         * its more work than its worth.
         * 
         */

#initialize
        $impressionsQueryResults = array();
        $clicksQueryResults = array();
        $impressions = array();
        $clicks = array();
        $dates = array();
        $new_clicks = array();
        $new_impressions = array();
        $impressionsQueryResults = $this->plugin()->getModule( 'Tools' )->mySQLCallProc(
                "nstock_stats_get_report('by-hour-for-today','impressions','" . $domain_name . "',Null)"// $stored_procedure 
        );
        $this->debug()->logVar( '$impressions before remapping ', $impressionsQueryResults );


        

        /*
         * Impressions
         */


        $impressions = $this->plugin()->getModule( 'Tools' )->remapAssocArray(
                $impressionsQueryResults, //the array to remap
                'hour', //$key1 The key whose value will become the key of the converted array. In the example, this would be 'date'
                'unique_count' //$key2 The key whose value will become the value of the remapped array. In the example, this would be 'unique_clicks'
        );
        /*
         * make sure we have an array before we do any array operations
         */
        $impressions = (is_array( $impressions )) ? $impressions : array();


        /*
         * Change hourly keys to proper labels.
         * 
         * so instead of a key like this '9' , 
         * we need keys like this '0900-1000' 
         * in the former, we are saying the series data is for hour 9
         * so we want the key to be the actual label we'll use in the chart,
         * which is 0900-1000
         */
        $impressions = $this->reformatJqPlotHourlyKeys( $impressions );


        $this->debug()->logVar( '$impressions after reformatting keys as hourly labels = ', $impressions );
        /*
         * Build Hour Tick Labels
         */



        $series_framework = $this->getjqPlotHourlySeriesFramework();

        /*
         * Use the keys for the framework as the ticks
         */
        $ticks = array_keys( $series_framework );


        /*
         * Create an array of all zeros with ticks as keys
         * it will fill in zeros for hours where impressions were not made
         * and numbers for where there were data.
         */

        $this->debug()->logVar( '$series_framework = ', $series_framework );

        $impressions = array_merge( $series_framework, $impressions );


        $clicks = $this->getjqPlotTodaysClickData( $impressions, $domain_name );

        /*
         * after conversion
         */

        $this->debug()->logVar( '$clicks = ', $clicks );
        /*
         * Send the data to the chart, using the keys for the ticks, and the values as the series
         */


        $todaysChart[ 'impressions' ] = array_values( $impressions );
        $todaysChart[ 'clicks' ] = array_values( $clicks );
        // $todaysChart[ 'ticks' ] = $this->convertDates( $dates, "M j" );
        $todaysChart[ 'ticks' ] = $ticks;
        $this->debug()->logVar( '$todaysChart = ', $todaysChart );



        /*
         * Now send everything to javascript
         */


        $vars = $this->plugin()->getLocalVars();
        $vars[ 'plugin' ][ 'charts' ][ 'jqplot' ][ 'historicalTraffic' ] = $historyChart;
        $vars[ 'plugin' ][ 'charts' ][ 'jqplot' ][ 'todaysTraffic' ] = $todaysChart;
        $this->debug()->logVar( '$vars = ', $vars );
        $this->plugin()->setLocalVars( $vars );




      }

    /**
     * Configure Flot Charts
     * 
     * 
     * Configures the Flot Charts on the status page
     *
     * @param none
     * @return void
     */
    public function OLDconfigFlotCharts( $domain_name = "mydomain.com" ) {



        $proc = ( "nstock_stats_get_report('by-day','clicks','" . $domain_name . "',Null)" );

        $chartData = $this->plugin()->getModule( 'Tools' )->mySQLCallProc( $proc );
        $series = array();
        foreach ( $chartData as $point ) {
            $series[] = array( strtotime( $point[ 'date' ] . " UTC" ) * 1000, $point[ 'unique_count' ] );
}



        /*
         * Localize Variables so we can use them with Grafico
         * (Send Variables to Javascript)
         * we want the namespace to be simpli.frames.vars.plugin.charts.flot
         */
        $chart[ 'clicks_history' ] = $series;

        /*
         * Get Impressions
         * 
         */

        $proc = ( "nstock_stats_get_report('by-day','impressions','" . $domain_name . "',Null)" );


        $chartData = $this->plugin()->getModule( 'Tools' )->mySQLCallProc( $proc );
        $series = array();
        foreach ( $chartData as $point ) {
            $series[] = array( strtotime( $point[ 'date' ] . " UTC" ) * 1000, $point[ 'count' ] );
}

        $chart[ 'impressions_history' ] = $series;

        /*
         * 
         * Now Get Today's Stats
         * 
         */



        $vars = $this->plugin()->getLocalVars();
        $vars[ 'plugin' ][ 'charts' ][ 'flot' ][ 'chart1' ] = $chart;
        $this->debug()->logVar( '$vars = ', $vars );
        $this->plugin()->setLocalVars( $vars );

    }

    /**
     * ConfigGraficoCharts
     * Grafico Not Used - Kept only as reference. 
     * Configures Grafico Charts .
     *
     * @param none
     * @return void
     */
    public function OLD_configGraficoCharts() {

        $mydomain = "mydomain.com";
        $chartData = $this->getChartData( "nstock_stats_get_clicks_by_day('" . $mydomain . "')" );
        $this->debug()->logVar( '$chartData = ', $chartData );
        $data = $this->plugin()->tools()->getArrayColumn( $chartData, 'unique_count' ); //$chartData[ 'unique_count' ];


        $labels = $this->plugin()->tools()->getArrayColumn( $chartData, 'date' );




        /*
         * Graphico Options for Chart 1
         */

        $options = array(
            'labels' => $labels,
            'color' => '#4b80b6',
            'acceptable_range' => 100,
            'meanline' => false,
            'label_rotation' => -30,
            //   'vertical_label_unit' => "#",
            'bargraph_lastcolor' => "#666666",
            'hover_color' => "#006677",
            'datalabels' => array( 'one' => $data )
        );



        /*
         * Localize Variables so we can use them with Grafico
         * (Send Variables to Javascript)
         * we want the namespace to be simpli.frames.vars.plugin.charts.grafico
         */

        $chart[ 'data' ] = $data;
        $chart[ 'options' ] = $options;
        $vars = $this->plugin()->getLocalVars();
        $vars[ 'plugin' ][ 'charts' ][ 'grafico' ][ 'chart1' ] = $chart;
        $this->debug()->logVar( '$vars = ', $vars );
        $this->plugin()->setLocalVars( $vars );


    }

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {
        $this->debug()->t();


        add_action( 'init', array( $this, 'init_session' ), 1 );
        add_action( 'wp_enqueue_scripts', array( $this, 'hookEnqueueScripts' ) );
        add_action( 'wp_print_styles', array( $this, 'hookPrintStyles' ) );




        /*
         * add scripts
         *  */
//
//        add_action( 'wp_enqueue_scripts', array( $this, 'hookEnqueueScripts' ) );
//        add_action( 'admin_enqueue_scripts', array( $this, 'hookEnqueueScripts' ) );


        /*
         *  add custom ajax handlers
         * this is where you map any form actions with the class method that handles the ajax request
         *
         * adding a wp_ajax hook in this format will execute the specified class method whenever the ajax request specifies an action = to $this->plugin()->getSlug() . '_xxxx'
          see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
         *
         * Example:
         * add_action('wp_ajax_' . $this->plugin()->getSlug() . '_settings_save', array($this, 'save'));
         *
         */
    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function hookEnqueueOtherChartScripts() {


        /* Example
          wp_enqueue_style($this->plugin()->getSlug() . '-admin-page', $this->plugin()->getAdminUrl() . '/css/settings.css', array(), $this->plugin()->getVersion());
          wp_enqueue_script('jquery');
          wp_enqueue_script('jquery-form');
          wp_enqueue_script('post');
         *
         */

        /* Example
          $handle = $this->plugin()->getSlug() . '_core.js';
          $src = $this->plugin()->getUrl() . '/js/' . $this->plugin()->getSlug() . '_core.js';
          $deps = 'jquery';
          $ver = '1.0';
          $in_footer = false;
          wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
         *
         */




        /*
         * Load Javascript Charting Library, Flot Charts
         * 
         */
        $handle = 'flot';
        $src = $this->plugin()->getUrl() . '/js/flot-charts/flot/jquery.flot.js';
        $deps = 'jquery';
        $ver = '1.0';
        $in_footer = false;
        //     wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );

        /*
         * Flot time plugin
         * 
         */
        $handle = 'flot-time';
        $src = $this->plugin()->getUrl() . '/js/flot-charts/flot/jquery.flot.time.js';
        //   wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );

        /*
         * Prototype, required by Graphico
         * Comes with WordPress, just need make sure its loaded
         */

        //    wp_enqueue_script( 'prototype' );
        /*
         * Load Raphael Javascript Library
         * http://raphaeljs.com/ , required by Graphico
         */
        $handle = $this->plugin()->getSlug() . '_raphael.js';
        $src = $this->plugin()->getUrl() . '/js/grafico-charts/raphael-min.js';
        wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );


        /*
         * Load Javascript Charting Library, Graphico
         * http://grafico.kilianvalkhof.com/index.html 
         * 
         * 
         */
        $handle = 'grafico';
        $src = $this->plugin()->getUrl() . '/js/grafico-charts/grafico.min.js';
        //    wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );



        /*
         * Load Javascript Charting Library, Graphael
         * http://g.raphaeljs.com/
         * 
         * 
         */
        $handle = $this->plugin()->getSlug() . '_graphael-charts.js';
        $src = $this->plugin()->getUrl() . '/js/g.raphael-charts/g.raphael-min.js';
        //    wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );

        /*
         * Load Graphael Bar Chart Library
         * http://g.raphaeljs.com/
         */

        $handle = $this->plugin()->getSlug() . '_graphael-charts-bar.js';
        $src = $this->plugin()->getUrl() . '/js/g.raphael-charts/g.bar-min.js';
        //     wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
        /*
         * Load Graphael Line Chart Library
         * 
         */

        $handle = $this->plugin()->getSlug() . '_graphael-charts-line.js';
        $src = $this->plugin()->getUrl() . '/js/g.raphael-charts/g.line-min.js';
        //   wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );

    }

    /**
     * Enqueue Chart Scripts
     *
     * Enqueues Scripts Needed for Charts
     *
     * @param none
     * @return void
     */
    public function _enqueuChartPageScripts() {
        /*
         * Load jqplot Charting Library
         * 
         * standard jqPlot library
         */

        wp_enqueue_script(
                'jqPlot', //$handle, 
                $this->plugin()->getURL() . '/js/jqplot-charts/jquery.jqplot.min.js', //url to js, 
                array(), //$deps, 
                '', //$ver, 
                false//$in_footer 
        );
        /*
         * Load jqplot Date Axis Render Plugin
         * 
         * Not needed for category axes, but may need in future
         */
//        wp_enqueue_script(
//                'jqplot.dateAxisRenderer', //$handle, 
//                $this->plugin()->getURL() . '/js/jqplot-charts/plugins/jqplot.dateAxisRenderer.min.js', //url to js 
//                array(), //$deps, 
//                '', //$ver, 
//                false//$in_footer 
//        );
        /*
         * Load jqplot Bar Renderer Plugin
         * 
         * without this, bar charts will get
         * rendered as line charts
         * 
         */
        wp_enqueue_script(
                'jqplot.barRenderer.min.js', //$handle, 
                $this->plugin()->getURL() . '/js/jqplot-charts/plugins/jqplot.barRenderer.min.js', //url to js 
                array(), //$deps, 
                '', //$ver, 
                false//$in_footer 
        );
        /*
         * Load jqplot Highlighter
         * 
         * Not sure why this is needed...? think it was leftover
         * from an example i was following
         */
//        wp_enqueue_script(
//                'jqplot.highlighter.min.js', //$handle, 
//                $this->plugin()->getURL() . '/js/jqplot-charts/plugins/jqplot.highlighter.min.js', //url to js 
//                array(), //$deps, 
//                '', //$ver, 
//                false//$in_footer 
//        );
        /*
         * jqPlot Category Axis Renderer
         */
        wp_enqueue_script(
                'jqplot.categoryAxisRenderer.min.js', //$handle, 
                $this->plugin()->getURL() . '/js/jqplot-charts/plugins/jqplot.categoryAxisRenderer.min.js', //url to js 
                array(), //$deps, 
                '', //$ver, 
                false//$in_footer 
        );

        /*
         * jqPlot jqplot.pointLabels.min.js
         * 
         * Needed to provide value labels on top of bars and lines
         * 
         * 
         */
        wp_enqueue_script(
                'jqplot.pointLabels.min.js', //$handle, 
                $this->plugin()->getURL() . '/js/jqplot-charts/plugins/jqplot.pointLabels.min.js', //url to js 
                array(), //$deps, 
                '', //$ver, 
                false//$in_footer 
        );
        /*
         * jqPlot jqplot.cursor.min.js
         */
//        wp_enqueue_script(
//                'jqplot.cursor.min.js', //$handle, 
//                $this->plugin()->getURL() . '/js/jqplot-charts/plugins/jqplot.cursor.min.js', //url to js 
//                array(), //$deps, 
//                '', //$ver, 
//                false//$in_footer 
//        );
        /*
         * jqPlot jqplot.canvasTextRenderer.min.js
         */
        wp_enqueue_script(
                'jqplot.canvasTextRenderer.min.js', //$handle, 
                $this->plugin()->getURL() . '/js/jqplot-charts/plugins/jqplot.canvasTextRenderer.min.js', //url to js 
                array(), //$deps, 
                '', //$ver, 
                false//$in_footer 
        );
        /*
         * jqPlot jqplot.canvasAxisTickRenderer.min.js
         * 
         * Needed to provide axis labels
         */
        wp_enqueue_script(
                'jqplot.canvasAxisTickRenderer.min.js', //$handle, 
                $this->plugin()->getURL() . '/js/jqplot-charts/plugins/jqplot.canvasAxisTickRenderer.min.js', //url to js 
                array(), //$deps, 
                '', //$ver, 
                false//$in_footer 
        );

        /*
         * Load jqPlot CSS
         */
        wp_enqueue_style(
                'jqplot', //handle
                $this->plugin()->getURL() . '/js/jqplot-charts/jquery.jqplot.css', //url t css
                array(), //deps
                $this->plugin()->getVersion() //version
        );


        /*
         * Load Chart Javascript that Loads Data and Populates Page
         * 
         */

        $this->plugin()->enqueueInlineScript(
                $this->plugin()->getSlug() . '_stats-charts', //$handle, 
                $this->plugin()->getDirectory() . '/js/stats-charts.js', //absolute file path, 
                //   $this->plugin()->getDirectory() . '/js/stats-charts-stackoverflow-soln.js', //absolute file path,
                array(), //$inline_deps, 
                array( 'jquery' ) //$external_deps, 
        );

    }

    /**
     * Deprecated - Enqueue Home Page Scripts
     * Deprecated when I switched to using waypoints.js instead of lazy load
     * 
     * Checks that requsted page is home page before loading scripts
     *
     * @param none
     * @return void
     */
    private function _enqueueHomePageScripts() {

        /*
         * Lazy Load Jquery Plugin - Required for Viewport Impression Metric
         * 
         */
        wp_enqueue_script(
                'jquery.lazyload', //$handle, 
                $this->plugin()->getURL() . '/js/lazyload-jquery-plugin/jquery.lazyload.min.js', //url to js 
                array( 'jquery' ), //$deps, 
                '', //$ver, 
                false//$in_footer 
        );
        /*
         * lazy load Config
         * 
         */
        $this->plugin()->enqueueInlineScript(
                $this->plugin()->getSlug() . '_lazy-load-config', //$handle, 
                $this->plugin()->getDirectory() . '/js/lazy-load-config.js', //absolute file path, 
                //   $this->plugin()->getDirectory() . '/js/stats-charts-stackoverflow-soln.js', //absolute file path,
                array(), //$inline_deps, 
                array( 'jquery' ) //$external_deps, 
        );

    }

    /**
     * Adds javascript and stylesheets
     * WordPress Hook - hookEnqueueScripts
     *
     * @param none
     * @return void
     */
    public function hookEnqueueScripts() {
        $this->debug()->t();



        /*
         * 
         * Load Chart Page Scripts
         * but only if requesting a page with '/stats/' in the url
         */
        if ( !is_null( $this->plugin()->tools()->getQueryVar( 'simpli_frames_action' ) ) && (stripos( $_SERVER[ 'REQUEST_URI' ], '/stats/' ) !== false)
        ) {
            $this->debug()->log( 'Loading Chart Page Scripts' );
            $this->_enqueuChartPageScripts();
            return;
}

        /*
         * Load Home Page Scripts
         */
        if ( $_SERVER[ 'REQUEST_URI' ] === '/' ) {

            $this->debug()->log( 'Loading Home Page Scripts' );

            $this->_enqueueHomePageScripts();

}



    }

    /**
     * Count Domain Sales Page Click
     *
     * Increases the counter of the domain sales page
     *
     * @param none
     * @return void
     */
    public function countDomainSalesPageClick() {
 


        /*
         * Check referer so we Don't count this if not originating from front page.
         */
        if ( basename(basename($_SERVER['HTTP_HOST']))!==basename($_SERVER['HTTP_REFERER']) ) {
          
            return;
}



        /*
         * Get the session id
         */

        $session_id = session_id(); //get_session_id() will check if cookie is present, if not it will set it, then return the value.

        $this->debug()->logVar( '$session_id = ', $session_id );
        /*
         * get the domain name from the url
         */

        $domain_name = $this->plugin()->tools()->getQueryVar( 'domain_name' );


        /*
         * Count the Click by calling nstock_stats_count_domain_name_click
         * 
         */


        $query = "nstock_stats_count_domain_name_click('$session_id','$domain_name')";
        $this->debug()->logVar( '$query = ', $query );
        
          
        $this->plugin()->getModule( 'Tools' )->mySQLCallProc( $query );

        
        
        return;

        }

    /**
     * Count Viewport Impression
     *
     * Increases the count of a viewport impression for new sessions
     *
     * @param none
     * @return void
     */
    public function countViewportImpression() {
        $domain_name = $this->plugin()->tools()->getQueryVar( 'domain_name' );

        $session_id = session_id();


        $query = "nstock_stats_count_domain_name_impress(" . "'" . $session_id . "'" . "," . "'" . $domain_name . "'" . ")";
        $this->debug()->logVar( '$query = ', $query );
        $this->plugin()->getModule( 'Tools' )->mySQLCallProc( $query );

        /*
         * Now echo out an image
         * the ob_end
         */
        ob_end_clean(); //must do this to clear out any output before it or you'll get an error
        header( "Content-type:image/jpeg" );
//echo $this->plugin()->getUrl().'/images/1x1.jpg';
        echo readfile( $this->plugin()->getUrl() . '/images/1x1.jpg' );
        // echo readfile($this->plugin()->getUrl().'/images/dummy-100x56-Map.jpg');

        exit();

    }

    /**
     * Initialize a session
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    function init_session()
{
        if ( !session_id() ) {
            session_start();
}
}

 

    /**
     * Show Stats Page
     *
     * Shows the Stats Page for the domain
     *
     * @param none
     * @return void
     */
    public function showStatsPage() {

        /*
         * Initialize Template Tags
         */
        $tags = array();

        /*
         * Get the Domain Name
         */
 $domain_name = $this->plugin()->tools()->getQueryVar( 'domain_name' );
        $tags[ 'domain_name' ] =  $domain_name;

        /*
         * Configure the Flot Charts
         */
        //DEPRECATED  $this->configFlotCharts( $tags[ 'domain_name' ] );

        /*
         * Add the jqPlot Charts
         * 
         */
        $this->addjqPlotCharts( $tags[ 'domain_name' ] );



        /*
         * Get the stats summary tags
         * 
         */

        /*
         * First check if we have a request for a demo, and if so,
         * use values that are hardcoded and were custom developed so
         * that they match the fake chart data.
         */


        if ( $domain_name === 'example.com' ) {


            $tags[ 'impressions' ] = number_format(23302);
            $tags[ 'clicks' ] = number_format (1581);
            $tags[ 'ctr' ] = sprintf( "%.0f%%", 0.067848 * 100 );

   
} else{


            $proc = "nstock_stats_get_report('stats-summary',NULL,'" . $tags[ 'domain_name' ] . "',NULL)";
            $this->debug()->logVar( '$proc = ', $proc );


            $results = $this->plugin()->getModule( 'Tools' )->mySQLCallProc( $proc );
   
            $this->debug()->logVar( 'stats summary results from database = ', $results );
            /*
             * Clicks Tag
             */
            $tags[ 'clicks' ] = number_format (intval( $results[ 0 ][ 'total_clicks' ] ));

      
            /*
             * IMpressions Tag
             */
            $tags[ 'impressions' ] = number_format (intval( $results[ 0 ][ 'total_impressions' ] ));

            /*
             * Click through rate - but check for division by zero first
             */
            if ( intval( $results[ 0 ][ 'total_impressions' ] ) !== 0 ) {
                $ctr = (intval( $results[ 0 ][ 'total_clicks' ] )) / intval( $results[ 0 ][ 'total_impressions' ] );
} else{

                $ctr = 0;

}



            $tags[ 'ctr' ] = sprintf( "%.0f%%", $ctr * 100 );

    }

        $templates = $this->plugin()->getModule( 'Templates' );
        /*
         * get the template as a string
         */
        $template = $templates->getTemplate( 'stats' );

        /*
         * Populate the template and display
         */


        $html.=$this->plugin()->tools()->crunchTpl( $tags, $template );
        echo $html;

        exit();

    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function Z_DEPRECATED_getChartData( $proc ) {

        //ref http://php.net/pdo_mysql#69592  //i got empty result set but pointed me in the right direction
        //http://php.net/pdo_mysql#80306 //this worked, but returned 0-indexed and assoc, i edited it so it only returns assoc mimicking $wpdb->get_results(
        //http://www.php.net/manual/en/pdo.connections.php
        //http://www.php.net/manual/en/pdostatement.fetch.php explains about FETCH_ASSOC
        $domain_name = "mydomain.com";

        $chartData = $this->plugin()->getModule( 'Tools' )->mySQLCallProc( $proc );
        $this->debug()->logVar( '$chartData = ', $chartData );


        return ($chartData);


    }

    /**
     * Hook Print Styles
     *
     * Add Stylesheets By Printing to the header. Sometimes you need
     * to do this ( for example, conditionally loading css)
     *
     * @param none
     * @return void
     */
    public function hookPrintStyles() {

        /*
         * jqPlot Excanvas stylesheet as per http://www.jqplot.com/docs/files/usage-txt.html 
         * Conditionally Load 
         */
        echo '<!--[if lt IE 9]><link href="' . $this->plugin()->getURL() . '/js/jqplot-charts/excanvas.css' . '" rel="stylesheet" type="text/css"><![endif]-->';
}

    /**
     * Array Walk Convert Date
     *
     * Helper function to convert dates within an array to different formats
     *
     * @param none
     * @return void
     */
    public function convertDates( $dates, $format ) {

        try {

            foreach ( $dates as $date ) {
                $old_date_time_stamp = strtotime( $date );
                $new_date = date( $format, $old_date_time_stamp );
                $new_dates[] = $new_date;
}




} catch ( Exception $exc ) {
            echo $exc->getTraceAsString();
}




        return($new_dates);
    }

    /**
     * Convert Hour To Label
     *
     * Converts a plain text integer, such as 8, to an hour label, '08:00-09:00'
     * This is used by the jqPlot chart function to take the query result of the 'hour'
     * for when the click or impression was recorded and turn it into something that can be charted.
     * 
     * 
     * @param string $hour , e.g.: '8'
     * @return string A formated string representing the hour long span of time , '08:00-09:00'
     */
    public function convertHourToLabel( $hour ) {

        if ( $i < 10 ) {
            $format = '%02d';
} else{
            $format = '%d';
}
        if ( $i + 1 < 10 ) {
            $formatt = '%02d';
} else{
            $formatt = '%d';
}

        $result = sprintf( "$format:00-$formatt:00", $hour, $hour + 1 );
        return $result;

    }

    /**
     * Get jqPlot History Click Data
     *
     * Retrieves the data from our database and massages it into a form that jqPlot can consume
     *
     * @param $impressions The impressions array returned from getjqImpressionData() . The dates from the impressions will be used to make sure we are scrubbing the click data to only include valid dates with impressions
     * @return void
     */
    function getjqPlotHistoryClickData( $impressions, $domain_name )
{
        $this->debug()->t();
        /*
         * Get Clicks
         */

        $clicksQueryResults = $this->plugin()->getModule( 'Tools' )->mySQLCallProc(
                "nstock_stats_get_report('by-day','clicks','" . $domain_name . "',Null)"// $stored_procedure 
        );


        $this->debug()->logVar( '$clicksQueryResults = ', $clicksQueryResults );
        /*
         * remap so instead of this :

          [0]=> Array
          (
          [date] => 2014-02-09
          [count] => 10
          )
          [1]=> Array
          (
          [date] => 2014-02-10
          [count] => 5
          )


          we get this :

          Array
          (
          [2014-02-09] => 10
          [2014-02-10] => 5
          [2014-02-11] => 2
          [2014-02-13] => 8
          )
         */

        $clicks = $this->plugin()->getModule( 'Tools' )->remapAssocArray(
                $clicksQueryResults, //the array to remap
                'date', //$key1 The key whose value will become the key of the converted array. In the example, this would be 'date'
                'unique_count' //$key2 The key whose value will become the value of the remapped array. In the example, this would be 'unique_clicks'
        );

        /*
         * make sure we have an array before we do any array operations
         */
        $clicks = (is_array( $clicks )) ? $clicks : array();



        $this->debug()->logVar( '$impressions after remapping = ', $impressions );
        $this->debug()->logVar( '$clicks after remapping = ', $clicks );
        /*
         * Get Dates
         */






        /*
         * 
         * Clean Up Click Data
         * 
         * Remove any Click data that doesn't have
         * impression data for the same date
         * we do this by allowing only dates that also appear
         * in the Impressions Array
         * ref:http://stackoverflow.com/a/4260168/3306354
         */
        $clicks_without_bad_dates = array_intersect_key(
                $clicks, // array with keys to validate
                $impressions  //the array with the keys you want to keep
        );


        $this->debug()->logVar( '$clicks_without_bad_dates  = ', $clicks_without_bad_dates );
        /*
         * now that you've filtered out the dates that aren't contained in
         * the impressions array, you still have to add all the keys from the impressions array, and
         * fill in with 0s any keys that dont exisst in the clicks array.
         * To do this, create a framework array that is essentially the impressions array but with zeros for values
         * todo:
         */

        $clicks_framework = array_fill_keys( array_keys( $impressions ), 0 ); //creates an array using the keys for the $impressions array and 0 for values

        $this->debug()->logVar( '$clicks_framework = ', $clicks_framework );

        $clicks = array_merge( $clicks_framework, $clicks_without_bad_dates ); //now overwrite the zero values with clicks if we've got them.

        $this->debug()->logVar( 'final $clicks, using dates from impressions, with 0s for dates not in original clicks array = ', $clicks );

        $this->debug()->logVar( '$impressions = ', $impressions );
        return $clicks;

}

    /**
     * Get jqPlot Todays Click Data
     *
     * Retrieves the data from our database and massages it into a form that jqPlot can consume
     *
     * @param $impressions The impressions array returned from getjqImpressionData() . The dates from the impressions will be used to make sure we are scrubbing the click data to only include valid dates with impressions
     * @return void
     */
    function getjqPlotTodaysClickData( $impressions, $domain_name )
{

        /*
         * Clicks
         */

        $clicksQueryResults = $this->plugin()->getModule( 'Tools' )->mySQLCallProc(
                "nstock_stats_get_report('by-hour-for-today','clicks','" . $domain_name . "',Null)"// $stored_procedure 
        );

        /*
         * remap $clicksQueryResults to get $clicks
         */
        $this->debug()->logVar( '$clicks before remapping ', $clicksQueryResults );
        $clicks = $this->plugin()->getModule( 'Tools' )->remapAssocArray(
                $clicksQueryResults, //the array to remap
                'hour', //$key1 The key whose value will become the key of the converted array. In the example, this would be 'date'
                'unique_count' //$key2 The key whose value will become the value of the remapped array. In the example, this would be 'unique_clicks'
        );
        /*
         * make sure we have an array before we do any array operations
         */
        $clicks = (is_array( $clicks )) ? $clicks : array();


        $this->debug()->logVar( '$clicks after remapping ', $clicks );

        /*
         * Change hourly keys to proper labels.
         * 
         * so instead of a key like this '9' , 
         * we need keys like this '0900-1000' 
         * in the former, we are saying the series data is for hour 9
         * so we want the key to be the actual label we'll use in the chart,
         * which is 0900-1000
         */
        $clicks = $this->reformatJqPlotHourlyKeys( $clicks );


        $this->debug()->logVar( '$clicks after reformatting keys as hourly labels = ', $clicks );







        /*
         * 
         * Clean Up Click Data
         * 
         */
        $clicks = array_intersect_key( $clicks, ($impressions ) );
        $this->debug()->logVar( '$clicks = ', $clicks );







        $this->debug()->logVar( '$clicks = ', $clicks );
        /*
         * Build Hour Tick Labels
         */



        // $ticks = array_keys( $impressions );
        $series_framework = $this->getjqPlotHourlySeriesFramework();
        // $ticks = $this->convertDates( $ticks, "'G:ia'" );

        /*
         * Create an array of all zeros with ticks as keys
         */

        $this->debug()->logVar( '$series_framework = ', $series_framework );
        /*
         * Merge this array with your current clicks
         */

        $clicks = array_merge( $series_framework, $clicks );
        return $clicks;
}

    /**
     * Get jqPlot Hourly Series Framework
     *
     * Returns an array full of 0s for values and keys that are hourly lables, covering a  full 24 hours worth of tick labels, starting from 0000 to 2400, in the form '0000-0100' each.
     *
     * @param none
     * @return array Returns an array with keys as labels, and 0s for values e.g.: array('0000-0100=>0,'0100-0200'=>0,etc)
     */
    public function getjqPlotHourlySeriesFramework()
       {

        static $_hourlySeriesFramework = null;

        if ( !is_null( $_hourlySeriesFramework ) ) {
            return $_hourlySeriesFramework;
}
        $ticks = array();
        for ( $i = 0; $i <= 23; $i++ ) {
            $label = $this->convertHourToLabel( $i );

            $series_framework[ $label ] = 0;
}
        return $series_framework;
    }

    /**
     * Reformat jqPlot Hourly Keys
     *
     * Takes an array like this (8=>24,9=>25) and changes it to : ('0800-0900'=>24,etc)

     *
     * @param none
     * @return void
     */
    public function reformatJqPlotHourlyKeys( $series )
{
        $new_series = array();
        /*
         * Reformat Hour Keys
         * fix the keys so instead of the hour as an integer
         * we get a label like '0800-0900'
         */
        foreach ( $series as $key => $value ) {
            $key2 = $this->convertHourToLabel( $key );
            $new_series[ $key2 ] = $value;

}
        $series = $new_series;
        return $series;

}
}

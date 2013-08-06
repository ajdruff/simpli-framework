<?php


/**
 * Admin Module
 *
 * This module creates the admin panel
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_AAtest extends Simpli_Basev1c0_Plugin_Module {

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {

    }

    /**
     * Adds javascript and stylesheets to admin panel
     * WordPress Hook - admin_enqueue_scripts
     *
     * @param none
     * @return void
     */
    public function admin_enqueue_scripts() {

    }


    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {



        $this->createClusters();
    }
    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function createClusters() {



           $gv = new Image_GraphViz();



        $example = ' digraph G {
        subgraph cluster0 {
            node [style = filled, color = white];
            style = filled;
            color = lightgrey;
          //  a0->a1->a2->a3;
          METHOD_1->METHOD_2;
          METHOD_1->METHOD_5
            label = "MY_CLASS";
        }
        subgraph cluster1 {
            node [style = filled, color = white];
          //  b0->b1->b2->b3;
          b0->b1;
          b2->b0;
          b1->b2;
             METHOD_4->METHOD_5
            label = "MY_CLASS2";
            color = blue;
        }
        start->a0;
        start->b0;
        a1->b3;
        b2->a3;
        a3->a0;
        a3->end;
        b3->end;
        start [shape = Mdiamond];
        end [shape = Msquare];
    }';




    //    $gv->load($tmpFilename);

    //    $file = file_get_contents($path);
//render
       // echo $gv->image('jpg');
//require_once 'Image/GraphViz.php';
        error_reporting(E_ALL ^ E_NOTICE);
        $gv = new Image_GraphViz();


        /*
         * create a temporary file
         */
        $tempHandle = tmpfile();
        /*
         * write the string to it
         */
        fwrite($tempHandle, $example);
        /*
         * return its path
         */
        $metaDatas = stream_get_meta_data($tempHandle);
        $tmpFilename = $metaDatas['uri'];

        $gv->load($tmpFilename);


//        $gv->addEdge(array('wake up' => 'visit bathroom'));
//        $gv->addEdge(array('visit bathroom' => 'make coffee'));
      //  echo $gv->image('jpg');



        /*
         * create a temporary file
         */
        $tempFileOutHandle = tmpfile();
        /*
         * write the string to it
         */
        //  fwrite($tempFileOutHandle, $example);
        /*
         * return its path
         */
        $metaDatas = stream_get_meta_data($tempFileOutHandle);
        $tmpFileOutName = $metaDatas['uri'];


        $gv->renderDotFile($tmpFilename, $tmpFileOutName, 'svg');

        $img = file_get_contents($tmpFileOutName);


        echo $img;

        /*
          digraph G {
          subgraph cluster0 {
          node [style = filled, color = white];
          style = filled;
          color = lightgrey;
          a0->a1->a2->a3;
          label = "process #1";
          }
          subgraph cluster1 {
          node [style = filled];
          b0->b1->b2->b3;
          label = "process #2";
          color = blue
          }
          start->a0;
          start->b0;
          a1->b3;
          b2->a3;
          a3->a0;
          a3->end;
          b3->end;
          start [shape = Mdiamond];
          end [shape = Msquare];
          }
         *
         */
    }

}
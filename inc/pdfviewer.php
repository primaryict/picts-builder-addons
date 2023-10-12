<?php
/*
Addon Name:  PICTS Custom PDF Viewer Loader
Description:  Dynamic sub menu on side bar based on the page structure
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Load plugin's stylesheet on frontend
 */
function pdfviewer_tb_enqueue()
{
    wp_enqueue_style('picts-pdfviewer-tb-addon-css', plugin_dir_url(__FILE__) . '/assets/css/pdfviewer.css');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/solid.min.css');

    wp_register_script('pdf-js', 'https://unpkg.com/pdfjs-dist@2.0.489/build/pdf.min.js', array(), false, true);
    wp_register_script('picts-pdfviewer-tb-addon-js', plugin_dir_url(__FILE__) . '/assets/js/pdfviewer.js', array(), false, true);
}
add_action('wp_enqueue_scripts', 'pdfviewer_tb_enqueue');

function shortcode_picts_pdfviewer_function($atts, $content, $tag)
{

    wp_enqueue_script('pdf-js-picts');
    wp_enqueue_script('picts-pdfviewer-tb-addon-js');

    $result = "";

    $a = shortcode_atts(array(
        'pdf' => null,
    ), $atts);



    $result = '<div id="picts-pdf">
              <div role="toolbar" id="toolbar" class="toolbar">
                  <div class="left">
                      <div id="pager">
                          <span data-pager="prev"><i data-pager="prev" class="fa-solid fa-circle-arrow-left"></i></span>
                          <span data-pager="next"><i data-pager="next" class="fa-solid fa-circle-arrow-right"></i></span>
                      </div>
                      <div id="page">
                          <span id="currentPage"></span> / <span id="totalPages"></span>
                      </div>
                  </div>
                  <div class="right">
                      <div id="zoom">
                          <span id="zoomlevel">100%</span>
                          <span data-pager="zoomout"><i data-pager="zoomout" class="fa-solid fa-magnifying-glass-minus"></i></span>
                          <span data-pager="zoomin"><i data-pager="zoomin" class="fa-solid fa-magnifying-glass-plus"></i></span>
                      </div>
                      <span id="download"><a href="' . $a['pdf'] . '" download><i class="fa-solid fa-download"></i></a></span>
                  </div>
              </div>
              <div id="viewport-container"><div role="main" id="viewport"></div></div>
            </div>
            
            <script>
              setTimeout(() => {
                  initPDFViewer("' . $a['pdf'] . '");
                }, "1000")
            </script>';


    return $result;
}

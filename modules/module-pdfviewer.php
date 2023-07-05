<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class PICTS_Pdfviewer_Module extends Themify_Builder_Component_Module
{

    function __construct()
    {
        parent::__construct(array(
            'name' => __('Add PDF', 'picts'),
            'slug' => 'pdfviewer',
            'category' => array('addon')
        ));
    }

    public function get_icon()
    {
        return 'file';
    }

    /**
     * Module options.
     *
     * @return array
     */
    public function get_options()
    {
        return array(
            array(
                'id' => 'pdf_file',
                'type' => 'file',
                'label' => __('Upload PDF', 'picts')
            ),
            array('type' => 'custom_css_id')
        );
    }

    public function get_styling()
    {
        return array(
            //background
            self::get_expand('bg', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_color('', 'background_color', 'bg_c', 'background-color'),
                        )
                    ),
                    'h' => array(
                        'options' => array(
                            self::get_color('', 'bg_c', 'bg_c', 'background-color', 'h')
                        )
                    )
                ))
            )),
            self::get_expand('f', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_font_family(),
                            self::get_color('', 'font_color'),
                            self::get_font_size(),
                            self::get_line_height(),
                            self::get_text_align()
                        )
                    ),
                    'h' => array(
                        'options' => array(
                            self::get_font_family('', 'f_f', 'h'),
                            self::get_color('', 'f_c', null, null, 'h'),
                            self::get_font_size('', 'f_s', '', 'h'),
                            self::get_line_height('', 'l_h', 'h'),
                            self::get_text_align('', 't_a', 'h')
                        )
                    )
                ))
            )),
            // Padding
            self::get_expand('p', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_padding()
                        )
                    ),
                    'h' => array(
                        'options' => array(
                            self::get_padding('', 'p', 'h')
                        )
                    )
                ))
            )),
            // Margin
            self::get_expand('m', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_margin()
                        )
                    ),
                    'h' => array(
                        'options' => array(
                            self::get_margin('', 'm', 'h')
                        )
                    )
                ))
            )),
            // Border
            self::get_expand('b', array(
                self::get_tab(array(
                    'n' => array(
                        'options' => array(
                            self::get_border()
                        )
                    ),
                    'h' => array(
                        'options' => array(
                            self::get_border('', 'b', 'h')
                        )
                    )
                ))
            ))
        );
    }
    protected function _visual_template()
    {
        $module_args = self::get_module_args('mod_title_icon');
?>
        <div class="module module-<?php echo $this->slug; ?>">
            <# if( data.pdf_file ) { #>
                <div id="picts-pdf">
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
                    <div id="viewport-container">
                        <div role="main" id="viewport">
                            <h2>Preview Not Available</h2>
                        </div>
                    </div>
                </div>

                <# initPDFViewer(data.pdf_file); } else { #>

                    <p>No File selected</p>

                    <# } #>
        </div>
<?php

    }
}
Themify_Builder_Model::register_module('PICTS_Pdfviewer_Module');

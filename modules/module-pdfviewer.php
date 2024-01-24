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

                <object data="{{{data.pdf_file}}}" type="application/pdf" style="width: 100%; aspect-ratio: 1/1.4">
                    <iframe src="https://docs.google.com/viewer?url={{{data.pdf_file}}}&embedded=true" style="width: 100%; aspect-ratio: 1/1.4"></iframe>
                </object>

                <# } else { #>

                    <p>No File selected</p>

                    <# } #>
        </div>
<?php

    }
}
Themify_Builder_Model::register_module('PICTS_Pdfviewer_Module');

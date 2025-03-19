<?php
class Elementor_Product_Grid_Widget extends \Elementor\Widget_Base
{


    public function get_name()
    {
        return 'product_grid_widget';
    }

    public function get_title()
    {
        return esc_html__('Product Grid', 'elementor-addon');
    }

    public function get_style_depends()
    {
        return ['productGrid'];
    }

    public function get_script_depends()
    {
        return ['productGrid'];
    }

    public function get_icon()
    {
        return 'eicon-navigation-horizontal';
    }

    public function get_categories()
    {
        return ['studioslab'];
    }

    protected function register_controls()
    {

        // Content Tab Start

        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__('Product Grid', 'elementor-addon'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->end_controls_section();

        // Content Tab End


        // Style Tab Start

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Product Grid', 'elementor-addon'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]

        );

        $this->end_controls_section();

        // Style Tab End

    }

    protected function render()
    {
        // $types = get_terms( array( 
        //     'taxonomy' => 'sorte',
        //     'parent'   => 0
        // ) );
        ?>

        <div class="content">
            <div id="filtres">
                <?php
                // Define the taxonomy name
                $taxonomy = 'sorte';
                
                // Get terms in hierarchical order
                $terms = get_terms(array(
                    'taxonomy'   => $taxonomy,
                    'orderby'    => 'name',        // Order by term name
                    'order'      => 'ASC',         // Ascending order
                    'hide_empty' => false,         // Include terms even if they don't have posts
                ));
                
                // Check if terms exist
                if (!is_wp_error($terms) && !empty($terms)) {
                    // Output the terms
                    esa_display_terms_hierarchically($terms);
                } else {
                    echo 'No terms found or an error occurred.';
                }
                
                ?>
            </div>
            <div class="view-column">
                <button id="view-five">
                    <svg width="18" height="18" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <rect x=1 y=1 width="6" height="6" stroke="black" fill="transparent" stroke-width="1" />
                        <rect x=1 y=11 width="6" height="6" stroke="black" fill="transparent" stroke-width="1" />
                        <rect x=11 y=1 width="6" height="6" stroke="black" fill="transparent" stroke-width="1" />
                        <rect x=11 y=11 width="6" height="6" stroke="black" fill="transparent" stroke-width="1" />
                    </svg>
                </button>
                <button id="view-four" class="view-active">
                    <svg width="18" height="18" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <rect x=1 y=1 width="6" height="16" stroke="black" fill="transparent" stroke-width="1" />
                        <rect x=10 y=1 width="6" height="16" stroke="black" fill="transparent" stroke-width="1" />
                    </svg>
                </button>
                <button id="view-three">
                    <svg width="18" height="18" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <rect x=1 y=1 width="16" height="16" stroke="black" fill="transparent" stroke-width="1" />
                    </svg>
                </button>
            </div>
        </div>

        <?php
    }
}
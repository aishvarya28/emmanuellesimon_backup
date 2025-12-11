<?php
add_action( 'wp_ajax_project_list', 'project_list' );
add_action( 'wp_ajax_nopriv_project_list', 'project_list' );

function project_list(){

    $argumentsFamille = array(
        'taxonomy'   => 'famille',
        'orderby' => 'description',
        'order' => 'ASC',
        'hide_empty' => true, // Mettez à true pour masquer les catégories sans produits
    );
    
    $familles = get_terms($argumentsFamille);

            
    $htmlLevelOne = "";
    $htmlLevelOne .= '<ul class="list uppercase"> <li><a href="';
    $htmlLevelOne .= get_post_type_archive_link('architecture');
    $htmlLevelOne .= '">Tout</a>';

    foreach($familles as $famille){
        $htmlLevelOne .= '<li><button class="header-list '; 
        $htmlLevelOne .= ($_POST['id'] == $famille->slug) ? 'active' : '';
        $htmlLevelOne .= '" onmousedown="clickProject(this)" onmouseover="clickProject(this)" data-level="one" data-id="' ;
        $htmlLevelOne .= $famille->slug; 
        $htmlLevelOne .= '" data-action="project_list" data-nonce="';
        $htmlLevelOne .= wp_create_nonce('project_list');
        $htmlLevelOne .= '" data-ajaxurl="';
        $htmlLevelOne .= admin_url( 'admin-ajax.php' );
        $htmlLevelOne .= '">';
        $htmlLevelOne .= $famille->name;
        $htmlLevelOne .= '</button></li>';
    }
    $htmlLevelOne .= '</ul>';


    $htmlLevelTwo = "";
    $htmlLevelTwo .= '<ul class="list">';


    $args = array(
        'post_type' => 'architecture',
        'post_status' => 'publish',
        'meta_key' => 'ordre_parution',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'posts_per_page' => -1,  // Pour obtenir tous les posts, sinon mettez un nombre spécifique
        'tax_query' => array(
            array(
                'taxonomy' => 'famille',
                'field'    => 'slug',  
                'terms'    => $_POST['id'],
            ),
        ),
    );
    
    $architecture_posts = get_posts($args);
            
    foreach ($architecture_posts as $post) {
        $permalink = get_permalink($post->ID);
        $htmlLevelTwo .= '<li><a href="';
        $htmlLevelTwo .= $permalink;
        $htmlLevelTwo .=  '">';
        $htmlLevelTwo .=  get_the_title($post->ID);
        $htmlLevelTwo .= '</a></li>';
    }

    $htmlLevelTwo .= '</ul>';

    
    $html=array("levelOne" => $htmlLevelOne, "levelTwo" => $htmlLevelTwo);
    return wp_send_json_success( $html );

	
}

?>
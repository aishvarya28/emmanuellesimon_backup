<?php 
class Elementor_Product_Price_Tag extends \Elementor\Core\DynamicTags\Tag {

public function get_name() {
    return 'product-price';
}

public function get_title() {
    return __( 'Prix du Produit', 'elementor' );
}

public function get_group() {
    return 'woocommerce';
}

public function get_categories() {
    return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
}

public function render() {
    // Récupérer l'ID du produit depuis la page courante
    global $product;

    if ( ! is_a( $product, 'WC_Product' ) ) {
        $product_id = get_the_ID(); // Utiliser l'ID de l'article courant
        $product = wc_get_product( $product_id );
    }

    // Si le produit n'est toujours pas trouvé, sortir
    if ( ! $product ) {
        echo __( '' );
        return;
    }

    // Vérifier si le produit est variable
    if ( $product->is_type( 'variable' ) ) {
        // Récupérer les prix minimum et maximum des variations
        $min_price = $product->get_variation_price( 'min', true );
        $max_price = $product->get_variation_price( 'max', true );

        // Afficher les prix des variations
        if ( $min_price !== $max_price ) {
            echo wc_price( $min_price ) . ' - ' . wc_price( $max_price );
        } else {
            echo 'à partir de ', wc_price( $min_price );
        }
    } else {
        // Si ce n'est pas un produit variable, afficher le prix simple
        if ($product->get_price() == 0){
            echo 'sur devis';
        }else {
            echo 'à partir de ' , wc_price($product->get_price());
        }
        
    }
}
}

?>
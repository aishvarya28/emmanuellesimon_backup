<?php

function add_custom_query_var($vars) {
    $vars[] = 'sorte'; // Ajoute 'sorte' à la liste des variables de requête reconnues
    return $vars;
}
add_filter('query_vars', 'add_custom_query_var');

function get_categorie() {
    
    $queried_object = get_queried_object();
    if (isset($queried_object->taxonomy) && $queried_object->taxonomy === 'product_cat') {
        return $queried_object->slug;
    } else {
        return '';
    }
}

function esa_get_category_id() {
    
    $queried_object = get_queried_object();
    if (isset($queried_object->taxonomy) && $queried_object->taxonomy === 'product_cat') {
        return $queried_object->term_id;
    } else {
        return '';
    }
}

function get_sorte(){
    $query = get_query_var('sorte');
    return $query;
}

function get_product_category() {
    $slug = get_query_var('product_cat');
    
    if ($slug) {
        return $slug;
    } else {
        return '';
    }
}

function get_product_search() {
    $query = get_query_var('s');
    
    if ($query) {
        return $query;
    } else {
        return '';
    }
}


?>
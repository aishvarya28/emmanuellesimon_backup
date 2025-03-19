<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="search-term">Search</label>
    <input type="text" id="search-term" name="s" placeholder="Search here..." value="<?php echo get_search_query(); ?>" />
    
    <label for="search-type">Search In:</label>
    <select id="search-type" name="post_type">
        <option value="">All</option>
        <option value="product">Products</option>
        <option value="category">Categories</option>
        <option value="architecture">Architecture</option>
    </select>

    <button type="submit">Search</button>
</form>

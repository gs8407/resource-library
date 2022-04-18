<?php
get_header(); ?>
<section class="success-hero">
  <div class="sub-contents">
    <h2 class="section-headline">Resource Library</h2>
  </div>
</section>
<section class="success-archive">
  <div class="sub-contents" id="form-resource-library">
    <div class="filter-section">
      <form data-js-form="filter-resource-library">
        <div>
          <label for="">Search</label>
          <input type="text" id="search" name="search">
        </div>

        <div>
          <?php
          $args = array(
            'type'                     => 'resource-library',
            'orderby'                  => 'name',
            'order'                    => 'ASC',
            'hierarchical'             => 1,
            'taxonomy'                 => 'resource_library_topic',
          );
          $categories = get_categories($args);
          echo '<label>Choose Topic</label><select name="topic" id="topic"> <option value="" selected>All</option>';

          foreach ($categories as $category) {
            $url = get_term_link($category); ?>
           
            <option value="<?php echo $category->slug; ?>"><?php echo $category->name; ?></option>
          <?php
          }
          echo '</select>';
          ?>
        </div>

        <div>
          <?php
          $args = array(
            'type'                     => 'resource-library',
            'orderby'                  => 'name',
            'order'                    => 'ASC',
            'hierarchical'             => 1,
            'taxonomy'                 => 'resource_library_type',
          );
          $categories = get_categories($args);
          echo '<label>Choose Type</label><select name="type" id="type"><option value="" selected>All</option>';

          foreach ($categories as $category) {
            $url = get_term_link($category); ?>
            
            <option value="<?php echo $category->slug; ?>"><?php echo $category->name; ?></option>
          <?php
          }
          echo '</select>';
          ?>
        </div>
        <!-- <button class="gform_button button" type="submit">Filter</button> -->
      </form>
    </div>
    <div class="response-section-resource-library">
      <div id="response-content-resource-library">
        <div class="lds-roller">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
      </div>
      <div id="paginate-resource-library"></div>
    </div>
  </div>
</section>
<?php get_footer(); ?>
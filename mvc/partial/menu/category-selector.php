<?php
	$Application   = \Core\Application::getInstance();
    $assetsBaseImg = $Application->getConfigs()->get('Application.core.mvc.controller.assets.base.img');
	$categories    = \Core\Hybernate\Listings\Listing_Category::getCategoryTree();
?>
<div class="page-section mid-page-section">
    <div class="container">
      <div class="row">
        <div class="section-fullwidth">
          <div class="element-size-100">
            <div class="cs_directory_categories  col-md-12">
              <ul class="row categorySelection">
              <?php foreach ($categories as $categoryId => $categoryData) { ?>
                <li class="col-md-3 categoryOptions">
                  <div class="cat-inner">
                    <img src="<?php echo $assetsBaseImg; ?>/categories/<?php echo $categoryData['id']; ?>.png" 
                        alt="<?php echo($Application->translate($categoryData['name_en'], $categoryData['name_fr'], $categoryData['name_ch'])); ?>" />
                    <a href="#"><?php echo ucwords($Application->translate($categoryData['name_en'], $categoryData['name_fr'], $categoryData['name_ch'])); ?></a> 
                    <a href="#">(<?php echo($Application->translate('View All', 'Tout Voire', 'View All')); ?>)</a>
                  </div>
                  <?php if (empty($categoryData['children']) === false) { ?>
                  <ul>
                  	<?php $cnt 	 = 0; ?>
                  	<?php $clpId = mt_rand(); ?>
                    <?php foreach ($categoryData['children'] as $childCatId => $childCatData) { ?>
                    	<?php if ($cnt === 4 && count($categoryData['children']) >= 5) { ?>
                        	<section class="collapse" id="<?php echo $clpId; ?>">
						<?php } ?>
                    	<li><a href="#"><?php echo ucwords($Application->translate($childCatData['name_en'], $childCatData['name_fr'], $childCatData['name_ch'])); ?></a>
                        	<span><?php echo $childCatData['catCount']; ?></span></li>
                        <?php $cnt++; ?>
                    <?php } ?>
                    <?php if (count($categoryData['children']) >= 5) { ?>
                    	</section>
                      
                        <a class="cs-link-more" href="#<?php echo $clpId; ?>" data-target="#<?php echo $clpId; ?>" 
                        	data-collapseTxt="<i class='icon-arrow-down9'></i> More categories" data-openTxt="<i class='icon-arrow-up8'></i> Less categories">
                            	<i class="icon-arrow-down9"></i>more categories</a>
					<?php } ?>
                    
                  </ul>
                  <?php } ?>
                </li>
              <?php } ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
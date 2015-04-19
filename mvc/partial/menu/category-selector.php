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
                  <?php /*?><ul>
                  	<?php $cnt 	 = 0; ?>
                  	<?php $clpId = time(); ?>
                    <?php foreach ($categoryData['children'] as $childCatId => $childCatData) { ?>
                    	<?php if ($cnt === 4) { ?>
                        	<li class="collapse" id="<?php echo ($clpId); ?>"><ol>
						<?php } ?>
                    	<li><a href="#"><?php echo ucwords($Application->translate($childCatData['name_en'], $childCatData['name_fr'], $childCatData['name_ch'])); ?></a>
                        	<span><?php echo $childCatData['catCount']; ?></span></li>
                        <?php $cnt++; ?>
                    <?php } ?>
                    <?php if ($cnt >= 4) { ?>
                    	</ol>
                      </li>
                      
                        <a class="cs-link-more collapsed"
                        	data-toggle="collapse" href="#<?php echo $clpId; ?>" data-target="#<?php echo $clpId; ?>" 
                            	aria-expanded="false" aria-controls="<?php echo $clpId; ?>">
                            	<i class="icon-plus8"></i>more categories</a>
					<?php } ?>
                    
                  </ul><?php */?>
                  <ul>
                  	<?php $cnt 	 = 0; ?>
                  	<?php $clpId = mt_rand(); ?>
                    <?php foreach ($categoryData['children'] as $childCatId => $childCatData) { ?>
                    	<?php if ($cnt === 4 && count($categoryData['children']) > 5) { ?>
                        	<section class="collapse" id="<?php echo $clpId; ?>">
						<?php } ?>
                    	<li><a href="#"><?php echo ucwords($Application->translate($childCatData['name_en'], $childCatData['name_fr'], $childCatData['name_ch'])); ?></a>
                        	<span><?php echo $childCatData['catCount']; ?></span></li>
                        <?php $cnt++; ?>
                    <?php } ?>
                    <?php if (count($categoryData['children']) > 5) { ?>
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

<?php /*
<li class="col-md-3">
                  <div class="cat-inner"> <img src="http://directory.chimpgroup.com/wp-content/uploads/Pets1.png" alt="" /> <a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=pets&amp;submit=">Pets</a> <a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=pets&amp;submit=">(View All)</a> </div>
                  <ul>
                    <li><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=pets&amp;directory_categories=birds&amp;submit=">Birds</a><span>2</span></li>
                    <li><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=pets&amp;directory_categories=cats&amp;submit=">Cats</a><span>1</span></li>
                    <li><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=pets&amp;directory_categories=dogs&amp;submit=">Dogs</a><span>2</span></li>
                    <li><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=pets&amp;directory_categories=equipment&amp;submit=">Equipment</a><span>2</span></li>
                    <li><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=pets&amp;directory_categories=exotics&amp;submit=">Exotics</a><span>2</span></li>
                    <li><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=pets&amp;directory_categories=fish&amp;submit=">Fish</a><span>1</span></li>
                    <li><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=pets&amp;directory_categories=missing-found&amp;submit=">Missing &amp; Found</a><span>2</span></li>
                    <li class="collapse" id="1568">
                      <ol>
                        <li><a href="http://directory.chimpgroup.com/listing-plain-heading/?&amp;filter=all&amp;type=pets&amp;directory_categories=pets-for-sale&amp;submit=">Pets for Sale</a><span>1</span></li>
                      </ol>
                    </li>
                  </ul>
                  <a class="cs-link-more cs-link-more-1568 collapsed" onclick="cs_slide_toogle(this.getAttribute('class'),1568)" data-toggle="collapse" href="#1568" aria-expanded="false" aria-controls="1568"> <i class="icon-plus8"></i>more categories</a></li>*/ ?>
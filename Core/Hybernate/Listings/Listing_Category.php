<?php
namespace Core\Hybernate\Listings;

/**
 * Listing category used with Hybernate loader
 *
 * @package    Core
 * @subpackage Interfaces
 * @file       Core/Interfaces/Base/HybernateBaseInterface.php
 * @desc       Used interface for ORM implementations
 * @author     Avi Aialon
 * @license    BSD/GPLv2
 *
 * copyright (c) Avi Aialon (DeviantLogic)
 * This source file is subject to the BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt.
 */
class Listing_Category extends \Core\Interfaces\HybernateInterface
{
    /**
     * This method is called before delete of a category
     *
     * @return void
     */
    public final function onBeforeDelete()
    {
        if ($this->getId() > 0) {
            $linkedProductCategories = \Core\Hybernate\Listings\Listing_Category_Link::getMultiInstance(array(
                'categoryId' => (int) $this->getId()
            ));

            foreach ($linkedProductCategories as $linkedProductCategory) {
                $linkedProductCategory->delete();
            }
        }
    }
	
	/**
     * This method is called before save of a category
     *
     * @return void
     */
    public final function onBeforeSave()
    {
        if ($this->getName_En() !== false) {
			$this->setUrlKey(\Core\Net\Url::makeUrlFriendlytext($this->getName_En()));
        }
    }
	
	/**
     * This method returns the translated name
     *
     * @return void
     */
    public final function getName()
    {
        return \Core\Application::getInstance()->translate($this->getName_En(), $this->getName_Fr(), $this->getName_Ch());
    }
	
	/**
     * This method returns the translated description
     *
     * @return void
     */
    public final function getDescription()
    {
        return \Core\Application::getInstance()->translate($this->getDescription_En(), $this->getDescription_Fr(), $this->getDescription_Ch());
    }
	
	/**
     * This method returns all the parent categories
     *
     * @param  boolean $returnArray (optional) Return data as \Core\Hybernate\Listings\Listing_Category or array
     * @return array | \Core\Hybernate\Listings\Listing_Category
     */
	public static final function getParentCategories($returnArray = true)
    {
		return \Core\Hybernate\Listings\Listing_Category::getMultiInstance(array('parentId' => 0), 
			(bool) $returnArray, array('order_by' => 'a.orderIndex ASC, a.name_en ASC'));	
	}
	
	/**
     * This method returns all the children categories
     *
     * @param  boolean $returnArray (optional) Return data as \Core\Hybernate\Listings\Listing_Category or array
     * @return array | \Core\Hybernate\Listings\Listing_Category
     */
	public static final function getChildrenCategories($returnArray = true)
    {
		return \Core\Hybernate\Listings\Listing_Category::getClassView(array(
			'columns'	 => array('a.*', 'COUNT(b.listingId) as catCount'),
			'left_join' => array('listing_category_link b' => 'b.categoryId = a.id'),
			'ret_object' => (false === $returnArray), 
			'filter_inline_unescaped' => array(' a.parentId >' => 0),
			'order_by' 	 => array('a.name_en'),
			'groupBy' 	 => 'a.id',
			'direction'  => 'ASC'
		));	
	}
	
    /**
     * This method returns the complete category tree
     *
     * @param  boolean $returnArray (optional) Return data as \Core\Hybernate\Listings\Listing_Category or array
     * @return array | \Core\Hybernate\Listings\Listing_Category
     */
    public static final function getCategoryTree($returnArray = true)
    {
        $categoriesIds    = array();
        $sortedCategories = array();
        $categorieParents = \Core\Hybernate\Listings\Listing_Category::getParentCategories();
        $categories       = \Core\Hybernate\Listings\Listing_Category::getChildrenCategories();

        foreach ($categorieParents as $categoryParent) {
            $categoryId = (int)  (true === $returnArray ? $categoryParent['id'] : $categoryParent->getId());
            $sortedCategories[$categoryId] = $categoryParent;
            $sortedCategories[$categoryId]['children'] = array();
        }

        // Extract sub categories
        foreach ($categories as $category) {
            $parentId   = (int) (true === $returnArray ? $category['parentId'] : $category->getParentId());
            $categoryId = (int) (true === $returnArray ? $category['id'] : $category->getId());
            $sortedCategories[$parentId]['children'][$categoryId] = $category;
        }

        unset($categoriesIds);
        unset($categories);
        unset($categorieParents);

        return $sortedCategories;
    }

    /**
     * This method returns the categories associated to a product
     *
     * @param  \Core\Hybernate\Products\Product $product      The product
     * @param  boolean                          $returnArray (optional) Return data as \Core\Hybernate\Listings\Listing_Category or array
     * @return array | \Core\Hybernate\Listings\Listing_Category
     */
     public static final function getCategoriesListing(\Core\Hybernate\Listings\Listing $listing, $returnArray = true)
     {
         if (false === $returnArray) {
            $listing->_dataAccessInterface->setFetchType(\PDO::FETCH_CLASS, __CLASS__);
         }

         return ($listing->_dataAccessInterface->getAll('
            SELECT       p2.id as mainCatId,
                         p.id as subCatId,
                         p2.name_en as mainCat_en,
                         p2.name_fr as mainCat_fr,
                         p.name_en as subCat_en,
                         p.name_fr as subCat_fr
            FROM         listing_category_link AS pcl
			
            INNER JOIN   listing_category AS p
            ON           p.id = pcl.categoryId
            LEFT JOIN    listing_category_parent AS pca
            ON           pca.categoryId = p.id
            LEFT JOIN    listing_category AS p2
            ON           p2.id = pca.parentCategoryId
            WHERE        pcl.productId = :productId
            GROUP BY     p2.id, p.id
            ORDER BY     p2.name_en ASC, p.name_en ASC;
         ', array('productId' => (int) $product->getId())));
     }

    /**
     * This method returns the categories associated by a parent categoryId
     *
     * @param  integer $parentCategoryId The parent category id
     * @param  boolean $returnArray      (optional) Return data as \Core\Hybernate\Listings\Listing_Category or array
     * @return array | \Core\Hybernate\Listings\Listing_Category
     */
     public static final function getCategoriesByParent($parentCategoryId, $returnArray = true)
     {
         if (false === $returnArray) {
            $product->_dataAccessInterface->setFetchType(\PDO::FETCH_CLASS, __CLASS__);
         }

         return ($product->_dataAccessInterface->getAll('
            SELECT           a.*
            FROM             `listing_category` a
            INNER JOIN       `listing_category_parent` b
            ON               b.categoryId = a.id
            WHERE            b.parentCategoryId = :categoryId
            GROUP BY         b.categoryId
            ORDER BY         a.name_en ASC, a.name_fr ASC;
         ', array('productId' => (int) $parentCategoryId)));
     }

    /**
     * This method will group a product's categories gathered by self::getCategoriesByProduct
     *
     * @param  \Core\Hybernate\Products\Product $product The product
     * @return array
     */
     public static final function groupCategories(\Core\Hybernate\Products\Product $product)
     {
         $groupCategories = array();

         if (false === $product->getGroupedCategories()) {
             foreach ($product->getCategories() as $categoryArray) {
                 if (empty($groupCategories[$categoryArray['mainCatId']]) === true) {
                    $groupCategories[$categoryArray['mainCatId']] = array(
                        'id'         => $categoryArray['mainCatId'],
                        'name_en'    => $categoryArray['mainCat_en'],
                        'name_fr'    => $categoryArray['mainCat_fr'],
                        'children'    => array()
                     );
                 }

                 $groupCategories[$categoryArray['mainCatId']]['children'][$categoryArray['subCatId']] = array(
                    'id'         => $categoryArray['subCatId'],
                    'name_en'    => $categoryArray['subCat_en'],
                    'name_fr'    => $categoryArray['subCat_fr']
                 );
             }
         }

         return $groupCategories;
     }
}
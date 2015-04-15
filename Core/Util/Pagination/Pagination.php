<?php 
/**
 * PHP Pagination Class
 * 
 * @dependencies 	PDO
 * @author 			Avi Aialon <aviaialon@gmail.com>
 * @package			Core
 * @subpackage		Util
 * @category		Core Utilities
 * @version 		2.0.0
 * @copyright 		(c) 2010 Deviant Logic. All Rights Reserved
 * @license 		CC Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0) - http://creativecommons.org/licenses/by-sa/3.0/
 * @link			SVN: $HeadURL$
 * @since			12:35:53 PM
 * @example			See below
 * @throws			\Exception
 */

namespace Core\Util\Pagination;

/*
	Features:
		Easy to use and reuse.
		Dynamically creates page numbers based on the total number of items in a query and the desired number of items per page.
		Ability to select a number of pages to display around the currently select page.
		Links to show all results instead of paginated results.
		Easily styled via CSS.
		Returns SQL which can be used to modify the results in query.
		The items per page can be changed by the user using a build in method which generates a simple drop down menu.
		A 'jump to page' menu can be generated to give the user quick access to jump to any page of the results.
		Creates 'previous' and 'next' buttons when more than 10 pages are generated.

	Example Usage:
	
		// -------------------------------------------
		// 1. Using shared_object class object view
		// -------------------------------------------
		$objPagination = \Core\Util\Pagination\Pagination::getInstance();
		$objPagination->setDefaultItemsPerPage(10);
		$objPagination->setIsFriendlyUrl(true);
		$objPagination->setBaseUrl('/sandbox/index/pagination/');
		$objPagination->paginateFromClassObjectView('PAGE_VIEWS', array(
			'limit' => 800,
			'orderBy' => 'a.id',
			'direction'	=> 'ASC'
		));
		new dump($objPagination->getPageData());
		$objPagination->getPaginationLinks(); // Will return an array with all the pagination links
		$objPagination->getPagination(); // Will return the built html 
		
		// -------------------------------------------
		// 2. Other source of data
		// -------------------------------------------
		
		$objPagination = \Core\Util\Pagination\Pagination::getInstance();
		$objPagination->setItemsTotal($objDb->iQuery("SELECT COUNT(*) recordCount FROM page_views")->fetchOneValue("recordCount"));
		$objPagination->setDefaultItemsPerPage(10);
		$objPagination->setBaseUrl('/sandbox/index/pagination/');
		$objPagination->setPaginationVariables();
		$arrResults = $objDb->query("SELECT * FROM page_views LIMIT " . $objPagination->limit);
		new dump($arrResults);
		new dump($objPagination->getPageData());
		$objPagination->getPaginationLinks(); // Will return an array with all the pagination links
		$objPagination->getPagination(); // Will return the built html 
*/ 
class Pagination 
    extends \Core\Interfaces\Base\ObjectBaseInterface
{
	/**
	 * Available Class Variables:
	 * 
	 * itemsPerPage			:		The desired number of items to be shown on a page. If you use this and the display_items_per_page 
	 *   							method at the same time, it will override anything the user chooses from the drop down menu 
	 *                      		created by display_items_per_page.
	 *                      
	 * itemsTotal			:		The total number of items you'll be paginating. Typically set by querying a table for a count of rows.
	 * 
	 * currentPage			:		The page the user is viewing. Will always be an integer >= 1.
	 * 
	 * totalPages			:		The total number of pages as generated by the paginator method.
	 * 
	 * midRange				:		The number of pages to show 'around' the current page.	
	 * 
	 * sqlLow				:		The offset to use in a SQL LIMIT statement (e.g. SELECT * FROM employees LIMIT 20,10).
	 * 
	 * sqlLimit				: 		A string used in an SQL statement to automatically handle the limiting of results based on the current page of results.
	 * 
	 * pagination			:		A string used to store the HTML containing the page numbers.Used in the display_pages method.
	 *
	 * paginationLinks		:		An array containing all the pagination links in order 
	 * 
	 * urlQueryString		:		The query string in the URL to append to pagination links
	 * 
	 * defaultItemsPerPage	:		25 – The default number of items to display per page.
	 * 
	 * itemsPerPageArray	:		Array of allowed selections for the items per page dropdown. defaults to : array(10, 25, 50, 100, 'All')
	 * 
	 * pageData				:		temporary container for the search results or displayable data
	 * 
	 * isFriendlyUrl		:		if the pagination links should use frienly URL (var_name:var_value)
	 */
	
	protected $end_range;
	protected $start_range;
	protected $range;
	protected $strHtmlOutput; // When the HTML is built, its stored in here for multiple HTML output
    
   /**
	* Class constructor
	*
	* @access	public
	* @return	\Core\Util\Pagination\Pagination
	*/
	public function __construct()
	{
		// Set the current page
		$this->setCurrentPage(1);
		
		// Set the current page
		$this->setPageUrlVariableName('page');
		
		// Set the mid range
		$this->setMidRange(7);
		
		// Set items per page array for dropdown
		$this->setItemsPerPageArray(array(10, 25, 50, 100, 'All'));
		
		// set the current items per page
		$this->setItemsPerPage((FALSE === empty($_GET['ipp']) && ((int) $_GET['ipp'] > 0)) ? (int) $_GET['ipp'] : $this->getdefaultItemsPerPage());
		
		// Initialise the pagination links variable as an array (so that addPaginationLinks() will contact to array)
		$this->setPaginationLinks(array());
		
		// Initialise the page data
		$this->setPageData(array());
	}
	
    /**
     * Class Object Instance Loader
     * 
     * @access	public, static, final
     * @param 	none
     * @return	\Core\Util\Pagination\Pagination
     */
	public static function getInstance()
	{
		return (new self());
	}
	
	/**
	 * This method parses and set the variables pointer to build the pagination
	 * 
	 * @access	public final
	 * @return	void
	 */	
	public final function setPaginationVariables()
	{
		if(((int) $this->getDefaultItemsPerPage()) <= 0) 
		{
			$this->setDefaltItemsPerPage(25);	
		}
		
		if(false === empty($_GET['ipp']) && $_GET['ipp'] == 'All')
		{
			$this->setTotalPages(1);
		}
		else
		{
			if (
				(FALSE === is_numeric($this->getItemsPerPage())) ||
				($this->getItemsPerPage() <= 0)
			) {
				$this->setItemsPerPage($this->getDefaultItemsPerPage());
			}
			
			$this->setTotalPages(ceil($this->getItemsTotal() /$this->getItemsPerPage()));
		}
		
		$this->setCurrentPage((TRUE === isset($_GET[$this->getPageUrlVariableName()]) && ((int) $_GET[$this->getPageUrlVariableName()] > 0)) ? (int) $_GET[$this->getPageUrlVariableName()] : 1);
	
		$prev_page = $this->getCurrentPage() - 1;
		$next_page = $this->getCurrentPage() + 1;
		
		
		/** -- Begin Pagination -- */
		
		if (
			($this->getTotalPages() > 1) &&
			($this->getCurrentPage() > 1)
		) {
			// Set the "First" link
			//$this->addPagination('<a class="paginate" href="' . $this->buildPageUrl(1) . '">&laquo; First</a> ');
			$this->addPaginationLinks(array(
				'href'		=>	$this->buildPageUrl(1),
				'text'		=>	'&laquo;',
				'link_type'	=>	'first_page',
				'page'		=> 1
			));
			
			// Set the "Previous" Link
			//$this->addPagination('<a class="paginate" href="' . $this->buildPageUrl($prev_page) . '">&laquo; Previous</a> ');
			$this->addPaginationLinks(array(
				'href'	=>	$this->buildPageUrl($prev_page),
				'text'	=>	'&lsaquo;',
				'link_type'	=>	'prev_page',
				'page'		=> $prev_page
			));
		}
		
		// Figure out the ranges (where to put the '...' in the left side
		$this->start_range 	= ($this->getCurrentPage() - floor($this->getMidRange() / 2));
		$this->end_range 	= ($this->getCurrentPage() + floor($this->getMidRange() / 2));
		
		if($this->start_range <= 0)
		{
			$this->end_range += abs($this->start_range)+1;
			$this->start_range = 1;
		}
		
		if($this->end_range > $this->getTotalPages())
		{
			$this->start_range -= $this->end_range-$this->getTotalPages();
			$this->end_range 	= $this->getTotalPages();
		}
		
		$this->range = range($this->start_range, $this->end_range);

		for($i=1; $i <= $this->getTotalPages(); $i++)
		{
			if (
				($this->range[0] > 2) 	&&
				($i == $this->range[0]) &&
				($this->getTotalPages() > 10)
			) {
				//$this->addPagination(' ... ');
				$this->addPaginationLinks(array(
					'text'	=>	' ... ',
					'class'	=>	'more',
					'href'	=>	$this->buildPageUrl($i - 1),
					'link_type'	=>	'page_num',
					'page'		=> $i - 1
				));
			}
			
			// loop through all pages. if first, last, or in range, display
			
			// First Range
			if(
				($i == 1) || 
				($i == $this->getTotalPages()) || 
				(in_array($i, $this->range))
			) {
				$strNextHref = (
					(
						($i == $this->getCurrentPage()) && 
						(empty($_GET[$this->getPageUrlVariableName()]) === false) && 
						($_GET[$this->getPageUrlVariableName()] != 'All')
					) ? 
					'#' : $this->buildPageUrl($i)
				);
				
				//$this->addPagination('<a title="Go to page ' . $i . ' of ' . $this->getTotalPages() . '" class="current"  href="' . $strNextHref . '">' . $i . '</a> ');
				$this->addPaginationLinks(array(
					'href'		=>	$strNextHref,
					'text'		=>	$i,
					'isCurrent' => 	($i === $this->getCurrentPage()),
					'class'		=>	($i === $this->getCurrentPage() ? 'current active' : ''), // For active links, we add both classes "Current" and "Active" they are the most comonly used,
					'page'		=> $i
				));
			}
			
			// Last Range
			if (
				($this->range[$this->getMidRange() - 1] < $this->getTotalPages() - 1) &&
				($i == $this->range[$this->getMidRange() - 1]) &&
				($this->getTotalPages() > 10)
			) {
				//$this->addPagination(' ... ');
				$this->addPaginationLinks(array(
					'text'	=>	' ... ',
					'class'	=>	'more',
					'href'	=>	$this->buildPageUrl($i + 1),
					'page'	=> $i + 1
				));
			}
		}
		
		
		if ($this->getCurrentPage() < $this->getTotalPages()) 
		{
			// Add the "Next" Link
			//$this->addPagination('<a class="paginate" href="' . $this->buildPageUrl($next_page) . '">Next &raquo;</a> ');
			$this->addPaginationLinks(array(
				'href'	=>	$this->buildPageUrl($next_page),
				'text'	=>	'&rsaquo;',
				'link_type'	=>	'next_page',
				'page'	=> $next_page
			));
			
			// Add the "Last" link
			//$this->addPagination('<a class="paginate" href="' . $this->buildPageUrl($this->getTotalPages()) . '">Last  &raquo;</a> ');
			$this->addPaginationLinks(array(
				'href'	=>	$this->buildPageUrl($this->getTotalPages()),
				'text'	=>	'&raquo;',
				'link_type'	=>	'last_page',
				'page'	=> $this->getTotalPages()
			));
		}
		
		if (
			(false === empty($_GET['page'])) &&
			($_GET['page'] != 'All') &&
			($this->getTotalPages() > 10)
		) {
			// Add the "All" link
			//$this->addPagination('<a class="paginate" href="' . $this->buildPageUrl(1, 'All') . '">All</a> ');
			$this->addPaginationLinks(array(
				'href'	=> 	$this->buildPageUrl(1, 'All'),
				'text'	=>	'All',
				'link_type'	=>	'all',
				'page'	=> 1
			));
		}
		
		/** -- End Pagination -- */
		
		// Sql Low value (LIMIT 25, 100) the 25 is the low value
		$this->setSqlLow(
			($this->getCurrentPage() <= 0) ? 
			($this->getCurrentPage() - 1) :
			(($this->getCurrentPage() - 1) * ($this->getItemsPerPage()))
		);
		
		if ($this->getCurrentPage() <= 0) 
		{
			$this->setItemsPerPage(0);
		}
		
		// Set the sql limit
		$this->setSqlLimit(
			((false === empty($_GET['ipp'])) && ($_GET['ipp'] == 'All')) ? "" : $this->getSqlLow() . ", " . $this->getItemsPerPage()
		);
	}
    
	/**
	 * Builds the pagination HTML
	 * 
	 * @access	protected
	 * @param	none
	 * @return 	string	the Pagination HTML
	 */
	public function getPagination() 
	{
		if (TRUE === empty($this->strHtmlOutput)) 
		{
			$arrHtmlLinks = $this->getPaginationLinks();
			
			if (FALSE === empty($arrHtmlLinks))	
			{
				$this->strHtmlOutput = '<ul>';
				
				reset($arrHtmlLinks);
				while (list($intIndex, $arrLinksData) = each($arrHtmlLinks))
				{
					$this->strHtmlOutput .= '<li>';
					
					$strNewHtmlLink = $arrLinksData['text'];
					
					// Href link
					$strNewHtmlLink = 	'<a href="' . (FALSE === empty($arrLinksData['href']) ? $arrLinksData['href'] : '#') . 
										'" class="' . $arrLinksData['class'] . '">' . $strNewHtmlLink . '</a> ';
						
					$this->strHtmlOutput .= $strNewHtmlLink;
					$this->strHtmlOutput .= '</li>';
				}
				
				$this->strHtmlOutput .= '</ul>';
			}
		}
		
		return ($this->strHtmlOutput);
	}
	
	/**
	 * Creates a page link URL
	 * 
	 * @access	protected
	 * @param	integer	$intPage 			- The page number
	 * @param	string	$strItemsPerPage 	- Items per page param (willdefault to the curremt
	 * @return 	string	the page URL
	 */
	protected final function buildPageUrl($intPageUrl = -1, $strItemsPerPage = NULL)
	{
		$strReturnUrl = '';
		if ((int) $intPageUrl > 0)
		{
			if ($this->getIsFriendlyUrl())
			{
				$objPageUrl  = \Core\Net\Url::getCanonicalUrl(NULL, false, true, true, array($this->getPageUrlVariableName(), 'ipp'));
				$objPageUrl .= '/' . $this->getPageUrlVariableName() . ':' . ((int) $intPageUrl);
			}
			else
			{
				$objPageUrl = new \Core\Net\Url(NULL, false);
				$objPageUrl->setIsFriendlyUrl((bool) $this->getIsFriendlyUrl());
				$objPageUrl->deleteAttribute($this->getPageUrlVariableName());
				$objPageUrl->deleteAttribute('ipp');
				$objPageUrl->setAttribute($this->getPageUrlVariableName(), (int) $intPageUrl);
			}
			
			/*
			$objPageUrl = new \Core\Net\Url(NULL, false);
			$objPageUrl->setIsFriendlyUrl((bool) $this->getIsFriendlyUrl());
			$objPageUrl->deleteAttribute($this->getPageUrlVariableName());
			$objPageUrl->deleteAttribute('ipp');
			$objPageUrl->setAttribute($this->getPageUrlVariableName(), (int) $intPageUrl);
			*/
			
			// We only want to set the items per page if a non default amount is requested
			$intItemsPerPage = (FALSE === is_null($strItemsPerPage) ? $strItemsPerPage : FALSE);
			if (
				(FALSE === $intItemsPerPage) &&
				($this->getdefaultItemsPerPage() <> $this->getItemsPerPage())
			) {
				$intItemsPerPage = $this->getItemsPerPage();
			}

			if ($intItemsPerPage) 
			{
				if (false === $this->getIsFriendlyUrl())
				{
					$objPageUrl->setAttribute('ipp', (int) $intItemsPerPage);				
				}
				else
				{
					$objPageUrl .= '/ipp:' . ((int) $intItemsPerPage);
				}
			}
			
			if ($this->getBaseUrl() && (! $this->getIsFriendlyUrl()))
			{
				$objPageUrl->setPath($this->getBaseUrl());
			}
			
			$strReturnUrl = ($this->getIsFriendlyUrl() ? $objPageUrl : $objPageUrl->build());
		}
		
		$strReturnUrl = str_replace('//', '/', $strReturnUrl);
		
		return ($strReturnUrl);
	}
	
	/**
	 * This method parses and paginates the class object view
	 * 
	 * @param 	string $strClassName - The class name (must implement SHARED_OBJECT)
	 * @param 	array  $arrViewData	 - The view parameter array
	 * @see		SHARED_OBJECT::getObjectClassView()
	 * @throws	SITE_EXCEPTION
	 * @return	PAGINATION
	 */
	public function paginateFromClassObjectView($strClassName, $arrViewData = array())
	{
		// Check if the clas exists
		if (FALSE === class_exists($strClassName))
		{
			\Core\Exception\Exception::report('Class ' . $strClassName . ' doesnt exists');
		}
		
		// Check if its a shared_object
		if (FALSE === (is_subclass_of($strClassName, '\\Core\\Interfaces\\HybernateInterface')))
		{
			\Core\Exception\Exception::report('Class ' . $strClassName . ' is not a shared object.');
		}
		
		// Continue with the class object record count
		$arrRecordCountRequest = $arrViewData;
		$arrRecordCountRequest['columns'] = 'COUNT(DISTINCT a.id) as RECORDCOUNT';
		$arrRecordCountRequest['groupBy'] = null;
		$arrRecordCountRequest['orderBy'] = null;
		$arrRcData = $strClassName::getClassView((array) $arrRecordCountRequest);
		$arrRecordCount = (false === empty($arrRcData) ? array_shift($arrRcData) : array());
		if (false === empty($arrRecordCount))
		{
			$this->setItemsTotal(
				(
					(false === empty($arrRecordCountRequest['limit'])) && 
					((int) $arrRecordCountRequest['columns'] < $arrRecordCount['RECORDCOUNT'])
				) ? (int) $arrRecordCountRequest['limit'] : (int) $arrRecordCount['RECORDCOUNT']
			);
		}
		
		// Build the pagination variables
		$this->setPaginationVariables();
		
        // Set  the limit in the object class view
        $arrViewData['limit'] = $this->getSqlLimit();
        $this->setPageData($strClassName::getClassView((array) $arrViewData));
        
        return ($this);
	}
}

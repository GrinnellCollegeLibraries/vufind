<?php
/**
 * EBSCO Search Results
 *
 * PHP version 5
 *
 * Copyright (C) Villanova University 2011.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category VuFind2
 * @package  Search_EBSCO
 * @author   Julia Bauder <bauderj@grinnell.edu>
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.vufind.org  Main Page
 */
namespace VuFind\Search\Article;

/**
 * EBSCO Search Parameters
 * Partially copied from WorldCat Search Parameters; partially copied from other
 * pieces of VuFind code
 *
 * @category VuFind2
 * @package  Search_EBSCO
 * @author   Julia Bauder <bauderj@grinnell.edu>
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.vufind.org  Main Page
 */
//use VuFind\Search\EIT\Results as EITResults;
//use VuFind\Search\Solr\Results as SolrResults;

class Results extends \VuFind\Search\Base\Results

{
protected function performSearch()
    {
	$query = $this->getParams()->getQuery();
//TODO: Instead of just dividing by 2, divide by the number of different backends configured and round to the nearest integer?
	$limit = ($this->getParams()->getLimit())/2;
//TODO: Make the offset actually work mathematically
	$offset = ($this->getStartRecord() - 1)/2;	
//	$eit = new EITResults($this->params);
//	$eitparams = $eit->getParams()->getBackendParameters();
//	$solr = new SolrResults($this->params);
//	$solrparams = $solr->getParams()->getBackendParameters();
//	print_r($solrparams);
//	print_r($eitparams);
//	$collection = $this->getSearchService()->search('Article', $query, $offset, $limit, $eitparams, $solrparams);
	$collection1 = $this->getSearchService()->search('Solr', $query, $offset, $limit, $solrparams);
	$collection2 = $this->getSearchService()->search('EIT', $query, $offset, $limit, $eitparams);
	$this->resultTotal = $collection1->getTotal() + $collection2->getTotal();
	if ($collection1->getTotal() > ($offset + $limit) && $collection2->getTotal() > ($offset + limit)) {
//TODO: Make this do something more logical than just merging the two arrays one after the other
	$this->results = array_merge($collection1->getRecords(), $collection2->getRecords());
	} elseif ($collection1->getTotal() < ($offset + $limit)) {
		$collection2 = $this->getSearchService()->search('EIT', $query, $offset, ($limit + $limit - $collection1->getTotal()), $eitparams);
	$this->results = array_merge($collection1->getRecords(), $collection2->getRecords());
	}
		
	
    }

    /**
     * Returns the stored list of facets for the last search
     *
     * @param array $filter Array of field => on-screen description listing
     * all of the desired facet fields; set to null to get all configured values.
     *
     * @return array        Facets data arrays
     */
    public function getFacetList($filter = null)
    {
        // No facets in EIT:
        return [];
    }
}

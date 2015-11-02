<?php
/**
 * Copyright 2015 Simen Thorsrud
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category    St
 * @package     St_SphinxSearch
 * @author      Simen Thorsrud <simen.thorsrud@gmail.com>
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

/**
 * Class St_SphinxSearch_Model_Fulltext
 */
class St_SphinxSearch_Model_Fulltext extends Mage_Core_Model_Abstract
{

    /**
     * Set up resource model
     */
    protected function _construct()
    {
        $this->_init('st_sphinxsearch/fulltext');
    }

    /**
     * Regenerate all Stores index
     *
     * Examples:
     * (null, null) => Regenerate index for all stores
     * (1, null)    => Regenerate index for store Id=1
     * (1, 2)       => Regenerate index for product Id=2 and its store view Id=1
     * (null, 2)    => Regenerate index for all store views of product Id=2
     *
     * @param   int|null        $storeId Store View Id
     * @param   int|array|null  $productIds Product Entity Id
     * @see     Mage_CatalogSearch_Model_Fulltext
     * @return  St_SphinxSearch_Model_Fulltext
     */
    public function rebuildIndex($storeId = null, $productIds = null)
    {
        Mage::dispatchEvent('st_sphinxsearch_catalog_index_process_start', array(
            'store_id'      => $storeId,
            'product_ids'   => $productIds
        ));

        $this->getResource()->rebuildIndex($storeId, $productIds);

        Mage::dispatchEvent('st_sphinxsearch_catalog_index_process_complete', array());

        return $this;
    }
}

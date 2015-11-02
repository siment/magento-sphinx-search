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
 * Class St_SphinxSearch_Model_Indexer
 */
class St_SphinxSearch_Model_Indexer
{

    /** @var null|array Array of searchable attributes */
    protected $_searchableAttributes = null;

    /**
     * Reindex all indexes associated with Sphinx
     */
    public function reindexAll()
    {

        //@todo: indexers should be dynamically injected. Probable via `$this->getIndeces()`
        /** @var St_SphinxSearch_Model_Fulltext $fulltextIndex */
        $fulltextIndex = Mage::getModel('st_sphinxsearch/fulltext');

        $fulltextIndex->rebuildIndex();

        return;
        /** @var Mage_Core_Model_Resource $resourceSingleton */
        $resourceSingleton = Mage::getSingleton('core/resource');

        /** @var Varien_Db_Adapter_Interface $writeAdapater */
        $writeAdapater = $resourceSingleton->getConnection('core_write');

        /** @var Varien_Db_Adapter_Interface $readAdapter */
        $readAdapter = $resourceSingleton->getConnection('core_read');

        /** @var string $tableName */
        $tableName = $readAdapter->getTableName('st_sphinxsearch_fulltext_tmp');

        /** @var Mage_CatalogSearch_Model_Resource_Indexer_Fulltext $indexerFulltext */
        $indexerFulltext = Mage::getResourceModel('catalogsearch/indexer_fulltext');

        $fulltextTableName = $indexerFulltext->getTable('fulltext');

        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` LIKE `$fulltextTableName`";

        $writeAdapater->query($sql);
        $t=1;
    }

    /**
     * Retrieve searchable attributes list
     *
     * @see \Mage_CatalogSearch_Model_Indexer_Fulltext::_getSearchableAttributes
     * @return array
     */
    protected function _getSearchableAttributes()
    {
        if (is_null($this->_searchableAttributes)) {
            /** @var $attributeCollection Mage_Catalog_Model_Resource_Product_Attribute_Collection */
            $attributeCollection = Mage::getResourceModel('catalog/product_attribute_collection');
            $attributeCollection->addIsSearchableFilter();

            foreach ($attributeCollection as $attribute) {
                $this->_searchableAttributes[] = $attribute->getAttributeCode();
            }
        }

        return $this->_searchableAttributes;
    }
}

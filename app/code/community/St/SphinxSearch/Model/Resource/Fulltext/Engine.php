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
 * Class St_SphinxSearch_Model_Resource_Fulltext_Engine
 *
 * This class is responsible for populated the intermediary index table with data.
 *
 * The native Spinx indexer will query the main table of this class to populate its index.
 */
class St_SphinxSearch_Model_Resource_Fulltext_Engine extends Mage_CatalogSearch_Model_Resource_Fulltext_Engine
{
    /**
     * Init resource model
     *
     */
    protected function _construct()
    {
        $this->_init('st_sphinxsearch/catalog_fulltext', 'product_id');
    }

    /**
     * @param int   $storeId
     * @param array $productIndexes
     */
    public function saveEntityIndexes($storeId, $entityIndexes, $entity = 'product')
    {

        // Use parent class to save `data_index` to this class' table
        // I can do this because this table is also structured with `product_id` and `store_id`
        // as duplicate keys and I do have the field `data_index` in this table
        parent::saveEntityIndexes($storeId, $entityIndexes);

        $data    = array();
        $storeId = (int)$storeId;
        foreach ($entityIndexes as $entityId => $index) {
            $name = $this->_getProductNameFromId((int) $entityId, $storeId);
            $sku = $this->_getProductSkuFromId((int) $entityId, $storeId);
            $data[] = array(
                'product_id'    => (int)$entityId,
                'store_id'      => $storeId,
                'name'          => $name,
                'attributes'    => $name . '|' . $index,
                'sku'           => $sku,
            );
        }

        if ($data) {
            Mage::getResourceHelper('catalogsearch')
                ->insertOnDuplicate($this->getMainTable(), $data, array('name', 'attributes', 'sku'));
        }
    }

    protected function _getProductSkuFromId($productId, $storeId = 0)
    {
        return $this->_getAttributeValueFromProductId($productId, $storeId, 'sku');
    }

    protected function _getProductNameFromId($productId, $storeId = 0)
    {
        return $this->_getAttributeValueFromProductId($productId, $storeId, 'name');
    }

    protected function _getAttributeValueFromProductId($productId, $storeId = 0, $attributeCode)
    {
        /** @var Mage_Catalog_Model_Resource_Product $productResource */
        $productResource = Mage::getResourceSingleton('catalog/product');

        /** @var mixed|string $attributeValue */
        $attributeValue = $productResource->getAttributeRawValue($productId, $attributeCode, $storeId);

        return $attributeValue;
    }

    public function prepareEntityIndex($index, $separator = ' ')
    {

        return parent::prepareEntityIndex($index);

        $indexData = '';

        $index = array();
        foreach ($this->_getSearchableAttributes('static') as $attribute) {
            if (isset($productData[$attribute->getAttributeCode()])) {
                if ($value = $this->_getAttributeValue($attribute->getId(), $productData[$attribute->getAttributeCode()], $storeId)) {
                    //For grouped products
                    if (isset($index[$attribute->getAttributeCode()])) {
                        if (!is_array($index[$attribute->getAttributeCode()])) {
                            $index[$attribute->getAttributeCode()] = array($index[$attribute->getAttributeCode()]);
                        }
                        $index[$attribute->getAttributeCode()][] = $value;
                    }
                    //For other types of products
                    else {
                        $index[$attribute->getAttributeCode()] = $value;
                    }
                }
            }
        }
        foreach ($indexData as $attributeData) {
            foreach ($attributeData as $attributeId => $attributeValue) {
                if ($value = $this->_getAttributeValue($attributeId, $attributeValue, $storeId)) {
                    $code = $this->_getSearchableAttribute($attributeId)->getAttributeCode();
                    //For grouped products
                    if (isset($index[$code])) {
                        if (!is_array($index[$code])) {
                            $index[$code] = array($index[$code]);
                        }
                        $index[$code][] = $value;
                    }
                    //For other types of products
                    else {
                        $index[$code] = $value;
                    }
                }
            }
        }
        $product = $this->_getProductEmulator()
            ->setId($productData['entity_id'])
            ->setTypeId($productData['type_id'])
            ->setStoreId($storeId);
        $typeInstance = $this->_getProductTypeInstance($productData['type_id']);
        if ($data = $typeInstance->getSearchableData($product)) {
            $index['options'] = $data;
        }
        if (isset($productData['in_stock'])) {
            $index['in_stock'] = $productData['in_stock'];
        }


        return $indexData;

    }

    /**
     * Define if engine is avaliable
     *
     * @return bool
     */
    public function test()
    {
        return true;
    }
}
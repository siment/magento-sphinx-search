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
 * Class St_SphinxSearch_Model_Resource_Fulltext
 */
class St_SphinxSearch_Model_Resource_Fulltext
{
    /**
     * Init resource model
     *
     */
    protected function _construct()
    {
        $this->_init('st_sphinxsearch/catalog_fulltext', 'product_id');
        $this->_engine = Mage::helper('catalogsearch')->getEngine();
    }
}

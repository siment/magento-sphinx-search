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
 * Class St_SphinxSearch_Model_Observer
 */
class St_SphinxSearch_Model_Observer
{

    /**
     * This is run after catalogsearch_index_process_complete
     *
     * @event catalogsearch_index_process_complete
     * @param Varien_Event_Observer $eventObserver
     */
    public function mageCatalogSearchIndexComplete(Varien_Event_Observer $eventObserver)
    {

        // @todo: check if this functionality is enabled

        /** @var St_SphinxSearch_Model_Indexer $indexer */
        $indexer = Mage::getModel('st_sphinxsearch/indexer');

        $indexer->reindexAll();

        return $this;
    }
}
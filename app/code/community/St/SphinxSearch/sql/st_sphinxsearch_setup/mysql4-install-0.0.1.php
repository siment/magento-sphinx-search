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

/** @var St_SphinxSearch_Model_Resource_Setup $installer */
$installer = $this;

/** @var string $tableName */
$tableName = $installer->getTable('st_sphinxsearch_catalog_fulltext');

/** @var Varien_Db_Adapter_Interface $connection */
$connection = $installer->getConnection();

// If something is botched with module resource, it is relatively safe
// to drop this table. It is just an index that will be rebuilt
if ($connection->isTableExists($tableName)) {
    $connection->dropTable($tableName);
}

/** @var Varien_Db_Ddl_Table $table */
$table = $connection->newTable($tableName);

$table->addColumn(
    'product_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,
    null,
    array(
        'unsigned'  => true,
        'nullable'  => false
    ),
    'Product ID'
);

$table->addColumn(
    'store_id',
    Varien_Db_Ddl_Table::TYPE_SMALLINT,
    null,
    array(
        'nullable'  => false
    ),
    'Store ID'
);

$table->addColumn(
    'name',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,
    255,
    array(
        'nullable'  => false
    ),
    'Product Name'
);

$table->addColumn(
    'sku',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    255,
    array(
        'nullable'  => false
    ),
    'Product SKU'
);

$table->addColumn(
    'attributes',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    null,
    array(
        'nullable'  => false
    ),
    'Product Name + selected attributes'
);

$table->addColumn(
    'data_index',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    null,
    array(
        'nullable'  => false
    ),
    'Original Magento data_index'
);

$table->addColumn(
    'sku',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    255,
    array(
        'nullable'  => false
    ),
    'Product SKU'
);

$table->addIndex(
    $installer->getIdxName(
        $tableName,
        array('product_id', 'store_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_PRIMARY
    ),
    array('product_id', 'store_id'),
    array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_PRIMARY)
);

$table->addIndex(
    $installer->getIdxName(
        $tableName,
        array('data_index'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_FULLTEXT
    ),
    array('data_index'),
    array(
        'type' => Varien_Db_Adapter_Interface::INDEX_TYPE_FULLTEXT
    )
);

$table->setOption('type','MyISAM');

$connection->createTable($table);

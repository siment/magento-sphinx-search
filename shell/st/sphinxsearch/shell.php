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

/*
 * Put random php tests in this file. The file does not contain any code required
 * by module to run
 */

chdir(dirname(__FILE__));

/** @var string $htdocsDir  If you are using Composer, this is the Magento
 *                          webroot directory relative to composer root set
 *                          this to '' or false if you are not using Composer */
$htdocsDir = 'htdocs'; // or '' if you are not using Composer

/*
 * Include abstract.php
 */
if(!class_exists('Mage_Shell_Abstract')) {

    /** @var int $tries Number of attempts */
    $tries = 0;

    /** @var int $maxTries Max number of attempts */
    $maxTries = 8;

    /** @var string $parentDir */
    $parentDir = DIRECTORY_SEPARATOR . '..';

    /** @var string $abstract Assumed location of abstract.php */
    $abstract =  $parentDir
        . (strlen($htdocsDir) > 0 ? DIRECTORY_SEPARATOR . $htdocsDir : '')
        . DIRECTORY_SEPARATOR
        . 'shell'
        . DIRECTORY_SEPARATOR
        . 'abstract.php';

    // Try one level up each time file is not found
    while (! file_exists(dirname(__FILE__) . $abstract) && $tries++ < 8) {
        $abstract = $parentDir . $abstract;
    }

    // Try to require file
    require_once dirname(__FILE__) . $abstract;
}

class Shell extends Mage_Shell_Abstract
{
    public function run()
    {

        /** @var Mage_CatalogSearch_Model_Fulltext $fulltextModel */
        $fulltextModel = Mage::getModel('catalogsearch/fulltext');

        /** @var string $queryText */
        $queryText = 'shirt';

        /** @var Mage_CatalogSearch_Model_Query $queryObject */
        $queryObject = Mage::getModel('catalogsearch/query')->load(61);

        /** @var St_SphinxSearch_Model_Resource_Fulltext $fulltextResource */
        $fulltextResource = Mage::getResourceModel('st_sphinxsearch/fulltext');

        $fulltextResource->prepareResult($fulltextModel, $queryText, $queryObject);

    }
}

$shell = new Shell();
$shell->run();
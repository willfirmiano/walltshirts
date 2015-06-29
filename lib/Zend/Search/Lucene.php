<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Lucene.php 22987 2010-09-21 10:39:53Z alexander $
 */


/** User land classes and interfaces turned on by Zend/Search/Lucene.php file inclusion. */
/** @todo Section should be removed with ZF 2.0 release as obsolete                      */

/** Zend_Search_Lucene_Document_Html */
#require_once 'Zend/Search/Lucene/Document/Html.php';

/** Zend_Search_Lucene_Document_Docx */
#require_once 'Zend/Search/Lucene/Document/Docx.php';

/** Zend_Search_Lucene_Document_Pptx */
#require_once 'Zend/Search/Lucene/Document/Pptx.php';

/** Zend_Search_Lucene_Document_Xlsx */
#require_once 'Zend/Search/Lucene/Document/Xlsx.php';

/** Zend_Search_Lucene_Search_QueryParser */
#require_once 'Zend/Search/Lucene/Search/QueryParser.php';

/** Zend_Search_Lucene_Search_QueryHit */
#require_once 'Zend/Search/Lucene/Search/QueryHit.php';

/** Zend_Search_Lucene_Analysis_Analyzer */
#require_once 'Zend/Search/Lucene/Analysis/Analyzer.php';

/** Zend_Search_Lucene_Search_Query_Term */
#require_once 'Zend/Search/Lucene/Search/Query/Term.php';

/** Zend_Search_Lucene_Search_Query_Phrase */
#require_once 'Zend/Search/Lucene/Search/Query/Phrase.php';

/** Zend_Search_Lucene_Search_Query_MultiTerm */
#require_once 'Zend/Search/Lucene/Search/Query/MultiTerm.php';

/** Zend_Search_Lucene_Search_Query_Wildcard */
#require_once 'Zend/Search/Lucene/Search/Query/Wildcard.php';

/** Zend_Search_Lucene_Search_Query_Range */
#require_once 'Zend/Search/Lucene/Search/Query/Range.php';

/** Zend_Search_Lucene_Search_Query_Fuzzy */
#require_once 'Zend/Search/Lucene/Search/Query/Fuzzy.php';

/** Zend_Search_Lucene_Search_Query_Boolean */
#require_once 'Zend/Search/Lucene/Search/Query/Boolean.php';

/** Zend_Search_Lucene_Search_Query_Empty */
#require_once 'Zend/Search/Lucene/Search/Query/Empty.php';

/** Zend_Search_Lucene_Search_Query_Insignificant */
#require_once 'Zend/Search/Lucene/Search/Query/Insignificant.php';




/** Internally used classes */

/** Zend_Search_Lucene_Interface */
#require_once 'Zend/Search/Lucene/Interface.php';

/** Zend_Search_Lucene_Index_SegmentInfo */
#require_once 'Zend/Search/Lucene/Index/SegmentInfo.php';

/** Zend_Search_Lucene_LockManager */
#require_once 'Zend/Search/Lucene/LockManager.php';


/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Search_Lucene implements Zend_Search_Lucene_Interface
{
    /**
     * Default field name for search
     *
     * Null means search through all fields
     *
     * @var string
     */
    private static $_defaultSearchField = null;

    /**
     * Result set limit
     *
     * 0 means no limit
     *
     * @var integer
     */
    private static $_resultSetLimit = 0;

    /**
     * Terms per query limit
     *
     * 0 means no limit
     *
     * @var integer
     */
    private static $_termsPerQueryLimit = 1024;

    /**
     * File system adapter.
     *
     * @var Zend_Search_Lucene_Storage_Directory
     */
    private $_directory = null;

    /**
     * File system adapter closing option
     *
     * @var boolean
     */
    private $_closeDirOnExit = true;

    /**
     * Writer for this index, not instantiated unless required.
     *
     * @var Zend_Search_Lucene_Index_Writer
     */
    private $_writer = null;

    /**
     * Array of Zend_Search_Lucene_Index_SegmentInfo objects for current version of index.
     *
     * @var array Zend_Search_Lucene_Index_SegmentInfo
     */
    private $_segmentInfos = array();

    /**
     * Number of documents in this index.
     *
     * @var integer
     */
    private $_docCount = 0;

    /**
     * Flag for index changes
     *
     * @var boolean
     */
    private $_hasChanges = false;


    /**
     * Signal, that index is already closed, changes are fixed and resources are cleaned up
     *
     * @var boolean
     */
    private $_closed = false;

    /**
     * Number of references to the index object
     *
     * @var integer
     */
    private $_refCount = 0;

    /**
     * Current segment generation
     *
     * @var integer
     */
    private $_generation;

    const FORMAT_PRE_2_1 = 0;
    const FORMAT_2_1     = 1;
    const FORMAT_2_3     = 2;


    /**
     * Index format version
     *
     * @var integer
     */
    private $_formatVersion;

    /**
     * Create index
     *
     * @param mixed $directory
     * @return Zend_Search_Lucene_Interface
     */
    public static function create($directory)
    {
        /** Zend_Search_Lucene_Proxy */
        #require_once 'Zend/Search/Lucene/Proxy.php';

        return new Zend_Search_Lucene_Proxy(new Zend_Search_Lucene($directory, true));
    }

    /**
     * Open index
     *
     * @param mixed $directory
     * @return Zend_Search_Lucene_Interface
     */
    public static function open($directory)
    {
        /** Zend_Search_Lucene_Proxy */
        #require_once 'Zend/Search/Lucene/Proxy.php';

        return new Zend_Search_Lucene_Proxy(new Zend_Search_Lucene($directory, false));
    }

    /** Generation retrieving counter */
    const GENERATION_RETRIEVE_COUNT = 10;

    /** Pause between generation retrieving attempts in milliseconds */
    const GENERATION_RETRIEVE_PAUSE = 50;

    /**
     * Get current generation number
     *
     * Returns generation number
     * 0 means pre-2.1 index format
     * -1 means there are no segments files.
     *
     * @param Zend_Search_Lucene_Storage_Directory $directory
     * @return integer
     * @throws Zend_Search_Lucene_Exception
     */
    public static function getActualGeneration(Zend_Search_Lucene_Storage_Directory $directory)
    {
        /**
         * Zend_Search_Lucene uses segments.gen file to retrieve current generation number
         *
         * Apache Lucene index format documentation mentions this method only as a fallback method
         *
         * Nevertheless we use it according to the performance considerations
         *
         * @todo check if we can use some modification of Apache Lucene generation determination algorithm
         *       without performance problems
         */

        #require_once 'Zend/Search/Lucene/Exception.php';
        try {
            for ($count = 0; $count < self::GENERATION_RETRIEVE_COUNT; $count++) {
                // Try to get generation file
                $genFile = $directory->getFileObject('segments.gen', false);

                $format = $genFile->readInt();
                if ($format != (int)0xFFFFFFFE) {
                    throw new Zend_Search_Lucene_Exception('Wrong segments.gen file format');
                }

                $gen1 = $genFile->readLong();
                $gen2 = $genFile->readLong();

                if ($gen1 == $gen2) {
                    return $gen1;
                }

                usleep(self::GENERATION_RETRIEVE_PAUSE * 1000);
            }

            // All passes are failed
            throw new Zend_Search_Lucene_Exception('Index is under processing now');
        } catch (Zend_Search_Lucene_Exception $e) {
            if (strpos($e->getMessage(), 'is not readable') !== false) {
                try {
                    // Try to open old style segments file
                    $segmentsFile = $directory->getFileObject('segments', false);

                    // It's pre-2.1 index
                    return 0;
                } catch (Zend_Search_Lucene_Exception $e) {
                    if (strpos($e->getMessage(), 'is not readable') !== false) {
                        return -1;
                    } else {
                        throw new Zend_Search_Lucene_Exception($e->getMessage(), $e->getCode(), $e);
                    }
                }
            } else {
                throw new Zend_Search_Lucene_Exception($e->getMessage(), $e->getCode(), $e);
            }
        }

        return -1;
    }

    /**
     * Get generation number associated with this index instance
     *
     * The same generation number in pair with document number or query string
     * guarantees to give the same result while index retrieving.
     * So it may be used for search result caching.
     *
     * @return integer
     */
    public function getGeneration()
    {
        return $this->_generation;
    }


    /**
     * Get segments file name
     *
     * @param integer $generation
     * @return string
     */
    public static function getSegmentFileName($generation)
    {
        if ($generation == 0) {
            return 'segments';
        }

        return 'segments_' . base_convert($generation, 10, 36);
    }

    /**
     * Get index format version
     *
     * @return integer
     */
    public function getFormatVersion()
    {
        return $this->_formatVersion;
    }

    /**
     * Set index format version.
     * Index is converted to this format at the nearest upfdate time
     *
     * @param int $formatVersion
     * @throws Zend_Search_Lucene_Exception
     */
    public function setFormatVersion($formatVersion)
    {
        if ($formatVersion != self::FORMAT_PRE_2_1  &&
            $formatVersion != self::FORMAT_2_1  &&
            $formatVersion != self::FORMAT_2_3) {
            #require_once 'Zend/Search/Lucene/Exception.php';
            throw new Zend_Search_Lucene_Exception('Unsupported index format');
        }

        $this->_formatVersion = $formatVersion;
    }

    /**
     * Read segments file for pre-2.1 Lucene index format
     *
     * @throws Zend_Search_Lucene_Exception
     */
    private function _readPre21SegmentsFile()
    {
        $segmentsFile = $this->_directory->getFileObject('segments');

        $format = $segmentsFile->readInt();

        if ($format != (int)0xFFFFFFFF) {
            #require_once 'Zend/Search/Lucene/Exception.php';
            throw new Zend_Search_Lucene_Exception('Wrong segments file format');
        }

        // read version
        $segmentsFile->readLong();

        // read segment name counter
        $segmentsFile->readInt();

        $segments = $segmentsFile->readInt();

        $this->_docCount = 0;

        // read segmentInfos
        for ($count = 0; $count < $segments; $count++) {
            $segName = $segmentsFile->readString();
            $segSize = $segmentsFile->readInt();
            $this->_docCount += $segSize;

            $this->_segmentInfos[$segName] =
                                new Zend_Search_Lucene_Index_SegmentInfo($this->_directory,
                                                                         $segName,
                                                                         $segSize);
        }

        // Use 2.1 as a target version. Index will be reorganized at update time.
        $this->_formatVersion = self::FORMAT_2_1;
    }

    /**
     * Read segments file
     *
     * @throws Zend_Search_Lucene_Exception
     */
    private function _readSegmentsFile()
    {
        $segmentsFile = $this->_directory->getFileObject(self::getSegmentFileName($this->_generation));

        $format = $segmentsFile->readInt();

        if ($format == (int)0xFFFFFFFC) {
            $this->_formatVersion = self::FORMAT_2_3;
        } else if ($format == (int)0xFFFFFFFD) {
            $this->_formatVersion = self::FORMAT_2_1;
        } else {
            #require_once 'Zend/Search/Lucene/Exception.php';
            throw new Zend_Search_Lucene_Exception('Unsupported segments file format');
        }

        // read version
        $segmentsFile->readLong();

        // read segment name counter
        $segmentsFile->readInt();

        $segments = $segmentsFile->readInt();

        $this->_docCount = 0;

        // read segmentInfos
        for ($count = 0; $count < $segments; $count++) {
            $segName = $segmentsFile->readString();
            $segSize = $segmentsFile->readInt();

            // 2.1+ specific properties
            $delGen = $seg
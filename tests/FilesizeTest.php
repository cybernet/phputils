<?php
 
use Mingalevme\Utils\Filesize;
 
class FilesizeTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        // pass
    }
    
    /**
     * @dataProvider humanizeDataProvider
     */
    public function testHumanize($humanSize, $byteSize, $precision, $useBinaryPrefix, $exception=null, $emessage=null)
    {
        if ($exception) {
            $this->setExpectedException($exception, $emessage);
        }
        
        $this->assertEquals($humanSize, Filesize::humanize($byteSize, $precision, $useBinaryPrefix));
    }
    
    public function humanizeDataProvider()
    {
        return [
            ['512b',    512,                2,  false],
            ['1Kb',     1000,               2,  false],
            ['2Mb',     2*pow(1000, 2),     2,  false],
            ['20Gb',    20*pow(1000, 3),    2,  false],
            
            ['5.65Mb',  5.65*pow(1000, 2),  2,  false],
            ['7.89Gb',  7.89*pow(1000, 3),  2,  false],
            
            ['1KiB',    1*pow(1024, 1),     2,  true],
            ['3GiB',    3*pow(1024, 3),     2,  true],
            
            [0,         1*pow(1000, 9),     2,  false, 'Mingalevme\Utils\Exception', 'Size is too big'],
        ];
    }
    
    /**
     * @dataProvider dehumanizeDataProvider
     */
    public function testDehumanize($byteSize, $humanSize, $exception=null, $emessage=null)
    {
        if ($exception) {
            $this->setExpectedException($exception, $emessage);
        }
        
        $this->assertEquals($byteSize, Filesize::dehumanize($humanSize));
    }
    
    public function dehumanizeDataProvider()
    {
        return [
            [1 * pow(1024, 1),  '1024'],
            [1 * pow(1024, 1),  '1024b'],
            [1 * pow(1024, 1),  '1024B'],
            
            [2 * pow(1000, 1),  '2K'],
            [2 * pow(1000, 1),  '2Kb'],
            [2 * pow(1000, 1),  '2KB'],
            [2 * pow(1024, 1),  '2KiB'],
            
            [3 * pow(1000, 2),  '3M'],
            [3 * pow(1000, 2),  '3Mb'],
            [3 * pow(1000, 2),  '3MB'],
            [3 * pow(1024, 2),  '3MiB'],
            
            [4 * pow(1000, 3),  '4G'],
            [4 * pow(1000, 3),  '4Gb'],
            [4 * pow(1000, 3),  '4GB'],
            [4 * pow(1024, 3),  '4GiB'],
            
            [5 * pow(1000, 4),  '5T'],
            [5 * pow(1000, 4),  '5Tb'],
            [5 * pow(1024, 4),  '5TiB'],
            
            [6 * pow(1000, 5),  '6P'],
            [6 * pow(1000, 5),  '6Pb'],
            [6 * pow(1024, 5),  '6PiB'],
            
            [7 * pow(1000, 6),  '7E'],
            [7 * pow(1000, 6),  '7Eb'],
            [7 * pow(1024, 6),  '7EiB'],
            
            [8 * pow(1000, 7),  '8Z'],
            [8 * pow(1000, 7),  '8Zb'],
            [8 * pow(1024, 7),  '8ZiB'],
            
            [9 * pow(1000, 8),  '9Y'],
            [9 * pow(1000, 8),  '9Yb'],
            [9 * pow(1024, 8),  '9YiB'],
            
            [intval(1.58 * pow(1000, 1)),   '1.58K'],
            [intval(2.20 * pow(1024, 2)),   '2.2MiB'],
            [intval(3.65 * pow(1000, 3)),   '3.65GB'],
            [intval(4.29 * pow(1000, 4)),   '4.29TB'],
            
            [0, '1bB',  'Mingalevme\Utils\Exception'],
            [0, '2BB',  'Mingalevme\Utils\Exception'],
            [0, '3biB', 'Mingalevme\Utils\Exception'],
            [0, '4BiB', 'Mingalevme\Utils\Exception'],
            [0, '5.2b', 'Mingalevme\Utils\Exception'],
            [0, 'err',  'Mingalevme\Utils\Exception'],
            
            [0, '7Q',   'Mingalevme\Utils\Exception', 'Invalid size format or unknown/unsupported units'],
        ];
    }
    
    public static function tearDownAfterClass()
    {
        // pass
    }
}

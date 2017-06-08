<?php

namespace Tests\Mingalevme;

class HelpersTest extends \PHPUnit_Framework_TestCase
{
    public function testUrlGetContents()
    {
        $this->assertSame('https://stackoverflow.com/questions/7229885/what-are-the-differences-between-gitignore-and-gitkeep',
            url_get_contents('https://raw.githubusercontent.com/mingalevme/utils/master/tests/.gitkeep?rand=1', $headers));
        $this->assertSame('text/plain; charset=utf-8', $headers['Content-Type']);
        
        try {
            $this->assertSame('https://stackoverflow.com/questions/7229885/what-are-the-differences-between-gitignore-and-gitkeep',
                url_get_contents('https://raw.githubusercontent.com/mingalevme/utils/master/tests/null'));
            $this->fail('ErrorException should have been raised');
        } catch (\ErrorException $e) {
            $this->assertInstanceOf(\ErrorException::class, $e);
        }
        
        try {
            $this->assertSame('https://stackoverflow.com/questions/7229885/what-are-the-differences-between-gitignore-and-gitkeep',
                url_get_contents(
                    'https://raw.githubusercontent.com/mingalevme/utils/master/tests/null',
                    $null,
                    null,
                    2,
                    function ($attemp) { throw new \RuntimeException(); }
                )
            );
            $this->fail('RuntimeException should have been raised');
        } catch (\RuntimeException $e) {
            $this->assertInstanceOf(\RuntimeException::class, $e);
        }
    }
}
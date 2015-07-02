<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-04-18 
 */

namespace Test\Net\Bazzline\Component\Cli\Arguments;

use Net\Bazzline\Component\Cli\Arguments\Arguments;
use PHPUnit_Framework_TestCase;

class ArgumentsTest extends PHPUnit_Framework_TestCase
{
    public function testWithNoArgv()
    {
        $arguments = $this->createArguments();

        $this->assertFalse($arguments->hasArguments());
        $this->assertFalse($arguments->hasLists());
        $this->assertFalse($arguments->hasFlags());
        $this->assertFalse($arguments->hasValues());
    }

    /**
     * @return array
     */
    public function testWithArgumentsProvider()
    {
        return array(
            'empty argv' => array(
                'argv'      => array(),
                'arguments' => array(),
                'flags'     => array(),
                'lists'     => array(),
                'values'    => array()
            ),
            'only file name argument' => array(
                'argv'      => array(
                    __FILE__
                ),
                'arguments' => array(),
                'flags'     => array(),
                'lists'     => array(),
                'values'    => array()
            ),
            'one value' => array(
                'argv'      => array(
                    __FILE__,
                    'foo'
                ),
                'arguments' => array(
                    'foo'
                ),
                'flags'     => array(),
                'lists'     => array(),
                'values'    => array(
                    'foo'
                )
            ),
            'one short trigger' => array(
                'argv'      => array(
                    __FILE__,
                    '-f'
                ),
                'arguments' => array(
                    '-f'
                ),
                'flags'     => array(
                    'f'
                ),
                'lists'     => array(),
                'values'    => array()
            ),
            'one long trigger' => array(
                'argv'      => array(
                    __FILE__,
                    '--foobar'
                ),
                'arguments' => array(
                    '--foobar'
                ),
                'flags'     => array(
                    'foobar'
                ),
                'lists'     => array(),
                'values'    => array()
            ),
            'one short list without quotation mark' => array(
                'argv'      => array(
                    __FILE__,
                    '-f=oo'
                ),
                'arguments' => array(
                    '-f=oo'
                ),
                'flags'     => array(),
                'lists'     => array(
                    'f' => array(
                        'oo'
                    )
                ),
                'values'    => array()
            ),
            'one short list with quotation mark' => array(
                'argv'      => array(
                    __FILE__,
                    '-f="oo"'
                ),
                'arguments' => array(
                    '-f="oo"'
                ),
                'flags'  => array(),
                'lists'     => array(
                    'f' => array(
                        'oo'
                    )
                ),
                'values'    => array()
            ),
            'one long list without quotation mark' => array(
                'argv'      => array(
                    __FILE__,
                    '--foobar=baz'
                ),
                'arguments' => array(
                    '--foobar=baz'
                ),
                'flags'  => array(),
                'lists'     => array(
                    'foobar' => array(
                        'baz'
                    )
                ),
                'values'    => array()
            ),
            'one long list with quotation mark' => array(
                'argv'      => array(
                    __FILE__,
                    '--foobar="baz"'
                ),
                'arguments' => array(
                    '--foobar="baz"'
                ),
                'flags'  => array(),
                'lists'     => array(
                    'foobar' => array(
                        'baz'
                    )
                ),
                'values'    => array()
            ),
            'complex example' => array(
                'argv'      => array(
                    __FILE__,
                    '--foobar="foo"',
                    '--foobar=bar',
                    'foobar',
                    '-f=foo',
                    '-f="bar"',
                    '-b',
                    'foo',
                    '-z',
                    '-flag'
                ),
                'arguments' => array(
                    '--foobar="foo"',
                    '--foobar=bar',
                    'foobar',
                    '-f=foo',
                    '-f="bar"',
                    '-b',
                    'foo',
                    '-z',
                    '-flag'
                ),
                'flags'  => array(
                    'b',
                    'z',
                    'f',
                    'l',
                    'a',
                    'g'
                ),
                'lists'     => array(
                    'foobar'    => array(
                        'foo',
                        'bar'
                    ),
                    'f'         => array(
                        'foo',
                        'bar'
                    )
                ),
                'values'    => array(
                    'foobar',
                    'foo'
                )
            )
        );
    }

    /**
     * @dataProvider testWithArgumentsProvider
     * @param array $argv
     * @param array $expectedArguments
     * @param array $expectedFlags
     * @param array $expectedLists
     * @param array $expectedValues
     */
    public function testWithArguments(
        array $argv,
        array $expectedArguments,
        array $expectedFlags,
        array $expectedLists,
        array $expectedValues
    )
    {
        $arguments = $this->createArguments($argv);

        $this->assertEquals((!empty($expectedArguments)), $arguments->hasArguments());
        $this->assertEquals((!empty($expectedFlags)), $arguments->hasFlags());
        $this->assertEquals((!empty($expectedLists)), $arguments->hasLists());
        $this->assertEquals((!empty($expectedValues)), $arguments->hasValues());

        $this->assertEquals($expectedArguments, $arguments->getArguments());
        $this->assertEquals($expectedFlags, $arguments->getFlags());
        $this->assertEquals($expectedLists, $arguments->getLists());
        $this->assertEquals($expectedValues, $arguments->getValues());

        foreach ($expectedFlags as $name) {
            $this->assertTrue($arguments->hasFlag($name));
        }

        foreach ($expectedLists as $name => $values) {
            $this->assertTrue($arguments->hasList($name));
            $this->assertEquals($values, $arguments->getList($name));
        }
    }

    public function testWithArgumentsAndNotRemovingFirstArgument()
    {
        $argv       = array('foo', 'bar');
        $arguments  = $this->createArguments($argv, false);

        $this->assertEquals($argv, $arguments->getArguments());
        $this->assertEquals($argv, $arguments->getValues());
        $this->assertEmpty($arguments->getFlags());
        $this->assertEmpty($arguments->getLists());
    }

    /**
     * @param null|array $argv
     * @param boolean $removeFirstArgument
     * @return Arguments
     */
    private function createArguments($argv = null, $removeFirstArgument = true)
    {
        return new Arguments($argv, $removeFirstArgument);
    }
}
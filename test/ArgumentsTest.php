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
        return [
            'empty argv' => [
                'argv'      => [],
                'arguments' => [],
                'flags'     => [],
                'lists'     => [],
                'values'    => [],
            ],
            'only file name argument' => [
                'argv'      => [
                    __FILE__
                ],
                'arguments' => [],
                'flags'     => [],
                'lists'     => [],
                'values'    => []
            ],
            'one value with one character' => [
                'argv'      => [
                    __FILE__,
                    '-'
                ],
                'arguments' => [
                    '-'
                ],
                'flags'     => [],
                'lists'     => [],
                'values'    => [
                    '-'
                ]
            ],
            'one value' => [
                'argv'      => [
                    __FILE__,
                    'foo'
                ],
                'arguments' => [
                    'foo'
                ],
                'flags'     => [],
                'lists'     => [],
                'values'    => [
                    'foo'
                ]
            ],
            'one short flag' => [
                'argv'      => [
                    __FILE__,
                    '-f'
                ],
                'arguments' => [
                    '-f'
                ],
                'flags'     => [
                    'f'
                ],
                'lists'     => [],
                'values'    => []
            ],
            'one long flag' => [
                'argv'      => [
                    __FILE__,
                    '--foobar'
                ],
                'arguments' => [
                    '--foobar'
                ],
                'flags'     => [
                    'foobar'
                ],
                'lists'     => [],
                'values'    => []
            ],
            'one short list without quotation mark' => [
                'argv'      => [
                    __FILE__,
                    '-f=oo'
                ],
                'arguments' => [
                    '-f=oo'
                ],
                'flags'     => [],
                'lists'     => [
                    'f' => [
                        'oo'
                    ]
                ],
                'values'    => []
            ],
            'one short list with quotation mark' => [
                'argv'      => [
                    __FILE__,
                    '-f="oo"'
                ],
                'arguments' => [
                    '-f="oo"'
                ],
                'flags'  => [],
                'lists'     => [
                    'f' => [
                        'oo'
                    ]
                ],
                'values'    => []
            ],
            'one long list without quotation mark' => [
                'argv'      => [
                    __FILE__,
                    '--foobar=baz'
                ],
                'arguments' => [
                    '--foobar=baz'
                ],
                'flags'  => [],
                'lists'     => [
                    'foobar' => [
                        'baz'
                    ]
                ],
                'values'    => []
            ],
            'one long list with quotation mark' => [
                'argv'      => [
                    __FILE__,
                    '--foobar="baz"'
                ],
                'arguments' => [
                    '--foobar="baz"'
                ],
                'flags'  => [],
                'lists'     => [
                    'foobar' => [
                        'baz'
                    ]
                ],
                'values'    => []
            ],
            'complex example' => [
                'argv'      => [
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
                ],
                'arguments' => [
                    '--foobar="foo"',
                    '--foobar=bar',
                    'foobar',
                    '-f=foo',
                    '-f="bar"',
                    '-b',
                    'foo',
                    '-z',
                    '-flag'
                ],
                'flags'  => [
                    'b',
                    'z',
                    'f',
                    'l',
                    'a',
                    'g'
                ],
                'lists'     => [
                    'foobar'    => [
                        'foo',
                        'bar'
                    ],
                    'f'         => [
                        'foo',
                        'bar'
                    ]
                ],
                'values'    => [
                    'foobar',
                    'foo'
                ]
            ]
        ];
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

        $this->assertEquals((count($expectedArguments)), $arguments->getNumberOfArguments());
        $this->assertEquals((count($expectedFlags)), $arguments->getNumberOfFlags());
        $this->assertEquals((count($expectedLists)), $arguments->getNumberOfLists());
        $this->assertEquals((count($expectedValues)), $arguments->getNumberOfValues());

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
        $argv       = ['foo', 'bar'];
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

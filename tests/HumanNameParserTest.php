<?php

/**
 * Tests for HumanNameParser.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */

use ChrisUllyott\HumanNameParser;

class HumanNameParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider functionalNameProvider
     */
    public function testName($string, $expected)
    {
        $parser = new HumanNameParser($string);
        $result = $parser->parse();
        $this->assertSame($result, $expected);
    }

    public function functionalNameProvider()
    {
        return [
            [
                'Finn',
                [
                    'full'       => 'Finn',
                    'salutation' => '',
                    'first'      => 'Finn',
                    'middle'     => '',
                    'last'       => '',
                    'suffix'     => ''
                ]
            ],
            [
                'Ryland Jones',
                [
                    'full'       => 'Ryland Jones',
                    'salutation' => '',
                    'first'      => 'Ryland',
                    'middle'     => '',
                    'last'       => 'Jones',
                    'suffix'     => ''
                ]
            ],
            [
                'Doctor martin luther king jr',
                [
                    'full'       => 'Dr. Martin Luther King Jr.',
                    'salutation' => 'Dr.',
                    'first'      => 'Martin',
                    'middle'     => 'Luther',
                    'last'       => 'King',
                    'suffix'     => 'Jr.'
                ]
            ],
            [
                'Kevin C Masters',
                [
                    'full'       => 'Kevin C. Masters',
                    'salutation' => '',
                    'first'      => 'Kevin',
                    'middle'     => 'C.',
                    'last'       => 'Masters',
                    'suffix'     => ''
                ]
            ],
            [
                'Senator John Sidney McCain III',
                [
                    'full'       => 'Sen. John Sidney McCain III',
                    'salutation' => 'Sen.',
                    'first'      => 'John',
                    'middle'     => 'Sidney',
                    'last'       => 'McCain',
                    'suffix'     => 'III'
                ]
            ],
            [
                'Sister Margaret P. Bowles, MPhil.',
                [
                    'full'       => 'Sr. Margaret P. Bowles MPhil',
                    'salutation' => 'Sr.',
                    'first'      => 'Margaret',
                    'middle'     => 'P.',
                    'last'       => 'Bowles',
                    'suffix'     => 'MPhil'
                ]
            ],
            [
                'Mister William Shrader Lawrence Senior',
                [
                    'full'       => 'Mr. William Shrader Lawrence Senior',
                    'salutation' => 'Mr.',
                    'first'      => 'William',
                    'middle'     => 'Shrader',
                    'last'       => 'Lawrence',
                    'suffix'     => 'Senior'
                ]
            ],
            [
                'Mr. Anthony R. Von Fange the 2nd',
                [
                    'full'       => 'Mr. Anthony R. Von Fange 2nd',
                    'salutation' => 'Mr.',
                    'first'      => 'Anthony',
                    'middle'     => 'R.',
                    'last'       => 'Von Fange',
                    'suffix'     => '2nd'
                ]
            ],
            [
                'The honorable Reverend Mark M. Phillips, doctor of divinity',
                [
                    'full'       => 'Hon. Rev. Mark M. Phillips D.Div.',
                    'salutation' => 'Hon. Rev.',
                    'first'      => 'Mark',
                    'middle'     => 'M.',
                    'last'       => 'Phillips',
                    'suffix'     => 'D.Div.'
                ]
            ],
            [
                'Professor Johnathan R Smith, PH.D',
                [
                    'full'       => 'Prof. Johnathan R. Smith Ph.D.',
                    'salutation' => 'Prof.',
                    'first'      => 'Johnathan',
                    'middle'     => 'R.',
                    'last'       => 'Smith',
                    'suffix'     => 'Ph.D.'
                ]
            ],
            [
                "D'arcy Elizabeth Wretzky-Brown",
                [
                    'full'       => "D'arcy Elizabeth Wretzky-Brown",
                    'salutation' => '',
                    'first'      => "D'arcy",
                    'middle'     => 'Elizabeth',
                    'last'       => 'Wretzky-Brown',
                    'suffix'     => ''
                ]
            ],
            [
                "Gerard K. O'Neill",
                [
                    'full'       => "Gerard K. O'Neill",
                    'salutation' => '',
                    'first'      => 'Gerard',
                    'middle'     => 'K.',
                    'last'       => "O'Neill",
                    'suffix'     => ''
                ]
            ]
        ];
    }
}

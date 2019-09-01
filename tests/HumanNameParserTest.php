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
        return array(
            array(
                'Finn',
                array(
                    'full'       => 'Finn',
                    'salutation' => '',
                    'first'      => 'Finn',
                    'middle'     => '',
                    'last'       => '',
                    'suffix'     => ''
                )
            ),
            array(
                'Ryland Jones',
                array(
                    'full'       => 'Ryland Jones',
                    'salutation' => '',
                    'first'      => 'Ryland',
                    'middle'     => '',
                    'last'       => 'Jones',
                    'suffix'     => ''
                )
            ),
            array(
                'Doctor martin luther king jr',
                array(
                    'full'       => 'Dr. Martin Luther King Jr.',
                    'salutation' => 'Dr.',
                    'first'      => 'Martin',
                    'middle'     => 'Luther',
                    'last'       => 'King',
                    'suffix'     => 'Jr.'
                )
            ),
            array(
                'Kevin C Masters',
                array(
                    'full'       => 'Kevin C. Masters',
                    'salutation' => '',
                    'first'      => 'Kevin',
                    'middle'     => 'C.',
                    'last'       => 'Masters',
                    'suffix'     => ''
                )
            ),
            array(
                'Senator John Sidney McCain III',
                array(
                    'full'       => 'Sen. John Sidney McCain III',
                    'salutation' => 'Sen.',
                    'first'      => 'John',
                    'middle'     => 'Sidney',
                    'last'       => 'McCain',
                    'suffix'     => 'III'
                )
            ),
            array(
                'Sister Margaret P. Bowles, MPhil.',
                array(
                    'full'       => 'Sr. Margaret P. Bowles MPhil',
                    'salutation' => 'Sr.',
                    'first'      => 'Margaret',
                    'middle'     => 'P.',
                    'last'       => 'Bowles',
                    'suffix'     => 'MPhil'
                )
            ),
            array(
                'Mister William Shrader Lawrence Senior',
                array(
                    'full'       => 'Mr. William Shrader Lawrence Senior',
                    'salutation' => 'Mr.',
                    'first'      => 'William',
                    'middle'     => 'Shrader',
                    'last'       => 'Lawrence',
                    'suffix'     => 'Senior'
                )
            ),
            array(
                'Mr. Anthony R. Von Fange the 2nd',
                array(
                    'full'       => 'Mr. Anthony R. Von Fange 2nd',
                    'salutation' => 'Mr.',
                    'first'      => 'Anthony',
                    'middle'     => 'R.',
                    'last'       => 'Von Fange',
                    'suffix'     => '2nd'
                )
            ),
            array(
                'The honorable Reverend Mark M. Phillips, doctor of divinity',
                array(
                    'full'       => 'Hon. Rev. Mark M. Phillips D.Div.',
                    'salutation' => 'Hon. Rev.',
                    'first'      => 'Mark',
                    'middle'     => 'M.',
                    'last'       => 'Phillips',
                    'suffix'     => 'D.Div.'
                )
            ),
            array(
                'Professor Johnathan R Smith, PH.D',
                array(
                    'full'       => 'Prof. Johnathan R. Smith Ph.D.',
                    'salutation' => 'Prof.',
                    'first'      => 'Johnathan',
                    'middle'     => 'R.',
                    'last'       => 'Smith',
                    'suffix'     => 'Ph.D.'
                )
            ),
            array(
                "D'arcy Elizabeth Wretzky-Brown",
                array(
                    'full'       => "D'arcy Elizabeth Wretzky-Brown",
                    'salutation' => '',
                    'first'      => "D'arcy",
                    'middle'     => 'Elizabeth',
                    'last'       => 'Wretzky-Brown',
                    'suffix'     => ''
                )
            ),
            array(
                "Gerard K. O'Neill",
                array(
                    'full'       => "Gerard K. O'Neill",
                    'salutation' => '',
                    'first'      => 'Gerard',
                    'middle'     => 'K.',
                    'last'       => "O'Neill",
                    'suffix'     => ''
                )
            )
        );
    }
}

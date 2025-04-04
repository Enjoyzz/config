<?php

/*
 * The MIT License
 *
 * Copyright 2020 Enjoys.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Tests\Enjoys\Config\Parse;

/**
 * Description of INITest
 *
 * @author Enjoys
 */
class INITest extends \PHPUnit\Framework\TestCase
{

    public function test_1()
    {
        $config = <<<INI
; Это пример файла настроек
; Комментарии начинаются с ';', как в php.ini

[first_section]
one = 1
five = 5
animal = BIRD

[second_section]
path = "/usr/local/bin"
URL = "http://www.example.com/~username"

[third_section]
phpversion[] = "5.0"
phpversion[] = "5.1"
phpversion[] = "5.2"
phpversion[] = "5.3"

urls[svn] = "http://svn.php.net"
urls[git] = "http://git.php.net"

INI;

        $parser = new \Enjoys\Config\Parse\INI();
        $parser->addConfigSource($config);
        $this->assertSame(['one' => 1,'five' => 5,'animal' => 'BIRD'], $parser->parse()['first_section']);
    }

     public function test_2(){
         $parser = new \Enjoys\Config\Parse\INI();
         $parser->addConfigSource(__DIR__.'/../fixtures/ini/file1.ini');
         if (\PHP_VERSION_ID < 80300) {
             $this->assertSame('3', $parser->parse()['three']);
             $this->assertSame('4', $parser->parse()['four']);
             $this->assertSame('5', $parser->parse()['five']);
             $this->assertSame('-2', $parser->parse()['negative_two']);
             $this->assertSame('7', $parser->parse()['seven']);
         }else{
             $this->assertSame(3, $parser->parse()['three']);
             $this->assertSame(4, $parser->parse()['four']);
             $this->assertSame(5, $parser->parse()['five']);
             $this->assertSame(-2, $parser->parse()['negative_two']);
             $this->assertSame(7, $parser->parse()['seven']);
         }

         $this->assertSame('\n', $parser->parse()['newline_is']);
         $this->assertSame('Она сказала "Именно моя точка зрения".', $parser->parse()['with quotes']);
         //$this->assertSame($_SERVER['PATH'], $parser->parse()['path']);
         //$this->assertSame('5', $parser->parse()['also_five']);
     }
     public function test_3(){
         $parser = new \Enjoys\Config\Parse\INI();
         $parser->setOptions([
             'process_sections' => false
         ]);
         $parser->addConfigSource(__DIR__.'/../fixtures/ini/file2.ini');
         $this->assertSame('value', $parser->parse()['key']);
         $this->assertSame(true, $parser->parse()['key2']);

         $parser->setOptions([
             'scanner_mode' => \INI_SCANNER_RAW
         ]);
         $this->assertSame('on', $parser->parse()['key2']);

         $parser->setOptions([
             'process_sections' => true
         ]);
         $this->assertSame('value', $parser->parse()['section']['key']);



     }
}

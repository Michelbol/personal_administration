<?php

namespace Tests\Unit\Console\Commands;

use App\Console\Commands\CoverageAnalisys;
use Exception;
use Tests\TestCase;

class CoverageAnalysisTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSuccess()
    {
        $classes = rand((config('app.min_coverage')+1), 100);
        $methods = rand((config('app.min_coverage')+1), 100);
        $lines = rand((config('app.min_coverage')+1), 100);
        $this->makeFile(base_path('coverage.txt'), $classes, $methods, $lines);
        $this->artisan(CoverageAnalisys::class, ['file_name' => base_path('coverage.txt')])->assertExitCode(0);
    }
    public function testError()
    {
        $classes = rand(0, config('app.min_coverage'));
        $methods = rand(0, config('app.min_coverage'));
        $lines = rand(0, config('app.min_coverage'));
        $this->makeFile(base_path('coverage.txt'), $classes, $methods, $lines);
        $this->expectException(Exception::class);
        $this->artisan(CoverageAnalisys::class, ['file_name' => base_path('coverage.txt')])
            ->assertExitCode(0);
    }
    public function testErrorEqual()
    {
        $classes = config('app.min_coverage');
        $methods = config('app.min_coverage');
        $lines = config('app.min_coverage');
        $this->makeFile(base_path('coverage.txt'), $classes, $methods, $lines);
        $this->expectException(Exception::class);
        $this->artisan(CoverageAnalisys::class, ['file_name' => base_path('coverage.txt')])
            ->assertExitCode(0);
    }

    public function makeFile(string $filename, int $classes, int $methods, int $lines)
    {
        file_put_contents($filename, "");
        $myFile = fopen($filename, "a+");
        $text = [
            "\r\r\n",
            "\r\r\n",
            "Code Coverage Report:     \r\r\n",
            "  2020-03-24 17:58:34     \r\r\n",
            "                          \r\r\n",
            " Summary:                 \r\r\n",
            "  Classes: $classes% (14/34) \r\r\n",
            "  Methods: $methods% (32/108)\r\r\n",
            "  Lines:   $lines% (95/336)\r\r\n",
            "\r\r\n",
            "\App\Console::App\Console\Kernel\r\r\n",
            "  Methods:  50.00% ( 1/ 2)   Lines:  75.00% (  3/  4)\r\r\n",
            "\App\Domains\Enums::App\Domains\Enums\Enum\r\r\n",
            "  Methods:  22.22% ( 2/ 9)   Lines:  15.79% (  3/ 19)\r\r\n",
            "\App\Domains\Models::App\Domains\Models\Request\r\r\n",
            "  Methods: 100.00% ( 2/ 2)   Lines: 100.00% (  4/  4)\r\r\n",
            "\App\Domains\Models::App\Domains\Models\User\r\r\n",
            "  Methods: 100.00% ( 3/ 3)   Lines: 100.00% (  3/  3)\r\r\n",
            "\App\Domains\Models\Kaspersky::App\Domains\Models\Kaspersky\Address\r\r\n",
            "  Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  6/  6)\r\r\n",
            "\App\Domains\Models\Kaspersky::App\Domains\Models\Kaspersky\Contact\r\r\n",
            "  Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  3/  3)\r\r\n",
            "\App\Domains\Models\Kaspersky::App\Domains\Models\Kaspersky\Customer\r\r\n",
            "  Methods: 100.00% ( 3/ 3)   Lines: 100.00% (  9/  9)\r\r\n",
            "\App\Domains\Models\Kaspersky::App\Domains\Models\Kaspersky\CustomerAgreement\r\r\n",
            "  Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  2/  2)\r\r\n",
            "\App\Domains\Models\Kaspersky::App\Domains\Models\Kaspersky\Distributor\r\r\n",
            "  Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  1/  1)\r\r\n",
            "\App\Domains\Models\Kaspersky::App\Domains\Models\Kaspersky\ExternalReference\r\r\n",
            "  Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  3/  3)\r\r\n",
            "\App\Domains\Models\Kaspersky::App\Domains\Models\Kaspersky\TermAndCondition\r\r\n",
            "  Methods: 100.00% ( 2/ 2)   Lines: 100.00% (  5/  5)\r\r\n",
            "\App\Exceptions::App\Exceptions\Handler\r\r\n",
            "  Methods:  50.00% ( 1/ 2)   Lines:  66.67% (  2/  3)\r\r\n",
            "\App\Http\Controllers::App\Http\Controllers\Controller\r\r\n",
            "  Methods:  22.22% ( 2/ 9)   Lines:  20.00% (  3/ 15)\r\r\n",
            "\App\Http\Middleware::App\Http\Middleware\SaveRequest\r\r\n",
            "  Methods: 100.00% ( 3/ 3)   Lines: 100.00% ( 13/ 13)\r\r\n",
            "\App\Providers::App\Providers\AppServiceProvider\r\r\n",
            "  Methods:  50.00% ( 1/ 2)   Lines:  55.56% (  5/  9)\r\r\n",
            "\App\Providers::App\Providers\AuthServiceProvider\r\r\n",
            "  Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  4/  4)\r\r\n",
            "\App\Providers::App\Providers\EventServiceProvider\r\r\n",
            "  Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  2/  2)\r\r\n",
            "\App\Providers::App\Providers\RouteServiceProvider\r\r\n",
            "  Methods: 100.00% ( 4/ 4)   Lines: 100.00% ( 14/ 14)\r\r\n",
            "\App\Services::App\Services\CRUDService\r\r\n",
            "  Methods:   0.00% ( 0/10)   Lines:   8.57% (  3/ 35)\r\r\n",
            "\App\Services::App\Services\RequestService\r\r\n",
            "  Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  6/  6)\r\r\n"
        ];
        foreach ($text as $line){
            fwrite($myFile, $line);
        }
        fclose($myFile);
    }
}

<?php


namespace App\Models;

/**
 * Class CoverageAnalysis
 * @package App\Models
 */
class CoverageAnalysis
{
    const classesLine = 1;
    const methodsLine = 2;
    const linesLine = 3;
    private $classes = 0;
    private $methods = 0;
    private $lines = 0;
    private $file;
    private $summaryLine;

    public function __construct(array $file)
    {
        $this->file = $file;
    }

    /**
     *  Will take from file all statistics of coverage
     */
    public function calcStatistics()
    {
        $this->findSummary();
        if(isset($this->summaryLine)){
            $this->classes = $this->cleanNumber($this->findNumberIntoFile($this->getClassesLine()));
            $this->methods = $this->cleanNumber($this->findNumberIntoFile($this->getMethodsLine()));
            $this->lines = $this->cleanNumber($this->findNumberIntoFile($this->getLinesLine()));
        }
    }

    /**
     * Find the summary into Coverage Txt File
     */
    private function findSummary()
    {
        foreach ($this->file as $index => $line){
            $line = strtolower($line);
            if(strpos($line,'summary')){
                $this->summaryLine = $index;
                break;
            }
        }
    }

    /**
     * Return the Line containing the Classes coverage
     * @return string
     */
    private function getClassesLine()
    {
        return $this->file[$this->summaryLine+1];
    }
    /**
     * Return the Line containing the Methods coverage
     * @return string
     */
    private function getMethodsLine()
    {
        return $this->file[$this->summaryLine+2];
    }
    /**
     * Return the Line containing the Lines coverage
     * @return string
     */
    private function getLinesLine()
    {
        return $this->file[$this->summaryLine+3];
    }

    /**
     * Return of the line only the number
     * @param $line
     * @return mixed
     */
    private function findNumberIntoFile($line)
    {
        return collect(explode(' ', $line))->filter(function($value){
            return strpos($value, '%');
        })->first();
    }

    /**
     * Removing special characters into the number
     * @param $number
     * @return float
     */
    private function cleanNumber($number)
    {
        return (float) str_replace('%', '', $number);
    }

    /**
     * Will validate the coverage, using the app min_coverage to compare
     * @return bool
     */
    public function isCoverageValid()
    {
        $minCoverage = config('app.min_coverage');
        return $this->classes > $minCoverage || $this->methods > $minCoverage || $this->lines > $minCoverage;
    }

    /**
     * Get coverage into Classes
     * @return int
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Get coverage into Methods
     * @return int
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Get coverage into Lines
     * @return int
     */
    public function getLines()
    {
        return $this->lines;
    }

}

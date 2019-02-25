<?php

// this is the parent class for all the report subclasses
abstract class Report
{
    // The following is the printToTerminal() method code you MUST use.
    public function printToTerminal()
    {
        echo "Report title: {$this->getTitle()}\n";
        echo "Report generated at: {$this->getGenerationTimeStamp()}\n";
        echo "Report description: {$this->getReportDescription()}\n";
        echo "Report parameters:\n{$this->getParameterString()}\n\n";
        echo "Report result:\n{$this->getReportResultString()}\n";
    }
}
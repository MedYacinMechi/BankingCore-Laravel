<?php

namespace App\Lib;

class Common {

	public static function periodescriptor($period) {
        if      ($period == 0) { return "Daily"; }
        else if ($period == 1) { return "Monthly"; }
        else if ($period == 2) { return "Quartely"; }
        else if ($period == 3) { return "Half-Yearly"; }
        else if ($period == 4) { return "Yearly"; }
    }

    public static function acctypedescriptor($period) {
        if      ($period == 0) { return "Standard"; }
        else if ($period == 1) { return "Minimum Balance"; }
        else if ($period == 2) { return "Line of Credit"; }
        else if ($period == 3) { return "Interest Generating"; }
        else if ($period == 4) { return "Term Deposit"; }
    }
}
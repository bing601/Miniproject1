<?php
/**
 * Created by PhpStorm.
 * User: bing
 * Date: 10/9/18
 * Time: 11:11 AM
 */

main::start("example.csv");
class main  {
    static public function start($filename) {
        $records = csv::getRecords($filename);
        $table = html::generateTable($records);
    }
}
class html {
    public static function generateTable($records) {

        $html ='<!doctype html>';
        $html.='<html lang="en">';
            $html.='<head>';

            $html.='<!-- Required meta tags -->';
            $html.='<meta charset="utf-8">';
            $html.='<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';

            $html.='<!-- Bootstrap CSS -->';
            $html.='<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">';
            $html.='</head>';

            $html.='<body>';

        $html.='<table class="table table-striped">';

        $count = 0;
        foreach ($records as $record) {
            if($count == 0) {
                $array = $record->returnArray();
                $fields = array_keys($array);
                $values = array_values($array);

                $html.='<thead class="thead-dark">';
                $html.='<tr>';
                foreach($fields as $field){
                    $html.='<th>'.htmlspecialchars($field).'</th>';
                }
                $html.='</tr>';
                $html.='</thead>';

                $html.='<tr>';
                foreach($values as $value){
                    $html.='<td>'.htmlspecialchars($value).'</td>';
                }
                $html.='</tr>';

            } else {
                $array = $record->returnArray();
                $values = array_values($array);
                $html.='<tr>';
                foreach($values as $value){
                    $html.='<td>'.htmlspecialchars($value).'</td>';
                }
                $html.='</tr>';
            }
            $count++;
        }
        $html.='</table>';

        $html.='<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>';
        $html.='<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>';
        $html.='<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>';

        $html.='</body>';

        print $html;
    }
}

class csv {
    static public function getRecords($filename) {
        $file = fopen($filename,"r");
        $fieldNames = array();
        $count = 0;
        while(! feof($file))
        {
            $record = fgetcsv($file);
            if($count == 0) {
                $fieldNames = $record;
            } else {
                $records[] = recordFactory::create($fieldNames, $record);
            }
            $count++;
        }
        fclose($file);
        return $records;
    }
}
class record {
    public function __construct(Array $fieldNames = null, $values = null )
    {
        $record = array_combine($fieldNames, $values);
        foreach ($record as $property => $value) {
            $this->createProperty($property, $value);
        }
    }
    public function returnArray() {
        $array = (array) $this;
        return $array;
    }
    public function createProperty($name = 'first', $value = 'Bing') {
        $this->{$name} = $value;
    }
}
class recordFactory {
    public static function create(Array $fieldNames = null, Array $values = null) {
        $record = new record($fieldNames, $values);
        return $record;
    }
}
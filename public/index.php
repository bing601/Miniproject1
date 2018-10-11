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

        $html='<table>';

        $count = 0;
        foreach ($records as $record) {
            if($count == 0) {
                $array = $record->returnArray();
                $fields = array_keys($array);
                $values = array_values($array);

                $html.='<tr>';
                foreach($fields as $field){
                    $html.='<th>'.htmlspecialchars($field).'</th>';
                }
                $html.='</tr>';

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
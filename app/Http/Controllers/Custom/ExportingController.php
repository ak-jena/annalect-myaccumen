<?php
namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;

class ExportingController extends Controller {
    
    private $filename;
    private $data;

    // Old version: Delete it in the future if the new works
    // public function downloadCSV(){
    //     ob_clean();
    //     header('Pragma: public');
    //     header('Expires: 0');
    //     header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    //     header('Cache-Control: private', false);
    //     header('Content-Type: text/csv');
    //     header('Content-Disposition: attachment; filename="'.$this->filename.'.csv";');
        
    //     $f = fopen('php://output', 'w');
    //     fputcsv($f, array_keys((array)$this->data[0]['attributes']));
    //     foreach ($this->data as $line) {
    //         fputcsv($f, (array)$line['attributes']);
    //     }
    //     fclose($f);
    //     ob_flush();
    //     exit;
    // }

    public function downloadCSV(){
        ob_clean();
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$this->filename.'.csv";');
        
        $f = fopen('php://output', 'w');
        fwrite($f, "\xef\xbb\xbf");         // fixes "pound" sign in CSV

        fputcsv($f, array_keys((array)$this->data[0]));
        foreach ($this->data as $row) {
            fputcsv($f, (array)$row);
        }
        fclose($f);
        ob_flush();
        exit;
    }

    /* It prepares and returns the CSV in a stream so it can be send by e-mail */
    public function prepareCSV(){
        if (!$f = fopen('php://temp', 'w+')) return FALSE;
    
        $f = $this->writeCSV($f);
    
        // Place stream pointer at beginning
        rewind($f);

        // Return the data
        return stream_get_contents($f);
    }

    public function writeCSV($f){
        $first = true;
        $num = 0;

        foreach ($this->data as $row) {
            $num++;
            $row = (array)$row; // Casting into from stdClass to Array
            if($first){
                fputcsv($f,array_keys($row));
                $first = false;
            }
            fputcsv($f, $row);
        }
        // If no rows, blank csv
        if($num == 0) exit;
        return $f;
    }
    
    public function setFilename($filename){
        $this->filename = $filename;
    }
    
    public function getFilename(){
        return $this->filename;
    }
    
    public function setData($data){
        $this->data = $data;
    }
    
    public function getData(){
        return $this->data;
    }
}

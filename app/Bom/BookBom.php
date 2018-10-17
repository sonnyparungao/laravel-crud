<?php
/**
 * Book Bom class for book module. This class contains all business logic for book module.
 *
 * @author sonny.parungao 
 * @date 15/10/2018
 */

namespace App\Bom;

use App\Models\Book;

use Response;
use DB;
use File;

//library to export data to xml file
use XMLWriter;

//library to export data to csv file
use Excel;

class BookBom {

    private $books = null;

    /*
     * @param object $request
     * @return Resultset
     */
    public function processSearch($request) {
        $books =  $this->books;

        if(isset($request->ddSearchType) && $request->filled('ddSearchType')) {
            if(isset($request->txtKeywords) && $request->filled('txtKeywords')) {
                if($request->ddSearchType==1) {
                    $book = $book->where('title', 'like', ''.$request->txtKeywords.'%');
                } else if($request->ddSearchType==2) {
                    $book = $book->where('author', 'like', ''.$request->txtKeywords.'%');
                }
            }
        }
        return $book->sortable()->paginate(5);
    }

    /*
     * @param object $request
     * @return xml file
    */
    public function processExportToXml($request) {

        $books =  $this->books->get();
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->startDocument();
        $xml->startElement('books');
        foreach($books as $book) {
            $xml->startElement('data');
            if($request->ddColumn==1) {
                $xml->writeAttribute('title', $book->title);
            } else if($request->ddColumn==2) {
                $xml->writeAttribute('author', $book->author);
            } else {
                $xml->writeAttribute('title', $book->title);
                $xml->writeAttribute('author', $book->author);
            }
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endDocument();
        $content = $xml->outputMemory();

        $fileName = "exported_data_" . strtotime(date('Y-m-d H:i:s')) . '.xml';
        File::put(public_path('/download/'.$fileName),$content);
        return Response::download(public_path('/download/'.$fileName));
    }


    /*
    * @param object $request
    * @return csv file
   */
    public function processExportToCsv($request) {
        $books = $this->books->get();
        $fileName = "exported_data_" . strtotime(date('Y-m-d H:i:s'));

        $bookArray = array();
        foreach($books as $book) {
            if($request->ddColumn==1) {
                $bookArray[] = array("author" => $book->author);
            } else if($request->ddColumn==2) {
                $bookArray[] = array("title" => $book->title);
            } else {
                $bookArray[] = array("author" => $book->author, "title" => $book->title);
            }
        }

        return Excel::create($fileName, function($excel) use($bookArray) {
            $excel->sheet('Sheet 1', function($sheet) use($bookArray) {
                $sheet->fromArray($bookArray,null,'A1',true);
            });
        })->export('csv');

    }

    public function setBookRecords() {
        $this->books = $this->getBookRecords();
    }

    public function getBookRecords() {
        return Book::where('flag',1);
    }
    


}
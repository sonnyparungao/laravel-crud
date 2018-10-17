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
use Symfony\Component\HttpFoundation\StreamedResponse;


class BookBom {

    /*
     * @param object $request
     * @return Resultset
     */
    public function processSearch($request) {
        $book = $this->retrieveBookRecords();

        if(isset($request->ddSearchType) && $request->filled('ddSearchType')) {
            if(isset($request->txtKeywords) && $request->filled('txtKeywords')) {
                if($request->ddSearchType==1) {
                    $book = $book->where('title', 'like', ''.$request->txtKeywords.'%');
                } else if($request->ddSearchType==2) {
                    $book = $book->where('author', 'like', ''.$request->txtKeywords.'%');
                }
            }
        }
        $book = $book->orderBy('book_id','desc');
        return $book->sortable()->paginate(5);
    }

    /*
     * @param object $request
     * @return xml file
    */
    public function processExportToXml($request) {
        $books =  $this->retrieveBookRecords()->get();
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
     * Exporting of Book Data in csv format
     * Used chunk for exporting large data
     *
    * @param object $request
    * @return csv file
     *
   */
    public function processExportToCsv($request) {

        //condition for export filter

        $header = $this->processFormattingOfCsvHeader($request);

        $books =  $this->retrieveBookRecords()->orderBy('book_id','desc');
        return Excel::create('Report', function($excel) use ($books,$header,$request) {
            $excel->sheet('report', function($sheet) use($books,$header,$request) {
                $sheet->appendRow($header);
                $books->chunk(20, function($rows) use ($sheet,$request)
                {
                    foreach ($rows as $row)
                    {
                        $sheet->appendRow($this->processFormattingOfCsvData($request,$row));
                    }
                });
            });
        })->download('csv');


    }

    /*
    *
    * @return Book resultset
   */
    public function retrieveBookRecords() {
        return Book::select('book_id','title','author')->where('flag',1);
    }
    /*
    * @param object $request
    * @return  array
   */
    private function processFormattingOfCsvHeader($request) {
        $header = null;
        if($request->ddColumn==1) {
            $header = array('title');
        } else if($request->ddColumn==2) {
            $header = array('author');
        } else {
            $header = array('title','author');
        }
        return $header;
    }
    /*
    * @param object $request
    * @return  array
   */
    private function processFormattingOfCsvData($request,$row) {
        $arrData = null;
        if($request->ddColumn==1) {
            $arrData = array($row->title);
        } else if($request->ddColumn==2) {
            $arrData = array($row->author);
        } else {
            $arrData = array($row->title,$row->author);
        }
        return $arrData;
    }

}
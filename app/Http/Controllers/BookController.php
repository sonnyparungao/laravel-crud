<?php

/**
 * Book Controller are composed of methods for manipulating book records like
 * listing of books, updating of books, deleting of books and exporting of books
 *
 * @author sonny.parungao
 * @date 15/10/2018
 */
namespace App\Http\Controllers;

use App\Models\Book;
use App\Bom\BookBom;

use Illuminate\Http\Request;



class BookController extends Controller
{
    //@var Object $bookBom
    private $bookBom = null;

    public function __construct()
    {
        //instantiate BookBom Object
        $this->bookBom = new BookBom();
    }

    /**
     * Display all books record
     * Filtering of books
     *
     * @param  Request  $request
     * @return Resultset
     */
    public function index(Request $request)
    {

        $books = $this->bookBom->processSearch($request);
        return view('book.index',compact('books'));
    }



    /**
     * Store a new book.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
            'author' => 'required'
        ]);

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
        ]);

        return redirect('/books')->with('success', 'New book has been created.');
    }


    /**
     * Update book existing record.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {

        //Validate
        $this->validate($request, [
            'author' => 'required'
        ]);

        Book::where("book_id",$request->book_id)->update(["author" => $request->author]);
        return redirect('/books')->with('success', 'You have successfully updated a record.');

    }
    /**
     * Deleting a book record using flag, update flag status to 0=deleted instead of deleting records.
     *
     * @param  Request  $request
     * @return Response
     */
    public function destroy(Request $request)
    {
        Book::where("book_id",$request->book_id)->update(["flag" => 0]);
        return redirect('/books')->with('success', 'You have successfully deleted a record.');
    }

    /**
     *  Exporting of books record in csv and xml format
     *
     * @param  Request  $request
     * @return Response
     */
    public function export(Request $request) {

        if(isset($request->btnExportToCsv)) {
            return $this->bookBom->processExportToCsv($request);
        } else if(isset($request->btnExportToXml)) {
            return $this->bookBom->processExportToXml($request);
        }

    }

}
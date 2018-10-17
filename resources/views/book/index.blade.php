@extends('templates.default')
<style type="text/css">
    .cardContent {
        margin-top:100px;
    }
</style>

@section('content')
  <div class="card cardContent">
      <div class="card-header">
        Book Listing
      </div>


      <div class="card-body">

          <div class="card">
              <div class="card-body">
              <form method="get" name="frmSearch" id="frmSearch" action="{{url('books')}}">
                  <div class="form-row align-items-center">
                      <div class="col-sm-1 my-1">
                          <label>Search By: </label>
                      </div>
                      <div class="col-sm-4 my-1">
                          <select name="ddSearchType" id="ddSearchType" class="form-control" >
                              <option value=""></option>
                              <option value="1" {{request('ddSearchType') == 1  ? 'selected' : ''}}>Title</option>
                              <option value="2" {{request('ddSearchType') == 2  ? 'selected' : ''}}>Author</option>
                          </select>
                      </div>
                      <div class="col-sm-4 my-1">
                            <input type="text" name="txtKeywords" id="txtKeywords" class="form-control"  placeholder="Keywords" value="{{ request('txtKeywords') }}">
                      </div>

                      <div class="col-auto my-1">
                          <button type="submit" name="btnSearch" class="btn btn-primary">Search</button>
						  <a href="{{url('books')}}" class="btn btn-danger">Clear Filters</a>
                      </div>
                  </div>
              </form>
              <form method="post" name="frmExport" id="frmExport" action="{{url('export')}}">
                  {!! csrf_field() !!}
                  <div class="form-row align-items-center">
                      <div class="col-auto my-1">
                          <label>Export Data:</label>
                      </div>
                      <div class="col-auto my-1">
                          <select name="ddColumn" id="ddColumn" class="form-control" >
                              <option value="1" {{request('ddColumn') == 1  ? 'selected' : ''}}>Title Only</option>
                              <option value="2" {{request('ddColumn') == 2  ? 'selected' : ''}}>Author Only</option>
                              <option value="3" {{request('ddColumn') == 3  ? 'selected' : ''}}>Export Both</option>
                          </select>
                      </div>
                      <div class="col-auto my-1">
                          <button type="submit" name="btnExportToCsv" class="btn btn-success" value="Export to CSV">Export to CSV</button>
                          <button type="submit" name="btnExportToXml" class="btn btn-info" value="Export to XML">Export to XML</button>
                      </div>
                  </div>
              </form>
          </div>
         </div>
          <br />

          @if(session()->has('success'))
               <div class="alert alert-success">
                 {{ session()->get('success') }}
              </div>
          @endif

             @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
              @endif


          <!-- Button trigger modal -->
          <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addBookModal">
              Create Book
          </button>
          <br /><br />
          @if(count($books) > 0)
              <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Book Id</th>
                  <th>@sortablelink('title')</th>
                  <th>@sortablelink('author')</th>
                  <th>Date Created</th>
                  <th>Date Updated</th>
                  <th align="center">Action</th>
                </tr>
              </thead>
              <tbody>
                
                @foreach($books as $book)
                <tr>
                  <td>{{$book->book_id}}</td>
                  <td>{{$book->title}}</td>
                  <td>{{$book->author}}</td>
                  <td>{{$book->created_at}}</td>
                  <td>{{$book->updated_at}}</td>
                  <td align="center">
                      <a data-toggle="tooltip" title="Edit Book" href="#" class="showEditBookModal" id="{{$book->book_id}}|{{$book->title}}|{{$book->author}}">
                          <i class="fa fa-pencil-square-o"></i>
                      </a>
                      <a data-toggle="tooltip" title="Delete Book" href="#" class="showDeleteBookModal" id="{{$book->book_id}}">
                          <i class="fa fa-trash"></i>
                      </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          <div class="pull-right">
            {!! $books->appends(\Request::except('page'))->render() !!}
          </div>
          @else
                <div class="text-center"">No Record has been retrieved.</div>
          @endif
      </div>
  </div>




  <!-- Create Book Modal -->
  <div class="modal fade" id="addBookModal" tabindex="-1" role="dialog" aria-labelledby="addBookModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="addBookModal">Create New Book Form</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <form method="post" name="frmCreateBook" id="frmCreateBook" action="{{url('books')}}">
                  <div class="modal-body">
                          {!! csrf_field() !!}
                          <div class="form-group">
                              <label>Book Title</label>
                              <input type="text" class="form-control" id="title" name="title" required="true">
                          </div>
                          <div class="form-group">
                              <label>Author Name</label>
                              <input type="text" class="form-control" id="author" name="author" required="true">
                          </div>

                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" name="saveBtn" class="btn btn-primary">Save</button>
                  </div>
              </form>
          </div>
      </div>
  </div>



  <!-- Update Book Modal -->
  <div class="modal fade" id="editBookModal" tabindex="-1" role="dialog" aria-labelledby="editBookModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="editBookModal">Edit Book Form</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <form method="post" name="frmEditBook" id="frmEditBook" action="{{url('books/update')}}">
                  <input type="hidden" name="book_id" id="book_id" value="" />
                  <div class="modal-body">
                      {!! csrf_field() !!}
                      <div class="form-group">
                          <label>Book Title</label>
                          <span id="title"></span>
                      </div>
                      <div class="form-group">
                          <label>Author Name</label>
                          <input type="text" class="form-control" id="author" name="author" required="true">
                      </div>

                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                  </div>
              </form>
          </div>
      </div>
  </div>

  <!-- Delete Book Modal -->
  <div class="modal fade" id="deleteBookModal" tabindex="-1" role="dialog" aria-labelledby="deleteBookModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">

              <form method="post" name="frmDeleteBook" id="frmDeleteBook" action="{{url('books/destroy')}}">
                  <input type="hidden" name="book_id" id="book_id" value="" />
                  <div class="modal-body">
                      {!! csrf_field() !!}
                      <div class="form-group">
                          <label>Are you sure you want to delete this book?</label>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Yes</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
  <script type="text/javascript">

      /*
        display  edit book modal form
        and parse the value from the id of the button and set to the ff fields
       */

      $(document).ready(function(){
          $(".showEditBookModal").click(function(){
              var bookDetails = $(this).attr('id').split("|");
              $("input#book_id").val(bookDetails[0]);
              $("span#title").text(bookDetails[1]);
              $("#frmEditBook input#author").val(bookDetails[2]);
              $("#editBookModal").modal();
          });
      });
      /*
       display  delete book modal form
       and parse the value from the id of the button and set to the ff field
       */
      $(document).ready(function(){
          $(".showDeleteBookModal").click(function(){
              var bookDetails = $(this).attr('id').split("|");
              $("#frmDeleteBook input#book_id").val(bookDetails[0]);
              $("#deleteBookModal").modal();
          });
      });

  </script>
@stop



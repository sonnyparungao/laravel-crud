<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//service provider for sorting column
use Kyslik\ColumnSortable\Sortable;

class Book extends Model
{
	use Sortable;

    protected $fillable = ['title', 'author'];
    // trait adds Sortable scope to the models
    //@var array $sortable
    public $sortable = ['title','author'];
}

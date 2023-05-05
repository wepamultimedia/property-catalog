<?php
namespace Wepa\PropertyCatalog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CategoryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'description'];
    protected $table = 'procat_categories_translations';
}

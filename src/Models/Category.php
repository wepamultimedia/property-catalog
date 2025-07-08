<?php

namespace Wepa\PropertyCatalog\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Wepa\Core\Http\Traits\Backend\PositionModelTrait;
use Wepa\Core\Http\Traits\SeoModelTrait;
use Wepa\Core\Models\Seo;
use Wepa\PropertyCatalog\Database\Factories\CategoryFactory;
use Wepa\PropertyCatalog\Http\Controllers\Frontend\PropertyController;

/**
 * Wepa\PropertyCatalog\Models\Category
 *
 * @property int $id
 * @property int $seo_id
 * @property int $type_id
 * @property int $position
 * @property string $name
 * @property string $description
 * @property int $published
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Property> $properties
 * @property-read Type $type
 * @property-read int|null $properties_count
 * @property-read CategoryTranslation|null $translation
 * @property-read Collection<int, CategoryTranslation> $translations
 * @property-read int|null $translations_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Category listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Category translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category withTranslation()
 *
 * @mixin \Eloquent
 */
class Category extends Model
{
    use HasFactory;
    use PositionModelTrait;
    use SeoModelTrait;
    use Translatable;

    public array $translatedAttributes = ['name', 'description'];

    public $translationForeignKey = 'category_id';

    protected $fillable = [
        'seo_id',
        'type_id',
        'name',
        'position',
        'published',
    ];

    protected $casts = [
        'seo_id' => 'integer',
        'type_id' => 'integer',
        'position' => 'integer',
        'published' => 'boolean',
    ];

    protected $table = 'procat_categories';

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'category_id', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }

    public function seoDefaultParams(): array
    {
        return [
            'package' => 'property-catalog',
            'controller' => PropertyController::class,
            'action' => 'index',
            'page_type' => 'article',
            'change_freq' => Seo::CHANGE_FREQUENCY_WEEKLY,
            'priority' => '0.8',
            'canonical' => true,
        ];
    }

    public function seoRequestParams(): array
    {
        return $this->id ? ['category_id' => $this->id] : [];
    }

    public function seoRouteParams(): array
    {
        return [];
    }
}

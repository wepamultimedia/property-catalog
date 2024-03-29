<?php

namespace Wepa\PropertyCatalog\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Wepa\PropertyCatalog\Http\Resources\PropertyResource;
use Wepa\PropertyCatalog\Models\Property;

class PropertyController extends Controller
{
    public function highlights(?string $locale = null): AnonymousResourceCollection
    {
        if ($locale) {
            app()->setLocale($locale);
        }

        return PropertyResource::collection(
            Property::where('highlighted', true)
                ->where('published', true)
                ->orderBy('position', 'desc')
                ->get());
    }

    public function index(Request $request, ?string $locale = null): AnonymousResourceCollection
    {
        if ($locale) {
            app()->setLocale($locale);
        }

        $query = Property::where('published', true);

        if ($request['orderByName']) {
            $query->orderByTranslation('name');
        } else {
            $query->orderBy('position', 'desc');
        }

        return PropertyResource::collection($query->get());
    }
}

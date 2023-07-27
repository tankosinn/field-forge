<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Visit;
use App\Models\VisitFile;
use App\Models\VisitLog;
use App\Models\VisitStockOut;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Closure;


class VisitController extends Controller
{
    public function create($route)
    {
        /**
         * Range of this week.
         */
        $rangeWeek = rangeWeek(date("Y-m-d"));

        /**
         * Is visit created? 
         */
        $visit = Visit::where('employee_route', $route)
            ->where('created_at', '>=', $rangeWeek['start'])
            ->where('created_at', '<=', $rangeWeek['end'])
            ->first();

        if (!$visit) {
            abort(404);
        }

        return view('visit', [
            'visit' => $visit,
            'images' => VisitFile::where('visit', $visit->id)->get()->groupBy('type')->all(),
            'products' => Product::all(),
            'stock_outs' => VisitStockOut::where('visit', $visit->id)->get()->groupBy('product')->all(),
        ]);
    }

    public function new()
    {
        $attributes = request()->validate([
            'employee_route' => ['required', 'exists:employee_routes,id'],
            'store' => ['required', 'exists:stores,id'],
            'store_branch' => ['required', 'exists:store_branches,id'],
            'day' => ['required', 'min:0', 'max:5'],
            'latitude' => ['required', 'between:-90,90'],
            'longitude' => ['required', 'between:-180,180']
        ], [
            'latitude.required' => 'Konumunuzunu açmadan ziyarete başlayamazsınız.',
            'longitude.required' => 'Konumunuzunu açmadan ziyarete başlayamazsınız.'
        ]);

        $visit = Visit::create([
            'employee_route' => $attributes['employee_route'],
            'employee' => Auth::user()->id,
            'store' => $attributes['store'],
            'store_branch' => $attributes['store_branch'],
            'day' => $attributes['day'],
            'status' => '1'
        ]);

        VisitLog::create([
            'employee' => Auth::user()->id,
            'visit' => $visit->id,
            'type' => 1,
            'latitude' => $attributes['latitude'],
            'longitude' => $attributes['longitude']
        ]);

        session()->flash('success', 'Ziyarete başlandı.');

        return response()->json([
            "status" => true
        ]);
    }

    public function image_store()
    {
        $request = request();

        $attributes = $request->validate([
            'image' => 'required|image',
            'visit' => ['required', 'exists:visits,id'],
            'type' => ['required'],
            'latitude' => ['required', 'between:-90,90'],
            'longitude' => ['required', 'between:-180,180']
        ], [
            'latitude.required' => 'Konumunuzunu açmadan ziyarete başlayamazsınız.',
            'longitude.required' => 'Konumunuzunu açmadan ziyarete başlayamazsınız.'
        ]);

        $image = $request->file('image');
        $file_name = time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/storage/visit');

        Image::make($image->getRealPath())->resize(1024, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $file_name);

        $relation = VisitFile::create([
            'visit' => $attributes['visit'],
            'type' => $attributes['type'],
            'file' => $file_name
        ]);

        VisitLog::create([
            'employee' => Auth::user()->id,
            'visit' => $attributes['visit'],
            'rel_id' => $relation->id,
            'type' => 2,
            'latitude' => $attributes['latitude'],
            'longitude' => $attributes['longitude']
        ]);

        return response()->json([
            "status" => true,
            "file_name" => $file_name
        ]);
    }

    public function store()
    {
        $request = request();

        $request->validate([
            'id' => ['required', 'exists:visits,id'],
            'latitude' => ['required', 'between:-90,90'],
            'longitude' => ['required', 'between:-180,180']
        ], [
            'id.required' => 'Ziyaret kimliği bulunamadı.',
            'latitude.required' => 'Konumunuzunu açmadan ziyareti tamamlayamazsınız.',
            'longitude.required' => 'Konumunuzunu açmadan ziyarete tamamlayamazsınız.'
        ]);

        $files = VisitFile::where('visit', $request->id)->get()->groupBy('type')->all();

        $request->request->add(["invoice" => isset($files[1]), "entrance" => isset($files[2]), "exit" => isset($files[3])]);

        $request->validate([
            'invoice' => [
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!$value) {
                        $fail("Fiş fotoğrafı zorunludur.");
                    }
                },
            ],
            'entrance' => [
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!$value) {
                        $fail("Giriş fotoğrafı zorunludur.");
                    }
                },
            ],
            'exit' => [
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!$value && (!isset(request()->note) || empty(request()->note))) {
                        $fail("Çıkış fotoğrafı veya not zorunludur.");
                    }
                },
            ],
        ]);

        $visit = Visit::where('id', $request->id)->first();

        VisitLog::create([
            'employee' => Auth::user()->id,
            'visit' => $request->id,
            'type' => ($visit->status == 2 ? 4 : 3),
            'note' => $request?->note,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        $visit->update(['status' => 2]);

        VisitStockOut::where('visit', $request->id)->delete();

        if (isset($request->stock_outs)) {
            foreach ($request->stock_outs as $stock_out) {
                VisitStockOut::create([
                    'visit' => $request->id,
                    'product' => $stock_out,
                ]);
            }
        }


        session()->flash('success', 'Ziyaret tamamlandı.');

        return response()->json([
            "status" => true
        ]);
    }
}

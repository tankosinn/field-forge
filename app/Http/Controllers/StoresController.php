<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreBranch;

class StoresController extends Controller
{
    public function list()
    {
        return view('pages.stores.list', ['stores' => Store::all()]);
    }

    public function create($slug = null)
    {
        return view('pages.stores.store', ['store' => $slug ? Store::where('slug', $slug)->first() : null]);
    }

    public function store()
    {
        $request = request();

        $store = Store::where('id', $request->id)->first();

        $attributes = $request->validate([
            'name' => ['required']
        ], [
            'name.required' => 'Satış noktasının adı boş bırakılamaz.'
        ]);

        $attributes['slug'] = null;

        $branchAttributes = $request->validate([
            'branch.name.*' => ['required'],
            'branch.address.*' => ['required'],
            'update_branch.id.*' => ['required'],
            'update_branch.name.*' => ['required'],
            'update_branch.address.*' => ['required']
        ], [
            'branch.name.*.required' => 'Şube Adı boş bırakılamaz.',
            'branch.address.*.required' => 'Şube Adresi boş bırakılamaz.',
            'update_branch.id.*.required' => 'Şube Adı boş bırakılamaz.',
            'update_branch.name.*.required' => 'Düzenlenen şube Adı boş bırakılamaz.',
            'update_branch.address.*.required' => 'Düzenlenen şube Adresi boş bırakılamaz.'
        ]);

        if ($store) {
            $store->update($attributes);
        } else {
            $store = Store::create($attributes);
        }

        if (isset($branchAttributes['branch'])) {
            foreach ($branchAttributes['branch']['name'] as $index => $name) {
                StoreBranch::create([
                    'parent_id' => $store->id,
                    'name' => $branchAttributes['branch']['name'][$index],
                    'address' => $branchAttributes['branch']['address'][$index]
                ]);
            }
        }

        if (isset($branchAttributes['update_branch'])) {
            foreach ($branchAttributes['update_branch']['name'] as $index => $name) {
                StoreBranch::where('id', $branchAttributes['update_branch']['id'][$index])->update([
                    'name' => $branchAttributes['update_branch']['name'][$index],
                    'address' => $branchAttributes['update_branch']['address'][$index]
                ]);
            }
        }

        session()->flash('success', 'Kaydedildi.');

        return response()->json([
            "status" => true
        ]);
    }
}

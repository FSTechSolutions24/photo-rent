<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        return view('superadmin.plans.index');
    }

    public function create()
    {
        return view('superadmin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatePlan($request);

        $data['photographer_id'] = Auth::id();
        SubscriptionPlan::create($data);

        return redirect()->route('superadmin.plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit($id){
        $plan = SubscriptionPlan::findOrFail($id);
        return view('superadmin.plans.edit', compact('plan'));   
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $data = $this->validatePlan($request, $plan->id);

        $plan->update($data);

        return redirect()->route('superadmin.plans.index')->with('success', 'Plan updated successfully.');
    }

    public function getData(){

        $eloquent = SubscriptionPlan::query();

        return DataTables::eloquent($eloquent)
         ->addColumn('actions', function ($model) {
            $editUrl = route('superadmin.plans.edit', $model->id);
            return '<a href="'.$editUrl.'" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-edit"></i>
            </a>';
        })
        ->addIndexColumn()
        ->rawColumns(['actions'])
        ->make(true);
    }

    protected function validatePlan(Request $request, $id = null)
    {
        return $request->validate([
            'name' => ['required','string','max:255','min:3'],
            'price' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
        ]);
    }

}

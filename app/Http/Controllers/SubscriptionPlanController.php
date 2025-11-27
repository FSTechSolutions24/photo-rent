<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanLine;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\HelperTrait;

class SubscriptionPlanController extends Controller
{
    use HelperTrait;

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
        $data = ($request->all());
        $items_array = json_decode($data['items'], true);     
        $data = $this->validatePlan($request);

        $data['photographer_id'] = Auth::id();
        $id = SubscriptionPlan::create($data)->id;

        $this->storeDynamicTableRecords(SubscriptionPlanLine::class,'subscription_plan_id',$id,$items_array);

        return redirect()->route('superadmin.plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit($id){
        $plan = SubscriptionPlan::with('lines')->findOrFail($id);
        return view('superadmin.plans.edit', compact('plan'));   
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $data = $this->validatePlan($request, $plan->id);

        $plan->update($data);

        $data = ($request->all());

        $items_array = json_decode($data['items'], true);    

        $this->storeDynamicTableRecords(SubscriptionPlanLine::class,'subscription_plan_id',$plan->id,$items_array);

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
            'most_popular' => ['nullable', 'in:0,1']
        ]);
    }

}

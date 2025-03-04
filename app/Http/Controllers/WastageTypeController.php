<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\WastageTypeUtil;
use App\WastageType;
use Yajra\DataTables\Facades\DataTables;

class WastageTypeController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $wastageTypeUtil;

    /**
     * Constructor
     *
     * @param  wastageTypeUtil  $wastageTypeUtil
     * @return void
     */
    public function __construct(WastageTypeUtil $wastageTypeUtil)
    {
        $this->wastageTypeUtil = $wastageTypeUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! auth()->user()->can('wastage_type_rate.view') && ! auth()->user()->can('wastage_type_rate.create')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $wastageType_rates = WastageType::where('business_id', $business_id)
                        ->select(['name', 'id']);

            return Datatables::of($wastageType_rates)
                ->addColumn(
                    'action',
                    '@can("wastage_type_rate.update")
                    <button data-href="{{action(\'App\Http\Controllers\WastageTypeController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_wastage_type_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                        &nbsp;
                    @endcan
                    @can("wastage_type_rate.delete")
                        <button data-href="{{action(\'App\Http\Controllers\WastageTypeController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_wastage_type_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                    @endcan'
                )
                ->removeColumn('id')
                ->rawColumns([1])
                ->make(false);
        }

        return view('wastage_type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->can('wastage_type.create')) {
            abort(403, 'Unauthorized action.');
        }

        return view('wastage_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('wastage_type.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['name']);
            $input['business_id'] = $request->session()->get('user.business_id');
            $input['created_by'] = $request->session()->get('user.id');

            $wastageType_rate = WastageType::create($input);
            $output = ['success' => true,
                'data' => $wastageType_rate,
                'msg' => __('wastage_type.added_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! auth()->user()->can('wastage_type.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $wastage_type = WastageType::where('business_id', $business_id)->find($id);

            return view('wastage_type.edit')
                ->with(compact('wastage_type'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('wastage_type.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['name']);
                $business_id = $request->session()->get('user.business_id');

                $wastage_type = WastageType::where('business_id', $business_id)->findOrFail($id);
                $wastage_type->name = $input['name'];
                $wastage_type->save();

                $output = ['success' => true,
                    'msg' => __('wastage_type.updated_success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! auth()->user()->can('wastage_type.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $wastage_type = WastageType::where('business_id', $business_id)->findOrFail($id);
                $wastage_type->delete();

                $output = ['success' => true,
                    'msg' => __('wastage_type.deleted_success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }
}

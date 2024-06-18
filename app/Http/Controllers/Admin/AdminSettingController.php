<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\Tax;

class AdminSettingController extends Controller
{

    public function __construct(Request $request)
    {
        $this->data['title'] = 'Settings';
        $this->data['settings'] = Setting::all();
        $this->data['taxes'] = Tax::all();
        $this->data['countries'] = Country::all()->pluck( 'name', 'id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['section']  = 'general';
        return view('admin.settings.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'settings.site_name' => 'required'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->data['title']    = ucwords(str_replace('_', ' ', $id)) . ' Settings';
        $this->data['section']  = $id;

        $this->data['logo'] = json_encode(Media::where('id', \setting('app.logo'))->get()->map(function ($media){
            return [
                'id' => $media->id,
                'name' => $media->filename . '.' . $media->extension,
                'image_url' => $media->getUrl('thumbnail'),
                'size' => $media->size
            ];
        }));

        $this->data['favicon'] = json_encode(Media::where('id', \setting('app.favicon'))->get()->map(function ($media){
            return [
                'id' => $media->id,
                'name' => $media->filename . '.' . $media->extension,
                'image_url' => $media->getUrl('thumbnail'),
                'size' => $media->size
            ];
        }));

        return view('admin.settings.index', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        // If general Section
        if ( $request->has('settings.site_name') ) {
            $rules = [
                'settings.site_name' => 'required'
            ];

            $request->validate($rules);
        }


        $settings = array_combine(
            array_keys($request->input('settings')),
            array_values($request->input('settings'))
        );


        if ( isset($settings['tax_rates']) ) {

            $tax_array = [];

            $submitted_ids  = array_map('intval', $request->input('settings.tax_rates.id.*'));
            $saved_ids      = Tax::all()->pluck('id')->toArray();

            $taxes_to_removed = array_diff($saved_ids, $submitted_ids);

            if ( !empty( $taxes_to_removed ) ) {
                try {
                    Tax::whereIn('id', $taxes_to_removed)->delete();
                } catch (\Exception $ex) {
                    Flash::error('Error: '.$ex->getMessage());
                    return redirect()->back();
                }

            }

                for ( $x = 0; $x<count($request->input('settings.tax_rates.country.*')); $x++ ) {

                    if ( empty($request->input('settings.tax_rates.country.'.$x)) ) {
                        continue;
                    }

                    $id = $request->input('settings.tax_rates.id.'.$x);
                    $country_wide = $request->input('settings.tax_rates.country_wide.'.$x);

                    if ( empty($id) ) {

                        try {
                            Tax::updateOrCreate([
                                'country_id' => $request->input('settings.tax_rates.country.'.$x),
                                'state_id' => $request->input('settings.tax_rates.state.'.$x),
                                'country_wide' => $country_wide,
                                'rate' => $request->input('settings.tax_rates.rate.'.$x)
                            ]);
                        } catch (\Exception $ex) {
                            Flash::error('Error: '.$ex->getMessage());
                            return redirect()->back();
                        }

                    } else {

                        try {
                            Tax::where('id', $id)->update([
                                'country_id' => $request->input('settings.tax_rates.country.'.$x),
                                'state_id' => $request->input('settings.tax_rates.state.'.$x),
                                'country_wide' => $country_wide,
                                'rate' => $request->input('settings.tax_rates.rate.'.$x)
                            ]);
                        } catch (\Exception $ex) {
                            Flash::error('Error: '.$ex->getMessage());
                            return redirect()->back();
                        }

                    }

                }

            unset($settings['tax_rates']);
        }

        Artisan::call('view:clear');

        Setting::updateSettings($settings);

        Flash::success('Settings updated');

        if (\request('active_tab')) {
            session()->flash('active_tab', \request('active_tab'));
        }

        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function ajax( Request $request ) {

        if ( $request->input('action') == 'get_states' ) {

            $states = State::where('country_id', $request->input('country_id'))->pluck('name', 'id');

            $states = json_encode($states);

            if ( !empty( $states ) ) {
                return response()->json($states, 200);
            } else {
                return response()->json(['error' => 'No States found.'], 422);
            }

        }

        if ( $request->input('action') == 'cart_get_states' ) {

            $states = State::where('country_id', $request->input('country_id'))->get();

            if ( !empty( $states ) ) {
                return response()->json($states->toArray(), 200);
            } else {
                return response()->json(['error' => 'No States found.'], 422);
            }

        }

    }
}

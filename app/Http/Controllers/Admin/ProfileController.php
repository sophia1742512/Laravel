<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Profiles;

use Carbon\Carbon;
use App\ProfileHistory;

class ProfileController extends Controller
{
    public function add()
    {
        return view('admin.profile.create');
    }
    
    public function create(Request $request)
    {
        $this->validate($request, Profiles::$rules);
        
        $profiles = new Profiles;
        $form = $request->all();
        
        unset($form['_token']);
        
        $profiles->fill($form);
        $profiles->save();
        
        return redirect('admin/profile/create');
    }
    
    public function edit(Request $request)
    {
        $profiles = Profiles::find($request->id);
        if (empty($profiles)) {
            abort(404);
        }
        return view('admin.profile.edit', ['profile_form' => $profiles]);
    }
    
    public function update(Request $request)
    {
        $this->validate($request, Profiles::$rules);
        
        $profiles = Profiles::find($request->id);
        
        $profile_form = $request->all();
        unset($profile_form['_token']);
        
        $profiles->fill($profile_form)->save();
        
        $history = new ProfileHistory;
        $history->profiles_id = $profiles->id;
        $history->edited_at = Carbon::now();
        $history->save();
        
        return redirect('admin/profile/');
    }
    
    public function delete(Request $request)
    {
        $profiles = Profiles::find($request->id);
        $profiles->delete();
        return redirect('admin/profile/');
    }
}

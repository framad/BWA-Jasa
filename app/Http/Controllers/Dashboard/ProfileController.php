<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

//dibawah ini ni package2 atau helper helper yang dasar gitu, jadi baiknya mau dipake atau engga ya tetep panggil aja dulu

use App\Http\Requests\Profile\UpdateDetailUserRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;

//fungsinya untuk untuk nyimpen file di storage laravel, jangan lupa di storage:link
use Illuminate\Support\Facades\Storage;

//untuk query builder
use Illuminate\Support\Facades\DB;

//kali aja dari request yang udah didefinisikan ada yang kelewat jadi mending panggil aja pake
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use File;
use Auth;

use App\Models\User;
use App\Models\DetailUser;
use App\Models\ExperienceUser;

class ProfileController extends Controller
{
    //fungsinya untuk ngecek apakah user udah login atau blm, kalau udah maka user bisa akses fungsi fungsi yang ada dibawah
    //fungsi construct ini
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fungsi ini untuk menampilkan semua data data yang akan ditampilkan di index profile

        //diperlukan data user pada index profile, dibuatlah variable ini, kenapa first() karena datanya cuman 1 (1 id 1 user)
        $user = User::where('id', Auth::user()->id)->first();

        //diperlukan juga data experience user dan exp user berelasi dengan tabel detail user maka dibuatlah eloquent seperti dibawah
        //kenapa get() karena data exp user kan lebih dari satu, yaitu 3, misal cuman satu mah bisa pake first() aja
        $experience_user = ExperienceUser::where('detail_user_id', $user->detail_user->id)->orderBy('id', 'asc')->get();

        //return halaman dan variable yang mau ditampilkan
        return view('pages.Dashboard.profile', compact('user', 'experience_user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //abort ini fungsinya untuk supaya user gabisa akses function ini karena lagian function ini ga kepake juga
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request_profile, UpdateDetailUserRequest $request_detail_user)
    {
        $data_profile = $request_profile->all();
        $data_detail_user = $request_detail_user->all();

        //get poto
        $get_photo = DetailUser::where('users_id', Auth::user()->id)->first();

        //delete old file (poto lama)
        if(isset($data_detail_user['photo'])){
            $data = 'storage/'.$get_photo['photo'];
            if(File::exists($data)){
                File::delete($data);
            }else{
                File::delete('storage/app/public/'.$get_photo['photo']);
            }
        }

        //simpan file baru nya
        if(isset($data_detail_user['photo'])){
            $data_detail_user['photo'] = $request_detail_user->file('photo')->store('assets/photo', 'public');
        }

        //proses save data baru ke user
        $user = User::find(Auth::user()->id); //cari data user nya
        $user->update($data_profile); //simpen datanya ke variable data_profile

        //proses save data baru ke detail user
        $user = DetailUser::find($user->detail_user->id); //cari data detail user nya, id nya
        $user->update($data_detail_user); //simpen datanya ke variable data_profile

        //proses save data baru ke tabel experience
        $experience_user_id = ExperienceUser::where('detail_user_id', $detail_user['id'])->first();
        if (isset($experience_user_id)) {
            foreach ($data_profile['experience'] as $key => $value) {
                $experience_user = ExperienceUser::find($key);
                $experience_user->detail_user_id = $detail_user['id'];
                $experience_user->experience = $value;
                $experience_user->save();
            }
        } else {
            foreach ($data_profile['experience'] as $key => $value) {
                if (isset($value)) {
                    $experience_user = new ExperienceUser;
                    $experience_user->detail_user_id = $detail_user['id'];
                    $experience_user->experience = $value;
                    $experience_user->save();
                }
                
        }
        
    }

    //menggunakan sweetalert untuk memunculkan notifikasi
    toast()->success('Update berhasil bos');
    //supaya setelah update selesai halaman nya tetep disini ga kemana mana
    return back();
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return abort(404);
    }

    //custom
    public function delete(){
        //get user
        $get_user_photo = DetailUser::where('users_id', Auth::user()->id)->first();
        $path_photo = $get_user_photo['photo'];

        //dari user yang udah didapet datanya, potonya dikosongkan atau dibaut null
        $data = DetailUser::find($get_user_photo['id']);
        $data->photo = NULL;
        $data->save();

        //hapus potonya dari storage
        $data = 'storage/'.$path_photo;
        if (File::exists($data)) {
            File::delete($data);
        } else {
            File::delete('storage/app/public/'.$path_photo);
        }

        toast()->success('Hapus poto sukses ah');
        return back();
    }
}
